<?php

/**
* Smarty method Unregister_Modifier
* 
* Unregister a Smarty modifier plugin
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Unregister_Modifier
* 
* Unregister a Smarty modifier plugin
*/

class Smarty_Method_Unregister_Modifier extends Smarty_Internal_Base {
    /**
    * Unregisters modifier
    * 
    * @param string $modifier name of template modifier
    */
    public function execute($modifier)
    {
        unset($this->smarty->plugins['modifier'][$modifier]);
    } 
} 

?>
