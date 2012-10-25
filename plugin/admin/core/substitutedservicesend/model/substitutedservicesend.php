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
 * $Id: substitutedservicesend.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SubstitutedservicesendModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('substitutedservice', Array(
			'idsubstitutedservice' => Array(
				'source' => 'S.idsubstitutedservice'
			),
			'name' => Array(
				'source' => 'S.name'
			),
			'transmailname' => Array(
				'source' => 'TM.name'
			)
		));
		
		$datagrid->setFrom('
				substitutedservice S
				LEFT JOIN transmail TM ON S.transmailid = TM.idtransmail
			');
		
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, 1, S.viewid= :viewid)
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSubstitutedserviceForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	/**
	 * Metoda, na podstawie identyfikatora akcji
	 * pobiera identyfikatory klientów, spełniających kryteria
	 * do wysyłki powiadomienia.
	 * 
	 * @param integet substitutedServiceId (identyfikator wybranego powiadomienia)
	 * @return array Data (tablica zawierająca identyfikatory klientów)
	 * @access public
	 */
	public function getClientsForSubstitutedServicesSend ($substitutedServiceId)
	{
		
		$substitutedServiceInfo = $this->getSubstitutedService($substitutedServiceId);
		$Data = Array();
		if (! empty($substitutedServiceInfo) && isset($substitutedServiceInfo['actionid'])){
			$action = $substitutedServiceInfo['actionid'];
			switch ($action) {
				case 1:
					$sql = "SELECT DISTINCT(C.idclient),
	      							CD.firstname, CD.surname, CD.email
								FROM client C
									LEFT JOIN clientdata CD ON C.idclient = CD.clientid
									LEFT JOIN `order` O ON C.idclient = O.clientid
								WHERE O.adddate <= :newdate
									AND IF (:viewid IS NOT NULL, O.viewid= :viewid, 1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$stmt->setInt('viewid', $viewid);
					}
					else{
						$stmt->setNull('viewid');
					}
					$stmt->setString('newdate', $substitutedServiceInfo['newdate']);
					try{
						$rs = $stmt->executeQuery();
						while ($rs->next()){
							$Data[] = Array(
								'idclient' => $rs->getInt('idclient')
							);
						}
					}
					catch (Exception $e){
						throw new CoreException($e->getMessage());
					}
					break;
				
				case 2:
					$sql = "SELECT DISTINCT(C.idclient),
	      							CD.firstname, CD.surname, CD.email
								FROM client C
									LEFT JOIN clientdata CD ON C.idclient = CD.clientid
									LEFT JOIN clienthistorylog CHL ON C.idclient = CHL.clientid
								WHERE CHL.`adddate` <= :newdate
									AND IF (:viewid IS NOT NULL, CHL.viewid= :viewid, 1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$stmt->setInt('viewid', $viewid);
					}
					else{
						$stmt->setNull('viewid');
					}
					$stmt->setString('newdate', $substitutedServiceInfo['newdate']);
					try{
						$rs = $stmt->executeQuery();
						while ($rs->next()){
							$Data[] = Array(
								'idclient' => $rs->getInt('idclient')
							);
						}
					}
					catch (Exception $e){
						throw new CoreException($e->getMessage());
					}
					break;
				
				case 3:
					$sql = "SELECT DISTINCT(C.idclient),
	      							CD.firstname, CD.surname, CD.email
								FROM client C
									LEFT JOIN clientdata CD ON C.idclient = CD.clientid
									LEFT JOIN clienthistorylog CHL ON C.idclient = CHL.clientid
								WHERE CHL.`adddate` <= :newdate
									AND IF (:viewid IS NOT NULL, CHL.viewid= :viewid, 1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$stmt->setInt('viewid', $stmt);
					}
					else{
						$stmt->setNull('viewid');
					}
					$stmt->setString('newdate', $substitutedServiceInfo['newdate']);
					try{
						$rs = $stmt->executeQuery();
						while ($rs->next()){
							$Data[] = Array(
								'idclient' => $rs->getInt('idclient')
							);
						}
					}
					catch (Exception $e){
						throw new CoreException($e->getMessage());
					}
					break;
				
				case 4:
					$sql = "SELECT DISTINCT(C.idclient),
	      							CD.firstname, CD.surname, CD.email
								FROM client C
									LEFT JOIN clientdata CD ON C.idclient = CD.clientid
									LEFT JOIN `order` O ON C.idclient = O.clientid
								WHERE O.adddate <= :newdate
	                				AND O.paymentmethodid IN (2, 4, 7)
	                 				AND O.orderstatusid IN (7,9, 17, 18, 21, 22, 24)
	                 				AND IF (:viewid IS NOT NULL, O.viewid= :viewid, 1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$stmt->setInt('viewid', $viewid);
					}
					else{
						$stmt->setNull('viewid');
					}
					$stmt->setString('newdate', $substitutedServiceInfo['newdate']);
					try{
						$rs = $stmt->executeQuery();
						while ($rs->next()){
							$Data[$idclient] = Array(
								'idclient' => $rs->getInt('idclient')
							);
						}
					}
					catch (Exception $e){
						throw new CoreException($e->getMessage());
					}
					break;
				
				case 5:
					$sql = "SELECT DISTINCT(C.idclient),
	      							CD.firstname, CD.surname, CD.email
								FROM client C
									LEFT JOIN clientdata CD ON C.idclient = CD.clientid
									LEFT JOIN `order` O ON C.idclient = O.clientid
								WHERE O.adddate <= :newdate
	                 				AND O.orderstatusid = 6
	                 				AND IF (:viewid IS NOT NULL, O.viewid= :viewid, 1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$viewid = Helper::getViewId();
					if ($viewid > 0){
						$stmt->setInt('viewid', $viewid);
					}
					else{
						$stmt->setNull('viewid');
					}
					$stmt->setString('newdate', $substitutedServiceInfo['newdate']);
					try{
						$rs = $stmt->executeQuery();
						while ($rs->next()){
							$Data[$idclient] = Array(
								'idclient' => $rs->getInt('idclient')
							);
						}
					}
					catch (Exception $e){
						throw new CoreException($e->getMessage());
					}
					break;
			}
		}
		if (! empty($Data)){
			$filtered = $this->filterClientsArray($Data, $substitutedServiceInfo['timeinterval']);
		}
		if (! empty($filtered) && count($filtered) > 0){
			return $filtered;
		}
		else{
			return $Data;
		}
	}

	/**
	 * Pobranie danych dla wybranego powiadomienia.
	 * Na podstawie określonego interwału, metoda wyznacza datę
	 * dla wybranej w szablonie powiadomienia akcji.
	 * 
	 * @param integer idSubstitutedService (identyfikator powiadomienia)
	 * @return array Data (informacje o powiadomieniu)
	 * @access public
	 */
	public function getSubstitutedService ($idSubstitutedService)
	{
		$sql = "SELECT S.idsubstitutedservice, S.transmailid, S.actionid, S.`date`, S.periodid, S.admin, S.name,
							P.idperiod, P.name as pname, P.timeinterval, P.intervalsql
						FROM substitutedservice S
	           				LEFT JOIN period P ON S.periodid = P.idperiod
						WHERE idsubstitutedservice= :idsubstitutedservice";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubstitutedservice', $idSubstitutedService);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'idsubstitutedservice' => $rs->getInt('idsubstitutedservice'),
					'transmailid' => $rs->getInt('transmailid'),
					'actionid' => $rs->getInt('actionid'),
					'date' => $rs->getString('date'),
					'periodid' => $rs->getInt('periodid'),
					'admin' => $rs->getInt('admin'),
					'name' => $rs->getString('name'),
					'idperiod' => $rs->getInt('idperiod'),
					'pname' => $rs->getString('pname'),
					'timeinterval' => $rs->getString('timeinterval'),
					'intervalsql' => $rs->getString('intervalsql'),
					'newdate' => ''
				);
				$dateInterval = $rs->getString('timeinterval');
				if (! empty($dateInterval)){
					$date = new DateTime();
					$date->setDate(date("Y"), date("m"), date("d"));
					$date->modify($dateInterval);
					$Data['newdate'] = $date->format("Y-m-d");
				}
				else{
					$Data['newdate'] = $rs->getString('date');
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Sprawdzenie wyselekcjonowanych klientów do wysyłki powiadomień.
	 * W przypadku, gdy do klienta została już wcześniej wysłana wiadomość,
	 * to ograniczenie czasowe wynikające z określonego interwału uniemożliwi
	 * ponownego wysłania wybranej wiadomości.
	 *  
	 * @param array clientIds
	 * @param string timeinterval (format: -1 month, -5 day)
	 * @return array clientIds
	 * @access public
	 */
	public function filterClientsArray ($clientsArray, $timeinterval)
	{
		$Data = Array();
		if (! empty($timeinterval)){
			$date = new DateTime();
			$date->setDate(date("Y"), date("m"), date("d"));
			$date->modify($timeinterval);
			$newdate = $date->format("Y-m-d");
		}
		$clients = Array();
		foreach ($clientsArray as $client){
			array_push($clients, $client['idclient']);
		}
		$sql = "SELECT SSC.clientid
					FROM substitutedserviceclients SSC
						LEFT JOIN substitutedservicesend SSS ON SSC.substitutedservicesendid = SSS.idsubstitutedservicesend
					WHERE SSC.clientid NOT IN (:clients)
						AND IF(:newdate IS NOT NULL, SSS.senddate <= :newdate, 1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInInt('clients', $clients);
		if (isset($newdate) && $newdate != ''){
			$stmt->setString('newdate', $newdate);
		}
		else{
			$stmt->setNull('newdate');
		}
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getInt('clientid');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getClientsForMailSending ($submittedData, $idSubstitutedService)
	{
		$Data = Array();
		$Data['idsubstitutedservice'] = $idSubstitutedService;
		if (! empty($submittedData['clients'])){
			foreach ($submittedData['clients'] as $client => $clientId){
				$emailClient = App::getModel('client')->getClientMailAddress($clientId);
				if ($emailClient){
					$Data[$clientId] = $emailClient;
				}
			}
		}
		return $Data;
	}

	/**
	 * Pobranie wszytkich informacji do uzupełnienia tagów w pliku szablonu
	 * Brak filtrowania pliku w poszukiwaniu określonego tagu. Z bazy powierane
	 * są informacje dotyczące wszystkich możliwych do zastosowania
	 * tagów, które są dostępne dla powiadomienia o aktywności.
	 *  
	 * @param integer clientId
	 * @param string newdate
	 * @return array 
	 * @access public
	 */
	public function changeMailTagsData ($clienId, $newdate)
	{
		//utwórz tablicę dla wszystkich tagów
		$Data = Array(
			'firstname' => NULL,
			'surname' => NULL,
			'lastDateOrder' => NULL,
			'lastLogged' => NULL,
			'dateOfMaturity' => NULL,
			'termsOfPayment' => NULL,
			'orderDate' => NULL,
			'orderNo' => NULL,
			'orderPrice' => NULL
		);
		//Pobranie imienia i nazwiska
		$clientData = App::getModel('client')->getClientView($clienId);
		if (! empty($clientData)){
			$Data['firstname'] = $clientData['firstname'];
			$Data['surname'] = $clientData['surname'];
		}
		//Pobranie informacji o ostanim niezapłaconym lub niepotwierdzonym zamówieniu klienta
		$sql = "SELECT O.`adddate`, O.idorder, O.paymentmethodname, O.globalprice
					FROM `order` O
					WHERE O.paymentmethodid IN (2, 4, 7)
						AND O.orderstatusid IN (6, 7, 9, 17, 18, 21, 22, 24)
						AND O.clientid= :clientid
					ORDER BY O.`adddate` DESC
					LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clienId);
		$stmt->setString('newdate', $newdate);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data['orderDate'] = $rs->getString('adddate');
				$Data['orderNo'] = $rs->getInt('idorder');
				$Data['termsOfPayment'] = $rs->getString('paymentmethodname');
				$Data['orderPrice'] = $rs->getFloat('globalprice');
				$Data['dateOfMaturity'] = $rs->getString('adddate');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		//Pobranie informacji o ostanim zamówieniu klienta
		$sql = "SELECT O.`adddate`
					FROM `order` O
					WHERE O.clientid= :clientid
					ORDER BY O.`adddate` DESC
					LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clienId);
		$stmt->setString('newdate', $newdate);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->next()){
				$Data['lastDateOrder'] = $rs->getString('adddate');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		//Pobranie informacji o ostanim logowaniu klienta
		$sql = "SELECT CHL.`adddate`
					FROM client C
						LEFT JOIN clientdata CD ON C.idclient = CD.clientid
						LEFT JOIN clienthistorylog CHL ON C.idclient = CHL.clientid
					WHERE CHL.clientid= :clientid
					ORDER BY CHL.`adddate` DESC
					LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clienId);
		$stmt->setString('newdate', $newdate);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->next()){
				$Data['lastLogged'] = $rs->getString('adddate');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Zachowanie informacji o danej wysyłce oraz klientach do których mają zostać przesłane 
	 * powiadomienia.
	 * 
	 * @param array $submittedData- dane przesłane postem z formularza
	 * @param int $substitutedServiceId- identyfikator wybranego powiadomienia
	 * @return int $newId- zwraca nowy identyfikator wysyłki
	 * @access public
	 */
	public function saveSendingInfoNotification ($submittedData, $substitutedServiceId)
	{
		if (isset($submittedData['clients']) && ! empty($submittedData['clients']) && $substitutedServiceId > 0){
			$this->registry->db->setAutoCommit(false);
			$newId = 0;
			try{
				$newId = $this->addNewSubstitutedServiceSend($substitutedServiceId);
				if ($newId > 0){
					$this->addClientToSubstitutedServiceClient($newId, $submittedData['clients']);
				}
				else{
					return 0;
				}
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NOTIFICATION_ADD'), 125, $e->getMessage());
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			return $newId;
		}
		else{
			return 0;
		}
	}

	/**
	 * Dodanie nowej wysyłki
	 * 
	 * @param int $substitutedServiceId- identyfikator wysyłki
	 * @return int idsubstitutedservicesend- nowy identyfikator wysyłki
	 * @access public
	 */
	public function addNewSubstitutedServiceSend ($substitutedServiceId)
	{
		$sql = 'INSERT INTO substitutedservicesend 
					SET
						substitutedserviceid= :substitutedserviceid, 
						senddate= NOW(), 
						sendid= :userid, 
						actionid= (SELECT actionid FROM substitutedservice WHERE idsubstitutedservice= :substitutedserviceid), 
						viewid= :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('substitutedserviceid', $substitutedServiceId);
		$stmt->setInt('userid', $this->registry->session->getActiveUserid());
		$stmt->setInt('actionid', 0);
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_INSERT_SUBSTITUTEDSERVICE_SEND'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	/**
	 * Dołączenie klientów do danej wysyłki oraz przydzielenie im identyfikatorów wysyłki klienta
	 * 
	 * @param int $newSubstitutedServiceSendId- identyfikator wysyłki
	 * @param array clientIds- tablica z identyfikatorami klientów
	 * @access public
	 */
	public function addClientToSubstitutedServiceClient ($newSubstitutedServiceSendId, $clientIds)
	{
		foreach ($clientIds as $clien => $clientId){
			$sql = 'INSERT INTO substitutedserviceclients 
						SET
							substitutedservicesendid= :substitutedservicesendid, 
							clientid= :clientid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('substitutedservicesendid', $newSubstitutedServiceSendId);
			$stmt->setInt('clientid', $clientId);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_INSERT_SUBSTITUTEDSERVICE_SEND'), 4, $e->getMessage());
			}
		}
	}

	/**
	 * Pobranie liczby wszystkich klientów kwalifikujących się do wysłania powiadomienia
	 * 
	 * @return int $count- suma wszystkich klientów
	 * @access public
	 */
	public function getCountClientsForNotification ()
	{
		$id = $this->registry->session->getActiveQuequeParam();
		$count = 0;
		$sql = "SELECT COUNT(clientid) as count
					FROM substitutedserviceclients
					WHERE substitutedservicesendid= :id
						AND send=0
						AND error=0";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$count = $rs->getInt('count');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $count;
	}

	/**
	 * Pobranie pierwszego identyfikatora wysyłki klienta dla określonej wysyłki powiadomienia.
	 *  
	 * @param int substitutedservicesendid - identyfikator wysyłki
	 * @return int idsubstitutedserviceclients- identyfikator wysyłki klienta
	 * @access public
	 */
	public function getIdSubstitutedServiceClients ($id)
	{
		$rowId = 0;
		$sql = "SELECT idsubstitutedserviceclients as id
					FROM substitutedserviceclients
					WHERE substitutedservicesendid= :id
						AND send=0
						AND error=0
					LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$rowId = $rs->getInt('id');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $rowId;
	}

	/**
	 * Pobiera ostatni identyfikator wysyłki klienta dla określonej wysyłki. 
	 * 
	 * @param int substitutedservicesendid - identyfikator wysyłki
	 * @return int idsubstitutedserviceclients- identyfikator wysyłki klienta
	 * @access public
	 */
	public function getLastRecordForNotification ($id)
	{
		$rowId = 0;
		$sql = "SELECT idsubstitutedserviceclients as id
					FROM substitutedserviceclients
					WHERE substitutedservicesendid= :id
						AND send=0
						AND error=0
					ORDER BY idsubstitutedserviceclients DESC
					LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$rowId = $rs->getInt('id');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $rowId;
	}

	/**
	 * Pobranie porcji danych o klientach i identyfikatorach jego wysyłki
	 * 
	 * @param int $startFrom identyfikator wysyłki od którego należy rozpocząć pobieranie kolejnych danych
	 * @param int $chunkSize- ustwia limit pobieranych w porcji danych
	 * @return array $Data zawierająca listę kientów oraz id wysyłek klientów
	 * @access public
	 */
	public function getPartsOfClientsForNotification ($startFrom, $chunkSize)
	{
		$id = $this->registry->session->getActiveQuequeParam();
		$Data = Array();
		if ($id > 0){
			$sql = "SELECT idsubstitutedserviceclients as id, clientid
						FROM substitutedserviceclients
						WHERE substitutedservicesendid = :id
							AND idsubstitutedserviceclients >= :startFrom
							AND send=0 
							AND error=0
						LIMIT :chunkSize";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$stmt->setInt('startFrom', $startFrom);
			$stmt->setInt('chunkSize', $chunkSize);
			try{
				$rs = $stmt->executeQuery();
				while ($rs->next()){
					$Data[] = Array(
						'id' => $rs->getInt('id'),
						'clientid' => $rs->getInt('clientid')
					);
				}
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
			return $Data;
		}
		else{
			return 0;
		}
	}

	/**
	 * Pobranie wszytkich informacji dotyczących wysyłanego powiadomienia o aktywności
	 * 
	 * @param int $idSubstitutedServicesend- identyfikator powiadomienia
	 * @return array $Data- dane dotyczące powiadomienia lub pusta tablica
	 * @access public 
	 */
	public function getSubstitutedServiceForNotification ($idSubstitutedServicesend)
	{
		$sql = "SELECT S.idsubstitutedservice, S.transmailid, S.actionid, S.date, S.periodid, S.admin, S.name,
							P.idperiod, P.name as pname, P.timeinterval, P.intervalsql
					FROM substitutedservicesend SS
						LEFT JOIN substitutedservice S ON SS.substitutedserviceid = S.idsubstitutedservice
	           			LEFT JOIN period P ON S.periodid = P.idperiod
					WHERE SS.idsubstitutedservicesend= :idSubstitutedServicesend";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idSubstitutedServicesend', $idSubstitutedServicesend);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'idsubstitutedservice' => $rs->getInt('idsubstitutedservice'),
					'transmailid' => $rs->getInt('transmailid'),
					'actionid' => $rs->getInt('actionid'),
					'date' => $rs->getString('date'),
					'periodid' => $rs->getInt('periodid'),
					'admin' => $rs->getInt('admin'),
					'name' => $rs->getString('name'),
					'idperiod' => $rs->getInt('idperiod'),
					'pname' => $rs->getString('pname'),
					'timeinterval' => $rs->getString('timeinterval'),
					'intervalsql' => $rs->getString('intervalsql'),
					'newdate' => ''
				);
				$dateInterval = $rs->getString('timeinterval');
				if (! empty($dateInterval)){
					$date = new DateTime();
					$date->setDate(date("Y"), date("m"), date("d"));
					$date->modify($dateInterval);
					$Data['newdate'] = $date->format("Y-m-d");
				}
				else{
					$Data['newdate'] = $rs->getString('date');
				}
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Pobranie łącznej ilości elementów do przetworzenia
	 * 
	 * @return array iTotal (łączna ilość maili), iCompleted = 0
	 * @access public
	 */
	public function doLoadQueque ()
	{
		$total = $this->getCountClientsForNotification(); //CALKOWITA ILOSC ELEMENTOW DO PRZETWORZENIA
		if ($total > 0){
			return Array(
				'iTotal' => $total,
				'iCompleted' => 0
			);
		}
		else{
			return Array(
				'iTotal' => 0,
				'iCompleted' => 0
			);
		}
	}

	/**
	 *  Metoda odpowiadająca za kolejkowanie i wysyłanie e-maili.
	 *  
	 *  @param array request 
	 *  @return array iStartFrom- identyfikator rekordu od którego wysyłana zostanie 
	 *  kolejna paczka 
	 *  @return array iStartFrom =0, bFinished = true po wysłaniu wszystkich e-maili
	 */
	public function doProcessQueque ($request)
	{
		
		$id = $this->registry->session->getActiveQuequeParam();
		$total = $this->getLastRecordForNotification($id);
		
		$chunkSize = intval($request['iChunks']);
		$startFromReq = intval($request['iStartFrom']);
		$totalReq = intval($request['iTotal']);
		
		if ($totalReq > 0){
			//while ($startFromReq <= $total) {
			$i = 0;
			while ($startFromReq <= $totalReq){
				$startFrom = $this->getIdSubstitutedServiceClients($id);
				$clients = $this->getPartsOfClientsForNotification($startFromReq, $chunkSize);
				if (! empty($clients)){
					$transMailData = $this->getSubstitutedServiceForNotification($id);
					$fileName = App::getModel('transmailtemplates')->getFileNameForTransMail($transMailData['transmailid']);
					$end = end($clients);
					foreach ($clients as $client){
						try{
							$i ++;
							$mailsTags = $this->changeMailTagsData($client['clientid'], $transMailData['newdate']);
							$clientMail = App::getModel('client')->getClientMailAddress($client['clientid']);
							$this->registry->template->assign('active', $mailsTags);
							
							$mailer = new Mailer($this->registry);
							$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
							$mailer->loadContentToBody($fileName);
							$mailer->addAddress($clientMail);
							$mailer->setSubject($transMailData['name']);
							$mailer->FromName = $this->registry->session->getActiveShopName();
							try{
								$mailer->Send();
							}
							catch (phpmailerException $e){
							
							}
							$mailer->ClearAddresses();
							$this->updateSendNotificationSuccess($client['id']);
						}
						catch (Exception $e){
							$this->updateSendNotificationError($client['id']);
						}
						if ($client['id'] == $end['id']){
							return Array(
								//'iStartFrom'=> $end['id']
								'iStartFrom' => $i
							);
						}
					}
				}
				else{
					return Array(
						'iStartFrom' => 0,
						'bFinished' => true
					);
				}
			}
			return Array(
				'iStartFrom' => 0,
				'bFinished' => true
			);
		}
		else{
			return Array(
				'iStartFrom' => 0
			);
		}
		//		$chunkSize = intval($request['iChunks']);
	//		$startFrom = intval($request['iStartFrom']);
	//		$total = intval($request['iTotal']);
	//		$i = 0;
	//		while (($i + $startFrom) < $total) {
	//			if (++$i >= $chunkSize) {
	//				return Array(
	//					'iStartFrom'=> $i + $startFrom
	//				);
	//			}
	//		}
	//		return Array(
	//			'iStartFrom'=> $i + $startFrom,
	//			'bFinished' => true
	//		);
	}

	public function doSuccessQueque ($request)
	{
		if ($request['bFinished']){
			return Array(
				'bCompleted' => true
			);
		}
	}

	/**
	 * Aktualizacja flagi dot. poprawnego wysłania wiadomości
	 * @param int idsubstitutedserviceclients
	 * @return bool true jeśli aktualizacja przebiegła pomyślnie
	 *
	 */
	public function updateSendNotificationSuccess ($idsubstitutedserviceclients)
	{
		$sql = "UPDATE substitutedserviceclients
					SET
						send = 1
					WHERE idsubstitutedserviceclients= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $idsubstitutedserviceclients);
		try{
			$rs = $stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return true;
	}

	/**
	 * Aktualizacja flagi dot. wstąpienia błędu wysłania wiadomości
	 * 
	 * @param int idsubstitutedserviceclients
	 * @return bool true w przypadku poprawnej aktualizacji
	 */
	public function updateSendNotificationError ($idsubstitutedserviceclients)
	{
		$sql = "UPDATE substitutedserviceclients
					SET
						error = 1
					WHERE idsubstitutedserviceclients= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $idsubstitutedserviceclients);
		try{
			$rs = $stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return true;
	}

	public function getNotificationstAll ($substitutedserviceid)
	{
		$Data = Array();
		$sql = "SELECT SSS.idsubstitutedservicesend as id, SSS.senddate, SSS.sendid, SSS.actionid, SSS.viewid,
						CONCAT(UD.firstname, ' ', UD.surname) as senduser
					FROM substitutedservicesend SSS
						LEFT JOIN `user` U ON SSS.sendid = U.iduser
						LEFT JOIN `userdata` UD ON U.iduser = UD.userid
					WHERE IF(:substitutedserviceid>0, SSS.substitutedserviceid= :substitutedserviceid, 1)
						AND IF(:viewid >0, SSS.viewid= :viewid, 1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('substitutedserviceid', $substitutedserviceid);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'senddate' => $rs->getString('senddate'),
					'sendid' => $rs->getInt('sendid'),
					'senduser' => $rs->getString('senduser'),
					'actionid' => $rs->getInt('actionid'),
					'viewid' => $rs->getInt('viewid')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getNotificationstAllToSelect ($substitutedserviceid)
	{
		$tmp = Array();
		$notifications = $this->getNotificationstAll($substitutedserviceid);
		if (is_array($notifications) && ! empty($notifications)){
			foreach ($notifications as $notification){
				$tmp[$notification['id']] = $notification['senddate'];
			}
		}
		return $tmp;
	}

	public function GetAllClientsForNotification ($request)
	{
		$Data = Array();
		if (isset($request['id']) && $request['id'] > 0){
			$sql = "SELECT idsubstitutedserviceclients, substitutedservicesendid, clientid, send, error, errorInfo
							FROM substitutedserviceclients
						WHERE  substitutedservicesendid = :substitutedservicesendid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('substitutedservicesendid', $request['id']);
			try{
				$rs = $stmt->executeQuery();
				while ($rs->next()){
					if ($rs->getInt('send') == 1 && $rs->getInt('error') == 0){
						$send = 'Wysłano';
					}
					elseif ($rs->getInt('send') == 0 && $rs->getInt('error') == 1){
						$send = 'Błąd podczas wysyłania';
					}
					else{
						$send = 'Wiadomość nie została jeszcze wysłana';
					}
					$mail = App::getModel('client')->getClientMailAddress($rs->getInt('clientid'));
					if (empty($mail)){
						$mail = $rs->getInt('clientid');
					}
					$cliens[$rs->getInt('idsubstitutedserviceclients')] = Array(
						$mail,
						$send
					);
				}
				if (! empty($cliens)){
					$Data = Array(
						'title' => 'Lista klientów do których wysłano wiadomość ',
						'data' => $cliens
					);
				}
				else{
					$Data = Array(
						'title' => 'Lista klientów jest pusta',
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
				'title' => '',
				'data' => Array()
			);
		}
		return $Data;
	}
}