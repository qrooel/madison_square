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
 * $Id: view.php 687 2012-09-01 12:02:47Z gekosale $ 
 */

class viewModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('view', Array(
			'idview' => Array(
				'source' => 'idview'
			),
			'name' => Array(
				'source' => 'V.name'
			),
			'namespace' => Array(
				'source' => 'V.namespace'
			),
			'store' => Array(
				'source' => 'S.name'
			),
			'url' => Array(
				'source' => 'VU.url'
			)
		));
		$datagrid->setFrom('
				view V
				LEFT JOIN viewurl VU ON VU.viewid = idview
				LEFT JOIN store S ON S.idstore = storeid
			');
		$datagrid->setGroupBy('
				V.idview
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getViewForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteView ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteView'
		), $this->getName());
	}

	public function deleteView ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idview' => $id
			), $this->getName(), 'deleteView');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getView ($id)
	{
		$sql = "SELECT * FROM view V WHERE idview=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'namespace' => $rs->getString('namespace'),
				'store' => $rs->getInt('storeid'),
				'faceboookappid' => $rs->getString('faceboookappid'),
				'faceboooksecret' => $rs->getString('faceboooksecret'),
				'gacode' => $rs->getString('gacode'),
				'gapages' => $rs->getInt('gapages'),
				'gatransactions' => $rs->getInt('gatransactions'),
				'periodid' => $rs->getInt('periodid'),
				'language' => $this->getViewTranslation($id),
				'taxes' => $rs->getInt('taxes'),
				'showtax' => $rs->getInt('showtax'),
				'category' => $this->viewCategoryIds($rs->getInt('idview')),
				'dispatchmethods' => $this->getDispatchmethodView($id),
				'paymentmethods' => $this->getPaymentmethodView($id),
				'url' => $this->getViewUrl($id),
				'offline' => $rs->getInt('offline'),
				'offlinetext' => $rs->getString('offlinetext'),
				'cartredirect' => $rs->getString('cartredirect'),
				'apikey' => $rs->getString('apikey'),
				'invoicenumerationkind' => $rs->getString('invoicenumerationkind'),
				'invoicedefaultpaymentdue' => $rs->getInt('invoicedefaultpaymentdue'),
				'enableopinions' => $rs->getInt('enableopinions'),
				'enabletags' => $rs->getInt('enabletags'),
				'enablerss' => $rs->getInt('enablerss'),
				'catalogmode' => $rs->getInt('catalogmode'),
				'forcelogin' => $rs->getInt('forcelogin'),
				'confirmregistration' => $rs->getInt('confirmregistration'),
				'enableregistration' => $rs->getInt('enableregistration'),
				'guestcheckout' => $rs->getInt('guestcheckout'),
				'confirmorder' => $rs->getInt('confirmorder'),
				'confirmorderstatusid' => $rs->getInt('confirmorderstatusid'),
				'minimumordervalue' => $rs->getFloat('minimumordervalue'),
				'photo' => Array(
					'file' => $rs->getString('photoid')
				),
				'favicon' => Array(
					'file' => $rs->getString('favicon')
				),
				'watermark' => Array(
					'file' => $rs->getString('watermark')
				),
				'uploaderenabled' => $rs->getInt('uploaderenabled'),
				'uploadmaxfilesize' => $rs->getInt('uploadmaxfilesize'),
				'uploadchunksize' => $rs->getInt('uploadchunksize'),
				'uploadextensions' => explode(',', $rs->getString('uploadextensions')),
				'ordernotifyaddresses' => explode(',', $rs->getString('ordernotifyaddresses')),
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_VIEW_NO_EXIST'));
		}
		return $Data;
	}

	public function getViewUrl ($id)
	{
		$sql = "SELECT url
					FROM viewurl
					WHERE viewid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getString('url');
		}
		return $Data;
	}

	public function getDispatchmethodView ($id)
	{
		$sql = "SELECT dispatchmethodid
					FROM dispatchmethodview
					WHERE viewid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('dispatchmethodid');
		}
		return $Data;
	}

	public function getPaymentmethodView ($id)
	{
		$sql = "SELECT paymentmethodid 
					FROM paymentmethodview
					WHERE viewid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('paymentmethodid');
		}
		return $Data;
	}

	public function getViewTranslation ($id)
	{
		$sql = "SELECT 
					keyword_title, 
					keyword,
					keyword_description, 
					languageid
				FROM viewtranslation
				WHERE viewid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}

	public function addView ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newViewId = $this->addViewData($Data);
			if (is_array($Data['url'])){
				$this->addViewUrl($Data['url'], $newViewId);
			}
			if ($Data['category'] > 0){
				$this->addViewToCategory($Data['category'], $newViewId);
			}
			$this->addViewTranslation($Data, $newViewId);
			$this->addAssignToGroupPerView($Data['table']['ranges'], $newViewId);
			if (is_array($Data['paymentmethod'])){
				$this->addPaymentmethod($Data['paymentmethod'], $newViewId);
			}
			if (is_array($Data['dispatchmethod'])){
				$this->addDispatchethod($Data['dispatchmethod'], $newViewId);
			}
			$event = new sfEvent($this, 'admin.view.model.save', Array(
				'id' => $newViewId,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SHOP_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	public function addViewUrl ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO viewurl (url, viewid, addid)
						VALUES (:url, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('url', $value);
			$stmt->setInt('viewid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_VIEWURL_ADD'), 77, $e->getMessage());
			}
		}
	}

	public function addPaymentmethod ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO paymentmethodview (paymentmethodid, viewid, addid)
						VALUES (:paymentmethodid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('paymentmethodid', $value);
			$stmt->setInt('viewid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addDispatchethod ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO dispatchmethodview (dispatchmethodid, viewid, addid)
						VALUES (:dispatchmethodid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dispatchmethodid', $value);
			$stmt->setInt('viewid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addViewTranslation ($Data, $id)
	{
		foreach ($Data['keyword'] as $key => $val){
			$sql = 'INSERT INTO viewtranslation (keyword_title, keyword,keyword_description ,viewid, languageid, addid)
						VALUES (:keyword_title, :keyword,:keyword_description, :viewid, :languageid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', $id);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_VIEW_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
		return true;
	}

	public function addViewData ($Data)
	{
		$sql = 'INSERT INTO view (
					name, 
					namespace, 
					storeid, 
					periodid, 
					taxes,
					showtax,
					faceboookappid,
					faceboooksecret, 
					gacode, 
					gatransactions, 
					gapages,
					offline,
					offlinetext,
					cartredirect,
					apikey,
					invoicenumerationkind,
					invoicedefaultpaymentdue,
					enableopinions,
					enabletags,
					enablerss,
					catalogmode,
					forcelogin,
					confirmregistration,
					enableregistration,
					guestcheckout,
					confirmorder,
					confirmorderstatusid,
					minimumordervalue,
					photoid, 
					favicon,
					watermark,
					ordernotifyaddresses,
					addid)
				VALUES (
					:name, 
					:namespace, 
					:storeid, 
					:periodid, 
					:taxes,
					:showtax, 
					:faceboookappid,
					:faceboooksecret, 
					:gacode, 
					:gatransactions, 
					:gapages,
					:offline,
					:offlinetext,
					:cartredirect,
					:apikey,
					:invoicenumerationkind,
					:invoicedefaultpaymentdue,
					:enableopinions,
					:enabletags,
					:enablerss,
					:catalogmode,
					:forcelogin,
					:confirmregistration,
					:enableregistration,
					:guestcheckout,
					:confirmorder,
					:confirmorderstatusid,
					:minimumordervalue,
					:photoid, 
					:favicon,
					:watermark,
					:ordernotifyaddresses,
					:addid
				)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('namespace', $Data['namespace']);
		$stmt->setString('faceboookappid', $Data['faceboookappid']);
		$stmt->setString('faceboooksecret', $Data['faceboooksecret']);
		$stmt->setString('gacode', $Data['gacode']);
		if (isset($Data['gatransactions']) && ! is_null($Data['gatransactions'])){
			$stmt->setInt('gatransactions', 1);
		}
		else{
			$stmt->setInt('gatransactions', 0);
		}
		if (isset($Data['gapages']) && ! is_null($Data['gapages'])){
			$stmt->setInt('gapages', 1);
		}
		else{
			$stmt->setInt('gapages', 0);
		}
		$stmt->setInt('storeid', $Data['store']);
		$stmt->setInt('periodid', $Data['periodid']);
		$stmt->setInt('taxes', $Data['taxes']);
		$stmt->setInt('showtax', $Data['showtax']);
		if (isset($Data['offline']) && ! is_null($Data['offline'])){
			$stmt->setInt('offline', 1);
		}
		else{
			$stmt->setInt('offline', 0);
		}
		$stmt->setString('offlinetext', $Data['offlinetext']);
		if (isset($Data['enableopinions']) && ! is_null($Data['enableopinions'])){
			$stmt->setInt('enableopinions', 1);
		}
		else{
			$stmt->setInt('enableopinions', 0);
		}
		if (isset($Data['enabletags']) && ! is_null($Data['enabletags'])){
			$stmt->setInt('enabletags', 1);
		}
		else{
			$stmt->setInt('enabletags', 0);
		}
		if (isset($Data['enablerss']) && ! is_null($Data['enablerss'])){
			$stmt->setInt('enablerss', 1);
		}
		else{
			$stmt->setInt('enablerss', 0);
		}
		if (isset($Data['catalogmode']) && ! is_null($Data['catalogmode'])){
			$stmt->setInt('catalogmode', 1);
		}
		else{
			$stmt->setInt('catalogmode', 0);
		}
		if (isset($Data['forcelogin']) && ! is_null($Data['forcelogin'])){
			$stmt->setInt('forcelogin', 1);
		}
		else{
			$stmt->setInt('forcelogin', 0);
		}
		if (isset($Data['confirmregistration']) && ! is_null($Data['confirmregistration'])){
			$stmt->setInt('confirmregistration', 1);
		}
		else{
			$stmt->setInt('confirmregistration', 0);
		}
		if (isset($Data['enableregistration']) && ! is_null($Data['enableregistration'])){
			$stmt->setInt('enableregistration', 1);
		}
		else{
			$stmt->setInt('enableregistration', 0);
		}
		if (isset($Data['guestcheckout']) && ! is_null($Data['guestcheckout'])){
			$stmt->setInt('guestcheckout', 1);
		}
		else{
			$stmt->setInt('guestcheckout', 0);
		}
		if (isset($Data['confirmorder']) && ! is_null($Data['confirmorder'])){
			$stmt->setInt('confirmorder', 1);
			$stmt->setInt('confirmorderstatusid', $Data['confirmorderstatusid']);
		}
		else{
			$stmt->setInt('confirmorder', 0);
			$stmt->setNull('confirmorderstatusid');
		}
		$stmt->setFloat('minimumordervalue', $Data['minimumordervalue']);
		$stmt->setString('cartredirect', $Data['cartredirect']);
		$stmt->setString('invoicenumerationkind', $Data['invoicenumerationkind']);
		$stmt->setString('apikey', $Data['apikey']);
		$stmt->setInt('invoicedefaultpaymentdue', $Data['invoicedefaultpaymentdue']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (isset($Data['photo']['file'])){
			$stmt->setString('photoid', $Data['photo']['file']);
		}
		else{
			$stmt->setString('photoid', '');
		}
		if (isset($Data['favicon']['file'])){
			$stmt->setString('favicon', $Data['favicon']['file']);
		}
		else{
			$stmt->setString('favicon', '');
		}
		if (isset($Data['watermark']['file'])){
			$stmt->setString('watermark', $Data['watermark']['file']);
		}
		else{
			$stmt->setString('watermark', '');
		}
		if (isset($Data['uploaderenabled']) && ! is_null($Data['uploaderenabled'])){
			$stmt->setInt('uploaderenabled', 1);
			$stmt->setString('uploadmaxfilesize', $Data['uploadmaxfilesize']);
			$stmt->setString('uploadchunksize', $Data['uploadchunksize']);
			$stmt->setString('uploadextensions', implode(',', $Data['uploadextensions']));
		}
		else{
			$stmt->setInt('uploaderenabled', 0);
			$stmt->setInt('uploadmaxfilesize', 0);
			$stmt->setInt('uploadchunksize', 0);
			$stmt->setString('uploadextensions', '');
		}
		$stmt->setString('ordernotifyaddresses', implode(',', $Data['ordernotifyaddresses']));
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SHOP_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addViewToCategory ($Data, $viewid)
	{
		foreach ($Data as $category){
			$sql = 'INSERT INTO viewcategory (viewid, categoryid, addid)
						VALUES (:viewid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', $viewid);
			$stmt->setInt('categoryid', $category);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_VIEW_CATEGORY_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addAssignToGroupPerView ($array, $viewid)
	{
		foreach ($array as $key => $value){
			$sql = 'INSERT INTO assigntogroup (clientgroupid, `from`, `to`, viewid, addid)
						VALUES (:clientgroupid, :from, :to, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', $viewid);
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
			$stmt->setString('clientgroupid', $value['price']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function editView ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateView($Data, $id);
			$this->updateViewUrl($Data['url'], $id);
			$this->updateViewTranslation($Data, $id);
			$this->updateAssignToGroupPerView($Data['table']['ranges'], $id);
			$this->updateDispatchmethodView($Data['dispatchmethod'], $id);
			$this->updatePaymentmethodView($Data['paymentmethod'], $id);
			$event = new sfEvent($this, 'admin.view.model.save', Array(
				'id' => $id,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SHOP_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	public function updateViewUrl ($Data, $id)
	{
		$sql = 'DELETE FROM viewurl WHERE viewid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data) && is_array($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO viewurl (url, viewid, addid)
							VALUES (:url, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('url', $value);
				$stmt->setInt('viewid', $id);
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

	public function updatePaymentmethodView ($Data, $id)
	{
		$sql = 'DELETE FROM paymentmethodview WHERE viewid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data) && is_array($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO paymentmethodview (paymentmethodid, viewid, addid)
							VALUES (:paymentmethodid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('paymentmethodid', $value);
				$stmt->setInt('viewid', $id);
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

	public function updateDispatchmethodView ($Data, $id)
	{
		$sql = 'DELETE FROM dispatchmethodview WHERE viewid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data) && is_array($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO dispatchmethodview (dispatchmethodid, viewid, addid)
							VALUES (:dispatchmethodid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('dispatchmethodid', $value);
				$stmt->setInt('viewid', $id);
				$stmt->setInt('disable', 0);
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

	public function updateViewTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM viewtranslation WHERE viewid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		if (! empty($Data) && is_array($Data)){
			foreach ($Data['keyword'] as $key => $val){
				$sql = 'INSERT INTO viewtranslation (keyword_title, keyword,keyword_description, viewid, languageid, addid)
							VALUES (:keyword_title, :keyword,:keyword_description, :viewid, :languageid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('viewid', $id);
				$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
				$stmt->setString('keyword', $Data['keyword'][$key]);
				$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
				$stmt->setInt('languageid', $key);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_VIEW_TRANSLATION_EDIT'), 4, $e->getMessage());
				}
			}
		}
		return true;
	}

	public function updateView ($Data, $id)
	{
		$sql = 'UPDATE view SET 
					name=:name, 
					namespace=:namespace, 
					storeid=:storeid, 
					periodid=:periodid, 
					taxes=:taxes,
					showtax = :showtax, 
					editid=:editid, 
					faceboookappid = :faceboookappid,
					faceboooksecret = :faceboooksecret,
					gacode=:gacode,
					gatransactions=:gatransactions, 
					gapages=:gapages,
					offline = :offline, 
					offlinetext = :offlinetext, 
					enableopinions = :enableopinions,
					enabletags = :enabletags,
					enablerss = :enablerss,
					catalogmode = :catalogmode,
					forcelogin = :forcelogin,
					confirmregistration = :confirmregistration,
					enableregistration = :enableregistration,
					guestcheckout = :guestcheckout,
					confirmorder = :confirmorder,
					confirmorderstatusid = :confirmorderstatusid,
					minimumordervalue = :minimumordervalue,
					cartredirect = :cartredirect,
					apikey = :apikey,
					invoicenumerationkind = :invoicenumerationkind,
					invoicedefaultpaymentdue = :invoicedefaultpaymentdue,
					photoid = :photoid,
					favicon = :favicon,
					watermark = :watermark,
					uploaderenabled = :uploaderenabled,
					uploadmaxfilesize = :uploadmaxfilesize,
					uploadchunksize = :uploadchunksize,
					uploadextensions = :uploadextensions,
					ordernotifyaddresses = :ordernotifyaddresses
				WHERE idview =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('namespace', $Data['namespace']);
		$stmt->setString('faceboookappid', $Data['faceboookappid']);
		$stmt->setString('faceboooksecret', $Data['faceboooksecret']);
		$stmt->setString('gacode', $Data['gacode']);
		$stmt->setString('ordernotifyaddresses', implode(',', $Data['ordernotifyaddresses']));
		if (isset($Data['gatransactions']) && $Data['gatransactions'] == 1){
			$stmt->setInt('gatransactions', 1);
		}
		else{
			$stmt->setInt('gatransactions', 0);
		}
		if (isset($Data['gapages']) && $Data['gapages'] == 1){
			$stmt->setInt('gapages', 1);
		}
		else{
			$stmt->setInt('gapages', 0);
		}
		$stmt->setInt('storeid', $Data['store']);
		$stmt->setInt('periodid', $Data['periodid']);
		$stmt->setInt('taxes', $Data['taxes']);
		$stmt->setInt('showtax', $Data['showtax']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		if (isset($Data['offline']) && $Data['offline'] == 1){
			$stmt->setInt('offline', 1);
		}
		else{
			$stmt->setInt('offline', 0);
		}
		$stmt->setString('offlinetext', $Data['offlinetext']);
		if (isset($Data['enableopinions']) && $Data['enableopinions'] == 1){
			$stmt->setInt('enableopinions', 1);
		}
		else{
			$stmt->setInt('enableopinions', 0);
		}
		if (isset($Data['enabletags']) && $Data['enabletags'] == 1){
			$stmt->setInt('enabletags', 1);
		}
		else{
			$stmt->setInt('enabletags', 0);
		}
		if (isset($Data['enablerss']) && $Data['enablerss'] == 1){
			$stmt->setInt('enablerss', 1);
		}
		else{
			$stmt->setInt('enablerss', 0);
		}
		if (isset($Data['catalogmode']) && $Data['catalogmode'] == 1){
			$stmt->setInt('catalogmode', 1);
		}
		else{
			$stmt->setInt('catalogmode', 0);
		}
		if (isset($Data['forcelogin']) && $Data['forcelogin'] == 1){
			$stmt->setInt('forcelogin', 1);
		}
		else{
			$stmt->setInt('forcelogin', 0);
		}
		if (isset($Data['confirmregistration']) && $Data['confirmregistration'] == 1){
			$stmt->setInt('confirmregistration', 1);
		}
		else{
			$stmt->setInt('confirmregistration', 0);
		}
		if (isset($Data['enableregistration']) && $Data['enableregistration'] == 1){
			$stmt->setInt('enableregistration', 1);
		}
		else{
			$stmt->setInt('enableregistration', 0);
		}
		if (isset($Data['guestcheckout']) && $Data['guestcheckout'] == 1){
			$stmt->setInt('guestcheckout', 1);
		}
		else{
			$stmt->setInt('guestcheckout', 0);
		}
		if (isset($Data['confirmorder']) && $Data['confirmorder'] == 1){
			$stmt->setInt('confirmorder', 1);
			$stmt->setInt('confirmorderstatusid', $Data['confirmorderstatusid']);
		}
		else{
			$stmt->setInt('confirmorder', 0);
			$stmt->setNull('confirmorderstatusid');
		}
		$stmt->setFloat('minimumordervalue', $Data['minimumordervalue']);
		$stmt->setString('cartredirect', $Data['cartredirect']);
		$stmt->setString('apikey', $Data['apikey']);
		$stmt->setString('invoicenumerationkind', $Data['invoicenumerationkind']);
		$stmt->setInt('invoicedefaultpaymentdue', $Data['invoicedefaultpaymentdue']);
		if (isset($Data['photo']['file'])){
			$stmt->setString('photoid', $Data['photo']['file']);
		}
		else{
			$stmt->setString('photoid', '');
		}
		if (isset($Data['favicon']['file'])){
			$stmt->setString('favicon', $Data['favicon']['file']);
		}
		else{
			$stmt->setString('favicon', '');
		}
		if (isset($Data['watermark']['file'])){
			$stmt->setString('watermark', $Data['watermark']['file']);
		}
		else{
			$stmt->setString('watermark', '');
		}
		if (isset($Data['uploaderenabled']) && ! is_null($Data['uploaderenabled'])){
			$stmt->setInt('uploaderenabled', 1);
			$stmt->setString('uploadmaxfilesize', $Data['uploadmaxfilesize']);
			$stmt->setString('uploadchunksize', $Data['uploadchunksize']);
			$stmt->setString('uploadextensions', implode(',', $Data['uploadextensions']));
		}
		else{
			$stmt->setInt('uploaderenabled', 0);
			$stmt->setInt('uploadmaxfilesize', 0);
			$stmt->setInt('uploadchunksize', 0);
			$stmt->setString('uploadextensions', '');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SHOP_EDIT'), 13, $e->getMessage());
		}
		if ($Data['category'] > 0){
			$this->updateViewCategory($Data['category'], $id);
		}
		$this->flushCache();
		return true;
	}

	public function updateAssignToGroupPerView ($array, $id)
	{
		$sqlDelete = 'DELETE FROM assigntogroup WHERE viewid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($array) && is_array($array)){
			foreach ($array as $key => $value){
				$sql = 'INSERT INTO assigntogroup (clientgroupid, `from`, `to`, viewid, addid)
							VALUES (:clientgroupid, :from, :to, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('viewid', $id);
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
				$stmt->setString('clientgroupid', $value['price']);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
		return $array;
	}

	public function viewCategoryIds ($id)
	{
		$Data = $this->viewCategory($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function viewCategory ($id)
	{
		$sql = 'SELECT categoryid AS id
					FROM viewcategory
					WHERE viewid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function updateViewCategory ($Data, $ViewId)
	{
		$sql = 'DELETE FROM viewcategory WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', $ViewId);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data)){
			foreach ($Data as $category){
				$sql = 'INSERT INTO viewcategory (viewid, categoryid, addid)
							VALUES (:viewid, :categoryid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('viewid', $ViewId);
				$stmt->setInt('categoryid', $category);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_UPDATE'), 112, $e->getMessage());
				}
			}
		}
	}

	public function changeActiveView ($view)
	{
		$objResponse = new xajaxResponse();
		Helper::setViewId($view);
		$sql = "SELECT storeid
					FROM view
					WHERE idview=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $view);
		$rs = $stmt->executeQuery();
		$Data = Array();
		
		if ($rs->first()){
			Helper::setStoreId($rs->getInt('storeid'));
		}
		else{
			Helper::setStoreId(0);
		}
		$objResponse->script('window.location.reload(true)');
		return $objResponse;
	}

	public function getViews ()
	{
		$globaluser = $this->registry->session->getActiveUserIsGlobal();
		
		$Data = Array();
		
		$Data['0'] = Array(
			'name' => $this->registry->core->getMessage('TXT_GLOBAL_LAYER'),
			'parent' => null,
			'weight' => 0,
			'type' => ($globaluser == 1) ? 'view' : 'store'
		);
		
		if ($globaluser == 1){
			
			$sql = "SELECT idstore AS id,name
						FROM store";
			$stmt = $this->registry->db->prepareStatement($sql);
			$rs = $stmt->executeQuery();
			
			while ($rs->next()){
				$Data['0_' . $rs->getInt('id')] = Array(
					'name' => $rs->getString('name'),
					'parent' => 0,
					'weight' => $rs->getInt('id'),
					'type' => 'store'
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
				$Data[$rs->getInt('id')] = Array(
					'name' => $rs->getString('name'),
					'parent' => '0_' . $rs->getInt('storeid'),
					'weight' => $rs->getInt('id'),
					'type' => 'view'
				);
			}
		
		}
		else{
			
			$sql = 'SELECT
						UGV.viewid,
						V.storeid,
						V.name as viewname,
						UGV.groupid,
						S.idstore as storeid,
						S.name as storename
						FROM usergroupview UGV 
						lEFT JOIN view V ON UGV.viewid = V.idview
						lEFT JOIN store S ON V.storeid = S.idstore
						WHERE UGV.userid = :userid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('userid', $this->registry->session->getActiveUserid());
			$rs = $stmt->executeQuery();
			
			while ($rs->next()){
				
				$Data['0_' . $rs->getInt('storeid')] = Array(
					'name' => $rs->getString('storename'),
					'parent' => 0,
					'weight' => $rs->getInt('storeid'),
					'type' => 'store'
				);
				
				$Data[$rs->getInt('viewid')] = Array(
					'name' => $rs->getString('viewname'),
					'parent' => '0_' . $rs->getInt('storeid'),
					'weight' => $rs->getInt('viewid'),
					'type' => 'view'
				);
			
			}
		
		}
		
		return json_encode($Data);
	
	}

	public function getCategoryAll ($id)
	{
		$sql = 'SELECT 
					C.idcategory AS id,
					C.categoryid AS parent, 
					CT.name as categoryname,
					C.distinction
					FROM category C
					LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'categoryname' => $rs->getString('categoryname'),
				'distinction' => $rs->getInt('distinction'),
				'parent' => $rs->getInt('parent'),
				'allow' => $this->getCategoryViewAllow($rs->getInt('id'), $id)
			);
		}
		return $Data;
	
	}

	public function getCategoryViewAllow ($categoryid, $parentid)
	{
		$sql = 'SELECT count(VC.idviewcategory) as allow
					FROM viewcategory VC,
					productcategory PC
					WHERE VC.categoryid = :categoryid AND VC.viewid = :viewid AND PC.productid = :productid AND VC.categoryid = PC.categoryid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', $categoryid);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('productid', $parentid);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			return $rs->getInt('allow');
		}
	}

	public function getViewsByStoreId ($id)
	{
		$sql = "SELECT idview AS id,name
					FROM view WHERE storeid = :storeid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('storeid', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getDefaultViewValues ($id)
	{
		$sql = "SELECT V.name as name, C.idcurrency AS currencyid, C.currencysymbol AS currencysymbol
					FROM store S
					INNER JOIN view AS V ON V.storeid = S.idstore
					INNER JOIN currency AS C ON S.currencyid = C.idcurrency
					WHERE V.idview = :id";
		
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			return Array(
				'name' => $rs->getString('name'),
				'currencyid' => $rs->getInt('currencyid'),
				'currencysymbol' => $rs->getString('currencysymbol')
			);
		}
	
	}

	public function getViewAllSelect ()
	{
		$sql = 'SELECT idview AS id , name
					FROM view';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$id = $rs->getInt('id');
			$Data[$id] = $rs->getString('name');
		}
		return $Data;
	}

	public function getViewAll ()
	{
		$sql = 'SELECT idview AS id , name
					FROM view';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function flushCache ()
	{
		Cache::destroyObject('views');
		Cache::destroyObject('categories');
	}
}