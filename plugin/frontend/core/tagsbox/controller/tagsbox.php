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
 * $Id: tagsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class TagsBoxController extends BoxController
{

	public function index ()
	{
		if (($tags = Cache::loadObject('tags')) === FALSE){
			$tags = App::getModel('TagsBox')->getAllTags();
			foreach ($tags as $key => $tag){
				$max[] = $tag['textcount'];
			}
			foreach ($tags as $key => $tag){
				$tags[$key]['percentage'] = ceil(($tag['textcount'] / max($max)) * 10);
			}
			Cache::saveObject('tags', $tags, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		$this->total = count($tags);
		$this->registry->template->assign('tags', $tags);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function boxVisible ()
	{
		return ($this->total > 0) ? true : false;
	}
}