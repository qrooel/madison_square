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
 * $Id: view.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class viewModel extends Model
{

	public function getViews ()
	{
		if (($Data = Cache::loadObject('views')) === FALSE){
			$sql = "SELECT idstore AS id,name
					FROM store";
			$Data = Array();
			$stmt = $this->registry->db->prepareStatement($sql);
			$rs = $stmt->executeQuery();
			$Data['0'] = Array(
				'name' => 'Globalny',
				'parent' => null,
				'weight' => 0,
				'type' => 'view'
			);
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
			Cache::saveObject('views', $Data, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		return json_encode($Data);
	}

	public function getUploadSettings ()
	{
		$sql = 'SELECT
					uploaderenabled,
					uploadmaxfilesize,
					uploadchunksize,
					uploadextensions
				FROM view WHERE idview = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'uploaderenabled' => $rs->getInt('uploaderenabled'),
				'uploadmaxfilesize' => $rs->getInt('uploadmaxfilesize'),
				'uploadchunksize' => $rs->getInt('uploadchunksize'),
				'uploadextensions' => $rs->getString('uploadextensions')
			);
		}
		return $Data;
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
				'taxes' => $rs->getInt('taxes'),
				'showtax' => $rs->getInt('showtax'),
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
				'minimumordervalue' => $rs->getFloat('minimumordervalue'),
				'photo' => Array(
					'file' => $rs->getString('photoid')
				),
				'favicon' => Array(
					'file' => $rs->getString('favicon')
				),
				'uploaderenabled' => $rs->getInt('uploaderenabled'),
				'uploadmaxfilesize' => $rs->getInt('uploadmaxfilesize'),
				'uploadchunksize' => $rs->getInt('uploadchunksize'),
				'uploadextensions' => explode(',', $rs->getString('uploadextensions'))
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_VIEW_NO_EXIST'));
		}
		return $Data;
	}
}