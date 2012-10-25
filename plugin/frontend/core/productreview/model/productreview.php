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
 * $Id: productreview.php 655 2012-04-24 08:51:44Z gekosale $
 */

class productreviewModel extends Model
{

	public function getProductReviews ($productid)
	{
		$sql = "SELECT
					PR.review,
					AES_DECRYPT(CD.firstname,:encryptionKey) AS firstname,
					PR.adddate,
					PR.idproductreview
					FROM productreview PR
					LEFT JOIN clientdata CD ON CD.clientid = PR.clientid
					LEFT JOIN client C ON CD.clientid = C.idclient
				WHERE productid = :productid AND C.viewid = :viewid
				ORDER BY adddate ASC
					";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$sql = "SELECT 
								PR.rangetypeid,
								PR.value,
								RTT.name
							FROM
								productrange PR
								LEFT JOIN rangetypetranslation RTT ON RTT.rangetypeid = PR.rangetypeid AND RTT.languageid = :languageid
							WHERE
								PR.productreviewid = :reviewid";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('reviewid', $rs->getInt('idproductreview'));
				$stmt->setInt('languageid', Helper::getLanguageId());
				$rangesRes = $stmt->executeQuery();
				$ranges = Array();
				while ($rangesRes->next()){
					$ranges[] = $rangesRes->getRow();
				}
				$Data[] = Array(
					'firstname' => $rs->getString('firstname'),
					'review' => $rs->getString('review'),
					'adddate' => $rs->getString('adddate'),
					'ranges' => $ranges
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while doing sql query- getProductReviews (productreview)');
		}
		return $Data;
	}
}
?>