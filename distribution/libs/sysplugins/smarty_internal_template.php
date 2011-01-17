<?php

/**
 * Smarty Internal Plugin Template
 * 
 * This file contains the Smarty template engine
 * 
 * @package Smarty
 * @subpackage Templates
 * @author Uwe Tews 
 */

/**
 * Main class with template data structures and methods
 */
class Smarty_Internal_Template extends Smarty_Internal_Data {
    // object cache
    public $compiler_object = null;
    public $cacher_object = null; 
    // Smarty parameter
    public $cache_id = null;
    public $compile_id = null;
    public $caching = null;
    public $cache_lifetime = null;
    public $cacher_class = null;
    public $caching_type = null;
    public $forceNocache = false; 
    // Template resource
    public $template_resource = null;
    //public $source = null; // magic loaded
    //public $compiled = null; // magic loaded
    //public $cached = null; // magic loaded

    // Compiled template
    public $compiled_template = null;
    public $mustCompile = null;
    
    public $suppressHeader = false;
    public $suppressFileDependency = false;
    public $has_nocache_code = false; 
    public $write_compiled_code = true; 
    // Rendered content
    public $rendered_content = null; 
    // Cache file
    private $isCached = null;
    private $cacheFileChecked = false; 
    // template variables
//    public $tpl_vars = array();
//    public $parent = null;
//    public $config_vars = array(); 
    // storage for plugin
    public $plugin_data = array(); 
    // special properties
    public $properties = array ('file_dependency' => array(),
        'nocache_hash' => '',
        'function' => array()); 
    // required plugins
    public $required_plugins = array('compiled' => array(), 'nocache' => array());
    public $saved_modifier = null;
    public $smarty = null;
    // blocks for template inheritance
    public $block_data = array();
    public $wrapper = null;
    /**
     * Create template data object
     * 
     * Some of the global Smarty settings copied to template scope
     * It load the required template resources and cacher plugins
     * 
     * @param string $template_resource template resource string
     * @param object $_parent back pointer to parent object with variables or null
     * @param mixed $_cache_id cache id or null
     * @param mixed $_compile_id compile id or null
     */
    public function __construct($template_resource, $smarty, $_parent = null, $_cache_id = null, $_compile_id = null, $_caching = null, $_cache_lifetime = null)
    {
        $this->smarty = &$smarty; 
        // Smarty parameter
        $this->cache_id = $_cache_id === null ? $this->smarty->cache_id : $_cache_id;
        $this->compile_id = $_compile_id === null ? $this->smarty->compile_id : $_compile_id;
        $this->caching = $_caching === null ? $this->smarty->caching : $_caching;
        if ($this->caching === true) $this->caching =  Smarty::CACHING_LIFETIME_CURRENT;
        $this->cache_lifetime = $_cache_lifetime === null ?$this->smarty->cache_lifetime : $_cache_lifetime;
        $this->parent = $_parent; 
        // dummy local smarty variable
        $this->tpl_vars['smarty'] = new Smarty_Variable; 
        // Template resource
        $this->template_resource = $template_resource; 
        // copy block data of template inheritance
        if ($this->parent instanceof Smarty_Internal_Template) {
        	$this->block_data = $this->parent->block_data;
        }
    } 

    /**
     * Returns if the current template must be compiled by the Smarty compiler
     * 
     * It does compare the timestamps of template source and the compiled templates and checks the force compile configuration
     * 
     * @return boolean true if the template must be compiled
     */
    public function mustCompile ()
    {
        if (!$this->source->exists) {
            throw new SmartyException("Unable to load template {$this->source->type} '{$this->source->name}'");
        }
        if ($this->mustCompile === null) {
            $this->mustCompile = (!$this->source->uncompiled && ($this->smarty->force_compile || $this->source->recompiled || $this->compiled->timestamp === false || 
                    ($this->smarty->compile_check && $this->compiled->timestamp < $this->source->timestamp)));
        } 
        return $this->mustCompile;
    } 

