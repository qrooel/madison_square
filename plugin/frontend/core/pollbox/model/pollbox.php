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
 * $Id: pollbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class PollBoxModel extends Model
{

	public function checkAnswers ($idpoll)
	{
		$sql = "SELECT idpollanswers, name
					FROM pollanswers
					WHERE pollid = :idpoll AND languageid = :languageid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('idpoll', $idpoll);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idpollanswers' => $rs->getInt('idpollanswers'),
				'name' => $rs->getString('name'),
				'qty' => $this->checkAnswersQty($rs->getInt('idpollanswers'))
			);
		}
		
		return $Data;
	}

	public function checkAnswersQty ($id)
	{
		$sql = "SELECT count(pollanswersid) as qty, clientid
					FROM answervolunteered
					WHERE pollanswersid = :id
					GROUP BY pollid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		$Data = Array(
			'qty' => 0,
			'clientid' => null
		);
		if ($rs->first()){
			$Data = Array(
				'qty' => $rs->getInt('qty'),
				'clientid' => $rs->getInt('clientid')
			);
		}
		return $Data;
	}

	public function getPoll ()
	{
		$sql = "SELECT idpoll, PT.name as questions, publish
					FROM poll
					LEFT JOIN polltranslation PT ON PT.pollid = idpoll AND languageid=:languageid
					LEFT JOIN pollview PV ON PV.pollid = idpoll
 					WHERE languageid = :languageid AND publish = 1 AND viewid = :viewid
 					ORDER BY RAND()";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->next()){
			$Data = Array(
				'questions' => $rs->getString('questions'),
				'idpoll' => $rs->getInt('idpoll'),
				'answers' => $this->getPollAnswers($rs->getInt('idpoll'))
			);
		}
		return $Data;
	}

	public function getPollAnswers ($id)
	{
		$sql = "SELECT name, votes, idpollanswers as id
					FROM pollanswers
					WHERE pollid = :id AND languageid = :languageid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'votes' => $rs->getInt('id')
			);
		}
		return $Data;
	}

	public function setAnswersMethodChecked ($vote, $pollid)
	{
		$sql = 'INSERT INTO answervolunteered (viewid, pollanswersid, clientid, pollid)
					VALUES(:viewid, :vote, :clientid, :pollid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('vote', $vote);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('pollid', $pollid);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function setAJAXAnswersMethodChecked ($votes, $idpoll)
	{
		$objResponsePollMet = new xajaxResponse();
		try{
			$this->setAnswersMethodChecked($votes, $idpoll);
		}
		catch (Exception $e){
			$objResponsePollMet->alert($this->registry->core->getMessage('ERR_WHILE_VOTING'));
			return $objResponsePollMet;
		}
		$answers = $this->checkAnswers($idpoll);
		$results = '<dl>';
		$maxQty = 0;
		foreach ($answers as $answer){
			$maxQty = max($maxQty, $answer['qty']['qty']);
		}
		foreach ($answers as $answer){
			$results .= "<dt>{$answer['name']}</dt><dd><span class=\"votes\">{$answer['qty']['qty']}</span><span class=\"indicator\" style=\"width: " . ceil(($answer['qty']['qty'] / $maxQty) * 100) . "%\"></span></dd>";
		}
		$results .= '</dl>';
		$objResponsePollMet->assign('poll-' . $idpoll, 'innerHTML', $results);
		return $objResponsePollMet;
	}

}