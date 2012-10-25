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
 * $Id: cartpreviewbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class CartPreviewBoxController extends BoxController
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'RefreshCart',
			$this,
			'ajax_refreshCart'
		));
		$this->registry->template->assign('cart', $this->getCartTemplate());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	protected function getCartTemplate ()
	{
		$qty = App::getModel('cart/cart')->getProductAllCount();
		$result = $this->registry->template->fetch($this->loadTemplate('items.tpl'));
		return $result;
	}

	public function ajax_refreshCart ()
	{
		$objResponse = new xajaxResponse();
		$objResponse->clear("cart-contents", "innerHTML");
		$objResponse->append("cart-contents", "innerHTML", $this->getCartTemplate());
		return $objResponse;
	}

	public function getBoxTypeClassname ()
	{
		return 'layout-box-type-cart-summary';
	}
}
?>