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
 * $Id: recommendfriendbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class RecommendFriendBoxController extends BoxController
{

	public function index ()
	{
		$form = new FE_Form(Array(
			'name' => 'recommendform',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data'
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'fromname',
			'label' => $this->registry->core->getMessage('TXT_YOUR_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'fromemail',
			'label' => $this->registry->core->getMessage('TXT_YOUR_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'friendemail',
			'label' => $this->registry->core->getMessage('TXT_FRIEND_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FRIEND_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'rows' => 10,
			'cols' => 72,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTENT'))
			)
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'send',
			'label' => $this->registry->core->getMessage('TXT_SEND')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$this->registry->template->assign('recommendform', $form);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
		if ($form->Validate(FE::SubmittedData())){
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$clean = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($clean == true){
				$getURL = App::getModel('recommendfriendbox')->getPageURL();
				$clientModel = App::getModel('client');
				$clientdata = $clientModel->getClient();
				$this->registry->template->assign('recommendurl', $getURL);
				$this->registry->template->assign('fromname', $formData['fromname']);
				$this->registry->template->assign('fromemail', $formData['fromemail']);
				$this->registry->template->assign('comment', $formData['content']);
				
				$mailer = new Mailer($this->registry);
				$mailer->loadContentToBody('recommendfriend');
				$mailer->addAddress($formData['friendemail']);
				$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
				$mailer->setSubject($this->registry->core->getMessage('TXT_RECOMMENDATION').' '.$this->registry->session->getActiveShopName());
				try{
					$mailer->Send();
				}
				catch (phpmailerException $e){
				
				}
			}
		}
	}
}