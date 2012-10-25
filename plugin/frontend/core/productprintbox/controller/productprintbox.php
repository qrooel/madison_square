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
 * $Id: productprintbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ProductPrintBoxController extends BoxController
{

	public function index ()
	{
		$this->disableLayout();
		$product = App::getModel('product/product')->getProductAndAttributesById((int) $this->registry->core->getParam());
		$range = App::getModel('product/product')->getRangeType((int) $this->registry->core->getParam());
		App::getModel('product/product')->getPhotos($product);
		
		$selectAttributes = App::getModel('product/product')->getProductAttributeGroups($product);
		$attset = App::getModel('product/product')->getProductVariant($product);
		$technicalData = App::getModel('product')->GetTechnicalDataForProduct((int) $this->registry->core->getParam());
		$this->registry->template->assign('product', $product);
		$this->registry->template->assign('attributes', $selectAttributes);
		$this->registry->template->assign('technicalData', $technicalData);
		$this->registry->template->assign('attset', $attset);
		$this->registry->template->assign('ROOTPATH', ROOTPATH);
		$htmlcontent = $this->registry->template->fetch($this->loadTemplate('index.tpl'));
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Gekosale');
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setHeaderFont(Array(
			PDF_FONT_NAME_MAIN,
			'',
			PDF_FONT_SIZE_MAIN
		));
		$pdf->setFooterFont(Array(
			PDF_FONT_NAME_DATA,
			'',
			PDF_FONT_SIZE_DATA
		));
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(1);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->AddPage();
		$pdf->writeHTML($htmlcontent, true, 0, true, 0);
		ob_clean();
		$pdf->Output($product['seo'], 'D');
	}
}
