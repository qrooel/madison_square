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
 * $Id: layoutboxscheme.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class LayoutboxschemeModel extends ModelWithDatagrid
{
	
	protected $newLayoutBoxSchemeId;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('layoutboxscheme', Array(
			'idlayoutboxscheme' => Array(
				'source' => 'LBS.idlayoutboxscheme'
			),
			'name' => Array(
				'source' => 'LBS.name'
			),
			'adddate' => Array(
				'source' => 'LBS.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UD.firstname, \' \', UD.surname)'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				layoutboxscheme LBS
				LEFT JOIN `user` U ON LBS.addid = U.iduser
				LEFT JOIN `userdata` UD ON U.iduser = UD.userid
				LEFT JOIN `user` UE ON LBS.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UE.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				LBS.idlayoutboxscheme
			');
		
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, LBS.viewid IS NULL, LBS.viewid = :viewid)
			');
	}

	public function getValueForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getLayoutboxschemeForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteLayoutboxscheme ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteLayoutboxscheme'
		), $this->getName());
	}

	public function deleteLayoutboxscheme ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idlayoutboxscheme' => $id
			), $this->getName(), 'deleteLayoutboxscheme');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewLayoutBoxScheme ($submitedData)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$idNewLayoutBoxSchemeScheme = $this->addLayoutBoxScheme($submitedData);
			if ($idNewLayoutBoxSchemeScheme != 0){
				$this->newLayoutBoxSchemeId = $idNewLayoutBoxSchemeScheme;
				App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idNewLayoutBoxSchemeScheme, $submitedData, Array(
					$this,
					'GetSelector'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCss'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCssValue'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCss2ndValue'
				));
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addLayoutBoxScheme ($submitedData)
	{
		$sql = 'INSERT INTO layoutboxscheme (name, viewid, addid)
					VALUES (:name, :viewid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		if(Helper::getViewId() > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}else{
			$stmt->setNull('viewid');
		}
		$stmt->setString('name', $submitedData['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	/**
	 * GetSelector
	 * 
	 * Tworzy selektor na podstawie selektora zrodlowego, zawierajacego
	 * ciag "__id__". W praktyce - podmienia ten ciag na id edytowanego
	 * szablonu boksow.
	 * 
	 * @param string $selector Selektor wejsciowy
	 * @return string Selektor zawierajacy podstawione id
	 */
	public function GetSelector ($selector)
	{
		return str_replace('__id__', (! $this->registry->core->getParam()) ? $this->newLayoutBoxSchemeId : $this->registry->core->getParam(), $selector);
	}

	public function addNewLayoutBoxSchemeAttributeCss ($idNewLayoutBoxSchemeScheme, $attribute, $selector, $class = NULL)
	{
		$sql = 'INSERT INTO layoutboxschemecss (class, selector, attribute, layoutboxschemeid)
					VALUES (:class, :selector, :attribute, :layoutboxschemeid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('class', $class);
		$stmt->setString('selector', $selector);
		$stmt->setString('attribute', $attribute);
		$stmt->setInt('layoutboxschemeid', $idNewLayoutBoxSchemeScheme);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewLayoutBoxSchemeAttributeCssValue ($idNewLayoutBoxSchemeScheme, $newLayoutAttrCssId, $name, $value)
	{
		$sql = 'INSERT INTO layoutboxschemecssvalue (layoutboxschemeid, layoutboxschemecssid, name, value)
					VALUES (:layoutboxschemeid, :layoutboxschemecssid, :name, :value)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemeid', $idNewLayoutBoxSchemeScheme);
		$stmt->setInt('layoutboxschemecssid', $newLayoutAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewLayoutBoxSchemeAttributeCss2ndValue ($idNewLayoutBoxSchemeScheme, $newLayoutAttrCssId, $name, $value, $value2)
	{
		$sql = 'INSERT INTO layoutboxschemecssvalue (layoutboxschemeid, layoutboxschemecssid, name, value, 2ndvalue)
					VALUES (:layoutboxschemeid, :layoutboxschemecssid, :name, :value, :2ndvalue)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemeid', $idNewLayoutBoxSchemeScheme);
		$stmt->setInt('layoutboxschemecssid', $newLayoutAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		$stmt->setString('2ndvalue', $value2);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LAYOUTBOX_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getLayoutBoxSchemeToEdit ($IdLayoutBoxScheme)
	{
		$sql = 'SELECT LBS.name, viewid
					FROM layoutboxscheme LBS
					WHERE LBS.idlayoutboxscheme= :idlayoutboxscheme';
		$name = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutboxscheme', $IdLayoutBoxScheme);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$name = Array(
				'name' => $rs->getString('name'),
				'view' => $rs->getInt('viewid')
			);
		}
		return $name;
	}

	public function getLayoutBoxSchemeCSSToEdit ($IdLayoutBoxScheme)
	{
		$sql = "SELECT LBSC.idlayoutboxschemecss, LBSC.class, LBSC.selector, LBSC.attribute
					FROM layoutboxschemecss LBSC
					WHERE LBSC.layoutboxschemeid = :layoutboxschemeid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemeid', $IdLayoutBoxScheme);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('selector')][$rs->getString('attribute')] = $this->getLayoutBoxSchemeCssValueToEdit($rs->getInt('idlayoutboxschemecss'));
		}
		return $Data;
	}

	public function prepareFieldName ($class = NULL, $selector, $attribute)
	{
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

	public function getLayoutBoxSchemeCssValueToEdit ($layoutboxschemecssid)
	{
		$sql = "SELECT LBSCV.idlayoutboxschemecssvalue, LBSCV.layoutboxschemecssid, LBSCV.layoutboxschemeid,
						LBSCV.name, LBSCV.value, LBSCV.2ndvalue
					FROM layoutboxschemecssvalue LBSCV
					WHERE LBSCV.layoutboxschemecssid= :layoutboxschemecssid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemecssid', $layoutboxschemecssid);
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

	public function editLayoutBoxScheme ($submitedData, $idlayoutboxscheme)
	{
		$this->registry->db->setAutoCommit(false);
		$this->updateLayoutBoxSchemeName($submitedData['name'], $idlayoutboxscheme);
		$cssValues = $this->deleteLayoutBoxSchemeTemplateCssValue($idlayoutboxscheme);
		if ($cssValues == true){
			$css = $this->deleteLayoutBoxSchemeTemplateCss($idlayoutboxscheme);
			if ($css == true){
				App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idlayoutboxscheme, $submitedData, Array(
					$this,
					'GetSelector'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCss'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCssValue'
				), Array(
					$this,
					'addNewLayoutBoxSchemeAttributeCss2ndValue'
				));
				$this->registry->db->commit();
				$this->registry->db->setAutoCommit(true);
				return true;
			}
		}
		else{
			return false;
		}
	}

	public function updateLayoutBoxSchemeName ($name, $idlayoutboxscheme)
	{
		$sql = 'UPDATE layoutboxscheme SET name=:name, editid=:editid
					WHERE idlayoutboxscheme = :idlayoutboxscheme';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutboxscheme', $idlayoutboxscheme);
		$stmt->setString('name', $name);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return true;
	}

	public function deleteLayoutBoxSchemeTemplateCssValue ($layoutboxschemeid)
	{
		$sql = "DELETE FROM layoutboxschemecssvalue
					WHERE layoutboxschemeid= :layoutboxschemeid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemeid', $layoutboxschemeid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return true;
	}

	public function deleteLayoutBoxSchemeTemplateCss ($layoutboxschemeid)
	{
		$sql = "DELETE FROM layoutboxschemecss
					WHERE layoutboxschemeid= :layoutboxschemeid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemeid', $layoutboxschemeid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return true;
	}

	public function getSchemeValuesForAjax ($request)
	{
		
		$LayoutData = $this->getLayoutboxForPreview($request['id']);
		$layoutboxid = $request['id'];
		$array['id'] = $layoutboxid;
		$array['layoutboxid'] = $request['data']['layoutboxid'];
		
		if (isset($LayoutData['.layout-box-' . $layoutboxid . '_border'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . '_border']['value'] as $key => $value){
				$array['#layout-box-id_border'][$value['name']][$value['value']] = $value['2ndvalue'];
			}
		}
		if (isset($LayoutData['.layout-box-' . $layoutboxid . '_border-radius'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . '_border-radius']['value'] as $key => $value){
				$array['#layout-box-' . $layoutboxid . '_border-radius'] = $value['value'];
			}
		}
		if (isset($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-header_font'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-header_font']['value'] as $key => $value){
				$array['#layout-box-' . $layoutboxid . ',.layout-box-header_font'][$value['name']] = $value['value'];
			}
		}
		if (isset($LayoutData['.layout-box-' . $layoutboxid . '-header_background'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . '-header_background']['value'] as $key => $value){
				$array['#layout-box-id-header_background'][$value['name']] = $value['value'];
			}
		}
		if (isset($LayoutData['.layout-box-' . $layoutboxid . '-header h3_text-align'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . '-header h3_text-align']['value'] as $key => $value){
				$array['#layout-box-id-header h3_text-align'][$value['name']] = $value['value'];
			}
		}
		if (isset($LayoutData['.layout-box-' . $layoutboxid . ' .layout-box-content_background'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . ' .layout-box-content_background']['value'] as $key => $value){
				$array['.layout-box-' . $layoutboxid . ' .layout-box-content_background'][$value['name']] = $value['value'];
			}
		}
		
		if (isset($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_font'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_font']['value'] as $key => $value){
				$array['#layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_font'][$value['name']] = $value['value'];
			}
		}
		
		if (isset($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_text-align'])){
			foreach ($LayoutData['.layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_text-align']['value'] as $key => $value){
				$array['#layout-box-' . $layoutboxid . ',.layout-box-content|.layout-box-content p_text-align'][$value['name']] = $value['value'];
			}
		}
		
		return $array;
	}

	public function getLayoutboxForPreview ($layoutboxId)
	{
		$Data = Array();
		$sql = 'SELECT LBC.idlayoutboxschemecss, LBC.class, LBC.selector, LBC.attribute, LBC.layoutboxschemeid
					FROM layoutboxscheme LB
					LEFT JOIN layoutboxschemecss LBC ON LB.idlayoutboxscheme = LBC.layoutboxschemeid
					WHERE idlayoutboxscheme = :idlayoutboxscheme';
		$stmt = $this->registry->db->prepareStatement($sql);
		//$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idlayoutboxscheme', $layoutboxId);
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$name = $this->prepareFieldNameBox($rs->getString('class'), $rs->getString('selector'), $rs->getString('attribute'));
				$Data[$name] = Array(
					'name' => $name,
					'idlayoutboxschemecss' => $rs->getInt('idlayoutboxschemecss'),
					'class' => $rs->getString('class'),
					'selector' => $rs->getString('selector'),
					'attribute' => $rs->getString('attribute'),
					'layoutboxschemeid' => $rs->getString('layoutboxschemeid'),
					'value' => $this->getLayoutboxCssValueForPreview($rs->getInt('idlayoutboxschemecss'))
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
			return false;
		}
		return $Data;
	}

	public function getLayoutboxCssValueForPreview ($idlayoutboxschemecss)
	{
		$sql = 'SELECT idlayoutboxschemecssvalue, layoutboxschemeid, layoutboxschemecssid, name, value, 2ndvalue
					FROM layoutboxschemecssvalue LB
					WHERE layoutboxschemecssid = :layoutboxschemecssid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('layoutboxschemecssid', $idlayoutboxschemecss);
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'idlayoutboxschemecssvalue' => $rs->getInt('idlayoutboxschemecssvalue'),
					'layoutboxschemeid' => $rs->getInt('layoutboxschemeid'),
					'layoutboxschemecssid' => $rs->getInt('layoutboxschemecssid'),
					'name' => $rs->getString('name'),
					'value' => $rs->getString('value'),
					'2ndvalue' => $rs->getString('2ndvalue')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
			return false;
		}
		return $Data;
	}

	public function prepareFieldNameBox ($class = NULL, $selector, $attribute)
	{
		$fieldName = '';
		if ($selector != NULL && $attribute != NULL){
			if ($class !== NULL){
				if (stripos($selector, '.') !== FALSE){
					$selector = str_replace('.', '#', $selector);
				}
				$prepareName = $class . ',' . $selector . '_' . $attribute;
			}
			else{
				if (stripos($selector, '.') !== FALSE){
					$selector = str_replace('.', '#', $selector);
				}
				$prepareName = $selector . '_' . $attribute;
			}
			$fieldName = $prepareName;
		}
		return $fieldName;
	}
}