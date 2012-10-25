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
 * $Id: layoutgenerator.php 655 2012-04-24 08:51:44Z gekosale $ 
 */
class LayoutGeneratorModel extends Model
{
	
	protected $_subpageName;
	protected $_columns;
	protected $_width;
	protected $_disabled;

	public function LoadLayout ($subpageName)
	{
		if ($this->registry->session->getActiveClientid() > 0){
			App::addClientHistorylog();
		}
		
		$this->_subpageName = $subpageName;
		$this->_columns = Array();
		if (($this->_columns = Cache::loadObject('columns' . $subpageName)) === FALSE){
			$query = '
						SELECT
							SLC.order AS `column`,
							SLC.width AS width,
							SLB.layoutboxid AS id,
							SLB.colspan AS colspan,
							SLB.collapsed AS collapsed
						FROM
							subpage S
							JOIN subpagelayout SL ON SL.subpageid = S.idsubpage
							JOIN subpagelayoutcolumn SLC ON SL.idsubpagelayout = SLC.subpagelayoutid
							LEFT JOIN subpagelayoutcolumnbox SLB ON SLC.idsubpagelayoutcolumn = SLB.subpagelayoutcolumnid
						WHERE
							S.name = :subpagename
							AND COALESCE(SL.viewid, 0) = (
								SELECT
									COALESCE(SL.viewid, 0) AS view
								FROM
									subpage S
									LEFT JOIN subpagelayout SL ON SL.subpageid = S.idsubpage
								WHERE
									S.name = :subpagename
									AND (SL.viewid = :viewid OR SL.viewid IS NULL)
								ORDER BY
									view DESC
								LIMIT 1
							)
						GROUP BY
							SLB.idsubpagelayoutcolumnbox
						ORDER BY
							SLC.order,
							SLB.order
					';
			$stmt = App::getRegistry()->db->prepareStatement($query);
			$stmt->set('subpagename', $this->_subpageName);
			$stmt->set('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			$previousColumn = 0;
			$currentColumn = - 1;
			while ($rs->next()){
				$column = $rs->getInt('column');
				if ($previousColumn != $column){
					$this->_columns[] = Array(
						'width' => $rs->getInt('width'),
						'boxes' => Array()
					);
					$previousColumn = $column;
					$currentColumn ++;
				}
				$this->_columns[$currentColumn]['boxes'][] = Array(
					'id' => $rs->getInt('id'),
					'colspan' => $rs->getInt('colspan'),
					'collapsed' => $rs->getBoolean('collapsed')
				);
			}
			Cache::saveObject('columns' . $subpageName, $this->_columns, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
	
	}

	/**
	 * GetTemplateData
	 * 
	 * Zwraca kod xhtml oraz JS opisujący boksy i ich zawartość dla podstrony, w przystępnej formie
	 * tablicy asocjacyjnej którą można przekazać bezpośrednio do szablonu.
	 * 
	 * @param string $containerId Identyfikator elementu, xhtml wewnatrz ktorego beda sie znajdowaly boksy.
	 * @return array Tablica zawierająca kod xhtml zawartości boksów oraz kod JS inicjalizujący boksy.
	 */
	public function GetTemplateData ($containerId, $action = 'index')
	{
		return Array(
			'content' => $this->GenerateContent($action),
			'js' => $this->GenerateScript($containerId)
		);
	}

	/**
	 * GenerateContent
	 * 
	 * Generuje kod xhtml dla boksow, wraz z ich zawartoscia.
	 * 
	 * @return string Kod xhtml bedacy zawartoscia wszystkich boksow na podstronie.
	 */
	public function GenerateContent ($action = 'index')
	{
		$query = '
				SELECT
					PSV.value AS value
				FROM
					pageschemecssvalue PSV
					LEFT JOIN pageschemecss PSC ON PSV.pageschemecssid = PSC.idpageschemecss
					LEFT JOIN pagescheme PS ON PSC.pageschemeid = PS.idpagescheme
				WHERE
					PS.default = 1
					AND PSC.selector = \'#main-container\'
					AND (PS.viewid = :viewid OR PS.viewid IS NULL)
				ORDER BY PS.viewid DESC
				LIMIT 1
			';
		$stmt = App::getRegistry()->db->prepareStatement($query);
		$stmt->set('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$rs->next();
		$width = $rs->getString('value');
		$content = '';
		$widths = Array();
		$autoWidths = 0;
		$widthLeft = intval($width);
		$marginWidth = 20;
		$widthLeft += $marginWidth;
		foreach ($this->_columns as $i => $column){
			$widths[$i] = $column['width'];
			if ($column['width'] == 0){
				$autoWidths ++;
			}
			$widthLeft -= $column['width'] + $marginWidth;
		}
		if (($LayoutBoxParams = Cache::loadObject('layoutbox')) === FALSE){
			$LayoutBoxParams = $this->getLayoutBoxParams();
			Cache::saveObject('layoutbox', $LayoutBoxParams, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		foreach ($this->_columns as $i => $column){
			$margin = ($i > 0) ? $marginWidth : 0;
			if ($widths[$i] == 0){
				$widths[$i] = $widthLeft / $autoWidths;
			}
			$page = strtolower($this->_subpageName);
			$content .= "\n<div class=\"layout-column {$page}\" id=\"layout-column-{$i}\" style=\"width: {$widths[$i]}px; margin-left: {$margin}px;\">";
			foreach ($column['boxes'] as $box){
				if (! isset($box['id'])){
					continue;
				}
				$showBox = false;
				
				if (isset($LayoutBoxParams[$box['id']]['js']['iEnableBox'])){
					if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 0){
						$showBox = true;
					}
					
					if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 1 && $this->registry->session->getActiveClientid() > 0){
						$showBox = true;
					}
					
					if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 2 && $this->registry->session->getActiveClientid() == NULL){
						$showBox = true;
					}
					
					if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 3){
						$showBox = false;
					}
				}
				else{
					$showBox = true;
				}
				
				if ($showBox == true){
					$controller = BoxController::Create($box['id'], $LayoutBoxParams[$box['id']]);
					$contents = $controller->getBoxContents($action);
					if ($controller->boxVisible()){
						$this->boxes[] = $box['id'];
						$content .= "\n" . $contents;
					}
				}
			}
			$content .= "\n</div>";
		}
		return $content;
	}

	public function getLayoutBoxParams ()
	{
		$LayoutBoxParams = Array();
		
		$query = '
					SELECT
						LB.idlayoutbox AS id,
						LB.controller AS controller,
						IF(LBT.title IS NOT NULL,LBT.title, LB.controller) AS heading,
						LB.layoutboxschemeid AS scheme
					FROM
						layoutbox LB
						LEFT JOIN layoutboxtranslation LBT ON LBT.layoutboxid = LB.idlayoutbox AND LBT.languageid = :languageid
					GROUP BY LB.idlayoutbox
				';
		$stmt = App::getRegistry()->db->prepareStatement($query);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$boxId = $rs->getInt('id');
			
			$queryJS = '
					SELECT
						JS.variable AS variable,
						JS.value AS value
					FROM
						layoutboxjsvalue AS JS
					WHERE
						JS.layoutboxid = :layoutboxid
				';
			$stmtJS = App::getRegistry()->db->prepareStatement($queryJS);
			$stmtJS->set('layoutboxid', $boxId);
			$rsJS = $stmtJS->executeQuery();
			$jsVariables = Array();
			while ($rsJS->next()){
				$jsVariables[$rsJS->getString('variable')] = $rsJS->getString('value');
			}
			
			$queryCSS = '
					SELECT
						CS.variable AS variable,
						CS.value AS value
					FROM
						layoutboxcontentspecificvalue AS CS
					WHERE
						CS.layoutboxid = :layoutboxid
						AND ((CS.languageid = :languageid) OR (CS.languageid IS NULL))
				';
			$stmtCSS = App::getRegistry()->db->prepareStatement($queryCSS);
			$stmtCSS->setInt('layoutboxid', $boxId);
			$stmtCSS->setInt('languageid', Helper::getLanguageId());
			$rsCSS = $stmtCSS->executeQuery();
			$boxAttributes = Array();
			while ($rsCSS->next()){
				$boxAttributes[$rsCSS->getString('variable')] = $rsCSS->getString('value');
			}
			
			$LayoutBoxParams[$boxId] = Array(
				'controller' => $rs->getString('controller'),
				'heading' => $rs->getString('heading'),
				'scheme' => $rs->getString('scheme'),
				'js' => $jsVariables,
				'css' => $boxAttributes
			);
		
		}
		return $LayoutBoxParams;
	
	}

	/**
	 * _GenerateLayoutHash
	 * 
	 * Z uzyciem funkcji skrotu generuje kod, ktory identyfikuje rozlozenie boksow na danej podstronie. Sluzy do tego,
	 * by po zmianie ukladu przez administratora, u uzytkownika zostal usuniety plik cookie zawierajacy jego wlasne
	 * rozmieszczenie boksow.
	 * 
	 * @return string Hash odpowiadajacy ulozeniu boksow na podstronie.
	 */
	protected function _GenerateLayoutHash ()
	{
		return md5(json_encode($this->_columns) . ' ' . json_encode($this->boxes));
	}

	/**
	 * GenerateScript
	 * 
	 * Generuje kod JS potrzebny do zainicjowania obslugi boksow na podstronie. Zwrocony kod jest opatrzony
	 * tagami <script/>. Wewnatrz kodu sa zawarte opcje rozlozenia boksow w kolumnach, jak i szerokosci
	 * tych ostatnich.
	 * 
	 * @param string $containerId Identyfikator elementu, xhtml wewnatrz ktorego beda sie znajdowaly boksy.
	 * @return string Kod JS zawarty wewnatrz tagow <script/>.
	 */
	public function GenerateScript ($containerId)
	{
		if (($LayoutBoxParams = Cache::loadObject('layoutbox')) === FALSE){
			$LayoutBoxParams = $this->getLayoutBoxParams();
			Cache::saveObject('layoutbox', $LayoutBoxParams, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		$columns = Array();
		foreach ($this->_columns as $column){
			$boxes = Array();
			foreach ($column['boxes'] as $box){
				if (! isset($box['id'])){
					continue;
				}
				if (in_array($box['id'], $this->boxes)){
					$showBox = false;
					
					if (isset($LayoutBoxParams[$box['id']]['js']['iEnableBox'])){
						if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 0){
							$showBox = true;
						}
						
						if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 1 && $this->registry->session->getActiveClientid() > 0){
							$showBox = true;
						}
						
						if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 2 && $this->registry->session->getActiveClientid() == NULL){
							$showBox = true;
						}
						
						if ($LayoutBoxParams[$box['id']]['js']['iEnableBox'] == 3){
							$showBox = false;
						}
					}
					else{
						$showBox = true;
					}
					
					if ($showBox == true){
						$boxes[] = '
											{
												sName: \'' . $box['id'] . '\',
												bCollapsed: ' . ($box['collapsed'] ? 'true' : 'false') . ',
												iSpan: ' . $box['colspan'] . '
											}';
					}
				
				}
			}
			$columns[] = '
									new GLayoutColumn({
										iWidth: ' . $column['width'] . ',
										asBoxes: [' . implode(',', $boxes) . '
										]
									})';
		}
		$script = '
				<script type="text/javascript">
					/* <![CDATA[ */
						GCore.OnLoad(function() {
							$(\'#' . $containerId . '\').GLayoutBoxes({
								aoColumns: [' . implode(',', $columns) . '
								],
								sLayoutHash: \'' . $this->_GenerateLayoutHash() . '\'
							});
						});
					/* ]]> */
				</script>
			';
		return $script;
	}

}