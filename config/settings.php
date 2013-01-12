<?php defined('ROOTPATH') OR die('No direct access allowed.');
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
 */

		$Config = Array(
			'database'=> Array(
				'phptype'=> 'mysqli',
				'hostspec'=> 'mysql5',
				'port'=> 3306,
				'username'=> 'qrooel_madison2',
				'password'=> 'madison',
				'database'=> 'qrooel_madison2',
				'encoding'=> 'utf8'
			),
			'phpmailer'=> Array(
				'Mailer'=> 'mail',
				'CharSet'=> 'UTF-8',
				'FromName'=> 'Sklep madison-square.pl',
				'FromEmail'=> 'sklep@madison-square.pl',
				'server'=> '',
				'port'=> 587,
				'SMTPSecure'=> '',
				'SMTPAuth'=> '',
				'SMTPUsername'=> '',
				'SMTPPassword'=> '',
			),
			'admin_panel_link'=> 'admin',
			'force_mod_rewrite'=> 0,
			'client_data_encription'=> 1,
			'client_data_encription_string'=> 'e8b2bea0a46f080f7920a3acaa7699d0',
			'ssl'=> 0,
		);