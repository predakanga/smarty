<?php

/**
 * Cache Handler API
 *
 * @package Smarty
 * @subpackage Cacher
 * @author Rodney Rehm
 */
abstract class Smarty_CacheResource {
	/**
	 * Determine the filepath (or some unique cache id) of the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @return string the cache filepath
	 */
	public abstract function getCachedFilepath(Smarty_Internal_Template $_template);
	
	/**
	 * Determine the timpestamp (epoch) of the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @return integer|booelan the template timestamp (epoch), or false if the file does not exist
	 */
	public abstract function getCachedTimestamp(Smarty_Internal_Template $_template);
	
	
	/**
	 * Get the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param boolean $no_render true to echo content immediately, false to return content as string
	 * @return string|booelan the template content, or false if the file does not exist
	 */
	public abstract function getCachedContents(Smarty_Internal_Template $_template, $no_render = false);
	
	/**
	 * Write the rendered template output to cache
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content content to cache
	 * @return boolean status
	 */
	public abstract function writeCachedContent(Smarty_Internal_Template $_template, $content);
	
	/**
	 * Empty cache
	 * 
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	 */
	public abstract function clearAll(Smarty $smarty, $exp_time=null);
	
	/**
	 * Empty cache for a specific template
	 * 
	 * @param string $resource_name template name
	 * @param string $cache_id cache id
	 * @param string $compile_id compile id
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	*/
	public abstract function clear(Smarty $smarty, $resource_name, $cache_id, $compile_id, $exp_time);
}