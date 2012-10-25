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
 * $Id: graphicsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class GraphicsBoxController extends BoxController
{

	public function index ()
	{
		$url = '';
		if (substr($this->_boxAttributes['url'], 0, 4) == 'http'){
			$url = $this->_boxAttributes['url'];
		}
		elseif (substr($this->_boxAttributes['url'], 0, 3) == 'www'){
			$url = 'http://' . $this->_boxAttributes['url'];
		}
		else{
			if ($this->_boxAttributes['url'] != ''){
				$url = App::getURLAdress() . $this->_boxAttributes['url'];
			}
		}
		$this->registry->template->assign('url', $url);
		$this->registry->template->assign('height', $this->_boxAttributes['height']);
		$url = str_replace('/design', '', DESIGNPATH);
		$this->_style = "height: {$this->_boxAttributes['height']}px;cursor:hand; background: url('{$url}{$this->_boxAttributes['image']}') {$this->_boxAttributes['align']} no-repeat;";
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

}