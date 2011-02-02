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
	 * Read the cached template and process header
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 */
	public abstract function process(Smarty_Internal_Template $_template);
	
	/**
	 * Write the rendered template output to cache
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content content to cache
	 * @return boolean success
	 */
	public abstract function writeCachedContent(Smarty_Internal_Template $_template, $content);

	/**
	 * Return cached content
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content content of cache
	 */
	public function getCachedContent(Smarty_Internal_Template $_template)
	{
		if ($_template->cached->handler->process($_template)) {
			ob_start();
			$_template->properties['unifunc']($_template);
        	return ob_get_clean();
        }
        return null;
	}
	
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
/**
 * Smarty Resource Data Object
 * 
 * Cache Data Container for Template Files
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
class Smarty_Template_Cached {
    /**
	 * Source Filepath
	 * @var string
	 */
    public $filepath = null;
    
    /**
	 * Source Timestamp
	 * @var integer
	 * @property $timestamp
	 */
	public $timestamp = null;
	
	/**
	 * Source Existance
	 * @var boolean
	 * @property $exists
	 */
	public $exists = false; 
	
	/**
	 * Cache Is Valid
	 * @var boolean
	 * @property $exists
	 */
	public $valid = null; 
		
	/**
	 * CacheResource Handler
	 * @var Smarty_CacheResource
	 */
	public $handler = null;
    
    /**
     * Template Compile Id (Smarty_Internal_Template::$compile_id)
     * @var string
     */
	public $compile_id = null;

	/**
     * Template Cache Id (Smarty_Internal_Template::$cache_id)
     * @var string
     */
	public $cache_id = null;
	
    /**
	 * Source Object
	 * @var Smarty_Template_Source
	 */
	public $source = null;

    /**
	 * create Cached Object container
	 *
	 * @param Smarty_CacheResource $handler CacheResource Handler this source object communicates with
	 * @param Smarty_Internal_Template $_template template object
	 */
	public function __construct(Smarty_CacheResource $handler, Smarty_Internal_Template $_template)
	{
	    $this->handler = $handler; // Note: prone to circular references

	    $this->compile_id = $_template->compile_id;
	    $this->cache_id = $_template->cache_id;
	    $this->source = $_template->source;
	}
	
	/**
	 * Write this cache object to handler
	 *
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content content to cache
     * @return boolean success
	 */
	public function write(Smarty_Internal_Template $_template, $content)
	{
        if (!$_template->source->recompiled) {
	        if ($this->handler->writeCachedContent($_template, $content)) {
    	    	$this->timestamp = time();
    	    	$this->exists = true;
    	    return true;
    		}
        }
	    return false;
	}

}
?>