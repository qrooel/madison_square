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
 * $Id: pollbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class PollBoxController extends BoxController
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'setAnswersChecked',
			App::getModel('pollbox'),
			'setAJAXAnswersMethodChecked'
		));
		$poll = App::getModel('PollBox')->getPoll();
		$clientId = $this->registry->session->getActiveClientid();
		$this->show = false;
		
		if (isset($poll['idpoll'])){
			$this->show = true;
			$answers = App::getModel('PollBox')->checkAnswers($poll['idpoll']);
			$check = 0;
			$maxQty = 0;
			foreach ($answers as $value){
				if (! empty($value['qty']['clientid']) && $value['qty']['clientid'] == $clientId){
					$check = 1;
				}
				$maxQty = max($maxQty, $value['qty']['qty']);
			}
			foreach ($answers as &$value){
				if ($maxQty){
					$value['qty']['percentage'] = ceil($value['qty']['qty'] / $maxQty * 100);
				}
				else{
					$value['qty']['percentage'] = 0;
				}
			}
			
			$this->registry->template->assign('check', $check);
			$this->registry->template->assign('poll', $poll);
			$this->registry->template->assign('answers', $answers);
		}
		$this->registry->template->assign('clientdata', $clientId);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function boxVisible ()
	{
		return $this->show;
	}

}