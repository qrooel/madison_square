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
 * $Id: productsearch.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class productsearchModel extends Model
{

	public function addProductToSearch ($request)
	{
		$Data = $request['data'];
		$productid = $request['id'];
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO productsearch (productid, languageid, name, shortdescription, description, producername, attributes)
						VALUES (:productid, :languageid, :name, :shortdescription, :description, :producername, :attributes)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $productid);
			$stmt->setInt('languageid', $key);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('producername', App::getModel('producer')->getProducerNameById($Data['producerid'], $key));
			if ($Data['variants'] == NULL){
				$stmt->setNull('attributes');
			}
			else{
				$stmt->setString('attributes', $Data['variants']['set']);
			}
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_SEARCH_INSERT'), 112, $e->getMessage());
			}
		}
	}

	public function updateProductSearch ($request)
	{
		$Data = $request['data'];
		$id = $request['id'];
		$sql = 'DELETE FROM productsearch WHERE productid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO productsearch (productid, languageid, name, shortdescription, description, producername, attributes)
						VALUES (:productid, :languageid, :name, :shortdescription, :description, :producername, :attributes)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('languageid', $key);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('producername', App::getModel('producer')->getProducerNameById($Data['producerid'], $key));
			if (! isset($Data['variants']) || $Data['variants'] == NULL){
				$stmt->setNull('attributes');
			}
			else{
				$stmt->setString('attributes', $Data['variants']['set']);
			}
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_SEARCH_UPDATE'), 112, $e->getMessage());
			}
		}
	}

	protected function ProductSearchStatus ($productid, $status)
	{
		$sql = 'UPDATE productsearch SET enable = :status WHERE productid = :productid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('status', $status);
		$stmt->setInt('productid', $productid);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableProductSearchForProduct ($idproduct)
	{
		$this->ProductSearchStatus($idproduct, 1);
	}

	public function disableProductSearchForProduct ($idproduct)
	{
		$this->ProductSearchStatus($idproduct, 0);
	}

	protected function getAttributeNamesFromProductArray ($Data)
	{
		$Attr = Array();
		foreach ($Data as $index => $attributes){
			foreach ($attributes['attribute'] as $attr){
				$Attr[] = $attr;
			}
		}
		$Attr = App::getModel('attributeproduct')->getAttributeNamesDistinctByArrayId($Attr);
		if (count($Attr) > 0){
			return implode(' ', $Attr);
		}
		return false;
	}
}