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
 * $Id: termsandcondiotionsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class TermsAndCondiotionsBoxModel extends Model
{

	public function getTermsAndCondiotions ()
	{
		$sql = "SELECT conditions, privacy 
					FROM `view` V
					LEFT JOIN viewtranslation VT ON VT.viewid = V.idview AND VT.languageid = :languageid
					WHERE V.idview = :idview";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('idview', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'conditions' => $rs->getString('conditions'),
				'privacy' => $rs->getString('privacy')
			);
		}
		return $Data;
	}

}
?>