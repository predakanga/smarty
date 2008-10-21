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
                    else {
                        $_var_ptr[$_key] = new Smarty_variable($_val, $nocache, $global);
                    } 
                } 
            } 
        } else {
            if ($tpl_var != '') {
                if (in_array($tpl_var, array('this', 'smarty')))
                    throw new SmartyException("Cannot assign value to reserved var '{$tpl_var}'");
                else {
                    $_var_ptr->tpl_vars[$tpl_var] = new Smarty_variable($value, $nocache, $global);
                } 
            } 
        } 
    } 

    /**
    * gets the Value of a Smarty variable
    * 
    * @param string $variable the name of the Smarty variable
    */
    public function getValue($variable)
    {
        $var = $this->getVariable($variable);
//        var_dump($var);
        return $this->getVariable($variable)->value;
    } 

    /**
    * gets the object of a Smarty variable
    * 
    * @param string $variable the name of the Smarty variable
    */
    public function getVariable($variable)
    {
        if (isset($this->tpl_vars[$variable])) {
            // found it, return it
           return $this->tpl_vars[$variable]; 
            // not found, try at parent
        } elseif ($this->parent_tpl_vars !== null) {
            // check there, may be called recursivly
            $var=$this->parent_tpl_vars->getVariable($variable);
            return $this->parent_tpl_vars->getVariable($variable);
        }
        if (Smarty::$error_unassigned) {
        if (class_exists('Smarty_Internal_Compiler',false)) {
            Smarty_Internal_Compiler::trigger_template_error('Undefined Smarty variable "' . $variable.'"');
//            die();
        } else {
            throw new SmartyException('Undefined Smarty variable "' . $variable.'"');
        }
        } 
    } 

    /**
    * creates a template object
    * 
    * @param string $template_resource the resource handle of the template file
    */
    public function createTemplate($template, $parent_tpl_vars = null, $cache_id = null, $compile_id = null)
    {
        if (!is_object($template)) {
            // we got a template resource

            $_templateId = $this->buildTemplateId ($template, $cache_id, $compile_id); 
            // already in template cache?
            if (is_object($this->template_objects[$_templateId])) {
                // return cached template object
                return $this->template_objects[$_templateId];
            } else {
                // create and cache new template object
                return new Smarty_Internal_Template ($template, $parent_tpl_vars, $cache_id, $compile_id);
            } 
        } else {
            // just return a copy of template class
            return $template;
        } 
    }
    
    // build a unique template ID
    public function buildTemplateId ($_resorce, $_cache_id, $_compile_id)
    {
        return md5($_resorce . md5($_cache_id) . md5($_compile_id));
    } 
 
} 
// Class for template data
class Smarty_Data extends Smarty_Internal_TemplateBase {
    // array template of variable objects
    public $tpl_vars = NULL; 
    // back pointer to parent vars
    public $parent_tpl_vars = null;

    public function __construct ($_parent_tpl_vars = null)
    { 
        // array template of variable objects
        $this->tpl_vars = array();
        if ($_parent_tpl_vars === null) {
            // no back pionter
            $this->parent_tpl_vars = null;
        } elseif ($_parent_tpl_vars instanceof Smarty_Data) {
            // when Smarty data object set up back pointer
            $this->parent_tpl_vars = $_parent_tpl_vars;
        } elseif (is_array($_parent_tpl_vars)) {
            // when PHP array no back pionter
            $this->parent_tpl_vars = null; 
            // set up varaible values
            foreach ($_parent_tpl_vars as $_key => $_val) {
                $this->tpl_vars[$_key] = new Smarty_variable($_val);
            } 
        } else {
            throw new SmartyException("Wrong type for template variables");
        } 
    } 
} 
// Class for a variable
class Smarty_Variable {
    // template variable
    public $value;
    public $nocache;
    public $global;
    public function __construct ($value = null, $nocache = false, $global = false)
    {
        $this->value = $value;
        $this->nocache = $nocache;
        $this->global = $global;
        $this->prop = array();
    } 
} 

?>
