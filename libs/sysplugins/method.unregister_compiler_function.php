<?php

/**
* Smarty method Unregister_Compiler_Function
* 
* Unregister a Smarty compiler function plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Unregister_Compiler_Function
* 
* Unregister a Smarty compiler function plugin
*/

class Smarty_Method_Unregister_Compiler_Function extends Smarty_Internal_Base {
    /**
    * Unregisters compiler function
    * 
    * @param string $function name of template function
    */
    public function execute($function)
    {
        unset($this->smarty->plugins['compiler'][$function]);
    } 
} 
?>
