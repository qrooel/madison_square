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
class CeneoModel extends Model{

	public function __construct($registry, $modelFile){
		parent::__construct($registry, $modelFile);
	}

	public function getChildCategories($parentCategory = 0) {
		$sql = '
				SELECT
					A.idorginal AS id,
					A.name,
					COUNT(B.idceneo) AS has_children
				FROM
					ceneo A
					LEFT JOIN ceneo B ON A.idorginal = B.parentorginalid
				WHERE
					A.parentorginalid = :parent
				GROUP BY
					A.idceneo
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('parent', $parentCategory);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while($rs->next()){
			$Data[$rs->getInt('id')] = Array(
			'name' => $rs->getString('name'),
			'hasChildren' => ($rs->getInt('has_children') > 0) ? true: false
			);
		}
		return $Data;
	}

	public function integrationUpdate($Data,$id)
	{
		try {
			$sql = 'DELETE FROM categoryceneo WHERE categoryid = :categoryid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', (int)$id);
			$stmt->executeUpdate();

			$sql = 'INSERT INTO categoryceneo (categoryid, ceneoid)
					VALUES (:categoryid, :ceneoid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', (int)$id);
			$stmt->setInt('ceneoid', (int)$Data['ceneocategory']);
			$stmt->executeQuery();

		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function Populate($id){
		$sql = 'SELECT ceneoid FROM categoryceneo WHERE categoryid = :categoryid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', (int)$id);
		$rs = $stmt->executeQuery();
		if($rs->first()){
			return $rs->getInt('ceneoid');
		}
	}
	
	public function Delete($id){
		$sql = 'DELETE FROM categoryceneo WHERE categoryid = :categoryid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', (int)$id);
		$stmt->executeQuery();
	}
	
	public function updateCategories(){
		$sql = 'TRUNCATE ceneo';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->executeUpdate();

		$this->xmlParser = new xmlParser();
		$categories = $this->xmlParser->parseExternal('http://api.ceneo.pl/Kategorie/dane.xml');
		$this->xmlParser->flush();
		$Data = Array();
		foreach ($categories->Category as $category){
			
			$sql = 'INSERT INTO ceneo (name, idorginal, parentorginalid, path)
					VALUES (:name, :idorginal, :parentorginalid, :path)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', (string) $category->Name);
			$stmt->setString('idorginal', (int) $category->Id);
			$stmt->setString('parentorginalid', 0);
			$stmt->setString('path', (string) $category->Name);
			$stmt->executeQuery();
			
			foreach ($category->Subcategories->Category as $subcategory){
				$sql = 'INSERT INTO ceneo (name, idorginal, parentorginalid, path)
						VALUES (:name, :idorginal, :parentorginalid, :path)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', (string) $subcategory->Name);
				$stmt->setString('idorginal', (int) $subcategory->Id);
				$stmt->setString('parentorginalid', (int) $category->Id);
				$stmt->setString('path', (string) $category->Name."|".(string) $subcategory->Name);
				$stmt->executeQuery();
				
				foreach ($subcategory->Subcategories->Category as $thirdcategory){
					$sql = 'INSERT INTO ceneo (name, idorginal, parentorginalid, path)
							VALUES (:name, :idorginal, :parentorginalid, :path)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('name', (string) $thirdcategory->Name);
					$stmt->setString('idorginal', (int) $thirdcategory->Id);
					$stmt->setString('parentorginalid', (int) $subcategory->Id);
					$stmt->setString('path', (string) $category->Name."|".(string) $subcategory->Name."|".(string) $thirdcategory->Name);
					$stmt->executeQuery();
					
					foreach ($thirdcategory->Subcategories->Category as $fourthcategory){
						$sql = 'INSERT INTO ceneo (name, idorginal, parentorginalid, path)
								VALUES (:name, :idorginal, :parentorginalid, :path)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('name', (string) $fourthcategory->Name);
						$stmt->setString('idorginal', (int) $fourthcategory->Id);
						$stmt->setString('parentorginalid', (int) $thirdcategory->Id);
						$stmt->setString('path', (string) $category->Name."|".(string) $subcategory->Name."|".(string) $thirdcategory->Name."|".(string) $fourthcategory->Name);
						$stmt->executeQuery();
					}
				}
			}
		}
	}

	public function getDescription(){
		return '<p><h3>Ceneo.pl jest największą na polskim rynku porównywarką cen produktów w sklepach internetowych.</h3></p>
<p>Jako jeden z największych serwisów e-commerce w Polsce dostarczamy naszym Użytkownikom narzędzie, które umożliwia <b>szybkie i łatwe wyszukanie 
produktów oraz informacji o produktach</b>, a następnie <b>porównanie ich cen w wielu sklepach</b></p>
<p>Kupującym w sieci chcemy umożliwić <b>szybkie znalezienie atrakcyjnej oferty oraz wiarygodnego sklepu</b>, a współpracującym z nami sklepom oferujemy 
możliwość <b>zwiększenia sprzedaży oraz promocję swojej marki</b> wśród dynamicznie rozwijającej się społeczności internetowej.</p>
<p>Użytkownikom, chcącym skorzystać z profesjonalnego doradztwa w zakresie planowanego zakupu, dostarczamy szereg <b>przewodników zakupowych</b>, które 
oferują kompleksową pomoc w wyborze konkretnego modelu z danej grupy produktów. Posiadamy także obszerną bazę opinii o produktach, tworzoną przez 
ich nabywców i użytkowników.</p>
<p>Jednak Ceneo to nie tylko ceny i produkty. To także <b>opinie o sklepach internetowych</b> - poziomie obsługi klienta, przebiegu realizacji zamówień 
czy terminowości przesyłek, wystawiane przez użytkowników po dokonaniu zakupu. Dzięki programowi <b>„Zaufane opinie”</b>, czyli specjalnemu mechanizmowi 
weryfikacji komentarzy, <b>ograniczamy możliwość wystawiania nieprawdziwych	 opinii o sklepach</b>, zwiększając tym samym 
bezpieczeństwo dokonywania transakcji handlowych w Internecie.</p>
<p><b>Liczba ofert sklepów internetowych, dostępnych na Ceneo.pl, stale rośnie</b>. Każdego dnia dołączają do nas nowe sklepy, współtworząc razem z nami 
największą w polskim Internecie bazę informacji o produktach i ich cenach.</p>
';
	}

	public function getConfigurationFields(){
		return Array();
	}

}