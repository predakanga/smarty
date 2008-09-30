<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_DisplayString extends Smarty_Internal_PluginBase {
  
  public function display($tpl,$tpl_vars) {

    // get compiled filename/filepath
    $_compiled_filename = $this->_get_compiled_filename($tpl);
    $_compiled_filepath = $this->smarty->compile_dir . $_compiled_filename;
    $_cached_filepath = $this->smarty->cache_dir . $_compiled_filename;
    $_tpl_filepath = 'String:' . $tpl;
    
    // compile if necessary 
    if(
      !file_exists($_compiled_filepath)
      || $this->smarty->force_compile
      ) {
      $this->_compiler = new Smarty_Internal_Compiler;

      $this->_compiler->compile($tpl,$_tpl_filepath,$_compiled_filepath);
    }
    
    // call cache handler if caching enabled
    if ($this->smarty->caching && $this->smarty->cache_lifetime != 0) {
         $cache = new Smarty_Internal_Caching;
         $cache->display($_compiled_filepath,$_cached_filepath);
      } else {
         // no caching, include compiled template for processing
         include($_compiled_filepath);
      }
    return;
  }
  
  private function _get_compiled_filename($tpl) {
    return md5($tpl) . $this->smarty->php_ext;
  }
  
}

?>
