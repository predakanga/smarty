<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_DisplayBase extends Smarty_Internal_Base {
  
  public $modifier = null;
  public $function = null;

  function __construct() {
    parent::__construct();
    // setup function and modifier objects
    $this->modifier = new Smarty_Internal_Modifier;
    $this->function = new Smarty_Internal_Function;
  }
  
}

?>
