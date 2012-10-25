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
 * $Revision: 583 $
 * $Author: gekosale $
 * $Date: 2011-10-28 22:19:07 +0200 (Pt, 28 paź 2011) $
 * $Id: layoutbox.php 583 2011-10-28 20:19:07Z gekosale $
 */
class LayoutboxModel extends ModelWithDatagrid {
	protected $newLayoutBoxSchemeId;

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid) {
		$datagrid->setTableData('layoutbox', Array(
			'idlayoutbox' => Array(
				'source' => 'LB.idlayoutbox'
			),
			'name' => Array(
				'source' => 'LB.name'
			),
			'title' => Array(
				'source' => 'LBT.title'
			),
			'controller' => Array(
				'source' => 'LB.controller'
			),
			'adddate' => Array(
				'source' => 'LB.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UD.firstname, \' \', UD.surname)'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				layoutbox LB
				LEFT JOIN `layoutboxtranslation` LBT ON LBT.layoutboxid = LB.idlayoutbox
				LEFT JOIN `user` U ON LB.addid = U.iduser
				LEFT JOIN `userdata` UD ON U.iduser = UD.userid
				LEFT JOIN `user` UE ON LB.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UE.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				LB.idlayoutbox
			');
		
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, LB.viewid IS NULL, LB.viewid = :viewid)
			');
	}

	public function getValueForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData () {
		return $this->getDatagrid()->getFilterData();
	}

	public function getLayoutboxForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteLayoutbox ($id, $datagrid) {
		$this->deleteLayoutbox($id);
		$this->flushLayoutBoxCache();
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteLayoutbox ($id) {
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idlayoutbox' => $id
			), $this->getName(), 'deleteLayoutbox');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getLayoutBoxContentTypeOptionsAllToSelect () {
		$Data = $this->getLayoutBoxContentTypeOptions();
		asort($Data);
		return $Data;
	}
	
	//
	// public function addContentType($event, $Data){
	// $event->setReturnValue(Array('21'=>'332'));
	// return Array('21'=>'332');
	// }
	public function getLayoutBoxContentTypeOptions ($Data = Array()) {
		$Data = Array(
			'TextBox' => $this->registry->core->getMessage('TXT_TEXT_BOX'),
			'ProductPromotionsBox' => $this->registry->core->getMessage('TXT_PRODUCT_PROMOTIONS_BOX'),
			'ProductNewsBox' => $this->registry->core->getMessage('TXT_PRODUCT_NEWS_BOX'),
			'GraphicsBox' => $this->registry->core->getMessage('TXT_GRAPHICS_BOX'),
			'CategoriesBox' => $this->registry->core->getMessage('TXT_CATEGORIES_BOX'),
			'PollBox' => $this->registry->core->getMessage('TXT_POLL_BOX'),
			'NewsBox' => $this->registry->core->getMessage('TXT_NEWS_BOX'),
			'ProductsInCategoryBox' => $this->registry->core->getMessage('TXT_PRODUCTS_IN_CATEGORY_BOX'),
			'ProductsCrossSellBox' => $this->registry->core->getMessage('TXT_PRODUCTS_CROSS_SELL_BOX'),
			'ProductsSimilarBox' => $this->registry->core->getMessage('TXT_PRODUCTS_SIMILAR_BOX'),
			'ProductsUpSellBox' => $this->registry->core->getMessage('TXT_PRODUCTS_UP_SELL_BOX'),
			'ProductBox' => $this->registry->core->getMessage('TXT_PRODUCT_BOX'),
			'LayeredNavigationBox' => $this->registry->core->getMessage('TXT_LAYERED_NAVIGATION_BOX'),
			'NewsListBox' => $this->registry->core->getMessage('TXT_NEWS_LIST_BOX'),
			'ContactBox' => $this->registry->core->getMessage('TXT_CONTACT_BOX'),
			'ProductBestsellersBox' => $this->registry->core->getMessage('TXT_PRODUCT_BESTSELLERS_BOX'),
			'TagsBox' => $this->registry->core->getMessage('TXT_TAGS_BOX'),
			'ClientTagsBox' => $this->registry->core->getMessage('TXT_CLIENT_TAGS_BOX'),
			'WishlistBox' => $this->registry->core->getMessage('TXT_WISHLIST_BOX'),
			'MostSearchedBox' => $this->registry->core->getMessage('TXT_MOST_SEARCHED_BOX'),
			'CartBox' => $this->registry->core->getMessage('TXT_CART'),
			'RegistrationCartBox' => $this->registry->core->getMessage('TXT_REGISTRATION_CART_BOX'),
			'PaymentBox' => $this->registry->core->getMessage('TXT_PAYMENT_BOX'),
			'FinalizationBox' => $this->registry->core->getMessage('TXT_FINALIZATION_BOX'),
			'ClientLoginBox' => $this->registry->core->getMessage('TXT_CLIENT_LOGIN_BOX'),
			'ForgotPasswordBox' => $this->registry->core->getMessage('TXT_FORGOT_PASSWORD_BOX'),
			'ClientSettingsBox' => $this->registry->core->getMessage('TXT_CLIENT_SETTINGS_BOX'),
			'ClientOrderBox' => $this->registry->core->getMessage('TXT_CLIENT_ORDER_BOX'),
			'ClientAddressBox' => $this->registry->core->getMessage('TXT_CLIENT_ADDRESS_BOX'),
			'ProductTagsListBox' => $this->registry->core->getMessage('TXT_PRODUCT_TAGS_LIST_BOX'),
			'ProductSearchListBox' => $this->registry->core->getMessage('TXT_PRODUCT_SEARCH_LIST_BOX'),
			'CartPreviewBox' => $this->registry->core->getMessage('TXT_CART_PREVIEW_BOX'),
			'RecommendFriendBox' => $this->registry->core->getMessage('TXT_RECOMMEND_FRIEND_BOX'),
			'NewsletterBox' => $this->registry->core->getMessage('TXT_NEWSLETTER_BOX'),
			'CmsBox' => $this->registry->core->getMessage('TXT_CMS_BOX'),
			'ShowcaseBox' => $this->registry->core->getMessage('TXT_SHOWCASE_BOX'),
			'ProductBuyAlsoBox' => $this->registry->core->getMessage('TXT_BUY_ALSO_BOX'),
			'SitemapBox' => $this->registry->core->getMessage('TXT_SITEMAP_BOX'),
			'SlideShowBox' => $this->registry->core->getMessage('TXT_SLIDESHOW_BOX'),
			'ProducerBox' => $this->registry->core->getMessage('TXT_PRODUCER_BOX'),
			'ProducerListBox' => $this->registry->core->getMessage('TXT_PRODUCER_LIST_BOX'),
			'FacebookLikeBox' => $this->registry->core->getMessage('TXT_FACEBOOK_LIKE_BOX'),
			'MainCategoriesBox' => $this->registry->core->getMessage('TXT_MAIN_CATEGORIES_BOX'),
			'CustomProductListBox' => $this->registry->core->getMessage('TXT_CUSTOM_PRODUCT_LIST_BOX')
		);
		
		$event = new sfEvent($this, 'admin.layoutbox.getLayoutBoxContentTypeOptions');
		$this->registry->dispatcher->filter($event, $Data);
		$arguments = $event->getReturnValues();
		foreach ($arguments as $key => $box){
			foreach ($box as $controller => $label){
				$Data[$controller] = $label;
			}
		}
		return $Data;
	}

	public function getLayoutBoxContentTypeSpecificValues ($idLayoutBox) {
		$query = '
				SELECT
					CS.variable AS variable,
					CS.value AS value,
					CS.languageid AS languageid
				FROM
					layoutboxcontentspecificvalue CS
				WHERE
					CS.layoutboxid = :layoutboxid
			';
		$stmt = App::getRegistry()->db->prepareStatement($query);
		$stmt->set('layoutboxid', $idLayoutBox);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			if ($languageid = $rs->getInt('languageid')){
				if (! isset($Data[$rs->getString('variable')])){
					$Data[$rs->getString('variable')] = Array();
				}
				$Data[$rs->getString('variable')][$languageid] = $rs->getString('value');
			}
			else{
				$Data[$rs->getString('variable')] = $rs->getString('value');
			}
		}
		return $Data;
	}

	public function getLayoutBoxSchemeTemplatesAllToSelect () {
		$Data = $this->getLayoutBoxSchemeTemplates();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function getLayoutBoxSchemeTemplates () {
		$Data = Array();
		$sql = "SELECT 
				LBST.idlayoutboxscheme AS id, LBST.name
				FROM layoutboxscheme LBST";
		if (Helper::getViewId() == 0){
			$sql .= ' WHERE LBST.viewid IS NULL';
		}
		else{
			$sql .= ' WHERE LBST.viewid = :viewid';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function addNewLayoutBox ($submittedData) {
		$this->registry->db->setAutoCommit(false);
		try{
			$idNewLayoutBox = $this->addLayoutBox($submittedData);
			if ($idNewLayoutBox != 0){
				$this->newLayoutBoxId = $idNewLayoutBox;
				if (isset($submittedData['save_changes']) && $submittedData['save_changes'] == 1){
					App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idNewLayoutBox, $submittedData, Array(
						$this,
						'GetSelector'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCss'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCssValue'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCss2ndValue'
					));
				}
				$this->updateLayoutBoxContentTypeSpecificValues($idNewLayoutBox, FE::SubmittedData());
				if (isset($submittedData['bFixedPosition']) && $submittedData['bFixedPosition'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'bFixedPosition', $submittedData['bFixedPosition']);
				}
				if (isset($submittedData['bClosingProhibited']) && $submittedData['bClosingProhibited'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'bClosingProhibited', $submittedData['bClosingProhibited']);
				}
				if (isset($submittedData['bNoHeader']) && $submittedData['bNoHeader'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'bNoHeader', $submittedData['bNoHeader']);
				}
				if (isset($submittedData['bCollapsingProhibited']) && $submittedData['bCollapsingProhibited'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'bCollapsingProhibited', $submittedData['bCollapsingProhibited']);
				}
				if (isset($submittedData['bExpandingProhibited']) && $submittedData['bExpandingProhibited'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'bExpandingProhibited', $submittedData['bExpandingProhibited']);
				}
				if (isset($submittedData['iDefaultSpan']) && $submittedData['iDefaultSpan'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'iDefaultSpan', $submittedData['iDefaultSpan']);
				}
				if (isset($submittedData['iEnableBox']) && $submittedData['iEnableBox'] !== NULL){
					$this->addLayoutboxJSValue($idNewLayoutBox, 'iEnableBox', $submittedData['iEnableBox']);
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushLayoutBoxCache();
		return true;
	}

	public function addLayoutBox ($submittedData) {
		if (isset($submittedData['chose_template']) && $submittedData['chose_template'] > 0){
			$sql = 'INSERT INTO layoutbox (name, addid, layoutboxschemeid,viewid,controller)
						VALUES (:name, :addid, :layoutboxschemeid,:viewid,:controller)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('layoutboxschemeid', $submittedData['chose_template']);
		}
		else{
			$sql = 'INSERT INTO layoutbox (name,viewid, addid,controller)
						VALUES (:name,:viewid, :addid,:controller)';
			$stmt = $this->registry->db->prepareStatement($sql);
		}
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('controller', $submittedData['box_content']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
			$layoutboxid = $stmt->getConnection()->getIdGenerator()->getId();
			$sql = '
					INSERT INTO
						layoutboxtranslation (layoutboxid, languageid, title)
					VALUES (:layoutboxid, :languageid, :title)
				';
			foreach ($submittedData['title'] as $languageid => $title){
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('layoutboxid', $layoutboxid);
				$stmt->setInt('languageid', $languageid);
				$stmt->setString('title', $title);
				$stmt->executeQuery();
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_ADD'), 11, $e->getMessage());
		}
		return $layoutboxid;
	}

	protected function deleteLayoutBoxContentTypeSpecificValues ($idLayoutBox) {
		$sql = '
				DELETE
				FROM
					layoutboxcontentspecificvalue
				WHERE
					layoutboxid = :id
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $idLayoutBox);
		$stmt->executeUpdate();
	}

	protected function updateLayoutBoxContentTypeSpecificValues ($idLayoutBox, $submittedData) {
		$this->deleteLayoutBoxContentTypeSpecificValues($idLayoutBox);
		$variables = Array();
		switch ($submittedData['box']['box_content']) {
			case 'TextBox':
				$content = Array();
				foreach ($submittedData['ct_TextBox']['textbox_content_translation'] as $languageid => $value){
					$content[$languageid] = $value['textbox_content'];
				}
				$variables['content'] = $content;
				break;
			case 'GraphicsBox':
				$variables['image'] = 'design/_images_frontend/upload/' . $submittedData['ct_GraphicsBox']['image']['file'];
				$size = getimagesize(ROOTPATH . $variables['image']);
				$variables['height'] = $size[1] - 10;
				$variables['align'] = $submittedData['ct_GraphicsBox']['align'];
				$variables['url'] = $submittedData['ct_GraphicsBox']['url'];
				break;
			case 'NewsListBox':
				$variables['newsCount'] = $submittedData['ct_NewsListBox']['newsCount'];
				break;
			case 'FacebookLikeBox':
				$variables['url'] = $submittedData['ct_FacebookLikeBox']['url'];
				$variables['width'] = $submittedData['ct_FacebookLikeBox']['width'];
				$variables['height'] = $submittedData['ct_FacebookLikeBox']['height'];
				$variables['scheme'] = $submittedData['ct_FacebookLikeBox']['scheme'];
				$variables['faces'] = $submittedData['ct_FacebookLikeBox']['faces'];
				$variables['stream'] = $submittedData['ct_FacebookLikeBox']['stream'];
				$variables['header'] = $submittedData['ct_FacebookLikeBox']['header'];
				break;
			case 'ProductBox':
				$variables['tabbed'] = (isset($submittedData['ct_ProductBox']['tabbed']) && $submittedData['ct_ProductBox']['tabbed']) ? '1' : '0';
				break;
			case 'ProductsInCategoryBox':
				$variables['productsCount'] = $submittedData['ct_ProductsInCategoryBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductsInCategoryBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductsInCategoryBox']['orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductsInCategoryBox']['orderDir'];
				$variables['pagination'] = (isset($submittedData['ct_ProductsInCategoryBox']['pagination']) && $submittedData['ct_ProductsInCategoryBox']['pagination']) ? '1' : '0';
				break;
			case 'ProductSearchListBox':
				$variables['productsCount'] = $submittedData['ct_ProductSearchListBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductSearchListBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductSearchListBox']['orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductSearchListBox']['orderDir'];
				$variables['pagination'] = (isset($submittedData['ct_ProductSearchListBox']['pagination']) && $submittedData['ct_ProductSearchListBox']['pagination']) ? '1' : '0';
				break;
			case 'ProductPromotionsBox':
				$variables['productsCount'] = $submittedData['ct_ProductPromotionsBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductPromotionsBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductPromotionsBox']['ct_ProductPromotionsBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductPromotionsBox']['ct_ProductPromotionsBox_orderDir'];
				$variables['pagination'] = (isset($submittedData['ct_ProductPromotionsBox']['pagination']) && $submittedData['ct_ProductPromotionsBox']['pagination']) ? '1' : '0';
				break;
			case 'ProductNewsBox':
				$variables['productsCount'] = $submittedData['ct_ProductNewsBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductNewsBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductNewsBox']['ct_ProductNewsBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductNewsBox']['ct_ProductNewsBox_orderDir'];
				$variables['pagination'] = (isset($submittedData['ct_ProductNewsBox']['pagination']) && $submittedData['ct_ProductNewsBox']['pagination']) ? '1' : '0';
				break;
			case 'ProductsCrossSellBox':
				$variables['productsCount'] = $submittedData['ct_ProductsCrossSellBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductsCrossSellBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductsCrossSellBox']['ct_ProductsCrossSellBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductsCrossSellBox']['ct_ProductsCrossSellBox_orderDir'];
				break;
			case 'ProductsSimilarBox':
				$variables['productsCount'] = $submittedData['ct_ProductsSimilarBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductsSimilarBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductsSimilarBox']['ct_ProductsSimilarBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductsSimilarBox']['ct_ProductsSimilarBox_orderDir'];
				break;
			case 'ProductsUpSellBox':
				$variables['productsCount'] = $submittedData['ct_ProductsUpSellBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductsUpSellBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductsUpSellBox']['ct_ProductsUpSellBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductsUpSellBox']['ct_ProductsUpSellBox_orderDir'];
				break;
			case 'CategoriesBox':
				App::getModel('category')->flushCache();
				$variables['showcount'] = (isset($submittedData['ct_CategoriesBox']['showcount']) && $submittedData['ct_CategoriesBox']['showcount']) ? '1' : '0';
				$variables['hideempty'] = (isset($submittedData['ct_CategoriesBox']['hideempty']) && $submittedData['ct_CategoriesBox']['hideempty']) ? '1' : '0';
				$variables['showall'] = isset($submittedData['ct_CategoriesBox']['showall']) ? $submittedData['ct_CategoriesBox']['showall'] : 1;
				$variables['categoryIds'] = (isset($submittedData['ct_CategoriesBox']['categoryIds']) && is_array($submittedData['ct_CategoriesBox']['categoryIds']) && count($submittedData['ct_CategoriesBox']['categoryIds']) > 0) ? implode(',', $submittedData['ct_CategoriesBox']['categoryIds']) : '';
				break;
			case 'ShowcaseBox':
				$variables['productsCount'] = $submittedData['ct_ShowcaseBox']['productsCount'];
				$variables['orderBy'] = $submittedData['ct_ShowcaseBox']['ct_ShowcaseBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ShowcaseBox']['ct_ShowcaseBox_orderDir'];
				$variables['statusId'] = $submittedData['ct_ShowcaseBox']['statusId'];
				break;
			case 'ProductBestsellersBox':
				$variables['productsCount'] = $submittedData['ct_ProductBestsellersBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProductBestsellersBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProductBestsellersBox']['ct_ProductBestsellersBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProductBestsellersBox']['ct_ProductBestsellersBox_orderDir'];
				break;
			case 'CustomProductListBox':
				$variables['productsCount'] = $submittedData['ct_CustomProductListBox']['productsCount'];
				$variables['view'] = $submittedData['ct_CustomProductListBox']['view'];
				$variables['orderBy'] = $submittedData['ct_CustomProductListBox']['ct_CustomProductListBox_orderBy'];
				$variables['orderDir'] = $submittedData['ct_CustomProductListBox']['ct_CustomProductListBox_orderDir'];
				$variables['products'] = (isset($submittedData['ct_CustomProductListBox']['custom_products'])) ? implode(',', $submittedData['ct_CustomProductListBox']['custom_products']) : '';
				break;
			case 'SitemapBox':
				$variables['categoryTreeLevels'] = $submittedData['ct_SitemapBox']['categoryTreeLevels'];
				break;
			case 'SlideShowBox':
				for ($i = 1; $i <= 10; $i ++){
					if ($submittedData['ct_SlideShowBox']['image' . $i]['file'] != ''){
						$variables['image' . $i] = 'design/_images_frontend/upload/' . $submittedData['ct_SlideShowBox']['image' . $i]['file'];
						$size = getimagesize(ROOTPATH . $variables['image' . $i]);
						$variables['height' . $i] = $size[1];
						$variables['url' . $i] = $submittedData['ct_SlideShowBox']['url' . $i];
						$variables['caption' . $i] = $submittedData['ct_SlideShowBox']['caption' . $i];
					}
				}
				break;
			case 'ProducerBox':
				$variables['view'] = $submittedData['ct_ProducerBox']['view'];
				$variables['producers'] = (isset($submittedData['ct_ProducerBox']['producers'])) ? implode(',', $submittedData['ct_ProducerBox']['producers']) : '';
				break;
			case 'ProducerListBox':
				$variables['productsCount'] = $submittedData['ct_ProducerListBox']['productsCount'];
				$variables['view'] = $submittedData['ct_ProducerListBox']['view'];
				$variables['orderBy'] = $submittedData['ct_ProducerListBox']['orderBy'];
				$variables['orderDir'] = $submittedData['ct_ProducerListBox']['orderDir'];
				$variables['pagination'] = (isset($submittedData['ct_ProducerListBox']['pagination']) && $submittedData['ct_ProducerListBox']['pagination']) ? '1' : '0';
				$variables['showphoto'] = (isset($submittedData['ct_ProducerListBox']['showphoto']) && $submittedData['ct_ProducerListBox']['showphoto']) ? '1' : '0';
				$variables['showdescription'] = (isset($submittedData['ct_ProducerListBox']['showdescription']) && $submittedData['ct_ProducerListBox']['showdescription']) ? '1' : '0';
				break;
		}
		
		foreach ($variables as $variable => $value){
			if (is_array($value)){
				foreach ($value as $languageid => $translatedValue){
					$sql = '
							INSERT
							INTO
								layoutboxcontentspecificvalue (
									layoutboxid,
									variable,
									value,
									languageid
								)
							VALUES (
								:id,
								:variable,
								:value,
								:languageid
							)
						';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('id', $idLayoutBox);
					$stmt->setString('variable', $variable);
					$stmt->setString('value', $translatedValue);
					$stmt->setInt('languageid', $languageid);
					$stmt->executeUpdate();
				}
			}
			else{
				$sql = '
						INSERT
						INTO
							layoutboxcontentspecificvalue (
								layoutboxid,
								variable,
								value
							)
						VALUES (
							:id,
							:variable,
							:value
						)
					';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('id', $idLayoutBox);
				$stmt->setString('variable', $variable);
				$stmt->setString('value', $value);
				$stmt->executeUpdate();
			}
		}
	}

	/**
	 * GetSelector
	 *
	 * Tworzy selektor na podstawie selektora zrodlowego, zawierajacego
	 * ciag "__id__". W praktyce - podmienia ten ciag na id edytowanego
	 * szablonu boksow.
	 *
	 * @param string $selector
	 *        	Selektor wejsciowy
	 * @return string Selektor zawierajacy podstawione id
	 */
	public function GetSelector ($selector) {
		return str_replace('__id__', (! $this->registry->core->getParam()) ? $this->newLayoutBoxId : $this->registry->core->getParam(), $selector);
	}

	public function addNewLayoutBoxAttributeCss ($idNewLayoutBox, $attribute, $selector, $class = NULL) {
		$sql = 'INSERT INTO layoutboxcss (class, selector, attribute, layoutboxid)
					VALUES (:class, :selector, :attribute, :layoutboxid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('class', $class);
		$stmt->setString('selector', $selector);
		$stmt->setString('attribute', $attribute);
		$stmt->setInt('layoutboxid', $idNewLayoutBox);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewLayoutBoxAttributeCssValue ($idNewLayoutBox, $newLayoutAttrCssId, $name, $value) {
		$sql = 'INSERT INTO layoutboxcssvalue (layoutboxid, layoutboxcssid, name, value)
					VALUES (:layoutboxid, :layoutboxcssid, :name, :value)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idNewLayoutBox);
		$stmt->setInt('layoutboxcssid', $newLayoutAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewLayoutBoxAttributeCss2ndValue ($idNewLayoutBox, $newLayoutAttrCssId, $name, $value, $value2) {
		$sql = 'INSERT INTO layoutboxcssvalue (layoutboxid, layoutboxcssid, name, value, 2ndvalue)
					VALUES (:layoutboxid, :layoutboxcssid, :name, :value, :2ndvalue)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idNewLayoutBox);
		$stmt->setInt('layoutboxcssid', $newLayoutAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		$stmt->setString('2ndvalue', $value2);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addLayoutboxJSValue ($idNewLayoutBox, $variable, $value) {
		$sql = 'INSERT INTO layoutboxjsvalue (layoutboxid, variable, value)
					VALUES (:layoutboxid, :variable, :value)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idNewLayoutBox);
		$stmt->setString('variable', $variable);
		$stmt->setString('value', $value);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function deleteLayoutboxJSValue ($idLayoutBox) {
		$sql = 'DELETE FROM layoutboxjsvalue 
					WHERE layoutboxid= :layoutboxid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idLayoutBox);
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
			return false;
		}
		return true;
	}

	public function getLayoutBoxToEdit ($IdLayoutBox) {
		$sql = 'SELECT 
					LB.name, 
					LB.layoutboxschemeid, 
					LB.layoutboxschemeid,  
					LB.controller
				FROM layoutbox LB
				WHERE LB.idlayoutbox= :idlayoutbox';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutbox', $IdLayoutBox);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$sql = '
					SELECT
						LBT.languageid AS languageid,
						LBT.title AS title
					FROM
						layoutboxtranslation LBT
					WHERE
						LBT.layoutboxid = :idlayoutbox
				';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('idlayoutbox', $IdLayoutBox);
			$rs2 = $stmt->executeQuery();
			$title = Array();
			while ($rs2->next()){
				$title[$rs2->getInt('languageid')] = $rs2->getString('title');
			}
			$Data = Array(
				'name' => $rs->getString('name'),
				'title' => $title,
				'layoutboxschemeid' => $rs->getInt('layoutboxschemeid'),
				'controller' => $rs->getString('controller')
			);
		}
		return $Data;
	}

	public function prepareFieldName ($class = NULL, $selector, $attribute) {
		$fieldName = '';
		if ($selector != NULL && $attribute != NULL){
			if ($class !== NULL){
				$prepareName = $class . ',' . $selector . '_' . $attribute;
			}
			else{
				$prepareName = $selector . '_' . $attribute;
			}
			$fieldName = $prepareName;
		}
		return $fieldName;
	}

	public function getLayoutBoxCSSToEdit ($IdLayoutBox) {
		$sql = "SELECT LBC.idlayoutboxcss, LBC.class, LBC.selector, LBC.attribute
					FROM layoutboxcss LBC
					WHERE LBC.layoutboxid = :layoutboxid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $IdLayoutBox);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('selector')][$rs->getString('attribute')] = $this->getLayoutBoxCssValueToEdit($rs->getInt('idlayoutboxcss'));
		}
		return $Data;
	}

	public function getLayoutBoxCssValueToEdit ($idLayoutBoxCss) {
		$sql = "SELECT LBCV.idlayoutboxcssvalue, LBCV.layoutboxcssid, LBCV.layoutboxid,
						LBCV.name, LBCV.value, LBCV.2ndvalue
					FROM layoutboxcssvalue LBCV
					WHERE LBCV.layoutboxcssid= :layoutboxcssid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxcssid', $idLayoutBoxCss);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($rs->getString('2ndvalue') != NULL){
				$Data[$rs->getString('name')][$rs->getString('value')] = $rs->getString('2ndvalue');
			}
			else{
				$Data[$rs->getString('name')] = $rs->getString('value');
			}
		}
		return $Data;
	}

	public function getLayoutBoxJSValuesToEdit ($idLayoutBox) {
		$sql = "SELECT LBJV.idlayoutboxjsvalue, LBJV.variable, LBJV.value
					FROM layoutboxjsvalue LBJV
					WHERE  LBJV.layoutboxid= :idlayoutbox";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutbox', $idLayoutBox);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('variable')] = $rs->getString('value');
		}
		return $Data;
	}

	public function editLayoutBox ($submittedData, $idlayoutbox) {
		$this->updateLayoutBox($submittedData, $idlayoutbox);
		$this->updateLayoutBoxContentTypeSpecificValues($idlayoutbox, FE::SubmittedData());
		if (isset($submittedData['save_changes']) && $submittedData['save_changes'] == 1){
			$cssValues = $this->deleteLayoutBoxCssValue($idlayoutbox);
			if ($cssValues == true){
				$css = $this->deleteLayoutBoxCss($idlayoutbox);
				if ($css == true){
					App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idlayoutbox, $submittedData, Array(
						$this,
						'GetSelector'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCss'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCssValue'
					), Array(
						$this,
						'addNewLayoutBoxAttributeCss2ndValue'
					));
				}
			}
		}
		$js = $this->deleteLayoutboxJSValue($idlayoutbox);
		if ($js == true){
			if (isset($submittedData['bFixedPosition']) && $submittedData['bFixedPosition'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'bFixedPosition', $submittedData['bFixedPosition']);
			}
			if (isset($submittedData['bClosingProhibited']) && $submittedData['bClosingProhibited'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'bClosingProhibited', $submittedData['bClosingProhibited']);
			}
			if (isset($submittedData['bNoHeader']) && $submittedData['bNoHeader'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'bNoHeader', $submittedData['bNoHeader']);
			}
			if (isset($submittedData['bCollapsingProhibited']) && $submittedData['bCollapsingProhibited'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'bCollapsingProhibited', $submittedData['bCollapsingProhibited']);
			}
			if (isset($submittedData['bExpandingProhibited']) && $submittedData['bExpandingProhibited'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'bExpandingProhibited', $submittedData['bExpandingProhibited']);
			}
			if (isset($submittedData['iDefaultSpan']) && $submittedData['iDefaultSpan'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'iDefaultSpan', $submittedData['iDefaultSpan']);
			}
			if (isset($submittedData['iEnableBox']) && $submittedData['iEnableBox'] !== NULL){
				$this->addLayoutboxJSValue($idlayoutbox, 'iEnableBox', $submittedData['iEnableBox']);
			}
		}
		$this->flushLayoutBoxCache();
	}

	public function updateLayoutBox ($submittedData, $idlayoutbox) {
		if (isset($submittedData['chose_template']) && $submittedData['chose_template'] > 0){
			$sql = 'UPDATE layoutbox 
							SET name= :name, 
							layoutboxschemeid= :layoutboxschemeid,
							controller = :controller  
							editid= :editid
						WHERE idlayoutbox = :idlayoutbox';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('layoutboxschemeid', $submittedData['chose_template']);
		}
		else{
			$sql = 'UPDATE layoutbox 
							SET name= :name, 
							editid= :editid,
							controller = :controller 
						WHERE idlayoutbox = :idlayoutbox';
			$stmt = $this->registry->db->prepareStatement($sql);
		}
		$stmt->setInt('idlayoutbox', $idlayoutbox);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('controller', $submittedData['box_content']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
			$sql = 'DELETE FROM layoutboxtranslation WHERE layoutboxid = :layoutboxid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('layoutboxid', $idlayoutbox);
			$stmt->executeQuery();
			foreach ($submittedData['title'] as $languageid => $title){
				$sql = '
						INSERT INTO
							layoutboxtranslation (layoutboxid, languageid, title)
						VALUES (:layoutboxid, :languageid, :title)
					';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('layoutboxid', $idlayoutbox);
				$stmt->setInt('languageid', $languageid);
				$stmt->setString('title', $title);
				$stmt->executeQuery();
			}
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function deleteLayoutBoxCssValue ($idlayoutbox) {
		$sql = "DELETE FROM layoutboxcssvalue
					WHERE layoutboxid= :layoutboxid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idlayoutbox);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function deleteLayoutBoxCss ($idlayoutbox) {
		$sql = "DELETE FROM layoutboxcss
					WHERE layoutboxid= :layoutboxid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxid', $idlayoutbox);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function changeBorderSize ($idBorderSize) {
		return $idBorderSize;
	}

	public function changeBackground ($background) {
		if ($background != NULL){
			return $background;
		}
	}

	public function getSchemeValuesForAjax ($request) {
		$values = Array();
		if ($request['id'] == '0'){
			$rawValues = App::getModel('cssgenerator/cssgenerator')->getPageSchemeStyleSheetContent();
			foreach ($rawValues as $value){
				if (strpos($value['selector'], 'layout-box') === false){
					continue;
				}
				$value['selector'] = str_replace(',', ', #layout-box-__id__', '#layout-box-__id__ ' . $value['selector']);
				$value['selector'] = str_replace('#layout-box-__id__ .layout-box ', '#layout-box-__id__ ', $value['selector']);
				$value['selector'] = str_replace('.layout-box.layout-box ', '#layout-box-__id__ ', $value['selector']);
				$value['selector'] = preg_replace('/#layout-box-__id__ \.layout-box$/', '#layout-box-__id__.layout-box', $value['selector']);
				$values[$value['selector']][$value['attribute']] = $value['value'];
			}
		}
		elseif (! empty($request['id'])){
			$rawValues = App::getModel('cssgenerator/cssgenerator')->getLayoutBoxSchemeStyleSheetContent($request['id']);
			foreach ($rawValues as $value){
				if (strpos($value['selector'], 'layout-box') === false){
					continue;
				}
				$value['selector'] = preg_replace('/.layout-box-scheme-\d+/', '#layout-box-__id__', $value['selector']);
				$values[$value['selector']][$value['attribute']] = $value['value'];
			}
		}
		return Array(
			'values' => $values
		);
	}

	public function flushLayoutBoxCache () {
		$dir = ROOTPATH . 'serialization' . DS;
		foreach (glob($dir . 'Cache*') as $key => $fn){
			if (is_file($fn)){
				unlink($fn);
			}
		}
	}
}