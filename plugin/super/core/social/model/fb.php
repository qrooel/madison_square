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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: breadcrumb.php 438 2011-08-27 09:29:36Z gekosale $ 
 */
require ROOTPATH . 'lib' . DS . 'social' . DS . 'facebook' . DS . 'facebook.php';
class fbModel extends Model
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->facebook = new Facebook(array(
			'appId' => $this->layer['faceboookappid'],
			'secret' => $this->layer['faceboooksecret']
		));
	}

	public function getParsedSignedRequest ()
	{
		return $this->facebook->getSignedRequest();
	}

	public function checkUser ()
	{
		$user = $this->facebook->getUser();
		if ($user){
			$Data = Array(
				'facebookid' => $user,
				'url' => $this->facebook->getLogoutUrl()
			);
		}
		else{
			$Data = Array(
				'facebookid' => NULL,
				'url' => $this->facebook->getLoginUrl()
			);
		}
		return $Data;
	}

	public function getUserProfile ()
	{
		$user = $this->facebook->getUser();
		return $this->facebook->api($user);
	}
}
