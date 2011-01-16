<?php

/**
 * Smarty Resource Plugin
 * 
 * Base implementation for resource plugins
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource {
    /**
     * cache for Smarty_Resource instances
     * @var array
     */
    protected static $resources = array();
    
	/**
	 * Name of the Class to compile this resource's contents with
	 * @var string
	 */
    public $compiler_class = 'Smarty_Internal_SmartyTemplateCompiler';

	/**
	 * Name of the Class to tokenize this resource's contents with
	 * @var string
	 */
    public $template_lexer_class = 'Smarty_Internal_Templatelexer';

    /**
	 * Name of the Class to parse this resource's contents with
	 * @var string
	 */
    public $template_parser_class = 'Smarty_Internal_Templateparser';

    /**
     * Create new Resource Plugin
     *
     */
    public function __construct()
    {
        
    }

    /**
     * Load template's source into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Template_Source $source source object
     * @return string template source
     * @throws SmartyException if source cannot be loaded
     */
    public abstract function getTemplateSource(Smarty_Template_Source $source);
    
    /**
     * populate Source Object with meta data from Resource
     *
     * @param Smarty_Template_Source $source source object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public abstract function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null);

    /**
     * populate Source Object with timestamp and exists from Resource
     *
     * @param Smarty_Template_Source $source source object
     * @return void
     */
    public function populateTimestamp(Smarty_Template_Source $source)
    {
        
    }
    
    /**
     * populate Compiled Object with compiled filepath
     *
     * @param Smarty_Template_Compiled $compiled compiled object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populateCompiledFilepath(Smarty_Template_Compiled $compiled, Smarty_Internal_Template $_template)
    {
        $_compile_id = isset($_template->compile_id) ? preg_replace('![^\w\|]+!', '_', $_template->compile_id) : null;
        $_filepath = $compiled->source->uid; 
        // if use_sub_dirs, break file into directories
        if ($_template->smarty->use_sub_dirs) {
            $_filepath = substr($_filepath, 0, 2) . DS
             . substr($_filepath, 2, 2) . DS
             . substr($_filepath, 4, 2) . DS
             . $_filepath;
        } 
        $_compile_dir_sep = $_template->smarty->use_sub_dirs ? DS : '^';
        if (isset($_compile_id)) {
            $_filepath = $_compile_id . $_compile_dir_sep . $_filepath;
        } 
        // caching token
        if ($_template->caching) {
            $_cache = '.cache';
        } else {
            $_cache = '';
        }
        $_compile_dir = rtrim($_template->smarty->compile_dir, '/\\') . DS;
        // set basename if not specified
        $_basename = $this->getBasename($compiled->source);
        if ($_basename === null) {
            $_basename = basename( preg_replace('![^\w\/]+!', '_', $compiled->source->name) );
        }
        // separate (optional) basename by dot
        if ($_basename) {
            $_basename = '.' . $_basename;
        }

        $compiled->filepath = $_compile_dir . $_filepath . '.' . $compiled->source->type . $_basename . $_cache . '.php';
    }
    
    /**
     * build template filepath by traversing the template_dir array
     *
     * @param Smarty_Template_Source $source source object
     * @param Smarty_Internal_Template $_template template object
     * @return string fully qualified filepath
     * @throws SmartyException if default template handler is registered but not callable
     */
    protected function buildFilepath(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
    {
        $file = $source->name;
        
        // template_dir index?
        if (preg_match('#^\[(?<key>[^\]]+)\](?<file>.+)$#', $file, $match) && is_array($source->smarty->template_dir)) {
            $_template_dir = null;
            // try string indexes
            if (isset($source->smarty->template_dir[$match['key']])) {
                $_template_dir = $source->smarty->template_dir[$match['key']];
            } else if (is_numeric($match['key'])) {
                // try numeric index
                $match['key'] = (int) $match['key'];
                if (isset($source->smarty->template_dir[$match['key']])) {
                    $_template_dir = $source->smarty->template_dir[$match['key']];
                } else {
                    // try at location index
                    $keys = array_keys($source->smarty->template_dir);
                    $_template_dir = $source->smarty->template_dir[$keys[$match['key']]];
                }
            }

            if ($_template_dir) {
                $_file = substr($file,strpos($file, ']') + 1);
                $_template_dir = rtrim($_template_dir, '/\\') . DS;
                $_filepath = $_template_dir . $_file;
                if (file_exists($_filepath)) {
                    return $_filepath;
                }
            }
        }
        
        // relative file name? 
        if (!preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $file)) {
	        foreach((array)$source->smarty->template_dir as $_template_dir) {
                $_template_dir = rtrim($_template_dir, '/\\') . DS;
            	$_filepath = $_template_dir . $file;
            	if (file_exists($_filepath)) {
                	return $_filepath;
            	}
        		if (!preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $_template_dir)) {
        			// try PHP include_path
        			if (($_filepath = Smarty_Internal_Get_Include_Path::getIncludePath($_filepath)) !== false) {
        				return $_filepath;
        			}
        		}
       		}
       	}

        // try absolute filepath
        if (file_exists($file)) {
            return $file;
        }

        // no tpl file found
        if (!empty($source->smarty->default_template_handler_func)) {
            if (!is_callable($source->smarty->default_template_handler_func)) {
                throw new SmartyException("Default template handler not callable");
            }
            $_return = call_user_func_array($source->smarty->default_template_handler_func,
                array($source->type, $source->name, &$_content, &$_timestamp, $source->smarty));
            if (is_string($_return)) {
                return $_return;
            } elseif ($_return === true) {
                $source->content = $_content;
                $source->timestamp = $_timestamp;
                return $_filepath;
            } 
        } 
        
        // give up
        return false;
    }

    /**
     * Determine basename for compiled filename
     *
     * @param Smarty_Template_Source $source source object
     * @return string resource's basename
     */
    protected function getBasename(Smarty_Template_Source $source)
    {
        return null;
    }
    
    
    /**
     * Test if the resource has been modified since a given timestamp
     *
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_type resource type
     * @param string $filepath path to the resource
     * @param integer $since timestamp (epoch) to compare against
     * @return boolean true if modified, false else
     */
    public static function isModifiedSince(Smarty_Internal_Template $_template, $resource_type, $filepath, $since)
    {
        if ($resource_type == 'file' || $resource_type == 'php') {
            // file and php types can be checked without loading the respective resource handlers
            $mtime = filemtime($filepath);
        } else {
            $source = self::source($_template);
            $mtime = $source->timestamp;
        }
        return $mtime > $since;
    }
    
    /**
     * Load Resource Handler
     *
     * @param Smarty $smarty smarty object
     * @param string $resource_type name of the resource
     * @return Smarty_Resource Resource Handler
     */
    public static function load(Smarty $smarty, $resource_type)
    {
        // try the instance cache
        if (isset(self::$resources[$resource_type])) {
            return self::$resources[$resource_type];
        }
        
        // try registered resource
        if (isset($smarty->registered_resources[$resource_type])) {
            if ($smarty->registered_resources[$resource_type] instanceof Smarty_Resource) {
                return self::$resources[$resource_type] = $smarty->registered_resources[$resource_type];
            }
            if (!isset(self::$resources['registered'])) {
                self::$resources['registered'] = new Smarty_Internal_Resource_Registered();
            }
            return self::$resources['registered'];
        } 
        
        // try sysplugins dir
        if (in_array($resource_type, array('file', 'string', 'extends', 'php', 'stream', 'eval'))) {
            $_resource_class = 'Smarty_Internal_Resource_' . ucfirst($resource_type);
            return self::$resources[$resource_type] = new $_resource_class();
        } 
        
        // try plugins dir
        $_resource_class = 'Smarty_Resource_' . ucfirst($resource_type);
        if ($smarty->loadPlugin($_resource_class)) {
            if (class_exists($_resource_class, false)) {
                return self::$resources[$resource_type] = new $_resource_class();
            } else {
            	$smarty->registerResource($resource_type,
            		array("smarty_resource_{$resource_type}_source",
                		"smarty_resource_{$resource_type}_timestamp",
                    	"smarty_resource_{$resource_type}_secure",
                    	"smarty_resource_{$resource_type}_trusted"));
                // give it another try, now that the resource is registered properly
                return self::load($smarty, $resource_type);
            } 
        }
        
        // try streams
        $_known_stream = stream_get_wrappers();
        if (in_array($resource_type, $_known_stream)) {
            // is known stream
            if (is_object($smarty->security_policy)) {
                $smarty->security_policy->isTrustedStream($resource_type);
            }
            if (!isset(self::$resources['stream'])) {
                self::$resources['stream'] = new Smarty_Internal_Resource_Stream();
            }
            return self::$resources['stream'];
        }
        
        // give up
        throw new SmartyException('Unkown resource type \'' . $resource_type . '\'');
    }
    
    /**
     * initialize Source Object for given resource
     *
     * Either [$_template] or [$smarty, $template_resource] must be specified
     * @param Smarty_Internal_Template $_template template object
     * @param Smarty $smarty smarty object
     * @param string $template_resource resource identifier
     * @return Smarty_Template_Source Source Object
     */
    public static function source(Smarty_Internal_Template $_template=null, Smarty $smarty=null, $template_resource=null)
    {
        if ($_template) {
            $smarty = $_template->smarty;
            $template_resource = $_template->template_resource;
        }
        
        if (($pos = strpos($template_resource, ':')) === false) {
            // no resource given, use default
            $resource_type = $smarty->default_resource_type;
            $resource_name = $template_resource;
        } else {
            // get type and name from path
            $resource_type = substr($template_resource, 0, $pos);
            $resource_name = substr($template_resource, $pos +1);
            if (strlen($resource_type) == 1) {
                // 1 char is not resource type, but part of filepath
                $resource_type = 'file';
                $resource_name = $template_resource;
            }
        }
        
        $resource = Smarty_Resource::load($smarty, $resource_type); 
        $source = new Smarty_Template_Source($resource, $smarty, $template_resource, $resource_type, $resource_name);
        $resource->populate($source, $_template);

        return $source;
    }

}

?>