    /**
     * Returns the compiled template 
     * 
     * It checks if the template must be compiled or just read from the template resource
     * 
     * @return string the compiled template
     */
    public function getCompiledTemplate ()
    {
        if ($this->compiled_template === null) {
            // see if template needs compiling.
            if ($this->mustCompile()) {
                $this->compileTemplateSource();
            } else {
                if ($this->compiled_template === null) {
                    $this->compiled_template = !$this->source->recompiled && !$this->source->uncompiled ? $this->compiled->content : false;
                } 
            } 
        } 
        return $this->compiled_template;
    } 

    /**
     * Compiles the template
     * 
     * If the template is not evaluated the compiled template is saved on disk
     */
    public function compileTemplateSource ()
    {
        if (!$this->source->recompiled) {
            $this->properties['file_dependency'] = array();
            $this->properties['file_dependency'][$this->source->uid] = array($this->source->filepath, $this->source->timestamp,$this->source->type);
        } 
        if ($this->smarty->debugging) {
            Smarty_Internal_Debug::start_compile($this);
        } 
        // compile template
        if (!is_object($this->compiler_object)) {
            // load compiler
            $this->smarty->loadPlugin($this->source->compiler_class);
            $this->compiler_object = new $this->source->compiler_class($this->source->template_lexer_class, $this->source->template_parser_class, $this->smarty);
        } 
        // compile locking
        if ($this->smarty->compile_locking && !$this->source->recompiled) {
            if ($saved_timestamp = $this->compiled->timestamp) {
                touch($this->compiled->filepath);
            } 
        } 
        // call compiler
        try {
            $this->compiler_object->compileTemplate($this);
        } 
        catch (Exception $e) {
            // restore old timestamp in case of error
            if ($this->smarty->compile_locking && !$this->source->recompiled && $saved_timestamp) {
                touch($this->compiled->filepath, $saved_timestamp);
            } 
            throw $e;
        } 
        // compiling succeded
        if (!$this->source->recompiled && $this->write_compiled_code) {
            // write compiled template
            $_filepath = $this->compiled->filepath;
            if($_filepath === false)
                throw new SmartyException( 'getCompiledFilepath() did not return a destination to save the compiled template to' );
            Smarty_Internal_Write_File::writeFile($_filepath, $this->compiled_template, $this->smarty);
        } 
        if ($this->smarty->debugging) {
            Smarty_Internal_Debug::end_compile($this);
        }
        // release objects to free memory
		Smarty_Internal_TemplateCompilerBase::$_tag_objects = array();	
        unset($this->compiler_object->parser->root_buffer,
        	$this->compiler_object->parser->current_buffer,
        	$this->compiler_object->parser,
        	$this->compiler_object->lex,
        	$this->compiler_object->template,
        	$this->compiler_object
        	); 
    } 

    /**
     * Returns the cached template output
     * 
     * @return string |booelan the template content or false if the file does not exist
     */
    public function getCachedContent ()
    {
        return $this->rendered_content === null ?
        $this->rendered_content = ($this->source->recompiled || !($this->caching == Smarty::CACHING_LIFETIME_CURRENT || $this->caching == Smarty::CACHING_LIFETIME_SAVED)) ? false : $this->cached->read($this) :
        $this->rendered_content;
    } 

    /**
     * Writes the cached template output
     */
    public function writeCachedContent ($content)
    {
        if ($this->source->recompiled || !($this->caching == Smarty::CACHING_LIFETIME_CURRENT || $this->caching == Smarty::CACHING_LIFETIME_SAVED)) {
            // don't write cache file
            return false;
        } 
        $this->properties['cache_lifetime'] = $this->cache_lifetime;
        return $this->cached->write($this, $this->createPropertyHeader(true) .$content);
    } 

