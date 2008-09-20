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

  // plugins directory
  public $sysplugins_dir = null;
  
  // use sub dirs for compiled/cached files?
  public $use_sub_dirs = false;
  
  // php file extention
  public $php_ext = '.php';

  // assigned tpl vars
  public $_tpl_vars = array();

  // compiled tpl display object
  private $_display_obj = null;
  
  /**
   * Class constructor, initializes basic smarty properties
   */
  public function __construct() {
    // set default dirs
    $this->template_dir = '.'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
    $this->compile_dir = '.'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
    $this->plugins_dir = array('.'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR);
    $this->cache_dir = '.'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
    $this->config_dir = '.'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR;
    $this->sysplugins_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sysplugins' . DIRECTORY_SEPARATOR;
    // set instance object
    self::instance($this);
  }
  
  /**
   * Sets a static instance of the smarty object. Retrieve with:
   * $smarty = Smarty::instance();
   *
   * @param obj $new_instance the Smarty object when setting
   * @return obj reference to Smarty object
   */
  public static function &instance($new_instance=null)
  {
    static $instance = null;
    if(isset($new_instance) && is_object($new_instance))
      $instance = $new_instance;
    return $instance;
  }
  
  /**
   * displays a Smarty template
   *
   * @param string $tpl the name of the template file
   */
  public function display($tpl) {
    // if tpl file ends in .php, just include it
    if(substr($tpl,-strlen($this->php_ext))==$this->php_ext)
    {
      // PHP template
      $this->_display_obj = new Smarty_Internal_DisplayPHP;
      $this->_display_obj->display($tpl,$this->_tpl_vars);
    }
    else
    {
      // compiled template
      $this->_display_obj = new Smarty_Internal_DisplayTPL;
      $this->_display_obj->display($tpl,$this->_tpl_vars);
    }
  }
  
  /**
   * assigns values to template variables
   *
   * @param array|string $tpl_var the template variable name(s)
   * @param mixed $value the value to assign
   */
  public function assign($tpl_var, $value = null)
  {
    if (is_array($tpl_var)){
      foreach ($tpl_var as $key => $val) {
        if ($key != '') {
          $this->_tpl_vars[$key] = $val;
        }
      }
    } else {
      if ($tpl_var != '')
        $this->_tpl_vars[$tpl_var] = $value;
    }
  }  
  
  
  /**
   * Takes unknown classes and lazy loads plugin files for them
   * class name format: Smarty_PluginType_PluginName
   * plugin filename format: plugintype.pluginname.php
   *
   * @param string $class_name unknown class name
   */
  public function autoload($class_name) {

    $name_parts = explode('_',$class_name);
    
    // class name must have three parts to be valid plugin
    if(count($name_parts) !== 3)
      return false;
    
    // class must start with Smarty_
    if(strtolower($name_parts[0]) !== 'smarty')
      return false;  
      
    $plugin_filename = strtolower($name_parts[1].'.'.$name_parts[2].$this->php_ext);
    
    foreach((array)$this->plugins_dir as $plugins_dir) {
      if(file_exists($plugins_dir . $plugin_filename))
        return require_once($plugins_dir . $plugin_filename);
    }
    
    // check sysplugins
    if(file_exists($this->sysplugins_dir . $plugin_filename))
      return require_once($this->sysplugins_dir . $plugin_filename);
    
    return false;
  }
  
  
}

/**
 * Lazy loads a smarty plugin for an unknown class.
 *
 * If you already have an __autoload() defined, copy the
 * smarty __autoload() function contents to the top of it.
 *
 * @param string $class_name unknown class name
 */

if(!function_exists('__autoload'))
{
  function __autoload($class_name) {  
    if(($smarty = Smarty::instance()) !== null)
      if($smarty->autoload($class_name) !== false)
        return true;
  }
}

?>