<?php

/**
* Smarty Internal Plugin Template
* 
* This files contains the Smarty template engine
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
    static $resource_objects = array();
    static $write_file_object = null;
    static $compiler_object = null;
    static $cacher_object = null;
    static $cache_resource_objects = array(); 
    // Smarty parameter
    public $cache_id = null;
    public $compile_id = null;
    public $caching = null;
    public $caching_lifetime = null;
    public $compiler_class = null;
    public $cacher_class = null;
    public $caching_type = null;
    public $force_compile = null; 
    // Template resource
    public $template_resource = null;
    public $resource_type = null;
    public $resource_name = null;
    private $usesCompiler = null;
    private $isEvaluated = null; 
    // Template source
    private $template_filepath = null;
    public $template_source = null;
    private $template_timestamp = null; 
    // Compiled temlate
    private $compiled_filepath = null;
    public $compiled_template = null;
    private $compiled_filestamp = null;
    public $compile_time = 0; 
    // Cache file
    private $cached_filepath = null;
    public $cached_template = null;
    private $cached_timestamp = null;
    private $isCached = null;
    public $cache_time = 0; 
    // template variables
    public $tpl_vars;
    public $render_time = 0;

    /**
    * Create template data object
    * 
    * Some of the global Smarty settings copied to template scope
    * It load the required template resources and cacher plugins
    * 
    * @param string $template_resource template resource string
    * @param object $_parent_tpl_vars back pointer to parent Smarty variables or null
    * @param mixed $_cache_id cache id or null
    * @param mixed $_compile_id compile id or null
    */
    public function __construct($template_resource, $_parent_tpl_vars = null, $_cache_id = null, $_compile_id = null)
    {
        $this->smarty = Smarty::instance(); 
        // Smarty parameter
        $this->cache_id = $_cache_id === null ? $this->smarty->cache_id : $_cache_id;
        $this->compile_id = $_compile_id === null ? $this->smarty->compile_id : $_compile_id;
        $this->force_compile = $this->smarty->force_compile;
        $this->caching = $this->smarty->caching;
        $this->caching_lifetime = $this->smarty->caching_lifetime;
        $this->compiler_class = $this->smarty->compiler_class;
        $this->cacher_class = $this->smarty->cacher_class;
        $this->caching_type = $this->smarty->default_caching_type;
        $this->cache_resource_class = 'Smarty_Internal_CacheResource_' . ucfirst($this->caching_type);
        $this->tpl_vars = new Smarty_Data($_parent_tpl_vars); 
        // load cacher
        if (!is_object($this->cacher_object)) {
            $this->smarty->loadPlugin($this->cacher_class);
            $this->cacher_object = new $this->cacher_class;
        } 
        // load cache resource
        if (!is_object($this->cache_resource_objects[$this->caching_type])) {
            $this->smarty->loadPlugin($this->cache_resource_class);
            $this->cache_resource_objects[$this->caching_type] = new $this->cache_resource_class;
        } 
        // Template resource
        $this->template_resource = $template_resource; 
        // parse resource name
        if (!$this->parseResourceName ($template_resource)) {
            throw new SmartyException ("Unable to parse resource '{$template_resource}'");
        } 
    } 

    /**
    * Returns the template filepath
    * 
    * The template filepath is determined by the actual resource handler
    * 
    * @return string the template filepath
    */
    public function getTemplateFilepath ()
    {
        return $this->template_filepath === null ?
        $this->template_filepath = $this->resource_objects[$this->resource_type]->getTemplateFilepath($this) :
        $this->template_filepath;
    } 

    /**
    * Returns the timpestamp of the template source
    * 
    * The template timestamp is determined by the actual resource handler
    * 
    * @return integer the template timestamp
    */
    public function getTemplateTimestamp ()
    {
        return $this->template_timestamp === null ?
        $this->template_timestamp = $this->resource_objects[$this->resource_type]->getTemplateTimestamp($this) :
        $this->template_timestamp;
    } 

    /**
    * Returns the template source code
    * 
    * The template source is being read by the actual resource handler
    * 
    * @return string the template source
    */
    public function getTemplateSource ()
    {
        if ($this->template_source === null) {
            if ($this->resource_objects[$this->resource_type]->getTemplateSource($this) === false) {
                throw new SmartyException("Unable to load template {$this->template_resource}");
            } 
        } 
        return $this->template_source;
    } 

    /**
    * Returns if the template resource uses the Smarty compiler
    * 
    * The status is determined by the actual resource handler
    * 
    * @return boolean true if the template will use the compiler
    */
    public function usesCompiler ()
    {
        return $this->usesCompiler === null ?
        $this->usesCompiler = $this->resource_objects[$this->resource_type]->usesCompiler() :
        $this->usesCompiler;
    } 

    /**
    * Returns if the compiled template is stored or just evaluated in memory
    * 
    * The status is determined by the actual resource handler
    * 
    * @return boolean true if the compiled template has to be evaluated
    */
    public function isEvaluated ()
    {
        return $this->isEvaluated === null ?
        $this->isEvaluated = $this->resource_objects[$this->resource_type]->isEvaluated() :
        $this->isEvaluated;
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
        return $this->mustCompile === null ?
        $this->mustCompile = ($this->usesCompiler() && ($this->force_compile || !file_exists($this->getCompiledFilepath ()) || $this->getTemplateTimestamp () === false || filemtime($this->getCompiledFilepath ()) !== $this->getTemplateTimestamp ())):
        $this->mustCompile;
    } 

    /**
    * Returns the compiled template filepath
    * 
    * @return string the template filepath
    */
    public function getCompiledFilepath ()
    {
        return $this->compiled_filepath === null ?
        $this->compiled_filepath = $this->buildCompiledFilepath() :
        $this->compiled_filepath;
    } 

    /**
    * Returns the timpestamp of the compiled template
    * 
    * @return integer the template timestamp
    */
    public function getCompiledTimestamp ()
    {
        return $this->compiled_timestamp === null ?
        ($this->compiled_timestamp = file_exists($this->getCompiledFilepath()) ? filemtime($this->getCompiledFilepath()) : 0) :
        $this->compiled_timestamp;
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
                $this->compiled_template = file_get_contents($this->getCompiledFilepath());
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
        $_start_time = $this->_get_time(); 
        // compile template
        if (!is_object($this->compiler_object)) {
            // load compiler
            $this->smarty->loadPlugin('Smarty_Internal_CompileBase');
            $this->smarty->loadPlugin($this->compiler_class);
            $this->compiler_object = new $this->compiler_class;
        } 
        if (!is_object($this->write_file_object)) {
            $this->smarty->loadPlugin("Smarty_Internal_Write_File");
            $this->write_file_object = new Smarty_Internal_Write_File;
        } 
        // call compiler
        if ($this->compiler_object->compileTemplate($this)) {
            // compiling succeded
            if (!$this->isEvaluated()) {
                // write compiled template
                $this->write_file_object->writeFile($this->getCompiledFilepath(), $this->getCompiledTemplate()); 
                // make template and compiled file timestamp match
                touch($this->getCompiledFilepath(), $this->getTemplateTimestamp());
            } 
        } else {
            // error compiling template
            throw new SmartyException("Error compiling template {$this->getTemplateFilepath ()}");
            return false;
        } 
        $this->compile_time = $this->_get_time() - $_start_time;
    } 

    /**
    * Returns the filepath of the cached template output
    * 
    * The filepath is determined by the actual resource handler of the cacher
    * 
    * @return string the cache filepath
    */
    public function getCachedFilepath ()
    {
        return $this->cached_filepath === null ?
        $this->cached_filepath = $this->cache_resource_objects[$this->caching_type]->getCachedFilepath($this) :
        $this->cached_filepath;
    } 

    /**
    * Returns the timpestamp of the cached template output
    * 
    * The timestamp is determined by the actual resource handler of the cacher
    * 
    * @return integer the template timestamp
    */
    public function getCachedTimestamp ()
    {
        return $this->caching_timestamp === null ?
        $this->cached_timestamp = $this->cache_resource_objects[$this->caching_type]->getCachedTimestamp($this) :
        $this->cached_timestamp;
    } 

    /**
    * Returns the cached template output
    * 
    * @return string |booelan the template content or false if the file does not exist
    */
    public function getCachedContent ()
    {
        return $this->cached_content === null ?
        $this->cached_content = $this->cacher_object->getCachedContent($this) :
        $this->cached_content;
    } 

    /**
    * Writes the cached template output 
    *
    */
    public function writeCachedContent ()
    {
        return $this->cacher_object->writeCachedContent($this);
    } 

    /**
    * Checks of a valid version redered HTML output is in the cache
    *
    * If the cache is valid the contents is stored in the template object
    * @return boolean true if cache is valid
    */ 
    public function isCached ()
    {
        if ($this->isCached === null) {
            $this->isCached = false;
            if ($this->caching) {
                if (!$this->mustCompile() && $this->getCompiledTimestamp() < $this->getCachedTimestamp() && ((time() <= $this->getCachedTimestamp() + $this->caching_lifetime) || $this->caching_lifetime < 0)) {
                    $this->cached_template = $this->cacher_object->getCachedContents($this);
                    $this->isCached = true;
                } 
            } 
        } 
        return $this->isCached;
    } 

    /**
    * Returns the rendered HTML output 
    *
    * If the cache is valid the cached content is used, otherwise
    * the output is rendered from the compiled template or PHP template source
    * @return string rendered HTML output
    */ 
    public function getRenderedTemplate ()
    { 
        // disable caching for evaluated code
        if ($this->isEvaluated()) {
            $this->caching = false;
        } 
        // read from cache or render
        if ($this->cached_template === null && !$this->isCached()) {
            // render template (not loaded and not in cache)
            $this->renderTemplate();
        } 
        if ($this->caching && $this->usesCompiler()) {
            // cached output could contain nocache code
            $_start_time = $this->_get_time();
            $_smarty_tpl = $this;
            ob_start();
            eval("?>" . $this->cached_template);
            $this->updateGlobalVariables();
            $this->cache_time = $this->_get_time() - $_start_time;
            return ob_get_clean();
        } else {
            $this->updateGlobalVariables();
            return $this->cached_template;
        } 
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
        if ($this->cached_template === null) {
            if ($this->usesCompiler()) {
                if ($this->mustCompile()) {
                    $this->compileTemplateSource();
                } 
                $_smarty_tpl = $this;
                $_start_time = $this->_get_time();
                ob_start();
                if ($this->isEvaluated()) {
                    eval("?>" . $this->compiled_template);
                } else {
                    include($this->getCompiledFilepath ());
                } 
            } else {
                // PHP template
                $_start_time = $this->_get_time(); 
                // Smarty variables as objects extract as objects
                $this->smarty->loadPlugin('Smarty_Internal_PHPVariableObjects');
                $_tpl_vars = $this->tpl_vars;
                do {
                    foreach ($_tpl_vars->tpl_vars as $_smarty_var => $_var_object) {
                        if (isset($_var_object->value)) {
                            $$_smarty_var = Smarty_Internal_PHPVariableObjects::createPHPVarObj($_var_object->value);
                        } 
                    } 
                    $_tpl_vars = $_tpl_vars->parent_tpl_vars;
                } while ($_tpl_vars != null);

                unset ($_smarty_var, $_smarty_value, $_tpl_vars);
                ob_start(); 
                // include PHP template
                include($this->getTemplateFilepath ());
            } 
            $this->render_time = $this->_get_time() - $_start_time;
            $this->cached_template = ob_get_clean(); 
            // write to cache when nessecary
            if (!$this->isEvaluated() && $this->caching) {
                // write rendered template
                $this->writeCachedContent($this);
            } 
        } 
    } 

    /**
    * parse a template resource in its name and type
    * 
    * @param string $template_resource template resource specification
    */
    private function parseResourceName($template_resource)
    {
        if (empty($template_resource))
            return false;
        if (strpos($template_resource, ':') === false) {
            // no resource given, use default
            $this->resource_type = $this->smarty->default_resource_type;
            $this->resource_name = $template_resource;
        } else {
            // get type and name from path
            list($this->resource_type, $this->resource_name) = explode(':', $template_resource, 2);
            if (strlen($this->resource_type) == 1) {
                // 1 char is not resource type, but part of filepath
                $this->resource_type = $this->smarty->default_resource_type;
                $this->resource_name = $template_resource;
            } else {
                $this->resource_type = strtolower($this->resource_type);
            } 
        } 
        // load resource handler if required
        if (!isset($this->resource_objects[$this->resource_type])) {
            // is this an internal or custom resource?
            if (in_array($this->resource_type, array('file', 'php', 'string'))) {
                // internal, get from sysplugins dir
                $_resource_class = "Smarty_Internal_Resource_{$this->resource_type}";
            } else {
                // custom, get from plugins dir
                $_resource_class = "Smarty_Resource_{$this->resource_type}";
            } 
            // load resource plugin, instantiate
            $this->smarty->loadPlugin($_resource_class);
            $this->resource_objects[$this->resource_type] = new $_resource_class;
        } 
        // cache template object under a unique ID
        Smarty::$template_objects[$this->buildTemplateId ($this->template_resource, $this->cache_id, $this->compile_id)] = $this;
        return true;
    } 

    /**
    * get system filepath to template
    */
    public function buildTemplateFilepath ()
    {
        foreach((array)$this->smarty->template_dir as $_template_dir) {
            $_filepath = $_template_dir . $this->resource_name;
            if (file_exists($_filepath))
                return $_filepath;
        } 
        if (file_exists($this->resource_name)) return $this->resource_name; 
        // no tpl file found
        throw new SmartyException("Unable to load template {$this->resource_name}");
        return false;
    } 

    /**
    * get system filepath to compiled file
    */
    private function buildCompiledFilepath ()
    {
        $_filepath = md5($this->resource_name); 
        // if use_sub_dirs, break file into directories
        if ($this->smarty->use_sub_dirs) {
            $_filepath = substr($_filepath, 0, 3) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 2) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 1) . DIRECTORY_SEPARATOR
             . $_filepath;
        } 
        $_compile_dir_sep = $this->smarty->use_sub_dirs ? DIRECTORY_SEPARATOR : '^';
        if (isset($this->compile_id)) {
            $_filepath = $this->compile_id . $_compile_dir_sep . $_filepath;
        } 
        if ($this->caching) {
            $_cache = '.cache';
        } else {
            $_cache = '';
        } 
        return $this->smarty->compile_dir . $_filepath . '.' . basename($this->resource_name) . $_cache . $this->smarty->php_ext;
    } 

    /**
    * Update global Smarty variables in parent variable object
    */
    public function updateGlobalVariables ()
    { 
        // do we have a back pointer?
        if (is_object($this->tpl_vars->parent_tpl_vars)) {
            foreach ($this->tpl_vars->tpl_vars as $_key => $_value) {
                // copy global vars back to parent
                if ($this->tpl_vars->tpl_vars[$_key]->global) {
                    if (isset($this->parent_tpl_vars->tpl_vars[$_key])) {
                        // variable is already defined in parent, copy value
                        $this->tpl_vars->parent_tpl_varstpl_vars[$_key]->value = $this->tpl_vars->tpl_vars[$_key]->value;
                    } else {
                        // create variable in parent
                        $this->tpl_vars->parent_tpl_vars->tpl_vars[$_key] = clone $_value;
                    } 
                } 
            } 
        } 
    } 
} 

/**
* wrapper for template class
*/
class Smarty_Template extends Smarty_Internal_Template {
} 

?>