    /**
     * Checks of a valid version redered HTML output is in the cache
     * 
     * If the cache is valid the contents is stored in the template object
     * 
     * @return boolean true if cache is valid
     */
    public function isCached ($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
    	if ($template === null) {    		
 			$no_render = true;
 		} elseif ($template === false) {
			$no_render = false;
  		} else {
  			if ($parent === null) {
  				$parent = $this;
  			}
			$this->smarty->isCached ($template, $cache_id, $compile_id, $parent);
  		}
        if ($this->isCached === null) {
            $this->isCached = false;
            if (($this->caching == Smarty::CACHING_LIFETIME_CURRENT || $this->caching == Smarty::CACHING_LIFETIME_SAVED) && !$this->source->recompiled) {
                $cachedTimestamp = $this->cached->timestamp;
                if ($cachedTimestamp === false || $this->smarty->force_compile || $this->smarty->force_cache) {
                    return $this->isCached;
                } 
                if ($this->caching === Smarty::CACHING_LIFETIME_SAVED || ($this->caching == Smarty::CACHING_LIFETIME_CURRENT && (time() <= ($cachedTimestamp + $this->cache_lifetime) || $this->cache_lifetime < 0))) {
                    if ($this->smarty->debugging) {
                        Smarty_Internal_Debug::start_cache($this);
                    } 
                    $this->rendered_content = $this->cached->read($this, $no_render);
                    if ($this->smarty->debugging) {
                        Smarty_Internal_Debug::end_cache($this);
                    } 
                    if ($this->cacheFileChecked) {
                        $this->isCached = true;
                        return $this->isCached;
                    } 
                    $this->cacheFileChecked = true;
                    if ($this->caching === Smarty::CACHING_LIFETIME_SAVED && $this->properties['cache_lifetime'] >= 0 && (time() > ($this->cached->timestamp + $this->properties['cache_lifetime']))) {
                        $this->tpl_vars = array();
                        $this->rendered_content = null;
                        return $this->isCached;
                    } 
                    if (!empty($this->properties['file_dependency']) && $this->smarty->compile_check) {
                        $resource_type = null;
                        $resource_name = null;
                        foreach ($this->properties['file_dependency'] as $_file_to_check) {
                            if (Smarty_Resource::isModifiedSince($this, $_file_to_check[2], $_file_to_check[0], $_file_to_check[1])) {
                                $this->tpl_vars = array();
                                $this->rendered_content = null;
                                return $this->isCached;
                            } 
                        } 
                    } 
                    $this->isCached = true;
                } 
            } 
        } 
        return $this->isCached;
    } 

