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

class NokautModel extends Model {

	public function getProductListIntegration() {
		$sql = "SELECT
					PC.categoryid AS id, 
					P.idproduct, 
					PT.name, 
					(P.sellprice * (1 + (V.value / 100)) * CR.exchangerate) AS sellprice,
					IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * (1 + (V.value / 100)) * CR.exchangerate, NULL) AS discountprice,
					PT.shortdescription, 
					Photo.photoid,
					PT.seo,
					(SELECT
					    GROUP_CONCAT(SUBSTRING(CT.name, 1) ORDER BY C.order DESC SEPARATOR ' / ')
					FROM categorytranslation CT
					LEFT JOIN categorypath C ON C.ancestorcategoryid = CT.categoryid
					WHERE C.categoryid = PC.categoryid AND CT.languageid = :languageid) AS nokaut,
					PRT.name AS producername,
					P.weight
				FROM product P
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN producttranslation PT ON PT.productid = P.idproduct AND PT.languageid=:languageid
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				LEFT JOIN categorypath CP ON CP.ancestorcategoryid = PC.categoryid
				LEFT JOIN categorytranslation CT ON CP.ancestorcategoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN producertranslation PRT ON PRT.producerid = P.producerid AND PRT.languageid = :languageid
				LEFT JOIN productphoto Photo ON Photo.productid = P.idproduct AND Photo.mainphoto = 1
	            GROUP BY P.idproduct";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while($rs->next()) {
			$Data[] = Array(
				'categoryid' => $rs->getString('id'),
				'seo' => $rs->getString('seo'),
				'categoryname' => $rs->getString('nokaut'),
				'producername' => $rs->getString('producername'),
				'productid' => $rs->getString('idproduct'),
				'name' => $rs->getString('name'),
				'shortdescription' => $rs->getString('shortdescription'),
				'sellprice' => number_format((!is_null($rs->getString('discountprice'))) ? $rs->getString('discountprice') : $rs->getString('sellprice'),2,'.',''),
				'photoid' => $rs->getInt('photoid'),
				'idproduct' => $rs->getInt('idproduct'),
			);
		}
		foreach($Data as $key=>$Product){
			$Image = App::getModel('gallery')->getOrginalImageById($Product['photoid']);
			$Data[$key]['photo'] = App::getModel('gallery')->getImagePath($Image, App::getURLAdress());
		}
		return $Data;
	}
}