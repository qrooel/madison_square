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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: controller.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

abstract class Controller
{
	
	const DEFAULT_ACTION = 'index';
	
	const LAYOUT_TEMPLATE = 'design/_tpl/frontend/core/layout.tpl';
	
	const ITEMS_TEMPLATE = 'design/_tpl/frontend/core/items.tpl';
	
	/*
	* @registry object
	*/
	protected $registry;
	
	protected $designPath;
	
	protected $printLayout = true;
	
	protected $helper;
	
	protected $controllerDirectory;
	
	protected $__seo;

	public function __construct ($registry, $designPath = NULL)
	{
		$this->registry = $registry;
		if ($designPath != NULL){
			$this->setDesignPath($designPath);
		}
		$this->layer = $this->registry->loader->getCurrentLayer();
	}

	public function setControllerDirectory ($directory)
	{
		$this->controllerDirectory = $directory;
	}

	public function setDesignPath ($path)
	{
		$this->designPath = $path;
	}

	/**
	 * @all controllers must contain an index method
	 */
	abstract function index ();

	public function getDesignPath ()
	{
		return $this->designPath;
	}

	public function getLanguagePrefixForFileName ()
	{
		return $this->registry->session->getActiveLanguage();
	}

	public function enableLayout ()
	{
		$this->printLayout = true;
	}

	public function disableLayout ()
	{
		$this->printLayout = false;
	}

	public function getLayoutStatus ()
	{
		return $this->printLayout;
	}

	public function Render ($layout, $action = 'index')
	{
		$layoutBoxes = App::getModel('LayoutGenerator/LayoutGenerator');
		$layoutBoxes->LoadLayout($layout);
		$this->registry->template->assign('pagescheme', $layoutBoxes->GetTemplateData('content', $action));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadDefaultTemplate());
	}

	public function loadTemplate ($fileName, $global = false)
	{
		if ($global == false){
			$defaultPath = $this->getDesignPath() . $fileName;
			
			$namespace = $this->registry->loader->getCurrentNamespace();
			$namespaceFile = str_replace($this->registry->loader->getSystemNamespace(), $namespace, $defaultPath);
			if (is_file($namespaceFile)){
				return $namespaceFile;
			}
			elseif (is_file($defaultPath)){
				return $this->getDesignPath() . $fileName;
			}
		}
		else{
			$namespace = $this->registry->loader->getCurrentNamespace();
			if (is_file(ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . $fileName)){
				return ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . $fileName;
			}
			return ROOTPATH . self::ITEMS_TEMPLATE;
		}
	}

	public function loadDefaultTemplate ()
	{
		$namespace = $this->registry->loader->getCurrentNamespace();
		if (is_file(ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'layout.tpl')){
			return ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'layout.tpl';
		}
		return ROOTPATH . self::LAYOUT_TEMPLATE;
	}

	public function getName ($fullName = NULL)
	{
		if ($fullName === NULL){
			return strtolower(str_replace('Controller', '', get_class($this)));
		}
		return get_class($this);
	}

}