<?php defined('ROOTPATH') OR die('No direct access allowed.');
function smarty_function_fe_form($params, &$smarty)
{
        /* Extract params into local vars
           yields $form, $fields and optionally $legend, $class, $id */
        extract($params);

        /* $form is required */
        if ( (!isset($form)) )
        {
            $smarty->trigger_error("fe_form: missing 'form' parameter");
            return;
        }
        /* $fields is required */
        if ( (!isset($render_mode)) )
        {
            $smarty->trigger_error("fe_form: missing 'render_mode' parameter");
            return;
        }
				
				$html = $form->Render($render_mode);

        return $html;
}

?>