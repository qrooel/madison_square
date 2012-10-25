<?php
/*
 * MODUŁ MIGRACJI DANYCH esklep-os DO GEKOSALE
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
ini_set('display_errors', 1);
require ('includes/configure.php');
require ('includes/database_tables.php');
require ('includes/filenames.php');
require ('includes/functions/database.php');
require ('includes/functions/general.php');
require ('includes/functions/password_funcs.php');
require ('includes/functions/validations.php');
require ('includes/functions/html_output.php');

class Migration
{
	protected $key;

	public function __construct ($key)
	{
		$this->key = $key;
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
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlManufacturers = "SELECT COUNT(`manufacturers_id`) AS total FROM `manufacturers`";
		$sqlManufacturersQuery = tep_db_query($sqlManufacturers);
		while ($manufacturers = tep_db_fetch_array($sqlManufacturersQuery)){
			$totalProducers = $manufacturers['total'];
		}
		return Array(
			'total' => $totalProducers
		);
	}

	public function getProductTotal ()
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlProducts = "SELECT COUNT(`products_id`) AS total FROM `products`";
		$sqlProductsQuery = tep_db_query($sqlProducts);
		while ($products = tep_db_fetch_array($sqlProductsQuery)){
			$totalProducts = $products['total'];
		}
		
		return Array(
			'total' => $totalProducts
		);
	}

	public function getCategoryTotal ()
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlCategories = "SELECT COUNT(`categories_id`) AS total FROM `categories`";
		$sqlCategoriesQuery = tep_db_query($sqlCategories);
		while ($categories = tep_db_fetch_array($sqlCategoriesQuery)){
			$totalCategories = $categories['total'];
		}
		
		return Array(
			'total' => $totalCategories
		);
	}

	public function getPhotosTotal ()
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$totalPhotos = 0;
		$sqlPhotos = "SELECT COUNT(products_image) AS total FROM `products`";
		$sqlPhotosQuery = tep_db_query($sqlPhotos);
		while ($photos = tep_db_fetch_array($sqlPhotosQuery)){
			$totalPhotos = $photos['total'];
		}
		
		return Array(
			'total' => $totalPhotos
		);
	}

	public function getProducer ($request)
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlManufacturers = "SELECT 
								`manufacturers_id` AS id, 
								`manufacturers_name` AS name 
							FROM `manufacturers` 
							LIMIT {$request['offset']},1";
		$sqlManufacturersQuery = tep_db_query($sqlManufacturers);
		$ManufacturersList = Array();
		while ($manufacturers = tep_db_fetch_array($sqlManufacturersQuery)){
			$ManufacturersList['producer'] = $manufacturers;
		}
		return $ManufacturersList;
	}

	public function getCategory ($request)
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlCategories = "SELECT 
							  C.categories_id AS id, 
							  C.parent_id AS categoryid, 
							  C.sort_order AS distinction, 
							  CD.categories_name AS name
						  FROM categories C
						  LEFT JOIN categories_description CD ON CD.categories_id = C.categories_id AND CD.language_id =1
						  GROUP BY CD.categories_id
						  LIMIT {$request['offset']},1";
		$sqlCategoriesQuery = tep_db_query($sqlCategories);
		$CategoriesList = Array();
		while ($categories = tep_db_fetch_array($sqlCategoriesQuery)){
			$CategoriesList['category'] = Array(
				'id' => $categories['id'],
				'categoryid' => $categories['categoryid'],
				'distinction' => $categories['distinction'],
				'name' => $categories['name'],
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
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlProducts = "SELECT 
							P.products_id AS idproduct,
							P.products_model AS ean, 
							P.products_image AS photo,
							P.products_price AS sellprice, 
							P.manufacturers_id AS producerid, 
							PD.products_name AS name,
							PD.products_description AS description,
							PD.products_short_description AS shortdescription,
							PC.categories_id AS categoryid,
							P.products_weight AS weight,
							P.products_status AS enable,
							TR.tax_rate AS vatvalue
						FROM products P
						LEFT JOIN products_description PD ON PD.products_id = P.products_id AND PD.language_id = 1
						LEFT JOIN tax_rates TR ON P.products_tax_class_id = TR.tax_class_id
						LEFT JOIN products_to_categories PC ON PC.products_id = P.products_id 
						LIMIT {$request['offset']},1
						";
		$sqlProductsQuery = tep_db_query($sqlProducts);
		$ProductsList = Array();
		while ($products = tep_db_fetch_array($sqlProductsQuery)){
			$photo = array_reverse(explode('/', $products['photo']));
			$ProductsList['product'] = Array(
				'id' => $products['idproduct'],
				'ean' => $products['ean'],
				'photo' => $photo[0],
				'buyprice' => 0,
				'sellprice' => $products['sellprice'],
				'producerid' => $products['producerid'],
				'name' => $products['name'],
				'description' => $products['description'],
				'shortdescription' => $products['shortdescription'],
				'categoryid' => $products['categoryid'],
				'weight' => $products['weight'],
				'enable' => $products['enable'],
				'vatvalue' => $products['vatvalue'],
				'stock' => 0
			);
		}
		return $ProductsList;
	}

	public function getPhoto ($request)
	{
		tep_db_connect();
		tep_db_query('SET names utf8');
		$sqlPhotos = "SELECT 
							products_image AS photo
						FROM products
						LIMIT {$request['offset']}, 1
						";
		$sqlPhotosQuery = tep_db_query($sqlPhotos);
		$PhotosList = Array();
		while ($photos = tep_db_fetch_array($sqlPhotosQuery)){
			$photo = array_reverse(explode('/', $photos['photo']));
			$PhotosList['photo'] = Array(
				'url' => HTTP_SERVER . '/' . DIR_WS_IMAGES . $photos['photo'],
				'name' => $photo[0]
			);
		}
		return $PhotosList;
	}
}

$key = '';
$migration = new Migration($key);
$migration->handle() or print 'no request';
