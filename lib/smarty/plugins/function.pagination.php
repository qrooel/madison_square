<?php defined('ROOTPATH') OR die('No direct access allowed.');
function smarty_function_pagination($params, &$smarty)
{
        /* Extract params into local vars
           yields $form, $fields and optionally $legend, $class, $id */
        extract($params);

        if ( (!isset($dataset)) )
        {
            $smarty->trigger_error("pagination: missing 'dataset' parameter");
            return;
        }
        
		if(count($dataset['totalPages']) > 1){
	        $vars = $smarty->get_template_vars();
			$smarty->clear_all_assign();
	        $smarty->assign('dataset',$dataset);
	        $smarty->assign('controller', App::getRegistry()->router->getCurrentController());
	        $smarty->assign('id',App::getRegistry()->router->getParams(0));
	        $smarty->assign($vars);
			$output = $smarty->fetch(ROOTPATH . 'design/_tpl/frontend/core/pagination.tpl');
			$smarty->clear_all_assign();
			$smarty->assign($vars);
	        return $output;
		}
}

?>