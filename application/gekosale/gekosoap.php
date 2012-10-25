<?php
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */

class GekoSoap extends SoapClient
{
	
	protected $registry;
	protected static $__instance;
	protected static $__soapclient;
	protected $__encoding = 'UTF-8';

	/**
	 * This is a class constructor
	 * 
	 * @param registry
	 * @param string wsdl (null by default)
	 * @param array option (null by default) 
	 * @return registry, $soapclient
	 * @access protected
	 */
	public function __construct ($registry, $wsdl, $option = NULL)
	{
		$this->registry = $registry;
		$this->wsdl = $wsdl;
		self::$__soapclient = $this->setSoapClient($this->wsdl, $option);
	}

	/**
	 *  Initialize SOAP Client object
	 *  
	 *  @param wsdl (required)
	 *  @param options array (null by default)
	 *  @access protected
	 */
	protected function initSoapClient ($wsdl, $options = null)
	{
		if ($options != null){
			$soapclient = new SoapClient($wsdl, $options);
		}
		else{
			$soapclient = new SoapClient($wsdl);
		}
		return $soapclient;
	}

	/**
	 *  Set SOAP Client object
	 * 
	 *  @param wsdl string (param is required)
	 *  @param options array (param is null by default)
	 *  @return soap client objcet
	 *  @access public
	 */
	public function setSoapClient ($options = null)
	{
		if (extension_loaded('soap')){
			$soapclient = $this->initSoapClient($this->wsdl, $options);
		}
		else{
			$soapclient = NULL;
		}
		self::$__soapclient = $soapclient;
		return self::$__soapclient;
	
	}

	/**
	 *  Get SOAP Client object
	 * 
	 *  @return existing soap client object
	 *  @access public
	 */
	public function getSoapClient ()
	{
		if (self::$__soapclient == NULL){
			$options = array(
				'features' => SOAP_USE_XSI_ARRAY_TYPE
			);
			self::$__soapclient = $this->setSoapClient($this->wsdl, $options);
		
		}
		return self::$__soapclient;
	}

	/**
	 *  A low level API function. 
	 *  Is used to make a SOAP call.
	 *  @param string function name
	 *  @param array arguments
	 *  @return array/object or fault
	 *  @throws on error a SoapFault object will be returned
	 */
	public function __call ($function_name, $arguments)
	{
		$result = false;
		$maxRetries = 2;
		$retryCounter = 0;
		while (! $result && $retryCounter < $maxRetries){
			parent::__construct($this->wsdl);
			$result = parent::__soapCall($function_name, $arguments);
			if (is_soap_fault($result)){
				trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
			}
			sleep(1);
			$retryCounter ++;
		}
		if ($retryCounter == $maxRetries){
			throw new SoapFault('Could not connect to host after 2 attempts');
		}
		return $result;
	}
}
?>