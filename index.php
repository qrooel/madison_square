<?php
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
 * $Id: index.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

DEFINE('__ENABLE_PROFILER__', 0);

if (__ENABLE_PROFILER__ == 1){
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;
}
$site_path = realpath(dirname(__FILE__));
ini_set('display_errors', true);

(defined('E_DEPRECATED')) ? error_reporting(E_ALL & ~ E_DEPRECATED) : error_reporting(E_ALL);
if (! defined('__SCRIPT_USE')){
	$__LOCAL_CATALOG = '';
	$__SERVER_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/'){
		$__SERVER_DOCUMENT_ROOT = substr($_SERVER['DOCUMENT_ROOT'], 0, - 1);
	}
	else{
		$__SERVER_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	}
	DEFINE('SERVER_DOCUMENT_ROOT', $__SERVER_DOCUMENT_ROOT);
	$__SCRIPT_FILENAME = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : str_replace($__SERVER_DOCUMENT_ROOT, '', $_SERVER['SCRIPT_FILENAME']);
	if (($indexPosition = strpos($__SCRIPT_FILENAME, '/index.php')) > 0){
		$requestURI = substr($_SERVER['REQUEST_URI'], $indexPosition);
		$__LOCAL_CATALOG = substr($_SERVER['REQUEST_URI'], 0, $indexPosition);
		if (strpos($__LOCAL_CATALOG, '/') == 0){
			$__LOCAL_CATALOG = substr($__LOCAL_CATALOG, 1);
		}
	}
	else{
		$requestURI = str_replace($__SCRIPT_FILENAME, '', $_SERVER['REQUEST_URI']);
	}
	if (! isset($_SERVER['SCRIPT_URI']) && strpos($requestURI, '/index.php') === FALSE && $requestURI != '/'){
		if (strpos($requestURI, '/') != 0){
			$requestURI = '/index.php/' . $requestURI;
		}
		else{
			$requestURI = '/index.php' . $requestURI;
		}
	}
	if (strpos($requestURI, '?') > 0){
		$requestURI = substr($requestURI, 0, strpos($requestURI, '?'));
	}
	DEFINE('REQUEST_URI', $requestURI);
	if (strlen($__LOCAL_CATALOG) > 0){
		if (substr($__LOCAL_CATALOG, - 2) == '//'){
			$__LOCAL_CATALOG = substr($__LOCAL_CATALOG, 0, - 1);
		}
	}
	DEFINE('LOCAL_CATALOG', $__LOCAL_CATALOG);
}
DEFINE('__ENABLE_DEBUG__', 1);
DEFINE('DS', DIRECTORY_SEPARATOR);
DEFINE('ROOTPATH', dirname(__FILE__) . DS);
DEFINE('__CLASS_DIR__', ROOTPATH . 'application' . DS . 'gekosale' . DS);
DEFINE('__CREOLE_CLASS__', ROOTPATH . 'lib' . DS . 'creole' . DS);
DEFINE('__SMARTY_CLASS__', ROOTPATH . 'lib' . DS . 'smarty' . DS);
DEFINE('__XAJAX_CLASS__', ROOTPATH . 'lib' . DS . 'xajax' . DS . 'xajax_core' . DS);
DEFINE('__PHPMAILER_CLASS__', ROOTPATH . 'lib' . DS . 'phpmailer' . DS);
DEFINE('__IMAGE_CLASS__', ROOTPATH . 'lib' . DS . 'imageGD' . DS);
DEFINE('__DISPATCHER_CLASS__', ROOTPATH . 'lib' . DS . 'dispatcher' . DS);
DEFINE('__TCPDF_CLASS_LANG__', ROOTPATH . 'lib' . DS . 'tcpdf' . DS . 'config' . DS . 'lang' . DS);
DEFINE('__TCPDF_CLASS__', ROOTPATH . 'lib' . DS . 'tcpdf' . DS);
DEFINE('__FE_CLASS__', ROOTPATH . 'lib' . DS . 'FormEngine' . DS);
DEFINE('__FE_CONDITIONS_CLASS__', ROOTPATH . 'lib' . DS . 'FormEngine' . DS . 'Conditions' . DS);
DEFINE('__FE_ELEMENTS_CLASS__', ROOTPATH . 'lib' . DS . 'FormEngine' . DS . 'Elements' . DS);
DEFINE('__FE_FILTERS_CLASS__', ROOTPATH . 'lib' . DS . 'FormEngine' . DS . 'Filters' . DS);
DEFINE('__FE_RULES_CLASS__', ROOTPATH . 'lib' . DS . 'FormEngine' . DS . 'Rules' . DS);
DEFINE('__PEAR_DIR__', ROOTPATH . 'lib' . DS . 'PEAR' . DS);
set_include_path(ROOTPATH . 'lib' . DS . 'PEAR' . DS . PATH_SEPARATOR);
set_include_path(ROOTPATH . 'lib' . DS . PATH_SEPARATOR . get_include_path());
include_once (__CREOLE_CLASS__ . 'Creole.php');
include_once (__SMARTY_CLASS__ . 'Smarty.class.php');
include_once (__XAJAX_CLASS__ . 'xajax.inc.php');
@unlink(__CLASS_DIR__ . 'FE.php');
date_default_timezone_set('Europe/Warsaw');

function autoLoader ($className)
{
	
	$directories = array(
		__CLASS_DIR__,
		__IMAGE_CLASS__,
		__DISPATCHER_CLASS__,
		__FE_CLASS__,
		__FE_CONDITIONS_CLASS__,
		__FE_ELEMENTS_CLASS__,
		__FE_FILTERS_CLASS__,
		__FE_RULES_CLASS__,
		__FE_RULES_CLASS__,
		__TCPDF_CLASS_LANG__,
		__TCPDF_CLASS__
	);
	
	$fileNameFormats = array(
		'%s.class.php',
		'%s.php'
	);
	
	$path = $className;
	if (@include_once $path . '.php'){
		return;
	}
	
	$rootPathLen = strlen(ROOTPATH);
	
	foreach ($directories as $directory){
		foreach ($fileNameFormats as $fileNameFormat){
			$path = substr($directory, $rootPathLen) . sprintf($fileNameFormat, $className);
			if (is_file(ROOTPATH . strtolower($path))){
				include_once ROOTPATH . strtolower($path);
				return;
			}
			else 
				if (is_file(ROOTPATH . $path)){
					include_once ROOTPATH . $path;
					return;
				}
		}
	}
}

spl_autoload_register('autoLoader');

try{
	App::Run();
}
catch (Exception $e){
	echo $e->getMessage();
	die();
}

if (__ENABLE_PROFILER__ == 1){
	$memory = memory_get_peak_usage();
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totaltime = round(($endtime - $starttime), 5);
	App::getRegistry()->db->getProfiler($totaltime, $memory);
}
