<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009-2011 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */

class FE_OrderEditor extends FE_Select
{
	
	public $datagrid;
	
	protected $_jsFunction;

	public function __construct ($attributes)
	{
		parent::__construct($attributes);
		$this->_jsFunction = 'LoadProducts_' . $this->_id;
		$this->_attributes['jsfunction'] = 'xajax_' . $this->_jsFunction;
		App::getRegistry()->xajax->registerFunction(array(
			$this->_jsFunction,
			$this,
			'loadProducts'
		));
		$this->_attributes['load_category_children'] = App::getRegistry()->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren_' . $this->_id,
			$this,
			'loadCategoryChildren'
		));
		$this->_attributes['datagrid_filter'] = $this->getDatagridFilterData();
	}

	protected function _PrepareAttributes_JS ()
	{
		$attributes = Array(
			$this->_FormatAttribute_JS('name', 'sName'),
			$this->_FormatAttribute_JS('label', 'sLabel'),
			$this->_FormatAttribute_JS('comment', 'sComment'),
			$this->_FormatAttribute_JS('error', 'sError'),
			$this->_FormatAttribute_JS('on_change', 'fOnChange', FE::TYPE_FUNCTION),
			$this->_FormatAttribute_JS('on_before_change', 'fOnBeforeChange', FE::TYPE_FUNCTION),
			$this->_FormatAttribute_JS('jsfunction', 'fLoadProducts', FE::TYPE_FUNCTION),
			$this->_FormatAttribute_JS('datagrid_filter', 'oFilterData', FE::TYPE_OBJECT),
			$this->_FormatAttribute_JS('load_category_children', 'fLoadCategoryChildren', FE::TYPE_FUNCTION),
			$this->_FormatRepeatable_JS(),
			$this->_FormatRules_JS(),
			$this->_FormatDependency_JS(),
			$this->_FormatDefaults_JS()
		);
		return $attributes;
	}

	public function loadCategoryChildren ($request)
	{
		return Array(
			'aoItems' => $this->getCategories($request['parentId'])
		);
	}

	protected function getCategories ($parent = 0)
	{
		$categories = App::getModel('category')->getChildCategories($parent);
		usort($categories, Array(
			$this,
			'sortCategories'
		));
		return $categories;
	}

	protected function sortCategories ($a, $b)
	{
		return $a['weight'] - $b['weight'];
	}

	public function loadProducts ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getDatagrid ()
	{
		if (($this->datagrid == NULL) || ! ($this->datagrid instanceof DatagridModel)){
			$this->datagrid = App::getModel(get_class($this) . '/datagrid');
			$this->initDatagrid($this->datagrid);
		}
		return $this->datagrid;
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function processVariants ($productId)
	{
		if (! isset($this->_attributes['clientgroupid'])){
			$this->_attributes['clientgroupid'] = 0;
		}
		if (! isset($this->_attributes['currencyid'])){
			$this->_attributes['currencyid'] = 0;
		}
		$rawVariants = (App::getModel('product/product')->getAttributeCombinationsForProduct($productId, $this->_attributes['clientgroupid'], $this->_attributes['currencyid']));
		$variants = Array();
		
		$variants[] = Array(
			'id' => '',
			'caption' => App::getRegistry()->core->getMessage('TXT_CHOOSE_VARIANT'),
			'price' => ''
		);
		foreach ($rawVariants as $variant){
			$caption = Array();
			foreach ($variant['attributes'] as $attribute){
				$caption[] = str_replace('"', '\'', $attribute['name']);
			}
			$variants[] = Array(
				'id' => $variant['id'],
				'caption' => implode(', ', $caption),
				'options' => Array(
					'price' => $variant['price'],
					'stock' => $variant['qty'],
					'weight' => $variant['weight']
				)
			);
		}
		return json_encode($variants);
	}

	public function processSellprice ($sellprice)
	{
		return $sellprice;
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('product', Array(
			'idproduct' => Array(
				'source' => 'P.idproduct'
			),
			'name' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'categoryname' => Array(
				'source' => 'CT.name'
			),
			'categoryid' => Array(
				'source' => 'PC.categoryid',
				'prepareForTree' => true,
				'first_level' => $this->getCategories()
			),
			'ancestorcategoryid' => Array(
				'source' => 'CP.ancestorcategoryid'
			),
			'categoriesname' => Array(
				'source' => 'GROUP_CONCAT(DISTINCT SUBSTRING(CONCAT(\' \', CT.name), 1))',
				'filter' => 'having'
			),
			'sellprice' => Array(
				'source' => 'IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), ROUND(P.discountprice, 2), ROUND(P.sellprice,2))',
			),
			'weight' => Array(
				'source' => 'P.weight'
			),
			'barcode' => Array(
				'source' => 'P.barcode',
				'prepareForAutosuggest' => true
			),
			'buyprice' => Array(
				'source' => 'P.buyprice'
			),
			'producer' => Array(
				'source' => 'PRT.name',
				'prepareForSelect' => true
			),
			'vat' => Array(
				'source' => 'CONCAT(V.value, \'%\')',
				'prepareForSelect' => true
			),
			'stock' => Array(
				'source' => 'stock'
			),
			'variant__options' => Array(
				'source' => 'P.idproduct',
				'processFunction' => Array(
					$this,
					'processVariants'
				)
			)
		));
		$datagrid->setFrom('
			product P
			LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
			LEFT JOIN productcategory PC ON PC.productid = P.idproduct
			LEFT JOIN viewcategory VC ON VC.categoryid = PC.categoryid
			LEFT JOIN category C ON C.idcategory = PC.categoryid
			LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
			LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
			LEFT JOIN `producer` R ON P.producerid = R.idproducer
			LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PRT.languageid = :languageid
			LEFT JOIN `vat` V ON P.vatid = V.idvat
		');
		
		$datagrid->setGroupBy('
			P.idproduct
		');
		
		$datagrid->setAdditionalWhere("
			VC.viewid = :view
		");
		
		$datagrid->setSQLParams(Array(
			'view' => $this->_attributes['viewid']
		));
	
	}
}