    /**
     * Render the output using the compiled template or the PHP template source
     * 
     * The rendering process is accomplished by just including the PHP files.
     * The only exceptions are evaluated templates (string template). Their code has 
     * to be evaluated
     */
    public function renderTemplate ()
    {
        if (!$this->source->uncompiled) {
            if ($this->mustCompile() && $this->compiled_template === null) {
                $this->compileTemplateSource();
            } 
            if ($this->smarty->debugging) {
                Smarty_Internal_Debug::start_render($this);
            } 
            $_smarty_tpl = $this;
            ob_start();
            if ($this->source->recompiled) {
                eval("?>" . $this->compiled_template);
            } else {
                include($this->compiled->filepath); 
                // check file dependencies at compiled code
                if ($this->smarty->compile_check) {
                    if (!empty($this->properties['file_dependency'])) {
                        $this->mustCompile = false;
                        $resource_type = null;
                        $resource_name = null;
                        foreach ($this->properties['file_dependency'] as $_file_to_check) {
                            if (Smarty_Resource::isModifiedSince($this, $_file_to_check[2], $_file_to_check[0], $_file_to_check[1])) {
                                $this->mustCompile = true;
                                break;
                            }
                        } 
                        if ($this->mustCompile) {
                            // recompile and render again
                            ob_get_clean();
                            $this->compileTemplateSource();
                            ob_start();
                            include($this->compiled->filepath);
                        } 
                    } 
                } 
            } 
        } else {
            if ($this->source->uncompiled) {
                if ($this->smarty->debugging) {
                    Smarty_Internal_Debug::start_render($this);
                } 
                ob_start();
                $this->source->renderUncompiled($this);
            } else {
                throw new SmartyException("Resource '$this->source->type' must have 'renderUncompiled' methode");
            } 
        } 
        $this->rendered_content = ob_get_clean();
        if (!$this->source->recompiled && empty($this->properties['file_dependency'][$this->source->uid])) {
            $this->properties['file_dependency'][$this->source->uid] = array($this->source->filepath, $this->source->timestamp,$this->source->type);
        } 
        if ($this->parent instanceof Smarty_Internal_Template) {
            $this->parent->properties['file_dependency'] = array_merge($this->parent->properties['file_dependency'], $this->properties['file_dependency']);
            foreach($this->required_plugins as $code => $tmp1) {
                foreach($tmp1 as $name => $tmp) {
                    foreach($tmp as $type => $data) {
                        $this->parent->required_plugins[$code][$name][$type] = $data;
                    } 
                } 
            } 
        } 
        if ($this->smarty->debugging) {
            Smarty_Internal_Debug::end_render($this);
        } 
        // write to cache when nessecary
        if (!$this->source->recompiled && ($this->caching == Smarty::CACHING_LIFETIME_SAVED || $this->caching == Smarty::CACHING_LIFETIME_CURRENT)) {
            if ($this->smarty->debugging) {
                Smarty_Internal_Debug::start_cache($this);
            } 
            $this->properties['has_nocache_code'] = false; 
            // get text between non-cached items
            $cache_split = preg_split("!/\*%%SmartyNocache:{$this->properties['nocache_hash']}%%\*\/(.+?)/\*/%%SmartyNocache:{$this->properties['nocache_hash']}%%\*/!s", $this->rendered_content); 
            // get non-cached items
            preg_match_all("!/\*%%SmartyNocache:{$this->properties['nocache_hash']}%%\*\/(.+?)/\*/%%SmartyNocache:{$this->properties['nocache_hash']}%%\*/!s", $this->rendered_content, $cache_parts);
            $output = ''; 
            // loop over items, stitch back together
            foreach($cache_split as $curr_idx => $curr_split) {
                // escape PHP tags in template content
                $output .= preg_replace('/(<%|%>|<\?php|<\?|\?>)/', '<?php echo \'$1\'; ?>', $curr_split);
                if (isset($cache_parts[0][$curr_idx])) {
                    $this->properties['has_nocache_code'] = true; 
                    // remove nocache tags from cache output
                    $output .= preg_replace("!/\*/?%%SmartyNocache:{$this->properties['nocache_hash']}%%\*/!", '', $cache_parts[0][$curr_idx]);
                } 
            } 
            if (isset($this->smarty->autoload_filters['output']) || isset($this->smarty->registered_filters['output'])) {
            	$output = Smarty_Internal_Filter_Handler::runFilter('output', $output, $this);
        	}
            // rendering (must be done before writing cache file because of {function} nocache handling)
            $_smarty_tpl = $this;
            ob_start();
            eval("?>" . $output);
            $this->rendered_content = ob_get_clean(); 
            // write cache file content
            $this->writeCachedContent('<?php if (!$no_render) {?>'. $output. '<?php } ?>');
            if ($this->smarty->debugging) {
                Smarty_Internal_Debug::end_cache($this);
            } 
        } else {
            // var_dump('renderTemplate', $this->has_nocache_code, $this->template_resource, $this->properties['nocache_hash'], $this->parent->properties['nocache_hash'], $this->rendered_content);
            if ($this->has_nocache_code && !empty($this->properties['nocache_hash']) && !empty($this->parent->properties['nocache_hash'])) {
                // replace nocache_hash
                $this->rendered_content = preg_replace("/{$this->properties['nocache_hash']}/", $this->parent->properties['nocache_hash'], $this->rendered_content);
                $this->parent->has_nocache_code = $this->has_nocache_code;
            } 
        } 
    } 

