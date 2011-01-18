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
class Smarty_Template_Source {
    /**
	 * Name of the Class to compile this resource's contents with
	 * @var string
	 */
    public $compiler_class = null;

	/**
	 * Name of the Class to tokenize this resource's contents with
	 * @var string
	 */
    public $template_lexer_class = null;

    /**
	 * Name of the Class to parse this resource's contents with
	 * @var string
	 */
    public $template_parser_class = null;
    
    /**
	 * Unique Template ID
	 * @var string
	 */
    public $uid = null;
    
    /**
	 * Template Resource (Smarty_Internal_Template::$template_resource)
	 * @var string
	 */
    public $resource = null;
    
    /**
	 * Resource Type
	 * @var string
	 */
    public $type = null;
    
    /**
	 * Resource Name
	 * @var string
	 */
    public $name = null;
    
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
	 * Source is bypassing compiler
	 * @var boolean
	 */
	public $uncompiled = null;
	
	/**
	 * Source must be recompiled on every occasion
	 * @var boolean
	 */
	public $recompiled = null;
	
	/**
	 * Resource Handler
	 * @var Smarty_Resource
	 */
	public $handler = null;
	
	/**
	 * Smarty instance
	 * @var Smarty
	 */
	public $smarty = null;
	
	/**
	 * Source Content
	 * @var string
	 * @property $content
	 */
	//public $content = null; // magic loaded
	
	/**
	 * create Source Object container
	 *
	 * @param Smarty_Resource $handler Resource Handler this source object communicates with
	 * @param Smarty $smarty Smarty instance this source object belongs to
	 * @param string $resource full template_resource
	 * @param string $type type of resource
	 * @param string $name resource name
	 */
	public function __construct(Smarty_Resource $handler, Smarty $smarty, $resource, $type, $name)
	{
	    $this->handler = $handler; // Note: prone to circular references

        $this->compiler_class = $handler->compiler_class;
        $this->template_lexer_class = $handler->template_lexer_class;
        $this->template_parser_class = $handler->template_parser_class;
        $this->uncompiled = $this->handler instanceof Smarty_Resource_Uncompiled;
        $this->recompiled = $this->handler instanceof Smarty_Resource_Recompiled;
        
        $this->smarty = $smarty;
        $this->resource = $resource;
        $this->type = $type;
        $this->name = $name;
	}
	
	/**
	 * get a Compiled Object of this source
	 *
	 * @param Smarty_Internal_Template $_template template objet
	 * @return Smarty_Template_Compiled compiled object
	 */
	public function getCompiled(Smarty_Internal_Template $_template)
	{
	    $compiled = new Smarty_Template_Compiled($this);
        $this->handler->populateCompiledFilepath($compiled, $_template);
        return $compiled;
	}
	
	/**
	 * render the uncompiled source
	 *
	 * @param Smarty_Internal_Template $_template template object
	 * @return void
	 */
    public function renderUncompiled(Smarty_Internal_Template $_template)
    {
        return $this->handler->renderUncompiled($this, $_template);
    }
    

    public function __set($property_name, $value)
    {
        switch ($property_name) {
            // required for extends: only
            case 'template':
            case 'components':
            // regular attributes
            case 'content':
            case 'timestamp':
            case 'exists':
                $this->$property_name = $value;
                break;
                
            default:
                throw new SmartyException("invalid source property '$property_name'.");
        }
    }
    
    public function __get($property_name)
    {
        switch ($property_name) {
            case 'timestamp':
            case 'exists':
                $this->handler->populateTimestamp($this);
                return $this->$property_name;
                
            case 'content':
                return $this->content = $this->handler->getContent($this);

            default:
                throw new SmartyException("source property '$property_name' does not exist.");
        }
    }
}


?>