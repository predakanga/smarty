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
     * cache for Smarty_CacheResource instances
     * @var array
     */
    protected static $resources = array();
    
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
	
	
	/**
     * Load Cache Resource Handler
     *
     * @param Smarty $smarty Smarty object
     * @param string $type name of the cache resource
     * @return Smarty_CacheResource Cache Resource Handler
     */
	public static function load(Smarty $smarty, $type = null)
	{
	    if (!isset($type)) {
            $type = $smarty->caching_type;
        }
        // try the instance cache
        if (isset(self::$resources[$type])) {
            return self::$resources[$type];
        }
        // try registered resource
        if (isset($smarty->registered_cache_resources[$type])) {
            // do not cache these instances as they may vary from instance to instance
            return $smarty->registered_cache_resources[$type];
        }
        // try sysplugins dir
        if (in_array($type, $smarty->cache_resource_types)) {
            $cache_resource_class = 'Smarty_Internal_CacheResource_' . ucfirst($type);
            return self::$resources[$type] = new $cache_resource_class();
        } 
        // try plugins dir
        $cache_resource_class = 'Smarty_CacheResource_' . ucfirst($type);
        if ($smarty->loadPlugin($cache_resource_class)) {
            return self::$resources[$type] = new $cache_resource_class();
        } 
        // give up
        throw new SmartyException("Unable to load cache resource '{$type}'");
	}
}