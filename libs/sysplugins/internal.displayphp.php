<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_DisplayPHP extends Smarty_Internal_DisplayBase {
    public function display($tpl, $tpl_vars)
    {
        extract($tpl_vars);
        if (!file_exists($_tpl_filepath)) {
            $this->smarty->trigger_fatal_error("Template file " . $this->smarty->template_dir . $tpl . " does not exist");
        } 
        include($this->smarty->template_dir . $tpl);
    } 
} 

?>
