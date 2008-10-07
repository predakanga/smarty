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
  
    $class_name = "Smarty_Function_{$name}";
    
    $this->smarty->loadPlugin($class_name);
    
    // no plugin found, use PHP function if exists
    if(!class_exists($class_name) && function_exists($name))
      return call_user_func_array($name , $args);
    
    $method = new $class_name;
    return call_user_func_array(array($method, 'execute'), $args);     
    
  }
  
}

?>
