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
 * $Id: store.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class storeModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('store', Array(
			'idstore' => Array(
				'source' => 'idstore'
			),
			'name' => Array(
				'source' => 'name'
			)
		));
		$datagrid->setFrom('
				store
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getStoreForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteStore ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteStore'
		), $this->getName());
	}

	public function deleteStore ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idstore' => $id
			), $this->getName(), 'deleteStore');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addStore ($Data)
	{
		$sql = 'INSERT INTO store (
						name, 
						countryid, 
						currencyid,
						defaultphotoid, 
						bankname, 
						banknr, 
						krs, 
						nip, 
						companyname, 
						shortcompanyname, 
						placename,
						postcode, 
						street, 
						streetno, 
						placeno, 
						province, 
						invoiceshopslogan,
						isinvoiceshopslogan, 
						isinvoiceshopname)
					VALUES (
						:name, 
						:countryid, 
						:currencyid, 
						:defaultphotoid,
						:bankname, 
						:banknr, 
						:krs, 
						:nip, 
						:companyname, 
						:shortcompanyname,
						:placename, 
						:postcode, 
						:street, 
						:streetno, 
						:placeno, 
						:province,
						:invoiceshopslogan, 
						:isinvoiceshopslogan, 
						:isinvoiceshopname)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('countryid', $Data['countries']);
		$stmt->setInt('currencyid', $Data['curriencies']);
		$stmt->setString('bankname', $Data['bankname']);
		$stmt->setString('banknr', $Data['banknr']);
		$stmt->setString('krs', $Data['krs']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('shortcompanyname', $Data['shortcompanyname']);
		$stmt->setString('placename', $Data['placename']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('province', $Data['province']);
		if (isset($Data['isinvoiceshopslogan']['value']) && $Data['isinvoiceshopslogan']['value'] == 2){
			//shop name with tag
			$stmt->setInt('isinvoiceshopslogan', 1);
			$stmt->setInt('isinvoiceshopname', 0);
			$stmt->setString('invoiceshopslogan', $Data['invoiceshopslogan']);
		}
		else{
			//only shop name
			$stmt->setInt('isinvoiceshopslogan', 0);
			$stmt->setInt('isinvoiceshopname', 1);
			$stmt->setString('invoiceshopslogan', '');
		}
		
		if (($Data['photo'][0]) > 0){
			$stmt->setInt('defaultphotoid', $Data['photo'][0]);
		}
		else{
			$stmt->setInt('defaultphotoid', $this->registry->core->setDefaultPhoto());
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERROR_STORE_ADD'), 18, $e->getMessage());
		}
		$this->flushCache();
	}

	public function getStoreView ($id)
	{
		$sql = 'SELECT 
						S.name, 
						S.countryid, 
						C.idcountry, 
						C.name as countryname, 
						S.defaultphotoid,
						S.currencyid, 
						CR.idcurrency, 
						CR.currencyname, 
						CR.currencysymbol,
						S.bankname, 
						S.banknr,
						S.krs, 
						S.nip, 
						S.companyname, 
						S.shortcompanyname,
						S.placename, 
						S.postcode, 
						S.street, 
						S.streetno, 
						S.placeno, 
						S.province, 
						S.invoiceshopslogan, 
						S.isinvoiceshopslogan, 
						S.isinvoiceshopname
					FROM store S
					LEFT JOIN country C ON S.countryid = C.idcountry 
					LEFT JOIN currency CR ON S.currencyid = CR.idcurrency
					WHERE idstore =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'countryid' => $rs->getInt('countryid'),
				'idcountry' => $rs->getInt('idcountry'),
				'countryname' => $rs->getString('countryname'),
				'currencyid' => $rs->getInt('currencyid'),
				'photo' => $rs->getInt('defaultphotoid'),
				'idcurrency' => $rs->getInt('idcurrency'),
				'currencyname' => $rs->getString('currencyname'),
				'currencysymbol' => $rs->getString('currencysymbol'),
				'bankname' => $rs->getString('bankname'),
				'banknr' => $rs->getString('banknr'),
				'krs' => $rs->getString('krs'),
				'nip' => $rs->getString('nip'),
				'companyname' => $rs->getString('companyname'),
				'shortcompanyname' => $rs->getString('shortcompanyname'),
				'placename' => $rs->getString('placename'),
				'postcode' => $rs->getString('postcode'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'province' => $rs->getString('province'),
				'invoiceshopslogan' => $rs->getString('invoiceshopslogan'),
				'isinvoiceshopslogan' => $rs->getInt('isinvoiceshopslogan'),
				'isinvoiceshopname' => $rs->getInt('isinvoiceshopname'),
				'gallerysettings' => $this->getGallerySettings()
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_STORE_NO_EXIST'));
		}
		return $Data;
	}

	public function editStore ($Data, $id)
	{
		$sql = 'UPDATE store
					SET 
						name=:name, 
						countryid=:countryid,
	  					currencyid=:currencyid, 
	  					defaultphotoid=:defaultphotoid, 
	  					bankname=:bankname, 
	  					banknr=:banknr, 
	  					krs=:krs, 
	  					nip=:nip, 
						companyname=:companyname, 
						shortcompanyname=:shortcompanyname,
						placename=:placename, 
						postcode=:postcode, 
						street=:street, 
						streetno=:streetno, 
						placeno=:placeno, 
						province=:province,
						invoiceshopslogan=:invoiceshopslogan,
						isinvoiceshopslogan=:isinvoiceshopslogan, 
						isinvoiceshopname=:isinvoiceshopname
					WHERE idstore = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('countryid', $Data['countries']);
		$stmt->setInt('currencyid', $Data['curriencies']);
		$stmt->setString('bankname', $Data['bankname']);
		$stmt->setString('banknr', $Data['banknr']);
		$stmt->setString('krs', $Data['krs']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('shortcompanyname', $Data['shortcompanyname']);
		$stmt->setString('placename', $Data['placename']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('province', $Data['province']);
		if (isset($Data['isinvoiceshopslogan']['value']) && $Data['isinvoiceshopslogan']['value'] == 2){
			//shop name with tag
			$stmt->setInt('isinvoiceshopslogan', 1);
			$stmt->setInt('isinvoiceshopname', 0);
			$stmt->setString('invoiceshopslogan', $Data['invoiceshopslogan']);
		}
		else{
			//only shop name
			$stmt->setInt('isinvoiceshopslogan', 0);
			$stmt->setInt('isinvoiceshopname', 1);
			$stmt->setString('invoiceshopslogan', '');
		}
		//$stmt->setInt('storeid', $Data['storeid']);
		if (($Data['photo'][0]) > 0){
			$stmt->setInt('defaultphotoid', $Data['photo'][0]);
		}
		else{
			$stmt->setInt('defaultphotoid', $this->registry->core->setDefaultPhoto());
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_STORE_EDIT'), 18, $e->getMessage());
		}
		$this->flushCache();
	}

	public function getGallerySettings ()
	{
		$sql = 'SELECT width, height, keepproportion FROM gallerysettings WHERE width IS NOT NULL';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['width'][] = $rs->getInt('width');
			$Data['height'][] = $rs->getInt('height');
			$Data['keepproportion'][] = $rs->getInt('keepproportion');
		}
		return $Data;
	}

	public function getStoreAll ()
	{
		$sql = 'SELECT idstore AS id , name
					FROM store';
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

	public function getStoreToSelect ()
	{
		$Data = $this->getStoreAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function flushCache ()
	{
		Cache::destroyObject('views');
	}
}