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
 * $Id: deliverer.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class DelivererModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('deliverer', Array(
			'iddeliverer' => Array(
				'source' => 'D.iddeliverer'
			),
			'name' => Array(
				'source' => 'DT.name',
				'prepareForAutosuggest' => true
			),
			'www' => Array(
				'source' => 'DT.www'
			),
			'adddate' => Array(
				'source' => 'D.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'D.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`deliverer` D
				LEFT JOIN deliverertranslation DT ON DT.delivererid = D.iddeliverer AND DT.languageid = :languageid
				LEFT JOIN `user` U ON D.addid = U.iduser
				LEFT JOIN `userdata` UDA ON U.iduser = UDA.userid
				LEFT JOIN `user` UE ON D.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON U.iduser = UDE.userid
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getDelivererForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteDeliverer ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteDeliverer'
		), $this->getName());
	}

	public function deleteDeliverer ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'iddeliverer' => $id
			), $this->getName(), 'deleteDeliverer');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getDelivererView ($id)
	{
		$sql = "SELECT iddeliverer AS id, photoid as photo FROM deliverer WHERE iddeliverer=:id";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getDelivererTranslation($id),
				'photo' => $rs->getInt('photo')
			);
			return $Data;
		}
		throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_NO_EXIST'));
	}

	public function getDelivererTranslation ($id)
	{
		$sql = "SELECT name,www,email,languageid
					FROM deliverertranslation
					WHERE delivererid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'www' => $rs->getString('www'),
				'email' => $rs->getString('email')
			);
		}
		return $Data;
	}

	public function getProductsForDelilverer ($id)
	{
		$sql = "SELECT PT.name, PD.delivererid as id, P.idproduct
					FROM productdeliverer PD
					LEFT JOIN producttranslation PT ON PT.productid = PD.productid AND PT.languageid=:languageid
					LEFT JOIN product P ON P.idproduct = PD.productid
					WHERE delivererid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('idproduct');
		}
		return $Data;
	}

	public function getDelivererAll ()
	{
		$sql = 'SELECT D.iddeliverer as id, DT.name 
					FROM deliverer D
					LEFT JOIN deliverertranslation DT ON DT.delivererid = D.iddeliverer AND DT.languageid = :language';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getDelivererToSelect ()
	{
		$Data = $this->getDelivererAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function editDeliverer ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateDeliverer($Data, $id);
			$this->updateDelivererTranslation($Data, $id);
			$this->updateProductForDeliverer($Data['products'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_EDIT'), 10, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateDeliverer ($Data, $id)
	{
		$sql = 'UPDATE deliverer SET editid = :editid, photoid = :photo WHERE iddeliverer = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		if (($Data['photo']) > 0){
			$stmt->setInt('photo', $Data['photo']);
		}
		else{
			$stmt->setInt('photo', $this->registry->core->setDefaultPhoto());
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_EDIT'), 10, $e->getMessage());
		}
	}

	public function updateDelivererTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM deliverertranslation WHERE delivererid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO deliverertranslation (delivererid, name, www, email, languageid)
						VALUES (:delivererid, :name, :www, :email, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('delivererid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('www', $Data['www'][$key]);
			$stmt->setString('email', $Data['email'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_TRANSLATION_EDIT'), 10, $e->getMessage());
			}
		}
		return true;
	}

	public function updateProductForDeliverer ($Data, $id)
	{
		$sql = 'DELETE FROM productdeliverer WHERE delivererid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO productdeliverer (productid, delivererid, addid)
							VALUES (:productid, :delivererid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $value);
				$stmt->setInt('delivererid', $id);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
	}

	public function addNewDeliverer ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newDelivererId = $this->addDeliverer($Data);
			$this->addDelivererTranslation($Data, $newDelivererId);
			if (! empty($Data['products'])){
				$this->addProductForDeliverer($Data['products'], $newDelivererId);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addProductForDeliverer ($Data, $delivererId)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO productdeliverer (productid, delivererid, addid)
						VALUES (:productid, :delivererid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $value);
			$stmt->setInt('delivererid', $delivererId);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_PRODUCT_ADD'), 11, $e->getMessage());
			}
		}
	}

	public function addDeliverer ($Data)
	{
		$sql = 'INSERT INTO deliverer (photoid, addid) VALUES (:photoid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (($Data['photo'][0]) > 0){
			$stmt->setInt('photoid', $Data['photo']);
		}
		else{
			$stmt->setInt('photoid', $this->registry->core->setDefaultPhoto());
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_ADD'), 15, $e->getMessage());
		}
		
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addDelivererTranslation ($Data, $id)
	{
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO deliverertranslation (delivererid, name, www, email, languageid)
						VALUES (:delivererid, :name, :www, :email, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('delivererid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('www', $Data['www'][$key]);
			$stmt->setString('email', $Data['email'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_TRANSLATION_EDIT'), 10, $e->getMessage());
			}
		}
	}

	public function getPhotoDelivererById ($id)
	{
		$sql = 'SELECT photoid FROM deliverer WHERE iddeliverer=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data[] = $rs->getInt('photoid');
		}
		return $Data;
	}

	public function getPhotos (&$deliverer)
	{
		if (! is_array($deliverer)){
			throw new Exception('Wrong array given');
		}
		foreach ($deliverer['photo'] as $photo){
			if (isset($photo['photoid'])){
				$deliverer['photo']['small'][] = App::getModel('gallery')->getSmallImageById($photo['photoid']);
			}
		}
	}
}