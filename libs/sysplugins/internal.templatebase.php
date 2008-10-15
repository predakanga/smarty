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
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $key => $val) {
                if ($key != '') {
                    if (in_array($key, array('this', 'smarty')))
                        throw new SmartyException("Cannot assign value to reserved var '{$key}'");
                    else
                        $this->tpl_vars[$key]->data = $val;
                    ($nocache === null)? $this->tpl_vars[$key]->nocache = false : $this->tpl_vars[$key]->nocache = $nocache;
                    ($global === null)? $this->tpl_vars[$key]->global = false : $this->tpl_vars[$key]->global = $global;
                } 
            } 
        } else {
            if ($tpl_var != '') {
                if (in_array($tpl_var, array('this', 'smarty')))
                    throw new SmartyException("Cannot assign value to reserved var '{$tpl_var}'");
                else
                    $this->tpl_vars[$tpl_var]->data = $value;
                ($nocache === null)? $this->tpl_vars[$tpl_var]->nocache = false : $this->tpl_vars[$tpl_var]->nocache = $nocache;
                ($global === null)? $this->tpl_vars[$tpl_var]->global = false : $this->tpl_vars[$tpl_var]->global = $global;
            } 
        } 
    } 

    /**
    * creates a template object
    * 
    * @param string $template_resource the resource handle of the template file
    */
    public function createTemplate($_template, $_cache_id = null, $_compile_id = null, $_parent = null)
    {
        if (!is_object($_template)) {
            // we got a template resource
            $_templateId = Smarty_Internal_Template::buildTemplateId ($_template, $_cache_id, $_compile_id); 
            // already in template cache?
            if (is_object($this->template_objects[$_templateId])) {
                // return cached template object
                return $this->template_objects[$_templateId];
            } else {
                // create and cache new template object
                return new Smarty_Internal_Template ($_template, $_cache_id, $_compile_id, $_parent);
            } 
        } else {
            // just return a copy of template class
            return $_template;
        } 
    } 


    /**
    * create a data object for template variables
    */
    public function CreateTemplateData ()
    {
        return new Smarty_Internal_TemplateData;
    } 
} 
// Class for template data
class Smarty_Internal_TemplateData extends Smarty_Internal_TemplateBase {
    // template variables
    var $tpl_vars = array(); 
    // parent object
    var $parent_object = null;
} 

?>
