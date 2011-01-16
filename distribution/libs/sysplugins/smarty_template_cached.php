<?php

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
	//public $timestamp = null; // magic loaded
	
	/**
	 * Source Existance
	 * @var boolean
	 * @property $exists
	 */
	//public $exists = false; // magic loaded
	
	/**
	 * Source Content
	 * @var string
	 * @property $content
	 */
	//public $content = null; // magic loaded
	
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
	 * Read this cache object from handler
	 *
	 * @param Smarty_Internal_Template $_template template object
	 * @param boolean $no_render true to echo content immediately, false to return content as string
     * @return string|booelan the template content, or false if the file does not exist
	 */
	public function read(Smarty_Internal_Template $_template, $no_render = false)
	{
	    return $this->handler->getCachedContents($_template, $no_render);
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
	        $t = $this->handler->writeCachedContent($_template, $content);
    	    unset($this->timestamp);
    	    unset($this->exists);
    	    return $t;
        }
	    return false;
	}


    public function __set($property_name, $value)
    {
        switch ($property_name) {
            case 'timestamp':
            case 'exists':
                $this->$property_name = $value;
                break;

            default:
                throw new SmartyException("invalid cached property '$property_name'.");
        }
    }
    
    public function __get($property_name)
    {
        switch ($property_name) {
            case 'timestamp':
            case 'exists':
                $this->handler->populateTimestamp($this);
                return $this->$property_name;

            default:
                throw new SmartyException("cached property '$property_name' does not exist.");
        }
    }
}


?>