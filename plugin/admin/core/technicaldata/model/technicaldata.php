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
 * $Id: technicaldata.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class TechnicalDataModel extends Model
{
	
	const FIELD_STRING = 0;
	const FIELD_MULTILINGUAL_STRING = 1;
	const FIELD_TEXT = 2;
	const FIELD_IMAGE = 3;
	const FIELD_BOOLEAN = 4;
	
	protected $languages;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
		$this->languages = App::getModel('Language')->getLanguageALLToSelect();
	}

	protected function getLanguageColumnString ($table, $valueColumnName = 'name')
	{
		$languageColumnName = 'languageid';
		$columns = Array();
		foreach ($this->languages as $languageId => $languageName){
			$columns[] = "GROUP_CONCAT(DISTINCT IF({$table}translation.{$languageColumnName} = {$languageId}, {$table}translation.{$valueColumnName}, '') SEPARATOR '') AS `{$valueColumnName}_{$languageId}`";
		}
		return implode(", ", $columns);
	}

	public function GetSets ($productId, $categoryIds)
	{
		$sql = 'SELECT
						TS.idtechnicaldataset AS id,
						TS.name AS caption
					FROM
						technicaldataset TS
					ORDER BY
						TS.name ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function GetSetData ($setId)
	{
		$sql = 'SELECT
						TG.idtechnicaldatagroup AS id,
						' . $this->getLanguageColumnString('technicaldatagroup') . '
					FROM
						technicaldatagroup TG
						LEFT JOIN technicaldatagrouptranslation ON technicaldatagrouptranslation.technicaldatagroupid = TG.idtechnicaldatagroup
						LEFT JOIN technicaldatasetgroup TSG ON TG.idtechnicaldatagroup = TSG.technicaldatagroupid
					WHERE
						TSG.technicaldatasetid = :setId
					GROUP BY
						TG.idtechnicaldatagroup
					ORDER BY
						TSG.order ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('setId', $setId);
		$rs = $stmt->executeQuery();
		$groups = Array();
		$groupIndices = Array();
		while ($rs->next()){
			$groupIndices[] = $rs->getInt('id');
			$captions = Array();
			foreach ($this->languages as $languageId => $languageName){
				$captions[$languageId] = $rs->getString('name_' . $languageId);
			}
			$groups[] = Array(
				'id' => $rs->getInt('id'),
				'caption' => $captions,
				'children' => Array()
			);
		}
		if (count($groups)){
			$sql = 'SELECT
							TA.idtechnicaldataattribute AS id,
							TA.type AS type,
							TSG.technicaldatagroupid AS group_id,
							' . $this->getLanguageColumnString('technicaldataattribute') . '
						FROM
							technicaldataattribute TA
							LEFT JOIN technicaldataattributetranslation ON technicaldataattributetranslation.technicaldataattributeid = TA.idtechnicaldataattribute
							LEFT JOIN technicaldatasetgroupattribute TGA ON TA.idtechnicaldataattribute = TGA.technicaldataattributeid
							LEFT JOIN technicaldatasetgroup TSG ON TGA.technicaldatasetgroupid = TSG.idtechnicaldatasetgroup
						WHERE
							TSG.technicaldatagroupid IN (:groupIds)
							AND TSG.technicaldatasetid = :setId
						GROUP BY
							TA.idtechnicaldataattribute
						ORDER BY
							TSG.order ASC,
							TGA.order ASC';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setINInt('groupIds', $groupIndices);
			$stmt->setInt('setId', $setId);
			$rs = $stmt->executeQuery();
			$groupIndex = 0;
			while ($rs->next()){
				$currentGroupIndex = $rs->getInt('group_id');
				if ($currentGroupIndex != $groups[$groupIndex]['id']){
					if ($currentGroupIndex != $groups[++ $groupIndex]['id']){
						throw new CoreException('Something\'s wrong with the technical data indices...');
					}
				}
				$captions = Array();
				foreach ($this->languages as $languageId => $languageName){
					$captions[$languageId] = $rs->getString('name_' . $languageId);
				}
				$groups[$groupIndex]['children'][] = Array(
					'id' => $rs->getInt('id'),
					'type' => $rs->getInt('type'),
					'caption' => $captions
				);
			}
		}
		return $groups;
	}

	public function SaveSet ($setId, $setName, $setData)
	{
		$this->registry->db->setAutoCommit(false);
		if ($setId == 'new'){
			$sql = 'INSERT INTO technicaldataset SET name = :name, addid = :addid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $setName);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
			$setId = $stmt->getConnection()->getIdGenerator()->getId();
		}
		else{
			$this->deleteSetData($setId);
		}
		foreach ($setData as $groupOrder => $group){
			if (substr($group['id'], 0, 3) == 'new'){
				$group['id'] = $this->SaveGroup('new', $group['caption']);
			}
			$sql = 'INSERT INTO
							technicaldatasetgroup
						SET
							technicaldatasetid = :setId,
							technicaldatagroupid = :groupId,
							`order` = :order';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('setId', $setId);
			$stmt->setInt('groupId', $group['id']);
			$stmt->setInt('order', $groupOrder);
			$stmt->executeUpdate();
			$setGroupId = $stmt->getConnection()->getIdGenerator()->getId();
			if (isset($group['children']) and is_array($group['children'])){
				foreach ($group['children'] as $attributeOrder => $attribute){
					if (substr($attribute['id'], 0, 3) == 'new'){
						$attribute['id'] = $this->SaveAttribute('new', $attribute['caption'], $attribute['type']);
					}
					$sql = 'INSERT INTO
									technicaldatasetgroupattribute
								SET
									technicaldatasetgroupid = :setGroupId,
									technicaldataattributeid = :attributeId,
									`order` = :order';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('setGroupId', $setGroupId);
					$stmt->setInt('attributeId', $attribute['id']);
					$stmt->setInt('order', $attributeOrder);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $setId;
	}

	public function DeleteSet ($setId)
	{
		$sql = 'DELETE FROM technicaldataset WHERE idtechnicaldataset = :setId';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('setId', $setId);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	protected function deleteSetData ($setId)
	{
		$sql = 'DELETE FROM technicaldatasetgroup WHERE technicaldatasetid = :setId';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('setId', $setId);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function GetGroups ()
	{
		$sql = 'SELECT
						TG.idtechnicaldatagroup AS id,
						' . $this->getLanguageColumnString('technicaldatagroup') . '
					FROM
						technicaldatagroup TG
						LEFT JOIN technicaldatagrouptranslation ON technicaldatagrouptranslation.technicaldatagroupid = TG.idtechnicaldatagroup
					GROUP BY
						TG.idtechnicaldatagroup';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$groups = Array();
		while ($rs->next()){
			$captions = Array();
			foreach ($this->languages as $languageId => $languageName){
				$captions[$languageId] = $rs->getString('name_' . $languageId);
			}
			$groups[] = Array(
				'id' => $rs->getInt('id'),
				'caption' => $captions
			);
		}
		return $groups;
	}

	public function SaveGroup ($groupId, $groupName)
	{
		if (substr($groupId, 0, 3) == 'new'){
			$sql = 'INSERT INTO technicaldatagroup SET addid = :addId';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addId', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
			$groupId = $stmt->getConnection()->getIdGenerator()->getId();
		}
		else{
			$sql = 'DELETE FROM technicaldatagrouptranslation WHERE technicaldatagroupid = :groupId';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('groupId', $groupId);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		foreach ($groupName as $languageId => $name){
			$sql = 'INSERT INTO
							technicaldatagrouptranslation
						SET
							technicaldatagroupid = :groupId,
							languageid = :languageId,
							name = :name';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('groupId', $groupId);
			$stmt->setInt('languageId', $languageId);
			$stmt->setString('name', $name);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		return $groupId;
	}

	public function DeleteGroup ($groupId)
	{
		$sql = 'DELETE FROM technicaldatagroup WHERE idtechnicaldatagroup = :groupId';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('groupId', $groupId);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function GetAttributes ()
	{
		$sql = 'SELECT
						TA.idtechnicaldataattribute AS id,
						TA.type AS type,
						' . $this->getLanguageColumnString('technicaldataattribute') . '
					FROM
						technicaldataattribute TA
						LEFT JOIN technicaldataattributetranslation ON technicaldataattributetranslation.technicaldataattributeid = TA.idtechnicaldataattribute
					GROUP BY
						TA.idtechnicaldataattribute';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$attributes = Array();
		while ($rs->next()){
			$captions = Array();
			foreach ($this->languages as $languageId => $languageName){
				$captions[$languageId] = $rs->getString('name_' . $languageId);
			}
			$attributes[] = Array(
				'id' => $rs->getInt('id'),
				'caption' => $captions,
				'type' => $rs->getInt('type')
			);
		}
		return $attributes;
	}

	public function SaveAttribute ($attributeId, $attributeName, $attributeType)
	{
		if (substr($attributeId, 0, 3) == 'new'){
			$sql = 'INSERT INTO technicaldataattribute SET type = :type, addid = :addId';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('type', $attributeType);
			$stmt->setInt('addId', $this->registry->session->getActiveUserid());
			$stmt->executeUpdate();
			$attributeId = $stmt->getConnection()->getIdGenerator()->getId();
		}
		else{
			$sql = 'DELETE FROM technicaldataattributetranslation WHERE technicaldataattributeid = :attributeId';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('attributeId', $attributeId);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		
		foreach ($attributeName as $languageId => $name){
			$sql = 'INSERT INTO
							technicaldataattributetranslation
						SET
							technicaldataattributeid = :attributeId,
							languageid = :languageId,
							name = :name';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('attributeId', $attributeId);
			$stmt->setInt('languageId', $languageId);
			$stmt->setString('name', $name);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		return $attributeId;
	}

	public function DeleteAttribute ($attributeId)
	{
		$sql = 'DELETE FROM technicaldataattribute WHERE idtechnicaldataattribute = :attributeId';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attributeId', $attributeId);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function GetValuesForProduct ($productId)
	{
		$sql = 'SELECT
						TG.idtechnicaldatagroup AS id,
						' . $this->getLanguageColumnString('technicaldatagroup') . '
					FROM
						technicaldatagroup TG
						LEFT JOIN technicaldatagrouptranslation ON technicaldatagrouptranslation.technicaldatagroupid = TG.idtechnicaldatagroup
						LEFT JOIN producttechnicaldatagroup TSG ON TG.idtechnicaldatagroup = TSG.technicaldatagroupid
					WHERE
						TSG.productid = :productId
					GROUP BY
						TG.idtechnicaldatagroup
					ORDER BY
						TSG.order ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productId', $productId);
		$rs = $stmt->executeQuery();
		$groups = Array();
		$groupIndices = Array();
		while ($rs->next()){
			$groupIndices[] = $rs->getInt('id');
			$captions = Array();
			foreach ($this->languages as $languageId => $languageName){
				$captions[$languageId] = $rs->getString('name_' . $languageId);
			}
			$groups[] = Array(
				'id' => $rs->getInt('id'),
				'caption' => $captions,
				'children' => Array()
			);
		}
		if (count($groups)){
			$sql = 'SELECT
							TA.idtechnicaldataattribute AS id,
							TA.type AS type,
							TGA.value AS value,
							TSG.technicaldatagroupid AS group_id,
							' . $this->getLanguageColumnString('technicaldataattribute') . ',
							' . $this->getLanguageColumnString('producttechnicaldatagroupattribute', 'value') . '
						FROM
							technicaldataattribute TA
							LEFT JOIN technicaldataattributetranslation ON technicaldataattributetranslation.technicaldataattributeid = TA.idtechnicaldataattribute
							LEFT JOIN producttechnicaldatagroupattribute TGA ON TA.idtechnicaldataattribute = TGA.technicaldataattributeid
							LEFT JOIN producttechnicaldatagroupattributetranslation ON producttechnicaldatagroupattributetranslation.producttechnicaldatagroupattributeid = TGA.idproducttechnicaldatagroupattribute
							LEFT JOIN producttechnicaldatagroup TSG ON TGA.producttechnicaldatagroupid = TSG.idproducttechnicaldatagroup
						WHERE
							TSG.productid = :productId
						GROUP BY
							TA.idtechnicaldataattribute,
							TGA.idproducttechnicaldatagroupattribute
						ORDER BY
							TSG.order ASC,
							TGA.order ASC';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productId', $productId);
			$rs = $stmt->executeQuery();
			$groupIndex = 0;
			while ($rs->next()){
				$currentGroupIndex = $rs->getInt('group_id');
				if ($currentGroupIndex != $groups[$groupIndex]['id']){
					if ($currentGroupIndex != $groups[++ $groupIndex]['id']){
						throw new CoreException('Something\'s wrong with the technical data indices...');
					}
				}
				$captions = Array();
				foreach ($this->languages as $languageId => $languageName){
					$captions[$languageId] = $rs->getString('name_' . $languageId);
				}
				$type = $rs->getInt('type');
				switch ($type) {
					case self::FIELD_MULTILINGUAL_STRING:
						$value = Array();
						foreach ($this->languages as $languageId => $languageName){
							$value[$languageId] = $rs->getString('value_' . $languageId);
						}
						break;
					default:
						$value = $rs->getString('value');
				}
				$groups[$groupIndex]['children'][] = Array(
					'id' => $rs->getInt('id'),
					'type' => $type,
					'value' => $value,
					'caption' => $captions
				);
			}
		}
		return $groups;
	}

	public function SaveValuesForProduct ($productId, $productData)
	{
		try{
			$this->DeleteValuesForProduct($productId);
			if (! isset($productData['groups']) or ! is_array($productData['groups'])){
				return;
			}
			foreach ($productData['groups'] as $groupOrder => $group){
				if (substr($group['id'], 0, 3) == 'new'){
					$group['id'] = $this->SaveGroup('new', $group['caption']);
				}
				$sql = 'INSERT INTO
								producttechnicaldatagroup
							SET
								productid = :productId,
								technicaldatagroupid = :groupId,
								`order` = :order';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productId', $productId);
				$stmt->setInt('groupId', $group['id']);
				$stmt->setInt('order', $groupOrder);
				$stmt->executeUpdate();
				$productGroupId = $stmt->getConnection()->getIdGenerator()->getId();
				if (isset($group['attributes']) and is_array($group['attributes'])){
					foreach ($group['attributes'] as $attributeOrder => $attribute){
						if (substr($attribute['id'], 0, 3) == 'new'){
							$attribute['id'] = $this->SaveAttribute('new', $attribute['caption'], $attribute['type']);
						}
						$sql = 'INSERT INTO
										producttechnicaldatagroupattribute
									SET
										producttechnicaldatagroupid = :productGroupId,
										technicaldataattributeid = :attributeId,
										`order` = :order,
										value = :value';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('productGroupId', $productGroupId);
						$stmt->setInt('attributeId', $attribute['id']);
						$stmt->setInt('order', $attributeOrder);
						switch ($attribute['type']) {
							case self::FIELD_STRING:
								$stmt->setString('value', $attribute['value']);
								break;
							case self::FIELD_BOOLEAN:
								$stmt->setString('value', $attribute['value'] ? '1' : '0');
								break;
							default:
								$stmt->setString('value', '');
						}
						$stmt->executeUpdate();
						$productGroupAttributeId = $stmt->getConnection()->getIdGenerator()->getId();
						switch ($attribute['type']) {
							case self::FIELD_MULTILINGUAL_STRING:
								if (! is_array($attribute['value'])){
									break;
								}
								foreach ($attribute['value'] as $languageId => $value){
									$sql = 'INSERT INTO
													producttechnicaldatagroupattributetranslation
												SET
													producttechnicaldatagroupattributeid = :productGroupAttributeId,
													languageid = :languageId,
													value = :value';
									$stmt = $this->registry->db->prepareStatement($sql);
									$stmt->setInt('productGroupAttributeId', $productGroupAttributeId);
									$stmt->setInt('languageId', $languageId);
									$stmt->setString('value', $value);
									$stmt->executeUpdate();
								}
								break;
						}
					}
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_TECHNICAL_DATA_ADD'), 112, $e->getMessage());
		}
	}

	public function DeleteValuesForProduct ($productId)
	{
		$sql = 'DELETE FROM producttechnicaldatagroup WHERE productid = :productId';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productId', $productId);
		$stmt->executeUpdate();
	}

}