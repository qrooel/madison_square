<?php
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */
class NokautModel extends Model{

	public function __construct($registry, $modelFile){
		parent::__construct($registry, $modelFile);
	}

	public function getDescription(){
		return '<b>Nokaut.pl</b> wszystkim sklepom gwarantuje realne i wymierne korzyści płynące z prezentacji swojej oferty w serwisie. Poprzez obecność w Nokaut.pl oferty sklepów są stale dostępne dla użytkowników aktywnie dokonujących zakupów w Internecie. Poprzez płynną wymianę opinii pomiędzy użytkownikami, Nokaut.pl promuje marki sklepów internetowych.';
	}
	
	public function getConfigurationFields(){
		return Array();
	}

}