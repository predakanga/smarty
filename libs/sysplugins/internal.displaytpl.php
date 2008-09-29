<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_DisplayTPL extends Smarty_Internal_PluginBase {
  
  public function display($tpl,$tpl_vars) {

    // get compiled filename/filepath
    $_compiled_filename = $this->_get_compiled_filename($tpl_filename);
    $_compiled_filepath = $this->smarty->compile_dir . $_compiled_filename;
    $_tpl_filepath = $this->smarty->template_dir . $tpl;
    
    // compile if needed
    if(
      $this->smarty->force_compile
      || !file_exists($this->smarty->compile_dir.$_compiled_filename)
      || filemtime($_compiled_filepath) !== filemtime($_tpl_filepath)
      ) {
      $this->_compiler = new Smarty_Internal_Compiler;
      $this->_compiler->compile($_tpl_filepath,$_compiled_filepath);
      // make tpl and compiled file timestamp match
      touch($_compiled_filepath,filemtime($_tpl_filepath));
    }
    extract($tpl_vars);
    include($_compiled_filepath);
  }
  
  private function _get_compiled_filename($tpl) {
    return md5($tpl) . $this->smarty->php_ext;
  }
  
}

?>