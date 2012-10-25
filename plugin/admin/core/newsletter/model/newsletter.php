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
 * $Id: newsletter.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class newsletterModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('newsletter', Array(
			'idnewsletter' => Array(
				'source' => 'idnewsletter'
			),
			'name' => Array(
				'source' => 'name'
			),
			'subject' => Array(
				'source' => 'subject'
			),
			'email' => Array(
				'source' => 'email'
			),
			'adddate' => Array(
				'source' => 'adddate'
			),
			'editdate' => Array(
				'source' => 'editdate'
			)
		));
		$datagrid->setFrom('
				newsletter 
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getNewsletterForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteNewsletter ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteNewsletter'
		), $this->getName());
	}

	public function deleteNewsletter ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idnewsletter' => $id
			), $this->getName(), 'deleteNewsletter');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getNewsletterData ($id)
	{
		$sql = "SELECT name, email, subject, htmlform, textform 
					FROM newsletter 
					WHERE idnewsletter = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'email' => $rs->getString('email'),
				'subject' => $rs->getString('subject'),
				'htmlform' => $rs->getString('htmlform'),
				'textform' => $rs->getString('textform')
			)//					'groups' => $this->clientgroupnewsletterhistory($id),
			//					'clients' => $this->clientnewsletterhistory($id)
			;
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_NO_EXIST'));
		}
		return $Data;
	}

	public function clientnewsletterhistory ($id)
	{
		$sql = 'SELECT clientnewsletterid 
					FROM clientnewsletterhistory
					WHERE newsletterid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['clientnewsletterid'][] = $rs->getInt('clientnewsletterid');
		}
		return $Data;
	}

	public function clientgroupnewsletterhistory ($id)
	{
		$sql = 'SELECT clientgroupid 
					FROM clientgroupnewsletterhistory
					WHERE newsletterid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['clientgroupid'][] = $rs->getInt('clientgroupid');
		}
		return $Data;
	}

	public function addNewsletter ($Data)
	{
		$sql = 'INSERT INTO newsletter (name, email, subject, textform, htmlform, addid) 
					VALUES (:name, :email, :subject, :textform, :htmlform, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('textform', $Data['textform']);
		$stmt->setString('subject', $Data['subject']);
		$stmt->setString('htmlform', $Data['htmlform']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewNewsletterHistory ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newsletterid = $this->addNewsletter($Data);
			//				$this->addClientNewsletterHostory($Data, $newsletterid);
		//				$this->addClientGroupNewsletterHostory($Data, $newsletterid);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $newsletterid;
	}

	protected function updateClientGroupNewsletterHistory ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM clientgroupnewsletterhistory WHERE newsletterid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		if (is_array($Data['groups'])){
			foreach ($Data['groups'] as $value){
				$sql = 'INSERT INTO clientgroupnewsletterhistory (clientgroupid, newsletterid, addid)
							VALUES (:clientgroupid, :newsletterid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('clientgroupid', $value);
				$stmt->setInt('newsletterid', $id);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_NEWSLETTER_ADD'), 112, $e->getMessage());
				}
			}
		}
		else{
			$sql = 'INSERT INTO clientgroupnewsletterhistory (clientgroupid, newsletterid, addid)
						VALUES (:clientgroupid, :newsletterid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', NULL);
			$stmt->setInt('newsletterid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_NEWSLETTER_ADD'), 11, $e->getMessage());
			}
		}
	
	}

	protected function updateClientNewsletterHistory ($Data, $id)
	{
		$sqlDelete = 'DELETE FROM clientnewsletterhistory WHERE newsletterid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		if (is_array($Data['clients'])){
			foreach ($Data['clients'] as $value){
				$sql = 'INSERT INTO clientnewsletterhistory (clientnewsletterid, newsletterid, addid)
							VALUES (:clientnewsletterid, :newsletterid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('clientnewsletterid', $value);
				$stmt->setInt('newsletterid', $id);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NEWSLETTER_ADD'), 112, $e->getMessage());
				}
			}
		}
		else{
			$sql = 'INSERT INTO clientnewsletterhistory (clientnewsletterid, newsletterid, addid)
						VALUES (:clientnewsletterid, :newsletterid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientnewsletterid', NULL);
			$stmt->setInt('newsletterid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NEWSLETTER_ADD'), 11, $e->getMessage());
			}
		}
	}

	public function updateNewsletter ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->editNewsletter($Data, $id);
			//				$this->updateClientNewsletterHistory($Data, $id);
		//				$this->updateClientGroupNewsletterHistory($Data, $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_EDIT'), 3002, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function editNewsletter ($Data, $id)
	{
		$sql = 'UPDATE 	newsletter SET name=:name, email=:email, textform=:textform, subject=:subject, 
							htmlform=:htmlform, editid=:editid
					WHERE idnewsletter =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('htmlform', $Data['htmlform']);
		$stmt->setString('subject', $Data['subject']);
		$stmt->setString('textform', $Data['textform']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_EDIT'), 13, $e->getMessage());
			return false;
		}
		return true;
	}

	public function addClientGroupNewsletterHostory ($Data, $newsletterid)
	{
		if (is_array($Data['groups'])){
			foreach ($Data['groups'] as $value){
				$sql = 'INSERT INTO clientgroupnewsletterhistory (clientgroupid, newsletterid, addid)
							VALUES (:clientgroupid, :newsletterid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('clientgroupid', $value);
				$stmt->setInt('newsletterid', $newsletterid);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_NEWSLETTER_ADD'), 112, $e->getMessage());
				}
			}
		}
		else{
			$sql = 'INSERT INTO clientgroupnewsletterhistory (clientgroupid, newsletterid, addid)
						VALUES (:clientgroupid, :newsletterid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', NULL);
			$stmt->setInt('newsletterid', $newsletterid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_NEWSLETTER_ADD'), 11, $e->getMessage());
			}
		}
	}

	public function addClientNewsletterHostory ($Data, $newsletterid)
	{
		if (is_array($Data['clients'])){
			foreach ($Data['clients'] as $value){
				$sql = 'INSERT INTO clientnewsletterhistory (clientnewsletterid, newsletterid, addid)
							VALUES (:clientnewsletterid, :newsletterid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('clientnewsletterid', $value);
				$stmt->setInt('newsletterid', $newsletterid);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NEWSLETTER_ADD'), 112, $e->getMessage());
				}
			}
		}
		else{
			$sql = 'INSERT INTO clientnewsletterhistory (clientnewsletterid, newsletterid, addid)
						VALUES (:clientnewsletterid, :newsletterid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientnewsletterid', NULL);
			$stmt->setInt('newsletterid', $newsletterid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_NEWSLETTER_ADD'), 11, $e->getMessage());
			}
		}
	}
}