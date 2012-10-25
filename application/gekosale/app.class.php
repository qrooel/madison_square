<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
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
 * $Id: app.class.php 438 2011-08-27 09:29:36Z gekosale $
 */
class App {
	protected $URI = Array();
	protected $registry;

	public function __construct () {
		echo 'app';
	}

	public static function getModel ($index) {
		global $registry;
		
		if (is_object($registry->$index)){
			return $registry->$index;
		}
		if (strpos($index, 'Model') === FALSE){
			$indexModel = $index . 'Model';
			if (is_object($registry->$indexModel)){
				return $registry->$indexModel;
			}
		}
		try{
			return $registry->router->modelLoader($index);
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
	}

	public static function getController ($index, $action = NULL, $generateContent = TRUE) {
		global $registry;
		
		if (strpos($index, 'Controller') === FALSE){
			$index .= 'Controller';
		}
		if (is_object($registry->$index)){
			return $registry->$index;
		}
		else{
			try{
				return $registry->router->controllerLoader($index, $action, $generateContent);
			}
			catch (Exception $e){
				throw new CoreException($e->getMessage());
			}
		}
	}

	public static function redirect ($path = false) {
		if ($path == false){
			header('Location: ' . self::getURLAdress());
		}
		else{
			header('Location: ' . self::getURLAdress() . $path);
		}
		die();
	}

	public static function redirectSeo ($url) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $url);
		header('Connection: Close');
		die();
	}

	public static function setUrl () {
		if (defined('__SCRIPT_USE'))
			return;
		global $URI;
		$server_protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
		$URI = Array(
			'protocol' => strtolower($server_protocol[0]),
			'protocol_ver' => $server_protocol[1],
			'host' => $_SERVER['HTTP_HOST'],
			'script_name' => $_SERVER['SCRIPT_NAME'],
			'script' => $_SERVER['REQUEST_URI']
		);
	}

	public static function checkSSL () {
		global $registry;
		$sslpages = array(
			'clientlogin',
			'registrationcart',
			'cart',
			'forgotpassword',
			'contact'
		);
		if (in_array($registry->router->getCurrentController(), $sslpages)){
			if (! isset($_SERVER['HTTPS']) && SSLNAME == 'https'){
				App::setHttps();
				App::redirect($registry->core->getControllerNameForSeo($registry->router->getCurrentController()));
			}
		}
		if ($registry->router->getCurrentController() == 'payment' && $registry->router->getCurrentAction() == 'accept'){
			if (! isset($_SERVER['HTTPS']) && SSLNAME == 'https'){
				App::setHttps();
				App::redirect('payment/accept');
			}
		}
	}

	public static function getHost ($setProtocol = null) {
		global $URI;
		$host = $URI['host'];
		if (substr($host, - 2) == '//'){
			$host = substr($host, 0, - 1);
		}
		if ($setProtocol !== NULL){
			return strtolower($URI['protocol']) . '://' . $host;
		}
		return $host;
	}

	public static function setHttps () {
		global $URI;
		$URI['protocol'] = SSLNAME;
	}

	public static function setHttp () {
		global $URI;
		$URI['protocol'] = 'http';
	}

	public static function getHttps () {
		global $URI;
		return $URI['protocol'];
	}

	public static function getURLAdress () {
		global $registry;
		if (isset($registry->config['force_mod_rewrite']) && (int) $registry->config['force_mod_rewrite'] == 1){
			$file = '';
		}
		else{
			if (function_exists('apache_get_modules')){
				$modules = apache_get_modules();
				$mod_rewrite = in_array('mod_rewrite', $modules);
			}
			elseif (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed')){
				$mod_rewrite = true;
			}
			elseif (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'nginx')){
				$mod_rewrite = true;
			}
			elseif (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'ZenServer')){
				$mod_rewrite = true;
			}
			else{
				$mod_rewrite = getenv('HTTP_MOD_REWRITE') == 'On' ? true : false;
			}
			
			if ($mod_rewrite){
				if (is_file(ROOTPATH . '.htaccess')){
					$file = '';
				}
				else{
					$file = 'index.php/';
				}
			}
			else{
				$file = 'index.php/';
			}
		}
		
		if (LOCAL_CATALOG != ''){
			return App::getHost(1) . '/' . LOCAL_CATALOG . '/' . $file;
		}
		return App::getHost(1) . '/' . $file;
	}

	public static function getCurrentURLAdress () {
		return (isset($_SERVER['SCRIPT_URI'])) ? $_SERVER['SCRIPT_URI'] : $_SERVER['PHP_SELF'];
	}

	public static function getURLAdressWithAdminPane () {
		return self::getURLAdress() . App::getAdminPaneName();
	}

	public static function getURLForDesignDirectory () {
		if (LOCAL_CATALOG != ''){
			return App::getHost(1) . '/' . LOCAL_CATALOG . '/design/';
		}
		return App::getHost(1) . '/design/';
	}

	public static function getURL () {
		global $URI;
		return strtolower($URI['protocol']) . '://' . $URI['host'] . $URI['script'];
	}

	public static function getProtocolVersion () {
		global $URI;
		return $URI['protocol_ver'];
	}

	public function getOfflineMessage () {
		global $registry;
		
		$sql = 'SELECT offlinetext FROM view WHERE idview = :viewid';
		$stmt = $registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			return $rs->getString('offlinetext');
		}
	}

	public static function Run () {
		global $registry;
		
		$registry = new registry();
		if (! @include_once (ROOTPATH . 'config' . DS . 'settings.php')){
			App::setUrl();
			include (ROOTPATH . 'includes' . DS . 'install.php');
			die();
		}
		$registry->config = $Config;
		DEFINE('SSLNAME', (isset($Config['ssl']) && $Config['ssl'] == 1) ? 'https' : 'http');
		App::setUrl();
		
		$adminlink = $Config['admin_panel_link'];
		DEFINE('__ADMINPANE__', is_array($adminlink) ? $adminlink[0] : $adminlink);
		$registry->router = new Router($registry);
		try{
			$registry->db = Db::getInstance($Config['database']);
		}
		catch (Exception $e){
			echo $e->getMessage();
			die();
		}
		$registry->session = new session($registry);
		Cache::setDefaultStoreType(Cache::SESSION);
		$registry->loader = new Loader($registry);
		$registry->session->setActiveShopEmail($Config['phpmailer']['FromEmail']);
		$registry->core = new Core($registry);
		$registry->session->setActiveEncryptionKeyValue((string) $Config['client_data_encription_string']);
		$registry->template = new template($registry);
		$registry->xajax = new Xajax();
		$registry->xajaxInterface = new XajaxInterface();
		$registry->right = new Right($registry);
		$registry->session->clearTemp();
		$registry->router->setVariables();
		$registry->viewer = new Viewer($registry);
		$registry->dispatcher = new sfEventDispatcher();
		$registry->loader->registerEvents();
		DEFINE('URL', App::getHost(1) . '/' . LOCAL_CATALOG);
		App::checkSSL();
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
			DEFINE('DESIGNPATH', str_replace('http://', 'https://', App::getURLForDesignDirectory()));
		}
		else{
			DEFINE('DESIGNPATH', App::getURLForDesignDirectory());
		}
		$pages = array(
			'login',
			'forgotlogin',
			'subiektgt'
		);
		$registry->router->setStaticTemplateVariables();
		$registry->router->controllerLoader();
		$registry->viewer->systemLoader();
		$html = $registry->viewer->drawTemplates();
		if ($registry->router->getMode() == 1){
			App::addUserHistorylog($registry->session->getActiveUserid());
		}
		else{
			if (count($registry->loader->getCurrentLayer()) == 0){
				if ($registry->router->getMode() == 0 && ! in_array($registry->router->getCurrentController(), $pages)){
					App::getModel('template')->assign('SHOP_NAME', App::getRegistry()->session->getActiveShopName());
					App::getModel('template')->assign('error', 'Konfiguracja sklepu jest niepoprawna. Sprawdź adresy WWW podane w <strong>Konfiguracja -> Multistore -> Sklepy</strong>. <br />Należy podać adresy z www i bez www na początku.');
					App::getModel('template')->assign('BASE_URL', App::getURLAdress());
					App::getModel('template')->display(ROOTPATH . 'design/frontend/core/error/index/index.tpl');
					die();
				}
			}
		}
		
		if ($registry->router->getMode() == 0 && ! in_array($registry->router->getCurrentController(), $pages)){
			if ($registry->session->getActiveShopOffline() == 1 && $registry->session->getActiveUserid() == NULL){
				$html = App::getOfflineMessage();
			}
		}
		
		echo $html;
	}

	public static function getRegistry () {
		global $registry;
		return $registry;
	}

	public static function addClientHistorylog () {
		global $registry;
		
		$sql = 'INSERT INTO clienthistorylog (
					clientid, 
					sessionid, 
					URL, 
					viewid
				)
				VALUES(
					:clientid, 
					:sessionid, 
					:URL, 
					:viewid
				)';
		$stmt = $registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $registry->session->getActiveClientid());
		$stmt->setString('sessionid', session_id());
		$stmt->setString('URL', $_SERVER['REQUEST_URI']);
		if (Helper::getViewId() > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($registry->core->getMessage('ERR_CLIENT_HISTORY_LOG'), 21, $e->getMessage());
		}
		return true;
	}

	public static function addUserHistorylog ($userId) {
		global $registry;
		
		$sql = 'INSERT INTO userhistorylog (userid, sessionid, URL, viewid)
				VALUES(:userid, :sessionid, :URL, :viewid)';
		$stmt = $registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $userId);
		$stmt->setString('sessionid', session_id());
		$stmt->setString('URL', $_SERVER['REQUEST_URI']);
		if (Helper::getViewId() > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($registry->core->getMessage('ERR_USER_HISTORY_LOG'), 21, $e->getMessage());
		}
		return true;
	}

	public static function getAdminPaneName () {
		global $registry;
		
		if ($registry->router->getMode() != 1)
			return '';
		return $registry->router->getAdminPaneName();
	}
}