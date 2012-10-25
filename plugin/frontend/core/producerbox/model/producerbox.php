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
 * $Revision: 222 $
 * $Author: gekosale $
 * $Date: 2011-06-25 15:20:08 +0200 (So, 25 cze 2011) $
 * $Id: categoriesbox.php 222 2011-06-25 13:20:08Z gekosale $
 */

class ProducerBoxModel extends Model
{

	public function getProducerAll ($available)
	{
		$available[] = 0;
		
		$sql = 'SELECT 
					P.idproducer AS id,
					PT.name,
					PT.seo,
					P.photoid
				FROM producer P
				LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :language
				LEFT JOIN producerview PV ON P.idproducer = PV.producerid
				WHERE PV.viewid = :viewid AND P.idproducer IN (:available) AND PT.seo != \'\'
				GROUP BY P.idproducer
				ORDER BY PT.name ASC';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setINInt('available', $available);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo'),
				'photo' => App::getModel('categorylist')->getImagePath($rs->getInt('photoid')),
			);
		}
		return $Data;
	}
}