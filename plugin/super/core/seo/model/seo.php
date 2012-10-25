<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 691 $
 * $Author: gekosale $
 * $Date: 2012-09-06 16:11:23 +0200 (Cz, 06 wrz 2012) $
 * $Id: seo.php 691 2012-09-06 14:11:23Z gekosale $
 */
class seoModel extends Model {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	public function clearSeoUTF ($string) {
		$a = array(
			'À',
			'Á',
			'Â',
			'Ã',
			'Ä',
			'Å',
			'Æ',
			'Ç',
			'È',
			'É',
			'Ê',
			'Ë',
			'Ì',
			'Í',
			'Î',
			'Ï',
			'Ð',
			'Ñ',
			'Ò',
			'Ó',
			'Ô',
			'Õ',
			'Ö',
			'Ø',
			'Ù',
			'Ú',
			'Û',
			'Ü',
			'Ý',
			'ß',
			'à',
			'á',
			'â',
			'ã',
			'ä',
			'å',
			'æ',
			'ç',
			'è',
			'é',
			'ê',
			'ë',
			'ì',
			'í',
			'î',
			'ï',
			'ñ',
			'ò',
			'ó',
			'ô',
			'õ',
			'ö',
			'ø',
			'ù',
			'ú',
			'û',
			'ü',
			'ý',
			'ÿ',
			'Ā',
			'ā',
			'Ă',
			'ă',
			'Ą',
			'ą',
			'Ć',
			'ć',
			'Ĉ',
			'ĉ',
			'Ċ',
			'ċ',
			'Č',
			'č',
			'Ď',
			'ď',
			'Đ',
			'đ',
			'Ē',
			'ē',
			'Ĕ',
			'ĕ',
			'Ė',
			'ė',
			'Ę',
			'ę',
			'Ě',
			'ě',
			'Ĝ',
			'ĝ',
			'Ğ',
			'ğ',
			'Ġ',
			'ġ',
			'Ģ',
			'ģ',
			'Ĥ',
			'ĥ',
			'Ħ',
			'ħ',
			'Ĩ',
			'ĩ',
			'Ī',
			'ī',
			'Ĭ',
			'ĭ',
			'Į',
			'į',
			'İ',
			'ı',
			'Ĳ',
			'ĳ',
			'Ĵ',
			'ĵ',
			'Ķ',
			'ķ',
			'Ĺ',
			'ĺ',
			'Ļ',
			'ļ',
			'Ľ',
			'ľ',
			'Ŀ',
			'ŀ',
			'Ł',
			'ł',
			'Ń',
			'ń',
			'Ņ',
			'ņ',
			'Ň',
			'ň',
			'ŉ',
			'Ō',
			'ō',
			'Ŏ',
			'ŏ',
			'Ő',
			'ő',
			'Œ',
			'œ',
			'Ŕ',
			'ŕ',
			'Ŗ',
			'ŗ',
			'Ř',
			'ř',
			'Ś',
			'ś',
			'Ŝ',
			'ŝ',
			'Ş',
			'ş',
			'Š',
			'š',
			'Ţ',
			'ţ',
			'Ť',
			'ť',
			'Ŧ',
			'ŧ',
			'Ũ',
			'ũ',
			'Ū',
			'ū',
			'Ŭ',
			'ŭ',
			'Ů',
			'ů',
			'Ű',
			'ű',
			'Ų',
			'ų',
			'Ŵ',
			'ŵ',
			'Ŷ',
			'ŷ',
			'Ÿ',
			'Ź',
			'ź',
			'Ż',
			'ż',
			'Ž',
			'ž',
			'ſ',
			'ƒ',
			'Ơ',
			'ơ',
			'Ư',
			'ư',
			'Ǎ',
			'ǎ',
			'Ǐ',
			'ǐ',
			'Ǒ',
			'ǒ',
			'Ǔ',
			'ǔ',
			'Ǖ',
			'ǖ',
			'Ǘ',
			'ǘ',
			'Ǚ',
			'ǚ',
			'Ǜ',
			'ǜ',
			'Ǻ',
			'ǻ',
			'Ǽ',
			'ǽ',
			'Ǿ',
			'ǿ',
			'Ą',
			'Ć',
			'Ę',
			'Ł',
			'Ń',
			'Ó',
			'Ś',
			'Ź',
			'Ż',
			'ą',
			'ć',
			'ę',
			'ł',
			'ń',
			'ó',
			'ś',
			'ź',
			'ż',
			' ',
			',',
			'_',
			'.',
			'?',
			'',
			'|',
			'(',
			')',
			'"',
			'*',
			'+',
			'&',
			':',
			"'",
			'"',
			'!'
		);
		$b = array(
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'AE',
			'C',
			'E',
			'E',
			'E',
			'E',
			'I',
			'I',
			'I',
			'I',
			'D',
			'N',
			'O',
			'O',
			'O',
			'O',
			'O',
			'O',
			'U',
			'U',
			'U',
			'U',
			'Y',
			's',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'ae',
			'c',
			'e',
			'e',
			'e',
			'e',
			'i',
			'i',
			'i',
			'i',
			'n',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'u',
			'u',
			'u',
			'u',
			'y',
			'y',
			'A',
			'a',
			'A',
			'a',
			'A',
			'a',
			'C',
			'c',
			'C',
			'c',
			'C',
			'c',
			'C',
			'c',
			'D',
			'd',
			'D',
			'd',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'G',
			'g',
			'G',
			'g',
			'G',
			'g',
			'G',
			'g',
			'H',
			'h',
			'H',
			'h',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'IJ',
			'ij',
			'J',
			'j',
			'K',
			'k',
			'L',
			'l',
			'L',
			'l',
			'L',
			'l',
			'L',
			'l',
			'l',
			'l',
			'N',
			'n',
			'N',
			'n',
			'N',
			'n',
			'n',
			'O',
			'o',
			'O',
			'o',
			'O',
			'o',
			'OE',
			'oe',
			'R',
			'r',
			'R',
			'r',
			'R',
			'r',
			'S',
			's',
			'S',
			's',
			'S',
			's',
			'S',
			's',
			'T',
			't',
			'T',
			't',
			'T',
			't',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'W',
			'w',
			'Y',
			'y',
			'Y',
			'Z',
			'z',
			'Z',
			'z',
			'Z',
			'z',
			's',
			'f',
			'O',
			'o',
			'U',
			'u',
			'A',
			'a',
			'I',
			'i',
			'O',
			'o',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'A',
			'a',
			'AE',
			'ae',
			'O',
			'o',
			'A',
			'C',
			'E',
			'L',
			'N',
			'O',
			'S',
			'Z',
			'Z',
			'a',
			'c',
			'e',
			'l',
			'n',
			'o',
			's',
			'z',
			'z',
			'-',
			'',
			'',
			'-',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			''
		);
		$str = str_replace($a, $b, strip_tags(html_entity_decode($string)));
		$str = str_replace('-------------', '-', $str);
		$str = str_replace('-----------', '-', $str);
		$str = str_replace('-------', '-', $str);
		$str = str_replace('-----', '-', $str);
		$str = str_replace('---', '-', $str);
		$str = str_replace('--', '-', $str);
		return $str;
	}

