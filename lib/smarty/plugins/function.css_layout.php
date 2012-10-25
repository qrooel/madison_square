<?php
defined('ROOTPATH') or die('No direct access allowed.');

function smarty_function_css_layout ($params, &$smarty)
{
	$viewid = Helper::getViewId();
	
	foreach (App::getRegistry()->loader->getNamespaces() as $namespace){
		if (file_exists(ROOTPATH . 'design' . DS . '_css_frontend' . DS . $namespace . DS . $viewid . '.css')){
			return DESIGNPATH . '_css_frontend' . '/' . $namespace . '/' . $viewid . '.css';
		}
	}
	return DESIGNPATH . '_css_frontend' . '/' . $namespace . '/' . 'gekosale.css';
}
?>
