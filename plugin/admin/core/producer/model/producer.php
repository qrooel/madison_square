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
 * $Revision: 658 $
 * $Author: gekosale $
 * $Date: 2012-04-25 16:14:48 +0200 (Śr, 25 kwi 2012) $
 * $Id: producer.php 658 2012-04-25 14:14:48Z gekosale $ 
 */

class ProducerModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('producer', Array(
			'idproducer' => Array(
				'source' => 'P.idproducer'
			),
			'name' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'adddate' => Array(
				'source' => 'P.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'P.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`producer` P
				LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :languageid
				LEFT JOIN producerview PV ON PV.producerid = P.idproducer
				LEFT JOIN `user` UA ON P.addid = UA.iduser
				LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
				LEFT JOIN `user` UE ON P.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
			');
		
		if (Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
					PV.viewid IN (:viewids)
				');
		}
		
		$datagrid->setGroupBy('
				P.idproducer
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getProducerForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteProducer ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteProducer'
		), $this->getName());
	}

	public function deleteProducer ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idproducer' => $id
			), $this->getName(), 'deleteProducer');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getProducerView ($id)
	{
		$sql = "SELECT idproducer AS id FROM producer WHERE idproducer = :id";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getProducerTranslationById($id),
				'photo' => $this->getPhotoProducerById($id),
				'deliverers' => $this->ProducerDelivererIds($id),
				'view' => $this->getProducerViews($id)
			);
			return $Data;
		}
	}

	public function getProducerTranslationById ($id)
	{
		$sql = "SELECT 
					name, 
					seo,
					description, 
					languageid,
					keyword_title,
					keyword,
					keyword_description
				FROM producertranslation
				WHERE producerid = :id ";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$langid = $rs->getInt('languageid');
			$Data[$langid] = Array(
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo'),
				'description' => $rs->getString('description'),
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}

	public function getProducerViews ($id)
	{
		$sql = "SELECT viewid FROM producerview WHERE producerid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getProducerTranslation ($id, $languageid)
	{
		$sql = "SELECT 
					name, 
					seo,
					description,
					keyword_title,
					keyword,
					keyword_description
				FROM producertranslation
				WHERE producerid = :id AND languageid = :languageid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $languageid);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$languageid] = Array(
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo'),
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}

	public function getProducerNameById ($id, $languageid)
	{
		try{
			$Data = $this->getProducerTranslation($id, $languageid);
			if (! empty($Data[$languageid]['name'])){
				return $Data[$languageid]['name'];
			}
			else{
				return Null;
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getPhotoProducerById ($id)
	{
		$sql = 'SELECT photoid
					FROM producer PR
					LEFT JOIN file F ON F.idfile = PR.photoid
					WHERE PR.idproducer=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = $rs->getInt('photoid');
		}
		return $Data;
	}

	public function getProducerDelivererView ($id)
	{
		$sql = "SELECT DT.name AS deliverername
					FROM producerdeliverer PD
					LEFT JOIN deliverer D ON D.iddeliverer = PD.delivererid
					LEFT JOIN deliverertranslation DT ON D.iddeliverer = DT.delivererid AND DT.languageid = :language
					WHERE PD.producerid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('language', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['deliverername'][] = $rs->getString('deliverername');
		}
		return $Data;
	}

	public function getProducerAll ()
	{
		$sql = 'SELECT P.idproducer AS id,PT.name
				FROM producer P
				LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :language
				LEFT JOIN producerview PV ON PV.producerid = P.idproducer
				WHERE PV.viewid IN (:viewids)';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getProducerToSelect ()
	{
		$Data = $this->getProducerAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function ProducerDeliverer ($id)
	{
		$sql = 'SELECT D.iddeliverer AS id, DT.name
					FROM producerdeliverer PD
					LEFT JOIN deliverer D ON PD.delivererid = D.iddeliverer
					LEFT JOIN deliverertranslation DT ON D.iddeliverer = DT.delivererid AND DT.languageid = :language
					WHERE PD.producerid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function ProducerDelivererIds ($id)
	{
		$Data = $this->ProducerDeliverer($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function getPhotos (&$producer)
	{
		if (! is_array($producer)){
			throw new Exception('Wrong array given');
		}
		foreach ($producer['photo'] as $photo){
			$producer['photo']['small'][] = App::getModel('gallery')->getSmallImageById($photo['photoid']);
		}
	}

	public function editProducer ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateProducer($Data, $id);
			$this->updateProducerDeliverer($Data['deliverer'], $id);
			$this->updateProducerView($Data['view'], $id);
			$this->updateProducerTranslation($Data, $id);
			$event = new sfEvent($this, 'admin.producer.model.save', Array(
				'id' => $id,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_EDIT'), 10, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	protected function updateProducerDeliverer ($array, $id)
	{
		$sql = 'DELETE FROM producerdeliverer WHERE producerid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($array)){
			foreach ($array as $value){
				$sql = 'INSERT INTO producerdeliverer (delivererid, producerid, addid) VALUES (:delivererid, :producerid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('delivererid', $value);
				$stmt->setInt('producerid', $id);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
	}
 
	public function updateProducer ($photoid, $id)
	{
		if (isset($photoid['photo']['unmodified']) && $photoid['photo']['unmodified']){
			return;
		}
		
		$sql = 'UPDATE producer SET editid = :editid, photoid = :photo WHERE idproducer = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		if (($photoid['photo'][0]) > 0){
			$stmt->setInt('photo', $photoid['photo'][0]);
		}
		else{
			$stmt->setNull('photo');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_EDIT'), 15, $e->getMessage());
		}
	}

	public function updateProducerView ($Data, $id)
	{
		$sql = 'DELETE FROM producerview WHERE producerid =:id';
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
				$sql = 'INSERT INTO producerview (producerid, viewid, addid)
							VALUES (:producerid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('producerid', $id);
				$stmt->setInt('viewid', $value);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_VIEW_EDIT'), 4, $e->getMessage());
				}
			}
		}
	}

	public function updateProducerTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM producertranslation WHERE producerid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO producertranslation (
						producerid,
						name, 
						seo, 
						description,
						keyword_title,
						keyword,
						keyword_description,
						languageid
					)
					VALUES 
					(
						:producerid,
						:name, 
						:seo,
						:description, 
						:keyword_title,
						:keyword,
						:keyword_description,
						:languageid
					)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('producerid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_TRANSLATION_EDIT'), 15, $e->getMessage());
			}
		}
	}

	public function addNewProducer ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newProducerId = $this->addProducer($Data);
			$this->addProducerTranslation($Data, $newProducerId);
			if (! empty($Data['view'])){
				$this->addProducerView($Data['view'], $newProducerId);
			}
			if (! empty($Data['deliverer'])){
				$this->addProducerDeliverer($Data['deliverer'], $newProducerId);
			}
			$event = new sfEvent($this, 'admin.producer.model.save', Array(
				'id' => $newProducerId,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_ADD'), 15, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addProducer ($Data)
	{
		$sql = 'INSERT INTO producer (addid, photoid) VALUES (:addid, :photoid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (($Data['photo'][0]) > 0){
			$stmt->setInt('photoid', $Data['photo'][0]);
		}
		else{
			$stmt->setNull('photoid');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_ADD'), 15, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addProducerView ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO producerview (producerid,viewid,addid)
						VALUES (:producerid, :viewid,:addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('producerid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_VIEW_ADD'), 15, $e->getMessage());
			}
		}
	}

	public function addProducerTranslation ($Data, $id)
	{
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO producertranslation (
						producerid,
						name, 
						seo, 
						description,
						keyword_title,
						keyword,
						keyword_description,
						languageid
					)
					VALUES 
					(
						:producerid,
						:name, 
						:seo,
						:description, 
						:keyword_title,
						:keyword,
						:keyword_description,
						:languageid
					)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('producerid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_TRANSLATION_EDIT'), 15, $e->getMessage());
			}
		}
	}

	protected function addProducerDeliverer ($Data, $newProducerId)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO producerdeliverer (delivererid, producerid, addid) VALUES (:delivererid, :producerid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('delivererid', $value);
			$stmt->setInt('producerid', $newProducerId);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCER_DELIVERER_ADD'), 15, $e->getMessage());
			}
		}
	}
}