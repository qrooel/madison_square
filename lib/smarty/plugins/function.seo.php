<?php
defined('ROOTPATH') or die('No direct access allowed.');

function smarty_function_seo ($params, &$smarty)
{
	
	extract($params);
	
	if ((! isset($controller))){
		$smarty->trigger_error("seo: missing 'controller' parameter");
		return;
	}
	
	$url = App::getRegistry()->core->getControllerNameForSeo($controller);
	
	if (isset($seo)){
		$url .= '/' . $seo;
	}
	
	if (isset($addproducer)){
		$producers = array_merge(array(
			$addproducer
		), $producers);
	}
	
	if (isset($removeproducer)){
		foreach ($producers as $key => $producer){
			if ($producer == $removeproducer){
				unset($producers[$key]);
			}
		}
	}
	
	if (isset($producers) && count($producers)){
		natsort($producers);
		$url .= ',' . implode(',', $producers);
	}
	
	$urlAttributes = Array();
	if (isset($attributes)){
		if (isset($addattribute) && is_numeric($addattribute)){
			$attr = 'g' . $group . '-' . $addattribute;
			$urlAttributes = array_merge($attributes, array(
				$attr
			));
		}
		else{
			$urlAttributes = $attributes;
		}
	}
	
	if (isset($removeattribute) && is_numeric($removeattribute)){
		$attr = 'g' . $group . '-' . $removeattribute;
		unset($urlAttributes[$attr]);
	}
	
	if (count($urlAttributes)){
		
		natsort($urlAttributes);
		$url .= ',' . implode(',', array_unique($urlAttributes));
	}
	
	$urlStaticAttributes = Array();
	if(isset($resetstatic)){
		$staticattributes = Array();
	}
	if (isset($staticattributes)){
		if (isset($addstaticattribute) && is_numeric($addstaticattribute)){
			$attr = 's' . $staticgroup . '-' . $addstaticattribute;
			$urlStaticAttributes = array_merge($staticattributes, array(
				$attr
			));
		}
		else{
			$urlStaticAttributes = $staticattributes;
		}
	}
	
	if (isset($removestaticattribute) && is_numeric($removestaticattribute)){
		$attr = 's' . $staticgroup . '-' . $removestaticattribute;
		unset($urlStaticAttributes[$attr]);
	}
	
	if (count($urlStaticAttributes)){
		natsort($urlStaticAttributes);
		$url .= ',' . implode(',', array_unique($urlStaticAttributes));
	}
	
	if (isset($price) && $price != ''){
		$url .= ',' . $price;
	}
	
	if (isset($page)){
		$url .= ',' . $page;
	}
	
	$paramsUrl = '';
	if (count($params) > 1){
		$Data = array_merge($_GET, $params);
		unset($Data['seo']);
		unset($Data['page']);
		unset($Data['price']);
		unset($Data['controller']);
		unset($Data['addproducer']);
		unset($Data['removeproducer']);
		unset($Data['producers']);
		unset($Data['attributes']);
		unset($Data['group']);
		unset($Data['addattribute']);
		unset($Data['removeattribute']);
		unset($Data['resetstatic']);
		unset($Data['staticattributes']);
		unset($Data['staticgroup']);
		unset($Data['addstaticattribute']);
		unset($Data['removestaticattribute']);
		if (count($Data) > 0){
			foreach ($Data as $key => $val){
				$paramsUrl .= '&' . $key . '=' . $val;
			}
			$url .= '?' . substr($paramsUrl, 1);
		}
	}
	return $url;
}