<?php defined('ROOTPATH') OR die('No direct access allowed.');
function smarty_function_seo_js($params, &$smarty){
	
	extract($params);
	
 	if ( (!isset($controller)) )
        {
            $smarty->trigger_error("seo: missing 'controller' parameter");
            return;
        }
        
	return 'sflink '.base64_encode(App::getURLAdress().App::getRegistry()->core->getControllerNameForSeo($controller));
}