<?php
/**
 * Project:     Smarty: the PHP compiling template engine
 * File:        SmartyBC.class.php
 * SVN:         $Id: $
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
 * @author Uwe Tews
 * @author Rodney Rehm
 * @package Smarty
 */

require(dirname(__FILE__) . '/Smarty.class.php');

/*
 * Smarty Backward Compatability Wrapper Class
 */

class SmartyBC extends Smarty {
	// Smarty 2 BC
	public $_version = self::SMARTY_VERSION;


	/**
	 * Initialize new SmartyBC object
	 *
	 * @param array $options options to set during initialization, e.g. array( 'forceCompile' => false )
	 */
	public function __construct(array $options=array())
	{
	    parent::__construct($options);
	    // register {php} tag
	    $this->registerPlugin('block','php','smarty_php_tag');
	}


	/**
	 * Get template directories
	 *
	 * @note this wrapper ensures data integrity that's non-BC done by setters
	 * @param mixed index of directory to get, null to get all
	 * @return array|string list of template directories, or directory of $index
	 */
	public function getTemplateDir($index=null)
	{
	    // make sure we're dealing with an array
	    $this->template_dir = (array) $this->template_dir;
	    // make sure directories end with a DS
	    foreach ($this->template_dir as $k => $v) {
            $this->template_dir[$k] = rtrim($v, '/\\') . DS;
	    }

	    if ($index !== null) {
	        return isset($this->template_dir[$index]) ? $this->template_dir[$index] : null;
	    }

		return $this->template_dir;
	}

	/**
	 * Get config directory
	 *
	 * @note this wrapper ensures data integrity that's non-BC done by setters
	 * @param mixed index of directory to get, null to get all
	 * @return array|string list of config directories, or directory of $index
	 */
	public function getConfigDir($index=null)
	{
	    // make sure we're dealing with an array
	    $this->config_dir = (array) $this->config_dir;
	    // make sure directories end with a DS
	    foreach ($this->config_dir as $k => $v) {
            $this->config_dir[$k] = rtrim($v, '/\\') . DS;
	    }

	    if ($index !== null) {
	        return isset($this->config_dir[$index]) ? $this->config_dir[$index] : null;
	    }

		return $this->config_dir;
	}

	/**
	 * Get plugin directories
	 *
	 * @note this wrapper ensures data integrity that's non-BC done by setters
	 * @param mixed index of directory to get, null to get all
	 * @return array list of plugin directories
	 */
	public function getPluginsDir($index=null)
	{
	    // make sure we're dealing with an array
	    $this->plugins_dir = (array) $this->plugins_dir;
	    // make sure directories end with a DS
	    foreach ($this->plugins_dir as $k => $v) {
            $this->plugins_dir[$k] = rtrim($v, '/\\') . DS;
	    }

	    if ($index !== null) {
	        return isset($this->plugins_dir[$index]) ? $this->plugins_dir[$index] : null;
	    }

		return $this->plugins_dir;
	}

	/**
	 * Get compiled directory
	 *
	 * @return string path to compiled templates
	 */
	public function getCompileDir()
	{
		return rtrim($this->smarty->compile_dir, '/\\') . DS;
	}

	/**
	 * Get cache directory
	 *
	 * @return string path of cache directory
	 */
	public function getCacheDir()
	{
		return rtrim($this->smarty->cache_dir, '/\\') . DS;
	}


	/**
	 * wrapper for assign_by_ref
	 *
     * @param string $tpl_var the template variable name
     * @param mixed $ &$value the referenced value to assign
     */
    public function assign_by_ref($tpl_var, &$value)
    {
        $this->assignByRef($tpl_var, $value);
    }

    /**
	 * wrapper for append_by_ref
     *
     * @param string $tpl_var the template variable name
     * @param mixed $ &$value the referenced value to append
     * @param boolean $merge flag if array elements shall be merged
     */
    public function append_by_ref($tpl_var, &$value, $merge = false)
    {
         $this->appendByRef($tpl_var, $value, $merge);
    }

    /**
     * clear the given assigned template variable.
     *
     * @param string $tpl_var the template variable to clear
     */
    function clear_assign($tpl_var)
    {
         $this->clearAssign($tpl_var);
    }

