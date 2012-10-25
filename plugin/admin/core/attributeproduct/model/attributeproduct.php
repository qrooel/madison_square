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
 * $Id: attributeproduct.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class AttributeProductModel extends ModelWithDatagrid
{
	
	protected $valuesMultiInput;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initValuesMultiInput ($multiinput)
	{
		if ((int) $this->registry->core->getParam()){
			$sql = 'SELECT idattributeproductvalue AS id, name
						FROM attributeproductvalue 
						WHERE attributeproductid = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', (int) $this->registry->core->getParam());
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'value' => $rs->getString('name')
				);
			}
			$multiinput->setValues($Data);
		}
	}

	public function getValuesMultiInputConfiguration ()
	{
		if (($this->valuesMultiInput == NULL) || ! ($this->valuesMultiInput instanceof MultiinputModel)){
			$this->valuesMultiInput = App::getModel('multiinput/multiinput');
			$this->initValuesMultiInput($this->valuesMultiInput);
		}
		return $this->valuesMultiInput;
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('attributeproduct', Array(
			'idattributeproduct' => Array(
				'source' => 'A.idattributeproduct'
			),
			'name' => Array(
				'source' => 'A.name',
				'prepareForAutosuggest' => true
			),
			'valuecount' => Array(
				'source' => 'COUNT(DISTINCT V.idattributeproductvalue)',
				'filter' => 'having'
			),
			'productcount' => Array(
				'source' => 'COUNT(DISTINCT P.productid)',
				'filter' => 'having'
			),
			'adddate' => Array(
				'source' => 'A.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'A.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`attributeproduct` A
				LEFT JOIN `attributeproductvalue` V ON A.idattributeproduct = V.attributeproductid
				LEFT JOIN (
					SELECT
						AP.idattributeproduct AS attributeproductid,
						PAS.productid AS productid
					FROM
						`productattributeset` PAS
						LEFT JOIN `productattributevalueset` PAVS ON PAVS.productattributesetid = PAS.idproductattributeset
						LEFT JOIN `attributeproductvalue` APV ON APV.idattributeproductvalue = PAVS.attributeproductvalueid
						RIGHT JOIN `attributeproduct` AP ON AP.idattributeproduct = APV.attributeproductid
				) P ON P.attributeproductid = A.idattributeproduct
				LEFT JOIN `user` UA ON A.addid = UA.iduser
				LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
				LEFT JOIN `user` UE ON A.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				A.idattributeproduct
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getAttributeProductsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteAttributeProducts ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteAttributeProducts'
		), $this->getName());
	}

	public function deleteAttributeProducts ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idattributeproduct' => $id
			), $this->getName(), 'deleteAttributeProducts');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addAttributeGroup ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$groupId = $this->addAttributeGroupName($Data['attributeproductgroupname']);
			$this->addAttributeGroupValues($Data['attributeproductvalues'], $groupId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_WHILE_ATTRIBUTE_PRODUCT_GROUP_ADD'), 114, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function addAttributeGroupName ($groupName)
	{
		$sql = 'INSERT INTO attributeproduct(name, addid) VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $groupName);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addAttributeGroupValues ($value, $groupId)
	{
		foreach ($value as $key){
			$sql = 'INSERT INTO attributeproductvalue(name, attributeproductid, addid) VALUES (:valuename, :productattrid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('valuename', $key);
			$stmt->setInt('productattrid', $groupId);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function getAttributeProductNames ()
	{
		$sql = 'SELECT idattributeproduct AS id, name FROM attributeproduct ORDER BY name';
		$rs = $this->registry->db->executeQuery($sql);
		return $rs->getAllRows();
	}

	public function getAttributeProductNamesByIds ($attributegroupnameid)
	{
		$sql = 'SELECT distinct AP.idattributeproduct as id, AP.name
    					FROM attributegroup AG
						LEFT JOIN attributeproduct AP ON AP.idattributeproduct = AG.attributeproductid
						WHERE attributegroupnameid=:attributegroupnameid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attributegroupnameid', $attributegroupnameid);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getAttributeProductValuesByAttributeGroupId ($id)
	{
		$sql = 'SELECT idattributeproductvalue AS id, name 
					FROM attributeproductvalue
					WHERE attributeproductid = :attrid ORDER BY name';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attrid', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getAttributeProductFull ()
	{
		$attrGroup = $this->getAttributeProductNames();
		$Data = Array();
		foreach ($attrGroup as $key => $value){
			$attrGroup[$key]['values'] = $this->getAttributeProductValuesByAttributeGroupId($value['id']);
		}
		return $attrGroup;
	}

	public function getAttributeProductNamesToSelect ()
	{
		$attr = $this->getAttributeProduct();
		$Data = Array();
		foreach ($attr as $value){
			$Data[$value['id']] = $value['name'];
		}
		return $Data;
	}

	public function getAttributeProductName ($id)
	{
		$sql = 'SELECT idattributeproduct AS id, name as attributeproductname
					FROM attributeproduct 
					WHERE idattributeproduct=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'attributeproductname' => $rs->getString('attributeproductname'),
				'attributes' => $this->getAttributeValues($id)
			)//					'category' => $this->getAttributeCategory($id)
			;
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_ATTRIBUTEGROUP_NO_EXIST'));
		}
		return $Data;
	}

	public function getAttributeCategory ($id)
	{
		$sql = 'SELECT categoryid 
					FROM categoryattributeproduct 
					WHERE attributeproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['categoryid'][] = $rs->getInt('categoryid');
		}
		return $Data;
	}

	public function getAttributeValues ($id)
	{
		$sql = 'SELECT idattributeproductvalue AS ids, name as attributesname, attributeproductid as id
					FROM attributeproductvalue 
					WHERE attributeproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('ids')] = $rs->getString('attributesname');
		}
		return $Data;
	}

	public function getAttributeName ($id)
	{
		$sql = 'SELECT attributeproductid AS id, name as attributesname
					FROM attributeproductvalue 
					WHERE attributeproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'attributesname' => $rs->getString('attributesname')
			);
		}
		return $Data;
	}

	public function updateAttribute ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->UpdateAttributeProductName($Data, $id);
			$this->updateAttributeValueName($Data['attributeproductvalues'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ATTRIBUTES_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function UpdateAttributeProductName ($Data, $id)
	{
		$sql = 'UPDATE attributeproduct SET name=:name WHERE idattributeproduct = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['attributeproductname']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
			return false;
		}
		return true;
	}

	public function updateAttributeValueName ($array, $id)
	{
		$sql = "SELECT idattributeproductvalue
					FROM attributeproductvalue
					WHERE attributeproductid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			(int) $idattribut = $rs->getInt('idattributeproductvalue');
			$Data[$idattribut] = $idattribut;
		}
		foreach ($array as $key => $oldid){
			if (is_int($key)){
				$sql = 'UPDATE attributeproductvalue 
							SET name=:name, editid=:editid
							WHERE idattributeproductvalue = :key';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('key', $key);
				$stmt->setString('name', $oldid);
				$stmt->setInt('editid', $this->registry->session->getActiveUserid());
				$stmt->executeUpdate();
				unset($Data[$key]);
			}
			else{
				$sql = 'INSERT INTO attributeproductvalue(name, attributeproductid, addid) VALUES (:name, :productattrid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', $oldid);
				$stmt->setInt('productattrid', $id);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->executeUpdate();
			}
		}
		foreach ($Data as $delete){
			$sqlDelete = 'DELETE FROM attributeproductvalue 
							WHERE idattributeproductvalue=:id';
			$stmt = $this->registry->db->prepareStatement($sqlDelete);
			$stmt->setInt('id', $delete);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function getAttributeNameById ($id)
	{
		$sql = 'SELECT name FROM attributeproductvalue WHERE idattributeproductvalue = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
			$rs->first();
			return $rs->getString('name');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getAttributeNamesDistinctByArrayId ($Data)
	{
		$sql = 'SELECT DISTINCT name FROM attributeproductvalue 
			WHERE idattributeproductvalue IN (:id)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInInt('id', $Data);
		$Attributes = Array();
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Execute $e){
			throw new Exception($e->getMessage());
		}
		while ($rs->next()){
			$Attributes[] = $rs->getString('name');
		}
		return $Attributes;
	}

	//		Czy to jest potrzebne ?
	public function updateAttributeGroupName ($Data, $id)
	{
		$sql = 'UPDATE attributegroupname SET name=:name, editid=:editid WHERE idattributegroupname = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ATTRIBUTE_GROUP_UPDATE'), 1, $e->getMessage());
			return false;
		}
		return true;
	}

	//		Czy to jest potrzebne ?
	public function editAttributeCategory ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM categoryattributeproduct WHERE attributeproductid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($Data as $key => $categoryid){
			$sql = 'INSERT INTO categoryattributeproduct(categoryid, attributeproductid, addid) VALUES (:categoryid, :attributeproductid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $categoryid);
			$stmt->setInt('attributeproductid', $id);
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