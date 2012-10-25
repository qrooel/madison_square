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
 * $Id: dispatchmethod.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class DispatchmethodModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
		$this->countries = App::getModel('countrieslist')->getCountryForSelect();
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('dispatchmethod', Array(
			'iddispatchmethod' => Array(
				'source' => 'D.iddispatchmethod'
			),
			'name' => Array(
				'source' => 'D.name',
				'prepareForAutosuggest' => true,
				'processLanguage' => true
			),
			'countries' => Array(
				'source' => 'D.countryids',
				'processFunction' => Array(
					$this,
					'getCountriesForDispatchmethod'
				)
			),
			'hierarchy' => Array(
				'source' => 'D.hierarchy'
			)
		));
		$datagrid->setFrom('
				dispatchmethod D
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
			');
		$datagrid->setGroupBy('
				D.iddispatchmethod
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL,1,DV.viewid = :viewid)
			');
	}

	public function getCountriesForDispatchmethod ($ids)
	{
		$countryList = Array();
		if ($ids != ''){
			$countries = explode(',', $ids);
			$countryString = '';
			foreach ($countries as $key => $country){
				if (isset($this->countries[$country])){
					$countryList[] = $this->countries[$country];
				}
			}
		}
		return (count($countryList) > 0) ? implode('<br />', $countryList) : '';
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getDispatchmethodForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteDispatchmethod ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteDispatchmethod'
		), $this->getName());
	}

	public function deleteDispatchmethod ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'iddispatchmethod' => $id
			), $this->getName(), 'deleteDispatchmethod');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getDispatchmethodAll ()
	{
		$sql = 'SELECT iddispatchmethod AS id, name FROM dispatchmethod';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $this->registry->core->getMessage($rs->getString('name'))
			);
		}
		return $Data;
	}

	public function getDispatchmethodToSelect ()
	{
		$Data = $this->getDispatchmethodAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function updateDispatchmethodPaymentmethod ($array, $id)
	{
		$sql = 'DELETE FROM dispatchmethodpaymentmethod WHERE dispatchmethodid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($array)){
			foreach ($array as $value){
				$sql = 'INSERT INTO dispatchmethodpaymentmethod (dispatchmethodid, paymentmethodid, addid)
							VALUES (:dispatchmethodid, :paymentmethodid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('dispatchmethodid', $id);
				$stmt->setInt('paymentmethodid', $value);
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

	public function editDispatchmethod ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateDispatchmethod($Data, $id);
			$this->updateDispatchmethodPaymentmethod($Data['paymentmethodname'], $id);
			if ($Data['type'] == 1){
				$this->updateDispatchmethodPrice($Data, $id);
			}
			if ($Data['type'] == 2){
				$this->updateDispatchmethodWeight($Data, $id);
			}
			$this->updateDispatchmethodView($Data, $id);
			$this->updateDispatchmethodPhoto($Data, $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateDispatchmethodView ($array, $id)
	{
		$sql = 'DELETE FROM dispatchmethodview WHERE dispatchmethodid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($array['view']) && is_array($array['view'])){
			foreach ($array['view'] as $value){
				$sql = 'INSERT INTO dispatchmethodview (viewid, dispatchmethodid, addid)
							VALUES (:viewid, :dispatchmethodid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('dispatchmethodid', $id);
				$stmt->setInt('viewid', $value);
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

	public function updateDispatchmethodPrice ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM dispatchmethodprice WHERE dispatchmethodid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['table']['ranges'] as $key => $value){
			$sql = 'INSERT INTO dispatchmethodprice (dispatchmethodid, `from`, `to`, dispatchmethodcost, vat, addid)
						VALUES (:dispatchmethodid, :from, :to, :dispatchmethodcost, :vat, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $id);
			if (isset($value['min'])){
				$stmt->setString('from', $value['min']);
			}
			else{
				$stmt->setString('from', 0.00);
			}
			if (isset($value['max'])){
				$stmt->setString('to', $value['max']);
			}
			else{
				$stmt->setString('to', 0.00);
			}
			if ($Data['table']['vat'] > 0 && isset($Data['table']['use_vat'])){
				$stmt->setInt('vat', $Data['table']['vat']);
				$stmt->setString('dispatchmethodcost', $value['price']);
			}
			else{
				$stmt->setInt('vat', NULL);
				$stmt->setString('dispatchmethodcost', $value['price']);
			}
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		return $Data;
	}

	public function updateDispatchmethodWeight ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM dispatchmethodweight WHERE dispatchmethodid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['tableweight']['ranges'] as $key => $value){
			$sql = 'INSERT INTO dispatchmethodweight (dispatchmethodid, `from`, `to`, cost, vat, addid)
						VALUES (:dispatchmethodid, :from, :to, :cost,:vat, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $id);
			if (isset($value['min'])){
				$stmt->setString('from', $value['min']);
			}
			else{
				$stmt->setString('from', 0.00);
			}
			if (isset($value['max'])){
				$stmt->setString('to', $value['max']);
			}
			else{
				$stmt->setString('to', 0.00);
			}
			if ($Data['tableweight']['vat'] > 0 && isset($Data['tableweight']['use_vat'])){
				$stmt->setInt('vat', $Data['tableweight']['vat']);
				$stmt->setString('cost', $value['price']);
			}
			else{
				$stmt->setInt('vat', NULL);
				$stmt->setString('cost', $value['price']);
			}
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		return $Data;
	}

	public function updateDispatchmethod ($Data, $id)
	{
		try{
			$sql = 'UPDATE dispatchmethod SET 
						name=:name, 
						description=:description,
						type = :type, 
						maximumweight = :maximumweight, 
						freedelivery = :freedelivery,
						countryids = :countryids,
						currencyid = :currencyid
					WHERE iddispatchmethod = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $Data['name']);
			$stmt->setString('description', $Data['description']);
			$stmt->setInt('editid', $this->registry->session->getActiveUserid());
			$stmt->setInt('type', $Data['type']);
			if ($Data['maximumweight'] != NULL){
				$stmt->setFloat('maximumweight', $Data['maximumweight']);
			}
			else{
				$stmt->setNull('maximumweight');
			}
			if ($Data['freedelivery'] != NULL){
				$stmt->setFloat('freedelivery', $Data['freedelivery']);
			}
			else{
				$stmt->setNull('freedelivery');
			}
			if (isset($Data['countryids']) && ! empty($Data['countryids'])){
				$stmt->setString('countryids', implode(',', $Data['countryids']));
			}
			else{
				$stmt->setString('countryids', '');
			}
			$stmt->setInt('currencyid', $Data['currencyid']);
			$stmt->setInt('id', $id);
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_EDIT'), 10, $e->getMessage());
		}
	}

	public function updateDispatchmethodPhoto ($Data, $id)
	{
		if (isset($Data['photo']['unmodified']) && $Data['photo']['unmodified']){
			return;
		}
		try{
			$sql = 'UPDATE dispatchmethod SET 
							photo = :photo
						WHERE iddispatchmethod = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			if (isset($Data['photo'][0])){
				$stmt->setInt('photo', $Data['photo'][0]);
			}
			else{
				$stmt->setNull('photo');
			}
			$stmt->setInt('id', $id);
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_EDIT'), 10, $e->getMessage());
		}
	}

	public function getDispatchmethodView ($id)
	{
		$sql = "SELECT 
					iddispatchmethod AS id, 
					name, 
					description, 
					photo, 
					type, 
					maximumweight, 
					freedelivery,
					countryids,
					currencyid
				FROM dispatchmethod
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = iddispatchmethod
				WHERE iddispatchmethod = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$countryids = Array();
			if ($rs->getString('countryids') != ''){
				$countryids = explode(',', $rs->getString('countryids'));
			}
			$Data = Array(
				'name' => $rs->getString('name'),
				'description' => $rs->getString('description'),
				'type' => $rs->getInt('type'),
				'currencyid' => $rs->getInt('currencyid'),
				'maximumweight' => $rs->getFloat('maximumweight'),
				'freedelivery' => $rs->getFloat('freedelivery'),
				'paymentmethods' => $this->DispatchmethodPaymentmethodIds($id),
				'view' => $this->DispatchmethodView($id),
				'countryids' => $countryids
			);
			$Data['photo'] = $rs->getInt('photo');
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_NO_EXIST'));
		}
		return $Data;
	}

	public function DispatchmethodView ($id)
	{
		$sql = "SELECT viewid
					FROM dispatchmethodview
					WHERE dispatchmethodid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function checkVat ($id)
	{
		$sql = "SELECT vat
					FROM dispatchmethodprice
					WHERE dispatchmethodid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'vat' => $rs->getInt('vat')
			);
		}
		if ($Data['vat'] > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function getDispatchmethodForOrder ($id)
	{
		$sql = 'SELECT type FROM dispatchmethod WHERE iddispatchmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$type = $rs->getInt('type');
		}
		if ($type == 1){
			$method = $this->getDispatchmethodPrice($id);
		}
		else{
			$method = $this->getDispatchmethodWeight($id);
		}
		if (isset($method['use_vat']) && $method['use_vat'] == 1 && $method['vat'] > 0){
			$vatData = App::getModel('vat')->getVATAllForRangeEditor();
			$vatValue = $vatData[$method['vat']];
		}
		else{
			$vatValue = 0;
		}
		return $vatValue;
	}

	public function getDispatchmethodPrice ($id)
	{
		$sql = 'SELECT iddispatchmethodprice as id, dispatchmethodcost, `from`, `to`, vat 
					FROM dispatchmethodprice
					WHERE dispatchmethodid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['ranges'][] = Array(
				'min' => $rs->getString('from'),
				'max' => $rs->getString('to'),
				'price' => $rs->getString('dispatchmethodcost')
			);
			if ($rs->getString('vat') > 0){
				$Data['vat'] = $rs->getInt('vat');
				$Data['use_vat'] = 1;
			}
		}
		return $Data;
	}

	public function getDispatchmethodWeight ($id)
	{
		$sql = 'SELECT cost, `from`, `to`,vat
					FROM dispatchmethodweight
					WHERE dispatchmethodid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['ranges'][] = Array(
				'min' => $rs->getString('from'),
				'max' => $rs->getString('to'),
				'price' => $rs->getString('cost')
			);
			if ($rs->getString('vat') > 0){
				$Data['vat'] = $rs->getInt('vat');
				$Data['use_vat'] = 1;
			}
		}
		return $Data;
	}

	public function DispatchmethodPaymentmethod ($id)
	{
		$sql = 'SELECT P.idpaymentmethod AS id, P.name AS paymentmethodname
					FROM dispatchmethodpaymentmethod DP
					LEFT JOIN paymentmethod P ON DP.paymentmethodid = P.idpaymentmethod
					WHERE DP.dispatchmethodid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'paymentmethodname' => $rs->getString('paymentmethodname'),
				'id' => $rs->getInt('id')
			);
		}
		return $Data;
	}

	public function DispatchmethodPaymentmethodIds ($id)
	{
		$Data = $this->DispatchmethodPaymentmethod($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function addDispatchmethod ($Data)
	{
		$sql = 'INSERT INTO `dispatchmethod` (name,currencyid, description,type,maximumweight,freedelivery, photo,countryids, addid) VALUES (:name,:currencyid, :description,:type,:maximumweight,:freedelivery, :photo,:countryids, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('description', $Data['description']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('currencyid', $Data['currencyid']);
		if (isset($Data['photo']['main'])){
			$stmt->setInt('photo', $Data['photo']['main']);
		}
		else{
			$stmt->setNull('photo');
		}
		$stmt->setInt('type', $Data['type']);
		if ($Data['maximumweight'] != NULL){
			$stmt->setFloat('maximumweight', $Data['maximumweight']);
		}
		else{
			$stmt->setNull('maximumweight');
		}
		if ($Data['freedelivery'] != NULL){
			$stmt->setFloat('freedelivery', $Data['freedelivery']);
		}
		else{
			$stmt->setNull('freedelivery');
		}
		if (isset($Data['countryids']) && ! empty($Data['countryids'])){
			$stmt->setString('countryids', implode(',', $Data['countryids']));
		}
		else{
			$stmt->setString('countryids', '');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	protected function addPaymentmethodToDispatchmethod ($array, $Dispatchmethodid)
	{
		foreach ($array as $value){
			$sql = 'INSERT INTO dispatchmethodpaymentmethod (dispatchmethodid, paymentmethodid, addid)
						VALUES (:dispatchmethodid, :paymentmethodid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $Dispatchmethodid);
			$stmt->setInt('paymentmethodid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addNewDispatchmethod ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newDispatchmethodid = $this->addDispatchmethod($Data);
			if (is_array($Data['paymentmethodname']) && ! empty($Data['paymentmethodname'])){
				$this->addPaymentmethodToDispatchmethod($Data['paymentmethodname'], $newDispatchmethodid);
			}
			$this->addDispatchmethodPrice($Data['table'], $newDispatchmethodid);
			$this->addDispatchmethodWeight($Data['tableweight'], $newDispatchmethodid);
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addDispatchmethodView($Data, $newDispatchmethodid);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	protected function addDispatchmethodView ($Data, $id)
	{
		foreach ($Data['view'] as $key => $val){
			$sql = 'INSERT INTO dispatchmethodview (viewid, dispatchmethodid, addid)
						VALUES (:viewid, :dispatchmethodid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $id);
			$stmt->setInt('viewid', $val);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addDispatchmethodPrice ($array, $dispatchmethodid)
	{
		foreach ($array['ranges'] as $key => $value){
			$sql = 'INSERT INTO dispatchmethodprice (dispatchmethodid, `from`, `to`, dispatchmethodcost, vat, addid)
						VALUES (:dispatchmethodid, :from, :to, :dispatchmethodcost, :vat, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $dispatchmethodid);
			if (isset($value['min'])){
				$stmt->setString('from', $value['min']);
			}
			else{
				$stmt->setString('from', 0.00);
			}
			if (isset($value['max'])){
				$stmt->setString('to', $value['max']);
			}
			else{
				$stmt->setString('to', 0.00);
			}
			if ($array['vat'] > 0 && isset($array['use_vat'])){
				$stmt->setInt('vat', $array['vat']);
				$stmt->setString('dispatchmethodcost', $value['price']);
			}
			else{
				$stmt->setInt('vat', NULL);
				$stmt->setString('dispatchmethodcost', $value['price']);
			}
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addDispatchmethodWeight ($array, $dispatchmethodid)
	{
		foreach ($array['ranges'] as $key => $value){
			$sql = 'INSERT INTO dispatchmethodweight (dispatchmethodid, `from`, `to`, cost, addid)
						VALUES (:dispatchmethodid, :from, :to, :cost, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $dispatchmethodid);
			if (isset($value['min'])){
				$stmt->setString('from', $value['min']);
			}
			else{
				$stmt->setString('from', 0.00);
			}
			if (isset($value['max'])){
				$stmt->setString('to', $value['max']);
			}
			else{
				$stmt->setString('to', 0.00);
			}
			$stmt->setString('cost', $value['price']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function doAJAXUpdateMethod ($id, $hierarchy)
	{
		$sql = 'UPDATE dispatchmethod SET 
					hierarchy = :hierarchy
				WHERE iddispatchmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('hierarchy', $hierarchy);
		$stmt->executeUpdate();
		$this->flushCache();
	}
}