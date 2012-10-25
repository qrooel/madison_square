<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o
 * licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: productaddcartbox.php 687 2012-09-01 12:02:47Z gekosale $
 */

class ProductAddCartBoxController extends BoxController {

	public function index () {
		$this->disableLayout();
		
		$clientModel = App::getModel('client');
		
		$this->registry->xajax->registerFunction(array(
			'addProductToCart',
			App::getModel('cart/cart'),
			'addAJAXProductToCart'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		
		$product = App::getModel('product/product')->getProductAndAttributesById((int) $this->registry->core->getParam());
		App::getModel('product/product')->getPhotos($product);
		
		$selectAttributes = App::getModel('product/product')->getProductAttributeGroups($product);
		$attset = App::getModel('product/product')->getProductVariant($product);
		
		foreach ($selectAttributes as $key => $val){
			natsort($val['attributes']);
			$selectAttributes[$key]['attributes'] = $val['attributes'];
		}
		
		$Data = Array();
		foreach ($attset as $group => $data){
			$Data[implode(',', array_keys($data['variant']))] = Array(
				'setid' => $group,
				'stock' => $data['stock'],
				'sellprice' => $this->registry->core->processPrice($data['sellprice']),
				'sellpricenetto' => $this->registry->core->processPrice($data['sellpricenetto'])
			);
		}
		
		$event2 = new sfEvent($this, 'frontend.productbox.assign');
		$this->registry->dispatcher->filter($event2, (int) $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data2){
			foreach ($Data2 as $tab => $values){
				$product[$tab] = $values;
			}
		}
		$this->registry->template->assign('product', $product);
		$this->registry->template->assign('attributes', $selectAttributes);
		$this->registry->template->assign('variants', json_encode($Data));
		$this->registry->template->assign('attset', $attset);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

}
?>