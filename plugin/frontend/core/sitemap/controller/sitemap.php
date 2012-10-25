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
 * $Id: sitemap.php 655 2012-04-24 08:51:44Z gekosale $
 */

class SitemapController extends Controller
{

	public function index ()
	{
		if ($this->registry->core->getParam() > 0){
			$this->registry->viewer->setHeader('xml');
			$this->disableLayout();
			try{
				App::getModel('sitemap')->generateSitemap($this->registry->core->getParam());
			}
			catch (Exception $e){
				echo $e->getMessage();
			}
		}
		else{
			$this->Render('Sitemap');
		}
	
	}

}