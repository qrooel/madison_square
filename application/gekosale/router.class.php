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
 * $Id: router.class.php 438 2011-08-27 09:29:36Z gekosale $
 */

class Router
{
	
	const FRONTEND_PANE = 'frontend';
	const ADMIN_PANE = 'adminside';
	
	protected $registry;
	
	protected $path;
	
	protected $args = array();
	
	public $modelFile;
	
	protected $model;
	
	protected $action = 'index';
	
	protected $param = Array();
	
	protected $parsedURL;
	
	protected $adminitrativeMode = 0;
	
	protected $_adminPane = '';
	
	protected $__MAGIC = Array(
		'logout;'
	);
	
	protected $__magicLock = NULL;
	
	protected $script = '/';
	
	protected $exceptionModel;
	
	protected $encryptionMode = NULL;
	
	protected $baseController;
	
	protected $baseControllerFullName;
	
	protected $controllerseo;

	public function __construct ($registry)
	{
		$this->registry = $registry;
		$this->path = ROOTPATH . 'plugin';
	}

	protected function setControllerFullName ($controllerName)
	{
		if (strpos($controllerName, 'Controller') === FALSE){
			$controllerName .= 'Controller';
		}
		return $controllerName;
	}

	public function controllerLoader ($controller = NULL, $action = NULL, $generateContent = TRUE)
	{
		
		if ($controller === NULL){
			$controller = $this->baseController;
		}
		else{
			if ($this->getAdministrativeMode() == 0){
				$controller = $this->getOrginalNameForController($controller);
			}
		}
		if ($action === NULL){
			$action = $this->action;
		}
		if (isset($this->parsedURL['mode']) && $this->parsedURL['mode'] == 'admin' && ($this->registry->session->getActiveUserid() == 0)){
			$this->registry->session->setActiveAdminLogin(1);
			App::redirect('login');
		}
		if (isset($this->parsedURL['controller']) && $this->parsedURL['controller'] == __ADMINPANE__ && ($this->registry->session->getActiveUserid() == 0)){
			$this->registry->session->setActiveAdminLogin(1);
			App::redirect('login');
		}
		if (isset($this->parsedURL['controller']) && $this->parsedURL['controller'] == __ADMINPANE__ && ($this->registry->session->getActiveUserid() > 0)){
			App::redirect(__ADMINPANE__ . '/mainside');
		}
		$controller = str_replace('Controller', '', $controller);
		$controllerFullName = $this->setControllerFullName($controller);
		$namespaces = $this->registry->loader->getNamespaces();
		switch ($this->getAdministrativeMode()) {
			
			case 1:
				if ($this->registry->session->getActiveUserid() == 0){
					App::redirect('login');
				}
				
				try{
					$this->registry->right->checkPermission($controller, $this->action, App::getModel('users')->getLayerIdByViewId(Helper::getViewId()));
				}
				catch (Exception $e){
					if (App::getRegistry()->router->getCurrentController() !== 'permissionerror'){
						App::redirect(__ADMINPANE__ . '/permissionerror');
					}
				}
				
				foreach ($namespaces as $namespace){
					if (is_file(ROOTPATH . 'plugin' . DS . 'admin' . DS . $namespace . DS . strtolower($controller . DS . 'controller' . DS . $controller . '.php'))){
						$controllerFile = ROOTPATH . 'plugin' . DS . 'admin' . DS . $namespace . DS . strtolower($controller . DS . 'controller' . DS . $controller . '.php');
						$controllerType = 'admin';
						$controllerDirectory = ROOTPATH . 'plugin' . DS . 'admin' . DS . $namespace . DS . $controller;
						$controllerNamespace = $namespace;
						break;
					}
				}
				break;
			case 0:
				if ($this->registry->session->getActiveClientid() == 0){
					if ($this->registry->session->getActiveForceLogin() == 1 && isset($this->parsedURL['controller']) && ! in_array($this->parsedURL['controller'], Array(
						'clientlogin',
						'forgotpassword',
						'registrationcart',
						'login',
						'forgotlogin'
					))){
						App::redirect(App::getRegistry()->core->getControllerNameForSeo('clientlogin'));
					}
				}
				foreach ($namespaces as $namespace){
					if (is_file(ROOTPATH . 'plugin' . DS . 'frontend' . DS . $namespace . DS . strtolower($controller . DS . 'controller' . DS . $controller . '.php'))){
						$controllerFile = ROOTPATH . 'plugin' . DS . 'frontend' . DS . $namespace . DS . strtolower($controller . DS . 'controller' . DS . $controller . '.php');
						$controllerType = 'frontend';
						$controllerDirectory = ROOTPATH . 'plugin' . DS . 'frontend' . DS . $namespace . DS . $controller;
						$controllerNamespace = $namespace;
						break;
					}
				}
		}
		
		if (! isset($controllerFile) || is_file($controllerFile) == false){
			throw new $this->exceptionModel($this->registry->core->getMessage('TXT_CONTROLLER_NOT_EXISTS') . ': ' . $controllerFullName);
		}
		require_once ($controllerFile);
		$controllerObject = new $controllerFullName($this->registry);
		$controllerObjectContent = $controllerFullName . 'Content';
		if (is_callable(Array(
			$controllerFullName,
			$action
		)) == false){
			$action = 'index';
		}
		else{
			$action = $this->action;
		}
		$designPath = ROOTPATH . 'design' . DS . $controllerType . DS . $controllerNamespace . DS . strtolower($controller . DS . $action . DS);
		$controllerObject->setDesignPath($designPath);
		$controllerObject->setControllerDirectory(ROOTPATH . 'design' . DS . $controllerType . DS . $controllerNamespace . DS . $controller . DS);
		$this->registry->$controllerFullName = $controllerObject;
		if ($generateContent === TRUE){
			ob_start();
			$controllerObject->$action();
			$this->registry->$controllerObjectContent = ob_get_contents();
			ob_end_clean();
		}
		return $controllerObject;
	}

