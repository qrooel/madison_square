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

class newsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('news', Array(
			'idnews' => Array(
				'source' => 'N.idnews'
			),
			'topic' => Array(
				'source' => 'NT.topic',
				'prepareForAutosuggest' => true
			),
			'publish' => Array(
				'source' => 'N.publish'
			),
			'adddate' => Array(
				'source' => 'N.adddate'
			)
		));
		
		$datagrid->setFrom('
			news N
			LEFT JOIN newsview NV ON NV.newsid = N.idnews
			LEFT JOIN newstranslation NT ON N.idnews = NT.newsid AND NT.languageid = :languageid
			LEFT JOIN language L ON L.idlanguage=NT.languageid
		');
		
		$datagrid->setGroupBy('
			NT.newsid
		');
		
		if (Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
				NV.viewid IN (:viewids)
			');
		}
	}

	public function doAJAXEnableNews ($datagridId, $id)
	{
		try{
			$this->enableNews($id);
			$this->flushCache();
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableNews ($datagridId, $id)
	{
		try{
			$this->disableNews($id);
			$this->flushCache();
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableNews ($id)
	{
		$sql = 'UPDATE news SET publish = 0	WHERE idnews = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableNews ($id)
	{
		$sql = 'UPDATE news SET publish = 1	WHERE idnews = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getTopicForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('topic', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getNewsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteNews ($id, $datagrid)
	{
		$this->deleteNews($id);
		$this->flushCache();
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteNews ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idnews' => $id
			), $this->getName(), 'deleteNews');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getNewsView ($id)
	{
		$sql = "SELECT N.idnews, N.publish,N.featured,NP.photoid AS mainphotoid
				FROM news N
				LEFT JOIN newsphoto NP ON N.idnews = NP.newsid AND NP.mainphoto = 1
				WHERE N.idnews =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data = Array(
				'mainphotoid' => $rs->getInt('mainphotoid'),
				'publish' => $rs->getString('publish'),
				'featured' => $rs->getString('featured'),
				'language' => $this->getNewsTranslation($id),
				'id' => $rs->getInt('idnews'),
				'photo' => $this->newsPhotoIds($rs->getInt('idnews')),
				'view' => $this->getStoreViews($id)
			);
		}
		return $Data;
	}

	public function newsPhoto ($id)
	{
		$sql = 'SELECT photoid AS id FROM newsphoto WHERE newsid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function newsPhotoIds ($id)
	{
		$Data = $this->newsPhoto($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function newsPhotoUpdate ($Data, $id)
	{
		if (isset($Data['photo']['unmodified']) && $Data['photo']['unmodified']){
			return;
		}
		$sql = 'DELETE FROM newsphoto WHERE newsid =:newsid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('newsid', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (isset($Data['photo']['main'])){
			$mainphoto = $Data['photo']['main'];
			foreach ($Data['photo'] as $key => $photo){
				if (! is_array($photo) && is_int($key) && ($photo > 0)){
					$sql = 'INSERT INTO newsphoto (newsid, mainphoto, photoid, addid)
								VALUES (:newsid, :mainphoto, :photoid,  :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('newsid', $id);
					$stmt->setInt('mainphoto', ($photo == $mainphoto) ? 1 : 0);
					$stmt->setInt('photoid', $photo);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_NEWS_PHOTO_UPDATE'), 112, $e->getMessage());
					}
				}
			}
		}
	}

	public function getStoreViews ($id)
	{
		$sql = "SELECT viewid
				FROM newsview
				WHERE newsid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getNewsTranslation ($id)
	{
		$sql = "SELECT topic, summary,content, seo, keyword_title, keyword ,keyword_description, languageid
				FROM newstranslation
				WHERE newsid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'topic' => $rs->getString('topic'),
				'seo' => $rs->getString('seo'),
				'summary' => $rs->getString('summary'),
				'content' => $rs->getString('content'),
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}

	public function addNewNews ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newNewsId = $this->addNews($Data['publish'], $Data['featured']);
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addNewsView($Data['view'], $newNewsId);
			}
			$this->addNewsTranslation($Data, $newNewsId);
			$this->addPhotoNews($Data['photo'], $newNewsId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	public function addPhotoNews ($array, $newsId)
	{
		if ($array['unmodified'] == 0 && isset($array['main'])){
			$mainphoto = $array['main'];
			foreach ($array as $key => $photo){
				if (! is_array($photo) && is_int($key)){
					$sql = 'INSERT INTO newsphoto (newsid, mainphoto, photoid, addid)
								VALUES (:newsid, :mainphoto, :photoid,  :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('newsid', $newsId);
					$stmt->setInt('mainphoto', ($photo == $mainphoto) ? 1 : 0);
					$stmt->setInt('photoid', $photo);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_news_PHOTO_ADD'), 112, $e->getMessage());
					}
				}
			}
		}
	}

	public function addNews ($publish, $featured)
	{
		$sql = 'INSERT INTO news SET
				publish = :publish, 
				featured = :featured, 
				addid = :addid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('publish', $publish);
		$stmt->setInt('featured', $featured);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewsTranslation ($Data, $id)
	{
		foreach ($Data['topic'] as $key => $val){
			$sql = 'INSERT INTO newstranslation SET
					newsid = :newsid,
					topic = :topic, 
					summary = :summary, 
					content = :content, 
					seo = :seo,
					keyword_title = :keyword_title,
					keyword = :keyword,
					keyword_description = :keyword_description,
					languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('newsid', $id);
			$stmt->setString('topic', $Data['topic'][$key]);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('summary', $Data['summary'][$key]);
			$stmt->setString('content', $Data['content'][$key]);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function addNewsView ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO newsview SET
					newsid = :newsid,
					viewid = :viewid, 
					addid = :addid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('newsid', $id);
			$stmt->setInt('viewid', $value);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_VIEW_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function editNews ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateNews($Data['publish'],$Data['featured'], $id);
			$this->updateNewsTranslation($Data, $id);
			$this->updateNewsView($Data['view'], $id);
			$this->newsPhotoUpdate($Data, $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	public function updateNews ($publish,$featured, $id)
	{
		$sql = 'UPDATE news SET 
				publish=:publish,
				featured=:featured,
				editid=:editid
				WHERE idnews =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('publish', $publish);
		$stmt->setInt('featured', $featured);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWS_EDIT'), 13, $e->getMessage());
		}
	}

	public function updateNewsView ($Data, $id)
	{
		$sql = 'DELETE FROM newsview WHERE newsid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO newsview SET
						newsid = :newsid,
						viewid = :viewid, 
						addid = :addid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->setInt('newsid', $id);
				$stmt->setInt('viewid', $value);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_NEWS_VIEW_ADD'), 4, $e->getMessage());
				}
			}
		}
	}

	public function updateNewsTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM newstranslation WHERE newsid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['topic'] as $key => $val){
			$sql = 'INSERT INTO newstranslation SET
					newsid = :newsid,
					topic = :topic, 
					summary = :summary, 
					content = :content, 
					seo = :seo,
					keyword_title = :keyword_title,
					keyword = :keyword,
					keyword_description = :keyword_description,
					languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('newsid', $id);
			$stmt->setString('topic', $Data['topic'][$key]);
			$stmt->setString('summary', $Data['summary'][$key]);
			$stmt->setString('content', $Data['content'][$key]);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_TRANSLATION_EDIT'), 4, $e->getMessage());
			}
		}
	}

	public function flushCache ()
	{
		Cache::destroyObject('news');
	}
}