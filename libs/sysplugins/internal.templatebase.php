<?php

/**
* Smarty Internal Plugin TemplateBase
* 
* This file contains the basic classes and methodes for template and variable creation
* 
* @package Smarty
* @subpackage Templates
* @author Uwe Tews 
*/

/**
* Base class with template and variable methodes
*/
class Smarty_Internal_TemplateBase {
    /**
    * assigns a Smarty variable
    * 
    * @param array $ |string $tpl_var the template variable name(s)
    * @param mixed $value the value to assign
    * @param boolean $nocache if true any output of this variable will be not cached
    * @param boolean $global if true the variable will have global scope
    */
    public function assign($tpl_var, $value = null, $nocache = false, $global = false)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                if ($_key != '') {
                    if (in_array($_key, array('this', 'smarty')))
                        throw new SmartyException("Cannot assign value to reserved var '{$_key}'");
                    else {
                        $this->tpl_vars[$_key] = new Smarty_variable($_val, $nocache, $global);
                    } 
                } 
            } 
        } else {
            if ($tpl_var != '') {
                if (in_array($tpl_var, array('this', 'smarty')))
                    throw new SmartyException("Cannot assign value to reserved var '{$tpl_var}'");
                else {
                    $this->tpl_vars[$tpl_var] = new Smarty_variable($value, $nocache, $global);
                } 
            } 
        } 
    } 

    /**
    * gets the Value of a Smarty variable
    * 
    * @param string $variable the name of the Smarty variable
    * @return mixed the value of the variable
    */
    public function getValue($variable)
    {
        $var = $this->getVariable($variable);
        if (!is_null($var)) {
            return $var->value;
        } else {
            return null;
        } 
    } 

    /**
    * gets the object of a Smarty variable
    * 
    * @param string $variable the name of the Smarty variable
    * @return object the object of the variable
    */
    public function getVariable($variable)
    {
        $_ptr = $this;
        while ($_ptr !== null) {
            if (isset($_ptr->tpl_vars[$variable])) {
                // found it, return it
                return $_ptr->tpl_vars[$variable];
            } 
            // not found, try at parent
            $_ptr = $_ptr->parent;
        } 
        if (Smarty::$error_unassigned) {
            if (class_exists('Smarty_Internal_Compiler', false)) {
                Smarty_Internal_Compiler::trigger_template_error('Undefined Smarty variable "' . $variable . '"'); 
                // die();
            } else {
                throw new SmartyException('Undefined Smarty variable "' . $variable . '"');
            } 
        } else {
            return null;
        } 
    } 

    /**
    * creates a template object
    * 
    * @param string $template the resource handle of the template file
    * @param object $parent_tpl_vars next higher level of Smarty variables
    * @param mixed $cache_id cache id to be used with this template
    * @param mixed $compile_id compile id to be used with this template
    * @returns object template object
    */
    public function createTemplate($template, $parent = null, $cache_id = null, $compile_id = null)
    {
        if (!is_object($template)) {
            // we got a template resource
            $_templateId = $this->buildTemplateId ($template, $cache_id, $compile_id); 
            // already in template cache?
            if (isset(Smarty::$template_objects[$_templateId])) {
                // return cached template object
                return Smarty::$template_objects[$_templateId];
            } else {
                // create and cache new template object
                return new Smarty_Internal_Template ($template, $parent, $cache_id, $compile_id);
            } 
        } else {
            // just return a copy of template class
            return $template;
        } 
    } 

    /**
    * generates a template id
    * 
    * @param string $_resource the resource handle of the template file
    * @param mixed $_cache_id cache id to be used with this template
    * @param mixed $_compile_id compile id to be used with this template
    * @returns string a unique template id
    */
    public function buildTemplateId ($_resource, $_cache_id, $_compile_id)
    {
        return md5($_resource . md5($_cache_id) . md5($_compile_id));
    } 

    /**
    * return current time
    * 
    * @returns double current time
    */
    function _get_time()
    {
        $_mtime = microtime();
        $_mtime = explode(" ", $_mtime);
        return (double)($_mtime[1]) + (double)($_mtime[0]);
    } 
} 

/**
* class for the Smarty data object
* 
* The Smarty data object will hold Smarty variables in the current scope
* 
* @param object $parent_tpl_vars next higher level of Smarty variables
*/
class Smarty_Data extends Smarty_Internal_TemplateBase {
    // array template of variable objects
    public $tpl_vars = array(); 
    // back pointer to parent object
    public $parent = null;

    /**
    * create Smarty data object
    */
    public function __construct ($_parent = null)
    {
        if (is_object($_parent)) {
            // when object set up back pointer
            $this->parent = $_parent;
        } elseif (is_array($_parent)) {
            // set up variable values
            foreach ($_parent as $_key => $_val) {
                $this->tpl_vars[$_key] = new Smarty_variable($_val);
            } 
        } else {
            throw new SmartyException("Wrong type for template variables");
        } 
    } 
} 
/**
* class for the Smarty variable object
* 
* This class defines the Smarty variable object
*/
class Smarty_Variable {
    // template variable
    public $value;
    public $nocache;
    public $global;
    /**
    * create Smarty variable object
    * 
    * @param mixed $value the value to assign
    * @param boolean $nocache if true any output of this variable will be not cached
    * @param boolean $global if true the variable will have global scope
    */
    public function __construct ($value = null, $nocache = false, $global = false)
    {
        $this->value = $value;
        $this->nocache = $nocache;
        $this->global = $global;
    } 
} 

?>
