<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 222 $
 * $Author: gekosale $
 * $Date: 2011-06-25 15:20:08 +0200 (So, 25 cze 2011) $
 * $Id: categoriesbox.php 222 2011-06-25 13:20:08Z gekosale $
 */
class LayeredNavigationBoxModel extends Model
{

	public function getPriceRangeForCategory ($id)
	{
		$sql = 'SELECT AVG(P.sellprice * CR.exchangerate * (1 + (V.value / 100))) AS average FROM product P
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN vat V ON P.vatid = V.idvat
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				WHERE PC.categoryid = :id AND P.enable = 1
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$rs = $stmt->executeQuery();
		$avg = 100;
		if ($rs->first()){
			$avg = $rs->getFloat('average');
		}
		
		$Data = Array();
		$Ranges = Array();
		$sql = 'SELECT 
					(CEILING(ROUND(P.sellprice * CR.exchangerate * (1 + (V.value / :avg)),0)/:avg) * :avg) AS price
				FROM product P
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN vat V ON P.vatid = V.idvat
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				WHERE PC.categoryid = :id AND P.enable = 1
                GROUP BY price
                ORDER BY price ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setFloat('avg', $avg);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$rs = $stmt->executeQuery();
		$Data[] = 0;
		while ($rs->next()){
			$Data[] = number_format($rs->getFloat('price'), 0, '.', '');
		}
		$total = count($Data);
		
		for ($i = 0; $i < $total; $i ++){
			if (isset($Data[$i + 1])){
				$Ranges[] = Array(
					'step' => 'od' . $Data[$i] . 'do' . $Data[$i + 1],
					'label' => 'od ' . $Data[$i] . ' do ' . $Data[$i + 1]
				);
			}
		}
		return $Ranges;
	}

	public function getPriceRangeForProducts ($ids)
	{
		$Data = Array();
		$Ranges = Array();
		$sql = 'SELECT 
					(CEILING(ROUND(P.sellprice * (1 + (V.value / 100)),0)/100) * 100) AS price
				FROM product P
				LEFT JOIN vat V ON P.vatid = V.idvat
				WHERE P.idproduct IN (:ids) AND P.enable = 1
                GROUP BY price
                ORDER BY price ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('ids', $ids);
		$rs = $stmt->executeQuery();
		$Data[] = 0;
		while ($rs->next()){
			$Data[] = number_format($rs->getFloat('price'), 0, '.', '');
		}
		$total = count($Data);
		
		for ($i = 0; $i < $total; $i ++){
			if (isset($Data[$i + 1])){
				$Ranges[] = Array(
					'step' => 'od' . $Data[$i] . 'do' . $Data[$i + 1],
					'label' => 'od ' . $Data[$i] . ' do ' . $Data[$i + 1]
				);
			}
		}
		return $Ranges;
	}

