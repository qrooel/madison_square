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
 * $Revision: 139 $
 * $Author: gekosale $
 * $Date: 2011-05-15 16:42:36 +0200 (N, 15 maj 2011) $
 * $Id: categorylist.php 139 2011-05-15 14:42:36Z gekosale $
 */

class producerlistController extends Controller
{

	public function index ()
	{
		$this->Render('Producerlist');
	}
}