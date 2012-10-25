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
 * $Id: stores.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class StoresModel extends Model
{

	public function getStores ()
	{
		$sql = "SELECT idstore AS id,name
					FROM store";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data['0'] = Array(
			'name' => 'Globalny',
			'parent' => null,
			'weight' => 0
		);
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'name' => $rs->getString('name'),
				'parent' => 0,
				'weight' => $rs->getInt('id')
			);
		}
		
		$Data['3_1'] = Array(
			'name' => 'Widok1',
			'parent' => 3,
			'weight' => 1
		);
		$Data['2_2'] = Array(
			'name' => 'Widok2',
			'parent' => 2,
			'weight' => 1
		);
		return json_encode($Data);
	
	}

	public function getLayers ()
	{
		$sql = "SELECT idstore AS id,name
					FROM store";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data['0'] = Array(
			'name' => 'Globalny',
			'parent' => null,
			'weight' => 0
		);
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'name' => $rs->getString('name'),
				'parent' => 0,
				'weight' => $rs->getInt('id')
			);
		}
		
		$sql = 'SELECT V.idview AS id,V.name,V.storeid
					FROM view V
					ORDER BY 
					V.name ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('storeid') . '_' . $rs->getInt('id')] = Array(
				'name' => $rs->getString('name'),
				'parent' => $rs->getInt('storeid'),
				'weight' => $rs->getInt('id')
			);
		}
		
		return json_encode($Data);
	
	}

	public function getViewsAll ()
	{
		$sql = 'SELECT V.idview AS id,V.name,V.storeid
					FROM view V
					WHERE V.idview IN (:viewids)
					ORDER BY 
					V.name ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $this->registry->session->getActiveLanguageId());
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'parent' => $rs->getInt('storeid')
			);
		}
		return $Data;
	}

	public function getStoresAll ()
	{
		$sql = 'SELECT S.idstore AS id,S.name
					FROM store S
					ORDER BY S.name ASC';
		
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getStoreViews ($storeid)
	{
		$sql = '
					SELECT idview AS id,name
					FROM view 
					WHERE storeid = :storeid
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('storeid', $storeid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'name' => $rs->getString('name'),
				'hasChildren' => 0
			);
		}
		return $Data;
	}

	public function getActiveStoreId ()
	{
		return (! is_null(Helper::getStoreId())) ? Helper::getStoreId() : 0;
	}

	public function getActiveLayer ()
	{
		$storeid = (! is_null(Helper::getStoreId())) ? Helper::getStoreId() : 0;
		$viewid = (! is_null(Helper::getViewId())) ? Helper::getViewId() : 0;
		if ($viewid > 0){
			return $storeid . '_' . $viewid;
		}
		else{
			return $storeid;
		}
	
	}

	public function changeActiveLayer ($layers, $hasDg = false)
	{
		$objResponse = new xajaxResponse();
		$layer = explode('_', $layers);
		if (! empty($layer[1])){
			$storeid = $layer[0];
			$viewid = $layer[1];
			Helper::setStoreId($storeid);
			Helper::setViewId($viewid);
		}
		else{
			Helper::setStoreId($layers);
			Helper::setViewId(null);
		}
		if ($hasDg == true){
			$objResponse->script('theDatagrid.LoadData();');
		}
		else{
			$objResponse->script('window.location.reload(true)');
		}
		
		return $objResponse;
	}

	public function changeActiveStoreId ($id)
	{
		$objResponse = new xajaxResponse();
		Helper::setStoreId($id);
		$objResponse->script('window.location.reload(true)');
		return $objResponse;
	}

	public function getViewForHelperAll ()
	{
		$globaluser = $this->registry->session->getActiveUserIsGlobal();
		
		$Data = Array();
		
		if ($globaluser == 1){
			
			$sql = 'SELECT V.idview AS id
						FROM view V
						GROUP BY V.idview
					';
			
			$stmt = $this->registry->db->prepareStatement($sql);
			$rs = $stmt->executeQuery();
			
			while ($rs->next()){
				$Data[] = $rs->getInt('id');
			}
		
		}
		else{
			
			$sql = 'SELECT
						UGV.viewid
						FROM usergroupview UGV 
						lEFT JOIN view V ON UGV.viewid = V.idview
						lEFT JOIN store S ON V.storeid = S.idstore
						WHERE UGV.userid = :userid
						GROUP BY UGV.viewid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('userid', $this->registry->session->getActiveUserid());
			$rs = $stmt->executeQuery();
			
			while ($rs->next()){
				$Data[] = $rs->getInt('viewid');
			}
		
		}
		
		return $Data;
	
	}
}