	public function getLayeredAttributesForCategory ($id)
	{
		$Data = Array();
		$sql = 'SELECT 
					AP.name AS attributegroupname, 
					AP.idattributeproduct AS attributegroupid,
					APV.name AS attributename, 
					APV.idattributeproductvalue AS attributeid
				FROM productattributeset AS PAS 
				LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid = PAS.idproductattributeset 
				LEFT JOIN attributeproductvalue APV ON PAVS.attributeproductvalueid = APV.idattributeproductvalue 
				LEFT JOIN attributeproduct AS AP ON APV.attributeproductid = AP.idattributeproduct 
				LEFT JOIN product AS P ON PAS.productid = P.idproduct 
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				WHERE 
					PAS.status = 1 AND
					PC.categoryid = :id AND 
					P.enable = 1 AND 
					IF(P.trackstock = 1, PAS.stock > 0, 1) AND
					IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
				ORDER BY APV.name ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('attributegroupid')]['name'] = $rs->getString('attributegroupname');
			$Data[$rs->getInt('attributegroupid')]['attributes'][$rs->getInt('attributeid')] = $rs->getString('attributename');
		}
		foreach ($Data as $key => $val){
			natsort($val['attributes']);
			$Data[$key]['attributes'] = $val['attributes'];
		}
		return $Data;
	}

	public function getStaticAttributesForCategory ($id)
	{
		$sql = 'SELECT 
					SAT.name AS attributename,
					SGT.name AS groupname,
					PSA.staticgroupid,
					PSA.staticattributeid
				FROM productstaticattribute PSA 
				LEFT JOIN staticattributetranslation SAT ON SAT.staticattributeid = PSA.staticattributeid AND SAT.languageid = :languageid
				LEFT JOIN staticgrouptranslation SGT ON SGT.staticgroupid = PSA.staticgroupid AND SGT.languageid = :languageid
				LEFT JOIN productcategory PC ON PC.productid = PSA.productid
				WHERE PC.categoryid = :id
				ORDER BY SAT.name ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('staticgroupid')]['name'] = $rs->getString('groupname');
			$Data[$rs->getInt('staticgroupid')]['attributes'][$rs->getInt('staticattributeid')] = $rs->getString('attributename');
		}
		return $Data;
	}

	public function getLayeredAttributesByProductIds ($ids)
	{
		$Data = Array();
		$sql = 'SELECT 
					AP.name AS attributegroupname, 
					AP.idattributeproduct AS attributegroupid,
					APV.name AS attributename, 
					APV.idattributeproductvalue AS attributeid
				FROM productattributeset AS PAS 
				LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid = PAS.idproductattributeset 
				LEFT JOIN attributeproductvalue APV ON PAVS.attributeproductvalueid = APV.idattributeproductvalue 
				LEFT JOIN attributeproduct AS AP ON APV.attributeproductid = AP.idattributeproduct 
				LEFT JOIN product AS P ON PAS.productid = P.idproduct 
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				WHERE P.idproduct IN (:ids) AND P.enable = 1 AND PAS.status = 1
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('ids', $ids);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('attributegroupid')]['name'] = $rs->getString('attributegroupname');
			$Data[$rs->getInt('attributegroupid')]['attributes'][$rs->getInt('attributeid')] = $rs->getString('attributename');
		}
		return $Data;
	}

	public function getProductsForAttributes ($categoryid, $attributes, $staticattributes)
	{
		$numGroups = count($attributes);
		$Data = Array();
		$Products = Array(
			0
		);
		$sql = 'SELECT 
					P.idproduct
				FROM productattributeset AS PAS 
				LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid = PAS.idproductattributeset 
				LEFT JOIN attributeproductvalue APV ON PAVS.attributeproductvalueid = APV.idattributeproductvalue 
				LEFT JOIN attributeproduct AS AP ON APV.attributeproductid = AP.idattributeproduct 
				LEFT JOIN product AS P ON PAS.productid = P.idproduct 
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				WHERE 
					IF(:categoryid > 0, PC.categoryid = :categoryid, 1) AND 
					AP.idattributeproduct IN (:group) AND 
					APV.idattributeproductvalue IN (:attributes) AND 
					P.enable = 1 AND
					PAS.status = 1
				GROUP BY P.idproduct
			';
		foreach ($attributes as $group => $attribute){
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $categoryid);
			$stmt->setInt('group', $group);
			$stmt->setINInt('attributes', $attribute);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[$rs->getInt('idproduct')][] = 1;
			}
		}
		foreach ($Data as $idproduct => $count){
			if (count($count) == $numGroups){
				$Products[] = $idproduct;
			}
		}
		
		$Filtered = Array(
			0
		);
		$FilteredProducts = Array();
		
		if (count($staticattributes) > 0){
			$staticFilter = Array();
			foreach ($staticattributes as $groupid => $sattributes){
				foreach ($sattributes as $sattribute){
					$staticFilter[] = $sattribute;
				}
			}
			$sql = 'SELECT 
						COUNT(DISTINCT PSA.staticattributeid) AS total,
						PSA.productid
					FROM productstaticattribute PSA
					LEFT JOIN product AS P ON PSA.productid = P.idproduct 
					LEFT JOIN productcategory PC ON PC.productid = P.idproduct
					WHERE 
						IF(:categoryid > 0, PC.categoryid = :categoryid, 1) AND 
						PSA.staticattributeid IN (:attributes) AND 
						P.enable = 1
					GROUP BY P.idproduct
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $categoryid);
			$stmt->setINInt('attributes', $staticFilter);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Filtered[$rs->getInt('productid')] = $rs->getInt('total');
			}
			$numGroups = count($staticattributes);
			foreach ($Filtered as $idproduct => $count){
				if ($count >= $numGroups){
					$FilteredProducts[] = $idproduct;
				}
			}
		}
		
		if (count($attributes) > 0 && count($staticattributes) > 0){
			$Final = Array(
				0
			);
			foreach ($Products as $key => $idproduct){
				if (in_array($idproduct, $FilteredProducts)){
					$Final[] = $idproduct;
				}
			}
			return $Final;
		}
		
		if (count($attributes) == 0 && count($staticattributes) > 0){
			return $FilteredProducts;
		}
		
		if (count($attributes) > 0 && count($staticattributes) == 0){
			return $Products;
		}
	}
}