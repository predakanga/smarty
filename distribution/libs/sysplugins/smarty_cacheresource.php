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
     * populate Cached Object with meta data from Resource
     *
     * @param Smarty_Template_Cached $cached cached object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public abstract function populate(Smarty_Template_Cached $cached, Smarty_Internal_Template $_template);
    
    /**
     * populate Cached Object with timestamp and exists from Resource
     *
     * @param Smarty_Template_Cached $source cached object
     * @return void
     */
	public abstract function populateTimestamp(Smarty_Template_Cached $cached);

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
	 * @return boolean success
	 */
	public abstract function writeCachedContent(Smarty_Internal_Template $_template, $content);
	
	/**
	 * Empty cache
	 * 
     * @param Smarty $smarty Smarty object
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	 */
	public abstract function clearAll(Smarty $smarty, $exp_time=null);
	
	/**
	 * Empty cache for a specific template
	 * 
     * @param Smarty $smarty Smarty object
	 * @param string $resource_name template name
	 * @param string $cache_id cache id
	 * @param string $compile_id compile id
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	*/
	public abstract function clear(Smarty $smarty, $resource_name, $cache_id, $compile_id, $exp_time);
	
	/**
	 * Decode and remove Smarty cache headers
	 *
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content cached content
	 * @param boolean $no_render true to echo content immediately, false to return content as string
	 * @return string cached content without headers
	 */
	protected function decodeCache(Smarty_Internal_Template $_template, $content, $no_render=false)
	{
		// variables required by the eval()ed header
		$_smarty_tpl = $_template;
		unset($_template);
		if (!$no_render) {
        	ob_start();
        }
        eval("?>" . $content);
		return !$no_render ? ob_get_clean() : null;
	}
	
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

    /**
     * initialize Cached Object for given Template
     *
     * @param Smarty_Internal_Template $_template template object
     * @return Smarty_Template_Cached Cached Object
     */
    public static function cached(Smarty_Internal_Template $_template)
    {
        $handler = self::load($_template->smarty);
        $cached = new Smarty_Template_Cached($handler, $_template);
        if (!$_template->caching) {
            $cached->filepath = false;
            $cached->timestamp = false;
            $cached->exists = false;
            return $cached;
        }
        $handler->populate($cached, $_template);
        return $cached;
    }
}