<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_DisplayString extends Smarty_Internal_PluginBase {
    public function display($tpl, $tpl_vars)
    { 
        // get compiled filename/filepath
        $_compiled_filename = $this->_get_compiled_filename($tpl);
        $_compiled_filepath = $this->smarty->compile_dir . $_compiled_filename;
        $_cached_filepath = $this->smarty->cache_dir . md5($tpl) . $this->smarty->php_ext;
        $_tpl_filepath = 'String:' . $tpl; 
        // compile if necessary
        if (!file_exists($_compiled_filepath) || $this->smarty->force_compile
                ) {
            $this->_compiler = new Smarty_Internal_Compiler;

            $this->_compiler->compile($tpl, $_tpl_filepath, $_compiled_filepath);
        } 

        if ($this->smarty->compile_error) {
            // Display error and die
            $this->smarty->trigger_fatal_error("Template compilation error");
        } 
        // call cache handler if caching enabled
        if ($this->smarty->caching && $this->smarty->cache_lifetime != 0) {
            $cache = new Smarty_Internal_Caching;
            $cache->display($_compiled_filepath, $_cached_filepath);
        } else {
            // no caching, include compiled template for processing
            include($_compiled_filepath);
        } 
        return;
    } 

    private function _get_compiled_filename($tpl)
    {
        $mode = ".n";
        if ($this->smarty->caching) $mode = ".c";
        if ($this->smarty->security) $mode .= "s";
        return md5($tpl). $mode . $this->smarty->php_ext;
    } 
} 

?>
