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
 * $Id: clientsettingsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ClientSettingsBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('client');
	
	}

	public function index ()
	{
		if ($this->registry->session->getActiveClientid() == NULL){
			App::redirect('mainside');
		}
		$this->registry->xajax->registerFunction(array(
			'sendAlert',
			$this->model,
			'sendAJAXAlertAfterChangingMail'
		));
		
		$formPass = new FE_Form(Array(
			'name' => 'changePassword',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $formPass->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_CHANGE_PASSWORD')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_ATTENTION') . '</strong>: ' . $this->registry->core->getMessage('TXT_ERROR_CHANGE_SETTINGS') . '</p>'
		)));
		
		$oldPassword = $requiredData->AddChild(new FE_Password(Array(
			'name' => 'password',
			'label' => $this->registry->core->getMessage('TXT_PASSWORD'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PASSWORD')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_PASSWORD_NEW_INVALID'), '/^(.{6,})*$/')
			)
		)));
		
		$newPassword = $requiredData->AddChild(new FE_Password(Array(
			'name' => 'newpassword',
			'label' => $this->registry->core->getMessage('TXT_PASSWORD_NEW'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PASSWORD')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_PASSWORD_NEW_INVALID'), '/^(.{6,})*$/')
			)
		)));
		
		$requiredData->AddChild(new FE_Password(Array(
			'name' => 'confirmpassword',
			'label' => $this->registry->core->getMessage('TXT_PASSWORD_REPEAT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONFIRM_PASSWORD')),
				new FE_RuleCompare($this->registry->core->getMessage('ERR_PASSWORDS_NOT_COMPATIBILE'), $newPassword)
			)
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'register',
			'label' => $this->registry->core->getMessage('TXT_CHANGE')
		)));
		$formPass->AddFilter(new FE_FilterTrim());
		$formPass->AddFilter(new FE_FilterNoCode());
		
		if ($formPass->Validate(FE::SubmittedData())){
			$formData = $formPass->getSubmitValues(FE_Form::FORMAT_FLAT);
			$BaseTable = $this->model->getClientPass();
			$PostValidatePass = $formData['password'];
			$checkpass = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($checkpass == true){
				if (sha1($PostValidatePass) === $BaseTable['password']){
					$this->model->updateClientPass($formData['newpassword']);
					$email = $this->registry->session->getActiveClientEmail();
					$this->registry->template->assign('PASS_NEW', $formData['newpassword']);
					
					$mailer = new Mailer($this->registry);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->loadContentToBody('editPassword');
					$mailer->FromName = $this->registry->session->getActiveShopName();
					$mailer->addAddress($email);
					$mailer->setSubject($this->registry->core->getMessage('TXT_PASSWORD_EDIT'));
					
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
					
					}
					$this->registry->session->setVolatileChangePassOk(1, false);
					App::redirect('clientsettings');
				
				}
				else{
					$this->registry->session->setVolatileOldPassError(1, false);
				}
			}
			else{
				$this->registry->session->setVolatileForbiddenCodePass(1, false);
			}
		}
		
		$this->registry->template->assign('formPass', $formPass);
		
		$forbiddencodepass = $this->registry->session->getVolatileForbiddenCodePass();
		if ($forbiddencodepass[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('TXT_ERROR_FORBIDDEN_CODE'));
		}
		
		$erroroldpass = $this->registry->session->getVolatileOldPassError();
		if ($erroroldpass[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('TXT_ERROR_OLD_PASSWORD'));
		}
		
		$changepassok = $this->registry->session->getVolatileChangePassOk();
		if ($changepassok[0] == 1){
			$this->registry->template->assign('success', $this->registry->core->getMessage('TXT_DATA_CHANGED_MAIL_SEND'));
		
		}
		
		$formUserEmail = new FE_Form(Array(
			'name' => 'changeEmail',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $formUserEmail->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_CHANGE_EMAIL')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_ATTENTION') . '</strong>: ' . $this->registry->core->getMessage('TXT_CHECK_DATA') . '</p>'
		)));
		
		$newEmail = $requiredData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'emailnew',
			'label' => $this->registry->core->getMessage('TXT_CONFIMR_EMAIL_NEW'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL')),
				new FE_RuleCompare($this->registry->core->getMessage('ERR_COMPARE'), $newEmail)
			)
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'submitmail',
			'label' => $this->registry->core->getMessage('TXT_CHANGE_EMAIL')
		)));
		
		if ($formUserEmail->Validate(FE::SubmittedData())){
			$formData = $formUserEmail->getSubmitValues(FE_Form::FORMAT_FLAT);
			$checkMailForm = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($checkMailForm == true){
				$result = $this->model->checkClientNewMail($formData);
				if ($result == 0){
					$changedMail = $this->model->updateClientEmail($formData);
					$changedLogin = $this->model->updateClientLogin($formData['emailnew']);
					$this->registry->template->assign('EMAIL_NEW', $formData['emailnew']);
					
					$mailer = new Mailer($this->registry);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->loadContentToBody('editMail');
					$mailer->FromName = $this->registry->session->getActiveShopName();
					$mailer->addAddress($formData['emailnew']);
					$mailer->setSubject($this->registry->core->getMessage('TXT_EMAIL_EDIT'));
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
					
					}
					$this->registry->session->setVolatileUserChangeData(1, false);
					App::redirect('clientsettings');
				}
				else{
					$this->registry->session->setVolatileUserEmailDuplicateError(1, false);
				}
			}
			else{
				$this->registry->session->setVolatileForbiddenCodeMailForm(1, false);
			}
		}
		
		$forbiddencodemailform = $this->registry->session->getVolatileForbiddenCodeMailForm();
		if ($forbiddencodemailform[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('TXT_ERROR_FORBIDDEN_CODE'));
		}
		
		$changeData = $this->registry->session->getVolatileUserChangeData();
		if ($changeData[0] == 1){
			$this->registry->template->assign('clientChangedMail', $this->registry->core->getMessage('TXT_LOGGOUT_CHANGED_EMAIL'));
		
		}
		$errorMail = $this->registry->session->getVolatileUserEmailDuplicateError();
		if ($errorMail[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('ERR_DUPLICATE_EMAIL'));
		
		}
		$this->registry->template->assign('formEmail', $formUserEmail);
		
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}