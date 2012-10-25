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
 * $Id: model.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

abstract class Model
{
	
	protected $registry;
	protected $mode = NULL;
	protected $modelFilePath = NULL;
	protected $namespace = NULL;
	protected $component = NULL;
	protected $file = NULL;
	protected $mainPath = NULL;

	public function __construct ($registry, $modelFile = NULL)
	{
		$this->registry = $registry;
		$this->modelFilePath = $modelFile;
		preg_match('/(?<mode>[a-z]*)\/(?<namespace>[a-z]*)\/(?<component>[a-z]*)\/[a-z]*\/(?<file>[a-z]*.php)$/', $this->modelFilePath, $matches);
		if (isset($matches) && is_array($matches) && count($matches) > 0){
			$this->mode = $matches['mode'];
			$this->namespace = $matches['namespace'];
			$this->component = $matches['component'];
			$this->file = $matches['file'];
			$this->mainPath = $this->mode . '/' . $this->namespace . '/' . $this->component . '/';
		}
		$this->layer = $this->registry->loader->getCurrentLayer();
	}

	public function getName ()
	{
		return str_replace('model', '', strtolower(get_class($this)));
	}

	public function getFilePath ()
	{
		return $this->modelFilePath;
	}

	public function getDirPath ()
	{
		return $this->mainPath;
	}

	public function getMode ()
	{
		return $this->mode;
	}

	public function getNamespace ()
	{
		return $this->namespace;
	}

	public function getFileName ()
	{
		return $this->file;
	}
	
}