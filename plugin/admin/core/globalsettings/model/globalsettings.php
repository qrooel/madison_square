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
 * $Revision: 114 $
 * $Author: gekosale $
 * $Date: 2011-05-07 18:41:26 +0200 (So, 07 maj 2011) $
 * $Id: store.php 114 2011-05-07 16:41:26Z gekosale $ 
 */

class GlobalsettingsModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function updateGallerySettings ($Data)
	{
		$sql = 'UPDATE gallerysettings SET
					width = :width, 
					height = :height, 
					keepproportion = :keepproportion
				WHERE method = :method';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('width', $Data['small_width']);
		$stmt->setInt('height', $Data['small_height']);
		if (isset($Data['small_keepproportion']) && $Data['small_keepproportion'] == 1){
			$stmt->setInt('keepproportion', 1);
		}
		else{
			$stmt->setInt('keepproportion', 0);
		}
		$stmt->setString('method', 'getSmallImageById');
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GALLERYSETTINGS_EDIT'), 18, $e->getMessage());
		}
		
		$sql = 'UPDATE gallerysettings SET
					width = :width, 
					height = :height, 
					keepproportion = :keepproportion
				WHERE method = :method';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('width', $Data['medium_width']);
		$stmt->setInt('height', $Data['medium_height']);
		if (isset($Data['medium_keepproportion']) && $Data['medium_keepproportion'] == 1){
			$stmt->setInt('keepproportion', 1);
		}
		else{
			$stmt->setInt('keepproportion', 0);
		}
		$stmt->setString('method', 'getMediumImageById');
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GALLERYSETTINGS_EDIT'), 18, $e->getMessage());
		}
		
		$sql = 'UPDATE gallerysettings SET
					width = :width, 
					height = :height, 
					keepproportion = :keepproportion
				WHERE method = :method';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('width', $Data['normal_width']);
		$stmt->setInt('height', $Data['normal_height']);
		if (isset($Data['normal_keepproportion']) && $Data['normal_keepproportion'] == 1){
			$stmt->setInt('keepproportion', 1);
		}
		else{
			$stmt->setInt('keepproportion', 0);
		}
		$stmt->setString('method', 'getNormalImageById');
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GALLERYSETTINGS_EDIT'), 18, $e->getMessage());
		}
	
	}

	public function getGallerySettings ()
	{
		$sql = 'SELECT 
					width, 
					height, 
					keepproportion,
					method 
				FROM gallerysettings 
				WHERE width IS NOT NULL';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$method = $rs->getString('method');
			switch ($method) {
				case 'getSmallImageById':
					$Data['small_width'] = $rs->getInt('width');
					$Data['small_height'] = $rs->getInt('height');
					$Data['small_keepproportion'] = $rs->getInt('keepproportion');
					break;
				case 'getMediumImageById':
					$Data['medium_width'] = $rs->getInt('width');
					$Data['medium_height'] = $rs->getInt('height');
					$Data['medium_keepproportion'] = $rs->getInt('keepproportion');
					break;
				case 'getNormalImageById':
					$Data['normal_width'] = $rs->getInt('width');
					$Data['normal_height'] = $rs->getInt('height');
					$Data['normal_keepproportion'] = $rs->getInt('keepproportion');
					break;
			}
		}
		return $Data;
	}

	public function configWriter ($Data)
	{
		$rewrite = (isset($Data['force_mod_rewrite']) && $Data['force_mod_rewrite'] == 1) ? 1 : 0;
		$ssl = (isset($Data['ssl']) && $Data['ssl'] == 1) ? 1 : 0;
		$Config = $this->registry->config;
		$Config['admin_panel_link'] = addslashes($Data['admin_panel_link']);
		$Config['ssl'] = $ssl;
		$Config['force_mod_rewrite'] = $rewrite;
		$filename = ROOTPATH . 'config' . DS . 'settings.php';
		$out = fopen($filename, "w");
		fwrite($out, "<?php defined('ROOTPATH') OR die('No direct access allowed.');\r\n");
		fwrite($out, '/**
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
 */' . "\r\n");
		
		fwrite($out, '
		$Config = Array(' . "
			'database'=> Array(
				'phptype'=> 'mysqli',
				'hostspec'=> '{$Config['database']['hostspec']}',
				'port'=> " . (int) $Config['database']['port'] . ",
				'username'=> '{$Config['database']['username']}',
				'password'=> '{$Config['database']['password']}',
				'database'=> '{$Config['database']['database']}',
				'encoding'=> 'utf8'
			),
			'phpmailer'=> Array(
				'Mailer'=> '{$Data['mailer']}',
				'CharSet'=> 'UTF-8',
				'FromName'=> '{$Data['fromname']}',
				'FromEmail'=> '{$Data['fromemail']}',
				'server'=> '{$Data['server']}',
				'port'=> " . (int) $Data['port'] . ",
				'SMTPSecure'=> '{$Data['smtpsecure']}',
				'SMTPAuth'=> '{$Data['smtpauth']}',
				'SMTPUsername'=> '{$Data['smtpusername']}',
				'SMTPPassword'=> '{$Data['smtppassword']}',
			),
			'admin_panel_link'=> '{$Config['admin_panel_link']}',
			'force_mod_rewrite'=> {$Config['force_mod_rewrite']},
			'client_data_encription'=> 1,
			'client_data_encription_string'=> '{$Config['client_data_encription_string']}',
			'ssl'=> {$Config['ssl']},
		);");
		fclose($out);
	}

	public function updateGlobalSettings ($Data, $type)
	{
		foreach ($Data as $param => $value){
			$sql = 'INSERT INTO globalsettings SET
						param = :param,
						type = :type,
						value = :value
					ON DUPLICATE KEY UPDATE
						value = :value
					';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('param', $param);
			$stmt->setString('type', $type);
			$stmt->setString('value', $value);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_GALLERYSETTINGS_EDIT'), 18, $e->getMessage());
			}
		}
	}

	public function getSettings ()
	{
		$Data = Array();
		$sql = 'SELECT * FROM globalsettings WHERE param IS NOT NULL';
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[$rs->getString('type')][$rs->getString('param')] = $rs->getString('value');
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GALLERYSETTINGS_EDIT'), 18, $e->getMessage());
		}
		return $Data;
	}
}