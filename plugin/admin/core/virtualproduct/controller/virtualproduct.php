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
 * $Id: virtualproduct.php 655 2012-04-24 08:51:44Z gekosale $
 */

class VirtualProductController extends Controller
{

	public function index ()
	{
		return false;
	}

	public function add ()
	{
		try{
			ob_start();
			$this->disableLayout();
			App::getModel('virtualproductfiles')->process($_FILES['Filedata']);
			$id = App::getModel('virtualproductfiles')->insert($_FILES['Filedata']);
			$image = Array(
				'path' => '',
				'filename' => $_FILES['Filedata']['name'],
				'filextensioname' => App::getModel('virtualproductfiles')->getFileExtension($_FILES['Filedata']['name']),
				'filetypename' => $_FILES['Filedata']['type']
			);
			echo "response = {sId: '{$id}', sThumb: '{$image['path']}', sFilename: '{$image['filename']}', sExtension: '{$image['filextensioname']}', sFileType: '{$image['filetypename']}'}";
		}
		catch (Exception $e){
			echo $e->getMessage();
		}
	}

	public function edit ()
	{
		return false;
	}

	public function view ()
	{
		return false;
	}
}