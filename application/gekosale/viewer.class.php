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
 * $Revision: 441 $
 * $Author: gekosale $
 * $Date: 2011-08-27 13:53:40 +0200 (So, 27 sie 2011) $
 * $Id: viewer.class.php 441 2011-08-27 11:53:40Z gekosale $ 
 */

class Viewer
{
	
	protected $registry;
	protected $mainDesignPath;
	protected $currentModeDirectory = '';
	protected $currentControllerPointer = NULL;
	protected $Templates = Array();
	protected $Headers = Array(
		'xml' => 'content-type: text/xml'
	);

	public function __construct (&$registry)
	{
		$this->mainDesignPath = 'design' . DS . '_tpl';
		$this->registry = $registry;
		$this->setCurrentModeDirectory();
	}

	public function systemLoader ()
	{
		if (App::getRegistry()->router->getMode() == 1){
			$this->Templates[] = $this->registry->template->fetchAdminHeader();
			$this->Templates[] = $this->registry;
			$this->Templates[] = $this->registry->template->fetchAdminFooter();
		}
		else{
			$this->addTemplate('header.tpl');
			$this->Templates[] = $this->registry;
			$this->addTemplate('footer.tpl');
		}
	}

	protected function setCurrentModeDirectory ()
	{
		if (App::getRegistry()->router->getMode() == 1){
			$this->currentModeDirectory = ROOTPATH . $this->mainDesignPath . DS . 'admin';
		}
		else{
			$this->currentModeDirectory = ROOTPATH . $this->mainDesignPath . DS . 'frontend';
		}
	}

	public function addAdminTemplate ($type)
	{
		
	}
	
	public function addTemplate ($fileName)
	{
		$namespace = $this->registry->loader->getCurrentNamespace();
		$namespaces = $this->registry->loader->getNamespaces();
		$filePath = NULL;
		
		if (is_file($this->currentModeDirectory . DS . $namespace . DS . $fileName)){
			$filePath = $this->currentModeDirectory . DS . $namespace . DS . $fileName;
		}
		else{
			foreach ($namespaces as $namespace){
				if (is_file($this->currentModeDirectory . DS . $namespace . DS . $fileName)){
					$filePath = $this->currentModeDirectory . DS . $namespace . DS . $fileName;
				}
			}
		}
		if ($filePath === NULL){
			throw new Exception('Template file doesn\'t exists: ' . $this->currentModeDirectory . $fileName);
		}
		$this->Templates[] = $filePath;
	}

	public function drawTemplates ()
	{
		$html = '';
		foreach ($this->Templates as $template){
			if (is_object($template)){
				$html .= App::getRegistry()->router->getLastControllerContent();
			}
			else{
				if ($this->currentControllerPointer === NULL){
					$this->currentControllerPointer = App::getRegistry()->router->getCurrentControllerPointer();
				}
				if (is_object($this->currentControllerPointer) && $this->currentControllerPointer->getLayoutStatus() !== false){
					if (App::getRegistry()->router->getMode() == 1){
						$html .= $template;
					}else{
						$html .= $this->registry->template->fetch($template);
					}
				}
			}
		}
		return $html;
	}

	public function setHeader ($name)
	{
		if (isset($this->Headers[$name])){
			header($this->Headers[$name]);
		}
	}
}