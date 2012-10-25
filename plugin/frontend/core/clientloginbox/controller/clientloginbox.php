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
 * $Id: clientloginbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ClientLoginBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('client');
		if ($this->layer['catalogmode'] == 1){
			if ($this->registry->session->getActiveForceLogin() == 0){
				App::redirect('');
			}
		}
	}

	public function index ()
	{
		
		if ($this->layer['faceboookappid'] != '' && $this->layer['faceboooksecret'] != ''){
			$user = App::getModel('social/fb')->checkUser();
			if ($user['facebookid'] == NULL){
				$this->registry->template->assign('facebooklogin', $user['url']);
			}
			else{
				if ($this->registry->session->getActiveClientid() == NULL){
					$result = App::getModel('clientlogin')->facebookAuthProccess($user['facebookid']);
					if ($result > 0){
						$this->registry->session->setActiveClientid($result);
						App::getModel('clientlogin')->checkClientGroup();
						$this->model->saveClientData();
						$misingCart = App::getModel('missingcart')->checkMissingCartForClient($this->registry->session->getActiveClientid());
						if (is_array($misingCart) && $misingCart != 0){
							App::getModel('cart')->addProductsToCartFromMissingCart($misingCart);
							App::getModel('missingcart')->cleanMissingCart($this->registry->session->getActiveClientid());
						}
						if (($this->Cart = $this->registry->session->getActiveCart()) != NULL){
							App::redirect('cart');
						}
						else{
							App::redirect('mainside');
						}
					}else{
						App::redirect($this->registry->core->getControllerNameForSeo('registrationcart'));
					}
				}
			}
		}

		$form = new FE_Form(Array(
			'name' => 'login',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'class' => 'clear-border'
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'login',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL'))
			)
		)));
		
		$newPassword = $requiredData->AddChild(new FE_Password(Array(
			'name' => 'password',
			'label' => $this->registry->core->getMessage('TXT_PASSWORD'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PASSWORD'))
			)
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'submit',
			'label' => $this->registry->core->getMessage('TXT_LOGIN')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<a href="' . App::getURLAdress() . App::getRegistry()->core->getControllerNameForSeo('forgotpassword') . '" style="margin-left:10px"><span>' . $this->registry->core->getMessage('TXT_FORGOT_PASSWORD') . '</span></a>'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$clean = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($clean == true){
				$result = App::getModel('clientlogin')->authProccess(sha1($formData['login']), sha1($formData['password']));
				if ($result > 0){
					$this->registry->session->setActiveClientid($result);
					App::getModel('clientlogin')->checkClientGroup();
					$this->model->saveClientData();
					$misingCart = App::getModel('missingcart')->checkMissingCartForClient($this->registry->session->getActiveClientid());
					if (is_array($misingCart) && $misingCart != 0){
						App::getModel('cart')->addProductsToCartFromMissingCart($misingCart);
						App::getModel('missingcart')->cleanMissingCart($this->registry->session->getActiveClientid());
					}
					if (($this->Cart = $this->registry->session->getActiveCart()) != NULL){
						App::redirect('cart');
					}
					else{
						App::redirect('mainside');
					}
				}
				elseif ($result < 0){
					$this->registry->session->setVolatileUserLoginError(2, false);
				}
				else{
					$this->registry->session->setVolatileUserLoginError(1, false);
				}
			}
		}
		
		$error = $this->registry->session->getVolatileUserLoginError();
		if ($error[0] == 1){
			$this->registry->template->assign('loginerror', $this->registry->core->getMessage('ERR_BAD_LOGIN_OR_PASSWORD'));
		}
		elseif ($error[0] == 2){
			$this->registry->template->assign('loginerror', $this->registry->core->getMessage('TXT_BLOKED_USER'));
		}
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}
?>
