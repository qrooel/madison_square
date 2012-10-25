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
 * @package    Image_Validator
 * @author     Krzysztof Kardasz <krzysztof.kardasz@gmail.com>
 * @copyright  Copyright (c) 2008 Krzysztof Kardasz
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt  GNU Lesser General Public
 * @version    2.1
 */

/**
 * Walidator do określenia minimalnej rozdzielczości zdjęcia
 * 
 * @category   Image
 * @package    Image_Validator
 * @author     Krzysztof Kardasz <krzysztof.kardasz@gmail.com>
 * @copyright  Copyright (c) 2008 Krzysztof Kardasz
 */
class Image_Validator_MinResolution extends Image_Validator_Abstract
{
    /**
     * Szerokość
     *
     * @access protected
     * @var integer|null
     */
    protected $x = null;
    
    /**
     * Wysokość
     *
     * @access protected
     * @var integer|null
     */
    protected $y = null;
    
    /**
     * Zwraca prawdę lub fałsz
     * @access public
     * @param  integer $x szerokość
     * @param  integer $y wysokość
     * @return void
     */
    public function __construct ($x = null, $y = null) 
    {
        $this->x    = $x;
        $this->y    = $y;
    }
    
    /**
     * Zwraca prawdę lub fałsz
     *
     * @access public
     * @return boolean
     */
    public function isValid () 
    {
        if((!empty($this->x) && $this->image->imageWidth() < $this->x) && (!empty($this->y) && $this->image->imageHeight() < $this->y)) {
            $this->message = 'rozdzielczość pliku "%s" jest mniejsza niż ' . $this->x . 'x' . $this->y . ' px';
            return false;
        } elseif((!empty($this->x) && $this->image->imageWidth() < $this->x)) {
            $this->message = 'szerokość pliku "%s" jest mniejsza niż ' . $this->x . ' px';
            return false;
        } elseif((!empty($this->y) && $this->image->imageHeight() < $this->y)) {
            $this->message = 'wysokość pliku "%s" jest mniejsza niż ' . $this->y . ' px';
            return false;
        }
        
        return true;
    }
}