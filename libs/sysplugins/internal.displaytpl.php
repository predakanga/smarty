<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_DisplayTPL extends Smarty_Internal_PluginBase {
    public function display($tpl, $tpl_vars)
    { 
        // get compiled filename/filepath
        $_compiled_filename = $this->_get_compiled_filename($tpl);
        $_compiled_filepath = $this->smarty->compile_dir . $_compiled_filename;
        $_cached_filepath = $this->smarty->cache_dir . md5($tpl) . $this->smarty->php_ext;
        $_tpl_filepath = $this->smarty->template_dir . $tpl; 
        // compile if needed
        if (!file_exists($_compiled_filepath) || filemtime($_compiled_filepath) !== filemtime($_tpl_filepath) || $this->smarty->force_compile
                ) {
            $this->_compiler = new Smarty_Internal_Compiler; 
            // read template file
            $_content = file_get_contents($_tpl_filepath);

            $this->_compiler->compile($_content, $_tpl_filepath, $_compiled_filepath);

            if ($this->smarty->compile_error) {
                // Display error and die
                $this->smarty->trigger_fatal_error("Template compilation error");
            } 

            // make tpl and compiled file timestamp match
            touch($_compiled_filepath, filemtime($_tpl_filepath));
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
        
        return md5($tpl) .$mode. $this->smarty->php_ext;
    } 
} 

?>
