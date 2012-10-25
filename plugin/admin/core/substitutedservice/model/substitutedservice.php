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
 * $Id: substitutedservice.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SubstitutedserviceModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('substitutedservice', Array(
			'idsubstitutedservice' => Array(
				'source' => 'S.idsubstitutedservice'
			),
			'name' => Array(
				'source' => 'S.name'
			),
			'transmailname' => Array(
				'source' => 'TM.name'
			)
		));
		$datagrid->setFrom('
			 	substitutedservice S
				LEFT JOIN transmail TM ON S.transmailid = TM.idtransmail
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, 1, S.viewid= :viewid)
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSubstitutedserviceForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteSubstitutedservice ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteSubstitutedservice'
		), $this->getName());
	}

	public function deleteSubstitutedservice ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idsubstitutedservice' => $id
			), $this->getName(), 'deleteSubstitutedservice');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	function replace ($input)
	{
		return $this->registry->core->getMessage($input[1]);
	}

	public function getTransMailAllToSelect ()
	{
		$sql = "SELECT TMA.idtransmailaction, 
						TM.idtransmail, TM.name
					FROM transmail TM
						LEFT JOIN transmailaction TMA ON TMA.idtransmailaction = TM.transmailactionid
						WHERE TMA.idtransmailaction = (SELECT TMA.idtransmailaction
												FROM transmailaction TMA
												WHERE TMA.isnotification =1)
      					AND IF(:viewid>0, TM.viewid= :viewid, 1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$viewid = Helper::getViewId();
		$stmt->setInt('viewid', $viewid);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$id = $rs->getInt('idtransmail');
				$Data[$id] = $rs->getString('name');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	public function SetContentTransMail ($idtransmail)
	{
		$contentTransMail = '';
		if (isset($idtransmail['id']) && $idtransmail['id'] > 0 && ! empty($idtransmail['id'])){
			$contentTransMail = $this->getContentTransMail($idtransmail['id']);
		}
		else{
			$contentTransMail = '';
		}
		$filename = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . 'substituteservicetemp.tpl';
		$file = @fopen($filename, "w+");
		$write = fwrite($file, $contentTransMail);
		fclose($file);
		ob_clean();
	}

	public function getContentTransMail ($idtransmail)
	{
		$sql = "SELECT TM.contenthtml, TMH.contenthtml as header, TMF.contenthtml as footer
					FROM transmail TM
						LEFT JOIN transmailheader TMH ON TM.transmailheaderid = TMH.idtransmailheader
						LEFT JOIN transmailfooter TMF ON TM.transmailfooterid = TMF.idtransmailfooter
					WHERE TM.idtransmail = :idtransmail";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmail', $idtransmail);
		$contenthtml = '';
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$contenthtml = $rs->getString('header') . ' ' . $rs->getString('contenthtml') . ' ' . $rs->getString('footer');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $contenthtml;
	}

	public function getPeriodsAllToSelect ()
	{
		$sql = "SELECT P.idperiod, P.name 
					FROM period P
					ORDER BY P.timeinterval";
		$stmt = $this->registry->db->prepareStatement($sql);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$idPeriod = $rs->getInt('idperiod');
				$Data[$idPeriod] = $rs->getString('name');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	public function addSubstitutedService ($submittedData)
	{
		try{
			$this->registry->db->setAutoCommit(false);
			try{
				$new = $this->insertSubstitutedService($submittedData);
			}
			catch (Exception $e){
				throw new CoreException($e->getMessage());
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			return true;
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			throw new CoreException($this->registry->core->getMessage('ERR_INSERT_SUBSTITUDED_SERVICE'), 112, $e->getMessage());
			return $objResponse;
		}
	}

	public function insertSubstitutedService ($submittedData)
	{
		$sql = 'INSERT INTO substitutedservice 
					SET
						transmailid= :transmailid, 
						actionid= :actionid, 
						date= :date, 
						periodid= :periodid, 
						admin= :admin,
						name= :name,
						addid= :addid,
						viewid= :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('transmailid', $submittedData['transmailid']);
		$stmt->setInt('actionid', $submittedData['actionid']['value']);
		if ($submittedData['actionid']['value'] == 2 && isset($submittedData['actionid'][2])){
			$stmt->setString('date', $submittedData['actionid'][2]);
		}
		else{
			$stmt->setNull('date');
		}
		if ($submittedData['actionid']['value'] != 2 && ! empty($submittedData['actionid'][$submittedData['actionid']['value']])){
			$stmt->setInt('periodid', $submittedData['actionid'][$submittedData['actionid']['value']]);
		}
		else{
			$stmt->setNull('periodid');
		}
		if (isset($submittedData['admin']) && $submittedData['admin'] > 0){
			$stmt->setInt('admin', $submittedData['admin']);
		}
		else{
			$stmt->setInt('admin', 0);
		}
		$stmt->setString('name', $submittedData['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		try{
			$stmt->executeUpdate();
			return $stmt->getConnection()->getIdGenerator()->getId();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_INSERT_SUBSTITUDED_SERVICE'), 112, $e->getMessage());
		}
	}

	public function getSubstitutedServiceToEdit ($idSubstitutedService)
	{
		$sql = "SELECT S.idsubstitutedservice, S.transmailid, S.actionid, S.date, S.periodid, 
						S.admin, S.name
					FROM substitutedservice S
					WHERE idsubstitutedservice= :idsubstitutedservice";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubstitutedservice', $idSubstitutedService);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'idsubstitutedservice' => $rs->getInt('idsubstitutedservice'),
					'transmailid' => $rs->getInt('transmailid'),
					'actionid' => $rs->getInt('actionid'),
					'date' => $rs->getString('date'),
					'periodid' => $rs->getInt('periodid'),
					'admin' => $rs->getInt('admin'),
					'name' => $rs->getString('name')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	public function editSubstitutedService ($submittedData, $idSubstitutedService)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateSubstitutedService($submittedData, $idSubstitutedService);
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateSubstitutedService ($submittedData, $idSubstitutedService)
	{
		$sql = 'UPDATE substitutedservice 
					SET
						transmailid= :transmailid, 
						actionid= :actionid, 
						date= :date, 
						periodid= :periodid, 
						admin= :admin,
						name= :name,
						editid= :editid,
						editdate= NOW(),
						viewid= :viewid
					WHERE idsubstitutedservice= :idsubstitutedservice';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubstitutedservice', $idSubstitutedService);
		$stmt->setInt('transmailid', $submittedData['transmailid']);
		$stmt->setInt('actionid', $submittedData['actionid']['value']);
		if ($submittedData['actionid']['value'] == 2){
			$stmt->setString('date', $submittedData['actionid'][2]);
		}
		else{
			$stmt->setNull('date');
		}
		if ($submittedData['actionid']['value'] != 2 && ! empty($submittedData['actionid'][$submittedData['actionid']['value']])){
			$stmt->setInt('periodid', $submittedData['actionid'][$submittedData['actionid']['value']]);
		}
		else{
			$stmt->setNull('periodid');
		}
		if (isset($submittedData['admin']) && ! empty($submittedData['admin'])){
			$stmt->setInt('admin', $submittedData['admin']);
		}
		else{
			$stmt->setInt('admin', 0);
		}
		$stmt->setString('name', $submittedData['name']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_INSERT_SUBSTITUDED_SERVICE'), 112, $e->getMessage());
		}
	}
}