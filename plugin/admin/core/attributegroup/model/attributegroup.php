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
 * $Id: attributegroup.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class attributegroupModel extends Model
{

	public function getAttributesForGroup ($groupId)
	{
		$attributes = App::getModel('attributeproduct/attributeproduct')->getAttributeProductNamesByIds($groupId);
		foreach ($attributes as &$attribute){
			$attribute['values'] = App::getModel('attributeproduct/attributeproduct')->getAttributeProductValuesByAttributeGroupId($attribute['id']);
		}
		return $attributes;
	}

	public function getGroupsForCategory ($categoryIds)
	{
		$Data = Array();
		if (! isset($categoryIds) || ! is_array($categoryIds) || ! count($categoryIds)){
			$categoryIds = Array(
				0
			);
		}
		$inArray = Array();
		foreach ($categoryIds as $i => $categoryId){
			$inArray[] = ':categoryId' . $i;
		}
		$sql = 'SELECT DISTINCT
						AG.attributegroupnameid AS id,
						AGN.name AS name,
						categoryid IN (' . implode(', ', $inArray) . ') AS current_category
					FROM
						attributegroup AG
						LEFT JOIN categoryattributeproduct CAP ON CAP.attributeproductid = AG.attributeproductid
						LEFT JOIN attributegroupname AGN ON AGN.idattributegroupname = AG.attributegroupnameid
					GROUP BY id
					ORDER BY current_category DESC';
		$stmt = $this->registry->db->prepareStatement($sql);
		foreach ($categoryIds as $i => $categoryId){
			$stmt->setInt('categoryId' . $i, $categoryId);
		}
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'current_category' => ($rs->getInt('current_category') ? true : false)
			);
		}
		return $Data;
	}

	public function getSugestVariant ($id)
	{
		$sql = 'SELECT attributegroupnameid AS sets
					FROM productattributeset 
					WHERE productid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'sets' => $rs->getInt('sets')
			);
		}
		return $Data;
	}

	public function getGroup ($idattributegroupname)
	{
		$sql = 'SELECT idattributegroupname AS id, name
					FROM attributegroupname 
					WHERE idattributegroupname=:idattributegroupname';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idattributegroupname', $idattributegroupname);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'attributes' => $this->getAllAttributeGroup($idattributegroupname)
			);
			$Data['category'] = $this->getAllCategoryAttributeProduct($Data['attributes']);
		}
		return $Data;
	}

	public function getAllAttributeGroup ($idattributegroupname)
	{
		$sql = 'SELECT attributeproductid
					FROM attributegroup 
					WHERE attributegroupnameid=:idattributegroupname';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idattributegroupname', $idattributegroupname);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getString('attributeproductid');
		}
		return $Data;
	}

	public function getAllAttributeGroupToSelect ($id)
	{
		$sql = 'SELECT attributeproductid
					FROM attributegroup
					WHERE attributegroupnameid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getString('attributeproductid');
		}
		return $Data;
	}

	public function getAllCategoryAttributeProduct ($attributes)
	{
		$Data = Array();
		if (count($attributes) > 0){
			$sql = 'SELECT DISTINCT categoryid
					FROM categoryattributeproduct 
					WHERE attributeproductid IN(:ids)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setINInt('ids', $attributes);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getInt('categoryid');
			}
		}
		return $Data;
	}

	public function getAllAttributeGroupName ()
	{
		$sql = 'SELECT idattributegroupname as id, name FROM attributegroupname';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function addEmptyGroup ($request)
	{
		if (! isset($request['name']) || ! strlen($request['name'])){
			$autoNameBase = $this->registry->core->getMessage('TXT_NEW_ATTRIBUTE_GROUP');
			$sql = "SELECT name FROM attributegroupname WHERE name LIKE :pattern";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('pattern', $autoNameBase . '%');
			$rs = $stmt->executeQuery();
			$existingNames = Array();
			while ($rs->next()){
				$existingNames[] = $rs->getString('name');
			}
			$i = 1;
			do{
				$nameAlreadyExists = false;
				$autoName = $autoNameBase . (($i > 1) ? ' ' . $i : '');
				foreach ($existingNames as $name){
					if ($name == $autoName){
						$nameAlreadyExists = true;
						break;
					}
				}
				$i ++;
			}
			while ($nameAlreadyExists);
			$request['name'] = $autoName;
		}
		$sql = 'INSERT INTO attributegroupname(name, addid) VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $request['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return Array(
			'id' => $stmt->getConnection()->getIdGenerator()->getId()
		);
	}

	public function deleteGroup ($request)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idattributegroupname' => $request
			), $this->getName(), 'deleteGroup');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function editAttributeGroup ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$checkValue = $this->deleteValue($Data['attributes']['editor']);
			$this->updateAttributeGroupName($Data, $id);
			$this->UpdateAttributeGroup($Data, $id);
			$allAttribute = $this->getAllAttributeGroupToSelect($id);
			if (is_array($Data['category'])){
				$this->updateCategoryAttributeProduct($Data, $allAttribute, $id);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ATTRIBUTES_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function deleteValue ($Data)
	{
		foreach ($Data as $parent){
			$sql = 'SELECT attributeproductid, idattributeproductvalue 
						FROM attributeproductvalue 
						WHERE attributeproductid=:atrid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('atrid', $parent['id']);
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$idattr = $rs->getInt('idattributeproductvalue');
				$Data[$idattr] = $rs->getInt('idattributeproductvalue');
			}
			foreach ($parent['values'] as $children){
				if (in_array($children['id'], $Data)){
					unset($Data[$children['id']]);
				}
			}
			foreach ($Data as $value){
				$sqlDelete = 'DELETE FROM productattributevalueset WHERE attributeproductvalueid = :attrvalueid ';
				$stmt = $this->registry->db->prepareStatement($sqlDelete);
				$stmt->setInt('attrvalueid', $value);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
				
				$sqlDelete = 'DELETE FROM attributeproductvalue WHERE attributeproductid=:atrid AND idattributeproductvalue=:attrvalueid ';
				$stmt = $this->registry->db->prepareStatement($sqlDelete);
				$stmt->setInt('atrid', $parent['id']);
				$stmt->setInt('attrvalueid', $value);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
	}

	public function addAttributeProductValues ($value, $productattrid)
	{
		foreach ($value as $key){
			$sql = 'INSERT INTO attributeproductvalue(name, attributeproductid, addid) 
						VALUES (:name, :productattrid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $key);
			$stmt->setInt('productattrid', $productattrid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function updateAttributeGroupName ($Data, $id)
	{
		$sql = 'UPDATE attributegroupname 
					SET name=:name, editid=:editid 
					WHERE idattributegroupname = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['attributegroupname']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ATTRIBUTE_GROUP_UPDATE'), 1, $e->getMessage());
			return false;
		}
		return true;
	}

	public function RenameAttribute ($attributeId, $newName)
	{
		$sql = 'UPDATE attributeproduct SET name = :name WHERE idattributeproduct = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $attributeId);
		$stmt->setString('name', $newName);
		$stmt->executeQuery();
	}

	public function RenameValue ($attributeId, $newName)
	{
		$sql = 'UPDATE attributeproductvalue SET name = :name WHERE idattributeproductvalue = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $attributeId);
		$stmt->setString('name', $newName);
		$stmt->executeQuery();
	}

	public function DeleteAttribute ($attributeId)
	{
		$sql = 'DELETE FROM attributeproductvalue WHERE attributeproductid =	:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $attributeId);
		$stmt->executeQuery();
		$sql = 'DELETE FROM attributeproduct WHERE idattributeproduct =	:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $attributeId);
		$stmt->executeQuery();
	}

	public function RemoveAttributeFromGroup ($attributeId, $groupId)
	{
		$sql = 'DELETE FROM attributegroup 
					WHERE attributegroupnameid = :attributegroupnameid AND attributeproductid = :attributeproductid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attributegroupnameid', $groupId);
		$stmt->setInt('attributeproductid', $attributeId);
		$stmt->executeQuery();
	}

	public function UpdateAttributeGroup ($Data, $attributegroupnameid)
	{
		$sqlDelete = 'DELETE FROM attributegroup WHERE attributegroupnameid=:attributegroupnameid';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('attributegroupnameid', $attributegroupnameid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (isset($Data['attributes'])){
			foreach ($Data['attributes']['editor'] as $key => $attributeproductid){
				$checkid = substr($attributeproductid['id'], 0, 3);
				if ($checkid != 'new'){
					//					Dodawanie istniejacego zestwu cech + warianty
					$sql = 'INSERT INTO attributegroup(attributegroupnameid, attributeproductid, addid) 
								VALUES (:attributegroupnameid, :attributeproductid, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('attributegroupnameid', $attributegroupnameid);
					$stmt->setInt('attributeproductid', $attributeproductid['id']);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
						foreach ($attributeproductid['values'] as $key => $valueid){
							$checknewid = substr($valueid['id'], 0, 3);
							if ($checknewid == 'new'){
								$sql = 'INSERT INTO attributeproductvalue(name, attributeproductid, addid) 
											VALUES (:name, :attributeproductid, :addid)';
								$stmt = $this->registry->db->prepareStatement($sql);
								$stmt->setString('name', $valueid['name']);
								$stmt->setInt('attributeproductid', $attributeproductid['id']);
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
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
				else{
					//						Dodawanie nowego zestwu cech + warianty
					$attributeproductids = $this->addNewAttributeProduct($attributeproductid['name']);
					$sql = 'INSERT INTO attributegroup(attributegroupnameid, attributeproductid, addid) 
								VALUES (:attributegroupnameid, :attributeproductid, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('attributegroupnameid', $attributegroupnameid);
					$stmt->setInt('attributeproductid', $attributeproductids);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
						foreach ($attributeproductid['values'] as $key => $valueid){
							$sql = 'INSERT INTO attributeproductvalue(name, attributeproductid, addid) VALUES (:name, :attributeproductid, :addid)';
							$stmt = $this->registry->db->prepareStatement($sql);
							$stmt->setString('name', $valueid['name']);
							$stmt->setInt('attributeproductid', $attributeproductids);
							$stmt->setInt('addid', $this->registry->session->getActiveUserid());
							try{
								$stmt->executeUpdate();
							}
							catch (Exception $e){
								throw new Exception($e->getMessage());
							}
						}
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
		}
	}

	public function updateCategoryAttributeProduct ($Data, $attr, $id)
	{
		foreach ($attr as $attrid){
			if (! is_array($attrid)){
				$sqlDelete = 'DELETE FROM categoryattributeproduct WHERE attributeproductid=:attrid';
				$stmt = $this->registry->db->prepareStatement($sqlDelete);
				$stmt->setInt('attrid', $attrid);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
				if (isset($Data['category'])){
					foreach ($Data['category'] as $key => $catid){
						$sql = 'INSERT INTO categoryattributeproduct(categoryid, attributeproductid, attributegroupnameid, addid) VALUES (:categoryid, :attributeproductid, :attributegroupnameid, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('categoryid', $catid);
						$stmt->setInt('attributeproductid', $attrid);
						$stmt->setInt('attributegroupnameid', $id);
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
		}
	}

	public function addNewAttributeProduct ($attribute)
	{
		$sql = 'INSERT INTO attributeproduct(name, addid) VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $attribute);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addAttributeToGroup ($attributeId, $groupId)
	{
		$sql = 'INSERT INTO attributegroup(attributegroupnameid, attributeproductid, addid)
					VALUES (:attributegroupnameid, :attributeproductid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attributegroupnameid', $attributeId);
		$stmt->setInt('attributeproductid', $groupId);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_CATEGORY_ATTRIBUTEPRODUCT_ADD'));
		}
	}
}