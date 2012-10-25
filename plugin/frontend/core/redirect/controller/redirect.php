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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: redirect.php 687 2012-09-01 12:02:47Z gekosale $
 */

class redirectController extends Controller
{

	public function index ()
	{
		$this->disableLayout();
		$url = $this->registry->router->getParams();
		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) == true){
			header('Location: ' . $url);
		}
		else{
			$url = 'http://' . $url;
			header('Location: ' . $url);
		}
	
	}

	public function view ()
	{
		$this->disableLayout();
		$sql = "SELECT 
					F.name AS filename,
					FE.name AS fileextension
				FROM file F
				LEFT JOIN fileextension FE ON F.fileextensionid = FE.idfileextension
				WHERE F.idfile = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', (int) $this->registry->core->getParam());
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				switch ($rs->getString('fileextension')) {
					case "pdf":
						$ctype = "application/pdf";
						break;
					case "zip":
						$ctype = "application/zip";
						break;
					case "doc":
						$ctype = "application/msword";
						break;
					case "xls":
						$ctype = "application/vnd.ms-excel";
						break;
					default:
						$ctype = "application/force-download";
				}
				$fullPath = ROOTPATH . 'design' . DS . '_virtualproduct' . DS . (int) $this->registry->core->getParam() . '.' . $rs->getString('fileextension');
				if (is_file($fullPath)){
					$fsize = filesize($fullPath);
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private", false);
					header("Content-Type: $ctype");
					header("Content-Disposition: attachment; filename=\"" . $rs->getString('filename') . "\";");
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: " . $fsize);
					readfile($fullPath);
				}
			
			}
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
	}
}
?>