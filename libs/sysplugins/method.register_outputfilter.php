<?php

/**
* Smarty method Register_Outputfilter
* 
* Registers a PHP function as outputfilter
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Outputfilter
* 
* Register a PHP function as outputfilter
*/

class Smarty_Method_Register_Outputfilter extends Smarty_Internal_Base {
    /**
    * Registers an output filter function to apply
    * to a template output
    * 
    * @param callback $function 
    */
    public function execute($function)
    {
        $this->smarty->plugins['outputfilter'][$function] = array($function, null, null, false);
    } 
} 
?>
