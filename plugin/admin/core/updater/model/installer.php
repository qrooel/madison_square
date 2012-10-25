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
 * $Id: installer.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

$GLOBALS['_PEAR_FRONTEND_CLASS'] = 'PEAR_Frontend_Web';

class InstallerModel extends PEAR_Frontend_Web
{
	
	public $tpl;
	protected $_echo = false;
	protected $tpl_dir;
	protected $_templateVariables = Array();
	protected $_out;

	public function outputData ($data, $command = '_default')
	{
		$this->_out = $data;
		if ($this->_echo){
			if (is_array($data)){
				if (isset($data['headline']) && ($data['headline'] == 'Install Errors')){
					$errorMsg = Array();
					foreach ($data['data'][0] as $error){
						$errorMsg[] = $error;
					}
					throw new Exception(implode("\n", $errorMsg));
				}
				foreach ($data as $line){
					if (is_array($line)){
						foreach ($line as $name => $subline){
							if (is_array($subline))
								continue;
							echo htmlspecialchars(strip_tags($name)) . ': ' . htmlspecialchars(strip_tags($subline)) . "\n";
						}
					}
					else{
						echo htmlspecialchars(strip_tags($line)) . "\n";
					}
				}
			}
			else{
				$line = htmlspecialchars(strip_tags($data));
				echo $line . "\n";
			}
			$this->flush();
		}
	}

	public function echoOn ()
	{
		$obLevel = @ob_get_level();
		for ($i = 0; $i < $obLevel; $i ++){
			@ob_end_clean();
		}
		if (is_callable('apache_setenv')){
			@apache_setenv('no-gzip', 1);
		}
		@ini_set('output_buffering', 0);
		@ini_set('zlib.output_compression', 0);
		@ini_set('implicit_flush', 1);
		@ob_implicit_flush(1);
		$this->_echo = true;
		header("Content-Type: text/html; charset=utf-8");
		header("Cache-Control: private, no-cache, must-revalidate");
		for ($i = 0; $i < 5; $i ++){
			echo '                                                                                                                                                                                                                                      ';
		} // Today's main course: ~1kB of Geko's favourite white spaces to avoid Google Chrome's buffering.
		$style = 'body {margin: 0; font-size: 11px; color: #464423; background: transparent;}';
		echo '<html><head><style>' . $style . '</style></head><body><pre>';
	}

	public function echoOff ()
	{
		echo '</pre><span style="display: none;">_!END!_</span></body></html>';
		$this->flush();
		$this->_echo = true;
		ob_start();
	}

	public function echoError ($message)
	{
		exit('<span style="display: none;">_!ERROR(' . $message . ')!_</span>');
	}

	protected function flush ()
	{
		flush();
	}

	public function getOutput ()
	{
		return $this->_out;
	}

	protected function setTemplateObject ()
	{
		$this->tpl = new HTML_Template_IT($this->tpl_dir);
	}

	protected function setTemplateDir ()
	{
		$this->tpl_dir = __DESIGN_PATH__ . DS . 'frontend' . DS;
		if (! file_exists($this->tpl_dir) || ! is_readable($this->tpl_dir)){
			PEAR::raiseError('<b>Error:</b> the template directory <i>(' . $this->tpl_dir . ')</i> is not a directory, or not readable. Make sure the \'data_dir\' of your config file <i>(' . $this->config->get('data_dir') . ')</i> points to the correct location !');
		}
	}

	public function setTemplateVariables ()
	{
		$this->setTemplateDir();
		$this->setTemplateObject();
	}

	public function _initTemplate ($file)
	{
		PEAR::staticPushErrorHandling(PEAR_ERROR_DIE);
		$this->setTemplateVariables();
		$this->tpl->loadTemplateFile($file, false, false);
		$this->inputContent();
		PEAR::staticPopErrorHandling(); // reset error handling
		return $this->tpl;
	}

	protected function inputContent ()
	{
		foreach ($this->_templateVariables as $variable => $content){
			$this->tpl->setVariable($variable, $content);
		}
		$this->tpl->parseCurrentBlock();
	}

	public function setContent ($variable, $content)
	{
		$this->_templateVariables[$variable] = $content;
	}

	public function outputBegin ()
	{
		$this->tpl = $this->_initTemplate('index.tpl');
		$this->tpl->show();
	}

	public function log ($text)
	{
		if ($text != '.'){
			echo $text . '<br/>';
		}
	}
}