    /**
     * Registers custom function to be used in templates
     *
     * @param string $function the name of the template function
     * @param string $function_impl the name of the PHP function to register
     */
    function register_function($function, $function_impl, $cacheable=true, $cache_attrs=null)
    {
        $this->registerPlugin('function',$function, $function_impl, $cacheable, $cache_attrs);
    }

    /**
     * Unregisters custom function
     *
     * @param string $function name of template function
     */
    function unregister_function($function)
    {
        $this->unregisterPlugin('function',$function);
    }

    /**
     * Registers object to be used in templates
     *
     * @param string $object name of template object
     * @param object $object_impl the referenced PHP object to register
     * @param null|array $allowed list of allowed methods (empty = all)
     * @param boolean $smarty_args smarty argument format, else traditional
     * @param null|array $block_functs list of methods that are block format
     */
    function register_object($object, $object_impl, $allowed = array(), $smarty_args = true, $block_methods = array())
    {
        settype($allowed, 'array');
        settype($smarty_args, 'boolean');
        $this->registerObject($object, $object_impl, $allowed, $smarty_args, $block_methods);
    }

    /**
     * Unregisters object
     *
     * @param string $object name of template object
     */
    function unregister_object($object)
    {
        $this->unregisterObject($object);
    }

    /**
     * Registers block function to be used in templates
     *
     * @param string $block name of template block
     * @param string $block_impl PHP function to register
     */
    function register_block($block, $block_impl, $cacheable=true, $cache_attrs=null)
    {
        $this->registerPlugin('block',$block, $block_impl, $cacheable, $cache_attrs);
    }


    /**
     * Unregisters block function
     *
     * @param string $block name of template function
     */
    function unregister_block($block)
    {
         $this->unregisterPlugin('block',$block);
    }

    /**
     * Registers compiler function
     *
     * @param string $function name of template function
     * @param string $function_impl name of PHP function to register
     */
    function register_compiler_function($function, $function_impl, $cacheable=true)
    {
        $this->registerPlugin('compiler',$function, $function_impl, $cacheable);
    }

    /**
     * Unregisters compiler function
     *
     * @param string $function name of template function
     */
    function unregister_compiler_function($function)
    {
        $this->unregisterPlugin('compiler',$function);
    }

    /**
     * Registers modifier to be used in templates
     *
     * @param string $modifier name of template modifier
     * @param string $modifier_impl name of PHP function to register
     */
    function register_modifier($modifier, $modifier_impl)
    {
        $this->registerPlugin('modifier',$modifier, $modifier_impl);
    }

    /**
     * Unregisters modifier
     *
     * @param string $modifier name of template modifier
     */
    function unregister_modifier($modifier)
    {
        $this->unregisterPlugin('modifier',$modifier);
    }

    /**
     * Registers a resource to fetch a template
     *
     * @param string $type name of resource
     * @param array $functions array of functions to handle resource
     */
    function register_resource($type, $functions)
    {
        $this->registerResource($type, $functions);
    }

    /**
     * Unregisters a resource
     *
     * @param string $type name of resource
     */
    function unregister_resource($type)
    {
        $this->unregisterResource($type);
    }

    /**
     * Registers a prefilter function to apply
     * to a template before compiling
     *
     * @param callback $function
     */
    function register_prefilter($function)
    {
        $this->registerFilter('pre', $function);
    }

    /**
     * Unregisters a prefilter function
     *
     * @param callback $function
     */
    function unregister_prefilter($function)
    {
        $this->unregisterFilter('pre', $function);
    }


    /**
     * Registers a postfilter function to apply
     * to a compiled template after compilation
     *
     * @param callback $function
     */
    function register_postfilter($function)
    {
        $this->registerFilter('post', $function);
    }

    /**
     * Unregisters a postfilter function
     *
     * @param callback $function
     */
    function unregister_postfilter($function)
    {
        $this->unregisterFilter('post', $function);
    }

    /**
     * Registers an output filter function to apply
     * to a template output
     *
     * @param callback $function
     */
    function register_outputfilter($function)
    {
        $this->registerFilter('output', $function);
    }

