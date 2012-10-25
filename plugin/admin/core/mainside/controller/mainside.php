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
 * $Id: mainside.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class MainsideController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('mainside');
	}

	public function index ()
	{
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('from', date('Y/m/1'));
		$this->registry->template->assign('to', date('Y/m/d'));
		$this->registry->template->assign('summaryStats', $this->model->getSummaryStats());
		$this->registry->template->assign('topten', $this->model->getTopTen());
		$this->registry->template->assign('mostsearch', $this->model->getMostSearch());
		$this->registry->template->assign('lastorder', $this->model->getLastOrder());
		$this->registry->template->assign('newclient', $this->model->getNewClient());
		$this->registry->template->assign('clientOnline', $this->model->getClientOnline());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function view ()
	{
		$this->disableLayout();
		if (strlen($this->registry->core->getParam(1)) > 0){
			$range = base64_decode($this->registry->core->getParam(1));
			if (strpos($range, '-') > 0){
				$dates = explode('-', $range);
				$request = Array(
					'from' => trim(str_replace('/', '-', $dates[0])),
					'to' => trim(str_replace('/', '-', $dates[1]))
				);
			}
			else{
				$request = Array(
					'from' => trim(str_replace('/', '-', $range)),
					'to' => trim(str_replace('/', '-', $range))
				);
			}
		}
		else{
			$request = Array(
				'from' => date('Y-m-1'),
				'to' => date('Y-m-d')
			);
		}
		switch ($this->registry->core->getParam(0)) {
			case 'sales':
				echo $this->model->salesChart($request);
				break;
			case 'orders':
				echo $this->model->ordersChart($request);
				break;
			case 'clients':
				echo $this->model->clientsChart($request);
				break;
		}
	}

	public function confirm ()
	{
		$this->disableLayout();
		$param = base64_decode($this->registry->core->getParam());
		$Data = App::getModel('mainside')->search($param);
		$html = '<div class="livesearch-results">';
		if (isset($Data['orders'])){
			$html .= '<h3>Zamówienia:</h3>';
			$html .= '<ul>';
			foreach ($Data['orders'] as $key => $result){
				$html .= $result;
			}
			$html .= '</ul>';
		}
		if (isset($Data['clients'])){
			$html .= '<h3>Klienci:</h3>';
			$html .= '<ul>';
			foreach ($Data['clients'] as $key => $result){
				$html .= $result;
			}
			$html .= '</ul>';
		}
		if (isset($Data['products'])){
			$html .= '<h3>Produkty:</h3>';
			$html .= '<ul>';
			foreach ($Data['products'] as $key => $result){
				$html .= $result;
			}
			$html .= '</ul>';
		}
		$html .= '</div>';
		echo $html;
	}
}