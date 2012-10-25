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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: categoriesbox.php 438 2011-08-27 09:29:36Z gekosale $
 */

class MainCategoriesBoxModel extends Model
{

	public function getMainCategories ()
	{
		$sql = "SELECT 
					C.idcategory, 
					CT.name,
					CT.seo,
					C.photoid,
					CT.shortdescription,
					CT.description,
     				COUNT(PC.productid) AS totalproducts,
     				MIN(P.sellprice) AS minsellprice
				FROM category C
				LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
				LEFT JOIN categorypath CP ON CP.ancestorcategoryid = C.idcategory
				LEFT JOIN productcategory PC ON CP.categoryid = PC.categoryid
				LEFT JOIN product P ON PC.productid = P.idproduct
				LEFT JOIN categorytranslation CT ON CT.categoryid = idcategory AND CT.languageid = :languageid
				WHERE C.categoryid IS NULL AND VC.viewid=:viewid AND C.enable = 1
				GROUP BY C.idcategory";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'idcategory' => $rs->getInt('idcategory'),
				'qry' => $rs->getInt('totalproducts'),
				'seo' => $rs->getString('seo'),
				'minsellprice' => $this->registry->core->processPrice($rs->getString('minsellprice')),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'photo' => $this->getImagePath($rs->getInt('photoid'))
			);
		}
		return $Data;
	}

	public function getImagePath ($id)
	{
		if ($id > 0){
			return App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($id));
		}
	}

}