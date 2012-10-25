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
 * $Id: pagescheme.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class PageschemeModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('pagescheme', Array(
			'idpagescheme' => Array(
				'source' => 'PS.idpagescheme'
			),
			'name' => Array(
				'source' => 'PS.name'
			),
			'def' => Array(
				'source' => 'PS.default'
			),
			'adddate' => Array(
				'source' => 'PS.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UD.firstname, \' \', UD.surname)'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				pagescheme PS
				LEFT JOIN `user` U ON PS.addid = U.iduser
				LEFT JOIN `userdata` UD ON U.iduser = UD.userid
				LEFT JOIN `user` UE ON PS.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UE.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				PS.idpagescheme
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, PS.viewid IS NULL, PS.viewid = :viewid)
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

	public function getPageschemeForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeletePagescheme ($id, $datagrid)
	{
		$fileCssDel = $this->getCssFileToDelete($datagrid);
		if (is_array($fileCssDel) && isset($fileCssDel['viewid']) && $fileCssDel['viewid'] > 0){
			$this->deleteCssFile($fileCssDel['viewid']);
		}
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deletePagescheme'
		), $this->getName());
	}

	public function getCssFileToDelete ($idpagesheme)
	{
		$default = 0;
		$sql = "SELECT PS.default, PS.viewid
					FROM pagescheme PS
					WHERE PS.idpagescheme= :idpagescheme";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagesheme);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$default = Array(
					'default' => $rs->getInt('default'),
					'viewid' => $rs->getInt('viewid')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $default;
	}

	public function deleteCssFile ($viewid)
	{
		$file = ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $viewid . '.css';
		if (file_exists($file)){
			$delete = @unlink($file);
		}
	}

	public function deletePagescheme ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idpagescheme' => $id
			), $this->getName(), 'deletePagescheme');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXDefaultPagescheme ($datagridId, $id)
	{
		try{
			$this->setDefaultPagescheme($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_SET_DEFAULT_PAGESCHEME')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function setDefaultPagescheme ($id)
	{
		$sql = 'UPDATE pagescheme SET `default`= 1 
					WHERE idpagescheme = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
			$viewid = Helper::getViewId();
			if (! empty($viewid)){
				$sql2 = 'UPDATE pagescheme SET `default`=0 
							WHERE idpagescheme <> :id
							AND viewid= :viewid';
				$stmt2 = $this->registry->db->prepareStatement($sql2);
				$stmt2->setInt('id', $id);
				$stmt2->setInt('viewid', $viewid);
			}
			else{
				$sql2 = 'UPDATE pagescheme SET `default`=0 
							WHERE idpagescheme <> :id
							AND viewid IS NULL';
				$stmt2 = $this->registry->db->prepareStatement($sql2);
				$stmt2->setInt('id', $id);
			}
			$stmt2->executeUpdate();
			App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getPageschemeAll ()
	{
		$sql = 'SELECT idpagescheme AS id, name 
					FROM pagescheme';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getPageschemeAllToSelect ()
	{
		$Data = $this->getVATAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function getSelectorOptionsAll ($idschemeoption)
	{
		$sql = 'SELECT SV.idschemevalue, SV.name
					FROM schemeoption SO
					LEFT JOIN schemeoptionvalue SOV ON SO.idschemeoption=SOV.schemeoptionid
					LEFT JOIN schemevalue SV ON SOV.schemevalueid=SV.idschemevalue
					WHERE SO.idschemeoption= :idschemeoption';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idschemeoption', $idschemeoption);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('idschemevalue'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getSelectorOptionsAllAllToSelect ($idschemeoption)
	{
		$tmp = Array();
		if ($idschemeoption != 0){
			$Data = $this->getSelectorOptionsAll($idschemeoption);
			foreach ($Data as $key){
				$tmp[$key['id']] = $key['name'];
			}
		}
		return $tmp;
	}

	public function checkIfIsDefaultOtherTemplate ($idpagescheme = 0)
	{
		$idTemplate = 0;
		if ($idpagescheme == 0){
			$sql = "SELECT PS.idpagescheme, PS.default
						FROM pagescheme PS
						WHERE PS.default = 1
						AND IF (:viewid >0, PS.viewid= :viewid, PS.viewid IS NULL)";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$viewid = $this->getViewidForThisPagesheme($idpagescheme);
			$sql = "SELECT PS.idpagescheme, PS.default
						FROM pagescheme PS
						WHERE PS.default = 1
						AND IF (:viewid >0, PS.viewid= :viewid, PS.viewid IS NULL)";
			$stmt = $this->registry->db->prepareStatement($sql);
			if (! empty($viewid)){
				$stmt->setInt('viewid', $viewid);
			}
			else{
				$stmt->setInt('viewid', Helper::getViewId());
			}
		}
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$idTemplate = $rs->getInt('idpagescheme');
		}
		return $idTemplate;
	}

	public function getViewidForThisPagesheme ($idpagescheme)
	{
		$viewid = 0;
		$sql = "SELECT PS.viewid
					FROM pagescheme PS
					WHERE PS.idpagescheme = :idpagescheme";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagescheme);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$viewid = $rs->getInt('viewid');
		}
		return $viewid;
	}

	public function updatePageSchemeChangeDefaultStatus ($idpagescheme)
	{
		$sql = 'UPDATE pagescheme SET `default`= 0, editid=:editid
					WHERE idpagescheme = :idpagescheme';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagescheme);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function addNewPageScheme ($submitedData)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			if ($submitedData['default'] == 1){
				$defaultTemplId = $this->checkIfIsDefaultOtherTemplate();
				if ($defaultTemplId > 0){
					$this->updatePageSchemeChangeDefaultStatus($defaultTemplId);
				}
			}
			$idNewPageScheme = $this->addPageScheme($submitedData);
			if ($idNewPageScheme != 0){
				App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idNewPageScheme, $submitedData, null, Array(
					$this,
					'addNewAttributeCss'
				), Array(
					$this,
					'addNewAttributeCssValue'
				), Array(
					$this,
					'addNewAttributeCss2ndValue'
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

	public function addPageScheme ($submitedData)
	{
		$sql = 'INSERT INTO pagescheme (name, `default`, addid, viewid)
					VALUES (:name, :default, :addid, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $submitedData['name']);
		$stmt->setInt('default', $submitedData['default']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (Helper::getViewId() > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setInt('viewid', NULL);
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PAGE_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewAttributeCss ($idNewPageScheme, $attribute, $selector, $class = NULL)
	{
		$sql = 'INSERT INTO pageschemecss (class, selector, attribute, pageschemeid)
					VALUES (:class, :selector, :attribute, :pageschemeid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('class', $class);
		$stmt->setString('selector', $selector);
		$stmt->setString('attribute', $attribute);
		$stmt->setInt('pageschemeid', $idNewPageScheme);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PAGE_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewAttributeCssValue ($idNewPageScheme, $newAttrCssId, $name, $value)
	{
		$sql = 'INSERT INTO pageschemecssvalue (pageschemeid, pageschemecssid, name, value)
					VALUES (:pageschemeid, :pageschemecssid, :name, :value)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('pageschemeid', $idNewPageScheme);
		$stmt->setInt('pageschemecssid', $newAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PAGE_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewAttributeCss2ndValue ($idNewPageScheme, $newAttrCssId, $name, $value, $value2)
	{
		$sql = 'INSERT INTO pageschemecssvalue (pageschemeid, pageschemecssid, name, value, 2ndvalue)
					VALUES (:pageschemeid, :pageschemecssid, :name, :value, :2ndvalue)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('pageschemeid', $idNewPageScheme);
		$stmt->setInt('pageschemecssid', $newAttrCssId);
		$stmt->setString('name', $name);
		$stmt->setString('value', $value);
		$stmt->setString('2ndvalue', $value2);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PAGE_SCHEME_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getTemplateNameToEdit ($idPageScheme)
	{
		$sql = 'SELECT PS.name, PS.default, PS.viewid
					FROM pagescheme PS
					WHERE PS.idpagescheme= :idpagescheme';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idPageScheme);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'default' => $rs->getInt('default'),
				'view' => $rs->getInt('viewid')
			);
		}
		return $Data;
	}

	public function getTemplateCssToEdit ($idPageScheme)
	{
		$sql = "SELECT PSC.idpageschemecss, PSC.class, PSC.selector, PSC.attribute
					FROM pageschemecss PSC
					WHERE PSC.pageschemeid = :idpagescheme";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idPageScheme);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('selector')][$rs->getString('attribute')] = $this->getTemplateCssValueToEdit($rs->getInt('idpageschemecss'));
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

	public function getTemplateCssValueToEdit ($pageschemecssid)
	{
		$sql = "SELECT PSCV.idpageschemecssvalue, PSCV.pageschemeid, PSCV.pageschemecssid, 
						PSCV.name, PSCV.value, PSCV.2ndvalue
					FROM pageschemecssvalue PSCV
					WHERE PSCV.pageschemecssid= :pageschemecssid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('pageschemecssid', $pageschemecssid);
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

	public function editPageScheme ($SubmitedData, $idpagescheme)
	{
		$this->registry->db->setAutoCommit(false);
		if ($SubmitedData['default'] == 1){
			$defaultTemplId = $this->checkIfIsDefaultOtherTemplate($idpagescheme);
			if ($defaultTemplId > 0){
				if ($idpagescheme != $defaultTemplId){
					$this->updatePageSchemeChangeDefaultStatus($defaultTemplId);
				}
			}
		}
		$this->updatePageSchemeMainInfo($SubmitedData, $idpagescheme);
		$cssValues = $this->DeletePageSchemeTemplateCssValue($idpagescheme);
		if ($cssValues == true){
			$css = $this->DeletePageSchemeTemplateCss($idpagescheme);
			if ($css == true){
				App::getModel('fieldgenerator/fieldgenerator')->SaveCSSValues($idpagescheme, $SubmitedData, null, Array(
					$this,
					'addNewAttributeCss'
				), Array(
					$this,
					'addNewAttributeCssValue'
				), Array(
					$this,
					'addNewAttributeCss2ndValue'
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

	public function updatePageSchemeMainInfo ($submitedData, $idpagescheme)
	{
		$sql = 'UPDATE pagescheme SET name=:name, `default`=:default, editid=:editid
					WHERE idpagescheme= :idpagescheme';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagescheme);
		$stmt->setString('name', $submitedData['name']);
		$stmt->setInt('default', $submitedData['default']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function DeletePageSchemeTemplateCss ($idpagescheme)
	{
		$sql = "DELETE FROM pageschemecss
					WHERE pageschemeid= :idpagescheme";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagescheme);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function DeletePageSchemeTemplateCssValue ($idpagescheme)
	{
		$sql = "DELETE FROM pageschemecssvalue
					WHERE pageschemeid= :idpagescheme";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idpagescheme);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}
}