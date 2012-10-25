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
 * $Id: compressor.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

require_once ROOTPATH . 'lib' . DS . 'minify' . DS . 'class.CSSCompressor.php';
require_once ROOTPATH . 'lib' . DS . 'minify' . DS . 'class.JSCompressor.php';

class CompressorController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->namespace = $registry->loader->getCurrentNamespace();
		$this->config = Array(
			'charset' => 'utf-8', // kodowanie znaków
			'import_mode' => true, // włączanie wewnętrznych styli (@import)
			'clean_code' => true, // status czyszczenia kodu
			'compress_code' => true, // status kompresji kodu
			'cache_enabled' => true, // buforowanie po stronie serwera
			'cache_location' => ROOTPATH . 'cache' . DS, // folder dla cache
			'use_cache_browser' => true, // buforowanie po stronie klienta
			'time_cache_browser' => 3600, // czas trzymania w buforze (sekundy)
			'gzip_contents' => true, // kompresja gzip
			'gzip_level' => 6, // poziom kompresji gzip,
			'images_path' => DESIGNPATH
		);
	
	}

	public function index ()
	{
		$this->disableLayout();
		$type = (int) $this->registry->core->getParam();
		switch ($type) {
			case 1: //CSS
				$compressor = new CSSCompressor($this->config);
				$compressor->addFile($this->getFileForNamespace('static.css'));
				$compressor->addFile(ROOTPATH . 'design'.DS.'_js_libs'.DS.'fancybox'.DS.'jquery.fancybox-1.3.2.css');
				$compressor->addFile($this->getSchemeFile());
				$compressor->addFile($this->getFileForNamespace('scheme-new.css'));
				break;
			case 2: //JS
				$compressor = new JSCompressor($this->config);
				
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery-1.4.2.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery-ui-1.8.6.custom.min.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.easing.1.3.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.cookie.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.checkboxes.pack.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.resize.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/json2.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/base64.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/print.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.onkeyup.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/sexyalertbox.v1.2.jquery.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/fancybox/jquery.fancybox-1.3.2.pack.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/jquery.nivo.slider.js');
				$compressor->addFile(DESIGNPATH . '_js_libs/xajax/xajax_core.js');
				$compressor->addFile(DESIGNPATH . '_js_frontend/core/gekosale.js');
				$compressor->addFile(DESIGNPATH . '_js_frontend/core/init.js');
				break;
		}
		$compressor->showCode('infile');
		die();
	}

	protected function getSchemeFile ()
	{
		$viewid = Helper::getViewId();
		$namespace = $this->registry->loader->getCurrentNamespace();
		if (file_exists(ROOTPATH . 'design' . DS . '_css_frontend' . DS . $this->namespace . DS . $viewid . '.css')){
			return ROOTPATH . 'design' . DS . '_css_frontend' . DS . $this->namespace . DS . $viewid . '.css';
		}else{
			if (file_exists(ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $viewid . '.css')){
				return ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $viewid . '.css';
			}
		}
		return ROOTPATH . 'design' . DS . '_css_frontend' . DS.'core' .DS. 'scheme.css';
	}

	protected function getFileForNamespace ($css_file)
	{
		$mode = '_css_frontend';
		if (file_exists(ROOTPATH . 'design' . DS . $mode . DS . $this->namespace . DS . $css_file)){
			return ROOTPATH . 'design' . DS . $mode . DS . $this->namespace . DS . $css_file;
		}
		return ROOTPATH .  'design' . DS . '_css_frontend' . DS. 'core' .DS. $css_file;
	}
}
