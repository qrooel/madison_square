<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009-2011 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 * 
 * $Revision: 426 $
 * $Author: gekosale $
 * $Date: 2011-08-26 10:05:23 +0200 (Pt, 26 sie 2011) $
 * $Id: cache.class.php 426 2011-08-26 08:05:23Z gekosale $ 
 */

class Cache
{
	
	const SESSION = 'SESSION';
	const FILE = 'FILE';

	public static function setDefaultStoreType ($type)
	{
		if (App::getRegistry()->session->getActiveCacheType() !== NULL && App::getRegistry()->session->getActiveCacheType() != $type){
			self::flushCacheObjects();
		}
		switch ($type) {
			case self::FILE:
				App::getRegistry()->session->setActiveCacheType(self::FILE);
				break;
			case self::SESSION:
			default:
				App::getRegistry()->session->setActiveCacheType(self::SESSION);
				break;
		}
	}

	public static function saveObjectHandler ($objName, $storeType)
	{
		try{
			$objects = self::unserializeFromFile('cache.objectHandler', false);
			self::serializeToFile(array_merge((array) $objects, Array(
				$objName => $storeType
			)), 'cache.objectHandler', false);
		}
		catch (Exception $e){
			self::serializeToFile(Array(
				$objName => $storeType
			), 'cache.objectHandler', false);
		}
	}

	public static function getObjectHandler ($objName)
	{
		try{
			$objects = self::unserializeFromFile('cache.objectHandler', false);
			if (isset($objects[$objName])){
				return $objects[$objName];
			}
			return false;
		}
		catch (Exception $e){
			return false;
		}
	}

