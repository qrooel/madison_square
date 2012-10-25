<?php
/*
 * MODUŁ MIGRACJI DANYCH KQS.store DO GEKOSALE
 */
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009-2011 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */
(defined('E_DEPRECATED')) ? error_reporting(E_ALL & ~ E_DEPRECATED) : error_reporting(E_ALL);
ini_set('display_errors', 1);
require (dirname(__FILE__) . '/config.php');
define('__LOCAL_CATALOG__', '/'); //nazwa katalogu w jakim znajduje sie sklep np /sklep/. Jezeli jest w katalogu glownym pozostaw /


class Migration
{
	protected $key;

	public function __construct ($key)
	{
		global $dbhost, $dbusername, $dbuserpassword, $dbname, $prek;
		
		$this->key = $key;
		$this->configuration = Array();
		$this->_DBSERVER = $dbhost;
		$this->_DBNAME = $dbname;
		$this->_DBUSER = $dbusername;
		$this->_DBPASSWD = $dbuserpassword;
		$this->dbPreffix = $prek;
		try{
			$this->db = new PDO("mysql:host={$this->_DBSERVER};dbname={$this->_DBNAME};charset=UTF-8", $this->_DBUSER, $this->_DBPASSWD);
			$this->db->query('SET names utf8');
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public function handle ()
	{
		
		if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] != 'application/json'){
			return false;
		}
		
		$request = json_decode(file_get_contents('php://input'), true);
		
		if ($request['key'] !== $this->key){
			$response = array(
				'result' => NULL,
				'error' => 'authentification failed'
			);
			header('content-type: text/javascript');
			echo json_encode($response);
			die();
		}
		try{
			if ($result = call_user_func_array(array(
				$this,
				$request['method']
			), $request['params'])){
				$response = array(
					'result' => $result,
					'error' => NULL
				);
			}
			else{
				$response = array(
					'result' => NULL,
					'error' => 'unknown method ' . $request['method'] . ' or incorrect parameters ' . json_encode($request['params']) . ' RESULT: ' . json_encode($result)
				);
			}
		}
		catch (Exception $e){
			$response = array(
				'result' => NULL,
				'error' => $e->getMessage()
			);
		}
		
		header('content-type: text/javascript');
		echo json_encode($response);
		return true;
	}

	public function getConfigurationKey ($key)
	{
		if (isset($this->configuration[$key])){
			return $this->configuration[$key];
		}
	}

	public function getProducerTotal ()
	{
		$total = 0;
		$sql = "SELECT COUNT(`numer`) AS total FROM `{$this->dbPreffix}producenci`";
		foreach ($this->db->query($sql) as $row){
			$total = $row['total'];
		}
		return Array(
			'total' => $total
		);
	}

	public function getProductTotal ()
	{
		$total = 0;
		$sql = "SELECT COUNT(`numer`) AS total FROM `{$this->dbPreffix}produkty`";
		foreach ($this->db->query($sql) as $row){
			$total = $row['total'];
		}
		return Array(
			'total' => $total
		);
	}

	public function getCategoryTotal ()
	{
		$total = 0;
		$sql = "SELECT COUNT(`numer`) AS total FROM `{$this->dbPreffix}kategorie`";
		foreach ($this->db->query($sql) as $row){
			$total = $row['total'];
		}
		return Array(
			'total' => $total
		);
	}

	public function getPhotosTotal ()
	{
		$total = 0;
		$sql = "SELECT COUNT(`numer`) AS total FROM `{$this->dbPreffix}galeria` WHERE produkt_id > 0";
		foreach ($this->db->query($sql) as $row){
			$total = $row['total'];
		}
		return Array(
			'total' => $total
		);
	}

	public function getProducer ($request)
	{
		
		$ManufacturersList = Array();
		$sql = "SELECT * FROM `{$this->dbPreffix}producenci` LIMIT {$request['offset']},1";
		foreach ($this->db->query($sql) as $row){
			$ManufacturersList['producer'] = Array(
				'id' => $row['numer'],
				'name' => $row['nazwa']
			);
		}
		return $ManufacturersList;
	}

	public function getCategory ($request)
	{
		$sql = "SELECT 
					*
				FROM `{$this->dbPreffix}kategorie`
				LIMIT {$request['offset']},1";
		foreach ($this->db->query($sql) as $row){
			$CategoriesList['category'] = Array(
				'id' => $row['numer'],
				'categoryid' => $row['kat_matka'],
				'distinction' => $row['kolejnosc'],
				'name' => $row['nazwa'],
				'shortdescription' => '',
				'description' => '',
				'keyword_title' => '',
				'keyword' => '',
				'keyword_description' => ''
			);
		}
		return $CategoriesList;
	}

	public function getProduct ($request)
	{
		$ProductsList = Array();
		$sql = "SELECT 
					*
				FROM `{$this->dbPreffix}produkty` p
				LEFT JOIN `{$this->dbPreffix}galeria` i ON p.`numer` = i.`produkt_id` AND i.`kolejnosc` = 1
				ORDER BY p.numer ASC
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$ProductsList['product'] = Array(
				'id' => $row['numer'],
				'ean' => $row['kod_kreskowy'],
				'photo' => $row['obraz'].'.jpg',
				'buyprice' => 0,
				'sellprice' => $row['cena'],
				'producerid' => $row['producent_id'],
				'name' => $row['nazwa'],
				'description' => $row['opis'],
				'shortdescription' => $row['krotki_opis'],
				'categoryid' => $row['kat_id'],
				'weight' => $row['waga'],
				'enable' => $row['aktywne'],
				'vatvalue' => $row['podatek'],
				'stock' => $row['stan']
			);
		}
		return $ProductsList;
	}

	public function getPhoto ($request)
	{
		$PhotosList = Array();
		$sql = "SELECT 
					*
				FROM `{$this->dbPreffix}galeria`
				WHERE produkt_id > 0
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$PhotosList['photo'] = Array(
				'url' => 'http://' . $_SERVER['HTTP_HOST'] . __LOCAL_CATALOG__ . 'galerie/' . substr($row['obraz'], 0, 1) . '/' . $row['obraz'] . '.jpg',
				'name' => $row['obraz'] . '.jpg'
			);
		
		}
		return $PhotosList;
	}
}

$key = '';
$migration = new Migration($key);
$migration->handle() or print 'no request';