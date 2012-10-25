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
 * $Id: missingcart.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class missingcartModel extends Model
{

	public function checkMissingCartForClient ($clientid)
	{
		$checkDataFromMissingCart = $this->getMissingCart($clientid);
		$Data = Array();
		if (! is_array($checkDataFromMissingCart) && ($checkDataFromMissingCart == 0 || $checkDataFromMissingCart == NULL)){
			$checkDataSessionHandler = $this->getClientMissingCartSessionHandler($clientid);
			if ($checkDataSessionHandler != 0 || $checkDataSessionHandler != NULL){
				$DataSessionHandler = $this->makeCartFromSessionhandler($checkDataSessionHandler);
				$Data = $DataSessionHandler;
			}
		}
		else{
			$DataMissingCart = $this->makeCartFromMissingCart($checkDataFromMissingCart);
			$Data = $DataMissingCart;
		}
		return $Data;
		;
	}

	public function getClientMissingCartSessionHandler ($clientid)
	{
		$sql = "SELECT S.sessionid, S.sessioncontent, S.adddate, S.clientid
					FROM sessionhandler S
					WHERE S.clientid = :clientid
		          	ORDER BY S.adddate DESC
	  				LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientid);
		try{
			$Data = Array();
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'sessionid' => $rs->getString('sessionid'),
					'sessioncontent' => $rs->getString('sessioncontent'),
					'decodecontent' => $this->decode_session($rs->getString('sessioncontent')),
					'adddate' => $rs->getString('adddate')
				);
			}
			else{
				$Data = 0;
			}
		}
		catch (Exception $e){
			new Exception($e->message('Error while selecting session content from sessionhandler.'));
		}
		return $Data;
	}

	public function getMissingCart ($clientid)
	{
		$sql = "SELECT MC.idmissingcart, MC.dispatchmethodid, MC.paymentmethodid 
					FROM missingcart MC
					WHERE MC.clientid = :clientid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('clientid', $this->registry->session->getActiveClientid());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'clientid' => $clientid,
					'idmissingcart' => $rs->getInt('idmissingcart'),
					'dispatchmethodid' => $rs->getInt('dispatchmethodid'),
					'paymentmethodid' => $rs->getInt('paymentmethodid'),
					'products' => $this->getProductFromMissingCart($idmissingcart),
					'sessionid' => $this->getString('sessionid')
				);
			}
			else{
				$Data = 0;
			}
		}
		catch (Exception $e){
			new Exception($e->message('Error while selecting session content from sessionhandler.'));
		}
		return $Data;
	}

	public function getProductFromMissingCart ($idmissingcart)
	{
		$sql = "SELECT MCP.idmissingcartproduct, MCP.productid, MCP.qty, MCP.productattributesetid
					FROM missingcartproduct MCP
					LEFT JOIN product P ON P.idproduct = MCP.productid
					WHERE missingcartid = :idmissingcart";
		$stmt->this->registry->db->prepareStatement($sql);
		$stmt->setInt('idmissingcart', $idmissingcart);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'idmissingcartproduct' => $rs->getInt('idmissingcartproduct'),
					'productid' => $rs->getInt('productid'),
					'qty' => $rs->getInt('qty'),
					'productattributesetid' => $rs->getInt('productattributesetid'),
					'name' => $rs->getString('name')
				);
			}
		}
		catch (Exception $e){
			new Exception($e->message('Error while selecting product from missingcart.'));
		}
	}

	public function makeCartFromSessionhandler ($DataSessionHandler)
	{
		$Data = Array();
		if (is_array($DataSessionHandler) && isset($DataSessionHandler['decodecontent']['CurrentState']['Cart'])){
			foreach ($DataSessionHandler['decodecontent']['CurrentState']['Cart'][0] as $products){
				if (isset($products['attributes']) && $products['attributes'] != NULL){
					foreach ($products['attributes'] as $attr){
						$Data[] = Array(
							'id' => $products['idproduct'],
							'attributes' => $attr['attr'],
							'qty' => $attr['qty'],
							'name' => $attr['name']
						);
					}
				}
				if (isset($products['standard']) && $products['standard'] == 1){
					$Data[] = Array(
						'id' => $products['idproduct'],
						'qty' => $products['qty'],
						'standard' => $products['standard'],
						'attributes' => $products['attributes'],
						'name' => $products['name']
					);
				}
			}
		}
		else{
			$Data = 0;
		}
		return $Data;
	}

	public function makeCartFromMissingCart ($DataMissingCart)
	{
		$Data = Array();
		if (is_array($DataMissingCart)){
			foreach ($DataMissingCart['products'] as $products){
				if ($products['productattributesetid'] != NULL){
					$Data[$products['idproduct']]['attributes'][$products['productattributesetid']] = Array(
						'id' => $products['idproduct'],
						'attributes' => $products['productattributesetid'],
						'qty' => $attr['qty'],
						'name' => $attr['name']
					);
				}
				else{
					$Data[$products['idproduct']] = Array(
						'id' => $products['productid'],
						'qty' => $products['qty'],
						'standard' => 1,
						'name' => $products['name']
					);
				}
			}
		
		}
		else{
			$Data = 0;
		}
		return $Data;
	}

	public function decode_session ($sessioncontent)
	{
		$serializecontent = $sessioncontent;
		$unserializecontent = $this->unserializesession($serializecontent);
		return $unserializecontent;
	}

	function unserializesession ($serializecontent)
	{
		$result = Array();
		$vars = preg_split('/([a-zA-Z0-9]+)\|/', $serializecontent, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		for ($i = 0; $i <= 1; $i ++){
			$result[$vars[$i ++]] = unserialize($vars[$i]);
		}
		return $result;
	}
}