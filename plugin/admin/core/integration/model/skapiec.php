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
class SkapiecModel extends Model{

	public function __construct($registry, $modelFile){
		parent::__construct($registry, $modelFile);
	}

	public function getDescription(){
		return '<p><h3>Skąpiec.pl</h3></p>
<p>Skąpiec.pl jest serwisem propagującym zakupy w internecie. Potrafimy przy pomocy naszego oprogramowania przeglądać strony internetowe sklepów, tworzyć powiązania pomiędzy produktami z oferty i naszą bazą produktów oraz prezentować te wszystkie informacje naszym użytkownikom.
Skąpiec.pl nie jest sklepem i niczego nie sprzedaje. Naszym zadaniem jest jedynie pokazywanie w którym sklepie dany towar można nabyć najtaniej i który sprzedawca posiada najlepszą opinię
Skąpiec.pl odwiedza miesięcznie ok. 2 mln unikalnych użytkowników wykonując ponad 19 mln odsłon. (dane wg. Google Analitycs)</p>
';
	}

	public function getConfigurationFields(){
		return Array();
	}

}