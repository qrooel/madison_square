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
 * $Id: db.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class Db
{
	
	/*** Declare instance ***/
	private static $instance = NULL;

	/**
	 *
	 * the constructor is set to private so
	 * so nobody can create a new instance using new
	 *
	 */
	private function __construct ()
	{
	/*** maybe set the db name here later ***/
	}

	/**
	 *
	 * Return DB instance or create intitial connection
	 *
	 * @return object (PDO)
	 *
	 * @access public
	 *
	 */
	public static function getInstance ($array)
	{
		if (! self::$instance){
			try{
				if (__ENABLE_PROFILER__ == 1){
					Creole::registerDriver('*', 'creole.contrib.DebugConnection');
				}
				self::$instance = Creole::getConnection($array, Creole::PERSISTENT | Creole::COMPAT_ASSOC_LOWER);
			}
			catch (Exception $e){
				throw new Exception('MySQL connection problem: ' . $e);
			}
		}
		return self::$instance;
	}

	/**
	 *
	 * Like the constructor, we make __clone private
	 * so nobody can clone the instance
	 *
	 */
	private function __clone ()
	{
	}

	public static function getDatabaseName ()
	{
		$data = self::$instance->getDSN();
		if (isset($data['database'])){
			return $data['database'];
		}
		return false;
	}

} /*** end of class ***/