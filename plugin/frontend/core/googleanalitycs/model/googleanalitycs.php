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
 * $Id: googleanalitycs.php 655 2012-04-24 08:51:44Z gekosale $
 */

class googleanalitycsModel extends Model
{

	public function addTransGoogleAnalitycsJs ($Data)
	{
		$account = $this->layer['gacode'];
		$code = '';
		if (strlen($account) > 0 && $this->layer['gatransactions'] == 1){
			$tax = $Data['orderData']['priceWithDispatchMethod'] - $Data['orderData']['priceWithDispatchMethodNetto'];
			$code .= "<script type=\"text/javascript\">
				  _gaq.push(['_addTrans',
				    '{$Data['orderId']}',
				    '{$this->registry->session->getActiveShopName()}',
				    '{$Data['orderData']['priceWithDispatchMethod']}',
				    '{$tax}',
				    '{$Data['orderData']['dispatchmethod']['dispatchmethodcost']}',
				    '{$Data['orderData']['clientdata']['placename']}',
				    '',
				    ''
				  ]);
			";
			foreach ($Data['orderData']['cart'] as $key => $item){
				if (isset($item['attributes'])){
					foreach ($item['attributes'] as $key => $prod){
						$code .= "_gaq.push(['_addItem',
					    '{$Data['orderId']}',  
					    '{$prod['name']}', 
					    '{$prod['name']}',
					    '',
					    '{$prod['newprice']}',
					    '{$prod['qty']}'
				  ]);";
					}
				}
				else{
					$code .= "_gaq.push(['_addItem',
				    '{$Data['orderId']}',  
				    '{$item['ean']}', 
				    '{$item['name']}',
				    '',
				    '{$item['newprice']}',
				    '{$item['qty']}'
				  ]);";
				}
			
			}
			$code .= "_gaq.push(['_trackTrans']);</script>";
		}
		return $code;
	}
}