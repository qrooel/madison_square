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

class NewsletterModel extends Model
{

	public function addAJAXClientAboutNewsletter ($email)
	{
		$objResponseNewClientNewsletter = new xajaxResponse();
		try{
			if ($email == NULL){
				$objResponseNewClientNewsletter->script('GError("' . $this->registry->core->getMessage('ERR_INSERT_CLIENT_TO_NEWSLETTER') . '")');
			}
			else{
				$checkmail = $this->checkMailAddress($email);
				if ($checkmail == true){
					$checkEmailExists = $this->checkEmailIfExists($email);
					if ($checkEmailExists > 0){
						$objResponseNewClientNewsletter->script('GError("' . $this->registry->core->getMessage('ERR_EMAIL_ALREADY_EXISTS') . '")');
						$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					}
					else{
						$newId = $this->addClientAboutNewsletter($email);
						if ($newId > 0){
							$this->updateNewsletterActiveLink($newId, $email);
						}
						$objResponseNewClientNewsletter->script('GMessage("' . $this->registry->core->getMessage('TXT_RECEIVE_EMAIL_WITH_ACTIVE_LINK') . '")');
						$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					}
				}
				else{
					$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					$objResponseNewClientNewsletter->script('GError("' . $this->registry->core->getMessage('ERR_WRONG_FORMAT') . '")');
				}
			}
		}
		catch (FrontendException $fe){
			$objResponseNewClientNewsletter->script('GError("' . $this->registry->core->getMessage('ERR_INSERT_CLIENT_TO_NEWSLETTER') . '")');
			$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
		}
		return $objResponseNewClientNewsletter;
	}

	public function checkEmailIfExists ($email)
	{
		$idclientnewsletter = 0;
		$sql = "SELECT idclientnewsletter
					FROM clientnewsletter
					WHERE email= :email";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('email', $email);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idclientnewsletter = $rs->getInt('idclientnewsletter');
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $idclientnewsletter;
	}

