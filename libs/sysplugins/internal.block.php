<?php

/**
 * Smarty modifier class
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_Block extends Smarty_Internal_Base {
                  
  /**
   * Takes unknown class methods and lazy loads plugin files for them
   * class name format: Smarty_Modifier_ModName
   * plugin filename format: modifier.modname.php
   *
   * @param string $name modifier name
   * @param string $args modifier args
   */
  public function __call($name, $args) {
   
      static $objects = array();
     
      $class_name = "Smarty_Block_{$name}";
      
      // re-use object if already instantiated
      if (!isset($objects[$class_name]))
      {
      
          if(class_exists($class_name,false) || $this->smarty->loadPlugin($class_name))
          {
              // use plugin if found
              $objects[$class_name] = new $class_name;
          }
          elseif(function_exists($name))
          {
              // use PHP function if found
              return call_user_func_array($name, $args);
          }
          else
          {
              // nothing found, throw exception
              throw new SmartyException("Unable to load modifier plugin {$name}");
          }
      
      }
    
      return call_user_func_array(array($objects[$class_name], 'execute'), $args);     
  }
  
}

?>
