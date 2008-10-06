<?php

/**
 * Smarty function class
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_Function extends Smarty_Internal_Base {
 
  /**
   * Takes unknown class methods and lazy loads plugin files for them
   * class name format: Smarty_Function_FuncName
   * plugin filename format: function.funcname.php
   *
   * @param string $name function name
   * @param string $args function args
   */
  public function __call($name, $args) {
  
    // use PHP function if it exists, otherwise load plugin
    if(function_exists($name))
      return $name($args[0]);
  
    $class_name = "Smarty_Function_{$name}";
    
    $this->smarty->loadPlugin($class_name);
    
    $method = new $class_name;
    return $method->execute($args[0]);
    
  }
  
}

?>