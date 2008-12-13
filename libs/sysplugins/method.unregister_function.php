<?php

/**
* Smarty method Unregister_Function
* 
* Unregister a Smarty function plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Unregister_Function
* 
* Unregister a Smarty function plugin
*/

class Smarty_Method_Unregister_Function extends Smarty_Internal_Base {
    /**
    * Unregisters custom function
    * 
    * @param string $function name of template function
    */
    public function execute($function)
    {
        unset($this->smarty->plugins['function'][$function]);
    } 
} 
?>
