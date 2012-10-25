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
 * $Revision: 693 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:12:12 +0200 (Cz, 06 wrz 2012) $
 * $Id: cart.php 693 2012-09-06 21:12:12Z gekosale $
 */
class cartModel extends Model {
	protected $Cart;
	protected $globalPrice;
	protected $globalWeight;
	protected $globalPriceWithoutVat;
	protected $globalPriceWithDispatchmethod;
	protected $globalPriceWithDispatchmethodNetto;
	protected $count;

	public function __construct ($registry) {
		parent::__construct($registry);
		if (($this->Cart = $this->registry->session->getActiveCart()) === NULL){
			$this->Cart = Array();
		}
		if (($this->globalPrice = $this->registry->session->getActiveGlobalPrice()) === NULL){
			$this->globalPrice = 0.00;
		}
		if (($this->globalWeight = $this->registry->session->getActiveGlobalWeight()) === NULL){
			$this->globalWeight = 0.00;
		}
		if (($this->globalPriceWithoutVat = $this->registry->session->getActiveGlobalPriceWithoutVat()) === NULL){
			$this->globalPriceWithoutVat = 0.00;
		}
		if (($this->globalPriceWithDispatchmethod = $this->registry->session->getActiveGlobalPriceWithDispatchmethod()) === NULL){
			$this->globalPriceWithDispatchmethod = 0.00;
		}
		if (($this->globalPriceWithDispatchmethodNetto = $this->registry->session->getActiveGlobalPriceWithDispatchmethodNetto()) === NULL){
			$this->globalPriceWithDispatchmethodNetto = 0.00;
		}
		if ($this->count = $this->registry->session->getActiveCount() === NULL){
			$this->count = 0;
		}
	}

	/**
	 * Xajax method.
	 * Adds chosen product to cart.
	 *
	 * @param
	 *        	int @idproduct- identity of product
	 * @param int $attr-
	 *        	identity of product's attribute
	 * @param int $qty-
	 *        	quantity of product
	 * @param $stockproce- two
	 *        	values ($stock and price) split by comma
	 * @return object xajaxResponse
	 */
	public function addAJAXProductToCart ($idproduct, $attr = NULL, $qty, $stockprice, $trackstock = 1) {
		$qty = (int) $qty;
		if ($attr == 0)
			$attr = NULL;
		$objResponse = new xajaxResponse();
		try{
			$stockpricetab = explode(",", $stockprice);
			$stock = $stockpricetab[0];
			// added product was chosen without attributes.
			// old version- show alert- select a variant of this product.
			// new version- add to cart standard product (product with
			// attributes), if its stock > 0
			if (! is_numeric($stock) || $stock === NULL){
				$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_CHOSE_PRODUCT_VARIANT') . '")');
			}
			elseif (! is_numeric($qty)){
				$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_CART_QUANTITY_IS_NOT_NUMERIC') . '")');
				$objResponse->assign('product-qty', 'value', 1);
			}
			// added product has a zero storage state (stock)
			elseif ($stock == 0 && $trackstock == 1){
				$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_SHORTAGE_OF_STOCK') . '")');
			}
			elseif ($qty <= 0){
				$objResponse->assign('product-qty', 'value', 1);
				$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_NONZERO_QTY') . '")');
				// storage state(stock) is less than quantity
			}
			else 
				if (($stock < $qty) && $trackstock == 1){
					$objResponse->assign('product-qty', 'value', $stock);
					$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_STOCK_LESS_THAN_QTY') . '")');
				}
				else{
					// add to cart product with attributes
					if ($attr != null){
						// chosen product with attributes is on the cart-
						// increase quantity
						if (isset($this->Cart[$idproduct]['attributes'][$attr])){
							$oldqty = $this->Cart[$idproduct]['attributes'][$attr]['qty'];
							$newqty = $this->Cart[$idproduct]['attributes'][$attr]['qty'] + $qty;
							// there is max product's quantity on the cart
							// TRACK STOCK
							if (($oldqty == $stock) && $trackstock == 1){
								$objResponse->assign('product-qty', 'value', 1);
								$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . ' (' . $stock . ' ' . $this->registry->core->getMessage('TXT_QTY') . ')' . '")');
							} // quantity could exceeds storage state- return max stock
							else 
								if (($newqty > $stock) && $trackstock == 1){
									$this->Cart[$idproduct]['attributes'][$attr]['qty'] = $stock;
									$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->$this->Cart[$idproduct]['attributes'][$attr]['newprice'] * ($this->Cart[$idproduct]['attributes'][$attr]['qty']);
									$this->updateSession();
									$objResponse->script("GCartRefresh();");
								}
								else{
									// quantity fit into storage state- icrease
									// quantity on cart
									$this->Cart[$idproduct]['attributes'][$attr]['qty'] = $newqty;
									$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->Cart[$idproduct]['attributes'][$attr]['newprice'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
									$this->updateSession();
									$objResponse->script("GCartRefresh();");
								}
						}
						elseif (isset($this->Cart[$idproduct])){
							// chosen product is on cart, but has a different
							// attributes or is standard
							// adding to cart product with new attribute and
							// set-up variant for it
							$this->cartAddProductWithAttr($idproduct, $qty, $attr);
							$this->getProductFeatures($idproduct, $attr);
							$objResponse->script("GCartRefresh();");
						}
						else{
							// adding to cart new product with attributes
							$this->cartAddProductWithAttr($idproduct, $qty, $attr);
							$this->getProductFeatures($idproduct, $attr);
							$objResponse->script("GCartRefresh();");
						}
						// product without attributes
					}
					else{
						// there is product (standard or simple) on cart-
						// increase quantity
						if (isset($this->Cart[$idproduct]) && isset($this->Cart[$idproduct]['standard'])){
							$oldqty = $this->Cart[$idproduct]['qty'];
							$newqty = $this->Cart[$idproduct]['qty'] + $qty;
							// there is max quantity on the cart- show alert
							if (($oldqty >= $stock) && $trackstock == 1){
								$objResponse->assign('product-qty', 'value', 1);
								$objResponse->script('GError("' . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . ' (' . $stock . ' ' . $this->registry->core->getMessage('TXT_QTY') . ')' . '")');
							}
							// check quantity
							else 
								if (($newqty >= $stock) && $trackstock == 1){
									// quantity exceeds storage state- return
									// storage state- show alert
									$this->Cart[$idproduct]['qty'] = $stock;
									$this->Cart[$idproduct]['qtyprice'] = $this->Cart[$idproduct]['newprice'] * $this->Cart[$idproduct]['qty'];
									$this->updateSession();
									$objResponse->script("GCartRefresh();");
								}
								else{
									// quantity ok- increase it on cart
									$this->Cart[$idproduct]['qty'] = $newqty;
									$this->Cart[$idproduct]['qtyprice'] = $this->Cart[$idproduct]['newprice'] * $this->Cart[$idproduct]['qty'];
									$this->updateSession();
									$objResponse->script("GCartRefresh();");
								}
						}
						else{
							// add standard product (of product with attributes)
							// or product without attributes (simple)
							$this->cartAddStandardProduct($idproduct, $qty);
							$objResponse->script("GCartRefresh();");
						}
					}
				}
		}
		catch (FrontendException $e){
			$objResponse->alert($e->getMessage());
		}
		return $objResponse;
	}