	public function addClientAboutNewsletter ($email)
	{
		$sql = 'INSERT INTO clientnewsletter (email, viewid)
					VALUES (:email, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('email', $email);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function updateNewsletterActiveLink ($idclientnewsletter, $email)
	{
		$date = date("Ymd");
		$activelink = sha1($date . $idclientnewsletter);
		$inactivelink = sha1('unwanted' . $date . $idclientnewsletter);
		$sql = "UPDATE clientnewsletter
					SET 
						activelink= :activelink,
						inactivelink= :inactivelink
					WHERE idclientnewsletter= :idclientnewsletter";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('activelink', $activelink);
		$stmt->setString('inactivelink', $inactivelink);
		$stmt->setInt('idclientnewsletter', $idclientnewsletter);
		try{
			$rs = $stmt->executeUpdate();
			$this->registry->template->assign('newsletterlink', $activelink);
			$this->registry->template->assign('unwantednewsletterlink', $inactivelink);
			
			$mailer = new Mailer($this->registry);
			$mailer->loadContentToBody('addClientNewsletter');
			$mailer->addAddress($email);
			$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
			$mailer->setSubject($this->registry->core->getMessage('TXT_REGISTRATION_NEWSLETTER'));
			try{
				$mailer->Send();
			}
			catch (phpmailerException $e){
				throw new FrontendException($e->getMessage());
			}
			return true;
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}

	public function deleteAJAXClientAboutNewsletter ($email)
	{
		$objResponseNewClientNewsletter = new xajaxResponse();
		try{
			if ($email == NULL){
				$objResponseNewClientNewsletter->assign("info", "innerHTML", '<strong><font color = "red">' . $this->registry->core->getMessage('ERR_DELETE_CLIENT_FROM_NEWSLETTER') . '<br> ' . $this->registry->core->getMessage('ERR_EMPTY_EMAIL_FORM_LOGIN') . '</font></strong>');
			}
			else{
				$checkmail = $this->checkMailAddress($email);
				if ($checkmail == true){
					$checkEmailExists = $this->checkEmailIfExists($email);
					if ($checkEmailExists > 0){
						$this->unsetClientAboutNewsletter($checkEmailExists, $email);
						$objResponseNewClientNewsletter->assign("info", "innerHTML", '<strong><font color = "green">' . $this->registry->core->getMessage('TXT_RECEIVE_EMAIL_WITH_DEACTIVE_LINK') . '</font></strong>');
						$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					}
					else{
						$objResponseNewClientNewsletter->assign("info", "innerHTML", '<strong><font color = "red">' . $this->registry->core->getMessage('ERR_EMAIL_NOT_EXISTS') . '</font></strong>');
						$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					}
				}
				else{
					$objResponseNewClientNewsletter->assign('newsletterformphrase', 'value', '');
					$objResponseNewClientNewsletter->assign("info", "innerHTML", '<strong><font color = "red">' . $this->registry->core->getMessage('ERR_WRONG_FORMAT') . '</font></strong>');
				}
			}
		}
		catch (FrontendException $fe){
			$objResponseNewClientNewsletter->assign("info", "innerHTML", '<strong><font color = "red">' . $this->registry->core->getMessage('ERR_DELETE_CLIENT_FROM_NEWSLETTER') . '</font></strong>');
		}
		return $objResponseNewClientNewsletter;
	}

	public function unsetClientAboutNewsletter ($idclientnewsletter, $email)
	{
		$sql = "SELECT inactivelink
					FROM clientnewsletter
					WHERE idclientnewsletter= :idclientnewsletter";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idclientnewsletter', $idclientnewsletter);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$inactivelink = $rs->getString('inactivelink');
				$this->registry->template->assign('unwantednewsletterlink', $inactivelink);
				
				$mailer = new Mailer($this->registry);
				$mailer->loadContentToBody('addClientNewsletter');
				$mailer->addAddress($email);
				$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
				$mailer->setSubject($this->registry->core->getMessage('TXT_REGISTRATION_NEWSLETTER'));
				try{
					$mailer->Send();
				}
				catch (phpmailerException $e){
					throw new FrontendException($e->getMessage());
				}
				return true;
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}

	public function deleteClientAboutNewsletter ($email)
	{
		$sql = 'DELETE FROM clientnewsletter WHERE email=:email AND viewid=:viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('email', $email);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeQuery();
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}

	public function checkMailAddress ($email)
	{
		if (! ereg("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$", $email)){
			return false;
		}
		return true;
	}

	public function checkLinkToActivate ($activeLink)
	{
		$idclientnewsletter = 0;
		$sql = 'SELECT idclientnewsletter
					FROM clientnewsletter 
					WHERE activelink LIKE :activelink
					AND active=0';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('activelink', $activeLink);
		//$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idclientnewsletter = $rs->getInt('idclientnewsletter');
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
		return $idclientnewsletter;
	}

	public function checkInactiveNewsletter ($inactivelink)
	{
		$idclientnewsletter = 0;
		$sql = 'SELECT idclientnewsletter
					FROM clientnewsletter 
					WHERE inactivelink LIKE :inactivelink';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('inactivelink', $inactivelink);
		//$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idclientnewsletter = $rs->getInt('idclientnewsletter');
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
		return $idclientnewsletter;
	}

	public function changeNewsletterStatus ($idclientnewsletter)
	{
		$sql = "UPDATE clientnewsletter
					SET 
						activelink= :activelink,
						active = 1
					WHERE idclientnewsletter= :idclientnewsletter";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setNull('activelink');
		$stmt->setInt('idclientnewsletter', $idclientnewsletter);
		try{
			$rs = $stmt->executeUpdate();
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}

	public function deleteClientNewsletter ($idclientnewsletter)
	{
		$sql = "DELETE FROM clientnewsletter
					WHERE idclientnewsletter= :idclientnewsletter";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idclientnewsletter', $idclientnewsletter);
		try{
			$rs = $stmt->executeUpdate();
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}
}