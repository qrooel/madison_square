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
 * $Revision: 468 $
 * $Author: gekosale $
 * $Date: 2011-08-31 16:24:34 +0200 (Śr, 31 sie 2011) $
 * $Id: graphicsbox.php 468 2011-08-31 14:24:34Z gekosale $
 */

class FacebookLikeBoxController extends BoxController
{

	public function index ()
	{
		$height = $this->_boxAttributes['height']+20;
		$this->_style = "padding: 0px;margin-top:0px;height: {$height}px;";
		$this->registry->template->assign('data', $this->_boxAttributes);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

}