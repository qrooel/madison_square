<?php
/**
 * Image
 *
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 * 
 * @link       http://code.google.com/p/nweb-image
 *
 * @category   Image
 * @package    Image_Captcha
 * @subpackage Background
 * @author     Krzysztof Kardasz <krzysztof.kardasz@gmail.com>
 * @copyright  Copyright (c) 2008 Krzysztof Kardasz
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt  GNU Lesser General Public
 * @version    2.1
 */

/**
 * Interface dla generowania tła tokenów captcha
 * 
 * @category   Image
 * @package    Image_Captcha
 * @subpackage Background
 * @author     Krzysztof Kardasz <krzysztof.kardasz@gmail.com>
 * @copyright  Copyright (c) 2008 Krzysztof Kardasz
 */
interface Image_Captcha_Background_Interface
{
    /**
     * Wykonuje operacje na obiekcie Image_Captcha
     *
     * @access public
     * @param  Image_Captcha $image
     * @return void
     */
    public function render (Image_Captcha $image);
}