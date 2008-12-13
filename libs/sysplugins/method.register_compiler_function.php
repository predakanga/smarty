<?php

/**
* Smarty method Register_Compiler_Function
* 
* Registers a PHP function as Smarty compiler function plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Compiler_Function
* 
* Register a PHP function as Smarty compiler function plugin
*/

class Smarty_Method_Register_Compiler_Function extends Smarty_Internal_Base {
    /**
    * Registers compiler function
    * 
    * @param string $function name of template function
    * @param string $function_impl name of PHP function to register
    */
    public function execute($function, $function_impl, $cacheable = true)
    {
        $this->smarty->plugins['compiler'][$function] =
        array($function_impl, null, null, false, $cacheable);
    } 
} 

?>
