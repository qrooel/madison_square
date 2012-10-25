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
 * $Id: forgotpasswordbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ForgotPasswordBoxController extends BoxController
{

	public function index ()
	{

		$form = new FE_Form(Array(
			'name' => 'forgotpassword',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'class' => 'clear-border'
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'send',
			'label' => $this->registry->core->getMessage('TXT_REMIND')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$clean = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($clean == true){
				$result = App::getModel('forgotpassword')->authProccess(sha1($formData['email']));
				if ($result > 0){
					$password = Core::passwordGenerate();
					$this->registry->template->assign('password', $password);
					
					$mailer = new Mailer($this->registry);
					$mailer->loadContentToBody('forgotPassword');
					$mailer->addAddress($formData['email']);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->setSubject($this->registry->core->getMessage('TXT_PASSWORD_FORGOT'));
					$mailer->Send();
					
					App::getModel('forgotpassword')->forgotPassword(sha1($formData['email']), sha1($password));
					$this->registry->session->setVolatileSendPassword(1, false);
					$this->registry->session->setVolatileForgotPasswordError();
				}
				elseif ($result < 0){
					$this->registry->session->setVolatileForgotPasswordError(2, false);
				}
				else{
					$this->registry->session->setVolatileSendPassword();
					$this->registry->session->setVolatileForgotPasswordError(1, false);
				}
			}
		}
		
		$error = $this->registry->session->getVolatileForgotPasswordError();
		if ($error[0] == 1){
			$this->registry->template->assign('emailerror', $this->registry->core->getMessage('ERR_EMAIL_NO_EXIST'));
		}
		elseif ($error[0] == 2){
			$this->registry->template->assign('emailerror', $this->registry->core->getMessage('TXT_BLOKED_USER'));
		}
		$sendPasswd = $this->registry->session->getVolatileSendPassword();
		if ($sendPasswd[0] == 1){
			$this->registry->template->assign('sendPasswd', $this->registry->core->getMessage('TXT_CHECK_PRIVATE_MAIL_WITH_NEW_PASSWD'));
		}
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}