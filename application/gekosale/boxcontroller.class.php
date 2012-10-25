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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: boxcontroller.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

abstract class BoxController extends Controller
{
	
	// Sciezka do pliku .tpl dla boksu
	const LAYOUT_BOX_TEMPLATE = 'design/_tpl/frontend/core/layoutbox.tpl';
	
	protected $_boxId;
	protected $_heading = '';
	protected $_scheme = '';
	protected $_style = '';
	protected $_jsVariables;
	protected $_boxAttributes;
	protected $_boxParams;

	/*
		* Create
		* 
		* Fabryka instancji kontrolerow boksow. Prowadzi do inicjalizacji boksu i pobrania
		* powiazanych z nim danych z bazy.
		* Instancje kontrolerow boksow nalezy tworzyc tylko w taki sposob.
		* Ta statyczna metoda jest wywolywana przez LayoutGeneratorModel w celu utworzenia
		* wszystkich boksow i pobrania ich zawartosci.
		* 
		* @param int $boxId Identyfikator layout-boxu, czyli idlayoutbox w bazie.
		* @return object BoxController Instancja kontrolera boksu.
		*/
	public static function Create ($boxId, $LayoutBoxParams)
	{
		
		$controllerName = $LayoutBoxParams['controller'];
		$controller = App::getController($controllerName, NULL, FALSE);
		$controller->_boxId = $boxId;
		$controller->_heading = $LayoutBoxParams['heading'];
		$controller->_scheme = $LayoutBoxParams['scheme'];
		$controller->_loadJSVariables($LayoutBoxParams['js']);
		$controller->_loadBoxAttributes($LayoutBoxParams['css']);
		$controller->_loadBoxParams();
		return $controller;
	}

	/*
		* getBoxHeading
		* 
		* Zwraca naglowek boksu.
		* Nalezy przyslonic ponizsza metode, jesli naglowek boksu ma byc generowany automatycznie
		* na podstawie np. atrybutow boksu.
		* 
		* @return string Tresc naglowka boksu.
		*/
	
	public function boxVisible ()
	{
		return true;
	}

	public function getBoxHeading ()
	{
		return $this->_heading;
	}

	/*
		* getBoxClassname
		* 
		* Zwraca ciag znakow, ktory jest klasa xhtml-owego kontenera boksu i zawiera w sobie opcje
		* behawioralne, jak i okreslenie szablonu boksu.
		* 
		* @return string Klasa xhtml-owego kontenera boksu.
		*/
	public function getBoxClassname ()
	{
		$className = Array(
			'layout-box'
		);
		$className[] = $this->getBoxTypeClassname();
		if (isset($this->_jsVariables['bFixedPosition']) && (int) $this->_jsVariables['bFixedPosition']){
			$className[] = 'layout-box-option-fixed-true';
		}
		else{
			$className[] = 'layout-box-option-fixed-false';
		}
		if (isset($this->_jsVariables['bClosingProhibited']) && (int) $this->_jsVariables['bClosingProhibited']){
			$className[] = 'layout-box-option-closable-false';
		}
		else{
			$className[] = 'layout-box-option-closable-true';
		}
		if (isset($this->_jsVariables['bCollapsingProhibited']) && (int) $this->_jsVariables['bCollapsingProhibited']){
			$className[] = 'layout-box-option-collapsible-false';
		}
		else{
			$className[] = 'layout-box-option-collapsible-true';
		}
		if (isset($this->_jsVariables['bExpandingProhibited']) && (int) $this->_jsVariables['bExpandingProhibited']){
			$className[] = 'layout-box-option-expandable-false';
		}
		else{
			$className[] = 'layout-box-option-expandable-true';
		}
		if (isset($this->_jsVariables['bNoHeader']) && (int) $this->_jsVariables['bNoHeader']){
			$className[] = 'layout-box-option-header-false';
		}
		else{
			$className[] = 'layout-box-option-header-true';
		}
		if (isset($this->_jsVariables['iDefaultSpan']) && (int) $this->_jsVariables['iDefaultSpan']){
			$className[] = 'layout-box-option-span-' . $this->_jsVariables['iDefaultSpan'];
		}
		return implode(' ', $className);
	}

	/*
		* getBoxTypeClassname
		* 
		* Zwraca komponent ciagu znakow bedacego klasa xhtml-owego kontenera boksu, ktory dotyczy typu
		* danego boksu.
		* 
		* @return string Klasa xhtml-owego kontenera boksu zwiazana z jego typem.
		*/
	public function getBoxTypeClassname ()
	{
		$className = get_class($this);
		$className = substr($className, 0, strlen($className) - strlen('BoxController'));
		$className = strtolower(preg_replace("/([A-Z])/", "-$1", $className));
		$className = trim($className, '-');
		return 'layout-box-type-' . $className;
	}

	/*
		* _loadJSVariables
		* 
		* Pobiera z bazy zmienne behawioralne boksu i umieszcza je w tablicy $_jsVariables. Sa one
		* wykorzystywane przez metode getBoxClassname().
		*/
	protected function _loadJSVariables ($LayoutBoxParams)
	{
		$this->_jsVariables = $LayoutBoxParams;
	}