    /**
     * Returns the rendered HTML output 
     * 
     * If the cache is valid the cached content is used, otherwise
     * the output is rendered from the compiled template or PHP template source
     * 
     * @return string rendered HTML output
     */
    public function getRenderedTemplate ()
    { 
        // disable caching for evaluated code
        if ($this->source->recompiled) {
            $this->caching = false;
        } 
        // checks if template exists
        if (!$this->source->exists) {
            throw new SmartyException("Unable to load template {$this->source->type} '{$this->source->name}'");
        }
        // read from cache or render
        if ($this->rendered_content === null) {
        	if ($this->isCached) {
        		if ($this->smarty->debugging) {
            	Smarty_Internal_Debug::start_cache($this);
            } 
            $this->rendered_content = $this->cached->read($this, false);
            if ($this->smarty->debugging) {
            	Smarty_Internal_Debug::end_cache($this);
            }
          } 
          if ($this->isCached === null) { 
            $this->isCached(false); 
          }
          if (!$this->isCached) {          
            // render template (not loaded and not in cache)
            $this->renderTemplate();
          }
        } 
        $this->updateParentVariables();
        $this->isCached = null;
        return $this->rendered_content;
    } 

    /**
     * Update Smarty variables in other scopes
     */
    public function updateParentVariables ($scope = Smarty::SCOPE_LOCAL)
    {
        $has_root = false;
        foreach ($this->tpl_vars as $_key => $_variable) {
            $_variable_scope = $this->tpl_vars[$_key]->scope;
            if ($scope == Smarty::SCOPE_LOCAL && $_variable_scope == Smarty::SCOPE_LOCAL) {
                continue;
            } 
            if (isset($this->parent) && ($scope == Smarty::SCOPE_PARENT || $_variable_scope == Smarty::SCOPE_PARENT)) {
                if (isset($this->parent->tpl_vars[$_key])) {
                    // variable is already defined in parent, copy value
                    $this->parent->tpl_vars[$_key]->value = $this->tpl_vars[$_key]->value;
                } else {
                    // create variable in parent
                    $this->parent->tpl_vars[$_key] = clone $_variable;
                    $this->parent->tpl_vars[$_key]->scope = Smarty::SCOPE_LOCAL;
                } 
            } 
            if ($scope == Smarty::SCOPE_ROOT || $_variable_scope == Smarty::SCOPE_ROOT) {
                if ($this->parent == null) {
                    continue;
                } 
                if (!$has_root) {
                    // find  root
                    $root_ptr = $this;
                    while ($root_ptr->parent != null) {
                        $root_ptr = $root_ptr->parent;
                        $has_root = true;
                    } 
                } 
                if (isset($root_ptr->tpl_vars[$_key])) {
                    // variable is already defined in root, copy value
                    $root_ptr->tpl_vars[$_key]->value = $this->tpl_vars[$_key]->value;
                } else {
                    // create variable in root
                    $root_ptr->tpl_vars[$_key] = clone $_variable;
                    $root_ptr->tpl_vars[$_key]->scope = Smarty::SCOPE_LOCAL;
                } 
            } 
            if ($scope == Smarty::SCOPE_GLOBAL || $_variable_scope == Smarty::SCOPE_GLOBAL) {
                if (isset(Smarty::$global_tpl_vars[$_key])) {
                    // variable is already defined in root, copy value
                    Smarty::$global_tpl_vars[$_key]->value = $this->tpl_vars[$_key]->value;
                } else {
                    // create global variable
                   Smarty::$global_tpl_vars[$_key] = clone $_variable;
                } 
               Smarty::$global_tpl_vars[$_key]->scope = Smarty::SCOPE_LOCAL;
            } 
        } 
    } 

