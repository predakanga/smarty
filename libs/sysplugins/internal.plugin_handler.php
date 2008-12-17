<?php

/**
* Smarty Internal Plugin Handler
* 
* @package Smarty
* @subpackage PluginsInternal
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Handler Class
*/
class Smarty_Internal_Plugin_Handler extends Smarty_Internal_Base {
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
        // load plugin if missing
        if (!isset($this->smarty->registered_plugins[$name])) {
            foreach ($this->smarty->plugin_search_order as $plugin_type) {
                if ($plugin != 'compiler') {
                    $plugin = 'smarty_' . $plugin_type . '_' . $name;
                    if ($this->smarty->loadPlugin($plugin)) {
                        if (class_exists($plugin, false)) {
                            $plugin = array($plugin, 'execute');
                        } 
                        if (is_callable($plugin)) {
                            $this->smarty->registered_plugins[$name] = array($plugin_type, $plugin, false);
                            break;
                        } else {
                            throw new SmartyException("Plugin \"{$name}\" not callable");
                        } 
                    } 
                } 
            } 
        } 

        if (isset($this->smarty->registered_plugins[$name])) {
            // call plugin
            return call_user_func_array($this->smarty->registered_plugins[$name][1], $args);
        } 
        // plugin not found
        throw new SmartyException("Unable to load plugin {$name}");
    } 
} 

?>
