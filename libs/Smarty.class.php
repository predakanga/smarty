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


/**
* set SMARTY_DIR to absolute path to Smarty library files.
* if not defined, include_path will be used. Sets SMARTY_DIR only if user
* application has not already defined it.
*/

if (!defined('SMARTY_DIR')) {
    define('SMARTY_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
} 

/** 
* load required base class for creation of the smarty object
*/
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sysplugins' . DIRECTORY_SEPARATOR . 'internal.templatebase.php');

/**
* This is the main Smarty class
*/
class Smarty extends Smarty_Internal_TemplateBase {
    // smarty version
    static $_version = 'Smarty3Alpha'; 
    // class used for templates
    public $template_class = 'Smarty_Internal_Template'; 
    // display error on not assigned variabled
    static $error_unassigned = false; 
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
    // use sub dirs for compiled/cached files?
    public $use_sub_dirs = false; 
    // php file extention
    public $php_ext = '.php'; 
    // compile_error?
    public $compile_error = false; 
    // caching enabled
    public $caching = false; 
    // caching lifetime
    public $caching_lifetime = 0; 
    // cache_id
    public $cache_id = null; 
    // compile_id
    public $compile_id = null; 
    // template delimiters
    public $left_delimiter = "{";
    public $right_delimiter = "}"; 
    // security 
    public $security = false;
    public $trusted_dir = array(); 
    // debug mode
    public $debugging = false;
    public $debugging_ctrl = 'URL';
    public $smarty_debug_id = 'SMARTY_DEBUG'; 
    public $request_use_auto_globals = true;
    // assigned tpl vars
    public $tpl_vars = null; 
    // dummy parent object
    public $parent_object = null; 
    // system plugins directory
    private $sysplugins_dir = null; 
    // modifier object
    public $modifier = null; 
    // function object
    public $function = null; 
    // resource type used if none given
    public $default_resource_type = 'file'; 
    // caching type
    public $default_caching_type = 'file'; 
    // internal cache resource types
    public $cache_resorce_types = array('file'); 
    // config type
    public $default_config_type = 'file'; 
    // class used for compiling templates
    public $compiler_class = 'Smarty_Internal_Compiler'; 
    // class used for cacher
    public $cacher_class = 'Smarty_Internal_Cacher_InlineCode'; 
    // public $cacher_class = 'Smarty_Internal_Cacher_NocacheInclude';
    // exception handler: set null to disable
    public $exception_handler = array('SmartyException', 'getStaticException'); 
    // cached template objects
    static $template_objects = null;
    // autoload filter
    public $autoload_filters = array();
    // check If-Modified-Since headers
    public $cache_modified_check = false;

    /**
    * Class constructor, initializes basic smarty properties
    */
    public function __construct()
    { 
        // set exception handler
        if (!empty($this->exception_handler))
            set_exception_handler($this->exception_handler); 
        // set default dirs
        $this->template_dir = '.' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->compile_dir = '.' . DIRECTORY_SEPARATOR . 'templates_c' . DIRECTORY_SEPARATOR;
        $this->plugins_dir = array('.' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->cache_dir = '.' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->config_dir = '.' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
        $this->sysplugins_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sysplugins' . DIRECTORY_SEPARATOR; 
        // set instance object
        self::instance($this); 
        // root data object
        $this->tpl_vars = new Smarty_Data; 
        // load base plugins
        $this->loadPlugin('Smarty_Internal_Base');
        $this->loadPlugin('Smarty_Internal_PluginBase');
        $this->loadPlugin($this->template_class); 
        // setup function , modifier block objects
        $this->loadPlugin('Smarty_Internal_Modifier');
        $this->modifier = new Smarty_Internal_Modifier;
        $this->loadPlugin('Smarty_Internal_Function');
        $this->function = new Smarty_Internal_Function;
        $this->loadPlugin('Smarty_Internal_Block');
        $this->block = new Smarty_Internal_Block;
        if (!$this->debugging && $this->debugging_ctrl == 'URL') {
            $_query_string = $this->request_use_auto_globals ? $_SERVER['QUERY_STRING'] : $GLOBALS['HTTP_SERVER_VARS']['QUERY_STRING'];
            if (@strstr($_query_string, $this->smarty_debug_id)) {
                if (@strstr($_query_string, $this->smarty_debug_id . '=on')) {
                    // enable debugging for this browser session
                    @setcookie('SMARTY_DEBUG', true);
                    $this->debugging = true;
                } elseif (@strstr($_query_string, $this->smarty_debug_id . '=off')) {
                    // disable debugging for this browser session
                    @setcookie('SMARTY_DEBUG', false);
                    $this->debugging = false;
                } else {
                    // enable debugging for this page
                    $this->debugging = true;
                }
            } else {
                $this->debugging = (bool)($this->request_use_auto_globals ? @$_COOKIE['SMARTY_DEBUG'] : @$GLOBALS['HTTP_COOKIE_VARS']['SMARTY_DEBUG']);
            }
        }
    } 

    /**
    * Class destructor
    */
    public function __destruct()
    { 
        // restore to previous exception handler, if any
        if (!empty($this->exception_handler))
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
    * fetches a rendered Smarty template
    * 
    * @param string $template_resource the resource handle of the template file or template object
    */
    public function fetch($template, $parent_tpl_vars = null, $cache_id = null, $compile_id = null)
    {
        if ($parent_tpl_vars === null) {
            // get default Smarty data object
            $parent_tpl_vars = $this->tpl_vars;
        } 
        // create template object if necessary
        ($template instanceof $this->template_class)? $_template = $template :
        $_template = $this->createTemplate ($template, $parent_tpl_vars , $cache_id, $compile_id); 
        // return redered template
        return $_template->getRenderedTemplate();
    } 

    /**
    * displays a Smarty template
    * 
    * @param string $template_resource the resource handle of the template file  or template object
    */
    public function display($template, $parent_tpl_vars = null, $cache_id = null, $compile_id = null)
    {
        if ($parent_tpl_vars === null) {
            // get default Smarty data object
            $parent_tpl_vars = $this->tpl_vars;
        } 
        // display template
        echo $this->fetch ($template, $parent_tpl_vars , $cache_id, $compile_id); 
        // debug output?
        if ($this->debugging) {
            $this->loadPlugin('Smarty_Internal_Debug');
            Smarty_Internal_Debug::display_debug();
        } 
        return true;
    } 

    /**
    * test if cache i valid
    * 
    * @param string $template_resource the resource handle of the template file or template object
    */
    public function is_cached($template, $cache_id = null, $compile_id = null)
    {
        if (!($template instanceof $this->template_class)) {
            $template = $this->createTemplate ($template, $cache_id, $compile_id);
        } 
        // return cache status of template
        return $template->isCached();
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
        if (class_exists($class_name, false))
            return true; 
        // Plugin name is expected to be: Smarty_[Type]_[Name]
        $class_name = strtolower($class_name);
        $name_parts = explode('_', $class_name, 3); 
        // class name must have three parts to be valid plugin
        if (count($name_parts) < 3 || $name_parts[0] !== 'smarty') {
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
    * Takes unknown class methods and lazy loads sysplugin files for them
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
        if (!class_exists($class_name, false)) {
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
        return "Code: " . $this->getCode . "<br>Error: " . htmlentities($this->getMessage()) . "<br>"
         . "File: " . $this->getFile() . "<br>"
         . "Line: " . $this->getLine() . "<br>" 
        // . "Trace: " . $this->getTraceAsString()
        . "\n";
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
