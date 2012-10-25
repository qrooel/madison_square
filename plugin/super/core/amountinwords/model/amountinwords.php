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
 * $Id: amountinwords.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class amountinwordsModel extends Model
{

	function odmiana ($odmiany, $int)
	{
		
		$txt = $odmiany[2];
		if ($int == 1){
			$txt = $odmiany[0];
		}
		$jednosci = (int) substr($int, - 1);
		$reszta = $int % 100;
		if (($jednosci > 1 && $jednosci < 5) & ! ($reszta > 10 && $reszta < 20)){
			$txt = $odmiany[1];
		}
		return $txt;
	}

	function liczba ($int)
	{
		
		$slowa = Array(
			'minus',
			Array(
				'zero',
				'jeden',
				'dwa',
				'trzy',
				'cztery',
				'pięć',
				'sześć',
				'siedem',
				'osiem',
				'dziewięć'
			),
			Array(
				'dziesięć',
				'jedenaście',
				'dwanaście',
				'trzynaście',
				'czternaście',
				'piętnaście',
				'szesnaście',
				'siedemnaście',
				'osiemnaście',
				'dziewiętnaście'
			),
			Array(
				'dziesięć',
				'dwadzieścia',
				'trzydzieści',
				'czterdzieści',
				'pięćdziesiąt',
				'sześćdziesiąt',
				'siedemdziesiąt',
				'osiemdziesiąt',
				'dziewięćdziesiąt'
			),
			Array(
				'sto',
				'dwieście',
				'trzysta',
				'czterysta',
				'pięćset',
				'sześćset',
				'siedemset',
				'osiemset',
				'dziewięćset'
			),
			Array(
				'tysiąc',
				'tysiące',
				'tysięcy'
			),
			Array(
				'milion',
				'miliony',
				'milionów'
			),
			Array(
				'miliard',
				'miliardy',
				'miliardów'
			),
			Array(
				'bilion',
				'biliony',
				'bilionów'
			)
		);
		$wynik = '';
		$j = abs((int) $int);
		if ($j == 0){
			return $slowa[1][0];
		}
		$jednosci = $j % 10;
		$dziesiatki = ($j % 100 - $jednosci) / 10;
		$setki = ($j - $dziesiatki * 10 - $jednosci) / 100;
		if ($setki > 0){
			$wynik .= $slowa[4][$setki - 1] . ' ';
		}
		if ($dziesiatki > 0)
			if ($dziesiatki == 1){
				$wynik .= $slowa[2][$jednosci] . ' ';
			}
			else{
				$wynik .= $slowa[3][$dziesiatki - 1] . ' ';
			}
		if ($jednosci > 0 && $dziesiatki != 1){
			$wynik .= $slowa[1][$jednosci] . ' ';
		}
		return $wynik;
	}

	function slownie ($int)
	{
		
		$slowa = Array(
			'minus',
			Array(
				'zero',
				'jeden',
				'dwa',
				'trzy',
				'cztery',
				'pięć',
				'sześć',
				'siedem',
				'osiem',
				'dziewięć'
			),
			Array(
				'dziesięć',
				'jedenaście',
				'dwanaście',
				'trzynaście',
				'czternaście',
				'piętnaście',
				'szesnaście',
				'siedemnaście',
				'osiemnaście',
				'dziewiętnaście'
			),
			Array(
				'dziesięć',
				'dwadzieścia',
				'trzydzieści',
				'czterdzieści',
				'pięćdziesiąt',
				'sześćdziesiąt',
				'siedemdziesiąt',
				'osiemdziesiąt',
				'dziewięćdziesiąt'
			),
			Array(
				'sto',
				'dwieście',
				'trzysta',
				'czterysta',
				'pięćset',
				'sześćset',
				'siedemset',
				'osiemset',
				'dziewięćset'
			),
			Array(
				'tysiąc',
				'tysiące',
				'tysięcy'
			),
			Array(
				'milion',
				'miliony',
				'milionów'
			),
			Array(
				'miliard',
				'miliardy',
				'miliardów'
			),
			Array(
				'bilion',
				'biliony',
				'bilionów'
			),
			Array(
				'biliard',
				'biliardy',
				'biliardów'
			),
			Array(
				'trylion',
				'tryliony',
				'trylionów'
			),
			Array(
				'tryliard',
				'tryliardy',
				'tryliardów'
			),
			Array(
				'kwadrylion',
				'kwadryliony',
				'kwadrylionów'
			),
			Array(
				'kwintylion',
				'kwintyliony',
				'kwintylionów'
			),
			Array(
				'sekstylion',
				'sekstyliony',
				'sekstylionów'
			),
			Array(
				'septylion',
				'septyliony',
				'septylionów'
			),
			Array(
				'oktylion',
				'oktyliony',
				'oktylionów'
			),
			Array(
				'nonylion',
				'nonyliony',
				'nonylionów'
			),
			Array(
				'decylion',
				'decyliony',
				'decylionów'
			)
		);
		$int = (int) $int;
		$in = preg_replace('/[^-\d]+/', '', $int);
		$out = '';
		if ($in{0} == '-'){
			$in = substr($in, 1);
			$out = $slowa[0] . ' ';
		}
		$txt = str_split(strrev($in), 3);
		if ($in == 0){
			$out = $slowa[1][0] . ' ';
		}
		for ($i = count($txt) - 1; $i >= 0; $i --){
			$liczba = (int) strrev($txt[$i]);
			if ($liczba > 0){
				if ($i == 0){
					$out .= $this->liczba($liczba) . ' ';
				}
				else{
					$out .= ($liczba > 1 ? $this->liczba($liczba) . ' ' : '') . $this->odmiana($slowa[4 + $i], $liczba) . ' ';
				}
			}
		}
		return trim($out);
	}

}