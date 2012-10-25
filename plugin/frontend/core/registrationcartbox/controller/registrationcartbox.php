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
 * $Revision: 691 $
 * $Author: gekosale $
 * $Date: 2012-09-06 16:11:23 +0200 (Cz, 06 wrz 2012) $
 * $Id: registrationcartbox.php 691 2012-09-06 14:11:23Z gekosale $
 */

class RegistrationCartBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('client');
		if ($this->layer['catalogmode'] == 1){
			App::redirect('');
		}
	}

	public function index ()
	{
		if (strlen($this->registry->core->getParam()) > 10){
			$checkClient = $this->model->checkClientLink($this->registry->core->getParam());
			if (count($checkClient > 0)){
				$result = App::getModel('clientlogin')->authProccess($checkClient['email'], $checkClient['password']);
				if ($result != 0){
					$this->registry->session->setActiveClientid($result);
					App::getModel('clientlogin')->checkClientGroup();
					$this->model->saveClientData();
				}
				if (($this->Cart = $this->registry->session->getActiveCart()) != NULL){
					App::redirect('cart');
				}
				else{
					App::redirect('mainside');
				}
			}
		}
		if ($this->layer['faceboookappid'] != '' && $this->layer['faceboooksecret'] != ''){
			$user = App::getModel('social/fb')->checkUser();
			if ($user['facebookid'] == NULL){
				$this->registry->template->assign('facebooklogin', $user['url']);
			}
			else{
				if ($this->registry->session->getActiveClientid() == NULL){
					$result = App::getModel('clientlogin')->facebookAuthProccess($user['facebookid']);
					if ($result > 0){
						App::redirect('clientlogin');
					}
					else{
						$profile = App::getModel('social/fb')->getUserProfile();
						$request['registration']['email'] = $profile['email'];
						$request['user_id'] = $profile['id'];
						$result = $this->model->bindClientAccount($request);
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
						else{
							$this->registry->session->setVolatileRecureMail(1, false);
						}
						$this->registry->template->assign('facebookRegister', 1);
					}
				}
				else{
					$this->registry->template->assign('facebookRegister', 1);
				}
			}
		}
		
		if (isset($_POST['signed_request']) && $this->layer['faceboookappid'] != '' && $this->layer['faceboooksecret'] != ''){
			$request = App::getModel('social/fb')->getParsedSignedRequest();
			
			if (isset($request['registration']) && ! empty($request['registration'])){
				$profile = App::getModel('social/fb')->getUserProfile();
				$formData = Array(
					'firstname' => $profile['first_name'],
					'surname' => $profile['last_name'],
					'email' => $request['registration']['email'],
					'phone' => $request['registration']['phone'],
					'facebookid' => $request['user_id'],
					'password' => Core::passwordGenerate()
				);
				$recurMail = $this->model->checkClientNewMail($formData);
				if ($recurMail == 0){
					$this->model->addNewClient($formData);
					$this->registry->template->assign('address', $formData);
					$this->registry->template->assign('password', $password);
					
					$mailer = new Mailer($this->registry);
					$mailer->loadContentToBody('addClient');
					$mailer->addAddress($formData['email']);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->setSubject($this->registry->core->getMessage('TXT_REGISTRATION_NEW'));
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
					
					}
					$this->registry->session->setVolatileRegistrationOk(1, false);
					$result = App::getModel('clientlogin')->authProccess(sha1($formData['email']), sha1($formData['password']));
					if ($result != 0){
						$this->registry->session->setActiveClientid($result);
						App::getModel('clientlogin')->checkClientGroup();
						$this->model->saveClientData();
					}
					if (($this->Cart = $this->registry->session->getActiveCart()) != NULL){
						App::redirect('cart');
					}
					else{
						App::redirect('mainside');
					}
				}
				else{
					$result = $this->model->bindClientAccount($request);
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
					else{
						$this->registry->session->setVolatileRecureMail(1, false);
					}
				}
			}
		}
		
		$form = new FE_Form(Array(
			'name' => 'clientRegistration',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_REGISTER_IN_SHOP') . ' ' . $this->registry->session->getActiveShopName()
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_REGISTER_INSTRUCTION') . '</p>'
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
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PHONE'))
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
		
		$newPassword = $requiredData->AddChild(new FE_Password(Array(
			'name' => 'password',
			'label' => $this->registry->core->getMessage('TXT_PASSWORD'),
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
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmterms',
			'label' => $this->registry->core->getMessage('TXT_ACCERT_TERMS_AND_POLICY_OF_PRIVATE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_TERMS_NOT_AGREED'))
			),
			'default' => 0
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p class="indent">' . $this->registry->core->getMessage('TXT_ACCEPT_PRIVACY_DESCRIPTION') . '</p>'
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'newsletter',
			'label' => $this->registry->core->getMessage('TXT_NEWSLETTER'),
			'default' => 0
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p class="indent">' . $this->registry->core->getMessage('TXT_NEWSLETTER_INFO_FRONTEND') . '</p>'
		)));
		
		$requiredData->AddChild(new FE_Submit(Array(
			'name' => 'register',
			'label' => $this->registry->core->getMessage('TXT_REGISTER')
		)));
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			$clean = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($clean == true){
				$recurMail = $this->model->checkClientNewMail($formData);
				if ($recurMail == 0){
					$clientId = $this->model->addNewClient($formData);
					if (isset($this->layer['confirmregistration']) && $this->layer['confirmregistration'] == 1){
						$link = $this->model->updateClientDisable($clientId, 1, sha1($formData['email'] . time()));
						$this->registry->template->assign('activelink', $link);
					}
					$this->registry->template->assign('address', $formData);
					$mailer = new Mailer($this->registry);
					$mailer->loadContentToBody('addClient');
					$mailer->addAddress($formData['email']);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->setSubject($this->registry->core->getMessage('TXT_REGISTRATION_NEW'));
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
						mail($this->registry->session->getActiveShopEmail(), 'Mailer exception', $e->getMessage());
					}
					
					if (isset($this->layer['confirmregistration']) && $this->layer['confirmregistration'] == 1){
						$this->registry->session->setVolatileActivationRequired(1, false);
					}
					else{
						$this->registry->session->setVolatileRegistrationOk(1, false);
						$result = App::getModel('clientlogin')->authProccess(sha1($formData['email']), sha1($formData['password']));
						if ($result != 0){
							$this->registry->session->setActiveClientid($result);
							App::getModel('clientlogin')->checkClientGroup();
							$this->model->saveClientData();
						}
						if (($this->Cart = $this->registry->session->getActiveCart()) != NULL){
							App::redirect('cart');
						}
						else{
							App::redirect('mainside');
						}
					}
				}
				else{
					$this->registry->session->setVolatileRecureMail(1, false);
				}
			}
			else{
				$this->registry->session->setVolatileForbiddenCode(1, false);
			}
		}
		
		$this->registry->template->assign('form', $form);
		
		$registrationok = $this->registry->session->getVolatileRegistrationOk();
		if ($registrationok[0] == 1){
			$this->registry->template->assign('registrationok', 'TXT_REGISTER_USER_OK');
		}
		
		$activationrequired = $this->registry->session->getVolatileActivationRequired();
		if ($activationrequired[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('TXT_ACTIVATION_REQUIRED'));
		}
		
		$recureMailError = $this->registry->session->getVolatileRecureMail();
		if ($recureMailError[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('ERR_DUPLICATE_EMAIL'));
		}
		
		$forbiddenCode = $this->registry->session->getVolatileForbiddenCode();
		if ($forbiddenCode[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('TXT_ERROR_FORBIDDEN_CODE'));
		}
		
		$passwdGenError = $this->registry->session->getVolatilePasswordGenerateError();
		if ($passwdGenError[0] == 1){
			$this->registry->template->assign('error', $this->registry->core->getMessage('ERROR_PASSWORD_GENERATE'));
		}
		
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

}