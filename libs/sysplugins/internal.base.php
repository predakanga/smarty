<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_Base {

  public $smarty = null;

  function __construct() {
    $this->smarty = Smarty::instance();
  }
  
}

?>
