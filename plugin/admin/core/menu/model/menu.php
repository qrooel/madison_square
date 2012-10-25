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
 * $Id: menu.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class MenuModel extends Model
{

	protected function createMenu ()
	{
		$this->xmlParser = new xmlParser();
		$namespace = $this->registry->loader->getCurrentNamespace();
		if (is_file(ROOTPATH . 'config' . DS . $namespace . DS . 'admin_menu.xml')){
			$this->xmlParser->parseFast(ROOTPATH . 'config' . DS . $namespace . DS . 'admin_menu.xml');
		}
		else{
			$this->xmlParser->parseFast(ROOTPATH . 'config/admin_menu.xml');
		}
		$menuXML = $this->xmlParser->getValue('menu', false);
		$this->xmlParser->flush();
		$menu = Array();
		foreach ($menuXML->block as $block){
			if (is_object($block->element)){
				foreach ($block->element as $element){
					if (isset($element->global) && (string) $element->global == '0'){
						$global = 0;
					}
					else{
						$global = 1;
					}
					if (($global == 0 && Helper::getViewId() > 0) || $global == 1){
						if ($this->registry->right->checkMenuPermission((string) $element->controller, 'index', App::getModel('users')->getLayerIdByViewId(Helper::getViewId())) !== false){
							if (is_object($element->subelement) && ! empty($element->subelement)){
								$sub = Array();
								$sort_subelement = Array();
								foreach ($element->subelement as $subelement){
									$sub[] = Array(
										'name' => $this->registry->core->getMessage((string) $subelement->name),
										'link' => (string) $subelement->link,
										'sort_order' => (int) $subelement->sort_order,
										'controller' => (string) $subelement->controller
									);
									$sort_subelement[] = (int) $subelement->sort_order;
								}
							}
							else{
								$sub = Array();
								$sort_subelement = Array();
							}
							if (isset($element->subelement) && ! empty($element->subelement)){
								$elem[] = Array(
									'name' => $this->registry->core->getMessage((string) $element->name),
									'link' => (string) $element->link,
									'sort_order' => (int) $element->sort_order,
									'controller' => (string) $element->controller,
									'subelement' => $sub
								);
								$sort_element[] = (int) $element->sort_order;
							}
							else{
								$elem[] = Array(
									'name' => $this->registry->core->getMessage((string) $element->name),
									'link' => (string) $element->link,
									'sort_order' => (int) $element->sort_order,
									'controller' => (string) $element->controller
								);
								$sort_element[] = (int) $element->sort_order;
							}
						
						}
					}
				}
				if (isset($elem)){
					if (isset($element->subelement) && ! empty($element->subelement)){
						array_multisort($sub, SORT_ASC, SORT_STRING, $sort_subelement, SORT_ASC);
						$menu[] = Array(
							'name' => $this->registry->core->getMessage((string) $block->name),
							'link' => (string) $block->link,
							'icon' => (string) $block->icon,
							'sort_order' => (int) $block->sort_order,
							'elements' => $elem
						);
					}
					else{
						array_multisort($elem, SORT_ASC, SORT_STRING, $sort_element, SORT_ASC);
						$menu[] = Array(
							'name' => $this->registry->core->getMessage((string) $block->name),
							'link' => (string) $block->link,
							'icon' => (string) $block->icon,
							'sort_order' => (int) $block->sort_order,
							'elements' => $elem
						);
					}
				}
				else{
					$menu[] = Array(
						'name' => $this->registry->core->getMessage((string) $block->name),
						'link' => (string) $block->link,
						'icon' => (string) $block->icon,
						'sort_order' => (int) $block->sort_order
					);
				}
				
				$sort_block[] = (int) $block->sort_order;
				if (isset($elem)){
					unset($elem);
					unset($sort_element);
					unset($sub);
					unset($subelement);
					unset($sort_subelement);
				}
			}
		}
		array_multisort($menu, SORT_ASC, SORT_STRING, $sort_block, SORT_ASC);
		$Data = Array();
		foreach ($menu as $key => $val){
			$Data[] = $menu[$key];
		}
		$event = new sfEvent($this, 'admin.menu.create');
		$this->registry->dispatcher->filter($event, $Data);
		$arguments = $event->getReturnValues();
		foreach ($arguments as $key => $item){
			if (isset($item['item'])){
				$Data[$item['item']]['elements'][] = $item;
			}
			else{
				$Data[] = $item;
			}
		}
		$this->registry->session->setActiveMenuData($Data);
	}

	public function getBlocks ()
	{
		if ($this->registry->session->getActiveMenuData() == NULL){
			$this->createMenu();
		}
		return $this->registry->session->getActiveMenuData();
	}

	public function flushMenu ()
	{
		$this->registry->session->setActiveMenuData(NULL);
	}
}