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
 */

class ProducerBoxController extends BoxController
{

	public function index ()
	{
		$producers = App::getModel('producerbox')->getProducerAll(explode(',', $this->_boxAttributes['producers']));
		$this->total = count($producers);
		$this->registry->template->assign('producers', $producers);
		$this->registry->template->assign('view', $this->_boxAttributes['view']);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		if ($this->total > 0){
			return 'layout-box-type-producer-list';
		}
	}

	public function boxVisible ()
	{
		return ($this->total > 0) ? true : false;
	}

}