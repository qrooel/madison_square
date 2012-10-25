<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 692 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:10:30 +0200 (Cz, 06 wrz 2012) $
 * $Id: newslistbox.php 692 2012-09-06 21:10:30Z gekosale $
 */
class NewsListBoxController extends BoxController
{

	public function index ()
	{
		if (($news = Cache::loadObject('news')) === FALSE){
			$news = App::getModel('News')->getNews();
			Cache::saveObject('news', $news, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		if (isset($this->_boxAttributes['newsCount']) && $this->_boxAttributes['newsCount'] > 0){
			$list = array_slice($news, 0, $this->_boxAttributes['newsCount']);
		}
		else{
			$list = $news;
		}
		$this->registry->template->assign('newslist', $list);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		return 'layout-box-type-news';
	}
}