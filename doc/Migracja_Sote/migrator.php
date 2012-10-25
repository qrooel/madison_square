<?php
/*
 * MODUŁ MIGRACJI DANYCH SOTESHOP DO GEKOSALE
 * 
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

define('_DB_SERVER_', '');//Host SQL
define('_DB_NAME_', '');//Nazwa bazy
define('_DB_USER_', '');//Nazwa uzytkownika
define('_DB_PASSWD_', '');//Haslo uzytkownika
define('_DB_PREFIX_', 'st_');//Preffix tabel Sote
define('_LOCAL_CATALOG_', '/');//Folder w jakim znajduje sie sklep

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

	public function getProducerTotal ()
	{
		$total = 0;
		$sql = "SELECT COUNT(`id`) AS total FROM `{$this->dbPreffix}producer_i18n` WHERE culture = 'pl_PL'";
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
		$sql = "SELECT COUNT(`id`) AS total FROM `{$this->dbPreffix}product`";
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
		$sql = "SELECT COUNT(`id`) AS total FROM `{$this->dbPreffix}category`";
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
		$sql = "SELECT COUNT(`opt_image`) AS total FROM `{$this->dbPreffix}product` WHERE opt_image !=''";
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
		$sql = "SELECT * FROM `{$this->dbPreffix}producer_i18n` WHERE culture = 'pl_PL' LIMIT {$request['offset']},1";
		foreach ($this->db->query($sql) as $row){
			$ManufacturersList['producer'] = Array(
				'id' => $row['id'],
				'name' => $row['name']
			);
		}
		return $ManufacturersList;
	}

	public function getCategory ($request)
	{
		$sql = "SELECT 
					C.id,
					C.parent_id AS categoryid,
					C.id AS distinction, 
					CD.name AS name, 
					CD.description AS description,
					CP.opt_title AS keyword_title, 
					CP.opt_keywords AS keyword, 
					CP.opt_description AS keyword_description
				FROM `{$this->dbPreffix}category` C
				LEFT JOIN `{$this->dbPreffix}category_i18n` CD ON CD.id = C.id AND CD.culture = 'pl_PL'
				LEFT JOIN `{$this->dbPreffix}category_has_positioning` CP ON CD.id = CP.category_id
				GROUP BY C.id
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
					P.id,
					P.code AS ean,
					P.opt_image,
					P.price,
					P.producer_id AS producerid,
					PD.name,
					PD.description,
					PD.short_description AS shortdescription,
					PC.category_id,
					P.weight AS weight,
					P.active,
					P.opt_vat,
					IF(P.stock IS NULL, 0, P.stock) AS stock
				FROM `{$this->dbPreffix}product` P
				LEFT JOIN `{$this->dbPreffix}product_i18n` PD ON P.`id` = PD.`id` AND PD.culture = 'pl_PL'
				LEFT JOIN `{$this->dbPreffix}product_has_category` PC ON PC.product_id = P.id AND PC.is_default = 1
				GROUP BY P.id
				ORDER BY P.id ASC
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$photo = array_reverse(explode('/', $row['opt_image']));
			$ProductsList['product'] = Array(
				'id' => $row['id'],
				'ean' => $row['ean'],
				'photo' => $photo[0],
				'buyprice' => 0,
				'sellprice' => $row['price'],
				'producerid' => $row['producerid'],
				'name' => $row['name'],
				'description' => $row['description'],
				'shortdescription' => $row['shortdescription'],
				'categoryid' => $row['category_id'],
				'weight' => $row['weight'],
				'enable' => $row['active'],
				'vatvalue' => $row['opt_vat'],
				'stock' => $row['stock']
			);
		}
		return $ProductsList;
	}

	public function getPhoto ($request)
	{
		$PhotosList = Array();
		$sql = "SELECT 
					opt_image
				FROM `{$this->dbPreffix}product`
				WHERE opt_image !=''
				LIMIT {$request['offset']},1
		";
		foreach ($this->db->query($sql) as $row){
			$photo = array_reverse(explode('/', $row['opt_image']));
			$PhotosList['photo'] = Array(
				'url' => 'http://' . $_SERVER['HTTP_HOST'] ._LOCAL_CATALOG_ .$row['opt_image'],
				'name' => $photo[0]
			);
		}
		return $PhotosList;
	}
}

$key = '';
$migration = new Migration($key);
$migration->handle() or print 'no request';