<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_DisplayPHP extends Smarty_Internal_DisplayBase {
  
  public function display($tpl,$tpl_vars) {
    extract($tpl_vars);
    include($this->smarty->template_dir . $tpl); 
  }
  
}

?>