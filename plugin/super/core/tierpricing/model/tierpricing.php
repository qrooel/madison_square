<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009-2011 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms
 * of the GNU General Public License Version 3, 29 June 2007 as published by the
 * Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it
 * through the
 * world-wide-web, please send an email to license@verison.pl so we can send you
 * a copy immediately.
 *
 * $Revision: 309 $
 * $Author: gekosale $
 * $Date: 2011-08-01 21:10:16 +0200 (Pn, 01 sie 2011) $
 * $Id: invoice.php 309 2011-08-01 19:10:16Z gekosale $
 */
class TierPricingModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getTierPricingForProductById ($id)
	{
		$sql = 'SELECT
				   	*
				FROM producttierprice 
				WHERE productid = :productid AND viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('clientgroupid')]['ranges'][] = Array(
				'min' => $rs->getInt('qtymin'),
				'max' => $rs->getInt('qtymax'),
				'price' => $rs->getInt('tierprice')
			);
		}
		return $Data;
	}

	public function populateFields ($event, $id)
	{
		if (Helper::getViewId() > 0){
			
			$Data = $this->getTierPricingForProductById($id);
			$populate = Array(
				'tierpricing_data' => Array(
					'standard_tier_price' => Array(
						'tiers' => isset($Data[0]) ? $Data[0] : Array()
					)
				)
			);
			
			foreach ($this->clientGroups as $clientGroup){
				if (isset($Data[$clientGroup['id']])){
					$populate['tierpricing_data']['tiers_' . $clientGroup['id']]['tier_' . $clientGroup['id']] = $Data[$clientGroup['id']];
				}
				else{
					$populate['tierpricing_data']['tiers_' . $clientGroup['id']]['tier_' . $clientGroup['id']] = Array();
				}
			}
			
			$event->setReturnValues($populate);
		}
	}

	public function getTierPricingById ($id)
	{
		$sql = 'SELECT
				   	*
				FROM producttierprice
				WHERE productid = :productid AND viewid = :viewid AND clientgroupid = :group';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('group', (int) $this->registry->session->getActiveClientGroupid());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'min' => $rs->getInt('qtymin'),
				'max' => $rs->getInt('qtymax'),
				'discount' => $rs->getInt('tierprice')
			);
		}
		return $Data;
	}

	public function productBoxAssign ($event, $id)
	{
		$sql = 'SELECT
				   	*
				FROM producttierprice 
				WHERE productid = :productid AND viewid = :viewid AND clientgroupid = :group';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('group', (int) $this->registry->session->getActiveClientGroupid());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			if (($rs->getInt('qtymin') + $rs->getInt('qtymax')) > 0){
				$Data[] = Array(
					'min' => $rs->getInt('qtymin'),
					'max' => $rs->getInt('qtymax'),
					'discount' => $rs->getInt('tierprice')
				);
			}
		}
		
		$event->setReturnValues(Array(
			'tierpricing' => $Data
		));
	}

	public function addFields ($request)
	{
		if (Helper::getViewId() > 0){
			$this->clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
			
			$tiers = &$request['form']->AddChild(new FE_Fieldset(Array(
				'name' => 'tierpricing_data',
				'label' => 'Progi cenowe'
			)));
			
			$standardTierPrice = $tiers->AddChild(new FE_Fieldset(Array(
				'name' => 'standard_tier_price',
				'label' => 'Cena standardowa'
			)));
			
			$standardTierPrice->AddChild(new FE_RangeEditor(Array(
				'name' => 'tiers',
				'suffix' => '% rabatu',
				'range_suffix' => $this->registry->core->getMessage('TXT_QTY'),
				'allow_vat' => false,
				'range_precision' => 0
			)));
			
			foreach ($this->clientGroups as $clientGroup){
				
				$tierGroups[$clientGroup['id']] = $tiers->AddChild(new FE_Fieldset(Array(
					'name' => 'tiers_' . $clientGroup['id'],
					'label' => $clientGroup['name']
				)));
				
				$tierGroups[$clientGroup['id']]->AddChild(new FE_RangeEditor(Array(
					'name' => 'tier_' . $clientGroup['id'],
					'suffix' => '% rabatu',
					'range_suffix' => $this->registry->core->getMessage('TXT_QTY'),
					'allow_vat' => false,
					'range_precision' => 0
				)));
			}
		}
		else{
			$tiers = &$request['form']->AddChild(new FE_Fieldset(Array(
				'name' => 'tierpricing_data',
				'label' => 'Progi cenowe'
			)));
			
			$tiers->AddChild(new FE_Tip(Array(
				'tip' => '<p align="center">Progi cenowe ustalane są per sklep. Przełącz się selektorem sklepów w prawym, górnym rogu.</strong></p>',
				'direction' => FE_Tip::DOWN
			)));
		}
	}

	public function saveSettings ($request)
	{
		$Data = $request['data'];
		$id = $request['id'];
		
		$sql = 'DELETE FROM producttierprice WHERE productid = :productid AND viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
		}
		
		$this->clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
		
		if (isset($Data['tiers']['ranges'])){
			foreach ($Data['tiers']['ranges'] as $key => $val){
				$sql = 'INSERT INTO producttierprice SET
							productid = :productid,
							clientgroupid = :clientgroupid,
							qtymin = :qtymin,
							qtymax = :qtymax,
							tierprice = :tierprice,
							viewid = :viewid
				';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $id);
				$stmt->setInt('clientgroupid', 0);
				$stmt->setInt('qtymin', $val['min']);
				$stmt->setInt('qtymax', $val['max']);
				$stmt->setFloat('tierprice', $val['price']);
				$stmt->setInt('viewid', Helper::getViewId());
				$stmt->executeUpdate();
			}
		}
		
		foreach ($this->clientGroups as $clientGroup){
			if (isset($Data['tier_' . $clientGroup['id']]['ranges'])){
				foreach ($Data['tier_' . $clientGroup['id']]['ranges'] as $key => $val){
					if ($val['price'] > 0){
						$sql = 'INSERT INTO producttierprice SET
							productid = :productid,
							clientgroupid = :clientgroupid,
							qtymin = :qtymin,
							qtymax = :qtymax,
							tierprice = :tierprice,
							viewid = :viewid
						';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('productid', $id);
						$stmt->setInt('clientgroupid', $clientGroup['id']);
						$stmt->setInt('qtymin', $val['min']);
						$stmt->setInt('qtymax', $val['max']);
						$stmt->setFloat('tierprice', $val['price']);
						$stmt->setInt('viewid', Helper::getViewId());
						$stmt->executeUpdate();
					}
				}
			}
		}
	}
}