	/*
		* _loadBoxAttributes
		*
		* Pobiera z bazy atrybuty boksu zalezne od rodzaju jego zawartosci, i umieszcza je w tablicy
		* $_boxAttributes pod nazwami odczytanymi z bazy. Dzieki temu w kontrolerze jest swobodny dostep
		* do wszystkich wartosci poprzez $this->_boxAttributes['nazwa_zmiennej'].
		*/
	protected function _loadBoxAttributes ($LayoutBoxParams)
	{
		$this->_boxAttributes = $LayoutBoxParams;
	}

	/*
		* _loadBoxParams
		*
		* Pobiera z URLa parametry boksu i umieszcza je w tablicy $_boxParams pod nazwami odczytanymi
		* ze zdekodowanego fragmentu URLa. Dzieki temu w kontrolerze jest swobodny dostep
		* do wszystkich wartosci dotyczacych tego, konkretnego boksu poprzez $this->_boxParams['nazwa_zmiennej'].
		*/
	protected function _loadBoxParams ()
	{
		$this->_boxParams = $this->registry->core->getParamsForBox($this->_boxId);
	}

	/*
		* getParam
		*
		* Zwraca wartość zadanego parametru przekazanego poprzez URL. Wartość dotyczy tego konkretnego boksu.
		* 
		* @param string $paramName Nazwa parametru do pobrania
		* @return string Wartosc parametru
		*/
	protected function getParam ($paramName)
	{
		if (isset($this->_boxParams[$paramName])){
			return $this->_boxParams[$paramName];
		}
		return null;
	}

	/**
	 * getBoxContents
	 * 
	 * Zwraca kod xhtml odpowiadajacy zawartosci boksu. Opakowuje wynik przetwarzania szablonu dla zadanej akcji (domyslnie
	 * index) w kontener typowy dla layout-boxu, korzystajac z szablonu LAYOUT_BOX_TEMPLATE.
	 * Najpierw czysci output buffer, po czym wywoluje odpowiednia akcje kontrolera, pobiera zawartosc output buffera
	 * i opakowuje ja w szablon boksu, co jest wynikiem dzialania metody. Na koniec przywraca pierwotny stan output buffera.
	 * 
	 * @param string $action Nazwa akcji kontrolera, ktora ma zostac wywolana w celu wygenerowania zawartosci. Domyslnie index.
	 * @return string Kod xhtml boksu wraz z zawartoscia.
	 */
	public function getBoxContents ($action = 'index')
	{
		if (! is_callable(Array(
			$this,
			$action
		))){
			//throw new CoreException("Tried to invoke an action <em>{$action}</em> that doesn't exist in the <em>{$this->getName()}</em> controller.");
			

			$action = 'index';
			if (! is_callable(Array(
				$this,
				$action
			))){
				throw new CoreException("Tried to invoke an action <em>{$action}</em> that doesn't exist in the <em>{$this->getName()}</em> controller.");
			}
		}
		
		$this->disableLayout();
		$ob = ob_get_clean();
		
		try{
			ob_start();
			$this->registry->template->assign('box', Array(
				'id' => $this->_boxId
			));
			call_user_func(Array(
				$this,
				$action
			));
			$box = ob_get_clean();
		}
		catch (Exception $e){
			$box = $e->getMessage();
		}
		ob_start();
		$client = App::getModel('client')->getClient();
		$this->registry->template->saveState();
		$this->registry->template->clear_all_assign();
		$this->registry->template->assign('DESIGNPATH', DESIGNPATH);
		$this->registry->template->assign('URL', App::getURLAdress());
		$this->registry->template->assign('CURRENT_CONTROLLER', $this->registry->router->getCurrentController());
		$this->registry->template->assign('languageCode', $this->registry->session->getActiveLanguage());
		$this->registry->template->assign('clientdata', $client);
		$this->registry->template->assign('box', Array(
			'id' => 'layout-box-' . $this->_boxId,
			'schemeClass' => $this->getBoxClassname(),
			'heading' => $this->getBoxHeading(),
			'content' => $box,
			'style' => $this->_style
		));
		
		$path = $this->loadTemplate('layoutbox.tpl');
		$namespace = $this->registry->loader->getCurrentNamespace();
		if (is_file($path)){
			$output = $this->registry->template->fetch($path);
		}
		else{
			if (is_file(ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'layoutbox.tpl')){
				$output = $this->registry->template->fetch(ROOTPATH . 'design' . DS . '_tpl' . DS . 'frontend' . DS . $namespace . DS . 'layoutbox.tpl');
			}
			else{
				$output = $this->registry->template->fetch(ROOTPATH . self::LAYOUT_BOX_TEMPLATE);
			}
		}
		
		$error = $this->registry->template->get_template_vars('error');
		$this->registry->template->reloadState();
		if (! empty($error)){
			$this->registry->template->assign('error', $error);
		}
		echo $ob;
		$this->enableLayout();
		return $output;
	
	}

}