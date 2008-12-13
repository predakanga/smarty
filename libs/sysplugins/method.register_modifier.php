<?php

/**
* Smarty method Register_Modifier
* 
* Registers a PHP function as Smarty modifier plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Modifier
* 
* Register a PHP function as Smarty modifier plugin
*/

class Smarty_Method_Register_Modifier extends Smarty_Internal_Base {
    /**
    * Registers modifier to be used in templates
    * 
    * @param string $modifier name of template modifier
    * @param string $modifier_impl name of PHP function to register
    */
    public function execute($modifier, $modifier_impl)
    {
        $this->smarty->plugins['modifier'][$modifier] =
        array($modifier_impl, null, null, false);
    } 
} 

?>
