<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o
 * licencji i autorach.
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
 * $Id: contactbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ContactBoxController extends BoxController {

	public function index () {
		if ((int) $this->registry->core->getParam() > 0){
			$this->product = App::getModel('product')->getProductAndAttributesById((int) $this->registry->core->getParam());
		}
		$contacts = App::getModel('Contact')->getContactToSelect();
		$contactList = App::getModel('Contact')->getContactList();
		
		$form = new FE_Form(Array(
			'name' => 'contactform',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data'
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		if ((int) $this->registry->core->getParam() > 0){
			$requiredData->AddChild(new FE_Hidden(Array(
				'name' => 'topic',
				'default' => $this->registry->core->getMessage('TXT_PRODUCT_QUOTE') . ' ' . $this->product['productname']
			)));
		}
		else{
			$requiredData->AddChild(new FE_TextField(Array(
				'name' => 'topic',
				'label' => $this->registry->core->getMessage('TXT_TOPIC'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOPIC'))
				)
			)));
		}
		
		if (count($contacts) > 0){
			
			$requiredData->AddChild(new FE_Select(Array(
				'name' => 'contactsubject',
				'label' => $this->registry->core->getMessage('TXT_CONTACT'),
				'options' => FE_Option::Make($contacts)
			)));
		
		}
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'rows' => 10,
			'cols' => 100
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'send',
			'label' => $this->registry->core->getMessage('TXT_SEND_YOUR_QUERY')
		)));
		
		foreach ($contactList as $key => $contact){
			$contactData = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'departament_data',
				'label' => $contact['name']
			)));
			
			if ($contact['businesshours'] != ''){
				$contactData->AddChild(new FE_StaticText(Array(
					'text' => "<dl><dt>{$this->registry->core->getMessage('TXT_BUSINESS_HOURS')}</dt><dd>{$contact['businesshours']}</dd></dl>"
				)));
			}
			
			$contactData->AddChild(new FE_StaticText(Array(
				'text' => "<dl><dt>{$this->registry->core->getMessage('TXT_ADDRESS')}</dt><dd>{$contact['street']} {$contact['streetno']} {$contact['placeno']} {$contact['postcode']} {$contact['placename']}</dd></dl>"
			)));
			
			if ($contact['phone'] != ''){
				$contactData->AddChild(new FE_StaticText(Array(
					'text' => "<dl><dt>{$this->registry->core->getMessage('TXT_PHONE')}</dt><dd>{$contact['phone']}</dd></dl>"
				)));
			}
			
			if ($contact['fax'] != ''){
				$contactData->AddChild(new FE_StaticText(Array(
					'text' => "<dl><dt>{$this->registry->core->getMessage('TXT_FAX')}</dt><dd>{$contact['fax']}</dd></dl>"
				)));
			}
			
			if ($contact['email'] != ''){
				$secure = str_replace(Array(
					'@',
					'.'
				), Array(
					' at ',
					' dot '
				), $contact['email']);
				$contactData->AddChild(new FE_StaticText(Array(
					'text' => "<dl><dt>{$this->registry->core->getMessage('TXT_EMAIL')}</dt><dd><span class=\"mailme\">{$secure}</span></dd></dl>"
				)));
			}
		
		}
		$this->clientModel = App::getModel('client');
		$clientData = $this->clientModel->getClient();
		$populateData = Array(
			'required_data' => Array(
				'firstname' => isset($clientData['firstname']) ? $clientData['firstname'] : '',
				'surname' => isset($clientData['surname']) ? $clientData['surname'] : '',
				'phone' => isset($clientData['phone']) ? $clientData['phone'] : '',
				'email' => isset($clientData['email']) ? $clientData['email'] : '',
				'topic' => '',
				'content' => ''
			)
		);
		$form->Populate($populateData);
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$clean = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($clean == true){
				if (isset($formData['contactsubject'])){
					$selectmail = App::getModel('Contact')->getDepartmentMail($formData['contactsubject']);
				}
				else{
					$selectmail = $this->registry->session->getActiveShopEmail();
				}
				if ($selectmail != NULL){
					$this->registry->template->assign('CONTACT_CONTENT', $formData['content']);
					$this->registry->template->assign('firstname', $formData['firstname']);
					$this->registry->template->assign('surname', $formData['surname']);
					$this->registry->template->assign('email', $formData['email']);
					$this->registry->template->assign('phone', $formData['phone']);
					
					$mailer = new Mailer($this->registry);
					$mailer->addAddress($selectmail);
					$mailer->AddBCC($formData['email']);
					$mailer->AddReplyTo($formData['email'], $formData['firstname'] . ' ' . $formData['surname']);
					$mailer->FromName = $formData['firstname'] . ' ' . $formData['surname'];
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->loadContentToBody('contact');
					if ((int) $this->registry->core->getParam() > 0){
						$mailer->setSubject($this->registry->core->getMessage('TXT_PRODUCT_QUOTE') . ' ' . $this->product['productname']);
					}
					else{
						$mailer->setSubject($formData['topic']);
					}
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
					}
					$this->registry->session->setVolatileSendContact(1, false);
				}
			}
		}
		$sendContact = $this->registry->session->getVolatileSendContact();
		if ($sendContact[0] == 1){
			$this->registry->template->assign('sendContact', 1);
		}
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxHeading () {
		if (isset($this->product) && (int) $this->registry->core->getParam() > 0){
			return $this->registry->core->getMessage('TXT_PRODUCT_QUOTE') . ' ' . $this->product['productname'];
		}
		else{
			return $this->_heading;
		}
	}
}