	public function getControllerContent ($controller)
	{
		$this->baseControllerLoader($controller);
		return $this->baseControllerContent[$controller];
	}

	protected function getXajaxMethodsForFrontend ()
	{
		$Data = Array(
			'changeLanguage' => Array(
				'model' => 'language',
				'method' => 'changeAJAXLanguageAboutView'
			),
			'changeCurrency' => Array(
				'model' => 'language',
				'method' => 'changeAJAXCurrencyView'
			),
			'changeCurrency' => Array(
				'model' => 'language',
				'method' => 'changeAJAXCurrencyView'
			),
			'updateCartPreview' => Array(
				'model' => 'cart',
				'method' => 'updateCartPreview'
			),
			'addNewsletter' => Array(
				'model' => 'newsletter',
				'method' => 'addAJAXClientAboutNewsletter'
			)
		);
		
		return $Data;
	}

	protected function getXajaxMethodsForAdmin ()
	{
		$Data = Array(
			'ChangeInterfaceLanguage' => Array(
				'model' => 'language',
				'method' => 'changeLanguage'
			),
			'ChangeActiveView' => Array(
				'model' => 'view',
				'method' => 'changeActiveView'
			)
		);
		
		return $Data;
	}

	public function setStaticTemplateVariables ()
	{
		if ($this->adminitrativeMode == 0){
			$link = $this->_adminPane = '';
		}
		else{
			$link = $this->_adminPane = __ADMINPANE__ . '/';
		}
		
		$languageModel = App::getModel('language');
		$languages = $languageModel->getLanguages();
		
		$cartModel = App::getModel('cart');
		
		$session = $this->registry->session;
		
		$this->layer = $this->registry->loader->getCurrentLayer();
		
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
			$this->registry->template->assign('SSLNAME', 'https://');
		}
		else{
			$this->registry->template->assign('SSLNAME', 'http://');
		}
		$this->registry->template->assign('URL', App::getURLAdress() . $link);
		$this->registry->template->assign('CURRENT_URL', App::getCurrentURLAdress());
		$this->registry->template->assign('DESIGNPATH', DESIGNPATH);
		$this->registry->template->assign('CURRENT_CONTROLLER', $this->baseController);
		$this->registry->template->assign('CURRENT_ACTION', $this->action);
		$this->registry->template->assign('CURRENT_PARAM', $this->registry->core->getParam());
		$this->registry->template->assign('SHOP_NAME', $this->registry->session->getActiveShopName());
		if ($this->adminitrativeMode == 0){
			$client = App::getModel('client')->getClient();
			
			$productCart = $cartModel->getShortCartList();
			$productCart = $cartModel->getProductCartPhotos($productCart);
			$this->registry->template->assign('SHOP_LOGO', $this->layer['photoid']);
			$logo = '_images_frontend/core/logos/' . $this->layer['photoid'];
			$size = @getimagesize(ROOTPATH . 'design/' . $logo);
			$path = DESIGNPATH . $logo;
			$width = isset($size[0]) ? $size[0] : 150;
			$height = isset($size[1]) ? $size[1] : 100;
			$logoCss = "a.logo{float:left;width:{$width}px;height:{$height}px;text-indent:-999px;margin-top:25px;background: url('{$path}') no-repeat;}";
			$this->registry->template->assign('logoCSS', $logoCss);
			$this->registry->template->assign('FAVICON', $this->layer['favicon']);
			$this->registry->template->assign('faceboookappid', $this->layer['faceboookappid']);
			$this->registry->template->assign('enableregistration', $this->layer['enableregistration']);
			$this->registry->template->assign('client', $client);
			$this->registry->template->assign('clientdata', $client);
			$this->registry->template->assign('showtax', $session->getActiveShowTax());
			$this->registry->template->assign('currencySymbol', $session->getActiveCurrencySymbol());
			$this->registry->template->assign('count', $cartModel->getProductAllCount());
			$this->registry->template->assign('globalPrice', $cartModel->getGlobalPrice());
			$this->registry->template->assign('productCart', $productCart);
			$this->registry->template->assign('language', $session->getActiveLanguageId());
			$this->registry->template->assign('languageCode', $session->getActiveLanguage());
			$this->registry->template->assign('languageFlag', $languageModel->getLanguages());
			$this->registry->template->assign('currencies', $languageModel->getAllCurrenciesForView());
			$this->registry->template->assign('breadcrumb', App::getModel('breadcrumb')->getPageLinks());
			$this->registry->template->assign('contentcategory', App::getModel('staticcontent')->getContentCategoriesTree());
			$this->registry->template->assign('gacode', $this->layer['gacode']);
			$this->registry->template->assign('gapages', $this->layer['gapages']);
			$this->registry->template->assign('gatransactions', $this->layer['gatransactions']);
			$this->registry->template->assign('enablerating', $this->layer['enablerating']);
			$this->registry->template->assign('enableopinions', $this->layer['enableopinions']);
			$this->registry->template->assign('enabletags', $this->layer['enabletags']);
			$this->registry->template->assign('confirmorder', $this->layer['confirmorder']);
			$this->registry->template->assign('enablerss', $this->layer['enablerss']);
			$this->registry->template->assign('catalogmode', $this->layer['catalogmode']);
			$this->registry->template->assign('cartpreview', $cartModel->getCartPreviewTemplate());
			if ($this->layer['cartredirect'] != ''){
				$this->registry->template->assign('cartredirect', App::getURLAdress() . App::getRegistry()->core->getControllerNameForSeo($this->layer['cartredirect']));
			}
			else{
				$this->registry->template->assign('cartredirect', '');
			}
			$this->registry->template->assign('categories', App::getModel('CategoriesBox')->getCategoriesTree(2));
			if ($this->registry->router->getCurrentController() == 'news'){
				$this->registry->template->assign('metadata', App::getModel('news')->getMetadataForNews());
			}
			elseif ($this->registry->router->getCurrentController() == 'productcart'){
				$this->registry->template->assign('metadata', App::getModel('product')->getMetadataForProduct());
			}
			elseif ($this->registry->router->getCurrentController() == 'categorylist'){
				$this->registry->template->assign('metadata', App::getModel('categorylist')->getMetadataForCategory());
			}
			elseif ($this->registry->router->getCurrentController() == 'staticcontent'){
				$this->registry->template->assign('metadata', App::getModel('staticcontent')->getMetaData($this->registry->core->getParam()));
			}
			elseif ($this->registry->router->getCurrentController() == 'producerlist'){
				$this->registry->template->assign('metadata', App::getModel('producerlistbox')->getProducerBySeo($this->registry->core->getParam()));
			}
			else{
				$this->registry->template->assign('metadata', App::getModel('seo')->getMetadataForPage());
			}
			
			$methods = $this->getXajaxMethodsForFrontend();
			foreach ($methods as $xajaxMethodName => $xajaxMethodParams){
				$this->registry->xajax->registerFunction(array(
					$xajaxMethodName,
					App::getModel($xajaxMethodParams['model']),
					$xajaxMethodParams['method']
				));
			}
		
		}
		else{
			$this->registry->template->assign('user_name', App::getModel('users')->getUserFullName());
			$this->registry->template->assign('user_id', App::getModel('users')->getActiveUserid());
			$this->registry->template->assign('menu', App::getModel('menu')->getBlocks());
			$this->registry->template->assign('language', $this->registry->session->getActiveLanguageId());
			$this->registry->template->assign('globalsettings', $this->registry->session->getActiveGlobalSettings());
			$this->registry->template->assign('languages', json_encode($languages));
			$this->registry->core->setAdminStoreConfig();
			if ($this->registry->session->getActiveShopUrl() != ''){
				$this->registry->template->assign('FRONTEND_URL', 'http://' . $this->registry->session->getActiveShopUrl());
			}
			else{
				$this->registry->template->assign('FRONTEND_URL', App::getURLAdress());
			}
			$this->registry->template->assign('appversion', $session->getActiveAppVersion());
			$methods = $this->getXajaxMethodsForAdmin();
			foreach ($methods as $xajaxMethodName => $xajaxMethodParams){
				$this->registry->xajax->registerFunction(array(
					$xajaxMethodName,
					App::getModel($xajaxMethodParams['model']),
					$xajaxMethodParams['method']
				));
			}
		}
		$this->registry->template->assign('views', App::getModel('view')->getViews());
		$this->registry->template->assign('view', Helper::getViewId());
		$this->registry->template->assign('viewid', Helper::getViewId());
	}

	public function getLastControllerContent ()
	{
		$controller = $this->setControllerFullName($this->baseController) . 'Content';
		return $this->registry->$controller;
	}

	public function modelLoader ($name = NULL)
	{
		$controller = $this->baseController;
		if ($this->model == ''){
			$this->model = $this->baseController;
		}
		
		if ($name != NULL){
			$name = str_replace('Model', '', $name);
			$name = (explode('/', $name));
			if (isset($name[1])){
				$controller = $name[0];
				$model = $name[1];
			}
			else{
				$model = $name[0];
			}
		}
		$modelFile = $this->getModelFile($controller, $model);
		if (! isset($modelFile[0]) || $modelFile[0] == false){
			print_r($modelFile);
			throw new Exception('Model file doesn\'t exists: ' . $model);
		}
		if (is_file($modelFile[0]) !== false){
			include_once $modelFile[0];
			
			$class = $model . 'Model';
			$objClassName = $modelFile[1] . '/' . $model . 'Model';
			if (class_exists($class)){
				try{
					return $this->registry->$objClassName = new $class($this->registry, $this->getModelFileFromArray($modelFile));
				}
				catch (Exception $e){
					throw new $this->exceptionModel($e->getMessage());
				}
			}
			else{
				throw new $this->exceptionModel('Class doesn\'t exists: ' . $class);
			}
		}
	}

	protected function getModelFileFromArray ($modelFile)
	{
		if (! empty($modelFile[0])){
			return $modelFile[0];
		}
		throw new Exception('No model file loaded to array.');
	}

	protected function getModelFile ($controller, $model)
	{
		$modelFile = NULL;
		$modelDirectory = NULL;
		$modeDirectory = NULL;
		$namespaces = $this->registry->loader->getNamespaces();
		
		switch ($this->adminitrativeMode) {
			case 0:
				foreach ($namespaces as $namespace){
					if (is_file($this->path . '/frontend/' . $namespace . '/' . strtolower($controller) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/frontend/' . $namespace . '/' . strtolower($controller) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $controller;
						break;
						break;
					}
					if (is_file($this->path . '/frontend/' . $namespace . '/' . strtolower($model) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/frontend/' . $namespace . '/' . strtolower($model) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $model;
						break;
						break;
					}
					if (is_file($this->path . '/super/' . $namespace . '/' . strtolower($controller) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/super/' . $namespace . '/' . strtolower($controller) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $controller;
						break;
						break;
					}
					if (is_file($this->path . '/super/' . $namespace . '/' . strtolower($model) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/super/' . $namespace . '/' . strtolower($model) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $model;
						break;
						break;
					}
				}
				break;
			case 1:
				foreach ($namespaces as $namespace){
					if (is_file($this->path . '/admin/' . $namespace . '/' . strtolower($controller) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/admin/' . $namespace . '/' . strtolower($controller) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $controller;
						break;
						break;
					}
					if (is_file($this->path . '/admin/' . $namespace . '/' . strtolower($model) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/admin/' . $namespace . '/' . strtolower($model) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $model;
						break;
						break;
					}
					if (is_file($this->path . '/super/' . $namespace . '/' . strtolower($controller) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/super/' . $namespace . '/' . strtolower($controller) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $controller;
						break;
						break;
					}
					if (is_file($this->path . '/super/' . $namespace . '/' . strtolower($model) . '/model/' . strtolower($model) . '.php')){
						$modelDirectory = $this->path . '/super/' . $namespace . '/' . strtolower($model) . '/model/';
						$modelFile = $modelDirectory . strtolower($model) . '.php';
						$modeDirectory = $model;
						break;
						break;
					}
				}
		}
		if ($modelFile === NULL){
			return false;
		}
		return Array(
			$modelFile,
			$modeDirectory
		);
	}

	protected function setBaseController ()
	{
		if (! empty($this->parsedURL)){
			if (isset($this->parsedURL['controller']) && ! empty($this->parsedURL['controller'])){
				$this->baseController = (isset($this->parsedURL['mode']) && $this->parsedURL['mode'] == 'admin') ? $this->parsedURL['controller'] : $this->getOrginalNameForController($this->parsedURL['controller']);
				isset($this->parsedURL['action']) ? $this->action = $this->parsedURL['action'] : $this->action = 'index';
			}
			else{
				$this->baseController = 'mainside';
			}
		}
		$this->baseControllerFullName = $this->baseController . 'Controller';
		if (isset($this->parsedURL['script']))
			$this->script = $this->parsedURL['script'];
		if (isset($this->parsedURL['param']))
			$this->param = $this->parsedURL['param'];
		isset($this->parsedURL['mode']) && $this->parsedURL['mode'] == 'admin' ? $this->setAdministrativeMode(1) : $this->setAdministrativeMode(0);
		$this->setCoreExceptionModel();
	}

	protected function setAdministrativeMode ($value = 0)
	{
		if ($value == 0 || $this->registry->session->getActiveUserid() === NULL){
			$this->adminitrativeMode = 0;
		}
		else{
			$this->adminitrativeMode = 1;
		}
	}

	public function getAdministrativeMode ()
	{
		return $this->adminitrativeMode;
	}

	public function getOrginalNameForController ($controllerName)
	{
		if (App::getRegistry()->router->getMode() == 0){
			if (($this->controllerseo = Cache::loadObject('controllerseo')) === FALSE){
				$this->registry->core->setSeoNames();
			}
			$flip = array();
			if (is_array($this->controllerseo)){
				$flip = array_flip($this->controllerseo);
			}
			if (! is_null($controllerName) && ($controllerName != '') && (isset($flip[$controllerName]))){
				return $flip[$controllerName];
			}
			else{
				return $controllerName;
			}
		}
		else{
			return $controllerName;
		}
	}

	protected function parseURL ()
	{
		foreach ($this->__MAGIC as $key){
			preg_match('/\/(' . $key . ')?$/', $_SERVER['REQUEST_URI'], $matches);
			if (isset($matches[1])){
				$this->__magicLock = $matches[1];
			}
		}
		if ($this->__magicLock == NULL){
			preg_match('/(?<script>[\/]?index.php[\/]?|[\/])?((?<mode>' . __ADMINPANE__ . ')[\/]?|[\/])?(?<controller>\w+)?[\/]?(?<action>' . implode('|', array_keys($this->registry->right->getAllRights())) . ')?[\/]?(?<param>[a-zA-Z0-9;,_\/\-=%?.&]*)?$/', REQUEST_URI, $matches);
			if (isset($matches['mode']) && $matches['mode'] == __ADMINPANE__){
				$matches['mode'] = 'admin';
			}
			else{
				unset($matches['mode']);
			}
			$this->parsedURL = $matches;
		}
		if (preg_match('/\.(html|php|asp|jsp)/', $this->parsedURL['param'])){
			$this->getMappedUrl(str_replace('/index.php/', '', $this->parsedURL[0]));
		}
	}

	public function getMappedUrl ($url)
	{
		$sql = 'SELECT controller, params FROM urlmap WHERE url = :url';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', $url);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->parsedURL['controller'] = $rs->getString('controller');
			$this->parsedURL['param'] = $rs->getString('params');
		}
	}

	protected function setCoreExceptionModel ()
	{
		if ($this->adminitrativeMode == 0){
			include_once ROOTPATH . 'application/gekosale/' . 'frontendexception.class.php';
			$this->exceptionModel = 'FrontendException';
		}
		if ($this->adminitrativeMode == 1){
			include_once ROOTPATH . 'application/gekosale/' . 'coreexception.class.php';
			$this->exceptionModel = 'CoreException';
		}
	}

	public function setVariables ()
	{
		$this->parseURL();
		if ($this->__magicLock != NULL){
			$this->doMagic();
		}
		$this->setBaseController();
	}

	protected function doMagic ()
	{
		switch ($this->__magicLock) {
			case 'logout;':
				if ($this->adminitrativeMode == 1){
					$this->registry->session->flush();
					$this->registry->session->setActiveAdminLogin(1);
					App::redirect('login');
				}
				else{
					$this->registry->session->flush();
					App::redirect('');
				}
				break;
		}
	}

	protected function getModel ($name)
	{
		$this->model = $name;
	}

	public function getPath ()
	{
		return $this->path;
	}

	public function getMode ()
	{
		return $this->adminitrativeMode;
	}

	public function getModeName ()
	{
		if ($this->adminitrativeMode == 1){
			return self::ADMIN_PANE;
		}
		return self::FRONTEND_PANE;
	
	}

	public function getAdminPaneName ()
	{
		return $this->_adminPane;
	}

	public function getFrontendPaneName ()
	{
		if ($this->adminitrativeMode != 0)
			return '';
		return self::FRONTEND_PANE;
	}

	public function getCurrentControllerPointer ()
	{
		$controller = $this->setControllerFullName($this->baseController);
		return $this->registry->$controller;
	}

	public function getParams ()
	{
		$clean = explode('?', (string) $this->param);
		if (count($clean) > 0){
			return (string) $clean[0];
		}
		else{
			return (string) $this->param;
		}
	
	}

	public function getCurrentController ()
	{
		if (isset($this->baseController) && $this->baseController != ''){
			return $this->baseController;
		}
	}

	public function getCurrentAction ()
	{
		if (isset($this->action) && $this->action != ''){
			return $this->action;
		}
		else{
			return 'index';
		}
	}
}