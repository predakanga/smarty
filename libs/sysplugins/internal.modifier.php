<?php

/**
 * Smarty modifier class
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_Modifier extends Smarty_Internal_Base {
                  
  /**
   * Takes unknown class methods and lazy loads plugin files for them
   * class name format: Smarty_Modifier_ModName
   * plugin filename format: modifier.modname.php
   *
   * @param string $name modifier name
   * @param string $args modifier args
   */
  public function __call($name, $args) {
   
    $class_name = "Smarty_Modifier_{$name}";
    
    $this->smarty->loadPlugin($class_name);
    
    // no plugin found, use PHP function if exists
    if(!class_exists($class_name) && function_exists($name))
      return call_user_func_array($name , $args);
    
    $method = new $class_name;
      return call_user_func_array(array($method, 'execute'), $args);     
  }
  
}

?>
