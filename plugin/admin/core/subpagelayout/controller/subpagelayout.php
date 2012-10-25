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

class subpagelayoutController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllSubpageLayout',
			App::getModel('subpagelayout'),
			'getSubpageLayoutForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetValueSuggestions',
			App::getModel('subpagelayout'),
			'getValueForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////        ADD SUBPAGELAYOUT       ////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add ()
	{
		
		$subpageLayoutList = App::getModel('subpagelayout')->getSubPageLayoutAllToSelect(null, true);
		
		if (is_array($subpageLayoutList) && count($subpageLayoutList) > 0){
			$list = 0;
			
			$form = new FE_Form(Array(
				'name' => 'add_subpagelayout',
				'action' => '',
				'method' => 'post'
			));
			////////////////////////////////////////        COLUMNS       ///////////////////////////////////////////
			$columnsAdd = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'columns',
				'label' => $this->registry->core->getMessage('TXT_SUBPAGE_COLUMNS')
			)));
			
			$subpagelayout = $columnsAdd->AddChild(new FE_Select(Array(
				'name' => 'subpagelayoutid',
				'label' => $this->registry->core->getMessage('TXT_CHOOSE_SUBPAGE_LAYOUT'),
				'options' => FE_Option::Make($subpageLayoutList)
			)));
			
			$columnsDataAdd = $columnsAdd->AddChild(new FE_FieldsetRepeatable(Array(
				'name' => 'columns_data',
				'label' => $this->registry->core->getMessage('TXT_COLUMNS_DATA'),
				'repeat_min' => 1,
				'repeat_max' => FE::INFINITE
			)));
			
			$columnsDataAdd->AddChild(new FE_TextField(Array(
				'name' => 'columns_width',
				'label' => $this->registry->core->getMessage('TXT_WIDTH'),
				'comment' => $this->registry->core->getMessage('TXT_COLUMN_WIDTH_INFO')
			)));
			
			$boxDataAdd = $columnsDataAdd->AddChild(new FE_LayoutBoxesList(Array(
				'name' => 'layout_boxes',
				'label' => 'Wybierz boksy',
				'boxes' => FE_Option::Make(App::getModel('subpagelayout')->getBoxesAllToSelect())
			)));
			
			if ($form->Validate(FE::SubmittedData())){
				App::getModel('subpagelayout')->addSubpageLayout($form->getSubmitValues());
				App::redirect(__ADMINPANE__ . '/subpagelayout');
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->registry->xajax->processRequest();
			$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
			$this->registry->template->assign('list', $list);
			$this->registry->template->assign('form', $form);
			$this->registry->template->display($this->loadTemplate('add.tpl'));
		}
		else{
			$list = 1;
			$emptyList = new FE_Form(Array(
				'name' => 'empty_list',
				'action' => '',
				'method' => 'post'
			));
			
			$fieldset = $emptyList->AddChild(new FE_Fieldset(Array(
				'name' => 'empty',
				'label' => $this->registry->core->getMessage('TXT_SUBPAGE_LAYOUT_ADD')
			)));
			
			$fieldset->AddChild(new FE_StaticText(Array(
				'text' => '<center><p><strong>Wszystkie podstrony zostały już użyte. Możliwa jest tylko edycja układów podstron</strong></p></center>'
			)));
			
			$this->registry->xajax->processRequest();
			$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
			$this->registry->template->assign('emptyList', $emptyList);
			$this->registry->template->assign('list', $list);
			$this->registry->template->display($this->loadTemplate('add.tpl'));
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////       EDIT SUBPAGELAYOUT       ////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit ()
	{
		
		$subpageLayout = App::getModel('subpagelayout')->getSubPageLayoutAll($this->registry->core->getParam());
		
		if (! isset($subpageLayout[0]['name'])){
			App::redirect(__ADMINPANE__ . '/subpagelayout');
		}
		
		$form = new FE_Form(Array(
			'name' => 'edit_subpagelayout',
			'action' => '',
			'method' => 'post'
		));
		
		////////////////////////////////////////        COLUMNS       ///////////////////////////////////////////
		$columnsEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'columns',
			'label' => $this->registry->core->getMessage('TXT_SUBPAGE_COLUMNS')
		)));
		
		$subpages = App::getModel('subpagelayout')->getSubPageLayoutAllToSelect($this->registry->core->getParam());
		
		$subpagelayoutid = $columnsEdit->AddChild(new FE_Constant(Array(
			'name' => 'subpagelayout_subpage',
			'label' => $this->registry->core->getMessage('Podstrona')
		)));
		
		$subpagelayoutid = $columnsEdit->AddChild(new FE_Hidden(Array(
			'name' => 'subpagelayoutid'
		)));
		
		$columnsDataEdit = $columnsEdit->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'columns_data',
			'label' => $this->registry->core->getMessage('TXT_COLUMNS_DATA'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$columnsDataEdit->AddChild(new FE_Tip(Array(
			'tip' => '<p>Aby kolumna rozciągnęła się na całą, pozostałą część strony, jako jej szerokość podaj wartość <strong>0</strong>.</p><p>Jeśli kilka kolumn będzie miało szerokość 0, wówczas zostaną im automatycznie przyznane równe części pozostałego miejsca.</p>',
			'retractable' => false
		)));
		
		$columnsDataEdit->AddChild(new FE_TextField(Array(
			'name' => 'columns_width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_WIDTH'))
			)
		)));
		
		$boxDataEdit = $columnsDataEdit->AddChild(new FE_LayoutBoxesList(Array(
			'name' => 'layout_boxes',
			'label' => 'Wybierz boksy',
			'boxes' => FE_Option::Make(App::getModel('subpagelayout')->getBoxesAllToSelect($subpageLayout[0]['name']))
		)));
		
		///////////////////////////////////////////        POPULATE       //////////////////////////////////////////////
		

		$subpagelayoutcolumn = App::getModel('subpagelayout')->getSubPageLayoutColumn($this->registry->core->getParam());
		$populate = Array();
		
		if (is_array($subpagelayoutcolumn) && count($subpagelayoutcolumn) > 0){
			if (isset($subpagelayoutcolumn['subpagelayoutid']) && $subpagelayoutcolumn['subpagelayoutid'] > 0){
				$populate['columns']['subpagelayout_subpage'] = $subpages[$subpagelayoutcolumn['subpagelayoutid']];
				$populate['columns']['subpagelayoutid'] = $subpagelayoutcolumn['subpagelayoutid'];
			}
			if (isset($subpagelayoutcolumn['columns']) && count($subpagelayoutcolumn['columns']) > 0){
				foreach ($subpagelayoutcolumn['columns'] as $column){
					$populate['columns']['columns_data'][$column['idsubpagelayoutcolumn']] = Array(
						'columns_width' => $column['width']
					);
					if (count($column['subpagelayoutcolumnbox']) > 0){
						foreach ($column['subpagelayoutcolumnbox'] as $boxes){
							$populate['columns']['columns_data'][$column['idsubpagelayoutcolumn']]['layout_boxes'][$boxes['order']] = Array(
								//$populate['columns']['columns_data'][$boxes['subpagelayoutcolumnid']]['layout_boxes']$boxes['layoutboxid'] = Array(
								// $boxes['layoutboxid'] => Array(
								'box' => $boxes['layoutboxid'],
								'span' => $boxes['colspan'],
								'collapsed' => $boxes['collapsed']
							); // )
						

						}
					}
				}
			}
			$form->Populate($populate);
		}
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				if ((int) $subpageLayout[0]['viewid'] != Helper::getViewId()){
					App::getModel('subpagelayout')->addSubpageLayoutForView($form->getSubmitValues());
				}
				else{
					App::getModel('subpagelayout')->editSubpageLayout($form->getSubmitValues(), $this->registry->core->getParam());
				}
				App::getModel('subpagelayout')->flushCache($subpages[$subpagelayoutcolumn['subpagelayoutid']]);
				App::redirect(__ADMINPANE__ . '/subpagelayout');
			}
			catch (Exception $e){
				$this->registry->session->setVolatileSubpageLayoutAdd(1, false);
			}
		}
		
		$error = $this->registry->session->getVolatileSubpageLayoutAdd();
		if ($error[0] == 1){
			$this->registry->template->assign('error', $e->getMessage());
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->registry->xajaxInterface->registerFunction(Array(
			'DeleteSubpageLayout',
			$this,
			'DeleteSubpageLayoutForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('subpageLayout', $subpageLayout[0]);
		$this->registry->template->assign('viewSpecific', (Helper::getViewId() and $subpageLayout[0]['viewid'] == Helper::getViewId()));
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function DeleteSubpageLayoutForAjax ($request)
	{
		return Array(
			'status' => App::getModel('subpagelayout')->DeleteSubpageLayout($request['idsubpagelayout'])
		);
	}

	public function view ()
	{
		$this->disableLayout();
		$Data = App::getModel('subpagelayout')->exportSubpagesForView();
		$xml = new SimpleXMLElement('<subpages></subpages>');
		foreach($Data as $key => $subpage){
			$node = $xml->addChild('subpage');
			$name = $node->addChild('name', $subpage['name']);
			foreach($subpage['subpage']['columns'] as $col){
				$column = $node->addChild('column');
				$column->addAttribute('order', $col['order']);
				$column->addAttribute('width', $col['width']);
				foreach($col['subpagelayoutcolumnbox'] as $box){
					$b = $column->addChild('box');
					$b->addAttribute('controller', $box['controller']);
					$b->addAttribute('colspan', $box['colspan']);
					$b->addAttribute('order', $box['order']);
					$b->addAttribute('collapsed', $box['collapsed']);
				}
			}
		}
		header('Content-type: text/xml; charset=utf-8');
		header('Content-disposition: attachment; filename=shoppica.xml');
		header('Content-type: text/xml');
		header('Cache-Control: max-age=0');
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;
		$domnode = dom_import_simplexml($xml);
		$domnode = $doc->importNode($domnode, true);
		$domnode = $doc->appendChild($domnode);
		echo $doc->saveXML();
	}
	
	public function confirm(){
		$this->disableLayout();
		App::getModel('subpagelayout')->importSubpagesForView();
	}

}