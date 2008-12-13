<?php

/**
* Smarty method Register_Object
* 
* Registers a PHP object
* 
* @package Smarty
* @subpackage SmartyMethod
* @author Uwe Tews 
*/

/**
* Smarty class Register_Object
* 
* Register a PHP object
*/

class Smarty_Method_Register_Object extends Smarty_Internal_Base {
    /**
    * Registers object to be used in templates
    * 
    * @param string $object name of template object
    * @param object $ &$object_impl the referenced PHP object to register
    * @param null $ |array $allowed list of allowed methods (empty = all)
    * @param boolean $smarty_args smarty argument format, else traditional
    * @param null $ |array $block_functs list of methods that are block format
    */
    public function execute($object, &$object_impl, $allowed = array(), $smarty_args = true, $block_methods = array())
    {
        settype($allowed, 'array');
        settype($smarty_args, 'boolean');
        $this->smarty->reg_objects[$object] =
        array(&$object_impl, $allowed, $smarty_args, $block_methods);
    } 
} 
?>
