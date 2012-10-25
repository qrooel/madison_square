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
 * $Id: subpagelayout.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SubpagelayoutModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('subpagelayout', Array(
			'idsubpagelayout' => Array(
				'source' => 'DISTINCT(SL.idsubpagelayout)'
			),
			'name' => Array(
				'source' => 'S.name'
			),
			'description' => Array(
				'source' => 'S.description'
			),
			'adddate' => Array(
				'source' => 'SLC.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UD.firstname, \' \', UD.surname)'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				subpage S
				LEFT JOIN subpagelayout SL ON SL.subpageid = S.idsubpage
				LEFT JOIN subpagelayoutcolumn SLC ON SL.idsubpagelayout = SLC.subpagelayoutid
				LEFT JOIN `user` U ON SLC.addid = U.iduser
				LEFT JOIN `userdata` UD ON U.iduser = UD.userid
				LEFT JOIN `user` UE ON SLC.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UE.iduser = UDE.userid
			');
		
		$datagrid->setGroupBy('
				SL.idsubpagelayout
			');
		
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, SL.viewid IS NULL, COALESCE(SL.viewid, 0) = (
					SELECT
						COALESCE(SL2.viewid, 0) AS view
					FROM
						subpage S2
						LEFT JOIN subpagelayout SL2 ON SL2.subpageid = S2.idsubpage
					WHERE
						S2.idsubpage = S.idsubpage
						AND (SL2.viewid = :viewid OR SL2.viewid IS NULL)
					ORDER BY
						view DESC
					LIMIT 1
				))
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

	public function getSubpageLayoutForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getSubPageLayoutAll ($idsubpagelayout = NULL)
	{
		$Data = Array();
		$sql = "
				SELECT
					DISTINCT(SL.idsubpagelayout) AS id,
					S.name AS name,
					SL.viewid AS viewid,
					COUNT(idsubpagelayoutcolumn) AS columns,
					COUNT(idsubpagelayoutcolumnbox) AS boxes
				FROM
					subpagelayout SL
					LEFT JOIN subpage S ON S.idsubpage = SL.subpageid
					LEFT JOIN subpagelayoutcolumn SLC ON SL.idsubpagelayout = SLC.subpagelayoutid
					LEFT JOIN subpagelayoutcolumnbox SLCB ON SLCB.subpagelayoutcolumnid = SLC.idsubpagelayoutcolumn
				WHERE
					IF(:viewid IS NULL, SL.viewid IS NULL, COALESCE(SL.viewid, 0) = (
						SELECT
							COALESCE(SL2.viewid, 0) AS view
						FROM
							subpage S2
							LEFT JOIN subpagelayout SL2 ON SL2.subpageid = S2.idsubpage
						WHERE
							SL.subpageid = S2.idsubpage
							AND (SL2.viewid = :viewid OR SL2.viewid IS NULL)
						ORDER BY
							view DESC
						LIMIT 1
					))
					AND IF(:idsubpagelayout > 0, SL.idsubpagelayout = :idsubpagelayout, 1)
				GROUP BY S.idsubpage
				ORDER BY S.name
			";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubpagelayout', $idsubpagelayout);
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getSubPageLayoutAllToSelect ($idsubpagelayout = NULL, $restrict = false)
	{
		
		$Data = $this->getSubPageLayoutAll($idsubpagelayout);
		$tmp = Array();
		foreach ($Data as $key){
			if ($restrict == true){
				if ($key['columns'] == 0 && $key['boxes'] == 0){
					$tmp[$key['id']] = $key['name'];
				}
			}
			else{
				$tmp[$key['id']] = $key['name'];
			}
		}
		return $tmp;
	}

	public function getBoxesAll ($subpage)
	{
		$Data = Array();
		$sql = '
				SELECT
					LB.idlayoutbox AS id,
					LB.name,
					LB.controller,
					LBT.title
				FROM
					layoutbox LB
					LEFT JOIN layoutboxtranslation LBT ON LBT.layoutboxid = LB.idlayoutbox
				WHERE
					LBT.languageid = :languageid AND
					IF(LB.viewid IS NOT NULL, LB.viewid = :viewid, 1)
				ORDER BY
					LB.name ASC
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($subpage != '' && $this->checkBoxForSubpage($rs->getString('controller'), $subpage) == 1){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'name' => $rs->getString('name') . ' - ' . $rs->getString('title')
				);
			}
		}
		return $Data;
	}

	public function checkBoxForSubpage ($controller, $subpage)
	{
		if ($controller == 'NewsBox' && $subpage != 'News'){
			return 0;
		}
		if ($controller == 'CartBox' && $subpage != 'Cart'){
			return 0;
		}
		if ($controller == 'ProductBox' && $subpage != 'Product'){
			return 0;
		}
		if ($controller == 'ProductsCrossSellBox' && ! in_array($subpage, Array(
			'Product',
			'Cart'
		))){
			return 0;
		}
		if ($controller == 'ProductsSimilarBox' && ! in_array($subpage, Array(
			'Product',
			'Cart'
		))){
			return 0;
		}
		if ($controller == 'ProductsUpSellBox' && ! in_array($subpage, Array(
			'Product',
			'Cart'
		))){
			return 0;
		}
		if ($controller == 'ProductBuyAlsoBox' && $subpage != 'Product'){
			return 0;
		}
		if ($controller == 'ProductsInCategoryBox' && $subpage != 'ProductInCategory'){
			return 0;
		}
		if ($controller == 'LayeredNavigationBox' && ! in_array($subpage, Array(
			'ProductInCategory',
			'ProductSearchList'
		))){
			return 0;
		}
		if ($controller == 'ClientSettingsBox' && $subpage != 'ClientSettings'){
			return 0;
		}
		if ($controller == 'ClientAddressBox' && $subpage != 'ClientAddress'){
			return 0;
		}
		if ($controller == 'ClientOrderBox' && $subpage != 'ClientOrder'){
			return 0;
		}
		if ($controller == 'ProductSearchListBox' && $subpage != 'ProductSearchList'){
			return 0;
		}
		if ($controller == 'CmsBox' && $subpage != 'Staticcms'){
			return 0;
		}
		if ($controller == 'SitemapBox' && $subpage != 'Sitemap'){
			return 0;
		}
		if ($controller == 'PaymentBox' && $subpage != 'Payment'){
			return 0;
		}
		if ($controller == 'ProducerListBox' && $subpage != 'Producerlist'){
			return 0;
		}
		if ($controller == 'RegistrationCartBox' && ! in_array($subpage, Array(
			'RegistrationCart',
			'Clientlogin'
		))){
			return 0;
		}
		if ($controller == 'ForgotPasswordBox' && $subpage != 'Forgotpassword'){
			return 0;
		}
		return 1;
	}

	public function getBoxesAllToSelect ($subpage = '')
	{
		$Data = $this->getBoxesAll($subpage);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function editSubpageLayout ($submitedData, $subpagelayoutid)
	{
		$this->registry->db->setAutoCommit(false);
		$columncounter = 0;
		$key = 0;
		$oldColumn = $this->getSubPageLayoutAllColumn($subpagelayoutid);
		if (isset($submitedData['columns']['columns_data']) && $submitedData['columns']['columns_data'] != NULL){
			foreach ($submitedData['columns']['columns_data'] as $column => $value){
				$ColumnNewId = 0;
				$ColumnId = 0;
				$columncounter = $columncounter + 1;
				if (is_numeric($column)){
					$ColumnId = $this->editSubpageLayoutColumn($column, $value['columns_width'], $columncounter);
					if (in_array($ColumnId, $oldColumn) == TRUE){
						$keyOld = array_search($ColumnId, $oldColumn);
						unset($oldColumn[$keyOld]);
					}
				}
				else{
					$ColumnNewId = $this->addSubpageLayoutColumn($submitedData['columns']['subpagelayoutid'], $value['columns_width'], $columncounter);
				}
				if ($ColumnId > 0){
					$this->deleteSubpageLayoutColumnBox($ColumnId);
					$boxOrder = 0;
					foreach ($value['layout_boxes'] as $box => $boxvalue){
						$this->addSubpageLayoutcolumnBox($ColumnId, $boxvalue, $boxOrder);
						$boxOrder ++;
					}
				}
				if ($ColumnNewId > 0){
					$boxOrder = 0;
					foreach ($value['layout_boxes'] as $box => $boxvalue){
						$this->addSubpageLayoutcolumnBox($ColumnNewId, $boxvalue, $boxOrder);
						$boxOrder ++;
					}
				}
			}
			if (count($oldColumn) > 0){
				foreach ($oldColumn as $old){
					$this->deleteSubpageLayoutColumn($old);
				}
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			return true;
		}
		else{
			return false;
		}
	}

	public function addSubpageLayoutForView ($submitedData)
	{
		$this->registry->db->setAutoCommit(false);
		$sql = '
				SELECT
					subpageid
				FROM
					subpagelayout
				WHERE
					idsubpagelayout = :idsubpagelayout
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubpagelayout', $submitedData['columns']['subpagelayoutid']);
		$rs = $stmt->executeQuery();
		$rs->next();
		$subpageid = $rs->getInt('subpageid');
		$sql = '
				INSERT INTO
					subpagelayout
					(subpageid, viewid)
				VALUES
					(:subpageid, :viewid)
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpageid', $subpageid);
		if (Helper::getViewId() == 0){
			$stmt->setNull('viewid');
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		$stmt->executeQuery();
		$subpageLayoutId = $stmt->getConnection()->getIdGenerator()->getId();
		$columnOrder = 0;
		if (isset($submitedData['columns']['columns_data']) && $submitedData['columns']['columns_data'] != NULL){
			foreach ($submitedData['columns']['columns_data'] as $column => $value){
				$columnId = $this->addSubpageLayoutColumn($subpageLayoutId, $value['columns_width'], ++ $columnOrder);
				if ($columnId > 0){
					$boxOrder = 0;
					foreach ($value['layout_boxes'] as $box => $boxvalue){
						$this->addSubpageLayoutcolumnBox($columnId, $boxvalue, ++ $boxOrder);
					}
				}
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
		}
	}

	public function addSubpageLayout ($submitedData)
	{
		$this->registry->db->setAutoCommit(false);
		$columncounter = 0;
		if (isset($submitedData['columns']['columns_data']) && $submitedData['columns']['columns_data'] != NULL){
			foreach ($submitedData['columns']['columns_data'] as $column => $value){
				$columncounter = $columncounter + 1;
				$ColumnId = $this->addSubpageLayoutColumn($submitedData['columns']['subpagelayoutid'], $value['columns_width'], $columncounter);
				if ($ColumnId > 0){
					$boxOrder = 0;
					foreach ($value['layout_boxes'] as $box => $boxvalue){
						$boxOrder = $boxOrder + 1;
						$this->addSubpageLayoutcolumnBox($ColumnId, $boxvalue, $boxOrder);
					}
				}
			}
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			return true;
		}
		else{
			return false;
		}
	}

	public function addSubpageLayoutColumn ($subpagelayoutid, $columnWidth, $order)
	{
		$sql = 'INSERT INTO subpagelayoutcolumn (subpagelayoutid, `order`, width, addid)
						VALUES (:subpagelayoutid, :order, :width, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutid', $subpagelayoutid);
		$stmt->setInt('order', $order);
		$stmt->setString('width', $columnWidth);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function editSubpageLayoutColumn ($columnId, $columnWidth, $order)
	{
		$sql = "UPDATE subpagelayoutcolumn 
					SET 
						`order`= :order,
						width= :width,
						editid= :editid
					WHERE idsubpagelayoutcolumn= :columnId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('columnId', $columnId);
		$stmt->setString('width', $columnWidth);
		$stmt->setInt('order', $order);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $columnId;
	}

	public function editSubpageLayoutcolumnBox ($subpageLayoutColumnId, $box, $boxvalue, $boxOrder)
	{
		$sql = "UPDATE subpagelayoutcolumnbox 
					SET 
						subpagelayoutcolumnid= :subpagelayoutcolumnid,
						layoutboxid= :layoutboxid,
						`order`= :order,
						colspan= :colspan,
						collapsed= :collapsed
					WHERE idsubpagelayoutcolumnbox= :subpagelayoutcolumnid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubpagelayoutcolumnbox', $box);
		$stmt->setInt('subpagelayoutcolumnid', $subpageLayoutColumnId);
		$stmt->setInt('layoutboxid', $boxvalue['layoutbox']);
		$stmt->setInt('order', $boxOrder);
		$stmt->setInt('colspan', $boxvalue['span']);
		if (isset($boxvalue['collapsed'])){
			$stmt->setInt('collapsed', 1);
		}
		else{
			$stmt->setInt('collapsed', 0);
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $box;
	}

	public function addSubpageLayoutcolumnBox ($subpageLayoutColumnId, $boxvalues, $boxOrder)
	{
		$sql = 'INSERT INTO subpagelayoutcolumnbox (subpagelayoutcolumnid, layoutboxid, `order`, colspan, collapsed)
					VALUES (:subpagelayoutcolumnid, :layoutboxid, :order, :colspan, :collapsed)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutcolumnid', $subpageLayoutColumnId);
		$stmt->setInt('layoutboxid', $boxvalues['layoutbox']);
		$stmt->setInt('order', $boxOrder);
		$stmt->setInt('colspan', $boxvalues['span']);
		if (isset($boxvalues['collapsed'])){
			$stmt->setInt('collapsed', 1);
		}
		else{
			$stmt->setInt('collapsed', 0);
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getSubPageLayoutAllColumn ($subpagelayoutid)
	{
		$Data = Array();
		$sql = "SELECT SLC.idsubpagelayoutcolumn
					FROM subpagelayoutcolumn SLC
						LEFT JOIN subpagelayout SL ON SL.idsubpagelayout = SLC.subpagelayoutid
					WHERE SLC.subpagelayoutid= :subpagelayoutid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutid', $subpagelayoutid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			array_push($Data, $rs->getInt('idsubpagelayoutcolumn'));
		}
		return $Data;
	}

	public function getSubPageLayoutAllColumnBoxes ($subpagelayoutid)
	{
		$Data = Array();
		$sql = "SELECT SLCB.idsubpagelayoutcolumnbox
					FROM subpagelayoutcolumnbox SLCB
						LEFT JOIN subpagelayoutcolumn SLC ON SLCB.subpagelayoutcolumnid = SLC.idsubpagelayoutcolumn
						LEFT JOIN subpagelayout SL ON SL.idsubpagelayout = SLC.subpagelayoutid
					WHERE SL.idsubpagelayout= :subpagelayoutid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutid', $subpagelayoutid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			array_push($Data, $rs->getInt('idsubpagelayoutcolumnbox'));
		}
		return $Data;
	}

	public function getSubPageLayoutColumn ($subpagelayoutid)
	{
		$Data = Array();
		$sql = "SELECT SLC.idsubpagelayoutcolumn, SLC.subpagelayoutid, SLC.`order`, SLC.width, SLC.viewid
					FROM subpagelayoutcolumn SLC
						LEFT JOIN subpagelayout SL ON SL.idsubpagelayout = SLC.subpagelayoutid
					WHERE SL.idsubpagelayout= :subpagelayoutid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutid', $subpagelayoutid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['subpagelayoutid'] = $rs->getInt('subpagelayoutid');
			$Data['columns'][] = Array(
				'idsubpagelayoutcolumn' => $rs->getInt('idsubpagelayoutcolumn'),
				'subpagelayoutid' => $rs->getInt('subpagelayoutid'),
				'order' => $rs->getInt('order'),
				'width' => $rs->getInt('width'),
				'viewid' => $rs->getInt('viewid'),
				'subpagelayoutcolumnbox' => $this->getSubPageLayoutColumnBox($rs->getInt('idsubpagelayoutcolumn'))
			);
		}
		return $Data;
	}

	public function getSubPageLayoutColumnBox ($subpagelayoutcolumnid)
	{
		$Data = Array();
		$sql = "SELECT 
					SLCB.idsubpagelayoutcolumnbox, 
					SLCB.subpagelayoutcolumnid, 
					SLCB.layoutboxid, 
					LB.controller,
					SLCB.`order`, 
					SLCB.colspan, 
					SLCB.collapsed
				FROM subpagelayoutcolumnbox SLCB
				LEFT JOIN layoutbox LB ON LB.idlayoutbox = SLCB.layoutboxid
				LEFT JOIN subpagelayoutcolumn SLC ON SLC.idsubpagelayoutcolumn = SLCB.subpagelayoutcolumnid
				WHERE SLC.idsubpagelayoutcolumn = :subpagelayoutcolumnid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutcolumnid', $subpagelayoutcolumnid);
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'idsubpagelayoutcolumnbox' => $rs->getInt('idsubpagelayoutcolumnbox'),
					'subpagelayoutcolumnid' => $rs->getInt('subpagelayoutcolumnid'),
					'layoutboxid' => $rs->getInt('layoutboxid'),
					'controller' => $rs->getString('controller'),
					'order' => $rs->getInt('order'),
					'colspan' => $rs->getInt('colspan'),
					'collapsed' => $rs->getInt('collapsed')
				);
			}
			return $Data;
		}
		catch (Exception $e){
			return false;
		}
	}

	public function deleteSubpageLayoutColumn ($subpagelayoutcolumnid)
	{
		$this->deleteSubpageLayoutColumnBox($subpagelayoutcolumnid);
		$sql = "DELETE FROM subpagelayoutcolumn  
					WHERE idsubpagelayoutcolumn = :subpagelayoutcolumnid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('subpagelayoutcolumnid', $subpagelayoutcolumnid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function deleteSubpageLayoutColumnBox ($idsubpagelayoutcolumn)
	{
		$sql = "DELETE FROM subpagelayoutcolumnbox
					WHERE subpagelayoutcolumnid = :idsubpagelayoutcolumn";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubpagelayoutcolumn', $idsubpagelayoutcolumn);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function DeleteSubpageLayout ($idsubpagelayout)
	{
		$sql = "DELETE FROM subpagelayout
					WHERE idsubpagelayout = :idsubpagelayout";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idsubpagelayout', $idsubpagelayout);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			return false;
		}
		return true;
	}

	public function flushCache ($subpageName)
	{
		Cache::destroyObject('columns' . $subpageName);
	}

	public function exportSubpagesForView ()
	{
		$Data = $this->getSubPageLayoutAll();
		foreach ($Data as $key => $subpage){
			$Data[$key]['subpage'] = $this->getSubPageLayoutColumn($subpage['id']);
		}
		return $Data;
	}

	public function importSubpagesForView ()
	{
		$this->registry->db->setAutoCommit(false);
		$file = 'shoppica.xml';
		$xml = simplexml_load_file(ROOTPATH . 'upload/' . $file);
		foreach ($xml->subpage as $row){
			$sql = 'SELECT
						idsubpage
					FROM subpage
					WHERE name = :name
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', (string) $row->name);
			$rs = $stmt->executeQuery();
			$rs->next();
			$subpageid = $rs->getInt('idsubpage');
			
			$sql = 'INSERT INTO	subpagelayout SET
						subpageid = :subpageid,
						viewid = :viewid
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('subpageid', $subpageid);
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->executeQuery();
			$subpageLayoutId = $stmt->getConnection()->getIdGenerator()->getId();
			foreach ($row->column as $column){
				$columnId = $this->addSubpageLayoutColumn($subpageLayoutId, $column->attributes()->width, $column->attributes()->order);
				if ($columnId > 0){
					$boxOrder = 0;
					foreach ($column->box as $box){
						
						$sql2 = 'SELECT
									idlayoutbox
								FROM layoutbox
								WHERE controller = :controller
						';
						$stmt2 = $this->registry->db->prepareStatement($sql2);
						$stmt2->setString('controller', (string) $box->attributes()->controller);
						$rs2 = $stmt2->executeQuery();
						$rs2->next();
						$layoutboxid = $rs2->getInt('idlayoutbox');
						
						$boxvalues['layoutbox'] = $layoutboxid;
						$boxvalues['span'] = (string) $box->attributes()->colspan;
						if ((string) $box->attributes()->collapsed == 1){
							$boxvalues['collapsed'] = 1;
						}
						$this->addSubpageLayoutcolumnBox($columnId, $boxvalues, (string) $box->attributes()->order);
					}
				}
			}
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}
}