<?php

/**
* Smarty method Register_Prefilter
* 
* Registers a PHP function as prefilter
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Prefilter
* 
* Register a PHP function as prefilter
*/

class Smarty_Method_Register_Prefilter extends Smarty_Internal_Base {
    /**
    * Registers a prefilter function to apply
    * to a template before compiling
    * 
    * @param callback $function 
    */
    public function execute($function)
    {
        $this->smarty->plugins['prefilter'][$function] = array($function, null, null, false);
    } 
} 
?>