    /**
     * Create property header
     */
    public function createPropertyHeader ($cache = false)
    {
        $plugins_string = ''; 
        // include code for plugins
        if (!$cache) {
            if (!empty($this->required_plugins['compiled'])) {
                $plugins_string = '<?php ';
                foreach($this->required_plugins['compiled'] as $tmp) {
                    foreach($tmp as $data) {
                        $plugins_string .= "if (!is_callable('{$data['function']}')) include '{$data['file']}';\n";
                    } 
                } 
                $plugins_string .= '?>';
            } 
            if (!empty($this->required_plugins['nocache'])) {
                $this->has_nocache_code = true;
                $plugins_string .= "<?php echo '/*%%SmartyNocache:{$this->properties['nocache_hash']}%%*/<?php ";
                foreach($this->required_plugins['nocache'] as $tmp) {
                    foreach($tmp as $data) {
                        $plugins_string .= "if (!is_callable(\'{$data['function']}\')) include \'{$data['file']}\';\n";
                    } 
                } 
                $plugins_string .= "?>/*/%%SmartyNocache:{$this->properties['nocache_hash']}%%*/';?>\n";
            } 
        } 
        // build property code
        $this->properties['has_nocache_code'] = $this->has_nocache_code;
        $properties_string = "<?php /*%%SmartyHeaderCode:{$this->properties['nocache_hash']}%%*/" ;
        if ($this->smarty->direct_access_security) {
            $properties_string .= "if(!defined('SMARTY_DIR')) exit('no direct access allowed');\n";
        } 
        if ($cache) {
            // remove compiled code of{function} definition
            unset($this->properties['function']);
            if (!empty($this->smarty->template_functions)) {
                // copy code of {function} tags called in nocache mode
                foreach ($this->smarty->template_functions as $name => $function_data) {
                    if (isset($function_data['called_nocache'])) {
                        unset($function_data['called_nocache'], $this->smarty->template_functions[$name]['called_nocache']);
                        $this->properties['function'][$name] = $function_data;
                    } 
                } 
            } 
        } 
        $properties_string .= "\$_smarty_tpl->decodeProperties(" . var_export($this->properties, true) . "); /*/%%SmartyHeaderCode%%*/?>\n";
        return $properties_string . $plugins_string;
    } 

    /**
     * Decode saved properties from compiled template and cache files
     */
    public function decodeProperties ($properties)
    {
        $this->has_nocache_code = $properties['has_nocache_code'];
        $this->properties['nocache_hash'] = $properties['nocache_hash'];
        if (isset($properties['cache_lifetime'])) {
            $this->properties['cache_lifetime'] = $properties['cache_lifetime'];
        } 
        if (isset($properties['file_dependency'])) {
            $this->properties['file_dependency'] = array_merge($this->properties['file_dependency'], $properties['file_dependency']);
        } 
        if (!empty($properties['function'])) {
            $this->properties['function'] = array_merge($this->properties['function'], $properties['function']);
            $this->smarty->template_functions = array_merge($this->smarty->template_functions, $properties['function']);
        } 
    } 

    /**
     * creates a loacal Smarty variable for array assignments
     */
    public function createLocalArrayVariable($tpl_var, $nocache = false, $scope = Smarty::SCOPE_LOCAL)
    {
        if (!isset($this->tpl_vars[$tpl_var])) {
            $tpl_var_inst = $this->getVariable($tpl_var, null, true, false);
            if ($tpl_var_inst instanceof Undefined_Smarty_Variable) {
                $this->tpl_vars[$tpl_var] = new Smarty_variable(array(), $nocache, $scope);
            } else {
                $this->tpl_vars[$tpl_var] = clone $tpl_var_inst;
                if ($scope != Smarty::SCOPE_LOCAL) {
                    $this->tpl_vars[$tpl_var]->scope = $scope;
                } 
            } 
        } 
        if (!(is_array($this->tpl_vars[$tpl_var]->value) || $this->tpl_vars[$tpl_var]->value instanceof ArrayAccess)) {
            settype($this->tpl_vars[$tpl_var]->value, 'array');
        } 
    } 

    /**
     * [util function] counts an array, arrayaccess/traversable or PDOStatement object
     * @param mixed $value
     * @return int the count for arrays and objects that implement countable, 1 for other objects that don't, and 0 for empty elements
     */
    public function _count($value)
    {
        if (is_array($value) === true || $value instanceof Countable) {
            return count($value);
        } elseif ($value instanceof Iterator) {
            $value->rewind();
            if ($value->valid()) {
                return iterator_count($value);
            }
        } elseif ($value instanceof PDOStatement) {
            return $value->rowCount();
        } elseif ($value instanceof Traversable) {
            return iterator_count($value);
        } elseif ($value instanceof ArrayAccess) {
            if ($value->offsetExists(0)) {
                return 1;
            }
       } elseif (is_object($value)) {
            return count($value);
       }
       return 0;
    }

