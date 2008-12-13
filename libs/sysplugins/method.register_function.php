<?php

/**
* Smarty method Register_Function
* 
* Registers a PHP function as Smarty function plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Function
* 
* Register a PHP function as Smarty function plugin
*/

class Smarty_Method_Register_Function extends Smarty_Internal_Base {
    /**
    * Registers custom function to be used in templates
    * 
    * @param string $function the name of the template function
    * @param string $function_impl the name of the PHP function to register
    * @param boolean $cacheable if true (default) this fuction is cachable
    */
    public function execute($function, $function_impl, $cacheable = true)
    {
        $this->smarty->plugins['function'][$function] =
        array($function_impl, $cacheable);
    } 
} 
?>
