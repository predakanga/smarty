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
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public abstract function isExisting(Smarty_Internal_Template $template);
    
    /**
    * Get filepath to template source
    * 
    * @param Smarty_Internal_Template $_template template object
    * @return string filepath to template source file
    */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    {
        $_filepath = $_template->buildTemplateFilepath();

        if ($_filepath !== false) {
            if (is_object($_template->smarty->security_policy)) {
                $_template->smarty->security_policy->isTrustedResourceDir($_filepath);
            } 
        } 
        $_template->templateUid = sha1($_filepath);
        return $_filepath;
    }
    
    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_type type of the resource to get modification time of. 
     * @param string $resource_name name of the resource to get modification time of, if null, $_template->resource_name is used
     * @return integer|boolean timestamp (epoch) the template was modified, false if resources has no timestamp
     */
    public abstract function getTemplateTimestamp(Smarty_Internal_Template $_template, $resource_name=null);
    
    /**
     * Load template's source into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public abstract function getTemplateSource(Smarty_Internal_Template $_template);
    
    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string|boolean path to compiled template or false if not applicable
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return $this->buildCompiledFilepath($_template);
    }
    
    /**
     * Build filepath to compiled template
     *
     * @param Smarty_Internal_Template $_template template object
     * @param string $_basename basename of the template to inject into the filepath
     * @return string path to compiled template
     */
    protected function buildCompiledFilepath(Smarty_Internal_Template $_template, $_basename=null)
    {
        $_compile_id = isset($_template->compile_id) ? preg_replace('![^\w\|]+!', '_', $_template->compile_id) : null;
        // calculate Uid if not already done
        if ($_template->templateUid == '') {
            $_template->getTemplateFilepath();
        } 
        $_filepath = $_template->templateUid; 
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
        if ($_basename === null) {
           $_basename = basename( preg_replace('![^\w\/]+!', '_', $_template->resource_name) );
        }
        // separate (optional) basename by dot
        if ($_basename) {
            $_basename = '.' . $_basename;
        }
        return $_compile_dir . $_filepath . '.' . $_template->resource_type . $_basename . $_cache . '.php';
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
        if ($resource_type == 'file' || $resource_type == 'extends' || $resource_type == 'php') {
            // file, extends and php types can be checked without loading the respective resource handlers
            $mtime = filemtime($filepath);
        } else {
            self::parse($_template, $filepath, $resource_type, $resource_name);
            $resource_handler = self::load($_template, $resource_type);
            $mtime = $resource_handler->getTemplateTimestamp($_template, $resource_name);
        }
        return $mtime > $since;
    }
    
    /**
     * Split a template resource into its name and type
     * 
     * @param Smarty_Internal_Template $_template template object
     * @param string $template_resource template resource specification
     * @param string $resource_type resource type
     * @param string $resource_name resource name
     * @return void
     */
    public static function parse(Smarty_Internal_Template $_template, $template_resource, &$resource_type, &$resource_name)
    {
        if (($pos = strpos($template_resource, ':')) === false) {
            // no resource given, use default
            $resource_type = $_template->smarty->default_resource_type;
            $resource_name = $template_resource;
        } else {
            // get type and name from path
            $resource_type = substr( $template_resource, 0, $pos );
            $resource_name = substr( $template_resource, $pos +1 );
            if (strlen($resource_type) == 1) {
                // 1 char is not resource type, but part of filepath
                $resource_type = 'file';
                $resource_name = $template_resource;
            }
        }
    }
    
    /**
     * Load Resource Handler
     *
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_type name of the resource
     * @return Smarty_Resource Resource Handler
     */
    public static function load(Smarty_Internal_Template $_template, $resource_type)
    {
        // try the instance cache
        if (isset(self::$resources[$resource_type])) {
            return self::$resources[$resource_type];
        }
        // try registered resource
        if (isset($_template->smarty->registered_resources[$resource_type])) {
            if ($_template->smarty->registered_resources[$resource_type] instanceof Smarty_Resource) {
                return self::$resources[$resource_type] = $_template->smarty->registered_resources[$resource_type];
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
        if ($_template->smarty->loadPlugin($_resource_class)) {
            if (class_exists($_resource_class, false)) {
                return self::$resources[$resource_type] = new $_resource_class();
            } else {
            	$_template->smarty->registerResource($resource_type,
            		array("smarty_resource_{$resource_type}_source",
                		"smarty_resource_{$resource_type}_timestamp",
                    	"smarty_resource_{$resource_type}_secure",
                    	"smarty_resource_{$resource_type}_trusted"));
                // give it another try, now that the resource is registered properly
                return self::load($_template, $resource_type);
            } 
        }
        // try streams
        $_known_stream = stream_get_wrappers();
        if (in_array($resource_type, $_known_stream)) {
            // is known stream
            if (is_object($_template->smarty->security_policy)) {
                $_template->smarty->security_policy->isTrustedStream($resource_type);
            }
            if (!isset(self::$resources['stream'])) {
                self::$resources['stream'] = new Smarty_Internal_Resource_Stream();
            }
            return self::$resources['stream'];
        }
        // give up
        throw new SmartyException('Unkown resource type \'' . $resource_type . '\'');
    }
}

?>