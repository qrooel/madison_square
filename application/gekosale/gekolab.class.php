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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: core.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class Gekolab
{
	
	protected $InstallerFile = NULL;
	protected $packageServer = 'http://www.gekolab.pl/packages';

	public function __construct ($registry)
	{
		$this->registry = $registry;
	}

	public function getInstalledPackages ()
	{
		$sql = 'SELECT 
					packagename, 
					version 
				FROM updatehistory WHERE packagename != :packagename';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('packagename', 'Gekosale');
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getString('packagename')] = $rs->getString('version');
		}
		return $Data;
	}

	public function __call ($method, $params)
	{
		
		if (is_array($params)){
			$params = array_values($params);
		}
		else{
			throw new Exception('Params must be given as array');
		}
		
		$request = array(
			'method' => $method,
			'params' => $params,
			'key' => sha1(App::getHost()),
			'domain' => App::getHost()
		);
		$request = json_encode($request);
		$curl = curl_init($this->packageServer);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json'
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		if (! is_null($response['error'])){
			throw new Exception('Request error: ' . $response['error']);
		}
		return $response['result'];
	}

	public function flushXML ()
	{
		$this->InstallerFile = NULL;
	}

	public function getInstallerFile ()
	{
		return $this->InstallerFile;
	}

	public function setInstallerFile ($content)
	{
		$this->InstallerFile = $content;
	}

	public function flushInstallerFile ()
	{
		$this->InstallerFile = NULL;
	}

	public function parseXML ($file)
	{
		if ($this->InstallerFile !== NULL){
			return $this->InstallerFile;
		}
		try{
			$this->xmlParser = new xmlParser();
			$this->setInstallerFile($this->xmlParser->parseFast($file));
			return $this->getInstallerFile();
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public function getLastUpdateHistoryByPackage ($packageName)
	{
		$sql = 'SELECT 
					idupdatehistory, 
					packagename, 
					version
				FROM updatehistory
				WHERE packageName = :packageName
				ORDER BY idupdatehistory DESC LIMIT 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('packageName', $packageName);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function addPackageHistory ($module, $version)
	{
		$sql = 'INSERT INTO updatehistory (packagename, version)
				VALUES (:packagename, :version)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('packagename', $module);
		$stmt->setString('version', $version);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public function installPackage ($module)
	{
		$package = $this->getPackage($module);
		$path = ROOTPATH . 'upload' . DS . $package['file'];
		if (is_file($path)){
			@unlink($path);
		}
		@set_time_limit(120);
		$curl = curl_init($package['url'] . ',' . sha1(App::getHost()));
		$fp = fopen($path, 'wb');
		$options = array(
			CURLOPT_FILE => $fp,
			CURLOPT_HEADER => 0
		);
		curl_setopt_array($curl, $options);
		curl_exec($curl);
		curl_close($curl);
		fclose($fp);
		
		require_once (ROOTPATH . 'lib' . DS . 'zip' . DS . 'zip.php');
		$archive = new PclZip($path);
		$list = $archive->extract(PCLZIP_OPT_PATH, ROOTPATH, PCLZIP_OPT_REPLACE_NEWER);
		if ($list != FALSE){
			$packageHistory = $this->getLastUpdateHistoryByPackage($module);
			if (empty($packageHistory)){
				$localVersion = 0;
			}
			else{
				$localVersion = $packageHistory[0]['version'];
			}
			$serverVersion = $package['server_version'];
			$this->executeUpdateXml($localVersion, $serverVersion, $module);
			$this->addPackageHistory($module, $package['server_version']);
		}
		$this->registry->session->setActiveMenuData(NULL);
		@unlink($path);
	}

	public function executeUpdateXml ($current, $revision, $packageName)
	{
		$updateXmlFile = ROOTPATH . 'sql' . DS . $packageName . DS . 'mysql_update' . DS . 'update.xml';
		if (is_file($updateXmlFile)){
			$Data = $this->parseXML($updateXmlFile);
			foreach ($Data->install as $queries){
				if ($revision == $current){
					if ($queries->attributes()->version == $current){
						foreach ($queries->query as $query){
							try{
								$this->registry->db->executeUpdate((string) $query);
							}
							catch (Exception $e){
							}
						}
					}
				}
				else{
					if ($queries->attributes()->version > $current){
						foreach ($queries->query as $query){
							try{
								$this->registry->db->executeUpdate((string) $query);
							}
							catch (Exception $e){
							}
						}
					}
				}
			
			}
			$this->flushXML();
		
		}
	}

	public function uninstall ($file)
	{
		$Data = $this->parseXML($file);
		foreach ($Data->uninstall as $uninstall){
			foreach ($uninstall->query as $query){
				try{
					$this->registry->db->executeUpdate((string) $query);
				}
				catch (Exception $e){
				}
			}
		}
	}

	public function deletePackageHistory ($package)
	{
		$sql = 'DELETE FROM updatehistory WHERE
				packagename = :packagename';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('packagename', $package);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw $e;
		}
		$configXmlFile = ROOTPATH . 'config' . DS . $package . '.xml';
		if (is_file($configXmlFile)){
			$configXML = $this->xmlParser->parseFast($configXmlFile);
			foreach ($configXML->file as $key => $val){
				$file = str_replace('/', DS, (string) $val);
				if (is_file(ROOTPATH . $file)){
					@unlink(ROOTPATH . $file);
				}
			}
		}
		@unlink($configXmlFile);
		$this->registry->session->setActiveMenuData(NULL);
	
	}
}