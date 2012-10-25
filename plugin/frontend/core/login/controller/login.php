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
 * $Id: login.php 655 2012-04-24 08:51:44Z gekosale $
 */

class LoginController extends Controller
{

	public function index ()
	{
		
		if ($this->registry->session->getActiveUserid() != null){
			
			App::redirect(__ADMINPANE__ . '/mainside');
		}
		
		$check = $this->registry->session->getActiveAdminLogin();
		if ($this->registry->session->getActiveAdminLogin() == NULL){
			App::redirect('');
		}
		
		$this->disableLayout();
		
		$form = new FE_Form(Array(
			'name' => 'login',
			'action' => '',
			'method' => 'post',
			'class' => 'login-form'
		));
		
		$form->AddChild(new FE_TextField(Array(
			'name' => 'login',
			'label' => $this->registry->core->getMessage('TXT_EMAIL_FORM_LOGIN'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL_FORM_LOGIN'))
			)
		)));
		
		$form->AddChild(new FE_Password(Array(
			'name' => 'password',
			'label' => $this->registry->core->getMessage('TXT_LOGIN_FORM_PASSWORD'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_LOGIN_FORM_PASSWORD'))
			)
		)));
		
		$form->AddChild(new FE_Submit(Array(
			'name' => 'log_in',
			'label' => $this->registry->core->getMessage('TXT_LOG_IN')
		)));
		
		$form->AddChild(new FE_StaticText(Array(
			'text' => '<a href="/forgotlogin">' . $this->registry->core->getMessage('TXT_LOGIN_FORM_RESET_PASSWORD') . '</a>'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$loginValues = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$result = App::getModel('loginModel')->authProccess(sha1($loginValues['login']), sha1($loginValues['password']));
			if ($result == 0){
				$this->registry->session->flush();
				$this->registry->session->setVolatileLoginError(1, false);
			}
			else{
				$this->registry->session->setActiveLoginError(null);
				$this->registry->session->setActiveUserid($result);
				App::getModel('login')->setLoginTime();
				App::getModel('login')->getUserData();
				App::getModel('login')->setDefaultView($result);
				App::redirect(__ADMINPANE__ . '/mainside');
			}
		}
		
		$error = $this->registry->session->getVolatileLoginError();
		if ($error[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('ERR_BAD_LOGIN_OR_PASSWORD'));
		}
		
		$languages = App::getModel('language')->getLanguages();
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('languages', json_encode($languages));
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}
?>