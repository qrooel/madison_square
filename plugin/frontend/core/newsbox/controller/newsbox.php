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
 * $Id: newsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class NewsBoxController extends BoxController
{

	public function index ()
	{
		$param = (int) $this->registry->core->getParam();
		if (! empty($param) && $this->registry->router->getCurrentController() == 'news'){
			$this->registry->template->assign('news', App::getModel('News')->getNewsById((int) $this->registry->core->getParam()));
		}
		else{
			if (($news = Cache::loadObject('news')) === FALSE){
				$news = App::getModel('News')->getNews();
				Cache::saveObject('news', $news, Array(
					Cache::SESSION => 0,
					Cache::FILE => 1
				));
			}
			$this->registry->template->assign('newslist', $news);
		}
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxHeading ()
	{
		$news = App::getModel('News')->getNewsById((int) $this->registry->core->getParam());
		if (isset($news['topic'])){
			return $news['topic'];
		}
		else{
			return $this->registry->core->getMessage('TXT_NEWS');
		}
	}
}