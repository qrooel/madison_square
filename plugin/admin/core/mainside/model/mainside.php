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
 * $Id: mainside.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class MainsideModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function salesChart ($request)
	{
		$seriesXML = '';
		$graphsXML = '';
		$from = $request['from'];
		$to = $request['to'];
		$sql = 'SELECT 
					DATE(adddate) AS adddate,
					ROUND(SUM(globalprice),2) as total
				FROM `order`
				WHERE (DATE(adddate) BETWEEN DATE(:from) AND DATE(:to)) AND viewid IN(:viewids)
				GROUP BY DATE(adddate) 
				ORDER BY adddate ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('from', $from);
		$stmt->setString('to', $to);
		$rs = $stmt->executeQuery();
		$s = 0;
		while ($rs->next()){
			$seriesXML .= '<value xid="' . $s . '">' . $rs->getString('adddate') . '</value>';
			$graphsXML .= '<value xid="' . $s . '">' . $rs->getString('total') . '</value>';
			$s ++;
		}
		return $this->getChart($seriesXML, $graphsXML);
	}

	public function ordersChart ($request)
	{
		$seriesXML = '';
		$graphsXML = '';
		$from = $request['from'];
		$to = $request['to'];
		$sql = 'SELECT 
					DATE(adddate) AS adddate,
					COUNT(idorder) as total
				FROM `order`
				WHERE (DATE(adddate) BETWEEN DATE(:from) AND DATE(:to)) AND viewid IN(:viewids)
				GROUP BY DATE(adddate) 
				ORDER BY adddate ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('from', $from);
		$stmt->setString('to', $to);
		$rs = $stmt->executeQuery();
		$s = 0;
		while ($rs->next()){
			$seriesXML .= '<value xid="' . $s . '">' . $rs->getString('adddate') . '</value>';
			$graphsXML .= '<value xid="' . $s . '">' . $rs->getString('total') . '</value>';
			$s ++;
		}
		return $this->getChart($seriesXML, $graphsXML);
	}

	public function clientsChart ($request)
	{
		$seriesXML = '';
		$graphsXML = '';
		$from = $request['from'];
		$to = $request['to'];
		$sql = 'SELECT 
					DATE(adddate) AS adddate,
					COUNT(idclient) as total
				FROM `client`
				WHERE (DATE(adddate) BETWEEN DATE(:from) AND DATE(:to)) AND viewid IN(:viewids)
				GROUP BY DATE(adddate) 
				ORDER BY adddate ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('from', $from);
		$stmt->setString('to', $to);
		$rs = $stmt->executeQuery();
		$s = 0;
		while ($rs->next()){
			$seriesXML .= '<value xid="' . $s . '">' . $rs->getString('adddate') . '</value>';
			$graphsXML .= '<value xid="' . $s . '">' . $rs->getString('total') . '</value>';
			$s ++;
		}
		return $this->getChart($seriesXML, $graphsXML);
	}

	public function getLastOrder ()
	{
		$sql = "SELECT AES_DECRYPT(OCD.surname, :encryptionKey) surname, O.globalprice, OCD.`adddate`, O.idorder 
						FROM `order` O
						LEFT JOIN orderclientdata OCD ON OCD.orderid=idorder
						WHERE O.viewid IN (:viewids)
 						ORDER BY idorder DESC LIMIT 10";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'price' => sprintf('%.2f', $rs->getFloat('globalprice')),
				'id' => $rs->getInt('idorder'),
				'surname' => $rs->getString('surname')
			);
		}
		return $Data;
	}

	public function getNewClient ()
	{
		$sql = "SELECT CD.clientid,AES_DECRYPT(surname, :encryptionKey) surname, AES_DECRYPT(firstname, :encryptionKey) firstname 
						FROM clientdata CD
						LEFT JOIN client C ON C.idclient=clientid
						WHERE C.viewid IN (:viewids)
 						ORDER BY CD.adddate DESC LIMIT 10";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'surname' => $rs->getString('surname'),
				'id' => $rs->getInt('clientid'),
				'firstname' => $rs->getString('firstname')
			);
		}
		return $Data;
	}

	public function getTopTen ()
	{
		$sql = "SELECT 
					OP.productid, 
					ROUND(OP.qty * OP.price,2) as productprice, 
					OP.name as productname, 
					SUM(OP.qty) as bestorder
				FROM orderproduct OP
				LEFT JOIN `order` O ON O.idorder = OP.orderid
				LEFT JOIN product P ON P.idproduct = OP.productid
				WHERE O.viewid IN (:viewids)
 				GROUP BY OP.productid 
 				ORDER BY bestorder DESC LIMIT 10";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'productprice' => sprintf('%.2f', $rs->getFloat('productprice')),
				'label' => $rs->getString('productname'),
				'value' => $rs->getInt('bestorder'),
				'productid' => $rs->getInt('productid')
			);
		}
		return $Data;
	}

	public function getMostSearch ()
	{
		$sql = "SELECT textcount as qty, name as productname 
					FROM mostsearch 
					WHERE viewid IN (:viewids)
					GROUP BY name ORDER BY qty DESC LIMIT 10;";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'productname' => $rs->getString('productname'),
				'qty' => $rs->getInt('qty')
			);
		}
		return $Data;
	}

	public function getStock ()
	{
		$sql = "SELECT PT.name, P.stock
					FROM product P
					LEFT JOIN producttranslation PT ON PT.productid = P.idproduct AND languageid=:languageid
					WHERE P.stock < 10 ORDER BY stock";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'label' => $rs->getString('name'),
				'value' => $rs->getInt('stock')
			);
		}
		return $Data;
	}

	public function getClientOnline ()
	{
		$sql = 'SELECT 
					SH.sessionid, 
					SH.clientid, 
					AES_DECRYPT(CD.firstname, :encryptionKey) firstname, 
					AES_DECRYPT(CD.surname, :encryptionKey) surname 
				FROM sessionhandler  SH
				LEFT JOIN clientdata CD ON CD.clientid = SH.clientid
				WHERE SH.viewid IN(:viewids) AND SH.clientid > 0
				GROUP BY SH.sessionid 
				LIMIT 10';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$Data = Array();
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'clientid' => $rs->getInt('clientid'),
				'sessionid' => $rs->getString('sessionid')
			);
		}
		return $Data;
	}

	public function getSummaryStats ()
	{
		$Data = Array();
		$period = date("Ym");
		$sql = 'SELECT ROUND(SUM(globalprice),2) as total, COUNT(idorder) as orders
					FROM `order`
					WHERE viewid IN (:viewids) AND DATE_FORMAT(adddate,\'%Y%m\') = :period';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('period', $period);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['summarysales'] = Array(
				'total' => sprintf('%.2f', $rs->getFloat('total')),
				'orders' => $rs->getInt('orders')
			);
		}
		//Daily sales
		$sql = 'SELECT ROUND(SUM(globalprice),2) as total, COUNT(idorder) as orders
					FROM `order`
					WHERE viewid IN (:viewids) AND DATE_FORMAT(adddate,\'%Y-%m-%d\') = CURDATE()';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['todaysales'] = Array(
				'total' => sprintf('%.2f', $rs->getFloat('total')),
				'orders' => $rs->getInt('orders')
			);
		}
		//Total clients
		$sql = 'SELECT COUNT(idclient) as totalclients
					FROM `client`
					WHERE viewid IN (:viewids) AND DATE_FORMAT(adddate,\'%Y%m\') = :period';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('period', $period);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['summaryclients'] = Array(
				'totalclients' => (int) $rs->getInt('totalclients')
			);
		}
		//Daily clients
		$sql = 'SELECT COUNT(idclient) as clients
					FROM `client`
					WHERE viewid IN (:viewids) AND DATE_FORMAT(adddate,\'%Y-%m-%d\') = CURDATE()';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['todayclients'] = Array(
				'totalclients' => (int) $rs->getInt('clients')
			);
		}
		return $Data;
	}

	public function getMostViewedProducts ($url, $productData)
	{
		$Data = Array();
		foreach ($productData as $key => $productid){
			$sql = 'SELECT name	FROM producttranslation WHERE productid=:productid AND languageid=:languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$stmt->setInt('productid', $productid);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'name' => $rs->getString('name'),
					'id' => $productid,
					'qty' => $url[$key]['qty']
				);
			}
		}
		return $Data;
	}

	protected function getChart ($seriesXML, $graphsXML)
	{
		$xml = '<chart>';
		
		$xml .= '<series>';
		if ($seriesXML != ''){
			$xml .= $seriesXML;
		}
		else{
			$xml .= '<value xid="0">0</value>';
		}
		$xml .= '</series>';
		
		$xml .= '<graphs>';
		
		$xml .= '<graph gid="0">';
		if ($graphsXML != ''){
			$xml .= $graphsXML;
		}
		else{
			$xml .= '<value xid="0">0</value>';
		}
		$xml .= '</graph>';
		
		$xml .= '</graphs>';
		
		$xml .= '</chart>';
		
		return $xml;
	}

	public function search ($phrase)
	{
		$phrase = strtolower($phrase);
		
		$sql = '
			SELECT 
				O.idorder, 
				O.adddate,
				AES_DECRYPT(OC.surname,:encryptionkey) AS surname,
				AES_DECRYPT(OC.firstname,:encryptionkey) AS firstname,
				AES_DECRYPT(OC.email,:encryptionkey) AS email
			FROM `order` O
			LEFT JOIN orderclientdata OC ON OC.orderid=O.idorder
			WHERE 
				O.idorder = :id OR
				CONVERT(LOWER(AES_DECRYPT(OC.surname,:encryptionkey)) USING utf8) LIKE :phrase OR
				CONVERT(LOWER(AES_DECRYPT(OC.firstname,:encryptionkey)) USING utf8) LIKE :phrase OR
				CONVERT(LOWER(AES_DECRYPT(OC.email,:encryptionkey)) USING utf8) LIKE :phrase
			ORDER BY O.adddate DESC
			LIMIT 10
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $phrase);
		$stmt->setString('phrase', '%' . $phrase . '%');
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$url = App::getURLAdressWithAdminPane().'order/edit/'.$rs->getInt('idorder');
			$str = '#' . $rs->getInt('idorder') . ': ' . $rs->getString('firstname') . ' ' . $rs->getString('surname') . ' (' . $rs->getString('email') . ') z dnia ' . $rs->getString('adddate');
			$str = $this->highlight($phrase, $str);
			$str = '<li><a href="'.$url.'">'.$str.'</a></li>';
			$Data['orders'][] = $str;
		}
		
		$sql = '
			SELECT 
				OC.clientid, 
				AES_DECRYPT(OC.surname,:encryptionkey) AS surname,
				AES_DECRYPT(OC.firstname,:encryptionkey) AS firstname,
				AES_DECRYPT(OC.email,:encryptionkey) AS email
			FROM clientdata OC
			WHERE 
				CONVERT(LOWER(AES_DECRYPT(OC.surname,:encryptionkey)) USING utf8) LIKE :phrase OR
				CONVERT(LOWER(AES_DECRYPT(OC.firstname,:encryptionkey)) USING utf8) LIKE :phrase OR
				CONVERT(LOWER(AES_DECRYPT(OC.email,:encryptionkey)) USING utf8) LIKE :phrase
			LIMIT 10
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $phrase);
		$stmt->setString('phrase', '%' . $phrase . '%');
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$url = App::getURLAdressWithAdminPane().'client/edit/'.$rs->getInt('clientid');
			$str = $rs->getString('firstname') . ' ' . $rs->getString('surname') . ' (' . $rs->getString('email') . ')';
			$str = $this->highlight($phrase, $str);
			$str = '<li><a href="'.$url.'">'.$str.'</a></li>';
			$Data['clients'][] = $str;
		}
		
		$sql = '
			SELECT 
				PT.productid,
				PT.name,
				P.ean,
				P.delivelercode
			FROM product P
			LEFT JOIN producttranslation PT ON PT.productid = P.idproduct AND PT.languageid = :languageid
			WHERE 
				PT.name LIKE :phrase OR
				P.ean LIKE :phrase OR
				P.delivelercode LIKE :phrase
			LIMIT 20
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $phrase);
		$stmt->setString('phrase', '%' . $phrase . '%');
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$url = App::getURLAdressWithAdminPane().'product/edit/'.$rs->getInt('productid');
			$str = $rs->getString('name');
			if($rs->getString('ean') != ''){
			$str.= ', EAN: '.$rs->getString('ean');
			}
			$str = $this->highlight($phrase, $str);
			$str = '<li><a href="'.$url.'">'.$str.'</a></li>';
			$Data['products'][] = $str;
		}
	
		
		return $Data;
	}

	public function highlight ($needle, $haystack)
	{
		$ind = stripos($haystack, $needle);
		$len = strlen($needle);
		if ($ind !== false){
			return substr($haystack, 0, $ind) . "<b>" . substr($haystack, $ind, $len) . "</b>" . $this->highlight($needle, substr($haystack, $ind + $len));
		}
		else
			return $haystack;
	}
}