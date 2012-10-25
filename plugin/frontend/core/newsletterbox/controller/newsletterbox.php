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
 * $Id: newsletterbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class NewsletterBoxController extends BoxController
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'addNewsletter',
			App::getModel('newsletter'),
			'addAJAXClientAboutNewsletter'
		));
		$this->registry->xajax->registerFunction(array(
			'deleteNewsletter',
			App::getModel('newsletter'),
			'deleteAJAXClientAboutNewsletter'
		));
		
		$param = $this->registry->core->getParam();
		if (! empty($param) && $this->registry->router->getCurrentController() == 'newsletter'){
			$linkActive = App::getModel('newsletter')->checkLinkToActivate($param);
			if ($linkActive > 0){
				$change = App::getModel('newsletter')->changeNewsletterStatus($linkActive);
				$this->registry->template->assign('activelink', 1);
			}
			else{
				$inactiveLink = App::getModel('newsletter')->checkInactiveNewsletter($param);
				if ($inactiveLink > 0){
					App::getModel('newsletter')->deleteClientNewsletter($inactiveLink);
					$this->registry->template->assign('inactivelink', 1);
				}
				else{
					$this->registry->template->assign('errlink', 1);
				}
			}
		}
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}