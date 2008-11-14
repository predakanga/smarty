<?php

/**
* Smarty Internal Plugin Run Filter
* 
* Smarty run filter class
* 
* @package Smarty
* @subpackage plugins
* @author Monte Ohrt 
*/

class Smarty_Internal_Run_Filter extends Smarty_Internal_Base {
    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format: Smarty_FilterType_FilterName
    * plugin filename format: filtertype.filtername.php
    * 
    * @param string $name function name
    * @param string $args function args
    */
    public function execute($type, $content)
    {
        $output = $content; 
        // loop over the filter
        foreach ($this->smarty->autoload_filters[$type] as $name) {
            $plugin_name = "Smarty_{$type}filter_{$name}";

            if (class_exists($plugin_name, false)) {
                $output = call_user_func_array(array($plugin_name, 'execute'), array($output, $this->smarty));
            } elseif (function_exists($plugin_name)) {
                $output = call_user_func_array($plugin_name, array($output, $this->smarty));
            } elseif ($this->smarty->loadPlugin($plugin_name)) {
                // use class plugin if found
                if (class_exists($plugin_name, false)) {
                    $output = call_user_func_array(array($plugin_name, 'execute'),array($output, $this->smarty));
                } elseif (function_exists($plugin_name)) {
                    // use loaded Smarty2 style plugin
                    $output = call_user_func_array($plugin_name, array($output, $this->smarty));
                } 
            } else {
                // nothing found, throw exception
                throw new SmartyException("Unable to load filter {$plugin_name}");
            } 
        } 
        return $output;
    } 
} 

?>
