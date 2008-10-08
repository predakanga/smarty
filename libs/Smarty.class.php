<?php

/**
* Project:     Smarty: the PHP compiling template engine
* File:        Smarty.class.php
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* 
* For questions, help, comments, discussion, etc., please join the
* Smarty mailing list. Send a blank e-mail to
* smarty-discussion-subscribe@googlegroups.com
* 
* @link http://www.smarty.net/
* @copyright 2008 New Digital Group, Inc.
* @author Monte Ohrt <monte at ohrt dot com> 
* @package Smarty
* @version 3.0-alpha1
*/

/* $Id: $ */

/**
* set SMARTY_DIR to absolute path to Smarty library files.
* if not defined, include_path will be used. Sets SMARTY_DIR only if user
* application has not already defined it.
*/

if (!defined('SMARTY_DIR')) {
    define('SMARTY_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
} 

class Smarty {
    // template directory
    public $template_dir = null; 
    // compile directory
    public $compile_dir = null; 
    // plugins directory
    public $plugins_dir = null; 
    // cache directory
    public $cache_dir = null; 
    // config directory
    public $config_dir = null; 
    // force template compiling?
    public $force_compile = false; 
    // plugins directory
    public $sysplugins_dir = null; 
    // use sub dirs for compiled/cached files?
    public $use_sub_dirs = false; 
    // php file extention
    public $php_ext = '.php'; 
    // assigned tpl vars
    public $tpl_vars = array(); 
    // compile_error?
    public $compile_error = false; 
    // caching enabled
    public $caching = false; 
    // caching lifetime
    public $caching_lifetime = 0; 
    // delimiter
    public $left_delimiter = "{";
    public $right_delimiter = "}"; 
    // security mode
    public $security = false;
    // modifier object
    public $modifier = null;
    // function object
    public $function = null;

    /**
    * Class constructor, initializes basic smarty properties
    */
    public function __construct()
    { 
        // set exception handler
        set_exception_handler(array('SmartyException', 'getStaticException')); 
        // set default dirs
        $this->template_dir = '.' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->compile_dir = '.' . DIRECTORY_SEPARATOR . 'templates_c' . DIRECTORY_SEPARATOR;
        $this->plugins_dir = array('.' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->cache_dir = '.' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->config_dir = '.' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
        $this->sysplugins_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sysplugins' . DIRECTORY_SEPARATOR; 
        // set instance object
        self::instance($this);
        // load base plugins
        $this->loadPlugin('Smarty_Internal_Base');
        $this->loadPlugin('Smarty_Internal_PluginBase');
        // setup function and modifier objects
        $this->loadPlugin('Smarty_Internal_Modifier');
        $this->modifier = new Smarty_Internal_Modifier;
        $this->loadPlugin('Smarty_Internal_Function');
        $this->function = new Smarty_Internal_Function;
    } 

    /**
    * Class destructor
    */
    public function __destruct()
    { 
        // restore to previous exception handler, if any
        restore_exception_handler();
    } 

    /**
    * Sets a static instance of the smarty object. Retrieve with:
    * $smarty = Smarty::instance();
    * 
    * @param string $id the object instance id
    * @param obj $new_instance the Smarty object when setting
    * @return obj reference to Smarty object
    */
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    } 

    /**
    * displays a Smarty template
    * 
    * @param string $tpl the name of the template file
    */
    public function display($tpl)
    {
        if (false) {
            // if tpl file ends in .php, just include it
            if (substr($tpl, - strlen($this->php_ext)) == $this->php_ext) {
                // PHP template
                $this->loadPlugin('Smarty_Internal_DisplayPHP');
                $display = new Smarty_Internal_DisplayPHP;
                $display->display($tpl, $this->tpl_vars);
            } elseif (substr($tpl, 0, 7) == "String:") {
                // String template
                $this->loadPlugin('Smarty_Internal_DisplayString');
                $display = new Smarty_Internal_DisplayString;
                $tpl = substr($tpl, 7);
                $display->display($tpl, $this->tpl_vars);
            } else {
                // compiled template
                $this->loadPlugin('Smarty_Internal_DisplayTPL');
                $display = new Smarty_Internal_DisplayTPL;
                $display->display($tpl, $this->tpl_vars);
            } 
        } else {
            // new process of compiling here
            $this->loadPlugin('Smarty_Internal_Resource_TPL');
            $resource = new Smarty_Internal_Resource_TPL; 
            // assemble file pathes
            $_tpl_filepath = $this->getTemplateFilepath($tpl);
            $_compiled_filepath = $this->getCompileFilepath($_tpl_filepath);

            if (($template_timestamp = $resource->get_timestamp($tpl, $_tpl_filepath)) !== false) {
                // check if we need a recompile
                if (!file_exists($_compiled_filepath) || filemtime($_compiled_filepath) !== $template_timestamp || $this->smarty->force_compile) {
                    // compile template
                    $this->loadPlugin('Smarty_Internal_CompileBase');
                    $this->loadPlugin('Smarty_Internal_Compiler');
                    $this->_compiler = new Smarty_Internal_Compiler;
                    $_template = $resource->get_template($tpl, $_tpl_filepath);
                    $_compiled_template = $this->_compiler->compile($_template, $_tpl_filepath, $_compiled_filepath);
                    if ($_compiled_template !== false) {
                        // write compiled template
                        file_put_contents($_compiled_filepath, $_compiled_template); 
                        // make tpl and compiled file timestamp match
                        touch($_compiled_filepath, filemtime($_tpl_filepath));
                    } else {
                        // Display error and die
                        $this->smarty->trigger_fatal_error("Template compilation error");
                    } 
                } 
            } 
            // display template
            extract($this->tpl_vars);
            include($_compiled_filepath);
        } 
    } 

    /**
    * assigns values to template variables
    * 
    * @param array $ |string $tpl_var the template variable name(s)
    * @param mixed $value the value to assign
    */
    public function assign($tpl_var, $value = null)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $key => $val) {
                if ($key != '') {
                    $this->tpl_vars[$key] = $val;
                } 
            } 
        } else {
            if ($tpl_var != '')
                $this->tpl_vars[$tpl_var] = $value;
        } 
    } 

    /*
        * Build template file path
        */
    private function getTemplateFilepath ($tpl)
    {
        foreach((array)$this->template_dir as $_template_dir) {
            $_filepath = $_template_dir . $tpl;
            if (file_exists($_filepath))
                return $_filepath;
        } 
        throw new SmartyException("Unable to load template '{$tpl}'");
        return false;
    } 

    /*
        * Build template file path
        */

    private function getCompileFilepath ($tpl)
    {
        return $this->compile_dir . md5($_tpl_filepath) . $this->php_ext;
    } 

    /**
    * Takes unknown classes and loads plugin files for them
    * class name format: Smarty_PluginType_PluginName
    * plugin filename format: plugintype.pluginname.php
    * 
    * @param string $class_name unknown class name
    */
    public function loadPlugin($class_name)
    { 
        // if class exists, exit silently (already loaded)
        if (class_exists($class_name))
            return true; 
        // Plugin name is expected to be: Smarty_[Type]_[Name]
        $class_name = strtolower($class_name);
        $name_parts = explode('_', $class_name,3); 
        
        // class name must have three parts to be valid plugin
        if (count($name_parts) < 3 || $name_parts[0] !== 'smarty')
        {
            throw new SmartyException("plugin {$class_name} is not a valid name format");
            return false;
        } 
        // plugin filename is expected to be: [type].[name].php
        $plugin_filename = "{$name_parts[1]}.{$name_parts[2]}{$this->php_ext}";
        
        // if type is "internal", get plugin from sysplugins
        if (($name_parts[1] == 'internal') && file_exists($this->sysplugins_dir . $plugin_filename)) {
            return require_once($this->sysplugins_dir . $plugin_filename);
        } 
        // loop through plugin dirs and find the plugin
        foreach((array)$this->plugins_dir as $plugin_dir) {
            if (file_exists($plugin_dir . $plugin_filename))
                return require_once($plugin_dir . $plugin_filename);
        } 
        // no plugin loaded
        return false;
    } 

    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format: Smarty_Method_MethodName
    * plugin filename format: method.methodname.php
    * 
    * @param string $class_name unknown class name
    */
    public function __call($name, $args)
    {
        $plugin_filename = strtolower('method.' . $name . $this->php_ext);
        if (!file_exists($this->sysplugins_dir . $plugin_filename)) {
            throw new SmartyException ("Sysplugin file " . $plugin_filename . " does not exist");
            die();
        } 
        require_once($this->sysplugins_dir . $plugin_filename);
        $class_name = "Smarty_Method_{$name}";
        if (!class_exists($class_name)) {
            throw new SmartyException ("Sysplugin file " . $plugin_filename . "does not define class " . $class_name);
            die();
        } 
        $method = new $class_name;
        return $method->execute($args);
    } 
} 

/**
* Smarty Exception Handler
* 
* All errors thrown in Smarty will be handled here.
* 
* @param string $message the error message
* @param string $code the error code
*/
class SmartyException extends Exception {
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    } 

    public function __toString()
    {
        return "Error: " . htmlentities($this->getMessage()) . "<br>\n"
         . "File: " . $this->getFile() . "<br>\n"
         . "Line: " . $this->getLine() . "\n";
    } 

    public function getException()
    {
        print $this; // returns output from __toString()
    } 

    public static function getStaticException($exception)
    {
        $exception->getException();
    } 
} 

?>
