<?php

/**
 * Smarty Resource Data Object
 * 
 * Meta Data Container for Template Files
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
class Smarty_Template_Compiled {
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
	 * Source Object
	 * @var Smarty_Template_Source
	 */
	public $source = null;
    
    /**
     * create Compiled Object container
     *
     * @param Smarty_Template_Source $source source object this compiled object belongs to
     */
	public function __construct(Smarty_Template_Source $source)
	{
	    $this->source = $source;
	}
	
	
    public function __set($property_name, $value)
    {
        switch ($property_name) {
            case 'content':
            case 'timestamp':
            case 'exists':
                $this->$property_name = $value;
                break;
                
            default:
                throw new SmartyException("invalid compiled property '$property_name'.");
        }
    }
    
    public function __get($property_name)
    {
        switch ($property_name) {
            case 'timestamp':
            case 'exists':
                $this->timestamp = @filemtime($this->filepath);
                $this->exists = !!$this->timestamp;
                return $this->$property_name;
                
            case 'content':
                return $this->content = file_get_contents($this->filepath);
                
            default:
                throw new SmartyException("compiled property '$property_name' does not exist.");
        }
    }
}


?>