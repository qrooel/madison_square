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
 * $Id: rangetype.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class rangetypeModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('rangetype', Array(
			'idrangetype' => Array(
				'source' => 'RT.idrangetype'
			),
			'name' => Array(
				'source' => 'RTT.name',
				'prepareForSelect' => true
			),
			'categoryname' => Array(
				'source' => 'CT.name'
			),
			'categoryid' => Array(
				'source' => 'RTC.categoryid',
				'prepareForTree' => true,
				'first_level' => App::getModel('product')->getCategories()
			),
			'ancestorcategoryid' => Array(
				'source' => 'CP.ancestorcategoryid'
			),
			'categoriesname' => Array(
				'source' => 'GROUP_CONCAT(DISTINCT SUBSTRING(CONCAT(\' \', CT.name), 1))',
				'filter' => 'having'
			)
		));
		$datagrid->setFrom('
				rangetype RT
				LEFT JOIN rangetypecategory RTC ON RTC.rangetypeid = RT.idrangetype
				LEFT JOIN rangetypetranslation RTT ON RTT.rangetypeid = RT.idrangetype AND RTT.languageid = :languageid
				LEFT JOIN category C ON C.idcategory = RTC.categoryid
				LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
				LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
			');
		$datagrid->setGroupBy('
				RT.idrangetype
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getRangeTypeForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteRangeType ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteRangeType'
		), $this->getName());
	}

	public function deleteRangeType ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idrangetype' => $id
			), $this->getName(), 'deleteRangeType');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getRangeTypeView ($id)
	{
		$sql = "SELECT idrangetype as id
					FROM rangetype
					WHERE idrangetype=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data = Array(
				'language' => $this->getRangeTypeTranslation($id),
				'rangetypecategorys' => $this->RangeTypeCategoryIds($id)
			);
		}
		return $Data;
	}

	public function getRangeTypeTranslation ($id)
	{
		$sql = "SELECT name, languageid
					FROM rangetypetranslation
					WHERE rangetypeid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function RangeTypeCategory ($id)
	{
		$sql = 'SELECT categoryid as id
					FROM rangetypecategory
					WHERE rangetypeid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function RangeTypeCategoryIds ($id)
	{
		$Data = $this->RangeTypeCategory($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function addNewRangeType ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newRangeTypeId = $this->addRangeType($Data);
			$this->addRangeTypeCategory($Data['category'], $newRangeTypeId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEW_RANGETYPE_ADD'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addRangeTypeCategory ($array, $RangeTypeId)
	{
		foreach ($array as $key => $value){
			$sql = 'INSERT INTO rangetypecategory (rangetypeid, categoryid, addid)
						VALUES (:rangetypeid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('rangetypeid', $RangeTypeId);
			$stmt->setInt('categoryid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addRangeType ($Data)
	{
		$sql = 'INSERT INTO rangetype (addid) VALUES (:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEW_RANGE_TYPE_ADD'), 15, $e->getMessage());
		}
		
		$rangetypeid = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO rangetypetranslation SET
						rangetypeid = :rangetypeid,
						name = :name, 
						languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('rangetypeid', $rangetypeid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_RANGETYPE_TRANSLATION_EDIT'), 4, $e->getMessage());
			}
		}
		
		return $rangetypeid;
	}

	public function editRangeType ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->editRangeTypeName($Data, $id);
			$this->editRangeTypCategory($Data['category'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_RANGETYPE_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function editRangeTypeName ($Data, $id)
	{
		
		$sql = 'DELETE FROM rangetypetranslation WHERE rangetypeid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			return false;
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO rangetypetranslation SET
						rangetypeid = :rangetypeid,
						name = :name, 
						languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('rangetypeid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_RANGETYPE_TRANSLATION_EDIT'), 4, $e->getMessage());
			}
		}
	
	}

	public function editRangeTypCategory ($array, $id)
	{
		$sqlDelete = 'DELETE FROM rangetypecategory WHERE rangetypeid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($array as $key => $value){
			$sql = 'INSERT INTO rangetypecategory (rangetypeid, categoryid, addid)
						VALUES (:rangetypeid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('rangetypeid', $id);
			$stmt->setInt('categoryid', $value);
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