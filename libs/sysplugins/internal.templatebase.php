<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage templates
*/

class Smarty_Internal_TemplateBase {
    /**
    * assigns values to template variables
    * 
    * @param array $ |string $tpl_var the template variable name(s)
    * @param mixed $value the value to assign
    */
    public function assign($tpl_var, $value = null, $nocache = false, $global = false)
    {
        if (is_object($this->tpl_vars)) {
            $_var_ptr = $this->tpl_vars;
        } else {
            $_var_ptr = $this;
        } 
       if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                if ($_key != '') {
                    if (in_array($_key, array('this', 'smarty')))
                        throw new SmartyException("Cannot assign value to reserved var '{$_key}'");
                    else
                        $_var_ptr[$_key]->data = $_val;
                    ($nocache === null)? $_var_ptr[$_key]->nocache = false : $_var_ptr[$_key]->nocache = $nocache;
                    ($global === null)? $_var_ptr[$_key]->global = false : $_var_ptr[$_key]->global = $global;
                } 
            } 
        } else {
            if ($tpl_var != '') {
                if (in_array($tpl_var, array('this', 'smarty')))
                    throw new SmartyException("Cannot assign value to reserved var '{$tpl_var}'");
                else
                    $_var_ptr->tpl_vars[$tpl_var]->data = $value;
                ($nocache === null)? $_var_ptr->tpl_vars[$tpl_var]->nocache = false : $_var_ptr->tpl_vars[$tpl_var]->nocache = $nocache;
                ($global === null)? $_var_ptr->tpl_vars[$tpl_var]->global = false : $_var_ptr->tpl_vars[$tpl_var]->global = $global;
            } 
        } 
    } 

    /**
    * creates a template object
    * 
    * @param string $template_resource the resource handle of the template file
    */
    public function createTemplate($template, $cache_id = null, $compile_id = null, $parent_tpl_vars = null)
    {
        if (!is_object($template)) {
            // we got a template resource
            $_templateId = Smarty_Internal_Template::buildTemplateId ($template, $cache_id, $compile_id); 
            // already in template cache?
            if (is_object($this->template_objects[$_templateId])) {
                // return cached template object
                return $this->template_objects[$_templateId];
            } else {
                // create and cache new template object
                return new Smarty_Internal_Template ($template, $cache_id, $compile_id, $parent_tpl_vars);
            } 
        } else {
            // just return a copy of template class
            return $template;
        } 
    } 
} 
// Class for template data
class Smarty_Data extends Smarty_Internal_TemplateBase {
    // template variables
    var $tpl_vars = array();
} 

?>
