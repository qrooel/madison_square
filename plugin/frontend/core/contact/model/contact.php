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
 * $Id: contact.php 655 2012-04-24 08:51:44Z gekosale $
 */

class contactModel extends Model
{

	public function getEmailContact ()
	{
		$sql = 'SELECT
					C.idcontact, 
					CT.email, 
					CT.name 
				FROM contact C
				LEFT JOIN contacttranslation CT ON CT.contactid = C.idcontact AND CT.languageid = :languageid
				LEFT JOIN contactview CV ON CV.contactid =idcontact
			 	WHERE C.publish = 1 AND CV.viewid = :viewid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'idcontact' => $rs->getInt('idcontact'),
				'name' => $rs->getString('name'),
				'email' => $rs->getString('email')
			);
		}
		return $Data;
	}

	public function getContactToSelect ()
	{
		$Data = $this->getEmailContact();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['idcontact']] = $key['name'];
		}
		return $tmp;
	}

	public function getDepartmentMail ($idcontact)
	{
		$sql = 'SELECT
					email 
				FROM contacttranslation 
				WHERE contactid = :idcontact AND languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idcontact', $idcontact);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$email = $rs->getString('email');
		}
		return $email;
	}

	public function getContactList ()
	{
		$sql = "SELECT
					CT.name, 
					CT.email, 
					CT.phone, 
					CT.fax, 
					CT.street, 
					CT.streetno, 
					CT.placeno, 
					CT.placename, 
					CT.postcode,
					CT.businesshours
				FROM contact C
				LEFT JOIN contacttranslation CT ON CT.contactid = C.idcontact AND CT.languageid = :languageid
				LEFT JOIN contactview CV ON CV.contactid = C.idcontact
				WHERE C.publish=1 AND CV.viewid = :viewid
				ORDER BY C.idcontact";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'name' => $rs->getString('name'),
					'phone' => $rs->getString('phone'),
					'fax' => $rs->getString('fax'),
					'email' => $rs->getString('email'),
					'street' => $rs->getString('street'),
					'streetno' => $rs->getString('streetno'),
					'placeno' => $rs->getString('placeno'),
					'placename' => $rs->getString('placename'),
					'postcode' => $rs->getString('postcode'),
					'businesshours' => $rs->getString('businesshours')
				);
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage('ERR_QUERY_WISHLIST'));
		}
		return $Data;
	}

}