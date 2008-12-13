<?php

/**
* Smarty Internal Plugin Block
* 
* Smarty block class
* 
* @package Smarty
* @subpackage PluginsInternal
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Debug Class
*/ 
class Smarty_Internal_Block extends Smarty_Internal_Base {
    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format: Smarty_Block_FuncName
    * plugin filename format: block.funcname.php
    * 
    * @param string $name block function name
    * @param array $args block function attributes
    * @return string output of block function
    */
    public function __call($name, $args)
    {

        if (function_exists($name)) {
            // use PHP function if found
            return call_user_func_array($name, $args);
        } 

        $plugin_name = "Smarty_Block_{$name}";

        if (class_exists($plugin_name, false)) {
            return call_user_func_array(array($plugin_name, 'execute'), $args);
        } 

        if (function_exists($plugin_name)) {
            return call_user_func_array($plugin_name, $args);
        } 
        // try to load plugin
        if ($this->smarty->loadPlugin($plugin_name)) {
            // use class plugin if found
            if (class_exists($plugin_name, false)) {
                return call_user_func_array(array($plugin_name, 'execute'), $args);
            } 
            // check if we got Smarty2 style plugin
            if (function_exists($plugin_name)) {
                // use loaded Smarty2 style plugin
                return call_user_func_array($plugin_name, $args);
            } 
        } else {
            // nothing found, throw exception
            throw new SmartyException("Unable to load function plugin {$name}");
        } 
    } 
} 

?>