    /**
     * wrapper for fetch
     */
    public function fetch ($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false)
    {
 		if ($template == null) {
        	return $this->smarty->fetch($this);
        } else {
        	if (!isset($parent)) {
        		$parent = $this;
        	}
         	return $this->smarty->fetch($template, $cache_id, $compile_id, $parent, $display);
        }
        
    } 
 
     /**
     * wrapper for display
     */
    public function display ($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
 		if ($template == null) {
        	return $this->smarty->display($this);
        } else {
        	if (!isset($parent)) {
        		$parent = $this;
        	}
       		return $this->smarty->display($template, $cache_id, $compile_id, $parent);
        }
       
    } 

    /**
     * set Smarty property in template context      
     * @param string $property_name property name
     * @param mixed $value value
     */
    public function __set($property_name, $value)
    {
        switch ($property_name) {
            case 'source':
            case 'compiled':
            case 'cached':
                $this->$property_name = $value;
                return;
            
            default:
                if (property_exists($this->smarty, $property_name)) {
            		$this->smarty->$property_name = $value;
            		return;
            	}
        }
        
        throw new SmartyException("invalid template property '$property_name'.");
    }

    /**
     * get Smarty property in template context      
     * @param string $property_name property name
     */
    public function __get($property_name)
    {
        switch ($property_name) {
            case 'source':
                if (empty($this->template_resource)) {
                    throw new SmartyException ("Unable to parse resource name \"{$this->template_resource}\"");
                }
                $this->source = Smarty_Resource::source($this);
                // cache template object under a unique ID
                // do not cache eval resources
                if ($this->source->type != 'eval') {
                    $this->smarty->template_objects[sha1($this->template_resource . $this->cache_id . $this->compile_id)] = $this;
                }

                return $this->source;
                
            case 'compiled':
                $this->compiled = $this->source->getCompiled($this);
                return $this->compiled;
                
            case 'cached':
                $this->cached = Smarty_CacheResource::cached($this);
                return $this->cached;

            default:
                if (property_exists($this->smarty, $property_name)) {
            		return $this->smarty->$property_name;
            	}
        }
        
        throw new SmartyException("template property '$property_name' does not exist.");
    }


    /**
     * Takes unknown class methods and lazy loads sysplugin files for them
     * class name format: Smarty_Method_MethodName
     * plugin filename format: method.methodname.php
     * 
     * @param string $name unknown methode name
     * @param array $args aurgument array
     */
    public function __call($name, $args)
    {
        static $camel_func;
        if (!isset($camel_func))
            $camel_func = create_function('$c', 'return "_" . strtolower($c[1]);'); 
        // see if this is a set/get for a property
        $first3 = strtolower(substr($name, 0, 3));
        if (in_array($first3, array('set', 'get')) && substr($name, 3, 1) !== '_') {
            // try to keep case correct for future PHP 6.0 case-sensitive class methods
            // lcfirst() not available < PHP 5.3.0, so improvise
            $property_name = strtolower(substr($name, 3, 1)) . substr($name, 4); 
            // convert camel case to underscored name
            $property_name = preg_replace_callback('/([A-Z])/', $camel_func, $property_name);
    		if (property_exists($this, $property_name)) {
            	if ($first3 == 'get')
                	return $this->$property_name;
            	else
                	return $this->$property_name = $args[0];
        	}
        }
        // Smarty Backward Compatible wrapper
		if (strpos($name,'_') !== false) {
        	if (!isset($this->wrapper)) {
           	 $this->wrapper = new Smarty_Internal_Wrapper($this);
        	} 
        	return $this->wrapper->convert($name, $args);
        }
        // pass call to Smarty object 	
        return call_user_func_array(array($this->smarty,$name),$args);
    } 

}
?>