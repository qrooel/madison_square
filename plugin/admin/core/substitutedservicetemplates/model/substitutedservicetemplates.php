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
 * $Id: substitutedservicetemplates.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SubstitutedservicetemplatesModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('transmail', Array(
			'idtransmail' => Array(
				'source' => 'TM.idtransmail'
			),
			'name' => Array(
				'source' => 'TM.name'
			),
			'action' => Array(
				'source' => 'TMA.name'
			),
			'adddate' => Array(
				'source' => 'TM.adddate'
			)
		));
		
		$datagrid->setFrom('
				transmail TM
				LEFT JOIN transmailaction TMA ON TM.transmailactionid = TMA.idtransmailaction
			');
		/* isnotification = 1 
			 * bierze pod uwagę wyłącznie szablony powiadomień o aktywności (bez szablonów transakcyjnych) 
			*/
		$datagrid->setAdditionalWhere('
				 TMA.isnotification=1
				 AND IF(:viewid IS NULL, TM.viewid IS NULL, TM.viewid = :viewid)
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getNotificationTemplForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	/**
	 * Usuwa wybrane powiadomienie z bazy oraz plik szablonu z dysku
	 * @param integer id
	 * @param integer datagrid
	 * @return coreException, jeśli nie dało się usunąć szablonu z dysku
	 */
	public function doAJAXDeleteNotificationTempl ($id, $datagrid)
	{
		$fileTempDel = $this->getNotificationTemplToDelete($datagrid);
		if ($fileTempDel == true){
			return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
				$this,
				'deleteNotificationTempl'
			), $this->getName());
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_DELETE_FILE_TEMPLATE'));
		}
	}

	/**
	 * Usunięcie wybranego powiadomienia.
	 * 
	 * @param int $id- identyfikator powiadomienia
	 * @access public
	 */
	public function deleteNotificationTempl ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idtransmail' => $id
			), $this->getName(), 'deleteNotificationTempl');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Metoda pobiera nazwę pliku szablonu dla usuwanego powiadomienia,
	 * a następnie usuwa go z dysku.
	 * 
	 * @param int $id- identyfikator powiadomienia
	 * @return bool- informacja, czy plik został usunięty
	 * @access public
	 */
	public function getNotificationTemplToDelete ($id)
	{
		$NotificationTempl = $this->getNotificationTempl($id);
		$delete = false;
		if (! empty($NotificationTempl)){
			$delete = $this->deleteFileTemplate($NotificationTempl);
		}
		return $delete;
	}

	/**
	 * Usuwa z dysku plik szablonu powiadomienia. 
	 * 
	 * @param string $NotificationTempl- nazwa pliku szablonu, który ma zostać usunięty
	 * @return bool true- jeśli plik został pomyślnie usunięty, lub jeśli plik nie istniał
	 * @return bool false- jeśli nie udało się usunąc pliku z dysku.
	 * @access public
	 */
	public function deleteFileTemplate ($NotificationTempl)
	{
		$file = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $NotificationTempl . '.tpl';
		if (file_exists($file)){
			$delete = @unlink($file);
			if ($delete = true){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}

	/**
	 * Pobiera nazwę pliku szablonu powiadomienia.
	 * 
	 * @param int $id- identyfikator powiadomienia
	 * @return string $filename- nazwa pliku szablonu danego powiadomienia
	 * @access public
	 */
	public function getNotificationTempl ($id)
	{
		$sql = "SELECT TM.filename
					FROM transmail TM 
					WHERE TM.idtransmail= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$filename = '';
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$filename = $rs->getString('filename');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $filename;
	}

	/**
	 * Gets all tags for substituted service action.
	 * 
	 * @return array of all tags or empty array 
	 * @access public
	 */
	public function GetAllTagsForThisNotificationAction ()
	{
		$tags = Array();
		$sql = "SELECT TMT.idtransmailtags, TMT.tag, TMT.name as tname, 
						TMA.name
					FROM transmailtags TMT
						LEFT JOIN transmailactiontag TMAT ON TMT.idtransmailtags = TMAT.transmailtagsid
						LEFT JOIN transmailaction TMA ON TMAT.transmailactionid = TMA.idtransmailaction
					WHERE TMA.isnotification = 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$tags[$rs->getString('tag')] = $this->registry->core->getMessage($rs->getString('tname'));
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_TEMPLATE_ACTION'));
		}
		return $tags;
	}

	/**
	 * Adding new template of notification's mail.
	 * 
	 * @param array submitted data from form
	 * @return bool true on success
	 * @access public  
	 */
	public function addNewNotificationTemplTemplate ($submittedData)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newId = $this->InsertNewNotificationTempl($submittedData);
			if ($newId > 0){
				$header = '';
				$footer = '';
				if (! empty($submittedData['filename']) && ! empty($submittedData['htmlform'])){
					if ($submittedData['transmailheaderid'] > 0){
						$header = App::getModel('transmailtemplates')->getHeaderContentForTemplate($submittedData['transmailheaderid']);
					}
					if ($submittedData['transmailfooterid'] > 0){
						$footer = App::getModel('transmailtemplates')->getFooterContentForTemplate($submittedData['transmailfooterid']);
					}
					$namespace = App::getRegistry()->loader->getCurrentNamespace();
					//$filePath = ROOTPATH.'design'.DS.'_tpl'.DS.'mailerTemplates'.DS.$templFile['filetpl'];
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$filePath = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $submittedData['filename'] . $viewid . '.tpl';
					}
					else{
						$filePath = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $submittedData['filename'] . '.tpl';
					}
					$file = @fopen($filePath, "w+");
					$content = str_replace("\\", "", $submittedData['htmlform']);
					$write = fwrite($file, $header . '' . $content . '' . $footer);
					fclose($file);
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NOTIFICATION_TEMPLATE_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	/**
	 * Insert new template of substituted service.
	 * 
	 * @param array submitted data from form
	 * @return integer new idtransmail on success or exception otherwise
	 * @access public  
	 */
	public function InsertNewNotificationTempl ($submittedData)
	{
		$sql = 'INSERT INTO transmail 
					SET
						name= :name,
						title= :title,
						transmailactionid= (SELECT idtransmailaction FROM transmailaction WHERE isnotification=1),
						contenthtml= :contenthtml,
						addid= :addid,
						viewid= :viewid,
						transmailheaderid= :transmailheaderid,
						transmailfooterid= :transmailfooterid,
						active= 0,
						filename= :filename';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('title', $submittedData['title']);
		$stmt->setString('contenthtml', $submittedData['htmlform']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->setString('filename', $submittedData['filename'] . $viewid);
		}
		else{
			$stmt->setNull('viewid');
			$stmt->setString('filename', $submittedData['filename']);
		}
		if ($submittedData['transmailheaderid'] > 0){
			$stmt->setInt('transmailheaderid', $submittedData['transmailheaderid']);
		}
		else{
			$stmt->setNull('transmailheaderid');
		}
		if ($submittedData['transmailfooterid'] > 0){
			$stmt->setInt('transmailfooterid', $submittedData['transmailfooterid']);
		}
		else{
			$stmt->setNull('transmailfooterid');
		}
		try{
			$stmt->executeUpdate();
			return $stmt->getConnection()->getIdGenerator()->getId();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NOTIFICATION_TEMPLATE_INSERT'), 112, $e->getMessage());
		}
	}

	/**
	 * Gets all informations about chosen notification.
	 * 
	 * @param int idTransMail
	 * @return array with information or empty array if idTransMail not exists
	 * @access public
	 */
	public function getSubstitutedServiceTemplToEdit ($idTransMail)
	{
		$Data = Array();
		$sql = "SELECT TM.idtransmail, TM.name, TM.transmailactionid, TM.title, TM.filename,
						TM.contenthtml, TM.transmailheaderid, TM.transmailfooterid
					FROM transmail TM
					WHERE TM.idtransmail= :idtransmail";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmail', $idTransMail);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'id' => $rs->getInt('idtransmail'),
					'name' => $rs->getString('name'),
					'transmailactionid' => $rs->getInt('transmailactionid'),
					'contenthtml' => $rs->getString('contenthtml'),
					'transmailheaderid' => $rs->getInt('transmailheaderid'),
					'transmailfooterid' => $rs->getInt('transmailfooterid'),
					'title' => $rs->getString('title'),
					'filename' => $rs->getString('filename')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NOTIFICATION_DOES_NOT_EXIST'));
		}
		return $Data;
	}

	/**
	 * Edycja szablonu maila powiadomienia o aktywności.
	 * Jeśli szablon będzie mieć ustawioną flagę domyślny na 1, nastąpi edycja pliku tpl przypisanego dla wybranej akcji.
	 * W przypadku maili powiadomień o aktywności, każda edycja powoduje nadpisanie utworzonego podczas
	 * dodawania pliku tpl.
	 * 
	 * @param array dane przesłane z formularza
	 * @param integer identyfikator edytowwanego transMail
	 * @access public
	 */
	public function editSubstitutedServiceTempl ($submittedData, $idtransmail)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$update = $this->updateSubstitutedServiceTempl($submittedData, $idtransmail);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_EDIT'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	/**
	 * Updates substituted service.
	 * 
	 * @param array submitted data from form
	 * @param integer id transmail
	 * @param active 0 by default
	 * @return bool true on success or throwException otherwise
	 */
	public function updateSubstitutedServiceTempl ($submittedData, $idtransmail)
	{
		$sql = 'UPDATE transmail 
					SET 
						name= :name, 
						transmailactionid= (SELECT idtransmailaction FROM transmailaction WHERE isnotification=1),
						contenthtml= :contenthtml, 
						editid= :editid, 
						editdate= NOW(),
						viewid= :viewid, 
						active= 0, 
						transmailheaderid= :transmailheaderid, 
						transmailfooterid= :transmailfooterid
					WHERE idtransmail= :idtransmail';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmail', $idtransmail);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('contenthtml', $submittedData['contenthtml']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		if ($submittedData['transmailheaderid'] > 0){
			$stmt->setInt('transmailheaderid', $submittedData['transmailheaderid']);
		}
		else{
			$stmt->setNull('transmailheaderid');
		}
		if ($submittedData['transmailfooterid'] > 0){
			$stmt->setInt('transmailfooterid', $submittedData['transmailfooterid']);
		}
		else{
			$stmt->setNull('transmailfooterid');
		}
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
		}
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_UPDATE'), 112, $e->getMessage());
		}
	}
}