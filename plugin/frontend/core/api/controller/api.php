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
 * $Revision: 263 $
 * $Author: gekosale $
 * $Date: 2011-07-24 16:23:40 +0200 (N, 24 lip 2011) $
 * $Id: cart.php 263 2011-07-24 14:23:40Z gekosale $
 */

class ApiController extends Controller
{

	public function index ()
	{
		$this->disableLayout();
		$this->registry->server = new Server($this->registry);
		$this->registry->server->handle(App::getModel('api')) or print 'no request';
	}
}