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
 * $Id: vat.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class VATModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('VAT', Array(
			'idvat' => Array(
				'source' => 'V.idvat'
			),
			'name' => Array(
				'source' => 'VT.name'
			),
			'value' => Array(
				'source' => 'CONCAT(V.value, \'%\')'
			),
			'productcount' => Array(
				'source' => 'COUNT(P.idproduct)'
			),
			'adddate' => Array(
				'source' => 'V.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'V.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`vat` V
				LEFT JOIN vattranslation VT ON VT.vatid = V.idvat AND VT.languageid = :languageid
				LEFT JOIN `product` P ON P.vatid = V.idvat
				LEFT JOIN `user` UA ON V.addid = UA.iduser
				LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
				LEFT JOIN `user` UE ON V.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				V.idvat
			');
	}

	public function getValueForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('value', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getVATForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteVAT ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteVAT'
		), $this->getName());
	}

	public function deleteVAT ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idvat' => $id
			), $this->getName(), 'deleteVAT');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getVATValuesAll ()
	{
		$sql = 'SELECT V.idvat AS id, V.value FROM vat V';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getString('value');
		}
		return $Data;
	}

	public function getVATAll ()
	{
		$sql = 'SELECT V.idvat AS id, V.value,	VT.name 
					FROM vat V
					LEFT JOIN vattranslation VT ON VT.vatid = V.idvat AND VT.languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getString('name');
		}
		return $Data;
	}

	public function getVATAllForRangeEditor ()
	{
		$sql = 'SELECT V.idvat AS id, V.value,	VT.name 
					FROM vat V
					LEFT JOIN vattranslation VT ON VT.vatid = V.idvat AND VT.languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getFloat('value');
		}
		return $Data;
	}

	public function getVATAllToSelect ()
	{
		$Data = $this->getVATAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['value'];
		}
		return $tmp;
	}

	public function getVATTranslation ($id)
	{
		$sql = "SELECT name, languageid
					FROM vattranslation
					WHERE vatid = :id";
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

	public function editVAT ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateVAT($Data['value'], $id);
			$this->updateVatTranslation($Data['name'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_VAT_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateVAT ($value, $id)
	{
		$sql = 'UPDATE vat SET value=:value WHERE idvat = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('value', $value);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
	}

	public function updateVatTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM vattranslation WHERE vatid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data as $key => $value){
			$sql = 'INSERT INTO vattranslation SET
							vatid = :vatid,
							name = :name, 
							languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('vatid', $id);
			$stmt->setString('name', $value);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_VAT_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function getVATView ($id)
	{
		$sql = "SELECT idvat AS id, value
					FROM vat
					WHERE idvat = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data = Array(
				'value' => $rs->getString('value'),
				'id' => $rs->getInt('id'),
				'language' => $this->getVATTranslation($id)
			);
		}
		return $Data;
	}

	public function addNewVAT ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newvatid = $this->addVAT($Data['value']);
			if (is_array($Data['name']) && ! empty($Data['name'])){
				$this->addVatTranslation($Data['name'], $newvatid);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_VAT_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addVAT ($value)
	{
		$sql = 'INSERT INTO `vat` (value, addid) VALUES (:value, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('value', $value);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_VAT_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addVatTranslation ($Data, $id)
	{
		foreach ($Data as $key => $value){
			$sql = 'INSERT INTO vattranslation SET
						vatid = :vatid,
						name = :name, 
						languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('vatid', $id);
			$stmt->setString('name', $value);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_VAT_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
	}
}