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
 * $Id: rulescart.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class RulesCartModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function doAJAXDeleteRulesCart ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteRulesCart'
		), $this->getName());
	}

	public function deleteRulesCart ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idrulescart' => $id
			), $this->getName(), 'deleteRulesCart');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getRulesCartAll ()
	{
		$sql = 'SELECT 
						idrulescart AS id,
						name,
						distinction
					FROM rulescart
						ORDER BY distinction';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'distinction' => $rs->getInt('distinction'),
				'parent' => null
			);
		}
		return $Data;
	
	}

	public function getSimpleRulesCart ($id)
	{
		$sql = "SELECT idcategory AS id, name, description,  discount
					FROM category WHERE idcategory=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'description' => $rs->getString('description'),
				'discount' => $rs->getFloat('discount'),
				'products' => $this->getProductsRulesCart($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_NO_EXIST'));
		}
		return $Data;
	}

	public function getProductsRulesCart ($id)
	{
		$sql = "SELECT P.name as productname
					FROM product P
					LEFT JOIN productcategory PC ON PC.productid = P.idproduct
					WHERE categoryid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'productname' => $rs->getString('productname')
			);
		}
		return $Data;
	}

	public function getRulesCartView ($id)
	{
		$sql = 'SELECT idrulescart AS id, name, suffixtypeid, discount, datefrom, dateto, discountforall
					FROM rulescart
					WHERE idrulescart= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'discount' => $rs->getFloat('discount'),
				'suffixtypeid' => $rs->getInt('suffixtypeid'),
				'name' => $rs->getString('name'),
				'datefrom' => $rs->getString('datefrom'),
				'dateto' => $rs->getString('dateto'),
				'discountforall' => $rs->getInt('discountforall')
			);
			return $Data;
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getRulesCartClientGroupView ($id)
	{
		$sql = 'SELECT clientgroupid, suffixtypeid, discount
					FROM rulescartclientgroup
					WHERE rulescartid= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'clientgroupid' => $rs->getInt('clientgroupid'),
				'suffixtypeid' => $rs->getInt('suffixtypeid'),
				'discount' => $rs->getFloat('discount')
			);
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getRulesCartDeliverersView ($id)
	{
		$sql = 'SELECT pkid
					FROM rulescartrule
					WHERE rulescartid= :id
					AND ruleid=(SELECT idrule FROM rule WHERE tablereferer LIKE "dispatchmethod"
								AND rulekindofid=2)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('pkid')] = $rs->getInt('pkid');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getRulesCartPaymentsView ($id)
	{
		$sql = 'SELECT pkid
					FROM rulescartrule
					WHERE rulescartid= :id
					AND ruleid=(SELECT idrule FROM rule WHERE tablereferer LIKE "paymentmethod"
								AND rulekindofid=2)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('pkid')] = $rs->getInt('pkid');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getRulesCartOtherDinamicDataConditionsView ($id)
	{
		$sql = 'SELECT RCR.idrulescartrule, RCR.pricefrom, RCR.priceto, RCR.ruleid,
						R.field
					FROM rulescartrule RCR
						LEFT JOIN rule R ON RCR.ruleid = R.idrule
					WHERE rulescartid= :id
						AND pkid IS NULL';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('idrulescartrule')] = Array(
				'pricefrom' => $rs->getFloat('pricefrom'),
				'priceto' => $rs->getFloat('priceto'),
				'ruleid' => $rs->getInt('ruleid'),
				'field' => $rs->getString('field')
			);
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getRulesCartViews ($id)
	{
		$sql = "SELECT viewid
					FROM rulescartview
					WHERE rulescartid= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function addEmptyRulesCart ($request)
	{
		$data = Array(
			'name' => (isset($request['name']) && strlen($request['name'])) ? $request['name'] : $this->registry->core->getMessage('TXT_NEW_RULES_CART')
		);
		return Array(
			'id' => $this->addRulesCart($data)
		);
	}

	public function changeRulesCartOrder ($request)
	{
		if (! isset($request['items']) || ! is_array($request['items'])){
			throw new Exception('No data received.');
		}
		$sql = 'UPDATE rulescart
					SET distinction = :distinction
					WHERE
						idrulescart = :id';
		foreach ($request['items'] as $item){
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $item['id']);
			$stmt->setInt('distinction', $item['weight']);
			$stmt->executeUpdate();
		}
		return Array(
			'status' => $this->registry->core->getMessage('TXT_RULE_CART_ORDER_SAVED')
		);
	}

	public function editRulesCart ($submitedData, $idRulesCart)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->rulesCartEdit($submitedData, $idRulesCart);
			$this->rulesCartClientGroupEdit($submitedData, $idRulesCart);
			$this->rulesCartRuleEdit($submitedData, $idRulesCart);
			$this->rulesCartViewEdit($submitedData['view'], $idRulesCart);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_RULE_CART_EDIT'), 3002, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function rulesCartEdit ($submitedData, $idRulesCart)
	{
		$sql = 'UPDATE rulescart
					SET name= :name, 
						datefrom= :datefrom, 
						dateto= :dateto, 
						discountforall= :discountforall,
						suffixtypeid= :suffixtypeid, 
						discount= :discount, 
						editid= :editid
					WHERE idrulescart = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (isset($submitedData['discountforall']) && $submitedData['discountforall'] == 1){
			$stmt->setInt('discountforall', $submitedData['discountforall']);
			$stmt->setInt('suffixtypeid', $submitedData['suffixtypeid']);
			$stmt->setFloat('discount', $submitedData['discount']);
		}
		else{
			$stmt->setInt('discountforall', 0);
			$stmt->setInt('suffixtypeid', NULL);
			$stmt->setFloat('discount', 0);
		}
		$stmt->setInt('id', $idRulesCart);
		$stmt->setString('name', $submitedData['name']);
		if (isset($submitedData['datefrom']) && ! empty($submitedData['datefrom'])){
			$stmt->setString('datefrom', $submitedData['datefrom']);
		}
		else{
			$stmt->setNull('datefrom');
		}
		if (isset($submitedData['dateto']) && ! empty($submitedData['dateto'])){
			$stmt->setString('dateto', $submitedData['dateto']);
		}
		else{
			$stmt->setNull('dateto');
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
			return false;
		}
		return true;
	}

	public function rulesCartClientGroupEdit ($submitedData, $idRulesCart)
	{
		$sql = "DELETE FROM rulescartclientgroup
					WHERE rulescartid= :rulescartid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('rulescartid', $idRulesCart);
		try{
			$stmt->executeUpdate();
			$clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
			if (isset($submitedData['discountforall']) && $submitedData['discountforall'] == 1){
				foreach ($clientGroups as $clientGroup){
					$sql = 'INSERT INTO rulescartclientgroup (rulescartid, clientgroupid, suffixtypeid, discount, addid)
								VALUES (:rulescartid, :clientgroupid, :suffixtypeid, :discount, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('rulescartid', $idRulesCart);
					$stmt->setInt('clientgroupid', $clientGroup['id']);
					$stmt->setInt('suffixtypeid', $submitedData['suffixtypeid']);
					$stmt->setFloat('discount', $submitedData['discount']);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_UPDATE_CLIENTGROUP_RULE_CART'), 112, $e->getMessage());
					}
				}
			}
			else{
				foreach ($clientGroups as $clientGroup){
					if (isset($submitedData['groupid_' . $clientGroup['id']]) && $submitedData['groupid_' . $clientGroup['id']] > 0){
						$sql = 'INSERT INTO rulescartclientgroup (rulescartid, clientgroupid, suffixtypeid, discount, addid)
									VALUES (:rulescartid, :clientgroupid, :suffixtypeid, :discount,:addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('rulescartid', $idRulesCart);
						$stmt->setInt('clientgroupid', $clientGroup['id']);
						$stmt->setInt('suffixtypeid', $submitedData['suffixtypeid_' . $clientGroup['id']]);
						$stmt->setFloat('discount', $submitedData['discount_' . $clientGroup['id']]);
						$stmt->setInt('addid', $this->registry->session->getActiveUserid());
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_UPDATE_CLIENTGROUP_RULE'), 112, $e->getMessage());
						}
					}
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_UPDATE_CLIENTGROUP_RULE_CART'), 112, $e->getMessage());
		}
	}

	public function rulesCartRuleEdit ($submitedData, $idRulesCart)
	{
		$sql = "DELETE FROM rulescartrule 
					WHERE rulescartid= :rulescartid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('rulescartid', $idRulesCart);
		try{
			$stmt->executeUpdate();
			if (isset($submitedData['deliverers']) && $submitedData['deliverers'] != NULL && count($submitedData['deliverers']) > 0){
				foreach ($submitedData['deliverers'] as $delivererKey => $delivererValue){
					$sql = "INSERT INTO rulescartrule 
									(rulescartid, ruleid, pkid)
								VALUES (:rulescartid, :ruleid, :pkid)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('rulescartid', $idRulesCart);
					$stmt->setInt('ruleid', 9);
					$stmt->setInt('pkid', $delivererValue);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
			if (isset($submitedData['payments']) && $submitedData['payments'] != NULL && count($submitedData['payments']) > 0){
				foreach ($submitedData['payments'] as $paymentKey => $paymentValue){
					$sql = "INSERT INTO rulescartrule 
									(rulescartid, ruleid, pkid)
								VALUES (:rulescartid, :ruleid, :pkid)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('rulescartid', $idRulesCart);
					$stmt->setInt('ruleid', 10);
					$stmt->setInt('pkid', $paymentValue);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
			if (isset($submitedData['cart_price_from']) && $submitedData['cart_price_from'] > 0){
				$sql = "INSERT INTO rulescartrule 
								(rulescartid, ruleid, pricefrom)
							VALUES (:rulescartid, :ruleid, :pricefrom)";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('rulescartid', $idRulesCart);
				$stmt->setInt('ruleid', 11);
				$stmt->setFloat('pricefrom', $submitedData['cart_price_from']);
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
			if (isset($submitedData['cart_price_to']) && $submitedData['cart_price_to'] > 0){
				$sql = "INSERT INTO rulescartrule 
								(rulescartid, ruleid, priceto)
							VALUES (:rulescartid, :ruleid, :priceto)";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('rulescartid', $idRulesCart);
				$stmt->setInt('ruleid', 12);
				$stmt->setFloat('priceto', $submitedData['cart_price_to']);
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
			
			if (isset($submitedData['dispatch_price_from']) && $submitedData['dispatch_price_from'] > 0){
				$sql = "INSERT INTO rulescartrule 
								(rulescartid, ruleid, pricefrom)
							VALUES (:rulescartid, :ruleid, :pricefrom)";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('rulescartid', $idRulesCart);
				$stmt->setInt('ruleid', 13);
				$stmt->setFloat('pricefrom', $submitedData['dispatch_price_from']);
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
			if (isset($submitedData['dispatch_price_to']) && $submitedData['dispatch_price_to'] > 0){
				$sql = "INSERT INTO rulescartrule 
								(rulescartid, ruleid, priceto)
							VALUES (:rulescartid, :ruleid, :priceto)";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('rulescartid', $idRulesCart);
				$stmt->setInt('ruleid', 14);
				$stmt->setFloat('priceto', $submitedData['dispatch_price_to']);
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_EDIT_RULES_CART_RULE'), 112, $e->getMessage());
		}
		return true;
	}

	public function rulesCartViewEdit ($views, $rulesCartId)
	{
		$sql = "DELETE FROM rulescartview 
					WHERE rulescartid= :rulescartid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('rulescartid', $rulesCartId);
		try{
			$stmt->executeUpdate();
			if ($views != NULL && count($views) > 0){
				foreach ($views as $viewKey => $viewValue){
					$sql = "INSERT INTO rulescartview 
									(rulescartid, viewid)
								VALUES (:rulescartid, :viewid)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('rulescartid', $rulesCartId);
					$stmt->setInt('viewid', $viewValue);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addRulesCart ($Data)
	{
		$sql = 'INSERT INTO rulescart (name, addid)
					VALUES (:name,:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CART_RULE_ADD'), 3003, $e->getMessage());
		}
		$cartRuleId = $stmt->getConnection()->getIdGenerator()->getId();
		return $cartRuleId;
	}
}