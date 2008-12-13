<?php

/**
* Smarty method Register_Block
* 
* Registers a PHP function as Smarty block function plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Block
* 
* Register a PHP function as Smarty block function plugin
*/

class Smarty_Method_Register_Block extends Smarty_Internal_Base {
    /**
    * Registers block function to be used in templates
    * 
    * @param string $block name of template block
    * @param string $block_impl PHP function to register
    */
    public function execute($block, $block_impl, $cacheable = true, $cache_attrs = null)
    {
        $this->smarty->plugins['block'][$block] =
        array($block_impl, null, null, false, $cacheable, $cache_attrs);
    } 
} 

?>
