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
 * $Id: formprotection.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class FormprotectionModel extends Model
{

	function cropDangerousCode ($string)
	{
		
		//JAVASCRIPT, XSS, AHREF, ON*
		$string = preg_replace("/href=(['\"]).*?javascript:(.*)?\\1/i", "", $string);
		$string = preg_replace("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "", $string);
		$string = preg_replace("/:expression\(.*?((?>[^ (.*?)]+)|(?R)).*?\)\)/i", "", $string);
		$string = preg_replace("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "", $string);
		
		//wyczyszczenie wszystkich zdarzeń zaczynających się od on* (onclick, onfocus, onmouseout, etc.)
		while (preg_match("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", $string))
			$string = preg_replace("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", "", $string);
		
		$string = preg_replace("/<(.*)?(\[cdata\[(.*?)\]\])?(.*)?(\]{2})?>/i", "", $string);
		
		//HTML
		$string = strip_tags($string);
		
		//PHP
		$string = preg_replace("/(.*)?(\<\?php)+(.*)?(\?\>)+(.*)?>/i", "", $string);
		
		return $string;
	}

	function cropDangerousCodeSubmitedValues ($tabStrings)
	{
		
		$Data = Array();
		
		foreach ($tabStrings as $string => $key){
			
			//JAVASCRIPT, XSS, AHREF, ON*
			$key = preg_replace("/href=(['\"]).*?javascript:(.*)?\\1/i", "", $key);
			$key = preg_replace("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "", $key);
			$key = preg_replace("/:expression\(.*?((?>[^ (.*?)]+)|(?R)).*?\)\)/i", "", $key);
			$key = preg_replace("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "", $key);
			
			//wyczyszczenie wszystkich zdarzeń zaczynających się od on* (onclick, onfocus, onmouseout, etc.)
			while (preg_match("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", $key))
				$key = preg_replace("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", "", $key);
			
			$key = preg_replace("/<(.*)?(\[cdata\[(.*?)\]\])?(.*)?(\]{2})?>/i", "", $key);
			
			//HTML
			$key = strip_tags($key);
			
			//PHP
			$key = preg_replace("/(.*)?(\<\?php)+(.*)?(\?\>)+(.*)?>/i", "", $key);
			
			$Data[$string] = $key;
		}
		
		if ($Data == $tabStrings)
			return true;
		else
			return false;
	
	}

}