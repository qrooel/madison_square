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
 * $Id: productbox.php 692 2012-09-06 21:10:30Z gekosale $
 */
class ProductBoxController extends BoxController {

	public function __construct ($registry) {
		parent::__construct($registry);
		$params = htmlspecialchars_decode($this->registry->router->getParams());
		if (! is_numeric($params)){
			$this->productid = App::getModel('product')->getProductIdBySeo($params);
		}
		else{
			$this->productid = (int) $this->registry->core->getParam();
		}
		$this->productModel = App::getModel('product/product');
	}

	public function index () {
		$clientData = App::getModel('client')->getClient();
		
		$this->registry->xajax->registerFunction(array(
			'addProductToCart',
			App::getModel('cart/cart'),
			'addAJAXProductToCart'
		));
		$this->registry->xajax->registerFunction(array(
			'addOpinion',
			$this->productModel,
			'addAJAXOpinionAboutProduct'
		));
		$this->registry->xajax->registerFunction(array(
			'addProductRangeOpinion',
			$this->productModel,
			'addAJAXProductRangeOpinion'
		));
		$this->registry->xajax->registerFunction(array(
			'addProductTags',
			$this->productModel,
			'addAJAXTagsForProduct'
		));
		$this->registry->xajax->registerFunction(array(
			'addProductToWishList',
			$this->productModel,
			'addAJAXProductToWishList'
		));
		$this->registry->xajax->registerFunction(array(
			'refreshTags',
			$this,
			'ajax_refreshTags'
		));
		
		if (isset($this->_boxAttributes['tabbed'])){
			$tabbed = $this->_boxAttributes['tabbed'];
		}
		else{
			$tabbed = 1;
		}
		
		$product = $this->productModel->getProductAndAttributesById((int) $this->productid);
		$this->heading = $product['productname'];
		
		if (isset($product['idproduct'])){
			$range = $this->productModel->getRangeType((int) $this->productid);
			$this->productModel->getPhotos($product);
			$this->productModel->getOtherPhotos($product);
			
			$selectAttributes = $this->productModel->getProductAttributeGroups($product);
			
			foreach ($selectAttributes as $key => $val){
				natsort($val['attributes']);
				$selectAttributes[$key]['attributes'] = $val['attributes'];
			}
			
			$attset = $this->productModel->getProductVariant($product);
			$Data = Array();
			foreach ($attset as $group => $data){
				$Data[implode(',', array_keys($data['variant']))] = Array(
					'setid' => $group,
					'stock' => $data['stock'],
					'photonormal' => $data['photonormal'],
					'photoorginal' => $data['photoorginal'],
					'sellprice' => $this->registry->core->processPrice($data['sellprice']),
					'sellpricenetto' => $this->registry->core->processPrice($data['sellpricenetto']),
					'sellpriceold' => $this->registry->core->processPrice($data['attributepricegrossbeforepromotion']),
					'sellpricenettoold' => $this->registry->core->processPrice($data['attributepricenettobeforepromotion'])
				);
			}
			$productreview = App::getModel('productreview')->getProductReviews((int) $this->productid);
			$technicalData = App::getModel('product')->GetTechnicalDataForProduct((int) $this->productid);
			
			if ($product['discountprice'] !== NULL){
				$priceForDelivery = $product['discountprice'];
			}
			else{
				$priceForDelivery = $product['price'];
			}
			
			$delivery = App::getModel('delivery')->getDispatchmethodPriceForProduct($priceForDelivery, $product['weight']);
			if (isset($product['shippingcost']) && $product['shippingcost'] != NULL){
				foreach ($delivery as $key => $val){
					if (isset($val['vatvalue'])){
						$delivery[$key]['dispatchmethodcost'] = $product['shippingcost'] * (1 + ($val['vatvalue'] / 100));
					}
					else{
						$delivery[$key]['dispatchmethodcost'] = $product['shippingcost'];
					}
				}
			}
			
			$deliverymin = PHP_INT_MAX;
			foreach ($delivery as $i){
				$deliverymin = min($deliverymin, $i['dispatchmethodcost']);
			}
			
			$files = App::getModel('product')->getFilesByProductId((int) $this->productid);
			
			$tabs = $this->registry->template->assign('tabbed', $tabbed);
			
			$Product = Array(
				'tags' => $this->getTagsTemplate(),
				'range' => $range,
				'files' => $files,
				'variants' => json_encode($Data),
				'product' => $product,
				'attributes' => $selectAttributes,
				'technicalData' => $technicalData,
				'attset' => $attset,
				'productreview' => $productreview,
				'delivery' => $delivery,
				'deliverymin' => $deliverymin,
				'eraty' => App::getModel('product')->checkEraty()
			);
			$event2 = new sfEvent($this, 'frontend.productbox.assign');
			$this->registry->dispatcher->filter($event2, (int) $this->productid);
			$arguments = $event2->getReturnValues();
			foreach ($arguments as $key => $Data){
				foreach ($Data as $tab => $values){
					$Product[$tab] = $values;
				}
			}
			$this->registry->template->assign($Product);
			$this->registry->template->display($this->loadTemplate('index.tpl'));
			$this->productModel->updateViewedCount((int) $this->productid);
		}
		else{
			App::redirectSeo(App::getURLAdress());
		}
	}

	protected function getTagsTemplate () {
		if (isset($this->_boxAttributes['tabbed'])){
			$tabbed = $this->_boxAttributes['tabbed'];
		}
		else{
			$tabbed = 1;
		}
		$this->registry->template->saveState();
		$tagsProduct = $this->productModel->getProductTagsById((int) $this->productid);
		$this->registry->template->assign('tagsProduct', $tagsProduct);
		$this->registry->template->assign('tabbed', $tabbed);
		$result = $this->registry->template->fetch($this->loadTemplate('tags.tpl'));
		$this->registry->template->reloadState();
		return $result;
	}

	public function ajax_refreshTags () {
		$objResponse = new xajaxResponse();
		$objResponse->clear("tags-cloud", "innerHTML");
		$objResponse->append("tags-cloud", "innerHTML", $this->getTagsTemplate());
		return $objResponse;
	}

	public function getBoxHeading () {
		return $this->heading;
	}
}