	public static function flushObjectHandler ()
	{
		try{
			self::destroySerializeFile('cache.objectHandler');
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public static function flushObjectFromObjectHandler ($objName)
	{
		try{
			$objects = self::unserializeFromFile('cache.objectHandler', false);
			if (isset($objects[$objName])){
				unset($objects[$objName]);
				self::serializeToFile($objects, 'cache.objectHandler', false);
				return true;
			}
			throw new Exception('Object: ' . $objName . ' not found in CacheObjectHandler');
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public static function flushCacheObjects ()
	{
		try{
			$objects = self::unserializeFromFile('cache.objectHandler', false);
			foreach ($objects as $object => $handler){
				switch ($handler) {
					case self::SESSION:
						call_user_func(array(
							App::getRegistry()->session,
							'unsetActive' . $object
						));
						break;
					case self::FILE:
						try{
							self::destroySerializeFile($object);
							break;
						}
						catch (Exception $e){
							throw $e;
						}
				}
			}
			self::flushObjectHandler();
			return true;
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public static function saveObject ($name, $value, $storeTypes = Array(), $dependent = true, $namespace = 'cache')
	{
		$__defaultNamespace = 'core.';
		if (strpos($namespace, '.') === 0)
			$namespace = substr($namespace, 1);
		if (strrpos($namespace, '.') === strlen($namespace) - 1){
			$namespace = substr($namespace, 0, - 1);
		}
		if (strlen($namespace) > 0){
			$__namespaceSlices = explode('.', $namespace);
			foreach ($__namespaceSlices as $key => $namespace){
				$__namespaceSlices[$key] = ucfirst(strtolower($namespace));
			}
			$__defaultNamespace = implode('.', $__namespaceSlices);
		}
		$__namespaceSlices = explode('.', $name);
		foreach ($__namespaceSlices as $key => $name){
			$__namespaceSlices[$key] = ucfirst(strtolower($name));
		}
		$__defaultNamespace = $__defaultNamespace . '.' . implode('.', $__namespaceSlices);
		switch (self::getPrimaryStoreType($storeTypes)) {
			case self::SESSION:
				self::saveObjectHandler($__defaultNamespace, self::SESSION);
				return call_user_func(array(
					App::getRegistry()->session,
					'setActive' . $__defaultNamespace
				), $value);
			case self::FILE:
				self::saveObjectHandler($__defaultNamespace, self::FILE);
				return self::serializeToFile($value, $__defaultNamespace, $dependent);
		}
	}

	public static function loadObject ($name, $namespace = 'cache', $dependent = true)
	{
		$__defaultNamespace = 'core.';
		if (strpos($namespace, '.') === 0)
			$namespace = substr($namespace, 1);
		if (strrpos($namespace, '.') === strlen($namespace) - 1){
			$namespace = substr($namespace, 0, - 1);
		}
		if (strlen($namespace) > 0){
			$__namespaceSlices = explode('.', $namespace);
			foreach ($__namespaceSlices as $key => $namespace){
				$__namespaceSlices[$key] = ucfirst(strtolower($namespace));
			}
			$__defaultNamespace = implode('.', $__namespaceSlices);
		}
		$__namespaceSlices = explode('.', $name);
		foreach ($__namespaceSlices as $key => $name){
			$__namespaceSlices[$key] = ucfirst(strtolower($name));
		}
		$__defaultNamespace = $__defaultNamespace . '.' . implode('.', $__namespaceSlices);
		switch (self::getObjectHandler($__defaultNamespace)) {
			case self::SESSION:
				return call_user_func(array(
					App::getRegistry()->session,
					'getActive' . $__defaultNamespace
				));
			case self::FILE:
				try{
					return self::unserializeFromFile($__defaultNamespace, $dependent);
				}
				catch (Exception $e){
					return false;
				}
			default:
				return false;
		}
	}

	public static function destroyObject ($name, $dependent = true, $namespace = 'cache')
	{
		$__defaultNamespace = 'core.';
		if (strpos($namespace, '.') === 0)
			$namespace = substr($namespace, 1);
		if (strrpos($namespace, '.') === strlen($namespace) - 1){
			$namespace = substr($namespace, 0, - 1);
		}
		if (strlen($namespace) > 0){
			$__namespaceSlices = explode('.', $namespace);
			foreach ($__namespaceSlices as $key => $namespace){
				$__namespaceSlices[$key] = ucfirst(strtolower($namespace));
			}
			$__defaultNamespace = implode('.', $__namespaceSlices);
		}
		$__namespaceSlices = explode('.', $name);
		foreach ($__namespaceSlices as $key => $name){
			$__namespaceSlices[$key] = ucfirst(strtolower($name));
		}
		$__defaultNamespace = $__defaultNamespace . '.' . implode('.', $__namespaceSlices);
		switch (self::getObjectHandler($__defaultNamespace)) {
			case self::SESSION:
				self::flushObjectFromObjectHandler($__defaultNamespace);
				return call_user_func(array(
					App::getRegistry()->session,
					'unsetActive' . $__defaultNamespace
				));
			case self::FILE:
				try{
					
					self::flushObjectFromObjectHandler($__defaultNamespace);
					return self::destroySerializeFile($__defaultNamespace, $dependent);
				}
				catch (Exception $e){
					return false;
				}
			default:
				return false;
		}
	}

	public static function serialize ($content)
	{
		if (is_object($content) && get_class($content) == 'SimpleXMLElement'){
			$__class = new StdClass();
			$__class->type = get_class($content);
			$__class->data = $content->asXml();
			return serialize($__class);
		}
		return serialize($content);
	}

	public static function unserialize ($content)
	{
		$__class = unserialize(str_replace(array(
			'O:16:"SimpleXMLElement":0:{}',
			'O:16:"SimpleXMLElement":'
		), array(
			's:0:"";',
			'O:8:"stdClass":'
		), $content));
		if (is_object($content) && get_class($__class) == 'stdClass'){
			if ($__class->type == 'SimpleXMLElement'){
				$__class = simplexml_load_string($__class->data);
			}
		}
		return $__class;
	}

	public static function serializeToFile ($content, $file, $dependent = TRUE)
	{
		if ($dependent){
			$viewid = Helper::getViewId();
			$languageid = App::getRegistry()->session->getActiveLanguageId();
			$file .= '_' . $viewid . '_' . $languageid;
		}
		if (substr($file, - 4) != '.reg'){
			$file .= '.reg';
		}
		$dir = ROOTPATH . 'serialization' . DS;
		if (file_put_contents($dir . $file, self::serialize($content), LOCK_EX) === FALSE){
			throw new Exception('Can not serialize content to file');
		}
	}

	public static function unserializeFromFile ($file, $dependent = TRUE)
	{
		if ($dependent){
			$viewid = Helper::getViewId();
			$languageid = App::getRegistry()->session->getActiveLanguageId();
			$file .= '_' . $viewid . '_' . $languageid;
		}
		if (substr($file, - 4) != '.reg'){
			$file .= '.reg';
		}
		$dir = ROOTPATH . 'serialization' . DS;
		if (! is_file($dir . $file)){
			throw new Exception('File does not exists: ' . $dir . $file);
		}
		if (($content = file_get_contents($dir . $file)) === FALSE){
			throw new Exception('Can not get serialized content from file');
		}
		return self::unserialize($content);
	}

	public static function destroySerializeFile ($file, $dependent = TRUE)
	{
		if ($dependent){
			$dir = ROOTPATH . 'serialization' . DS;
			foreach (glob($dir . $file . '*') as $key => $fn){
				if (is_file($fn)){
					unlink($fn);
				}
			}
		
		}
		else{
			if (substr($file, - 4) != '.reg'){
				$file .= '.reg';
			}
			$dir = ROOTPATH . 'serialization' . DS;
			
			if (! is_file($dir . $file)){
				throw new Exception('File does not exists: ' . $dir . $file);
			}
			if (unlink($dir . $file) === FALSE){
				throw new Exception('File does not exists');
			}
		}
	}

	public static function getPrimaryStoreType ($storeTypes)
	{
		if (! isset($storeTypes[App::getRegistry()->session->getActiveCacheType()])){
			return App::getRegistry()->session->getActiveCacheType();
		}
		if (isset($storeTypes[App::getRegistry()->session->getActiveCacheType()])){
			if ($storeTypes[App::getRegistry()->session->getActiveCacheType()] == 1){
				return App::getRegistry()->session->getActiveCacheType();
			}
		}
		if (isset($storeTypes[self::SESSION]) && $storeTypes[self::SESSION] == 1)
			return self::SESSION;
		if (isset($storeTypes[self::FILE]) && $storeTypes[self::FILE] == 1)
			return self::FILE;
	}
}
