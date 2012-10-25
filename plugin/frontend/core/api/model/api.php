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
 * $Revision: 279 $
 * $Author: gekosale $
 * $Date: 2011-07-28 23:13:43 +0200 (Cz, 28 lip 2011) $
 * $Id: product.php 279 2011-07-28 21:13:43Z gekosale $
 */

class apiModel extends Model
{

	public function addProduct ()
	{
	
	}

	public function updateProduct ()
	{
	
	}

	public function getProduct ($id)
	{
		try{
			return App::getModel('product')->getProductAndAttributesById($id);
		}
		catch (Exception $e){
			return $e->getMessage();
		}
	}

	public function deleteProduct ()
	{
	
	}

	public function getProducts ($request = Array())
	{
		try{
			$limit = isset($request['limit']) ? $request['limit'] : 100;
			$offset = isset($request['offset']) ? $request['offset'] : 0;
			$orderBy = isset($request['orderby']) ? $request['orderby'] : 'adddate';
			$orderDir = isset($request['orderdir']) ? $request['orderdir'] : 'desc';
			$categoryId = isset($request['categoryid']) ? $request['categoryid'] : 0;
			
			$dataset = App::getModel('api/productapi')->getDataset();
			$dataset->setPagination($limit);
			$dataset->setCurrentPage(ceil($offset / $limit));
			$dataset->setOrderBy('adddate', $orderBy);
			$dataset->setOrderDir('desc', $orderDir);
			$dataset->setAdditionalWhere('
				IF(:categoryid > 0, PC.categoryid = :categoryid, 1)
			');
			if ($categoryId > 0){
				$dataset->setSQLParams(Array(
					'categoryid' => $categoryId
				));
			}
			$products = App::getModel('api/productapi')->getProductDataset();
			return $products['rows'];
		}
		catch (Exception $e){
			return $e->getMessage();
		}
	
	}

	public function assignProductToCategory ()
	{
	
	}

	public function removeProductFromCategory ()
	{
	
	}

	public function getStock ($id)
	{
		try{
			$product = App::getModel('product')->getProductAndAttributesById($id);
			return $product['stock'];
		}
		catch (Exception $e){
			return $e->getMessage();
		}
	}

	public function updateStock ()
	{
	
	}

	public function getProductPhotos ()
	{
	
	}

	public function getProductMainPhoto ()
	{
	
	}

	public function addProductPhoto ()
	{
	
	}

	public function countProductPhotos ()
	{
	
	}

	public function deleteProductPhoto ()
	{
	
	}
}