	/**
	 * Adding to cart all products from missing cart.
	 *
	 * @param array $Data-
	 *        	data with identity of products ids
	 * @access public
	 */
	public function addProductsToCartFromMissingCart ($Data) {
		foreach ($Data as $idproduct => $values){
			$product = App::getModel('product')->getProductAndAttributesById($idproduct);
			if (isset($values['standard']) && $values['standard'] == 1){
				$qty = $this->checkProductQuantity($product['trackstock'], $values['qty'], $product['stock']);
				if ($qty > 0){
					$this->cartAddStandardProduct($idproduct, $qty);
				}
			}
			else{
				if (isset($values['attributes'])){
					foreach ($values['attributes'] as $attr => $variant){
						if (isset($product['attributes'])){
							foreach ($product['attributes'] as $k => $v){
								if ($v['idproductattributeset'] == $attr){
									$qty = $this->checkProductQuantity($product['trackstock'], $variant['qty'], $v['stock']);
									if ($qty > 0){
										$this->cartAddProductWithAttr($idproduct, $qty, $attr);
										$this->getProductFeatures($idproduct, $attr);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	public function checkProductQuantity ($trackStock, $qty, $stock) {
		if ($trackStock == 0){
			return $qty;
		}
		else{
			if ($qty > $stock){
				return $stock;
			}
			else{
				return $qty;
			}
		}
		return 0;
	}

	public function deleteAJAXProductFromCart ($idproduct, $attr = NULL) {
		$objResponseDel = new xajaxResponse();
		try{
			// product without attributes- simple product
			if (! isset($this->Cart[$idproduct]['attributes']) && $attr == NULL){
				$this->deleteProductCart($idproduct);
				// product with attributes and standard product
			}
			elseif ($this->Cart[$idproduct]['attributes'] != NULL && $attr != NULL){
				// if standard product
				if (isset($this->Cart[$idproduct]['standard'])){
					// then delete chosen attribute only and leave standard
					// product
					$this->deleteProductAttributeCart($idproduct, $attr);
				}
				else{
					// first- delete attributes of this product
					$this->deleteProductAttributeCart($idproduct, $attr);
					if ($this->Cart[$idproduct]['attributes'] == NULL){
						// if there isnt other prodcut attributes or isnt set-up
						// standard product
						// delete product from cart
						$this->deleteProductAtributesCart($idproduct);
					}
				}
				// if there are product attributes on cart
			}
			elseif ($this->Cart[$idproduct]['attributes'] != NULL && $attr == NULL){
				if (isset($this->Cart[$idproduct])){
					// then delete only product standard
					$this->deleteProductAttributeCart($idproduct, NULL);
				}
				// if there arent attributes of product on cart
			}
			elseif ($this->Cart[$idproduct]['attributes'] == NULL && $attr == NULL){
				// then delete only product standard
				unset($this->Cart[$idproduct]);
			}
			else{
				throw new Exception('No such product (id=' . $idproduct . ') on cart');
			}
		}
		catch (Exception $e){
			$objResponseDel->alert($e->getMessage());
		}
		$this->updateSession();
		$objResponseDel->script('window.location.reload( false )');
		return $objResponseDel;
	}

	/**
	 * Xajax method which decreasing quantity of chosen product on the cart
	 *
	 * @param int $idproduct-
	 *        	identity of product
	 * @param int $attr-
	 *        	identity of product's attribute (NULL by default)
	 * @return object xajaxResponse
	 * @access public
	 */
	public function decreaseAJAXQuantityProduct ($idproduct, $attr = NULL) {
		$objResponseDec = new xajaxResponse();
		$step = 1;
		try{
			if (isset($this->Cart[$idproduct])){
				// standard product (of product with attributes)
				if (isset($this->Cart[$idproduct]['standard']) && $attr == NULL){
					// decrease quantity
					if ($this->Cart[$idproduct]['qty'] > 1){
						$newQty = $this->Cart[$idproduct]['qty'];
						$this->Cart[$idproduct]['qty'] = $newQty - $step;
						$this->Cart[$idproduct]['qtyprice'] = $this->Cart[$idproduct]['newprice'] * $this->Cart[$idproduct]['qty'];
						$this->Cart[$idproduct]['weighttotal'] = $this->Cart[$idproduct]['weight'] * $this->Cart[$idproduct]['qty'];
						$this->updateSession();
						$objResponseDec->script('window.location.reload( false )');
					}
					else{
						// if quantity is equal 1, than delete product from cart
						$this->deleteAJAXProductFromCart($idproduct, $attr);
						$objResponseDec->script('window.location.reload( false )');
						return $objResponseDec;
					}
				}
				// product with attributes
				if (isset($this->Cart[$idproduct]['attributes']) && $this->Cart[$idproduct]['attributes'] != NULL && $attr != NULL){
					// decrease quantity
					if ($this->Cart[$idproduct]['attributes'][$attr]['qty'] > 1){
						$this->Cart[$idproduct]['attributes'][$attr]['qty'] = $this->Cart[$idproduct]['attributes'][$attr]['qty'] - $step;
						$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->Cart[$idproduct]['attributes'][$attr]['newprice'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
						$this->Cart[$idproduct]['attributes'][$attr]['weighttotal'] = $this->Cart[$idproduct]['attributes'][$attr]['weight'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
						$this->updateSession();
						$objResponseDec->script('window.location.reload( false )');
					}
					else{
						// if quantity is equal 1, than delete product from cart
						$this->deleteAJAXProductFromCart($idproduct, $attr);
						$objResponseDec->script('window.location.reload( false )');
						return $objResponseDec;
					}
				}
			}
		}
		catch (Exception $e){
			$objResponseDec->alert($e->getMessage());
		}
		return $objResponseDec;
	}

	/**
	 * Increase quantity
	 *
	 * @param int $idproduct-
	 *        	identity of product
	 * @param int $attr-
	 *        	identity of product's attribute (NULL by default)
	 * @return object xajaxResponse
	 * @access public
	 */
	public function increaseAJAXQuantityProduct ($idproduct, $attr = NULL) {
		$objResponseInc = new xajaxResponse();
		try{
			$step = 1;
			if (isset($this->Cart[$idproduct])){
				// standard product (of product with attributes)
				if (isset($this->Cart[$idproduct]['standard']) && $this->Cart[$idproduct]['standard'] == 1 && $attr == NULL){
					$newqty = $this->Cart[$idproduct]['qty'] += $step;
					if (($newqty > $this->Cart[$idproduct]['stock']) && $this->Cart[$idproduct]['trackstock'] == 1){
						$objResponseInc->script('GError("' . $this->registry->core->getMessage('ERR_COULDNT_INCREASE_QTY') . '\n' . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . '")');
					}
					else{
						$this->Cart[$idproduct]['qty'] = $newqty;
						$this->Cart[$idproduct]['qtyprice'] = $this->Cart[$idproduct]['newprice'] * $this->Cart[$idproduct]['qty'];
						$this->Cart[$idproduct]['weighttotal'] = $this->Cart[$idproduct]['weight'] * $this->Cart[$idproduct]['qty'];
						$this->updateSession();
						$objResponseInc->script('window.location.reload( false )');
					}
				}
				// product with attributes
				if ($this->Cart[$idproduct]['attributes'] != NULL && $attr != NULL){
					$this->Cart[$idproduct]['attributes'][$attr]['qty'] += $step;
					$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->Cart[$idproduct]['attributes'][$attr]['newprice'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
					$this->Cart[$idproduct]['attributes'][$attr]['weighttotal'] = $this->Cart[$idproduct]['attributes'][$attr]['weight'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
					if (($this->Cart[$idproduct]['attributes'][$attr]['qty'] > $this->Cart[$idproduct]['attributes'][$attr]['stock']) && $this->Cart[$idproduct]['attributes'][$attr]['trackstock'] == 1){
						$objResponseInc->script('GError("' . $this->registry->core->getMessage('ERR_COULDNT_INCREASE_QTY') . '\n' . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . '")');
					}
					else{
						$this->updateSession();
						$objResponseInc->script('window.location.reload( false )');
					}
				}
			}
		}
		catch (Exception $e){
			$objResponseInc->alert($e->getMessage());
		}
		return $objResponseInc;
	}

	public function changeQuantity ($idproduct, $attr = NULL, $newqty) {
		$objResponseInc = new xajaxResponse();
		$newqty = ceil($newqty);
		if ($newqty == 0){
			$this->deleteAJAXProductFromCart($idproduct, $attr);
			$objResponseInc->script('window.location.reload( false )');
			return $objResponseInc;
		}
		
		try{
			if (isset($this->Cart[$idproduct])){
				// standard product (of product with attributes)
				if (isset($this->Cart[$idproduct]['standard']) && $this->Cart[$idproduct]['standard'] == 1 && $attr == NULL){
					$oldQty = $this->Cart[$idproduct]['stock'];
					if (($newqty > $this->Cart[$idproduct]['stock']) && $this->Cart[$idproduct]['trackstock'] == 1){
						$objResponseInc->script('GError("' . $this->registry->core->getMessage('ERR_COULDNT_INCREASE_QTY') . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . '");');
						$objResponseInc->script('restoreQty();');
					}
					else{
						$this->Cart[$idproduct]['qty'] = $newqty;
						$this->Cart[$idproduct]['qtyprice'] = $this->Cart[$idproduct]['newprice'] * $this->Cart[$idproduct]['qty'];
						$this->Cart[$idproduct]['weighttotal'] = $this->Cart[$idproduct]['weight'] * $this->Cart[$idproduct]['qty'];
						$this->updateSession();
						$objResponseInc->script('window.location.reload( false )');
					}
				}
				// product with attributes
				if ($this->Cart[$idproduct]['attributes'] != NULL && $attr != NULL){
					$oldQty = $this->Cart[$idproduct]['attributes'][$attr]['qty'];
					$this->Cart[$idproduct]['attributes'][$attr]['qty'] = $newqty;
					$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->Cart[$idproduct]['attributes'][$attr]['newprice'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
					$this->Cart[$idproduct]['attributes'][$attr]['weighttotal'] = $this->Cart[$idproduct]['attributes'][$attr]['weight'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
					if ($this->Cart[$idproduct]['attributes'][$attr]['trackstock'] == 0){
						$this->updateSession();
						$objResponseInc->script('window.location.reload( false )');
					}
					else{
						if ($this->Cart[$idproduct]['attributes'][$attr]['qty'] <= $this->Cart[$idproduct]['attributes'][$attr]['stock']){
							$this->updateSession();
							$objResponseInc->script('window.location.reload( false )');
						}
						else{
							$this->Cart[$idproduct]['attributes'][$attr]['qty'] = $oldQty;
							$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $this->Cart[$idproduct]['attributes'][$attr]['newprice'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
							$this->Cart[$idproduct]['attributes'][$attr]['weighttotal'] = $this->Cart[$idproduct]['attributes'][$attr]['weight'] * $this->Cart[$idproduct]['attributes'][$attr]['qty'];
							$objResponseInc->script('GError("' . $this->registry->core->getMessage('ERR_COULDNT_INCREASE_QTY') . '<br />' . $this->registry->core->getMessage('ERR_MAX_STORAGE_STATE_ON_CART') . '");');
						}
					}
				}
			}
		}
		catch (Exception $e){
			$objResponseInc->alert($e->getMessage());
		}
		return $objResponseInc;
	}

	public function deleteProductCart ($idproduct) {
		try{
			if (isset($this->Cart[$idproduct])){
				unset($this->Cart[$idproduct]);
			}
		}
		catch (Exception $e){
			throw new Exception('No such product on cart');
		}
	}

	/**
	 * Deleting product with attribute (and standard product) from cart
	 *
	 * @param int $idproduct-
	 *        	identity of produktu
	 * @param int $attr-
	 *        	identity of attribute; NULL by default
	 */
	public function deleteProductAttributeCart ($idproduct, $attr = NULL) {
		try{
			if (isset($this->Cart[$idproduct]['attributes']) && $this->Cart[$idproduct]['attributes'] != NULL && $attr == NULL){
				unset($this->Cart[$idproduct]['standard']);
				unset($this->Cart[$idproduct]['qty']);
				unset($this->Cart[$idproduct]['qtyprice']);
				unset($this->Cart[$idproduct]['weight']);
				unset($this->Cart[$idproduct]['weighttotal']);
				unset($this->Cart[$idproduct]['newprice']);
				unset($this->Cart[$idproduct]['vat']);
				unset($this->Cart[$idproduct]['pricewithoutvat']);
				unset($this->Cart[$idproduct]['mainphotoid']);
				unset($this->Cart[$idproduct]['shortdescription']);
				unset($this->Cart[$idproduct]['name']);
				unset($this->Cart[$idproduct]['stock']);
			}
			elseif ($this->Cart[$idproduct]['attributes'] == NULL && $attr == NULL){
				$this->deleteProductCart($idproduct);
			}
			else{
				if (isset($this->Cart[$idproduct]['attributes'][$attr]) && $attr != NULL){
					unset($this->Cart[$idproduct]['attributes'][$attr]);
				}
			}
		}
		catch (Exception $e){
			throw new Exception('There is not product with attributes on cart.');
		}
	}

	/**
	 * Deleting product without attributes from cart.
	 *
	 * @param $idproduct- identity
	 *        	of product
	 * @access public
	 */
	public function deleteProductAtributesCart ($idproduct) {
		try{
			if ($this->Cart[$idproduct]['attributes'] == NULL && ! isset($this->Cart[$idproduct]['standard'])){
				unset($this->Cart[$idproduct]);
			}
		}
		catch (Exception $e){
			throw new Exception('There are not attributes for this' . $idproduct . ' product');
		}
	}

	/**
	 * Adding to cart a standard product (treat product as product with
	 * attributes)
	 *
	 * @param int $idproduct-
	 *        	identity of product
	 * @param int $attr-
	 *        	identity of attribute; NULL by default
	 * @param int $qty-
	 *        	quantity of product
	 */
	public function cartAddStandardProduct ($idproduct, $qty) {
		$product = App::getModel('product')->getProductById($idproduct);
		
		if (is_null($product['discountpricenetto'])){
			$price = $product['price'];
			$priceWithoutVat = $product['pricewithoutvat'];
		}
		else{
			$price = $product['discountprice'];
			$priceWithoutVat = $product['discountpricenetto'];
		}
		
		$qtyprice = $qty * $price;
		$weighttotal = $qty * $product['weight'];
		
		$this->Cart[$idproduct] = Array(
			'ean' => $product['ean'],
			'seo' => $product['seo'],
			'idproduct' => $idproduct,
			'name' => $product['productname'],
			'mainphotoid' => $product['mainphotoid'],
			'shortdescription' => $product['shortdescription'],
			'stock' => $product['stock'],
			'trackstock' => $product['trackstock'],
			'newprice' => $price,
			'pricewithoutvat' => $priceWithoutVat,
			'pricenettobeforetier' => $priceWithoutVat,
			'pricegrossbeforetier' => $price,
			'qty' => $qty,
			'qtyprice' => $qtyprice,
			'shippingcost' => $product['shippingcost'],
			'weight' => $product['weight'],
			'weighttotal' => $weighttotal,
			'vat' => $product['vatvalue'],
			'standard' => 1,
			'attributes' => isset($this->Cart[$idproduct]['attributes']) ? $this->Cart[$idproduct]['attributes'] : null,
			'tierpricing' => $product['tierpricing']
		);
		
		$this->updateSession();
	}

	/**
	 * Adding to cart a new product with attribute.
	 * Update session for $this->Cart
	 *
	 * @param int $idproduct-
	 *        	identity of product
	 * @param
	 *        	int qty- quantity of product with attribute
	 * @param int $attr-
	 *        	identity of product's attribute
	 * @access public
	 */
	public function cartAddProductWithAttr ($idproduct, $qty, $attr) {
		$product = App::getModel('product')->getProductAndAttributesById($idproduct);
		foreach ($product['attributes'] as $key => $variant){
			if ($variant['idproductattributeset'] == $attr){
				$priceWithoutVat = $variant['attributeprice'];
				$price = $variant['price'];
				$weight = $variant['weight'];
				$stock = $variant['stock'];
				break;
			}
		}
		
		if (! (isset($this->Cart[$idproduct]))){
			$this->Cart[$idproduct] = Array(
				'idproduct' => $product['idproduct']
			);
		}
		$qtyprice = $price * $qty;
		$weighttotal = $weight * $qty;
		
		$this->Cart[$idproduct]['attributes'][$attr] = Array(
			'attr' => $attr,
			'idproduct' => $product['idproduct'],
			'seo' => $product['seo'],
			'name' => $product['productname'],
			'mainphotoid' => $product['mainphotoid'],
			'shortdescription' => $product['shortdescription'],
			'stock' => $stock,
			'trackstock' => $product['trackstock'],
			'newprice' => $price,
			'qty' => $qty,
			'qtyprice' => $qtyprice,
			'shippingcost' => $product['shippingcost'],
			'weight' => $weight,
			'weighttotal' => $weighttotal,
			'vat' => $product['vatvalue'],
			'pricewithoutvat' => $priceWithoutVat,
			'pricenettobeforetier' => $priceWithoutVat,
			'pricegrossbeforetier' => $price,
			'tierpricing' => $product['tierpricing']
		);
		$this->updateSession();
	}

	/**
	 * Select all attributes for product and update session for $this->Cart
	 *
	 * @param int $idproduct-
	 *        	identity of product
	 * @param int $attr-
	 *        	identity of product's attribute
	 */
	public function getProductFeatures ($idproduct, $attr) {
		$query = "SELECT
					PAVS.idproductattributevalueset as idfeature, 
					PAVS.attributeproductvalueid as feature, 
					AP.name AS groupname,
					APV.name AS attributename
			      FROM productattributeset AS PAS
			        LEFT JOIN productattributevalueset AS PAVS ON PAS.idproductattributeset= PAVS.productattributesetid
					LEFT JOIN attributeproductvalue AS APV ON PAVS.attributeproductvalueid= APV.idattributeproductvalue
			        LEFT JOIN attributeproduct AS AP ON APV.attributeproductid= AP.idattributeproduct
			      WHERE PAS.productid= :idproduct 
			      	AND PAVS.productattributesetid= :attr";
		$stm = $this->registry->db->prepareStatement($query);
		$stm->setInt('idproduct', $idproduct);
		$stm->setInt('attr', $attr);
		try{
			$rs = $stm->executeQuery();
			while ($rs->next()){
				$idfeature = $rs->getInt('idfeature');
				
				$this->Cart[$idproduct]['attributes'][$attr]['features'][$idfeature] = Array(
					'feature' => $rs->getString('feature'),
					'group' => $rs->getString('groupname'),
					'attributename' => $rs->getString('attributename')
				);
			}
			$this->updateSession();
		}
		catch (Exception $e){
			throw new Exception('Error while doing sql query- product features (cartModel).');
		}
	}

	/**
	 * set-up global price cart and update session for $this->Cart
	 */
	public function setGlobalPrice () {
		$price = 0.00;
		$priceWithoutVat = 0.00;
		foreach ($this->Cart as $key => $product){
			if ((! isset($product['attributes']) || $product['attributes'] == NULL)){
				$price += $product['newprice'] * $product['qty'];
			}
			else{
				if (isset($product['standard'])){
					$price += $product['newprice'] * $product['qty'];
					foreach ($product['attributes'] as $attrtab){
						$price += $attrtab['newprice'] * $attrtab['qty'];
					}
				}
				else{
					foreach ($product['attributes'] as $attrtab){
						$price += $attrtab['newprice'] * $attrtab['qty'];
					}
				}
			}
		}
		$this->globalPrice = $price;
	}

	public function setGlobalWeight () {
		$weight = 0.00;
		foreach ($this->Cart as $product){
			if ((! isset($product['attributes']) || $product['attributes'] == NULL)){
				$weight += $product['weight'] * $product['qty'];
			}
			else{
				if (isset($product['standard'])){
					$weight += $product['weight'] * $product['qty'];
					foreach ($product['attributes'] as $attrtab){
						$weight += $attrtab['weight'] * $attrtab['qty'];
					}
				}
				else{
					foreach ($product['attributes'] as $attrtab){
						$weight += $attrtab['weight'] * $attrtab['qty'];
					}
				}
			}
		}
		$this->globalWeight = $weight;
	}

	public function setCartForDelivery () {
		$weight = 0.00;
		$price = 0.00;
		$priceWithoutVat = 0.00;
		$shippingCost = 0.00;
		foreach ($this->Cart as $product){
			if ((! isset($product['attributes']) || $product['attributes'] == NULL)){
				if (isset($product['shippingcost']) && $product['shippingcost'] != NULL){
					$shippingCost += $product['shippingcost'] * $product['qty'];
				}
				else{
					$weight += $product['weight'] * $product['qty'];
					$price += $product['newprice'] * $product['qty'];
				}
			}
			else{
				if (isset($product['standard'])){
					if (isset($product['shippingcost']) && $product['shippingcost'] != NULL){
						$shippingCost += $product['shippingcost'] * $product['qty'];
					}
					else{
						$weight += $product['weight'] * $product['qty'];
						$price += $product['newprice'] * $product['qty'];
					}
					
					foreach ($product['attributes'] as $attrtab){
						if (isset($attrtab['shippingcost']) && $attrtab['shippingcost'] != NULL){
							$shippingCost += $attrtab['shippingcost'] * $attrtab['qty'];
						}
						else{
							$weight += $attrtab['weight'] * $attrtab['qty'];
							$price += $attrtab['newprice'] * $attrtab['qty'];
						}
					}
				}
				else{
					foreach ($product['attributes'] as $attrtab){
						if (isset($attrtab['shippingcost']) && $attrtab['shippingcost'] != NULL){
							$shippingCost += $attrtab['shippingcost'] * $attrtab['qty'];
						}
						else{
							$weight += $attrtab['weight'] * $attrtab['qty'];
							$price += $attrtab['newprice'] * $attrtab['qty'];
						}
					}
				}
			}
		}
		$Data = Array(
			'shippingcost' => $shippingCost,
			'weight' => $weight,
			'price' => $price
		);
		$this->registry->session->setActiveCartForDelivery($Data);
	}

	/**
	 * set-up global price without vat and update session for $this->Cart
	 */
	public function setGlobalPriceWithoutVat () {
		$priceWithoutVat = 0.00;
		foreach ($this->Cart as $product){
			if (! isset($product['attributes']) || $product['attributes'] == NULL){
				$priceWithoutVat += $product['pricewithoutvat'] * $product['qty'];
			}
			else{
				if (isset($product['standard'])){
					$priceWithoutVat += $product['pricewithoutvat'] * $product['qty'];
					foreach ($product['attributes'] as $attrtab){
						$priceWithoutVat += $attrtab['pricewithoutvat'] * $attrtab['qty'];
					}
				}
				else{
					foreach ($product['attributes'] as $attrtab){
						$priceWithoutVat += $attrtab['pricewithoutvat'] * $attrtab['qty'];
					}
				}
			}
		}
		$this->globalPriceWithoutVat = $priceWithoutVat;
	}

	public function getGlobalPrice () {
		return $this->globalPrice;
	}

	public function getGlobalWeight () {
		return $this->globalWeight;
	}

	public function getGlobalPriceWithoutVat () {
		return $this->globalPriceWithoutVat;
	}

	public function getShortCartList () {
		return $this->Cart;
	}

	public function getCount () {
		return $this->count;
	}

	/**
	 * Updating session for $this->Cart
	 */
	public function updateSession () {
		$this->setTierPricing();
		$this->setGlobalPrice();
		$this->setGlobalPriceWithoutVat();
		$this->setGlobalWeight();
		$this->setCartForDelivery();
		
		$this->registry->session->setActiveCart($this->Cart);
		$this->registry->session->setActiveGlobalPrice($this->globalPrice);
		$this->registry->session->setActiveGlobalWeight($this->globalWeight);
		$this->registry->session->setActiveGlobalPriceWithoutVat($this->globalPriceWithoutVat);
		$this->registry->session->setActiveDispatchmethodChecked(0);
		$this->registry->session->setActiveglobalPriceWithDispatchmethod($this->globalPrice);
		$this->registry->session->setActiveglobalPriceWithDispatchmethodNetto($this->globalPriceWithoutVat);
		$this->registry->session->setActivePaymentMethodChecked(0);
		$this->registry->session->unsetActiveClientOrder();
	}

	protected function getTierDiscount ($qty, $tiers) {
		if (count($tiers) > 0){
			foreach ($tiers as $tier){
				if ($tier['min'] == 0 && $qty <= $tier['max']){
					return 1 - ($tier['discount'] / 100);
				}
				
				if ($tier['max'] == 0 && $qty >= $tier['min']){
					return 1 - ($tier['discount'] / 100);
				}
				
				if ($tier['min'] > 0 && $tier['max'] > 0){
					if ($qty >= $tier['min'] && $qty <= $tier['max']){
						return 1 - ($tier['discount'] / 100);
					}
				}
			}
		}
		return 1;
	}

	public function setTierPricing () {
		foreach ($this->Cart as $idproduct => $product){
			if (! isset($product['attributes']) || $product['attributes'] == NULL){
				$modifier = $this->getTierDiscount($product['qty'], $product['tierpricing']);
				$this->Cart[$idproduct]['pricewithoutvat'] = $product['pricenettobeforetier'] * $modifier;
				$this->Cart[$idproduct]['newprice'] = $product['pricegrossbeforetier'] * $modifier;
				$this->Cart[$idproduct]['qtyprice'] = $product['newprice'] * $product['qty'] * $modifier;
			}
			else{
				if (! isset($product['standard'])){
					foreach ($product['attributes'] as $attr => $attrtab){
						$modifier = $this->getTierDiscount($attrtab['qty'], $attrtab['tierpricing']);
						$this->Cart[$idproduct]['attributes'][$attr]['pricewithoutvat'] = $attrtab['pricenettobeforetier'] * $modifier;
						$this->Cart[$idproduct]['attributes'][$attr]['newprice'] = $attrtab['pricegrossbeforetier'] * $modifier;
						$this->Cart[$idproduct]['attributes'][$attr]['qtyprice'] = $attrtab['newprice'] * $attrtab['qty'] * $modifier;
					}
				}
			}
		}
	}

	/**
	 * cart product's counter
	 *
	 * @return $count
	 * @access public
	 */
	public function getProductAllCount () {
		$count = 0;
		foreach ($this->Cart as $product){
			if (isset($product['standard']) && $product['standard'] > 0){
				$count += $product['qty'];
				if (isset($product['attributes']) && $product['attributes'] != NULL){
					foreach ($product['attributes'] as $attrtab){
						$count += $attrtab['qty'];
					}
				}
			}
			else{
				if (isset($product['attributes']) && $product['attributes'] != NULL){
					foreach ($product['attributes'] as $attrtab){
						$count += $attrtab['qty'];
					}
				}
			}
		}
		return $count;
	}

	public function getProductIds () {
		$Data = Array(
			0
		);
		foreach ($this->Cart as $product){
			if (isset($product['standard']) && $product['standard'] > 0){
				$Data[] = $product['idproduct'];
				if (isset($product['attributes']) && $product['attributes'] != NULL){
					foreach ($product['attributes'] as $attrtab){
						$Data[] = $attrtab['idproduct'];
					}
				}
			}
			else{
				if (isset($product['attributes']) && $product['attributes'] != NULL){
					foreach ($product['attributes'] as $attrtab){
						$Data[] = $attrtab['idproduct'];
					}
				}
			}
		}
		return $Data;
	}

	/**
	 * Get products' photos
	 *
	 * @param
	 *        	handler to array productCart
	 * @return array productCart with information about photos for products on
	 *         cart
	 * @access public
	 */
	public function getProductCartPhotos (&$productCart) {
		if (! is_array($productCart)){
			throw new FrontendException('Wrong array given.');
		}
		foreach ($productCart as $index => $key){
			if ((isset($key['mainphotoid']) && $key['mainphotoid'] > 0)){
				$productCart[$index]['smallphoto'] = App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($key['mainphotoid']), App::getURLAdress());
			}
			if (isset($key['attributes']) && $key['attributes'] != NULL){
				foreach ($key['attributes'] as $attrindex => $attrkey){
					if ($attrkey['mainphotoid'] > 0){
						$productCart[$index]['attributes'][$attrindex]['smallphoto'] = App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($attrkey['mainphotoid']), App::getURLAdress());
					}
				}
			}
		}
		return $productCart;
	}

	public function checkRulesCart () {
		$Data = Array();
		$condition = Array();
		if ($this->globalPriceWithoutVat > 0){
			$clientGroupId = $this->registry->session->getActiveClientGroupid();
			if ($clientGroupId > 0){
				$sql = "SELECT 
							RCCG.rulescartid, 
							RCR.ruleid, 
							RCR.pkid, 
							RCR.pricefrom, 
							RCR.priceto,
							RCCG.suffixtypeid, 
							RCCG.discount, 
							S.symbol,
							RCCG.clientgroupid
						FROM rulescartclientgroup RCCG
							LEFT JOIN rulescart RC ON RCCG.rulescartid = RC.idrulescart
							LEFT JOIN rulescartrule RCR ON RCR.rulescartid = RC.idrulescart
							LEFT JOIN rulescartview RCV ON RCV.rulescartid = RC.idrulescart
							LEFT JOIN suffixtype S ON RCCG.suffixtypeid = S.idsuffixtype
						WHERE
							RCV.viewid= :viewid
							AND RCCG.clientgroupid= :clientgroupid
							AND IF(RC.datefrom is not null, (cast(RC.datefrom as date) <= curdate()), 1)
							AND IF(RC.dateto is not null, (cast(RC.dateto as date)>= curdate()),1)
						ORDER BY RCR.rulescartid";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('clientgroupid', $clientGroupId);
				$stmt->setInt('viewid', Helper::getViewId());
			}
			else{
				$sql = "SELECT 
							RCR.rulescartid, 
							RCR.ruleid, 
							RCR.pkid, 
							RCR.pricefrom, 
							RCR.priceto,
							RC.suffixtypeid, 
							RC.discount, 
							S.symbol,
							'clientgroupid'=NULL as clientgroupid
						FROM  rulescart RC
							LEFT JOIN rulescartrule RCR ON RCR.rulescartid = RC.idrulescart
							LEFT JOIN rulescartview RCV ON RCV.rulescartid = RC.idrulescart
							LEFT JOIN suffixtype S ON RC.suffixtypeid = S.idsuffixtype
	      				WHERE
	      					RC.discountforall =1
	        				AND RCV.viewid= :viewid
	        				AND IF(RC.datefrom is not null, (cast(RC.datefrom as date) <= curdate()), 1)
							AND IF(RC.dateto is not null, (cast(RC.dateto as date)>= curdate()),1)
						ORDER BY RCR.rulescartid";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('viewid', Helper::getViewId());
			}
			try{
				$rs = $stmt->executeQuery();
				while ($rs->next()){
					$rulescartid = $rs->getInt('rulescartid');
					$ruleid = $rs->getInt('ruleid');
					$currencySymbol = $this->registry->session->getActiveCurrencySymbol();
					if ($rs->getString('symbol') == '%'){
						$Data[$rulescartid]['discount'] = abs($rs->getFloat('discount') - 100) . $rs->getString('symbol');
						$type = ($rs->getFloat('discount') > 100) ? 1 : 0;
					}
					else{
						$Data[$rulescartid]['discount'] = $rs->getString('symbol') . $rs->getFloat('discount');
						$type = ($rs->getString('symbol') == '+') ? 1 : 0;
					}
					
					switch ($ruleid) {
						case 9: // delivery
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $this->getDeliveryToCondition($rs->getInt('pkid'));
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_DELIVERY_TYPE') . ": " . $this->getDeliveryToCondition($rs->getInt('pkid'))
								);
							}
							break;
						case 10: // paymentmethod
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $this->getPaymentToCondition($rs->getInt('pkid'));
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_PAYMENT_TYPE') . ": " . $this->getPaymentToCondition($rs->getInt('pkid'))
								);
							}
							break;
						case 11: // final cart price
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $rs->getFloat('pricefrom');
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_CART_VALUE_AMOUNT_EXCEED') . ": " . $rs->getFloat('pricefrom') . $currencySymbol
								);
							}
							break;
						case 12: // final cart price
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $rs->getFloat('priceto') . $currencySymbol;
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_CART_VALUE_NOT_GREATER_THAN') . ": " . $rs->getFloat('priceto') . $currencySymbol
								);
							}
							break;
						case 13: // final cart price with dispatch method
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $rs->getFloat('pricefrom') . $currencySymbol;
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_CART_DELIVERY_VALUE_AMOUNT') . ": " . $rs->getFloat('pricefrom') . $currencySymbol
								);
							}
							break;
						case 14: // final cart price with dispatch method
							if (isset($Data[$rulescartid][$ruleid])){
								$Data[$rulescartid][$ruleid]['condition'] = $Data[$rulescartid][$ruleid]['condition'] . " " . $this->registry->core->getMessage('TXT_OR') . " " . $rs->getFloat('priceto') . $currencySymbol;
							}
							else{
								$Data[$rulescartid][$ruleid] = Array(
									'is' => 0,
									'ruleid' => $ruleid,
									'condition' => $this->registry->core->getMessage('TXT_CART_DELIVERY_VALUE_NOT_GREATER_THAN') . ": " . $rs->getFloat('priceto') . $this->registry->session->getActiveCurrencySymbol()
								);
							}
							break;
					}
				}
				if (count($Data) > 0){
					foreach ($Data as $rulescart => $rules){
						foreach ($rules as $rule){
							if ($rule['is'] == 0){
								$condition[$rulescart]['conditions'][$rule['ruleid']] = $rule['condition'];
							}
						}
						$condition[$rulescart]['discount'] = $rules['discount'];
						$condition[$rulescart]['type'] = $type;
					}
				}
				else{
					$condition = 0;
				}
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_RULES_CART'));
			}
		}
		else{
			$condition = 0;
		}
		return $condition;
	}

	public function getDeliveryToCondition ($iddispatchmethod) {
		$dispatchmethodname = '';
		$sql = "SELECT 
					DM.name
				FROM dispatchmethod DM
				WHERE DM.iddispatchmethod = :iddispatchmethod";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('iddispatchmethod', $iddispatchmethod);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$dispatchmethodname = $rs->getString('name');
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_DELIVERER_CHECK'));
		}
		return $dispatchmethodname;
	}

	public function getPaymentToCondition ($idpaymentmethod) {
		$paymentname = '';
		$sql = "SELECT 
					PM.name as paymentname
				FROM paymentmethod PM
				WHERE PM.idpaymentmethod = :idpaymentmethod";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpaymentmethod', $idpaymentmethod);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$paymentname = $rs->getString('paymentname');
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_PAYMENT_CHECK'));
		}
		return $paymentname;
	}

	public function setTempCartAfterCurrencyChange () {
		$cart = $this->registry->session->getActiveCart();
		$this->registry->session->setActiveCart(0);
		if (is_array($cart)){
			foreach ($cart as $product){
				$productid = $product['idproduct'];
				if ($productid > 0){
					// product bez cech
					if (isset($product['standard']) && $product['standard'] == 1){
						$this->cartAddStandardProduct($productid, $product['qty']);
					}
					// produkt z cechami
					if (isset($product['attributes']) || ! empty($product['attributes'])){
						foreach ($product['attributes'] as $attributes){
							$attr = $attributes['attr'];
							$this->cartAddProductWithAttr($productid, $attributes['qty'], $attributes['attr']);
						}
					}
				}
			}
		}
	}

	public function getCartPreviewTemplate () {
		$namespace = $this->registry->loader->getCurrentNamespace();
		if (is_file(ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'cartpreview.tpl')){
			$tpl = ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'cartpreview.tpl';
		}
		else{
			$tpl = ROOTPATH . 'design/_tpl/frontend/core/cartpreview.tpl';
		}
		$result = $this->registry->template->fetch($tpl);
		return $result;
	}

	public function updateCartPreview () {
		$objResponse = new xajaxResponse();
		$objResponse->clear("cart-preview", "innerHTML");
		$objResponse->append("cart-preview", "innerHTML", $this->getCartPreviewTemplate());
		return $objResponse;
	}
} 