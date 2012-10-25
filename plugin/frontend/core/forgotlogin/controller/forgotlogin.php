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
 * $Id: forgotlogin.php 655 2012-04-24 08:51:44Z gekosale $
 */

class forgotloginController extends Controller
{

	public function index ()
	{
		$this->disableLayout();
		if ($this->registry->session->getActiveUserid() != null){
			App::redirect(__ADMINPANE__ . '/mainside');
		}
		
		$form = new FE_Form(Array(
			'name' => 'forgotlogin',
			'action' => '',
			'method' => 'post',
			'class' => 'login-form'
		));
		
		$form->AddChild(new FE_TextField(Array(
			'name' => 'login',
			'label' => $this->registry->core->getMessage('TXT_EMAIL_FORM_LOGIN'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_LOGIN_FORM_LOGIN'))
			)
		)));
		
		$form->AddChild(new FE_Submit(Array(
			'name' => 'log_in',
			'label' => $this->registry->core->getMessage('TXT_FORGOT_PASSWORD')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$loginValues = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$result = App::getModel('loginModel')->checkUsers(sha1($loginValues['login']));
			if ($result == 0){
				$this->registry->session->flush();
				$this->registry->session->setVolatileLoginError(1, false);
			}
			else{
				$password = Core::passwordGenerate();
				App::getModel('login')->changeUsersPassword($result, $password);
				$this->registry->template->assign('password', $password);
				
				$mailer = new Mailer($this->registry);
				$mailer->loadContentToBody('forgotUsers');
				$mailer->addAddress($_POST['login']);
				$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
				$mailer->IsHTML(true);
				$mailer->setSubject($this->registry->core->getMessage('TXT_FORGOT_PASSWORD'));
				try{
					$mailer->Send();
				}
				catch (phpmailerException $e){
				
				}
				App::redirect('login');
			}
		}
		
		$error = $this->registry->session->getVolatileLoginError();
		if ($error[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('ERR_BAD_EMAIL'));
		}
		
		$languages = App::getModel('language')->getLanguages();
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('languages', json_encode($languages));
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}