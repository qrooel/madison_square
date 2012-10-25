<?php
/*
 * MODUŁ MIGRACJI DANYCH PRESTASHOP 1.3.x DO GEKOSALE
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
require (dirname(__FILE__) . '/config/config.inc.php');

class Migration
{
	protected $key;

	public function __construct ($key)
	{
		$this->key = $key;
		$this->configuration = Array();
		$this->_DBSERVER = _DB_SERVER_;
		$this->_DBNAME = _DB_NAME_;
		$this->_DBUSER = _DB_USER_;
		$this->_DBPASSWD = _DB_PASSWD_;
		$this->dbPreffix = _DB_PREFIX_;
		try{
			$this->db = new PDO("mysql:host={$this->_DBSERVER};dbname={$this->_DBNAME};charset=UTF-8", $this->_DBUSER, $this->_DBPASSWD);
			$this->db->query('SET names utf8');
		}
		catch (Exception $e){
			throw $e;
		}
		$this->setConfiguration();
		$this->link = new Link();
		$this->languageid = $this->getConfigurationKey('PS_LANG_DEFAULT');
		$this->countryid = $this->getConfigurationKey('PS_COUNTRY_DEFAULT');
		$this->currencyid = $this->getConfigurationKey('PS_CURRENCY_DEFAULT');
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

	public function setConfiguration ()
	{
		$sql = "SELECT * FROM `{$this->dbPreffix}configuration`";
		foreach ($this->db->query($sql) as $row){
			$this->configuration[$row['name']] = $row['value'];
		}
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
		$sql = "SELECT COUNT(`id_manufacturer`) AS total FROM `{$this->dbPreffix}manufacturer`";
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
		$sql = "SELECT COUNT(`id_product`) AS total FROM `{$this->dbPreffix}product`";
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
		$sql = "SELECT COUNT(`id_category`) AS total FROM `{$this->dbPreffix}category` WHERE id_parent > 0";
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
		$sql = "SELECT COUNT(`id_image`) AS total FROM `{$this->dbPreffix}image` WHERE cover = 1";
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
		$sql = "SELECT * FROM `{$this->dbPreffix}manufacturer` LIMIT {$request['offset']},1";
		foreach ($this->db->query($sql) as $row){
			$ManufacturersList['producer'] = Array(
				'id' => $row['id_manufacturer'],
				'name' => $row['name']
			);
		}
		return $ManufacturersList;
	}

	public function getCategory ($request)
	{
		$sql = "SELECT 
					C.id_category AS id, 
					IF(C.id_parent = 1, 0, C.id_parent) AS categoryid, 
					0 AS distinction, 
					CD.name AS name,
					CD.description AS description,
					CD.meta_title AS keyword_title,
					CD.meta_keywords AS keyword,
					CD.meta_description AS keyword_description
				FROM `{$this->dbPreffix}category` C
				LEFT JOIN `{$this->dbPreffix}category_lang` CD ON CD.id_category = C.id_category AND CD.id_lang = {$this->languageid}
				WHERE C.id_parent > 0
				GROUP BY C.id_category
				LIMIT {$request['offset']},1";
		foreach ($this->db->query($sql) as $row){
			$CategoriesList['category'] = Array(
				'id' => $row['id'],
				'categoryid' => $row['categoryid'],
				'distinction' => $row['distinction'],
				'name' => $row['name'],
				'shortdescription' => '',
				'description' => $row['description'],
				'keyword_title' => $row['keyword_title'],
				'keyword' => $row['keyword'],
				'keyword_description' => $row['keyword_description']
			);
		}
		return $CategoriesList;
	}

	public function getProduct ($request)
	{
		$ProductsList = Array();
		$sql = "SELECT 
					p.*, 
					pl.* , 
					i.*,
					t.`rate` AS tax_rate
				FROM `{$this->dbPreffix}product` p
				LEFT JOIN `{$this->dbPreffix}image` i ON p.`id_product` = i.`id_product` AND i.`cover` = 1
				LEFT JOIN `{$this->dbPreffix}product_lang` pl ON (p.`id_product` = pl.`id_product`)
	    		LEFT JOIN `{$this->dbPreffix}tax` t ON (t.`id_tax` = p.`id_tax`)
				LEFT JOIN `{$this->dbPreffix}category_product` c ON (c.`id_product` = p.`id_product`)
				WHERE pl.`id_lang` = {$this->languageid}
				GROUP BY p.id_product
				ORDER BY p.id_product ASC
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$photo = array_reverse(explode('/', $this->link->getImageLink($row['link_rewrite'], $row['id_product'].'-'.$row['id_image'])));
			$ProductsList['product'] = Array(
				'id' => $row['id_product'],
				'ean' => '',
				'photo' => $photo[0],
				'buyprice' => 0,
				'sellprice' => $row['price'],
				'producerid' => $row['id_manufacturer'],
				'name' => $row['name'],
				'description' => $row['description'],
				'shortdescription' => $row['description_short'],
				'categoryid' => $row['id_category_default'],
				'weight' => $row['weight'],
				'enable' => $row['active'],
				'vatvalue' => $row['tax_rate'],
				'stock' => $row['quantity']
			);
		}
		return $ProductsList;
	}

	public function getPhoto ($request)
	{
		$PhotosList = Array();
		$sql = "SELECT 
					p.*, 
					pl.* , 
					i.*
				FROM `{$this->dbPreffix}product` p
				LEFT JOIN `{$this->dbPreffix}image` i ON p.`id_product` = i.`id_product` AND i.`cover` = 1
				LEFT JOIN `{$this->dbPreffix}product_lang` pl ON (p.`id_product` = pl.`id_product`)
				WHERE pl.`id_lang` = {$this->languageid}
				GROUP BY p.id_product
				ORDER BY p.id_product ASC
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$photo = array_reverse(explode('/', $this->link->getImageLink($row['link_rewrite'], $row['id_product'].'-'.$row['id_image'])));
			$PhotosList['photo'] = Array(
				'url' => 'http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.$this->link->getImageLink($row['link_rewrite'], $row['id_product'].'-'.$row['id_image']),
				'name' => $photo[0]
			);
		}
		return $PhotosList;
	}
}

$key = '';
$migration = new Migration($key);
$migration->handle() or print 'no request';