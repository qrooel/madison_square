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

	//____________________________________________________________________________________________//
	//																							  //
	//                          FUNKCJE WSPOMAGAJĄCE GARBAGE COLLECTOR                            //
	//____________________________________________________________________________________________//
	

	//zapisywanie danych do tablicy missingCart. 
	//dane uzupełniane są, jeśli zadziała garbage collector
	//na wybraną sesję, której kontent zawiera niepusty koszyk
	public function saveMissingCartData ($cart, $sessionid)
	{
		try{
			$missingCartId = $this->saveMissingCart($sessionid);
			if ($missingCartId > 0){
				$this->saveMissingCartProducts($cart, $missingCartId);
			}
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
	}

	//zapisanie danych o porzuconym koszyku- tablica missingCart
	public function saveMissingCart ($sessionid)
	{
		
		$sql = "INSERT INTO missingcart
					(clientid, clientmail, sessionid, viewid)
				VALUES 
					(:clientid, :clientmail, :sessionid, :viewid)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', $_SESSION['CurrentState']['MainsideViewId'][0]);
		$stmt->setInt('clientid', $_SESSION['CurrentState']['Clientid'][0]);
		$stmt->setString('clientmail', $_SESSION['CurrentState']['ClientEmail'][0]);
		$stmt->setString('sessionid', $sessionid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	
	}

	//zapis do bazy info o produktach w koszyku
	public function saveMissingCartProducts ($cart, $missingCartId)
	{
		$viewid = $_SESSION['CurrentState']['MainsideViewId'][0];
		foreach ($cart as $productsmissingcart => $product){

			if (isset($product['standard']) && $product['standard'] == 1){
				$sql = "SELECT * FROM product WHERE idproduct = :id";
				$Data = Array();
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('id', $product['idproduct']);
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = "INSERT INTO missingcartproduct
							(missingcartid, productid, stock, qty, productattributesetid, viewid)
						VALUES 
							(:missingcartid, :productid, :stock, :qty, 0, :viewid)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('missingcartid', $missingCartId);
					$stmt->setInt('productid', $product['idproduct']);
					$stmt->setInt('stock', $product['stock']);
					$stmt->setInt('qty', $product['qty']);
					$stmt->setInt('viewid', $viewid);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
			//zapis produktów z atrybutami
			if (isset($product['attributes']) && $product['attributes'] != NULL){
				foreach ($product['attributes'] as $attributesmissingcart => $attribute){
					$sql = "SELECT * FROM product WHERE idproduct = :id";
					$Data = Array();
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('id', $attribute['idproduct']);
					$rs = $stmt->executeQuery();
					if ($rs->first()){
						$sql = "INSERT INTO missingcartproduct
							(missingcartid, productid, stock, qty, productattributesetid, viewid)
						VALUES 
							(:missingcartid, :productid, :stock, :qty, :productattributesetid, :viewid)";
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('missingcartid', $missingCartId);
						$stmt->setInt('productid', $attribute['idproduct']);
						$stmt->setInt('stock', $attribute['stock']);
						$stmt->setInt('qty', $attribute['qty']);
						$stmt->setInt('productattributesetid', $attribute['attr']);
						$stmt->setInt('viewid', $viewid);
						try{
							$stmt->executeQuery();
						}
						catch (Exception $e){
							throw new Exception($e->getMessage());
						}
					}
				}
			}
		
		}
	}

	//Stwórz tablicę z informacjami o usuwanej przez garbage collector sesji
	//Tablica zostanie utwórzona tylko wtedy, gdy dana sesja ($sessionid) 
	//będzie ostatnią zapisaną sesją danego klienta ($rs->getString('sessionid'))
	public function checkMissingCartSessionid ($sessionid)
	{
		$sql = "SELECT 
					S.sessionid, 
					S.sessioncontent, 
					S.adddate, 
					S.clientid,
					S.cart
				FROM sessionhandler S 
				WHERE S.sessionid = :sessionid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('sessionid', $sessionid);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'sessionid' => $sessionid,
					'sessioncontent' => $rs->getString('sessioncontent'),
					'cart' => json_decode($rs->getString('cart'), true),
					'adddate' => $rs->getString('adddate')
				);
			}
		}
		catch (FrontendException $e){
			new FrontendException($e->getMessage());
		}
		return $Data;
	}

	//sprawdź, czy usuwana przez garbage collector sesja, zawiera dane o koszyku 
	//i koszyk należy do zalogowanego klienta- jeśli tak, to true,
	//w przeciwnym razie false
	public function checkSessionHandlerHasCartData ($cart)
	{
		if (is_array($cart) && ! empty($cart)){
			return true;
		}
		else{
			return false;
		}
	}

	//____________________________________________________________________________________________//
	//																							  //
	//                           FUNKCJE OBSŁUGUJĄCE PORZUCONE KOSZYKI                            //
	//____________________________________________________________________________________________//
	

	public function checkMissingCartForClient ($clientid)
	{
		//sprawdzenie, czy są dane w tablicy missingCart
		$checkDataFromMissingCart = $this->getMissingCart($clientid);
		$Data = Array();
		//jeśli brak danych w tablicy missingCart, 
		//sprawdź, czy dane znajdują się w tablicy sessionhandler 
		if (! is_array($checkDataFromMissingCart) && ($checkDataFromMissingCart == 0 || $checkDataFromMissingCart == NULL)){
			$checkDataSessionHandler = $this->getClientMissingCartSessionHandler($clientid);
			if ($checkDataSessionHandler != 0 || $checkDataSessionHandler != NULL){
				//utwórz tablicę z danych tablicy session handler-> sesyjny koszyczek
				$DataSessionHandler = $this->makeCartFromSessionhandler($checkDataSessionHandler);
				$Data = $DataSessionHandler;
			}
		}
		else{
			//utwórz tablicę danych z missing cart- sesyjny koszyczek
			$DataMissingCart = $this->makeCartFromMissingCart($checkDataFromMissingCart);
			$Data = $DataMissingCart;
		}
		return $Data;
	}

	//utworenie tablicy z poprzedniej sesji danego klienta.
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
		catch (FrontendException $fe){
			new FrontendException($fe->message('Error while selecting session content from sessionhandler.'));
		}
		return $Data;
	}

	//pobranie danych z missing cart
	public function getMissingCart ($clientid)
	{
		$sql = "SELECT 
					MC.idmissingcart, 
					MC.sessionid 
				FROM missingcart MC
				WHERE MC.clientid = :clientid
				ORDER BY MC.adddate DESC
				LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('clientid', $this->registry->session->getActiveClientid());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'clientid' => $clientid,
					'idmissingcart' => $rs->getInt('idmissingcart'),
					'products' => $this->getProductFromMissingCart($rs->getInt('idmissingcart')),
					'sessionid' => $rs->getString('sessionid')
				);
			}
			else{
				$Data = 0;
			}
		}
		catch (FrontendException $fe){
			new FrontendException($fe->message('Error while selecting session content from sessionhandler.'));
		}
		return $Data;
	}

	public function cleanMissingCart ($clientId)
	{
		$sql = "DELETE FROM missingcartproduct WHERE missingcartid IN(SELECT idmissingcart FROM missingcart WHERE clientid = :clientid)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientId);
		$rs = $stmt->executeQuery();
		
		$sql = "DELETE FROM missingcart WHERE clientid = :clientid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientId);
		$rs = $stmt->executeQuery();
	}

	//missing cart-pobranie informacji o produktach
	public function getProductFromMissingCart ($idmissingcart)
	{
		$sql = "SELECT 
					MCP.idmissingcartproduct, 
					MCP.productid, 
					MCP.qty, 
					MCP.productattributesetid
				FROM missingcartproduct MCP
				WHERE missingcartid = :idmissingcart";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idmissingcart', $idmissingcart);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'idmissingcartproduct' => $rs->getInt('idmissingcartproduct'),
					'productid' => $rs->getInt('productid'),
					'qty' => $rs->getInt('qty'),
					'productattributesetid' => $rs->getInt('productattributesetid')
				);
			}
		}
		catch (FrontendException $fe){
			new FrontendException($fe->message('Error while selecting product from missingcart.'));
		}
		return $Data;
	}

	public function makeCartFromSessionhandler ($DataSessionHandler)
	{
		$Data = Array();
		if (is_array($DataSessionHandler) && isset($DataSessionHandler['decodecontent']['CurrentState']['Cart'])){
			foreach ($DataSessionHandler['decodecontent']['CurrentState']['Cart'][0] as $products){
				if (isset($products['attributes']) && $products['attributes'] != NULL){
					foreach ($products['attributes'] as $attr){
						$Data[$products['idproduct']] = Array(
							'idproduct' => $products['idproduct'],
							'attributes' => $attr['attr'],
							'qty' => $attr['qty']
						);
					}
				}
				if (isset($products['standard']) && $products['standard'] == 1){
					$Data[$products['idproduct']] = Array(
						'idproduct' => $products['idproduct'],
						'qty' => $products['qty'],
						'standard' => $products['standard'],
						'attributes' => $products['attributes']
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
		if (is_array($DataMissingCart['products'])){
			foreach ($DataMissingCart['products'] as $key => $products){
				if ($products['productattributesetid'] != NULL){
					$Data[$products['productid']]['attributes'][$products['productattributesetid']] = Array(
						'idproduct' => $products['productid'],
						'attributes' => $products['productattributesetid'],
						'qty' => $products['qty']
					);
				}
				else{
					$Data[$products['productid']] = Array(
						'idproduct' => $products['productid'],
						'qty' => $products['qty'],
						'standard' => 1
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
		$count = count($vars);
		for ($i = 0; $i < $count; $i ++){
			$result[$vars[$i ++]] = unserialize($vars[$i]);
		}
		return $result;
	}

	public function deleteDataFromMissingCartAfterUpdateSession ($clientid)
	{
		$sql = "SELECT MC.idmissingcart
				FROM missingcart MC
				WHERE MC.clientid = :clientid";
		$stmt->this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientid);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idmissingcart = $rs->getInt('idmissingcart');
				if ($idmissingcart > 0){
					$sql = "DELETE FROM missingcartproduct
							WHERE missingcartid = :idmissingcart";
					$stmt->this->registry->db->prepareStatement($sql);
					$stmt->setInt('idmissingcart', $idmissingcart);
					try{
						$rs = $stmt->executeQuery();
						$sql = "DELETE FROM missingcart 
								WHERE idmissingcart = :idmissingcart";
						$stmt->this->registry->db->prepareStatement($sql);
						$stmt->setInt('idmissingcart', $idmissingcart);
						$rs = $stmt->executeQuery();
					}
					catch (FrontendException $fe){
						new FrontendException($fe->message('Error while selecting product from missingcart.'));
					}
				}
			}
		}
		catch (FrontendException $fe){
			new FrontendException($fe->message('Error while selecting product from missingcart.'));
		}
	}

}
?>