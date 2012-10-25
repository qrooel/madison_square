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
 * $Id: forgotpassword.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ForgotPasswordModel extends Model
{

	public function authProccess ($login)
	{
		$sql = 'SELECT idclient,disable FROM client WHERE login = :login AND viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', $login);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if ($rs->getInt('disable') == 0){
				return $rs->getInt('idclient');
			}
			else{
				return - 1;
			}
		}
		else{
			return 0;
		}
	}

	public function forgotPassword ($mail, $pass)
	{
		$sql = 'UPDATE client SET
					password=:pass
					WHERE login=:mail';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('mail', $mail);
		$stmt->setString('pass', $pass);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return true;
	}
}