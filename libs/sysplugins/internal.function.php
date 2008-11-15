<?php

/**
* Smarty Internal Plugin Function
* 
* Smarty function class
* 
* @package Smarty
* @subpackage PluginsInternal
* @author Monte Ohrt 
*/

class Smarty_Internal_Function extends Smarty_Internal_Base {
    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format: Smarty_Function_FuncName
    * plugin filename format: function.funcname.php
    * 
    * @param string $name function name
    * @param array $args function arguments
    * @return unkown function result
    */
    public function __call($name, $args)
    {
        $plugin_name = "Smarty_Function_{$name}";

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
        } 

        if (function_exists($name)) {
            // use PHP function if found
            return call_user_func_array($name, $args);
        } else {
            // nothing found, throw exception
            throw new SmartyException("Unable to load function plugin {$name}");
        } 
    } 
} 

?>
