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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: staticattribute.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class StaticAttributeModel extends ModelWithDatagrid
{
	
	protected $valuesMultiInput;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('staticgroup', Array(
			'idstaticgroup' => Array(
				'source' => 'SG.idstaticgroup'
			),
			'name' => Array(
				'source' => 'SGT.name',
				'prepareForAutosuggest' => true
			)
		));
		$datagrid->setFrom('
			`staticgroup` SG
			LEFT JOIN `staticgrouptranslation` SGT ON SGT.staticgroupid = SG.idstaticgroup AND SGT.languageid = :languageid
		');
		
		$datagrid->setGroupBy('
			SG.idstaticgroup
		');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getStaticAttributesForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteStaticAttributes ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteStaticAttributes'
		), $this->getName());
	}

	public function deleteStaticAttributes ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idstaticgroup' => $id
			), $this->getName(), 'deleteStaticAttributes');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addAttributeGroup ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$groupId = $this->addAttributeGroupName($Data['required_data']['language_data']['staticattributegroupname']);
			$this->updateAttributeGroupValues($Data['attributes_data']['language_data'], $groupId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_WHILE_ATTRIBUTE_PRODUCT_GROUP_ADD'), 114, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function addAttributeGroupName ($Data)
	{
		$sql = 'INSERT INTO staticgroup (addid) VALUES (:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$id = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($Data as $languageid => $name){
			$sql = 'INSERT INTO staticgrouptranslation SET
						staticgroupid = :staticgroupid,
						name = :name,
						languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticgroupid', $id);
			$stmt->setString('name', $name);
			$stmt->setInt('languageid', $languageid);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
		}
		return $id;
	}

	public function getStaticAttributeFull ()
	{
		$sql = 'SELECT 
					staticgroupid, 
					languageid, 
					name
				FROM staticgrouptranslation
				WHERE languageid = :languageid
				GROUP BY staticgroupid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			
			$attributes = $this->getAttributesView($rs->getInt('staticgroupid'));
			
			$Data['groups'][$rs->getInt('staticgroupid')] = Array(
				'name' => $rs->getString('name'),
				'attributes' => $attributes['name_' . Helper::getLanguageId()]
			);
		}
		return $Data;
	}

	public function getStaticGroupView ($id)
	{
		
		$Data = Array(
			'language' => $this->getStaticAttributeName($id),
			'attributes' => $this->getAttributesView($id)
		);
		return $Data;
	}

	public function getAttributesView ($id)
	{
		$sql = "SELECT 
					SA.idstaticattribute,
					SAT.name, 
					SAT.description,
					SAT.file,
					SAT.languageid
				FROM staticattributetranslation SAT
				LEFT JOIN staticattribute SA ON SAT.staticattributeid = SA.idstaticattribute
				WHERE SA.staticgroupid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['name_' . $rs->getInt('languageid')][$rs->getInt('idstaticattribute')] = $rs->getString('name');
			$Data['description_' . $rs->getInt('languageid')][$rs->getInt('idstaticattribute')] = $rs->getString('description');
			$Data['files_' . $rs->getInt('languageid')][$rs->getInt('idstaticattribute')]['file'] = $rs->getString('file');
		}
		return $Data;
	}

	public function getStaticAttributeName ($id)
	{
		$sql = 'SELECT 
					staticgroupid, 
					languageid, 
					name
				FROM staticgrouptranslation
				WHERE staticgroupid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'staticattributegroupname' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function updateAttribute ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateStaticAttributeName($Data['required_data']['language_data']['staticattributegroupname'], $id);
			$this->updateAttributeGroupValues($Data['attributes_data']['language_data'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ATTRIBUTES_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateStaticAttributeName ($Data, $id)
	{
		
		$sql = 'DELETE FROM staticgrouptranslation WHERE staticgroupid = :staticgroupid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('staticgroupid', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data as $languageid => $name){
			$sql = 'INSERT INTO staticgrouptranslation SET
						staticgroupid = :staticgroupid,
						name = :name,
						languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticgroupid', $id);
			$stmt->setString('name', $name);
			$stmt->setInt('languageid', $languageid);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
		}
	}

	public function updateAttributeGroupValues ($Data, $groupId)
	{
		$keys = Array();
		$languages = App::getModel('language')->getLanguageALL();
		
		foreach ($Data as $key => $values){
			$check = substr($key, 0, 3);
			if ($check == 'new'){
				$sql = 'INSERT INTO staticattribute (staticgroupid, addid) VALUES (:staticgroupid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('staticgroupid', $groupId);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
				
				$id = $stmt->getConnection()->getIdGenerator()->getId();
				
				$keys[] = $id;
				
				foreach ($languages as $language){
					$sql = 'INSERT INTO staticattributetranslation SET
								staticattributeid = :staticattributeid,
								name = :name,
								description = :description,
								file = :file,
								languageid = :languageid';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('staticattributeid', $id);
					$stmt->setString('name', $values['name_' . $language['id']]);
					$stmt->setString('description', $values['description_' . $language['id']]);
					$stmt->setString('file', $values['files_' . $language['id']]['file']);
					$stmt->setInt('languageid', $language['id']);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
					}
				}
			}
			else{
				$keys[] = $key;
				foreach ($languages as $language){
					
					$sql = 'DELETE FROM staticattributetranslation WHERE staticattributeid = :staticattributeid AND languageid = :languageid';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('staticattributeid', $key);
					$stmt->setInt('languageid', $language['id']);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
					
					$sql = 'INSERT INTO staticattributetranslation SET
								staticattributeid = :staticattributeid,
								name = :name,
								description = :description,
								file = :file,
								languageid = :languageid';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('staticattributeid', $key);
					$stmt->setString('name', $values['name_' . $language['id']]);
					$stmt->setString('description', $values['description_' . $language['id']]);
					$stmt->setString('file', $values['files_' . $language['id']]['file']);
					$stmt->setInt('languageid', $language['id']);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
					}
				}
			}
		}
		
		if (count($keys) > 0){
			$sql = 'DELETE FROM staticattributetranslation WHERE staticattributeid IN (SELECT idstaticattribute FROM staticattribute WHERE staticgroupid = :staticgroupid AND idstaticattribute NOT IN(:ids))';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticgroupid', $groupId);
			$stmt->setINInt('ids', $keys);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
			
			$sql = 'DELETE FROM staticattribute WHERE staticgroupid = :staticgroupid AND idstaticattribute NOT IN(:ids)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticgroupid', $groupId);
			$stmt->setINInt('ids', $keys);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

}