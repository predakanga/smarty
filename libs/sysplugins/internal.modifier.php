<?php

/**
* Smarty Internal Plugin Modifier
* 
* Smarty modifier class
* 
* @package Smarty
* @subpackage plugins
* @author Monte Ohrt 
*/

/**
* This class lazy loads plugin files for modifer and executes them.
* 
* class name format: Smarty_Modifier_ModName
* plugin filename format: modifier.modname.php
*/
class Smarty_Internal_Modifier extends Smarty_Internal_Base {
    /**
    * Takes unknown class method and lazy loads plugin files for them
    * class name format: Smarty_Modifier_ModName
    * plugin filename format: modifier.modname.php
    * 
    * @param string $name modifier name
    * @param string $args modifier args
    */
    public function __call($name, $args)
    {
        $plugin_name = "Smarty_Modifier_{$name}";

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
            throw new SmartyException("Unable to load modifier plugin {$name}");
        } 
    } 
} 

?>
