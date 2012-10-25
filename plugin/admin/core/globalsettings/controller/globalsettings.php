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
 * $Revision: 111 $
 * $Author: gekosale $
 * $Date: 2011-05-06 21:54:00 +0200 (Pt, 06 maj 2011) $
 * $Id: store.php 111 2011-05-06 19:54:00Z gekosale $ 
 */

class GlobalsettingsController extends Controller
{

	public function index ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_globalsettings',
			'action' => '',
			'method' => 'post',
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'config_data',
			'label' => $this->registry->core->getMessage('TXT_SYSTEM_CONFIGURATION')
		)));
		
		$requiredData->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_ATTENTION_AFTER_CHANGING_ADMINLINK') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'admin_panel_link',
			'label' => $this->registry->core->getMessage('TXT_ADMIN_PANEL_LINK'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ADMIN_PANEL_LINK'))
			),
			'default' => __ADMINPANE__
		)));
		
		$requiredData->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_SSL_HELP') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'ssl',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_SSL'),
		)));
		
		$requiredData->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_FORCE_MOD_REWRITE_HELP') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'force_mod_rewrite',
			'label' => $this->registry->core->getMessage('TXT_FORCE_MOD_REWRITE')
		)));
		
		$mailerdata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'mailer_data',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SETTINGS')
		)));
		
		$mailerType = $mailerdata->AddChild(new FE_Select(Array(
			'name' => 'mailer',
			'label' => $this->registry->core->getMessage('TXT_MAIL_TYPE'),
			'options' => FE_Option::Make(Array(
				'mail' => 'mail',
				'sendmail' => 'sendmail',
				'smtp' => 'smtp'
			))
		)));
		
		$mailerdata->AddChild(new FE_TextField(Array(
			'name' => 'server',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SERVER'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_SERVER'))
			)
		)));
		
		$mailerdata->AddChild(new FE_TextField(Array(
			'name' => 'port',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SERVER_PORT'),
			'default' => 587,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_SERVER_PORT'))
			)
		)));
		
		$mailerdata->AddChild(new FE_Select(Array(
			'name' => 'smtpsecure',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SMTP_SECURE'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			),
			'options' => FE_Option::Make(Array(
				'' => 'brak',
				'ssl' => 'ssl',
				'tls' => 'tls'
			)),
			'value' => ''
		)));
		
		$mailerdata->AddChild(new FE_Checkbox(Array(
			'name' => 'smtpauth',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SMTP_AUTH'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			)
		)));
		
		$mailerdata->AddChild(new FE_TextField(Array(
			'name' => 'smtpusername',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SMTP_USERNAME'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_SMTP_USERNAME'))
			)
		)));
		
		$mailerdata->AddChild(new FE_Password(Array(
			'name' => 'smtppassword',
			'label' => $this->registry->core->getMessage('TXT_MAIL_SMTP_PASSWORD'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_SMTP_PASSWORD'))
			)
		)));
		
		$mailerdata->AddChild(new FE_TextField(Array(
			'name' => 'fromname',
			'label' => $this->registry->core->getMessage('TXT_MAIL_FROMNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMNAME'))
			)
		)));
		
		$mailerdata->AddChild(new FE_TextField(Array(
			'name' => 'fromemail',
			'label' => $this->registry->core->getMessage('TXT_MAIL_FROMEMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			)
		)));
		
		$gallerySettings = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'gallerysettings_data',
			'label' => $this->registry->core->getMessage('TXT_GALLERY_SETTINGS')
		)));
		
		$gallerySettings->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_GALLERY_SMALL_IMAGE_SETTINGS') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'small_width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'small_height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_Checkbox(Array(
			'name' => 'small_keepproportion',
			'label' => $this->registry->core->getMessage('TXT_KEEP_PROPORTION')
		)));
		
		$gallerySettings->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_GALLERY_MEDIUM_IMAGE_SETTINGS') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'medium_width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'medium_height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_Checkbox(Array(
			'name' => 'medium_keepproportion',
			'label' => $this->registry->core->getMessage('TXT_KEEP_PROPORTION')
		)));
		
		$gallerySettings->AddChild(new FE_Tip(Array(
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_GALLERY_NORMAL_IMAGE_SETTINGS') . '</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'normal_width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_TextField(Array(
			'name' => 'normal_height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MAIL_FROMEMAIL'))
			),
			'suffix' => 'px'
		)));
		
		$gallerySettings->AddChild(new FE_Checkbox(Array(
			'name' => 'normal_keepproportion',
			'label' => $this->registry->core->getMessage('TXT_KEEP_PROPORTION')
		)));
		
		$interfaceSettings = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'interface',
			'label' => $this->registry->core->getMessage('TXT_INTERFACE_SETTINGS')
		)));
		
		$interfaceSettings->AddChild(new FE_TextField(Array(
			'name' => 'datagrid_rows_per_page',
			'label' => $this->registry->core->getMessage('TXT_DATAGRID_ROWS_PER_PAGE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_DATAGRID_ROWS_PER_PAGE'))
			),
			'default' => 50
		)));
		
		$interfaceSettings->AddChild(new FE_Select(Array(
			'name' => 'datagrid_click_row_action',
			'label' => $this->registry->core->getMessage('TXT_DATAGRID_CLICK_ROW_ACTION'),
			'options' => FE_Option::Make(Array(
				'edit' => $this->registry->core->getMessage('TXT_DATAGRID_EDIT_ROW'),
				'contextmenu' => $this->registry->core->getMessage('TXT_SHOW_CONTEXT_MENU')
			))
		)));
		
		$Config = $this->registry->config;
		
		$configData = Array(
			'config_data' => Array(
				'admin_panel_link' => __ADMINPANE__,
				'force_mod_rewrite' => (string) (isset($Config['force_mod_rewrite']) && $Config['force_mod_rewrite'] == 1) ? 1 : 0,
				'ssl' => (string) (isset($Config['ssl']) && $Config['ssl'] == 1) ? 1 : 0
			),
			'mailer_data' => Array(
				'mailer' => $Config['phpmailer']['Mailer'],
				'fromname' => $Config['phpmailer']['FromName'],
				'fromemail' => $Config['phpmailer']['FromEmail'],
				'server' => $Config['phpmailer']['server'],
				'port' => $Config['phpmailer']['port'],
				'smtpsecure' => $Config['phpmailer']['SMTPSecure'],
				'smtpauth' => $Config['phpmailer']['SMTPAuth'],
				'smtpusername' => $Config['phpmailer']['SMTPUsername'],
				'smtppassword' => $Config['phpmailer']['SMTPPassword']
			),
			'gallerysettings_data' => App::getModel('globalsettings')->getGallerySettings()
		);
		
		$settingsData = App::getModel('globalsettings')->getSettings();
		
		$event = new sfEvent($this, 'admin.globalsettings.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.globalsettings.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$configData[$tab] = $values;
			}
		}
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		$form->Populate(array_merge($configData, $settingsData));
		if ($form->Validate(FE::SubmittedData())){
			try{
				$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				$Settings = $form->getSubmitValues();
				$event = new sfEvent($this, 'admin.globalsettings.model.save', Array(
					'id' => 1,
					'data' => $Data
				));
				App::getModel('globalsettings')->updateGallerySettings($Data);
				App::getModel('globalsettings')->updateGlobalSettings($Settings['interface'], 'interface');
				$this->registry->dispatcher->notify($event);
				$this->registry->session->setActiveGlobalSettings(NULL);
				App::getModel('globalsettings')->configWriter($Data);
				if (__ADMINPANE__ != $Data['admin_panel_link']){
					App::redirect('logout;');
				}
				else{
					$sUrl = __ADMINPANE__ . '/globalsettings';
					echo "<script>window.location.href({$sUrl});</script>";
				}
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
		
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

}