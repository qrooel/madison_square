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
 * $Id: currencieslist.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class currencieslistModel extends ModelWithDatagrid
{
	
	protected $currencyCodes = array(
		'AUD' => 'AUD',
		'CAD' => 'CAD',
		'EUR' => 'EUR',
		'GBP' => 'GBP',
		'JPY' => 'JPY',
		'USD' => 'USD',
		'NZD' => 'NZD',
		'CHF' => 'CHF',
		'HKD' => 'HKD',
		'SGD' => 'SGD',
		'SEK' => 'SEK',
		'DKK' => 'DKK',
		'PLN' => 'PLN',
		'NOK' => 'NOK',
		'HUF' => 'HUF',
		'CZK' => 'CZK',
		'ILS' => 'ILS',
		'MXN' => 'MXN',
		'BRL' => 'BRL',
		'MYR' => 'MYR',
		'PHP' => 'PHP',
		'TWD' => 'TWD',
		'THB' => 'THB'
	);

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getCurrenciesALLToSelect ()
	{
		$tmp = Array();
		foreach ($this->currencyCodes as $key){
			$tmp[$key] = $key;
		}
		return $tmp;
	}

	public function downloadExchangeRates ($basecurrency)
	{
		
		$xml_file = "http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $xml_file);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rates = curl_exec($curl);
		curl_close($curl);
		
		preg_match_all("{<Cube\s*currency='(\w*)'\s*rate='([\d\.]*)'/>}is", $rates, $xml_rates);
		array_shift($xml_rates);
		
		$exchange_rate['EUR'] = 1;
		
		for ($i = 0; $i < count($xml_rates[0]); $i ++){
			$exchange_rate[$xml_rates[0][$i]] = $xml_rates[1][$i];
		}
		$Data = Array();
		
		foreach ($exchange_rate as $currency => $rate){
			if ((is_numeric($rate)) && ($rate != 0)){
				$Data[$currency] = $rate;
			}
		}
		$Rates = Array();
		if (isset($Data[$basecurrency])){
			foreach ($Data as $currency => $rate){
				$Rates[$currency] = number_format((1 / $Data[$basecurrency]) * $Data[$currency], 4, '.', '');
			}
		}
		
		return $Rates;
	}

	public function initDatagrid ($datagrid)
	{
		
		$datagrid->setTableData('currency', Array(
			'id' => Array(
				'source' => 'C.idcurrency'
			),
			'name' => Array(
				'source' => 'C.currencyname'
			),
			'currencysymbol' => Array(
				'source' => 'C.currencysymbol'
			),
			'currencyto' => Array(
				'source' => 'C2.currencysymbol',
				'prepareForSelect' => true
			),
			'exchangerate' => Array(
				'source' => 'GROUP_CONCAT(DISTINCT SUBSTRING(CONCAT(C2.currencysymbol,\': \', CR2.exchangerate), 1) SEPARATOR \'<br>\')'
			)
		));
		
		$datagrid->setFrom('
				currency C
				LEFT JOIN currencyrates CR ON CR.currencyfrom = C.idcurrency
				LEFT JOIN currency C2 ON CR.currencyto = C2.idcurrency
				LEFT JOIN currencyrates CR2 ON CR2.currencyfrom = C.idcurrency AND CR2.currencyto = CR.currencyto 
			');
		
		$datagrid->setGroupBy('
				C.idcurrency
			');
	
	}

	public function getExchangeRateForCurrency ($id)
	{
		return $id + 1;
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getCurrencieslistForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteCurrencieslist ($datagrid, $id)
	{
		$this->deleteCurrency($id);
		$this->flushCache();
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function doAJAXUpdateCurrencieslist ($datagridId, $id)
	{
		
		try{
			$this->refreshCurrency($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_REFRESH_CURRENCY')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXRefreshAllCurrencies ()
	{
		$objResponse = new xajaxResponse();
		
		$sql = 'SELECT idcurrency AS id	FROM currency';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$this->refreshCurrency($rs->getInt('id'));
		}
		$objResponse->script('theDatagrid.LoadData();');
		return $objResponse;
	}

	public function refreshCurrency ($id)
	{
		
		$sql = 'SELECT currencysymbol FROM currency WHERE idcurrency = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('id', $id);
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			
			$sql = 'DELETE FROM currencyrates WHERE currencyfrom = :id';
			$stmtDelete = $this->registry->db->prepareStatement($sql);
			$stmtDelete->setString('id', $id);
			$stmtDelete->executeQuery();
			
			$currencyfrom = trim($rs->getString('currencysymbol'));
			
			$exchangerates = $this->downloadExchangeRates($currencyfrom);
			
			foreach ($exchangerates as $currency => $rate){
				
				$sql = 'SELECT idcurrency FROM currency	WHERE currencysymbol = :symbol';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('symbol', $currency);
				$rs = $stmt->executeQuery();
				
				if ($rs->first()){
					$sql = 'INSERT INTO currencyrates SET
								currencyfrom = :currencyfrom, 
								currencyto = :currencyto, 
								exchangerate = :exchangerate';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('currencyfrom', $id);
					$stmt->setInt('currencyto', $rs->getInt('idcurrency'));
					$stmt->setString('exchangerate', $rate);
					$stmt->executeQuery();
				}
			}
		}
	
	}

	public function getFirstnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('firstname', $request, $processFunction);
	}

	public function getSurnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('surname', $request, $processFunction);
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getCodeForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('code', $request, $processFunction);
	}

	public function deleteCurrency ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idcurrency' => $id
			), $this->getName(), 'deleteCurrency');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addCurrencieslist ($Data)
	{
		$sql = 'INSERT INTO currency SET
					currencyname = :name, 
					currencysymbol = :symbol,
					decimalseparator = :decimalseparator,
					decimalcount 	= :decimalcount,
					thousandseparator = :thousandseparator,
					positivepreffix = :positivepreffix,
					positivesuffix = :positivesuffix,
					negativepreffix = :negativepreffix,
					negativesuffix = :negativesuffix';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('symbol', $Data['symbol']);
		$stmt->setString('decimalseparator', $Data['decimalseparator']);
		$stmt->setInt('decimalcount', $Data['decimalcount']);
		$stmt->setString('thousandseparator', $Data['thousandseparator']);
		$stmt->setString('positivepreffix', $Data['positivepreffix']);
		$stmt->setString('positivesuffix', $Data['positivesuffix']);
		$stmt->setString('negativepreffix', $Data['negativepreffix']);
		$stmt->setString('negativesuffix', $Data['negativesuffix']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CURRENCY_ADD'), 4, $e->getMessage());
		}
		$id = $stmt->getConnection()->getIdGenerator()->getId();
		$this->addCurrencyView($Data['view'], $id);
		$this->flushCache();
		return true;
	}

	public function addCurrencyView ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO currencyview (currencyid, viewid, addid)
						VALUES (:currencyid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('currencyid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CURRENCY_VIEW_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function editCurrencieslist ($Data, $id)
	{
		
		
		$sql = 'UPDATE currency SET
						currencyname = :name, 
						currencysymbol = :symbol,
						decimalseparator = :decimalseparator,
						decimalcount = :decimalcount,
						thousandseparator = :thousandseparator,
						positivepreffix = :positivepreffix,
						positivesuffix = :positivesuffix,
						negativepreffix = :negativepreffix,
						negativesuffix = :negativesuffix
					WHERE idcurrency = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('symbol', $Data['symbol']);
		$stmt->setString('decimalseparator', $Data['decimalseparator']);
		$stmt->setInt('decimalcount', $Data['decimalcount']);
		$stmt->setString('thousandseparator', $Data['thousandseparator']);
		$stmt->setString('positivepreffix', $Data['positivepreffix']);
		$stmt->setString('positivesuffix', $Data['positivesuffix']);
		$stmt->setString('negativepreffix', $Data['negativepreffix']);
		$stmt->setString('negativesuffix', $Data['negativesuffix']);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CURRENCIES_LIST_ADD'), 4, $e->getMessage());
		}
		$this->editCurrencyView($Data['view'], $id);
		
		$sql = 'DELETE FROM currencyrates WHERE currencyfrom = :id';
		$stmtDelete = $this->registry->db->prepareStatement($sql);
		$stmtDelete->setString('id', $id);
		$stmtDelete->executeQuery();
		
		foreach($Data as $key => $val){
			if(substr($key,0,8) == 'currency'){
				
				$sql = 'INSERT INTO currencyrates SET
							currencyfrom = :currencyfrom, 
							currencyto = :currencyto, 
							exchangerate = :exchangerate';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('currencyfrom', $id);
				$stmt->setInt('currencyto', substr($key,9));
				$stmt->setString('exchangerate', $val);
				$stmt->executeQuery();
			}
		}

		
		$this->flushCache();
		return true;
	}

	public function editCurrencyView ($Data, $id)
	{
		$sql = 'DELETE FROM currencyview WHERE currencyid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO currencyview (currencyid, viewid,addid)
							VALUES (:currencyid, :viewid,:addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('currencyid', $id);
				$stmt->setInt('viewid', $value);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CURRENCY_VIEW_EDIT'), 4, $e->getMessage());
				}
			}
		}
	}

	public function getCurrencieslistView ($id)
	{
		$sql = "SELECT 
					currencyname, 
					currencysymbol,
					decimalseparator,
					decimalcount,
					thousandseparator,
					positivepreffix,
					positivesuffix,
					negativepreffix,
					negativesuffix
				FROM currency 
				WHERE idcurrency =:id
		";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('currencyname'),
				'symbol' => $rs->getString('currencysymbol'),
				'decimalseparator' => $rs->getString('decimalseparator'),
				'decimalcount' => $rs->getInt('decimalcount'),
				'thousandseparator' => $rs->getString('thousandseparator'),
				'positivepreffix' => $rs->getString('positivepreffix'),
				'positivesuffix' => $rs->getString('positivesuffix'),
				'negativepreffix' => $rs->getString('negativepreffix'),
				'negativesuffix' => $rs->getString('negativesuffix'),
				'exchangerates' => $this->getExchangeRatesForCurrency($id),
				'view' => $this->getCurrencyView($id)
			);
		}
		return $Data;
	}

	public function getCurrencyView ($id)
	{
		$sql = "SELECT viewid
					FROM currencyview
					WHERE currencyid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function calculateCurrencyExchangeRate ($from, $to)
	{
		return $to;
	}

	public function getExchangeRatesForCurrency ($id)
	{
		$sql = "SELECT 
					CR.currencyto,
					CR.exchangerate 
				FROM currencyrates CR 
				WHERE CR.currencyfrom = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['currency_'.$rs->getInt('currencyto')] = $rs->getString('exchangerate');
		}
		
		return $Data;
	}

	public function getCurrencies ()
	{
		$sql = 'SELECT CR.idcurrency, CR.currencyname, CR.currencysymbol
					FROM currency CR 
					ORDER BY currencysymbol ASC';
		$results = $this->registry->db->executeQuery($sql);
		return $results->getAllRows();
	}

	public function getCurrencyForSelect ()
	{
		$results = $this->getCurrencies();
		$Data = Array();
		
		foreach ($results as $value){
			$Data[$value['idcurrency']] = $value['currencysymbol'] . " (" . $value['currencyname'] . ") ";
		}
		return $Data;
	}

	public function getCurrencyIds ()
	{
		$results = $this->getCurrencies();
		$Data = Array();
		
		foreach ($results as $value){
			$Data[$value['currencysymbol']] = $value['idcurrency'];
		}
		return $Data;
	}

	public function flushCache ()
	{
		//		App::getModel('dataset')->flushCache();
		Cache::destroyObject('currencies');
	}
}