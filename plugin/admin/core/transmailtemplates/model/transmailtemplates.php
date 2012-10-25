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
 * $Revision: 668 $
 * $Author: gekosale $
 * $Date: 2012-04-25 19:31:18 +0200 (Śr, 25 kwi 2012) $
 * $Id: transmailtemplates.php 668 2012-04-25 17:31:18Z gekosale $ 
 */

class TransmailtemplatesModel extends ModelWithDatagrid
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
			'active' => Array(
				'source' => 'TM.active'
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
		/* isnotification = 0 
		 * bierze pod uwagę wyłącznie szablony transakcyjne (bez powiadomień o aktywności) 
		*/
		$datagrid->setAdditionalWhere('
			 TMA.isnotification=0
			 AND IF(:viewid IS NULL, TM.viewid IS NULL, TM.viewid = :viewid)
		');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getTransmailForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	/**
	 * Usuwa wybrany szablon z bazy oraz plik szablonu z dysku. 
	 * 				WAŻNE!!!
	 * Plik szablonu usuwany jest tylko dla view (nazwa pliku dla view składa się 
	 * z domyślnej nazwy szablonu oraz przyrostka identyfikatora danego view).
	 * Domyślnie utworzone pliki szablonów transakcyjnych nie są usuwane z dysku!!!
	 * 
	 * @param integer id
	 * @param integer datagrid
	 * @return coreException, jeśli nie dało się usunąć szablonu z dysku
	 */
	public function doAJAXDeleteTransmail ($id, $datagrid)
	{
		$fileTempDel = $this->getTransMailTemplToDelete($datagrid);
		if ($fileTempDel == true){
			return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
				$this,
				'deleteTransmail'
			), $this->getName());
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_DELETE_FILE_TEMPLATE'));
		}
	}

	public function deleteTransmail ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idtransmail' => $id
			), $this->getName(), 'deleteTransmail');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function setAJAXDefaultTransMailTemplate ($datagridId, $id)
	{
		try{
			$this->registry->db->setAutoCommit(false);
			try{
				$actionId = $this->getTransMailAction($id);
				if (isset($actionId['transmailactionid']) && ! empty($actionId['transmailactionid'])){
					$this->setNoDefaultTransMail($actionId['transmailactionid'], $id);
				}
				$this->setDefaultTransMailTemplate($id);
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_SET_DEFAULT_TRANS_MAIL_TEMPL'), 112, $e->getMessage());
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_SET_DEFAULT_TRANS_MAIL')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	/**
	 * Pobranie nazwy pliku szablonu transakcyjnego oraz wywołanie metody
	 * służącej do usunięcie pliku z dysku. 
	 * 
	 * @param int $id- identyfikator szablonu transakcyjnego
	 * @return bool- wynik działania funkcji usuwającej plik z dysku
	 * @access public
	 */
	public function getTransMailTemplToDelete ($id)
	{
		$transMail = $this->getTransMailAction($id);
		$delete = false;
		if (! empty($transMail) && ! empty($transMail['filename'])){
			$delete = $this->deleteFileTemplate($transMail);
		}
		return $delete;
	}

	/** 
	 * Usunięcie pliku szablonu transakcyjnego.
	 * 
	 * @param array $transMail (tablica z nazwą pliku oraz identyfikatorem akcji transakcyjnej)
	 * @return bool ture (plik został usunięty, pliku nie ma już na dysku, plik dot. domyślnego szablonu)
	 * @return bool false (plik nie został usunięty)
	 * @access public
	 */
	public function deleteFileTemplate ($transMail)
	{
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$file = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $transMail['filename'] . $viewid . '.tpl';
			if (file_exists($file)){
				$delete = @unlink($file);
				if ($delete == true){
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
		else{
			return true;
		}
	}

	/**
	 * Pobiera identyfikator akcji szablonu oraz nazwę pliku szablonu
	 * dla wybranego szablonu transakcyjnego.
	 * 
	 * @param int $id- identyfikator szablonu transakcyjnego
	 * @return array $Data (identyfikator akcji oraz nazwa pliku szablonu)
	 * @access public
	 */
	public function getTransMailAction ($id)
	{
		$sql = "SELECT TM.transmailactionid, TMA.filetpl
					FROM transmail TM 
						LEFT JOIN transmailaction TMA ON TMA.idtransmailaction= TM.transmailactionid
					WHERE TM.idtransmail= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$transMail = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$transMail = Array(
					'transmailactionid' => $rs->getInt('transmailactionid'),
					'filename' => $rs->getString('filetpl')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $transMail;
	}

	/**
	 * Zmiana statusu flagi (domyślny=0) dla wybranego szablonu.
	 * 
	 * @param int $actionId- identyfikator akcji 
	 * @param int $id- identyfikator szablon transakcyjnego
	 * @return bool true, gdy zmiana statusu zakończyła się powodzeniem
	 * @access public
	 */
	public function setNoDefaultTransMail ($actionId, $id)
	{
		$sql = 'UPDATE transmail 
					SET `active`=0 
					WHERE idtransmail <> :id
						AND transmailactionid= :actionid
						AND IF(:viewid>0, viewid= :viewid, viewid IS NULL)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('actionid', $actionId);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/** 
	 * Zmiana statusu flagi (domyślny=1) wybranego szablonu transakcyjnego
	 * 
	 * @param int $id- identyfikator szablonu transakcyjnego
	 * @return bool true dla pomyślnej zmiany statusu
	 * @access public
	 */
	public function setDefaultTransMailTemplate ($id)
	{
		$sql = 'UPDATE transmail SET `active`= 1 
					WHERE idtransmail = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Get all actions which are use in defined translation mails.
	 * 
	 * @return array (id and name of action)
	 * @access public
	 */
	public function getTransMailActionAllToSelect ()
	{
		$sql = "SELECT TMA.idtransmailaction, TMA.name
					FROM transmailaction TMA
						LEFT JOIN transmail TM ON TMA.idtransmailaction = TM.transmailactionid
						WHERE TMA.isnotification=0";
		//		WHERE TM.transmailactionid IS NULL";
		$stmt = $this->registry->db->prepareStatement($sql);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$id = $rs->getInt('idtransmailaction');
				$Data[$id] = $rs->getString('name');
			}
			asort($Data);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_TEMPLATE_ACTION'));
		}
		return $Data;
	}

	/**
	 * Get all transaction mails headers.
	 * 
	 * @return array headers (idtransmailheader => name)
	 * @access public
	 */
	public function getTransMailHeaderAllToSelect ()
	{
		$sql = "SELECT TMH.idtransmailheader, TMH.name
					FROM transmailheader TMH";
		$stmt = $this->registry->db->prepareStatement($sql);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$id = $rs->getInt('idtransmailheader');
				$Data[$id] = $rs->getString('name');
			}
			asort($Data);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_HEADER_TEMPLATE'));
		}
		return $Data;
	}

	/**
	 * Get all transaction mails footers
	 * 
	 * @return array footers (idtransmailfooter => name)
	 * @access public
	 */
	public function getTransMailFooterAllToSelect ()
	{
		$sql = "SELECT TMF.idtransmailfooter, TMF.name
					FROM transmailfooter TMF";
		$stmt = $this->registry->db->prepareStatement($sql);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$id = $rs->getInt('idtransmailfooter');
				$Data[$id] = $rs->getString('name');
			}
			asort($Data);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_FOOTER_TEMPLATE'));
		}
		return $Data;
	}

	/**
	 * Adding new template of transaction mail.
	 * 
	 * @param array submitted data from form
	 * @return bool true on success
	 * @access public  
	 */
	public function addNewTransMailTemplate ($submittedData)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newId = $this->InsertNewTransMailTemplate($submittedData);
			if ($submittedData['active'] == 1 && $newId > 0 && $submittedData['filename'] == ''){
				$this->setNoDefaultTransMail($submittedData['action'], $newId);
				$templFile = $this->getTemplateFileForAction($submittedData['action']);
				$header = $this->getHeaderContentForTemplate($submittedData['transmailheaderid']);
				$footer = $this->getFooterContentForTemplate($submittedData['transmailfooterid']);
				if (! empty($templFile) && ! empty($submittedData['htmlform'])){
					//$namespace = $this->registry->namespace->getCurrentControllerNamespace();
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$filename = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $templFile['filetpl'] . $viewid . '.tpl';
					}
					else{
						$filename = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $templFile['filetpl'] . '.tpl';
					}
					$file = @fopen($filename, "w+");
					$content = str_replace("\\", "", $submittedData['htmlform']);
					$write = fwrite($file, $header . ' ' . $content . ' ' . $footer);
					fclose($file);
				}
			}
			//				if(isset($submittedData['filename']) && !empty($submittedData['filename']) && $newId > 0) {
		//					$header = $this->getHeaderContentForTemplate($submittedData['transmailheaderid']);
		//					$footer = $this->getFooterContentForTemplate($submittedData['transmailfooterid']);
		//					//$namespace = $this->registry->namespace->getCurrentControllerNamespace();
		//					$filename = ROOTPATH.'design'.DS.'_tpl'.DS.'mailerTemplates'.DS.$submittedData['filename'].'.tpl';
		//					$file = @fopen($filename, 'w+');
		//					$content = $submittedData['htmlform'];
		//					$content = str_replace("\\", "", $content);
		//					$write = fwrite($file, $header .' '. $content .' '. $footer);
		//					fclose($file);
		//				} 
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	/**
	 * Insert new template of transaction mail
	 * 
	 * @param array submitted data from form
	 * @return integer new idtransmail on success or exception otherwise
	 * @access public  
	 */
	public function InsertNewTransMailTemplate ($submittedData)
	{
		$sql = 'INSERT INTO transmail 
					SET
						name= :name,
						transmailactionid= :transmailactionid,
						contenthtml= :contenthtml,
						addid= :addid,
						viewid= :viewid,
						active= :active,
						transmailheaderid= :transmailheaderid,
						transmailfooterid= :transmailfooterid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setInt('transmailactionid', $submittedData['action']);
		//$stmt->setString('contenttxt', $submittedData['textform']);
		$stmt->setString('contenthtml', $submittedData['htmlform']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
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
		if ($submittedData['active'] == NULL){
			$stmt->setInt('active', 0);
		}
		else{
			$stmt->setInt('active', $submittedData['active']);
		}
		try{
			$stmt->executeUpdate();
			return $stmt->getConnection()->getIdGenerator()->getId();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_INSERT'), 112, $e->getMessage());
		}
	}

	/**
	 * Gets all informations about chosen transmail.
	 * 
	 * @param int idTransMail
	 * @return array with information or empty array if idTransMail not exists
	 * @access public
	 */
	public function getTransMailToEdit ($idTransMail)
	{
		$Data = Array();
		$sql = "SELECT TM.idtransmail, TM.name, TM.transmailactionid, TM.contenttxt,
						TM.contenthtml, TM.active, TM.transmailheaderid, TM.transmailfooterid
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
					'contenttxt' => $rs->getString('contenttxt'),
					'contenthtml' => $rs->getString('contenthtml'),
					'active' => $rs->getInt('active'),
					'transmailheaderid' => $rs->getInt('transmailheaderid'),
					'transmailfooterid' => $rs->getInt('transmailfooterid')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_TEMPLATE_ACTION'));
		}
		return $Data;
	}

	/**
	 * Gets all tags for chosen action.
	 * 
	 * @param array request including action's id
	 * @return array of all tags or empty array 
	 * @access public
	 */
	public function GetAllTagsForThisAction ($request)
	{
		$Data = Array();
		if (isset($request['id']) && $request['id'] > 0){
			$sql = "SELECT TMT.idtransmailtags, TMT.tag, TMT.name as tname, 
							TMA.name
						FROM transmailtags TMT
							LEFT JOIN transmailactiontag TMAT ON TMT.idtransmailtags = TMAT.transmailtagsid
							LEFT JOIN transmailaction TMA ON TMAT.transmailactionid = TMA.idtransmailaction
						WHERE TMA.idtransmailaction = :idtransmailaction";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('idtransmailaction', $request['id']);
			try{
				$rs = $stmt->executeQuery();
				while ($rs->next()){
					$name = $rs->getString('name');
					$tags[$rs->getInt('idtransmailtags')] = Array(
						$rs->getString('tag'),
						$this->registry->core->getMessage($rs->getString('tname'))
					);
				}
				if (! empty($name) && ! empty($tags)){
					$Data = Array(
						'title' => $this->registry->core->getMessage('TXT_TAGS_FOR_ACTION') . ': ' . $name,
						'data' => $tags
					);
				}
				else{
					$Data = Array(
						'title' => $this->registry->core->getMessage('TXT_TAGS_FOR_ACTION'),
						'data' => Array()
					);
				}
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NO_TEMPLATE_ACTION'));
			}
		}
		else{
			$Data = Array(
				'title' => $this->registry->core->getMessage('TXT_TAGS_FOR_ACTION'),
				'data' => Array()
			);
		}
		return $Data;
	}

	/**
	 * Edycja szablonu maila transakcyjnego.
	 * Jeśli szablon będzie mieć ustawioną flagę domyślny na 1, nastąpi edycja pliku tpl przypisanego dla wybranej akcji.
	 * W przypadku maili powiadomień o aktywności, każda edycja powoduje nadpisanie utworzonego podczas
	 * dodawania pliku tpl.
	 * 
	 * @param array dane przesłane z formularza
	 * @param integer identyfikator edytowwanego transMail
	 * @access public
	 */
	public function editTransMailTemplate ($submittedData, $idtransmail)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			if ($submittedData['active'] == 1){
				$this->setNoDefaultTransMail($submittedData['active'], $idtransmail);
				$active = 1;
			}
			else{
				$checkDefault = $this->getDefaultTramsMail($submittedData['action']);
				if ($checkDefault > 0 && $checkDefault != $idtransmail){
					$active = 0;
				}
				else{
					$active = 1;
				}
			}
			$update = $this->updateTransMailTemplate($submittedData, $idtransmail, $active);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_EDIT'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	/**
	 * Updates trans mail
	 * @param array submitted data from form
	 * @param integer id transmail
	 * @param active 0 by default
	 * @return bool true on success or throwException otherwise
	 */
	public function updateTransMailTemplate ($submittedData, $idtransmail, $active = 0)
	{
		$sql = 'UPDATE transmail 
					SET 
						name= :name, 
						transmailactionid= :transmailactionid, 
						contenttxt = :contenttxt, 
						contenthtml= :contenthtml, 
						editid= :editid, 
						editdate= NOW(),
						viewid= :viewid, 
						active= :active, 
						transmailheaderid= :transmailheaderid, 
						transmailfooterid= :transmailfooterid
					WHERE idtransmail= :idtransmail';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmail', $idtransmail);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setInt('transmailactionid', $submittedData['action']);
		$stmt->setString('contenttxt', $submittedData['contenttxt']);
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
		$stmt->setInt('active', $active);
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_UPDATE'), 112, $e->getMessage());
		}
	}

	/** 
	 * Gets name of file template and controller for choces action
	 * @param integer id of transMailAction
	 * @return array with name of template and controller on success or empty array otherwise
	 * @access public
	 */
	public function getTemplateFileForAction ($transmailactionid)
	{
		$sql = "SELECT filetpl, controller
					FROM transmailaction
					WHERE idtransmailaction= :transmailactionid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('transmailactionid', $transmailactionid);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'filetpl' => $rs->getString('filetpl'),
					'controller' => $rs->getString('controller')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Pobierz nazwę pliku tpl 
	 * @param integer identyfikator maila transakcyjnego
	 * @return string nazwa pliku, jeśli istnieje lub pusty string w przeciwnym wypadku
	 * @access public
	 */
	public function getFileNameForTransMail ($idtransmail)
	{
		$sql = "SELECT filename 
					FROM transmail
					WHERE idtransmail= :idtransmail";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmail', $idtransmail);
		$filename = '';
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$filename = $rs->getString('filename');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $filename;
	}

	/**
	 * Pobierz zawartość wybranego nagłówka szablonu transakcyjnego
	 * @param integer identyfikator nagłówka szablonu
	 * @return string zawartość nagłówka, jeśli istnieje lub pusty string
	 * @access public
	 */
	public function getHeaderContentForTemplate ($idtransmailheader)
	{
		$sql = "SELECT contenthtml
					FROM transmailheader
					WHERE idtransmailheader= :idtransmailheader";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmailheader', $idtransmailheader);
		$headercontenthtml = '';
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$headercontenthtml = $rs->getString('contenthtml');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $headercontenthtml;
	}

	/**
	 * Pobierz zawartość wybranego stopki szablonu transakcyjnego
	 * @param integer identyfikator stopki szablonu
	 * @return string zawartość stopki, jeśli istnieje lub pusty string
	 * @access public
	 */
	public function getFooterContentForTemplate ($idtransmailfooter)
	{
		$sql = "SELECT contenthtml
					FROM transmailfooter
					WHERE idtransmailfooter= :idtransmailfooter";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmailfooter', $idtransmailfooter);
		$footercontenthtml = '';
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$footercontenthtml = $rs->getString('contenthtml');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $footercontenthtml;
	}

	/**
	 * Pobiera identyfikator maila transakcyjnego, ustawionego jako domyślnego dla wybranej akcji
	 * @param integer identyfikator wybranej akcji
	 * @return integer identyfikator maila transakcyjnego, jeśli istnieje szablon domyślny lub 0 w przeciwnym razie
	 * @access public
	 */
	public function getDefaultTramsMail ($transmailactionid)
	{
		$sql = "SELECT idtransmail
					FROM transmail
					WHERE transmailactionid= :transmailactionid
					AND active=1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('transmailactionid', $transmailactionid);
		$idtransmail = 0;
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idtransmail = $rs->getInt('idtransmail');
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_TEMPLATE_UPDATE'), 112, $e->getMessage());
		}
		return $idtransmail;
	}

	public function doAJAXRefreshTransmail ()
	{
		$objResponse = new xajaxResponse();
		$sql = 'SELECT 
					TMA.idtransmailaction AS id,
					TMA.filetpl 
				FROM transmailaction TMA';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$file = $rs->getString('filetpl');
			if (is_file($fileHandler = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $file . '.tpl')){
				$content = file_get_contents($fileHandler);
				$sql2 = 'UPDATE transmail SET 
							contenthtml= :contenthtml
						WHERE transmailactionid = :transmailactionid';
				$stmt2 = $this->registry->db->prepareStatement($sql2);
				$stmt2->setInt('transmailactionid', $rs->getInt('id'));
				$stmt2->setString('contenthtml', $content);
				$rs2 = $stmt2->executeQuery();
			} 
		}
		$objResponse->script("window.location.reload(false);");
		return $objResponse;
	}
}