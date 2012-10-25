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
 * $Id: namespaced.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class Namespaced
{
	
	protected $registry;
	
	protected $namespaces;
	
	protected $defaultNamespace;
	
	protected $currentNamespace;

	public function __construct ($registry)
	{
		$this->registry = $registry;
	}

	public function loadNamespaces ($namespaces)
	{
		$this->namespaces = $namespaces;
		if (! isset($namespaces['default_namespace'])){
			throw new CoreException('Default namespace not set.');
		}
		$this->defaultNamespace = $namespaces['default_namespace'];
	}

	public function getDefaultNamespace ()
	{
		return $this->defaultNamespace;
	}

	public function namespaceIfExists ($name)
	{
		if (! isset($this->$namespaces[$name])){
			throw new CoreException('Namespace doesn\'t exists: ' . $name);
		}
		return $name;
	}

	public function getControllerNamespace ($name)
	{
		return $this->namespaceSeeker($name, 'controller');
	}

	public function getModelNamespace ($name)
	{
		return $this->namespaceSeeker($name, 'model');
	}

	public function getCurrentControllerNamespace ()
	{
		return $this->namespaceSeeker(App::getRegistry()->router->getCurrentController(), 'controller');
	}

	protected function namespaceSeeker ($name, $type)
	{
		if (App::getRegistry()->router->getMode() == 1){
			$layer = App::getAdminPaneName();
		}
		if (App::getRegistry()->router->getMode() == 0){
			$layer = App::getRegistry()->router->getFrontendPaneName();
		}
		foreach ($this->getNamespaces() as $key){
			if (isset($this->namespaces[$key])){
				$tmp = (array) $this->namespaces[$key]->$layer;
				if (isset($tmp[$type])){
					if (! is_array($tmp[$type])){
						$tmp[$type] = (array) $tmp[$type];
					}
					if (in_array($name, $tmp[$type])){
						return $key;
					}
				}
			}
		}
		return $this->getDefaultNamespace();
	}

	public function getNamespaces ()
	{
		if (! isset($this->namespaces['namespace'])){
			return Array();
		}
		if (! is_array($this->namespaces['namespace'])){
			return Array(
				$this->namespaces['namespace']
			);
		}
		return $this->namespaces['namespace'];
	}

	public function setDefaultNamespace ($namespace)
	{
		$this->defaultNamespace = $namespace;
	}
}