    /**
     * Unregisters an outputfilter function
     *
     * @param callback $function
     */
    function unregister_outputfilter($function)
    {
        $this->unregisterFilter('output', $function);
    }

    /**
     * load a filter of specified type and name
     *
     * @param string $type filter type
     * @param string $name filter name
     */
    function load_filter($type, $name)
    {
        $this->loadFilter($type, $name);
    }

    /**
     * clear cached content for the given template and cache id
     *
     * @param string $tpl_file name of template file
     * @param string $cache_id name of cache_id
     * @param string $compile_id name of compile_id
     * @param string $exp_time expiration time
     * @return boolean
     */
    function clear_cache($tpl_file = null, $cache_id = null, $compile_id = null, $exp_time = null)
    {
        return $this->clearCache($tpl_file, $cache_id, $compile_id, $exp_time);
    }

    /**
     * clear the entire contents of cache (all templates)
     *
     * @param string $exp_time expire time
     * @return boolean
     */
    function clear_all_cache($exp_time = null)
    {
        return $this->clearCache(null, null, null, $exp_time);
    }

    /**
     * test to see if valid cache exists for this template
     *
     * @param string $tpl_file name of template file
     * @param string $cache_id
     * @param string $compile_id
     * @return boolean
     */
    function is_cached($tpl_file, $cache_id = null, $compile_id = null)
    {
        return $this->isCached($tpl_file, $cache_id, $compile_id );
    }

    /**
     * clear all the assigned template variables.
     *
     */
    function clear_all_assign()
    {
        $this->clearAllAssign();
    }

    /**
     * clears compiled version of specified template resource,
     * or all compiled template files if one is not specified.
     * This function is for advanced use only, not normally needed.
     *
     * @param string $tpl_file
     * @param string $compile_id
     * @param string $exp_time
     * @return boolean results of {@link smarty_core_rm_auto()}
     */
    function clear_compiled_tpl($tpl_file = null, $compile_id = null, $exp_time = null)
    {
        return $this->clearCompiledTemplate($tpl_file, $compile_id, $exp_time);
    }

    /**
     * Checks whether requested template exists.
     *
     * @param string $tpl_file
     * @return boolean
     */
    function template_exists($tpl_file)
    {
        return $this->templateExists($tpl_file);
    }

    /**
     * Returns an array containing template variables
     *
     * @param string $name
     * @return array
     */
    function get_template_vars($name=null)
    {
        return $this->getTemplateVars($name);
    }

    /**
     * Returns an array containing config variables
     *
     * @param string $name
     * @return array
     */
    function get_config_vars($name=null)
    {
        return $this->getConfigVars($name);
    }


    /**
     * load configuration values
     *
     * @param string $file
     * @param string $section
     * @param string $scope
     */
    function config_load($file, $section = null, $scope = 'global')
    {
        $this->ConfigLoad($file, $section, $scope);
    }

    /**
     * return a reference to a registered object
     *
     * @param string $name
     * @return object
     */
    function get_registered_object($name)
    {
        return $this->getRegisteredObject($name);
    }


    /**
     * clear configuration values
     *
     * @param string $var
     */
    function clear_config($var = null)
    {
    	$this->clearConfig($var);
    }

    /**
     * trigger Smarty error
     *
     * @param string $error_msg
     * @param integer $error_type
     */
    function trigger_error($error_msg, $error_type = E_USER_WARNING)
    {
        trigger_error("Smarty error: $error_msg", $error_type);
    }

    /**
     * magic getter to allow transparent access through getOption
     *
     * @param string $name name of option to get
     * @return mixed option's value
     */
    public function __get($name)
    {
        return $this->getOption($name);
    }

    /**
     * magic setter to allow transparent access through setOption
     *
     * @param string $name name of option to set
     * @param string $value new value to set
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setOption($name, $value);
    }
}

/**
 * Smarty {php}{/php} block function
 *
 * @param string $content contents of the block
 * @param object $template template object
 * @param boolean $ &$repeat repeat flag
 * @return string content re-formatted
 */
function smarty_php_tag($params, $content, $template, &$repeat)
{
    eval($content);
    return '';
}
?>