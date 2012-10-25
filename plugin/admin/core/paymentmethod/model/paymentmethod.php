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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: paymentmethod.php 687 2012-09-01 12:02:47Z gekosale $ 
 */

class PaymentmethodModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('paymentmethod', Array(
			'idpaymentmethod' => Array(
				'source' => 'P.idpaymentmethod'
			),
			'name' => Array(
				'source' => 'P.name',
				'prepareForAutosuggest' => true,
				'processLanguage' => true
			),
			'controller' => Array(
				'source' => 'P.controller',
				'prepareForAutosuggest' => true
			),
			'active' => Array(
				'source' => 'P.active'
			),
			'hierarchy' => Array(
				'source' => 'P.hierarchy'
			)
		));
		$datagrid->setFrom('
				paymentmethod P
				LEFT JOIN paymentmethodview PV ON PV.paymentmethodid = P.idpaymentmethod
			');
		$datagrid->setGroupBy('
				P.idpaymentmethod
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NOT NULL,PV.viewid = :viewid, 1)
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getControllerForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('controller', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getPaymentmethodForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeletePaymentmethod ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deletePaymentmethod'
		), $this->getName());
	}

	public function deletePaymentmethod ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idpaymentmethod' => $id
			), $this->getName(), 'deletePaymentmethod');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnablePaymentmethod ($datagridId, $id)
	{
		try{
			$this->enablePaymentmethod($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisablePaymentmethod ($datagridId, $id)
	{
		try{
			$this->disablePaymentmethod($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disablePaymentmethod ($id)
	{
		$sql = 'UPDATE paymentmethod SET active = 0 WHERE idpaymentmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enablePaymentmethod ($id)
	{
		$sql = 'UPDATE paymentmethod SET active = 1 WHERE idpaymentmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getPaymentmethodView ($id)
	{
		$sql = "SELECT name,controller FROM paymentmethod WHERE idpaymentmethod = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'controller' => $rs->getString('controller'),
				'dispatchmethod' => $this->DispatchmethodPaymentmethodIds($id),
				'view' => $this->PaymentmethodView($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_PAYMENTMETHOD_NO_EXIST'));
		}
		return $Data;
	}

	public function PaymentmethodView ($id)
	{
		$sql = "SELECT viewid
					FROM paymentmethodview
					WHERE paymentmethodid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function DispatchmethodPaymentmethod ($id)
	{
		$sql = 'SELECT DM.iddispatchmethod AS id, DM.name AS dispatchmethodname
					FROM dispatchmethodpaymentmethod DPM
					LEFT JOIN dispatchmethod DM ON DPM.dispatchmethodid = DM.iddispatchmethod
					WHERE DPM.paymentmethodid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'dispatchmethodname' => $rs->getString('dispatchmethodname'),
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

	public function getPaymentmethodAll ()
	{
		$sql = 'SELECT idpaymentmethod as id, name, controller FROM paymentmethod';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $this->registry->core->getMessage($rs->getString('name')),
				'controller' => $rs->getString('controller')
			);
		}
		return $Data;
	}

	public function editPaymentmethod ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updatePaymentmethod($Data, $id);
			$this->updateDispatchmethodPaymentmethod($Data['dispatchmethod'], $id);
			$this->updatePaymentmethodView($Data['view'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updatePaymentmethodView ($array, $id)
	{
		$sql = 'DELETE FROM paymentmethodview WHERE paymentmethodid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (is_array($array) && ! empty($array)){
			foreach ($array as $value){
				$sql = 'INSERT INTO paymentmethodview (viewid, paymentmethodid, addid)
							VALUES (:viewid, :paymentmethodid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('paymentmethodid', $id);
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

	public function updatePaymentmethod ($Data, $id)
	{
		$sql = 'UPDATE paymentmethod SET name=:name WHERE idpaymentmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['name']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function updateDispatchmethodPaymentmethod ($array, $id)
	{
		$sql = 'DELETE FROM dispatchmethodpaymentmethod WHERE paymentmethodid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (is_array($array) && ! empty($array)){
			foreach ($array as $value){
				$sql = 'INSERT INTO dispatchmethodpaymentmethod (dispatchmethodid, paymentmethodid, addid)
							VALUES (:dispatchmethodid, :paymentmethodid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('paymentmethodid', $id);
				$stmt->setInt('dispatchmethodid', $value);
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

	public function getPaymentmethodModelById ($id)
	{
		$sql = 'SELECT controller FROM paymentmethod WHERE idpaymentmethod = :idpaymentmethod';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpaymentmethod', $id);
		$rs = $stmt->executeQuery();
		$controller = null;
		while ($rs->next()){
			$controller = $rs->getString('controller');
		}
		return $controller;
	}

	public function getPaymentmethodToSelect ()
	{
		$Data = $this->getPaymentmethodAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	protected function addPaymentmethodToDispatchmethod ($dispatchmethodarray, $Paymentmethodid)
	{
		foreach ($dispatchmethodarray as $value){
			$sql = 'INSERT INTO dispatchmethodpaymentmethod (dispatchmethodid, paymentmethodid, addid)
						VALUES (:dispatchmethodid, :paymentmethodid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('paymentmethodid', $Paymentmethodid);
			$stmt->setInt('dispatchmethodid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	protected function addPaymentmethodView ($array, $id)
	{
		foreach ($array as $value){
			$sql = 'INSERT INTO paymentmethodview (viewid, paymentmethodid, addid)
						VALUES (:viewid, :paymentmethodid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('paymentmethodid', $id);
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

	public function addNewPaymentmethod ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newPaymentmethodid = $this->addPaymentmethod($Data);
			if (is_array($Data['dispatchmethod']) && ! empty($Data['dispatchmethod'])){
				$this->addPaymentmethodToDispatchmethod($Data['dispatchmethod'], $newPaymentmethodid);
			}
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addPaymentmethodView($Data['view'], $newPaymentmethodid);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addPaymentmethod ($Data)
	{
		$sql = 'INSERT INTO paymentmethod (name,controller, addid) VALUES (:name,:controller, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('controller', $Data['controller']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException('ERR_PAYMENTMETHOD_ADD', 15, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getDispatchmethodPrice ($id)
	{
		$sql = 'SELECT idpaymentmethod as id, dispatchmethodcost, `from`, `to`, vat 
					FROM paymentmethod
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
			$Data['vat'] = $rs->getInt('vat');
		}
		return $Data;
	}

	public function doAJAXUpdateMethod ($id, $hierarchy)
	{
		$sql = 'UPDATE paymentmethod SET 
					hierarchy = :hierarchy
				WHERE idpaymentmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('hierarchy', $hierarchy);
		$stmt->executeUpdate();
		$this->flushCache();
	}
}