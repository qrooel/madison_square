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
 * $Id: gekolab.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class GekolabController extends Controller
{
	
	protected $installedPackages = Array();

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->gekoLab = new Gekolab($registry);
		$this->installedPackages = $this->gekoLab->getInstalledPackages();
		$this->xmlParser = new xmlParser();
	}

	public function index ()
	{
		$packages = $this->gekoLab->getPackages();
		$Data = Array();
		if (! is_array($packages)){
			$this->registry->template->assign('channelError', $this->registry->core->getMessage('ERR_CHANNEL_CONNECT'));
		}
		else{
			foreach ($packages as $key => $package){
				$install = 0;
				$upgrade = 0;
				$uninstall = 0;
				$version = null;
				if (isset($this->installedPackages[$package['package']])){
					$version = $this->installedPackages[$package['package']];
					$uninstall = 1;
					if ($package['server_version'] > $this->installedPackages[$package['package']]){
						$upgrade = 1;
					}
				}
				else{
					$install = 1;
				}
				$Data['packages'][] = Array(
					'name' => $package['name'],
					'package' => $package['package'],
					'server_version' => $package['server_version'],
					'local_version' => $version,
					'install' => $install,
					'upgrade' => $upgrade,
					'uninstall' => $uninstall
				);
			}
			$this->registry->template->assign('packages', json_encode($Data['packages']));
		}
		
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$this->disableLayout();
		$this->gekoLab->installPackage($this->registry->core->getParam());
		App::redirect(__ADMINPANE__ . '/gekolab');
	}

	public function edit ()
	{
		$this->disableLayout();
		$this->gekoLab->installPackage($this->registry->core->getParam());
		App::redirect(__ADMINPANE__ . '/gekolab');
	}

	public function delete ()
	{
		$this->disableLayout();
		$module = $this->registry->core->getParam();
		$updateXmlFile = ROOTPATH . 'sql' . DS . $module . DS . 'mysql_update' . DS . 'update.xml';
		if (is_file($updateXmlFile)){
			$this->gekoLab->uninstall($updateXmlFile);
		}
		$this->gekoLab->deletePackageHistory($module);
		App::redirect(__ADMINPANE__ . '/gekolab');
	}

	protected function clearCache ($dir)
	{
		if (! $dh = @opendir($dir))
			return;
		while (false !== ($obj = readdir($dh))){
			if ($obj == '.' || $obj == '..')
				continue;
			if (! @unlink($dir . '/' . $obj))
				$this->clearCache($dir . '/' . $obj, true);
		}
		
		closedir($dh);
	}

}