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
 * $Id: contact.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class contactModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('contact', Array(
			'idcontact' => Array(
				'source' => 'C.idcontact'
			),
			'name' => Array(
				'source' => 'CT.name',
				'prepareForAutosuggest' => true
			),
			'email' => Array(
				'source' => 'CT.email'
			),
			'phone' => Array(
				'source' => 'CT.phone'
			),
			'fax' => Array(
				'source' => 'CT.fax'
			),
			'address' => Array(
				'source' => 'CONCAT(CT.street, \' \', CT.streetno, \' \', CT.placeno, \', \', CT.postcode, \' \', CT.placename)'
			),
			'street' => Array(
				'source' => 'CT.street',
				'prepareForAutosuggest' => true
			),
			'streetno' => Array(
				'source' => 'CT.streetno'
			),
			'placeno' => Array(
				'source' => 'CT.placeno'
			),
			'postcode' => Array(
				'source' => 'CT.postcode'
			),
			'placename' => Array(
				'source' => 'CT.placename',
				'prepareForAutosuggest' => true
			)
		));
		
		$datagrid->setFrom('
			contact C
			LEFT JOIN contacttranslation CT ON CT.contactid = C.idcontact AND CT.languageid = :languageid
			LEFT JOIN contactview CV ON CV.contactid = C.idcontact
		');
		
		$datagrid->setAdditionalWhere('
			CV.viewid IN (:viewids)
		');
		
		$datagrid->setGroupBy('C.idcontact');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getStreetForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('street', $request, $processFunction);
	}

	public function getPlacenameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('placename', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getContactForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteContact ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteContact'
		), $this->getName());
	}

	public function deleteContact ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idcontact' => $id
			), $this->getName(), 'deleteContact');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getContactView ($id)
	{
		$sql = "SELECT idcontact AS id,	publish 
				FROM contact 
				WHERE idcontact = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'publish' => $rs->getInt('publish'),
				'language' => $this->getContactTranslation($id),
				'view' => $this->getContactViews($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_NO_EXIST'));
		}
		return $Data;
	}

	public function getContactTranslation ($id)
	{
		$sql = "SELECT 
					name, 
					email, 
					phone, 
					fax, 
					street, 
					streetno, 
					placeno, 
					placename, 
					postcode, 
					languageid,
					countryid,
					businesshours
				FROM contacttranslation
				WHERE contactid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'email' => $rs->getString('email'),
				'phone' => $rs->getString('phone'),
				'fax' => $rs->getString('fax'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'placename' => $rs->getString('placename'),
				'postcode' => $rs->getString('postcode'),
				'countryid' => $rs->getInt('countryid'),
				'businesshours' => $rs->getString('businesshours')
			);
		}
		return $Data;
	}

	public function getContactViews ($id)
	{
		$sql = "SELECT viewid FROM contactview WHERE contactid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function editContact ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateContact($Data['publish'], $id);
			$this->updateContactTranslation($Data, $id);
			$this->updateContactView($Data['view'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateContact ($publish, $id)
	{
		$sql = 'UPDATE contact SET 
					publish = :publish,
					editid = :editid
				WHERE idcontact = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (! empty($publish)){
			$stmt->setInt('publish', $publish);
		}
		else{
			$stmt->setInt('publish', 0);
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_EDIT'), 13, $e->getMessage());
		}
	}

	public function updateContactTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM contacttranslation WHERE contactid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO contacttranslation SET
						contactid = :contactid,
						name = :name, 
						email = :email, 
						street = :street,
						streetno = :streetno,
						placeno = :placeno,
						fax = :fax,
						phone = :phone,
						placename = :placename,
						postcode = :postcode,
						languageid = :languageid,
						countryid = :countryid,
						businesshours = :businesshours';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('contactid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('email', $Data['email'][$key]);
			$stmt->setString('street', $Data['street'][$key]);
			$stmt->setString('streetno', $Data['streetno'][$key]);
			$stmt->setString('placeno', $Data['placeno'][$key]);
			$stmt->setString('fax', $Data['fax'][$key]);
			$stmt->setString('phone', $Data['phone'][$key]);
			$stmt->setString('placename', $Data['placename'][$key]);
			$stmt->setString('postcode', $Data['postcode'][$key]);
			$stmt->setString('businesshours', $Data['businesshours'][$key]);
			$stmt->setInt('countryid', $Data['countryid'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_TRANSLATION_EDIT'), 13, $e->getMessage());
			}
		}
	}

	public function updateContactView ($array, $id)
	{
		$sql = 'DELETE FROM contactview WHERE contactid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (is_array($array) && ! empty($array)){
			foreach ($array as $key => $val){
				$sql = 'INSERT INTO contactview (contactid,viewid, addid)
						VALUES (:contactid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->setInt('contactid', $id);
				$stmt->setInt('viewid', $val);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_VIEW_EDIT'), 4, $e->getMessage());
				}
			}
		}
	}

	public function addNewContact ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newContactId = $this->addContact($Data['publish']);
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addContactView($Data['view'], $newContactId);
			}
			$this->addContactTranslation($Data, $newContactId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addContact ($publish)
	{
		$sql = 'INSERT INTO contact 
				SET	publish = :publish,
				addid = :addid';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (! empty($publish)){
			$stmt->setInt('publish', $publish);
		}
		else{
			$stmt->setInt('publish', 0);
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addContactView ($array, $id)
	{
		foreach ($array as $value){
			$sql = 'INSERT INTO contactview 
					SET	contactid = :contactid,
					viewid = :viewid, 
					addid = :addid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('contactid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_VIEW_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function addContactTranslation ($Data, $id)
	{
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO contacttranslation SET
						contactid = :contactid,
						name = :name, 
						email = :email, 
						street = :street,
						streetno = :streetno,
						placeno = :placeno,
						fax = :fax,
						phone = :phone,
						placename = :placename,
						postcode = :postcode,
						languageid = :languageid,
						countryid = :countryid,
						businesshours = :businesshours';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('contactid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('email', $Data['email'][$key]);
			$stmt->setString('street', $Data['street'][$key]);
			$stmt->setString('streetno', $Data['streetno'][$key]);
			$stmt->setString('placeno', $Data['placeno'][$key]);
			$stmt->setString('fax', $Data['fax'][$key]);
			$stmt->setString('phone', $Data['phone'][$key]);
			$stmt->setString('placename', $Data['placename'][$key]);
			$stmt->setString('postcode', $Data['postcode'][$key]);
			$stmt->setString('businesshours', $Data['businesshours'][$key]);
			$stmt->setInt('countryid', $Data['countryid'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTACT_TRANSLATION_ADD'), 13, $e->getMessage());
			}
		}
	}
}