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
 * $Id: poll.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class pollModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('poll', Array(
			'idpoll' => Array(
				'source' => 'P.idpoll'
			),
			'questions' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'votes' => Array(
				'source' => 'P.idpoll',
				'processFunction' => Array(
					$this,
					'getPollAnswers'
				)
			),
			'publish' => Array(
				'source' => 'publish'
			)
		));
		$datagrid->setFrom('
				poll P
				LEFT JOIN answervolunteered AV ON P.idpoll = AV.pollid
				LEFT JOIN pollanswers PA ON AV.pollanswersid = PA.idpollanswers
				LEFT JOIN polltranslation PT ON PT.pollid = idpoll
				LEFT JOIN pollview PV ON PV.pollid = idpoll
			');
		$datagrid->setGroupBy('
				P.idpoll
			');
		$datagrid->setAdditionalWhere('
				PT.languageid=:languageid
			');
	}

	public function getQuestionsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('questions', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getPollForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeletePoll ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deletePoll'
		), $this->getName());
	}

	public function deletePoll ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idpoll' => $id
			), $this->getName(), 'deletePoll');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnablePoll ($datagridId, $id)
	{
		try{
			$this->enablePoll($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisablePoll ($datagridId, $id)
	{
		try{
			$this->disablePoll($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disablePoll ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception($this->registry->core->getMessage('ERR_CAN_NOT_DISABLE_YOURSELF'));
		}
		$sql = 'UPDATE poll SET publish = 0 WHERE idpoll = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enablePoll ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception($this->registry->core->getMessage('ERR_CAN_NOT_ENABLE_YOURSELF'));
		}
		$sql = 'UPDATE poll SET publish = 1 WHERE idpoll = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getPollView ($id)
	{
		$sql = "SELECT idpoll as id, publish FROM poll WHERE idpoll=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'publish' => $rs->getInt('publish'),
				'answers' => $this->getAnswersPoll($id),
				'view' => $this->getPollViews($id),
				'language' => $this->getPollTranslation($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_POLL_NO_EXIST'));
		}
		return $Data;
	}

	public function getPollTranslation ($id)
	{
		$sql = "SELECT name as questions, languageid
					FROM polltranslation
					WHERE pollid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'questions' => $rs->getString('questions')
			);
		}
		return $Data;
	}

	public function getPollViews ($id)
	{
		$sql = "SELECT viewid
					FROM pollview
					WHERE pollid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getAnswersPoll ($id)
	{
		$sql = "SELECT name, languageid
					FROM pollanswers
					WHERE pollid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$lnaguageId = $rs->getInt('languageid');
			$Data['name_' . $lnaguageId][] = $rs->getString('name');
		}
		return $Data;
	}

	public function editPoll ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updatePoll($Data, $id);
			$this->editPollTranslation($Data, $id);
			$this->editPollAnswers($Data, $id);
			$this->editPollView($Data['view'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_POLL_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function editPollTranslation ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM polltranslation WHERE pollid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['questions'] as $key => $value){
			$sql = 'INSERT INTO polltranslation (name, languageid, pollid, addid)
						VALUES (:name, :languageid, :pollid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $value);
			$stmt->setInt('languageid', $key);
			$stmt->setInt('pollid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_POLL_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
		return $Data;
	}

	public function editPollView ($array, $id)
	{
		$sql = 'DELETE FROM pollview WHERE pollid = :id';
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
				$sql = 'INSERT INTO pollview (viewid, pollid, addid)
							VALUES (:viewid, :pollid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('pollid', $id);
				$stmt->setInt('viewid', $value);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_POLL_VIEW_ADD'), 4, $e->getMessage());
				}
			}
		}
	}

	public function editPollAnswers ($Data, $id)
	{
		$sql = 'DELETE FROM answervolunteered WHERE pollid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$sqlDelete = 'DELETE FROM pollanswers WHERE pollid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data as $key => $value){
			if (is_array($value)){
				$check = substr($key, 0, 5);
				if ($check == 'name_'){
					$languageid = substr($key, 5, 5);
					foreach ($Data[$key] as $newkey => $trans){
						$sql = 'INSERT INTO pollanswers (name, pollid, languageid, addid)
									VALUES (:name, :pollid, :languageid, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('name', $trans);
						$stmt->setInt('pollid', $id);
						$stmt->setInt('languageid', $languageid);
						$stmt->setInt('addid', $this->registry->session->getActiveUserid());
						try{
							$stmt->executeQuery();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_POLL_ANSWERS_ADD'), 1225, $e->getMessage());
						}
					}
				}
			}
		}
		return $Data;
	}

	public function updatePoll ($Data, $id)
	{
		$sql = 'UPDATE poll SET publish=:publish, editid=:editid 
					WHERE idpoll =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('publish', $Data['publish']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_POLL_EDIT'), 13, $e->getMessage());
		}
		return true;
	}

	public function addNewPoll ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newPollId = $this->addPoll($Data);
			$this->addPollTranslation($Data, $newPollId);
			$this->addPollValue($Data, $newPollId);
			if (! empty($Data['view']) && is_array($Data['view'])){
				$this->addPollView($Data['view'], $newPollId);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_POLL_ADD'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	protected function addPollView ($array, $id)
	{
		foreach ($array as $value){
			$sql = 'INSERT INTO pollview (viewid, pollid, addid)
						VALUES (:viewid, :pollid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('pollid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_POLL_VIEW_ADD'), 125, $e->getMessage());
			}
		}
	}

	protected function addPollValue ($Data, $newPollId)
	{
		foreach ($Data as $key => $value){
			if (is_array($value)){
				$check = substr($key, 0, 5);
				if ($check == 'name_'){
					$languageid = substr($key, 5, 5);
					foreach ($Data[$key] as $newkey => $trans){
						$sql = 'INSERT INTO pollanswers (name, pollid, languageid, addid)
									VALUES (:name, :pollid, :languageid, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('name', $trans);
						$stmt->setInt('pollid', $newPollId);
						$stmt->setInt('languageid', $languageid);
						$stmt->setInt('addid', $this->registry->session->getActiveUserid());
						try{
							$stmt->executeQuery();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_POLL_ANSWERS_ADD'), 1225, $e->getMessage());
						}
					}
				}
			}
		}
		return $Data;
	}

	public function addPoll ($Data)
	{
		$sql = 'INSERT INTO poll (publish, addid)
					VALUES (:publish, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('publish', $Data['publish']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_POLL_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addPollTranslation ($Data, $pollid)
	{
		foreach ($Data['questions'] as $key => $value){
			$sql = 'INSERT INTO polltranslation (name, languageid, pollid, addid)
						VALUES (:name, :languageid, :pollid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $value);
			$stmt->setInt('languageid', $key);
			$stmt->setInt('pollid', $pollid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_POLL_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getPollAnswers ($id)
	{
		$sql = "SELECT PA.name, COUNT(DISTINCT AV.pollanswersid) AS votes, PA.idpollanswers as id
					FROM pollanswers PA
					LEFT JOIN answervolunteered AV ON AV.pollid = PA.pollid
					WHERE PA.pollid = :id AND PA.languageid = :languageid
					GROUP BY AV.pollid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			
			$Data[] = $rs->getString('name') . " ({$rs->getInt('votes')})";
		}
		return implode('<br />', $Data);
	}
}