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
 * $Id: updater.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class UpdaterController extends Controller
{
	protected $packageName = 'Gekosale';
	protected $packageInfo = 'http://www.gekosale.pl/updates';
	protected $packageServer = 'update.gekosale.pl';

	public function index ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'update',
			'action' => '',
			'method' => 'post'
		));
		
		$typePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'package_data',
			'label' => 'Dostępne aktualizacje'
		)));
		
		$this->xmlParser = new xmlParser();
		$packageXML = $this->xmlParser->parseExternal($this->packageInfo);
		
		$Data = Array();
		$current = App::getModel('updater')->getLastUpdateHistoryByPackage($this->packageName);
		
		if (! is_object($packageXML)){
			$this->registry->template->assign('channelError', $this->registry->core->getMessage('ERR_CHANNEL_CONNECT'));
		}
		else{
			$options = Array();
			foreach ($packageXML->package as $package){
				if ($package->version > $current){
					$options[] = new FE_Option((string) $package->version, (string) $package->name . ' (' . (string) $package->stability . ')');
				}
			}
			if (count($options) > 0){
				$version = $typePane->AddChild(new FE_Select(Array(
					'name' => 'version',
					'label' => 'Wersja',
					'options' => $options
				)));
				
				$typePane->AddChild(new FE_StaticText(Array(
					'text' => '<a href="#update" rel="submit" class="button" style="margin-left: 183px;position: absolute;top: 75px;left: 195px;"><span><img src="'.DESIGNPATH.'_images_panel/icons/datagrid/refresh.png" alt=""/>Aktualizuj Gekosale</span></a>'
				)));
			}
			else{
				$typePane->AddChild(new FE_StaticText(Array(
					'text' => 'Brak aktualizacji w tym momencie'
				)));
			}
			foreach ($packageXML->package as $package){
				if ($package->version > $current){
					
					$options[] = new FE_Option((string) $package->version, (string) $package->name . ' (' . (string) $package->stability . ')');
					
					$improvements = 'improvements_' . str_replace('.', '', (string) $package->version);
					
					$$improvements = $form->AddChild(new FE_Fieldset(Array(
						'name' => $improvements,
						'label' => 'Usprawnienia dla ' . (string) $package->version
					)));
					
					$$improvements->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $version, new FE_ConditionEquals((string) $package->version)));
					
					$$improvements->AddChild(new FE_StaticText(Array(
						'text' => implode('<br/><br/>', $this->parseList($package->improvements))
					)));
					
					$bugfixes = 'bugfixes_' . str_replace('.', '', (string) $package->version);
					
					$$bugfixes = $form->AddChild(new FE_Fieldset(Array(
						'name' => $bugfixes,
						'label' => 'Poprawki dla ' . (string) $package->version
					)));
					
					$$bugfixes->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $version, new FE_ConditionEquals((string) $package->version)));
					
					$$bugfixes->AddChild(new FE_StaticText(Array(
						'text' => implode('<br/><br/>', $this->parseList($package->bugfixes))
					)));
				
				}
			}
		}
		
		if ($form->Validate(FE::SubmittedData())){
			$post = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			
			$Data = Array();
			
			foreach ($packageXML->package as $package){
				if ($post['version'] == (string) $package->version){
					$url = 'http://' . $this->packageServer . '/' . (string) $package->url;
					$Data = Array(
						'name' => (string) $package->name,
						'url' => $url,
						'file' => (string) $package->url,
						'version' => (string) $package->version,
						'improvements' => $this->parseList($package->improvements),
						'bugfixes' => $this->parseList($package->bugfixes),
						'current' => $current
					);
					break;
				}
			}
			$this->registry->session->setActiveUpdateData($Data);
			App::redirect(__ADMINPANE__ . '/updater/confirm');
		}
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function confirm ()
	{
		$Data = $this->registry->session->getActiveUpdateData();
		
		if ($Data == NULL){
			App::redirect('admin/updater');
		}
		
		$form = new FE_Form(Array(
			'name' => 'add_substitutedservicesend',
			'action' => '',
			'method' => 'post'
		));
		
		$progress = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'progres_data',
			'label' => 'Aktualizacja'
		)));
		
		$progress->AddChild(new FE_ProgressIndicator(Array(
			'name' => 'progress',
			'label' => 'Postęp aktualizacji',
			'chunks' => 1,
			'load' => Array(
				App::getModel('updater'),
				'doLoadQueque'
			),
			'process' => Array(
				App::getModel('updater'),
				'doProcessQueque'
			),
			'success' => Array(
				App::getModel('updater'),
				'doSuccessQueque'
			),
			'preventSubmit' => true
		)));
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('package', $Data);
		$this->registry->template->display($this->loadTemplate('confirm.tpl'));
	
	}

	protected function parseList ($str)
	{
		return explode("\n", $str);
	}

}