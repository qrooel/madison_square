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
 * $Id: producttagslistbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ProductTagsListBoxController extends BoxController
{

	public function index ()
	{
		$productList = App::getModel('productsearch/productsearch')->getProductTags((int) $this->registry->core->getParam());
		if (count($productList) > 0){
			$this->heading = $productList[0]['tagname'];
		}
		$this->registry->template->assign('productList', $productList);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		if (isset($this->heading)){
			return 'layout-box-type-product-list';
		}
	}

	public function getBoxHeading ()
	{
		if (isset($this->heading)){
			return $this->registry->core->getMessage('TXT_PRODUCT_TAGS_RESULTS') . ' "' . $this->heading . '"';
		}
		else{
			return $this->registry->core->getMessage('TXT_PRODUCT_TAGS_RESULTS');
		}
	}
}