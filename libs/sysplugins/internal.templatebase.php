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
    * @return mixed the value of the variable
    */
    public function getValue($variable)
    {
        $var = $this->getVariable($variable);
        return $this->getVariable($variable)->value;
    } 

    /**
    * gets the object of a Smarty variable
    * 
    * @param string $variable the name of the Smarty variable
    * @return object the object of the variable
    */
    public function getVariable($variable)
    {
        if (isset($this->tpl_vars[$variable])) {
            // found it, return it
            return $this->tpl_vars[$variable]; 
            // not found, try at parent
        } elseif ($this->parent_tpl_vars !== null) {
            // check there, may be called recursivly
            $var = $this->parent_tpl_vars->getVariable($variable);
            return $this->parent_tpl_vars->getVariable($variable);
        } 
        if (Smarty::$error_unassigned) {
            if (class_exists('Smarty_Internal_Compiler', false)) {
                Smarty_Internal_Compiler::trigger_template_error('Undefined Smarty variable "' . $variable . '"'); 
                // die();
            } else {
                throw new SmartyException('Undefined Smarty variable "' . $variable . '"');
            } 
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
    public function createTemplate($template, $parent_tpl_vars = null, $cache_id = null, $compile_id = null)
    {
        if (!is_object($template)) {
            // we got a template resource
            $_templateId = $this->buildTemplateId ($template, $cache_id, $compile_id); 
            // already in template cache?
            if (is_object(Smarty::$template_objects[$_templateId])) {
                // return cached template object
                return Smarty::$template_objects[$_templateId];
            } else {
                // create and cache new template object
                return new Smarty_Internal_Template ($template, $parent_tpl_vars, $cache_id, $compile_id);
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
    public $tpl_vars = null; 
    // back pointer to parent vars
    public $parent_tpl_vars = null;

    /**
    * create Smarty data object
    */
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
        $this->prop = array();
    } 

    /**
    * Return output string
    * 
    * @return string variable content
    */
    public function __toString()
    {
        if (isset($this->_tmp)) {
            // result from modifer
            $_tmp = $this->_tmp; 
            // must unset because variable could be reused
            unset($this->_tmp);
            return $_tmp;
        } else {
            // variable value
            return $this->value;
        } 
    } 

    /**
    * Lazy load modifier and execute it
    * 
    * @return object variable object
    */
    public function __call($name, $args = array())
    {
        if (is_object($this->value)) {
            if (method_exists($this->value, $name)) {
                // call objects methode
                $_tmp = call_user_func_array(array($this->value, $name), $args);
                if (is_object($_tmp)) {
                    // is methode chaining, we must return the variable object
                    return $this;
                } else {
                    // save result and return variable object
                    $this->_tmp = $_tmp;
                    return $this;
                } 
            } 
        } 
        $_smarty = Smarty::instance(); 
        // get variable value
        if (isset($this->_tmp)) {
            $args = array_merge(array($this->_tmp), $args);
        } else {
            $args = array_merge(array($this->value), $args);
        } 
        // call modifier and save result
        $this->_tmp = call_user_func_array(array($_smarty->modifier, $name), $args); 
        // return variable object for methode chaining
        return $this;
    } 
} 

?>
