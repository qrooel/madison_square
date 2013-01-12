<?php
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */

	class CeneoModel extends Model {

		public function getProductListIntegration() {
			$sql = "SELECT 
					  PC.categoryid AS id, 
					  P.idproduct, 
					  P.stock,
					  P.weight,
					  PT.name, 
					  (P.sellprice * (1 + (V.value / 100)) * CR.exchangerate) AS sellprice,
					  IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * (1 + (V.value / 100)) * CR.exchangerate, NULL) AS discountprice,
					  PT.shortdescription, 
					  Photo.photoid, 
					  NC.name as ceneooriginal,
					  CN.categoryid, 
					  NC.idceneo, 
					  CN.ceneoid,
					  PT.seo,
					  NC.path
					FROM product P
					LEFT JOIN vat V ON P.vatid= V.idvat
					LEFT JOIN producttranslation PT ON PT.productid = P.idproduct AND PT.languageid=:languageid
					LEFT JOIN productcategory PC ON PC.productid = P.idproduct
					LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
					INNER JOIN productphoto Photo ON Photo.productid = P.idproduct AND Photo.mainphoto=1
					INNER JOIN categoryceneo CN ON CN.categoryid = PC.categoryid
					INNER JOIN ceneo NC ON NC.idorginal = CN.ceneoid
				WHERE P.enable = 1 
	            GROUP BY P.idproduct";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
			$rs = $stmt->executeQuery();
			$Data = Array();
			while($rs->next()) {
				$Data[] = Array( 
					'categoryid' => $rs->getString('id'),
					'productid' => $rs->getString('idproduct'),
					'stock' => $rs->getString('stock'),
					'avail' => ($rs->getString('stock') > 0) ? 1 : 7,
					'weight' => $rs->getString('weight'),
					'seo' => $rs->getString('seo'),
					'name' => $rs->getString('name'),
					'shortdescription' => $rs->getString('shortdescription'),
					'sellprice' => number_format((!is_null($rs->getString('discountprice'))) ? $rs->getString('discountprice') : $rs->getString('sellprice'),2,'.',''),
					'photoid' => $rs->getInt('photoid'),
					'idproduct' => $rs->getInt('idproduct'),
					'ceneo' => str_replace('|', '\\', $rs->getString('path'))
				);
			}
			foreach($Data as $key=>$Product){
				$Image = App::getModel('gallery')->getOrginalImageById($Product['photoid']);
				$Data[$key]['photo'] = App::getModel('gallery')->getImagePath($Image, App::getURLAdress());
			}
			return $Data;
		}
	
		public function generateCeneoTreeByCategoryId($id) {
			$sql = "SELECT C.name FROM ceneo C LEFT JOIN categoryceneo CN ON C.idorginal = CN.ceneoid WHERE CN.categoryid = :catid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('catid', $id);
			$rs = $stmt->executeQuery();
			while($rs->next()) {
				return $rs->getString('name');
			}
		}
	}