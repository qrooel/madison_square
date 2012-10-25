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
				'hostspec'=> 'localhost',
				'port'=> 3306,
				'username'=> '',
				'password'=> '',
				'database'=> '',
				'encoding'=> 'utf8'
			),
			'phpmailer'=> Array(
				'Mailer'=> 'mail',
				'CharSet'=> 'UTF-8',
				'FromName'=> '',
				'FromEmail'=> '',
				'server'=> 'foo.example.com',
				'port'=> 25,
				'SMTPSecure'=> '',
				'SMTPAuth'=> 'true',
				'SMTPUsername'=> 'foo@example.com',
				'SMTPPassword'=> 'password',
			),
			'admin_panel_link'=> 'admin',
			'client_data_encription'=> 1,
			'client_data_encription_string'=> 'topsecret',
		);