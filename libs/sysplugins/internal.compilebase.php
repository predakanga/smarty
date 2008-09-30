<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_CompileBase {

  public $smarty = null;

  function __construct() {
//    $this->smarty = Smarty::instance();
    $this->compiler = Smarty_Internal_Compiler::instance();
  }
  
}

?>
