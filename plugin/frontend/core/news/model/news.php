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
 * $Id: news.php 655 2012-04-24 08:51:44Z gekosale $
 */

class newsModel extends Model
{

	public function getNews ()
	{
		$sql = "SELECT 
					N.idnews, 
					NT.topic, 
					NT.summary,
					NT.content,
					N.adddate,
					N.featured,
					NT.seo
				FROM news N
				LEFT JOIN newsview NV ON NV.newsid = idnews
				LEFT JOIN newstranslation NT ON N.idnews = NT.newsid AND NT.languageid = :languageid
				WHERE publish = 1 AND NV.viewid = :viewid ORDER BY N.`adddate` desc";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'topic' => $rs->getString('topic'),
				'adddate' => $rs->getString('adddate'),
				'summary' => $rs->getString('summary'),
				'content' => $rs->getString('content'),
				'seo' => $rs->getString('seo'),
				'idnews' => $rs->getInt('idnews'),
				'featured' => $rs->getInt('featured'),
				'mainphoto' => $this->getPhotosByNewsId($rs->getInt('idnews'))
			);
		}
		return $Data;
	}

	public function getNewsById ($id)
	{
		$sql = "SELECT 
					N.idnews, 
					NT.topic, 
					NT.summary,
					NT.content,
					NT.seo,
					NT.keyword_title,
					NT.keyword,
					NT.keyword_description,
					N.adddate,
					N.featured
				FROM news N
				LEFT JOIN newsview NV ON NV.newsid = idnews
				LEFT JOIN newstranslation NT ON N.idnews = NT.newsid AND NT.languageid = :languageid
				WHERE idnews=:id AND publish = 1 AND NV.viewid = :viewid ORDER BY N.`adddate` desc";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'featured' => $rs->getString('featured'),
				'topic' => $rs->getString('topic'),
				'adddate' => $rs->getString('adddate'),
				'summary' => $rs->getString('summary'),
				'content' => $rs->getString('content'),
				'seo' => $rs->getString('seo'),
				'keyword_title' => ($rs->getString('keyword_title') == NULL || $rs->getString('keyword_title') == '') ? $rs->getString('topic') : $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description'),
				'mainphoto' => $this->getPhotosByNewsId($id),
				'otherphoto' => $this->getOtherPhotosByNewsId($id)
			);
		}
		return $Data;
	}

	public function getPhotosByNewsId ($id)
	{
		$sql = "SELECT photoid
				FROM newsphoto
				WHERE newsid= :id AND mainphoto= 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data['small'] = App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($rs->getInt('photoid')));
				$Data['normal'] = App::getModel('gallery')->getImagePath(App::getModel('gallery')->getNormalImageById($rs->getInt('photoid')));
				$Data['orginal'] = App::getModel('gallery')->getImagePath(App::getModel('gallery')->getOrginalImageById($rs->getInt('photoid')));
			}
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
		return $Data;
	}

	public function getOtherPhotosByNewsId ($id)
	{
		$sql = "SELECT photoid
				FROM newsphoto
				WHERE newsid= :id 
				AND mainphoto = 0";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'small' => App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($rs->getInt('photoid'))),
					'normal' => App::getModel('gallery')->getImagePath(App::getModel('gallery')->getNormalImageById($rs->getInt('photoid'))),
					'orginal' => App::getModel('gallery')->getImagePath(App::getModel('gallery')->getOrginalImageById($rs->getInt('photoid')))
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
		return $Data;
	}

	public function getMetadataForNews ()
	{
		if ($this->registry->core->getParam() == NULL){
			$Data = App::getModel('seo')->getMetadataForPage();
		}
		else{
			$Data = $this->getNewsById((int) $this->registry->core->getParam());
		}
		return $Data;
	}
}