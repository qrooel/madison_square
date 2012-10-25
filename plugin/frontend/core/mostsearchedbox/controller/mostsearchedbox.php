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
 * $Revision: 576 $
 * $Author: gekosale $
 * $Date: 2011-10-22 10:23:55 +0200 (So, 22 paź 2011) $
 * $Id: tagsbox.php 576 2011-10-22 08:23:55Z gekosale $
 */

class MostSearchedBoxController extends BoxController
{

	public function index ()
	{
		$search = App::getModel('MostSearchedBox')->getAllMostSearched();
		foreach ($search as $key => $tag){
			$max[] = $tag['textcount'];
		}
		foreach ($search as $key => $tag){
			$search[$key]['percentage'] = ceil(($tag['textcount'] / max($max)) * 10);
		}
		$this->total = count($search);
		$this->registry->template->assign('mostsearched', $search);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function boxVisible ()
	{
		return ($this->total > 0) ? true : false;
	}
}