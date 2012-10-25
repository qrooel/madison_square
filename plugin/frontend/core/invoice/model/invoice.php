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
 * $Id: invoice.php 687 2012-09-01 12:02:47Z gekosale $
 */

class invoiceModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function generateInvoiceNumber ($invoicenumerationkind, $invoiceType, $orderDate, $viewId)
	{
		$sql = 'SELECT 
					COUNT(idinvoice) + 1 AS nextnumber
				FROM invoice
				WHERE 
					invoicetype = :invoicetype AND 
					YEAR(invoicedate) = YEAR(:invoicedate) AND
					viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('invoicetype', $invoiceType);
		$stmt->setString('invoicedate', $orderDate);
		$stmt->setInt('viewid', $viewId);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$nextNumber = $rs->getInt('nextnumber');
		}
		switch ($invoiceType) {
			case 1:
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_PRO');
				break;
			case 2:
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_VAT');
				break;
			case 3:
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_COR');
				break;
		}
		
		$numerationDateMonth = date('m', strtotime($orderDate));
		$numerationDateYear = date('Y', strtotime($orderDate));
		
		switch ($invoicenumerationkind) {
			case 'ntmr':
				$invoiceNumber = Array(
					$nextNumber,
					$invoiceTypeName,
					$numerationDateMonth,
					$numerationDateYear
				);
				break;
			case 'trmn':
				$invoiceNumber = Array(
					$invoiceTypeName,
					$numerationDateYear,
					$numerationDateMonth,
					$nextNumber
				);
				break;
			case 'tmnr':
				$invoiceNumber = Array(
					$invoiceTypeName,
					$numerationDateMonth,
					$nextNumber,
					$numerationDateYear
				);
				break;
			case 'tnr':
				$invoiceNumber = Array(
					$invoiceTypeName,
					$nextNumber,
					$numerationDateYear
				);
				break;
			case 'trn':
				$invoiceNumber = Array(
					$invoiceTypeName,
					$numerationDateYear,
					$nextNumber
				);
				break;
			case 'rnt':
				$invoiceNumber = Array(
					$numerationDateYear,
					$nextNumber,
					$invoiceTypeName
				);
				break;
			case 'rtn':
				$invoiceNumber = Array(
					$numerationDateYear,
					$invoiceTypeName,
					$nextNumber
				);
				break;
		}
		return implode(' / ', $invoiceNumber);
	}

	public function addVATInvoice($orderId, $comment = ''){
		
		$orderData = App::getModel('order')->getOrderById($orderId);
		$formData = Array(
			'salesperson' => '',
			'duedate' => date('Y-m-d'),
			'comment' => $this->registry->core->getMessage('TXT_ORDER').': '.$orderId.(($comment != '') ? $comment : ''),
			'totalpayed' => $orderData['total'],
			'invoicedate' => date('Y-m-d'),
			'invoicenumber' => $this->getInvoiceNumberFormat(date('Y-m-d'), $orderData, 2),
		
		);
		return $this->addInvoice($formData, $orderId, 2, $orderData);
	}
	public function getInvoiceNumberFormat ($date, $orderData, $invoiceType)
	{
		$viewData = App::getModel('view')->getView($orderData['viewid']);
		$invoiceNumber = $this->generateInvoiceNumber($viewData['invoicenumerationkind'], $invoiceType, $date, $orderData['viewid']);
		return $invoiceNumber;
	}

	public function addInvoice ($Data, $orderId, $invoiceTypeId, $orderData)
	{
		$content = '';
		$fileHandler = '';
		switch ($invoiceTypeId) {
			case 1:
				$file = 'pro.tpl';
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_PRO');
				break;
			case 2:
				$file = 'vat.tpl';
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_VAT');
				break;
			case 3:
				$file = 'cor.tpl';
				$invoiceTypeName = $this->registry->core->getMessage('TXT_INVOICE_TYPE_COR');
				break;
		}
		$namespace = $this->registry->loader->getCurrentNamespace();
		$systemFile = ROOTPATH . 'design' . DS . '_tpl' . DS . 'invoiceTemplates' . DS . 'core' . DS . $file;
		$namespaceFile = ROOTPATH . 'design' . DS . '_tpl' . DS . 'invoiceTemplates' . DS . $namespace . DS . $file;
		if (is_file($namespaceFile)){
			$fh = $namespaceFile;
		}
		elseif (is_file($systemFile)){
			$fh = $systemFile;
		}
		else{
			throw new Exception('Invoice template file (' . $file . ')not found.');
		}
		
		$lp = 1;
		foreach ($orderData['products'] as $key => $val){
			$orderData['products'][$key]['lp'] = $lp;
			$orderData['products'][$key]['net_price'] = sprintf('%01.2f', $orderData['products'][$key]['net_price']);
			$orderData['products'][$key]['subtotal'] = sprintf('%01.2f', $orderData['products'][$key]['subtotal']);
			$orderData['products'][$key]['net_subtotal'] = sprintf('%01.2f', $orderData['products'][$key]['net_subtotal']);
			$lp ++;
		}
		$orderData['products'][] = Array(
			'name' => $orderData['delivery_method']['deliverername'],
			'net_price' => sprintf('%01.2f', $orderData['delivery_method']['delivererpricenetto']),
			'quantity' => 1,
			'net_subtotal' => sprintf('%01.2f', $orderData['delivery_method']['delivererpricenetto']),
			'vat' => sprintf('%01.2f', $orderData['delivery_method']['deliverervat']),
			'vat_value' => sprintf('%01.2f', $orderData['delivery_method']['deliverervatvalue']),
			'subtotal' => sprintf('%01.2f', $orderData['delivery_method']['delivererprice']),
			'lp' => $lp
		);
		
		$orderData['order_date'] = date('Y-m-d', strtotime($orderData['order_date']));
		$Data['invoiceTypeName'] = $invoiceTypeName;
		$Data['symbol'] = $Data['invoicenumber'];
		$allpricebrutto = sprintf('%01.2f', $orderData['total']);
		$stringAllPriceBrutto = (string) $allpricebrutto;
		$explodePrice = explode('.', $stringAllPriceBrutto);
		$zl = $explodePrice[0];
		if (isset($explodePrice[1]) && $explodePrice[1] != NULL){
			$gr = $explodePrice[1];
		}
		else{
			$gr = '0';
		}
		$InWordsZl = App::getModel('amountinwords')->slownie($zl);
		$amountInWords = $InWordsZl . ' ' . $orderData['currencysymbol'] . ' ' . (int) $explodePrice[1] . '/100';
		
		$slogan = $this->checkLogoShopNameTag($orderData['viewid']);
		if ($slogan['isinvoiceshopslogan'] == 1){
			$this->registry->template->assign('invoiceshopslogan', $slogan['invoiceshopslogan']);
		}
		else{
			$this->registry->template->assign('invoiceshopslogan', '');
		}
		$summary = $this->getOrderSummary($orderId);
		$bDelivererVatExists = false;
		foreach ($summary as $key => $group){
			if ($group['vat'] == $orderData['delivery_method']['deliverervat']){
				$summary[$key]['netto'] = $group['netto'] + $orderData['delivery_method']['delivererpricenetto'];
				$summary[$key]['brutto'] = $group['brutto'] + $orderData['delivery_method']['delivererprice'];
				$summary[$key]['vatvalue'] = $group['vatvalue'] + $orderData['delivery_method']['deliverervatvalue'];
				$bDelivererVatExists = true;
				break;
			}
		}
		if ($bDelivererVatExists == false){
			$summary[] = Array(
				'vat' => $orderData['delivery_method']['deliverervat'],
				'netto' => $orderData['delivery_method']['delivererpricenetto'],
				'brutto' => $orderData['delivery_method']['delivererprice'],
				'vatvalue' => $orderData['delivery_method']['deliverervatvalue']
			);
		}
		$companyaddress = $this->getMainCompanyAddress($orderData['viewid']);
		$Total = Array(
			'netto' => 0,
			'brutto' => 0,
			'vatvalue' => 0
		);
		foreach ($summary as $key => $group){
			$Total['netto'] += $group['netto'];
			$Total['brutto'] += $group['brutto'];
			$Total['vatvalue'] += $group['vatvalue'];
		}
		
		$this->registry->template->assign('invoiceData', $Data);
		$this->registry->template->assign('order', $orderData);
		$this->registry->template->assign('comment', $Data['comment']);
		$this->registry->template->assign('amountInWords', $amountInWords);
		$this->registry->template->assign('companyaddress', $companyaddress);
		$this->registry->template->assign('summary', $summary);
		$this->registry->template->assign('total', $Total);
		$this->registry->template->assign('originalCopy', $this->registry->core->getMessage('TXT_ORIGINAL'));
		$contentOriginalHtml = $this->registry->template->fetch($fh);
		
		$this->registry->template->assign('invoiceData', $Data);
		$this->registry->template->assign('order', $orderData);
		$this->registry->template->assign('amountPayed', $Data['totalpayed']);
		$this->registry->template->assign('amountToPay', $orderData['total'] - $Data['totalpayed']);
		$this->registry->template->assign('amountInWords', $amountInWords);
		$this->registry->template->assign('companyaddress', $companyaddress);
		$this->registry->template->assign('summary', $summary);
		$this->registry->template->assign('total', $Total);
		$this->registry->template->assign('originalCopy', $this->registry->core->getMessage('TXT_COPY'));
		$contentCopyHtml = $this->registry->template->fetch($fh);
		
		$sql = "INSERT INTO invoice SET
					symbol = :symbol,
					invoicedate = :invoicedate,
					salesdate = :salesdate,
					paymentduedate = :paymentduedate,
					salesperson = :salesperson,
					invoicetype = :invoicetype,
					comment = :comment,
					contentoriginal = :contentoriginal,
					contentcopy = :contentcopy,
					orderid = :orderid,
					totalpayed = :totalpayed,
					viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('symbol', $Data['invoicenumber']);
		$stmt->setString('invoicedate', $Data['invoicedate']);
		$stmt->setString('salesdate', $orderData['order_date']);
		$stmt->setString('paymentduedate', $Data['duedate']);
		$stmt->setString('salesperson', $Data['salesperson']);
		$stmt->setInt('invoicetype', $invoiceTypeId);
		$stmt->setString('comment', $Data['comment']);
		$stmt->setBlob('contentoriginal', $contentOriginalHtml);
		$stmt->setBlob('contentcopy', $contentCopyHtml);
		$stmt->setInt('orderid', $orderId);
		$stmt->setFloat('totalpayed', $Data['totalpayed']);
		$stmt->setInt('viewid', $orderData['viewid']);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$id = $stmt->getConnection()->getIdGenerator()->getId();
		return $id;
	}

	public function getMainCompanyAddress ($viewid)
	{
		
		$sql = "SELECT
					S.name AS shopname, 
					S.postcode, 
					S.street, 
					S.streetno, 
					S.placeno, 
					S.placename, 
					S.province, 
					S.nip,
					S.bankname, 
					S.banknr
				FROM store S
				LEFT JOIN view V ON V.storeid = S.idstore
				WHERE V.idview = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $viewid);
		
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'shopname' => $rs->getString('shopname'),
					'postcode' => $rs->getString('postcode'),
					'street' => $rs->getString('street'),
					'streetno' => $rs->getString('streetno'),
					'placeno' => $rs->getString('placeno'),
					'placeno' => $rs->getString('placeno'),
					'placename' => $rs->getString('placename'),
					'province' => $rs->getString('province'),
					'nip' => $rs->getString('nip'),
					'bankname' => $rs->getString('bankname'),
					'banknr' => $rs->getString('banknr')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GET_COMPANYADDRESS'));
		}
		return $Data;
	}

	public function getOrderSummary ($idorder)
	{
		
		$sql = "SELECT 
					OP.vat,
					SUM(OP.price * OP.qty) as brutto,
            		SUM(OP.pricenetto * OP.qty) as netto
				FROM `order` O
				LEFT JOIN orderclientdata OCD ON OCD.orderid=O.idorder
				LEFT JOIN orderproduct OP ON OP.orderid=O.idorder
				WHERE idorder=:idorder
				GROUP BY OP.vat";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'vat' => sprintf('%01.2f', $rs->getFloat('vat')),
					'netto' => sprintf('%01.2f', $rs->getFloat('netto')),
					'brutto' => sprintf('%01.2f', $rs->getFloat('brutto')),
					'vatvalue' => sprintf('%01.2f', ($rs->getFloat('brutto') - $rs->getFloat('netto')))
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GET_COMPANYADDRESS'));
		}
		return $Data;
	}

	public function checkLogoShopNameTag ($viewid)
	{
		
		$sql = "SELECT
					S.name as shopname, 
					S.invoicephotoid, 
					S.invoiceshopslogan, 
					S.isinvoiceshopslogan, 
					S.isinvoiceshopname
				FROM store S 
				LEFT JOIN view V ON V.storeid = S.idstore
				WHERE V.idview = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $viewid);
		
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'shopname' => $rs->getString('shopname'),
					'invoicephotoid' => $rs->getInt('invoicephotoid'),
					'isinvoiceshopslogan' => $rs->getInt('isinvoiceshopslogan'),
					'isinvoiceshopname' => $rs->getInt('isinvoiceshopname'),
					'invoiceshopslogan' => $rs->getString('invoiceshopslogan')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('Error while doing sql query to invoice'));
		}
		return $Data;
	}

	public function getInvoiceById ($id)
	{
		
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
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
		$sql = 'SELECT 
					*
				FROM invoice I
				LEFT JOIN `order` O ON O.idorder = I.orderid
				WHERE 
					I.idinvoice = :id AND IF(O.clientid IS NOT NULL, O.clientid = :clientid, 1)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			switch ($rs->getString('contenttype')) {
				case 'html':
					$htmlcontent = $rs->getBlob('contentoriginal');
					$pdf->AddPage();
					$pdf->writeHTML($htmlcontent, true, 0, true, 0);
					ob_clean();
					$pdf->Output($rs->getString('symbol'), 'D');
					break;
				case 'pdf':
					$data = base64_decode($rs->getBlob('contentoriginal'));
					header('Content-Type: application/pdf');
					header('Content-Description: File Transfer');
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: attachment; filename="' . $rs->getString('symbol') . '.pdf"');
					header('Content-Length: ' . strlen($data));
					header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
					header('Expires: 0');
					echo $data;
					exit();
					break;
			
			}
		
		}
	}
	
	public function getInvoiceFileById ($id)
	{
	
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
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
		$sql = 'SELECT
		*
		FROM invoice I
		LEFT JOIN `order` O ON O.idorder = I.orderid
		WHERE
		I.idinvoice = :id AND IF(O.clientid IS NOT NULL, O.clientid = :clientid, 1)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			switch ($rs->getString('contenttype')) {
				case 'html':
					$htmlcontent = $rs->getBlob('contentoriginal');
					$pdf->AddPage();
					$pdf->writeHTML($htmlcontent, true, 0, true, 0);
					ob_clean();
					$symbol = str_replace(Array('/',' '),Array('_',''), $rs->getString('symbol').'.pdf');
					$pdf->Output(ROOTPATH.'upload'.DS.$symbol, 'F');
					break;
			}
	
		}
		return $symbol;
	}

}