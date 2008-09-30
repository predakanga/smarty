<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_DisplayPHP extends Smarty_Internal_PluginBase {
    public function display($tpl, $tpl_vars)
    {
        $_cached_filename = $this->_get_cached_filename($tpl);
        $_cached_filepath = $this->smarty->cache_dir . $_cached_filename;

             extract($tpl_vars);

        if ($this->smarty->caching && $this->smarty->cache_lifetime != 0) {
            $display = new Smarty_Internal_Caching;
            $display->display($this->smarty->template_dir . $tpl, $_cached_filepath);
        } else {
            include($this->smarty->template_dir . $tpl);
        } 
    } 

    private function _get_cached_filename($tpl)
    {
        return md5($tpl) . $this->smarty->php_ext;
    } 
} 

?>
