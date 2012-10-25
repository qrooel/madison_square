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
 * $Id: users.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class UsersController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteUser',
			App::getModel('users'),
			'doAJAXDeleteUser'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllUser',
			App::getModel('users'),
			'getUsersForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetFirstnameSuggestions',
			App::getModel('users'),
			'getFirstnameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetSurnameSuggestions',
			App::getModel('users'),
			'getSurnameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetEmailSuggestions',
			App::getModel('users'),
			'getEmailForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'disableUser',
			APP::getModel('users'),
			'doAJAXDisableUser'
		));
		$this->registry->xajax->registerFunction(array(
			'enableUser',
			APP::getModel('users'),
			'doAJAXEnableUser'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('users')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$this->registry->template->assign('groups', App::getModel('groups')->getGroupsAll());
		$groups = App::getModel('groups/groups')->getGroupsAllToSelect();
		
		$form = new FE_Form(Array(
			'name' => 'add_user',
			'action' => '',
			'method' => 'post'
		));
		
		$personalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'personal_data',
			'label' => $this->registry->core->getMessage('TXT_PERSONAL_DATA')
		)));
		
		$firstname = $personalData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_EMAIL_ALREADY_EXISTS'), 'userdata', 'email')
			)
		)));
		
		$rightsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'rights_data',
			'label' => $this->registry->core->getMessage('TXT_RIGHTS')
		)));
		
		$rightsData->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_SET_USER_LAYER_RIGHTS') . '</p>'
		)));
		
		$isglobal = App::getModel('users')->checkActiveUserIsGlobal();
		
		if ($isglobal == 1){
			
			$global = $rightsData->AddChild(new FE_Checkbox(Array(
				'name' => 'global',
				'label' => $this->registry->core->getMessage('TXT_GLOBAL_USER')
			)));
			
			$rightsData->AddChild(new FE_Select(Array(
				'name' => 'group',
				'label' => $this->registry->core->getMessage('TXT_GROUPS'),
				'options' => FE_Option::Make(App::getModel('groups/groups')->getGroupsAllToSelect()),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP'))
				),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $global, new FE_ConditionNot(new FE_ConditionEquals('1')))
				)
			)));
			
			$layers = App::getModel('users')->getLayersAll();
			
			foreach ($layers as $key => $store){
				$storeRightsData[$store['id']] = $rightsData->AddChild(new FE_Fieldset(Array(
					'name' => 'store_' . $store['id'],
					'label' => $this->registry->core->getMessage('TXT_RIGHTS') . ' dla ' . $store['name'],
					'dependencies' => Array(
						new FE_Dependency(FE_Dependency::SHOW, $global, new FE_ConditionNot(new FE_ConditionEquals('1')))
					)
				)));
				
				foreach ($store['views'] as $v => $view){
					
					$storeRightsData[$store['id']]->AddChild(new FE_Select(Array(
						'name' => 'view_' . $view['id'],
						'label' => $view['name'],
						'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('groups/groups')->getGroupsAllToSelect())
					)));
				
				}
			}
		}
		else{
			
			$layers = App::getModel('users')->getLayersAll();
			
			foreach ($layers as $key => $store){
				$storeRightsData[$store['id']] = $rightsData->AddChild(new FE_Fieldset(Array(
					'name' => 'store_' . $store['id'],
					'label' => $this->registry->core->getMessage('TXT_RIGHTS') . ' dla ' . $store['name']
				)));
				
				foreach ($store['views'] as $v => $view){
					
					$storeRightsData[$store['id']]->AddChild(new FE_Select(Array(
						'name' => 'view_' . $view['id'],
						'label' => $view['name'],
						'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('groups/groups')->getGroupsAllToSelect($view['id']))
					)));
				
				}
			}
		}
		
		$additionalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'additional_data',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_DATA')
		)));
		
		$additionalData->AddChild(new FE_Textarea(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 3000',
			'max_length' => 3000
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			
			$password = Core::passwordGenerate();
			$users = $form->getSubmitValues();
			App::getModel('users')->addNewUser($users, $password);
			
			$mailer = new Mailer($this->registry);
			$mailer->addAddress($users['personal_data']['email']);
			$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
			$this->registry->template->assign('password', $password);
			$this->registry->template->assign('users', $form->getSubmitValues(FE_Form::FORMAT_FLAT));
			$mailer->loadContentToBody('newPasswordForUser');
			$mailer->setSubject($this->registry->core->getMessage('TXT_NEW_USER'));
			try{
				$mailer->Send();
			}
			catch (phpmailerException $e){
			
			}
			
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/users/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/users');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$layers = App::getModel('users')->getLayersAll();
		
		$form = new FE_Form(Array(
			'name' => 'edit_user',
			'action' => '',
			'method' => 'post'
		));
		
		$personalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'personal_data',
			'label' => $this->registry->core->getMessage('TXT_PERSONAL_DATA')
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_EMAIL_ALREADY_EXISTS'), 'userdata', 'email', null, Array(
					'column' => 'userid',
					'values' => $this->registry->core->getParam()
				))
			)
		)));
		
		$changePassword = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'change_password',
			'label' => $this->registry->core->getMessage('TXT_CHANGE_USERS_PASSWORD')
		)));
		
		$userid = $this->registry->session->getActiveUserid();
		$edituserid = $this->registry->core->getParam();
		
		if ($userid == $edituserid){
			$oldPassword = $changePassword->AddChild(new FE_Password(Array(
				'name' => 'oldpasswd',
				'label' => $this->registry->core->getMessage('TXT_PASSWORD_OLD')
			)));
			
			$newPassword = $changePassword->AddChild(new FE_Password(Array(
				'name' => 'newppasswd',
				'label' => $this->registry->core->getMessage('TXT_PASSWORD_NEW'),
				'comment' => $this->registry->core->getMessage('TXT_PASSWORD_NEW_COMMENT')
			)));
			
			$changePassword->AddChild(new FE_Password(Array(
				'name' => 'newpasswdrep',
				'label' => $this->registry->core->getMessage('TXT_PASSWORD_REPEAT'),
				'rules' => Array(
					new FE_RuleCompare($this->registry->core->getMessage('ERR_PASSWORD_REPEAT_DOESNT_MATCH'), $newPassword)
				)
			)));
		}
		else{
			
			$changePassword->AddChild(new FE_StaticText(Array(
				'text' => '<p>' . $this->registry->core->getMessage('TXT_PASSWORD_CHANGE_INSTRUCTION') . '</p>'
			)));
			
			$newPassword = $changePassword->AddChild(new FE_Checkbox(Array(
				'name' => 'newpassword',
				'label' => $this->registry->core->getMessage('TXT_PASSWORD_NEW')
			)));
		
		}
		
		$isglobal = App::getModel('users')->checkActiveUserIsGlobal();
		
		if ($isglobal == 1){
			
			$rightsData = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'rights_data',
				'label' => $this->registry->core->getMessage('TXT_RIGHTS')
			)));
			
			$rightsData->AddChild(new FE_StaticText(Array(
				'text' => '<p>' . $this->registry->core->getMessage('TXT_SET_USER_LAYER_RIGHTS') . '</p>'
			)));
			
			$global = $rightsData->AddChild(new FE_Checkbox(Array(
				'name' => 'global',
				'label' => $this->registry->core->getMessage('TXT_GLOBAL_USER')
			)));
			
			$rightsData->AddChild(new FE_Select(Array(
				'name' => 'group',
				'label' => $this->registry->core->getMessage('TXT_GROUPS'),
				'options' => FE_Option::Make(App::getModel('groups/groups')->getGroupsAllToSelect()),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP'))
				),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $global, new FE_ConditionNot(new FE_ConditionEquals('1')))
				)
			)));
			
			$layers = App::getModel('users')->getLayersAll();
			
			foreach ($layers as $key => $store){
				$storeRightsData[$store['id']] = $rightsData->AddChild(new FE_Fieldset(Array(
					'name' => 'store_' . $store['id'],
					'label' => $this->registry->core->getMessage('TXT_RIGHTS') . ' dla ' . $store['name'],
					'dependencies' => Array(
						new FE_Dependency(FE_Dependency::SHOW, $global, new FE_ConditionNot(new FE_ConditionEquals('1')))
					)
				)));
				
				foreach ($store['views'] as $v => $view){
					
					$storeRightsData[$store['id']]->AddChild(new FE_Select(Array(
						'name' => 'view_' . $view['id'],
						'label' => $view['name'],
						'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('groups/groups')->getGroupsAllToSelect())
					)));
				
				}
			}
		}
		
		$additionalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'additional_data',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_DATA')
		)));
		
		$additionalData->AddChild(new FE_Textarea(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 3000',
			'max_length' => 3000
		)));
		
		$additionalData->AddChild(new FE_Checkbox(Array(
			'name' => 'active',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_USER')
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawUserData = App::getModel('users')->getUserById($this->registry->core->getParam());
		
		$userData = Array(
			'personal_data' => Array(
				'firstname' => $rawUserData['firstname'],
				'surname' => $rawUserData['surname'],
				'email' => $rawUserData['email']
			),
			'additional_data' => Array(
				'description' => $rawUserData['description'],
				'active' => $rawUserData['active']
			),
			'photos_pane' => Array(
				'photo' => $rawUserData['photo']
			),
			'rights_data' => Array(
				'global' => $rawUserData['globaluser'],
				'group' => $rawUserData['idgroup']
			)
		);
		foreach ($rawUserData['layer'] as $key => $layer){
			$userData['rights_data']['store_' . $layer['store']][] = Array(
				'view_' . $layer['view'] => $layer['group']
			);
		}
		
		$form->Populate($userData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				
				$edituser = $form->getSubmitValues();
				App::getModel('users')->updateUser($edituser, $this->registry->core->getParam());
				if (isset($_POST['change_password'])){
					if ($userid == $edituserid){
						$editpassword = $edituser['change_password']['newppasswd'];
						$changPassword = App::getModel('users')->updateUserPassword($edituser['change_password']['newppasswd']);
						$this->registry->session->setActiveUserFirstname($edituser['personal_data']['firstname']);
						$this->registry->session->setActiveUserSurname($edituser['personal_data']['surname']);
						$this->registry->session->setActiveUserEmail($edituser['personal_data']['email']);
					}
					else{
						$editpassword = Core::passwordGenerate();
						$changPassword = App::getModel('users')->updateUserPassword($editpassword);
						
						if ($changPassword == true){
							$password = Core::passwordGenerate();
							$mailer = new Mailer($this->registry);
							$mailer->addAddress($edituser['personal_data']['email']);
							$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
							$this->registry->template->assign('password', $editpassword);
							$mailer->loadContentToBody('editPasswordForUser');
							$mailer->setSubject($this->registry->core->getMessage('TXT_EDIT_PASSWORD_USER'));
							$mailer->IsHTML(true);
							try{
								$mailer->Send();
							}
							catch (phpmailerException $e){
							
							}
						}
					}
				
				}
			}
			catch (Exception $e){
				$this->registry->session->setVolatileUsereditError(1, false);
			}
			App::redirect(__ADMINPANE__ . '/users');
		}
		
		$error = $this->registry->session->getVolatileUsereditError();
		if ($error[0] == 1){
			$this->registry->template->assign('error', $e->getMessage());
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}