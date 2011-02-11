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
class Smarty_Internal_Template extends Smarty_Internal_TemplateBase {
	// object cache
	public $compiler_object = null;
	
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
	* Writes the cached template output
	*/
	public function writeCachedContent ($content)
	{
		if ($this->source->recompiled || !($this->caching == Smarty::CACHING_LIFETIME_CURRENT || $this->caching == Smarty::CACHING_LIFETIME_SAVED)) {
			// don't write cache file
			return false;
		}
		$this->properties['cache_lifetime'] = $this->cache_lifetime;
		return $this->cached->write($this, $this->createWriteContent($content,true));
	}


	/**
	* Get Subtemplate Content
	*
	* @param string $template the resource handle of the template file
	* @param mixed $cache_id cache id to be used with this template
	* @param mixed $compile_id compile id to be used with this template
	* @param integer $caching cache mode
	* @param integer $cache_lifetime life time of cache data
	* @param array $vars optional variables to assign
	* @param object $parent next higher level of Smarty variables
	* @param int $parent_scope scope in which {include} should execute
	* @returns string template content
	*/
	public function getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent, $parent_scope)
	{
		// already in template cache?
		$_templateId =  sha1($template . $cache_id . $compile_id);
		if (isset($parent->smarty->template_objects[$_templateId])) {
			// return cached template object
			$known = true;
			$tpl = $parent->smarty->template_objects[$_templateId];
			$save = array($tpl->parent, $tpl->tpl_vars, $tpl->caching, $tpl->cache_lifetime);
			$tpl->parent = $parent;
			$tpl->tpl_vars = array('smarty' => new Smarty_variable());
			$tpl->caching = $caching;
			$tpl->cache_lifetime = $cache_lifetime;
		} else {
			$known = false;
			$tpl = new $parent->smarty->template_class($template, $parent->smarty, $parent, $cache_id, $compile_id, $caching, $cache_lifetime);
		}
		if (!empty($data)) {
			// set up variable values
			foreach ($data as $_key => $_val) {
				$tpl->tpl_vars[$_key] = new Smarty_variable($_val);
			}
		}
		$output = $tpl->fetch();
		if ($parent_scope != Smarty::SCOPE_LOCAL) {
			$tpl->updateParentVariables($parent_scope);
		}
		if ($known) {
			list($tpl->parent, $tpl->tpl_vars, $tpl->caching, $tpl->cache_lifetime) = $save;
		}
		return $output;
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
	public function createWriteContent ($content = '', $cache = false)
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
		$output = '';
		if (!$this->source->recompiled) {
			$output = "<?php /*%%SmartyHeaderCode:{$this->properties['nocache_hash']}%%*/" ;
			if ($this->smarty->direct_access_security) {
				$output .= "if(!defined('SMARTY_DIR')) exit('no direct access allowed');\n";
			}
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
		$this->properties['version'] = $this->smarty->_version;
		$this->properties['unifunc'] = 'content_'.uniqid();
		if (!$this->source->recompiled) {
			$output .= "\$_smarty_tpl->decodeProperties(" . var_export($this->properties, true) . "); /*/%%SmartyHeaderCode%%*/?>\n";
		}
		if (!$this->source->recompiled) {
			$output .= '<?php if (!is_callable(\''.$this->properties['unifunc'].'\')) {function '.$this->properties['unifunc'].'($_smarty_tpl) {?>';
		}
		$output .= $plugins_string;
		$output .= $content;
		if (!$this->source->recompiled) {
			$output .= '<?php }} ?>';
		}
		return $output;
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
		$this->properties['version'] = $properties['version'];
		$this->properties['unifunc'] = $properties['unifunc'];
	}

	/**
	* creates a local Smarty variable for array assignments
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
			Smarty_CacheResource::cached($this);
			return $this->cached;

			default:
			if (property_exists($this->smarty, $property_name)) {
				return $this->smarty->$property_name;
			}
		}

		throw new SmartyException("template property '$property_name' does not exist.");
	}
}
?>