<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_PluginBase {

  public $smarty = null;

  function __construct() {
    $this->smarty = Smarty::instance();
  }
  
}

?>
