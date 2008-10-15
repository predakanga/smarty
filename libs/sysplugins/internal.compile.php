<?php

/**
* Smarty modifier class
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Compile extends Smarty_Internal_Base {
    static $objects = array();

    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format:  Smarty_Compile_TagName or Smarty_Internal_Compile_TagName
    * plugin filename format: compile.tagname.php  or internal.compile_tagname.php
    * 
    * @param string $name modifier name
    * @param string $args modifier args
    */
    public function __call($name, $args)
    {
        $ucname = ucfirst($name);
        $classes = array("Smarty_Internal_Compile_{$name}", "Smarty_Compile_{$name}");

        foreach ($classes as $class_name) {
            // re-use object if already instantiated
            if (!isset(self::$objects[$name])) {
                if ($this->smarty->loadPlugin($class_name)) {
                    // use plugin if found
                    self::$objects[$name] = new $class_name;
                    return call_user_func_array(array(self::$objects[$name], 'compile'), $args);
                } 
            } else {
                return call_user_func_array(array(self::$objects[$name], 'compile'), $args);
            } 
        } 
        return false;
    } 
} 

?>
