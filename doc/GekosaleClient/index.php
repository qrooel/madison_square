<?php
require_once 'GekosaleClient.php';
$client = new GekosaleClient('http://www.sklep.pl/api', 'klucz_api_z_konfiguracji_sklepu');

try{
	print_r($client->getProducts());
}
catch (Exception $e){
	echo nl2br($e->getMessage()) . '<br />' . "\n";
}
try{
	print_r($client->getProduct(29));
}
catch (Exception $e){
	echo nl2br($e->getMessage()) . '<br />' . "\n";
}
try{
	print_r($client->getStock(29));
}
catch (Exception $e){
	echo nl2br($e->getMessage()) . '<br />' . "\n";
}
