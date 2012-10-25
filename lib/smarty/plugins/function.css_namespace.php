<?php
defined('ROOTPATH') or die('No direct access allowed.');

function smarty_function_css_namespace ($params, &$smarty)
{
	
	extract($params);
	
	if ((! isset($css_file))){
		$smarty->trigger_error("css_namespace: missing 'css_file' parameter");
		return;
	}
	
	if ((! isset($mode))){
		$smarty->trigger_error("css_namespace: missing 'mode' parameter");
		return;
	}
	switch ($mode) {
		case 'adminside':
			$mode = '_css_panel';
			break;
		case 'frontend':
		default:
			$mode = '_css_frontend';
			break;
	}
	$namespace = App::getRegistry()->loader->getCurrentNamespace();
	if (file_exists(ROOTPATH . DS . 'design' . DS . $mode . DS . $namespace . DS . $css_file)){
		return DESIGNPATH . $mode . '/' . $namespace . '/' . $css_file;
	}
	return DESIGNPATH . $mode . '/core/' . $css_file;
}