<?php

/**
* Smarty Internal Plugin Debug
* 
* Class to collect data for the Smarty Debugging Consol
* 
* @package Smarty
* @subpackage Debug
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Debug Class
*/
class Smarty_Internal_Debug extends Smarty_Internal_TemplateBase {
    /**
    * Opens a window for the Smarty Debugging Consol and display the data
    */
    public function display_debug()
    {
        $this->smarty = Smarty::instance();

        if (empty($this->smarty->debug_tpl)) {
            // set path to debug template from SMARTY_DIR
            $this->smarty->debug_tpl = SMARTY_DIR . 'debug.tpl';
        } 
        // get template names
        $i = 0;
        foreach (Smarty::$template_objects as $_template_obj) {
            // exculde the debugging template from displayed data
            if ($this->smarty->debug_tpl != $_template_obj->resource_name) {
                $_template_data[$i]['name'] = $_template_obj->getTemplateFilepath();
                $_template_data[$i]['compile_time'] = $_template_obj->compile_time;
                $_template_data[$i]['render_time'] = $_template_obj->render_time;
                $_template_data[$i]['cache_time'] = $_template_obj->cache_time;
                $i++;
            } 
        } 
        // prepare information of assigned variables
        $_assigned_vars = $this->smarty->tpl_vars;
        ksort($_assigned_vars);

        $_template = new Smarty_Template ($this->smarty->debug_tpl);
        $_template->caching = false;
        $_template->force_compile = false;
        $_template->security = false;
        $_template->assign('template_data', $_template_data);
        $_template->assign('assigned_vars', $_assigned_vars);
        echo $this->smarty->fetch($_template);
    } 
} 

?>
