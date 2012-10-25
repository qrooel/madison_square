<?php defined('ROOTPATH') OR die('No direct access allowed.');
	function smarty_function_boxparams($params, &$smarty) {
		$currentBox = $smarty->get_template_vars('box');
		$currentParams = App::getRegistry()->core->getBoxParams();
		$currentParams[$currentBox['id']] = array_merge(isset($currentParams[$currentBox['id']]) ? $currentParams[$currentBox['id']] : Array(), $params);
		return ',p=' . base64_encode(json_encode($currentParams));
	}