	public function doAJAXCreateSeo ($request) {
		$seo = $this->clearSeoUTF(trim($request['name']));
		return Array(
			'seo' => str_replace('/', '', strtolower($seo))
		);
	}

	public function doAJAXRefreshSeoProducts () {
		@set_time_limit(0);
		$this->registry->db->setAutoCommit(false);
		$sql = 'SELECT
					PT.productid AS id,
					PT.name AS name
				FROM producttranslation PT
				WHERE PT.languageid = :languageid
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$seo = $this->doAJAXCreateSeo(Array(
				'name' => $rs->getString('name')
			));
			
			$sql = 'UPDATE producttranslation SET
						seo = :seo
					WHERE languageid = :languageid AND productid = :id
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('seo', $seo['seo']);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$stmt->setInt('id', $rs->getInt('id'));
			$stmt->executeQuery();
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function doAJAXCreateSeoCategory ($request) {
		
		$name = trim($request['name']);
		
		if (isset($request['id'])){
			$id = $request['id'];
		}
		else{
			$id = $this->registry->core->getParam();
		}
		
		$sql = 'SELECT
					GROUP_CONCAT(SUBSTRING(IF(CT.categoryid = :id, :name, LOWER(CT.name)), 1) ORDER BY C.order DESC SEPARATOR \'/\') AS seo
				FROM categorytranslation CT
				LEFT JOIN categorypath C ON C.ancestorcategoryid = CT.categoryid
				WHERE C.categoryid = :id AND CT.languageid = :languageid
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', $request['language']);
		$stmt->setString('name', $name);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if (! is_null($rs->getString('seo'))){
				$seo = $this->clearSeoUTF($rs->getString('seo'));
			}
			else{
				$seo = $this->clearSeoUTF($name);
			}
		}
		else{
			$seo = $this->clearSeoUTF($name);
		}
		
		return Array(
			'seo' => strtolower($seo)
		);
	}

	public function doRefreshUrlMap () {
		$this->doRefreshUrlMapProducts();
		$this->doRefreshUrlMapCategories();
	}

	public function doRefreshUrlMapProducts () {
		$sql = 'INSERT INTO urlmap (url, controller, params, pkid)
				SELECT CONCAT(seo,:suffix), :controller, seo, productid FROM producttranslation 
				ON DUPLICATE KEY UPDATE url = url
				
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('suffix', '.html');
		$stmt->setString('controller', 'productcart');
		$stmt->executeUpdate();
	}

	public function doRefreshUrlMapCategories () {
		$sql = 'INSERT INTO urlmap (url, controller, params, pkid)
				SELECT CONCAT(seo,:suffix), :controller, seo, categoryid FROM categorytranslation 
				ON DUPLICATE KEY UPDATE url = url
				
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('suffix', '.html');
		$stmt->setString('controller', 'categorylist');
		$stmt->executeUpdate();
	}

	public function doRefreshSeoCategory () {
		$this->registry->db->setAutoCommit(false);
		$sql = 'SELECT idcategory FROM category';
		$stmt = $stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			
			$sql2 = 'SELECT
						CT.languageid,
						GROUP_CONCAT(SUBSTRING(IF(CT.categoryid = :id, CT.name, LOWER(CT.name)), 1) ORDER BY C.order DESC SEPARATOR \'/\') AS seo
					FROM categorytranslation CT
					LEFT JOIN categorypath C ON C.ancestorcategoryid = CT.categoryid
					WHERE C.categoryid = :id 
					GROUP BY C.categoryid, CT.languageid
					';
			$stmt2 = $this->registry->db->prepareStatement($sql2);
			$stmt2->setInt('id', $rs->getInt('idcategory'));
			$rs2 = $stmt2->executeQuery();
			if ($rs2->first()){
				
				$seo = $this->clearSeoUTF($rs2->getString('seo'));
				
				$sql = 'UPDATE categorytranslation SET
							seo = :seo
						WHERE
							categoryid = :categoryid AND languageid = :languageid
						';
				
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('categoryid', $rs->getInt('idcategory'));
				$stmt->setInt('languageid', $rs2->getInt('languageid'));
				$stmt->setString('seo', strtolower($seo));
				$stmt->executeUpdate();
			}
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		App::getModel('category')->flushCache();
	}

	public function doAJAXRefreshSeoCategory () {
		$objResponse = new xajaxResponse();
		$this->doRefreshSeoCategory();
		$objResponse->script('window.location.reload(false)');
		return $objResponse;
	}

	public function getMetadataForPage () {
		$controller = $this->registry->router->getCurrentController();
		$Data = Array();
		$sql = "SELECT
					VT.keyword_title,
					C.description,
					VT.keyword,
					VT.keyword_description
				FROM controller C
				LEFT JOIN viewtranslation VT ON VT.viewid = :viewid AND languageid = :languageid
				WHERE C.name = :controller AND C.mode = 0";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('controller', $controller);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if ($rs->getString('keyword_title') == NULL || $rs->getString('keyword_title') == ''){
				$keyword_title = ($controller == 'mainside') ? $this->registry->session->getActiveShopName() : $this->registry->core->getMessage($rs->getString('description'));
			}
			else{
				$keyword_title = $rs->getString('keyword_title');
			}
			$title = ($controller == 'mainside') ? $keyword_title : $this->registry->core->getMessage($rs->getString('description'));
			$Data = Array(
				'keyword_title' => $title,
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}
}