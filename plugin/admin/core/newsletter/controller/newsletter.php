<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version. 
 * 
 * 
 * $Revision: 655 $
 * $Author: gekosale $
 * $Date: 2012-04-24 10:51:44 +0200 (Wt, 24 kwi 2012) $
 * $Id: newsletter.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class newsletterController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteNewsletter',
			App::getModel('newsletter'),
			'doAJAXDeleteNewsletter'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllNewsletter',
			App::getModel('newsletter'),
			'getNewsletterForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('newsletter')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_newsletter',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'newsletter', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_SENDER'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SENDER')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'subject',
			'label' => $this->registry->core->getMessage('TXT_TOPIC'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOPIC'))
			)
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'htmlform',
			'label' => $this->registry->core->getMessage('TXT_HTML'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'rows' => 50
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'textform',
			'label' => $this->registry->core->getMessage('TXT_TEXT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEXT'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'rows' => 50
		)));
		
		$recipientData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'recipient_data',
			'label' => $this->registry->core->getMessage('TXT_RECIPIENT')
		)));
		
		$recipientData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'recipient',
			'label' => $this->registry->core->getMessage('TXT_RECIPIENT_LIST'),
			'key' => 'idrecipientlist',
			'datagrid_init_function' => Array(
				App::getModel('recipientlist'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getRecipientListDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('newsletter')->addNewNewsletterHistory($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/newsletter/add');
			}
			
			elseif (FE::IsAction('send')){
				$newsletter = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				if (is_array($newsletter['recipient'])){
					
					$clients = $newsletter['recipient'];
					$clientModel = App::getModel('client');
					
					$clientid = $clientModel->selectClient($clients);
					$clientsData = $clientModel->selectClientsFromNewsletter($clientid);
					
					$clientnewsletterid = $clientModel->selectClientNewsletter($clients);
					$clientsNewsletterData = $clientModel->selectClientsNewsletterFromNewsletter($clientnewsletterid);
					
					$clientgroupid = $clientModel->selectClientGroup($clients);
					$clientsGroupData = $clientModel->selectClientsGroupFromNewsletter($clientgroupid);
					
					$sum = array_unique(array_merge($clientsData, $clientsNewsletterData, $clientsGroupData));
					$qty = count($sum);
					$newsletter['htmlform'] = $this->registry->template->fetch('text:'.stripcslashes($newsletter['htmlform']));
					$this->registry->template->assign('newsletter', $newsletter);
					
					$mailer = new Mailer($this->registry);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->loadContentToBody('newsletter.tpl');
					$filename = ROOTPATH . '/design/_tpl/mailerTemplates/newsletterclear.tpl';
					$file = @fopen($filename, 'w+');
					fwrite($file, $newsletter['textform']);
					fclose($file);
					$mailer->AddAttachment(ROOTPATH . '/design/_tpl/mailerTemplates/newsletterclear.tpl', 'Text', 'base64', 'text/html');
					for ($r = 0; $r <= $qty - 1; $r ++){
						$mailer->addAddress($sum[$r]);
						$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
						$mailer->setSubject($newsletter['subject']);
						$mailer->FromName = $newsletter['email'];
						try{
							$mailer->Send();
						}
						catch (phpmailerException $e){
						
						}
						$mailer->ClearAddresses();
						unset($sum[$r]);
					}
				}
				App::redirect(__ADMINPANE__ . '/newsletter');
			}
			else{
				App::redirect(__ADMINPANE__ . '/newsletter');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		$form = new FE_Form(Array(
			'name' => 'edit_newsletter',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'newsletter', 'name', null, Array(
					'column' => 'idnewsletter',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_SENDER'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'subject',
			'label' => $this->registry->core->getMessage('TXT_TOPIC'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOPIC'))
			)
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'htmlform',
			'label' => $this->registry->core->getMessage('TXT_HTML'),
			'rows' => 50
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'textform',
			'label' => $this->registry->core->getMessage('TXT_TEXT'),
			'rows' => 50
		)));
		
		$recipientData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'recipient_data',
			'label' => $this->registry->core->getMessage('TXT_RECIPIENT')
		)));
		
		$recipientData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'recipient',
			'label' => $this->registry->core->getMessage('TXT_RECIPIENT_LIST'),
			'key' => 'idrecipientlist',
			'datagrid_init_function' => Array(
				App::getModel('recipientlist'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getRecipientListDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawNewsletterData = App::getModel('newsletter')->getNewsletterData($this->registry->core->getParam());
		$newsletterData = Array(
			'required_data' => Array(
				'name' => $rawNewsletterData['name'],
				'email' => $rawNewsletterData['email'],
				'subject' => $rawNewsletterData['subject'],
				'textform' => $rawNewsletterData['textform'],
				'htmlform' => $rawNewsletterData['htmlform']
			)
		);
		
		$form->Populate($newsletterData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('newsletter')->updateNewsletter($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
				if (FE::IsAction('send')){
					$newsletter = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
					if (is_array($newsletter['recipient'])){
						$mailer = new Mailer($this->registry);
						$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
						
						$clients = $newsletter['recipient'];
						$clientModel = App::getModel('client');
						
						$clientid = $clientModel->selectClient($clients);
						$clientsData = $clientModel->selectClientsFromNewsletter($clientid);
						
						$clientnewsletterid = $clientModel->selectClientNewsletter($clients);
						$clientsNewsletterData = $clientModel->selectClientsNewsletterFromNewsletter($clientnewsletterid);
						
						$clientgroupid = $clientModel->selectClientGroup($clients);
						$clientsGroupData = $clientModel->selectClientsGroupFromNewsletter($clientgroupid);
						
						$sum = array_unique(array_merge($clientsData, $clientsNewsletterData, $clientsGroupData));
						$qty = count($sum);
						$newsletter['htmlform'] = $this->registry->template->fetch('text:'.stripcslashes($newsletter['htmlform']));
						$this->registry->template->assign('newsletter', $newsletter);
						$mailer->loadTemplateToBody('newsletter.tpl');
						$filename = ROOTPATH . '/design/_tpl/mailerTemplates/newsletterclear.tpl';
						$file = @fopen($filename, 'w+');
						fwrite($file, $newsletter['textform']);
						fclose($file);
						$mailer->AddAttachment(ROOTPATH . '/design/_tpl/mailerTemplates/newsletterclear.tpl', 'Text', 'base64', 'text/html');
						for ($r = 0; $r <= $qty - 1; $r ++){
							$mailer->addAddress($sum[$r]);
							$mailer->setSubject($newsletter['subject']);
							$mailer->FromName = $newsletter['email'];
							try{
								$mailer->Send();
							}
							catch (phpmailerException $e){
							
							}
							$mailer->ClearAddresses();
							unset($sum[$r]);
						}
					}
				}
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/newsletter');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	protected function getRecipientListDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idrecipientlist',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_BETWEEN
				)
			),
			Array(
				'id' => 'name',
				'caption' => $this->registry->core->getMessage('TXT_NAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_INPUT
				)
			),
			Array(
				'id' => 'adddate',
				'caption' => $this->registry->core->getMessage('TXT_DATE'),
				'appearance' => Array(
					'width' => 150
				)
			)
		);
	}

	protected function getOrderStatusDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idorderstatus',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				)
			),
			Array(
				'id' => 'name',
				'caption' => $this->registry->core->getMessage('TXT_NAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			)
		);
	}
}