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
 * $Revision: 6 $
 * $Author: gekosale $
 * $Date: 2011-03-27 21:01:27 +0200 (N, 27 mar 2011) $
 * $Id: graphicsbox.php 6 2011-03-27 19:01:27Z gekosale $
 */

class SlideShowBoxController extends BoxController
{

	public function index ()
	{
		$Height = 'auto';
		$Data = Array();
		for ($i = 1; $i <= 10; $i ++){
			if (isset($this->_boxAttributes['image' . $i]) && $this->_boxAttributes['image' . $i] != ''){
				$image = str_replace('design/', '', $this->_boxAttributes['image' . $i]);
				$height = $this->getImageHeight(ROOTPATH.$this->_boxAttributes['image' . $i]);
				if ($height > 0){
					$Data[] = Array(
						'image' => $image,
						'height' => $height,
						'url' => $this->_boxAttributes['url' . $i],
						'caption' => $this->_boxAttributes['caption' . $i]
					);
				}
			}
		}
		$this->total = count($Data);
		if ($this->total > 0){
			$Height = $Data[0]['height'];
		}
		$this->registry->template->assign('id', $this->_boxId);
		$this->registry->template->assign('slideshow', $Data);
		$this->registry->template->assign('height', $Height);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function boxVisible ()
	{
		return ($this->total > 0) ? true : false;
	}

	protected function getImageHeight ($image)
	{
		$size = @getimagesize($image);
		if (isset($size[1])){
			return $size[1];
		}
		else{
			return 0;
		}
	}

}