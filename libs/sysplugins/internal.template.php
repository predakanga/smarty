<?php

/**
* Smarty template class
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Template extends Smarty_Internal_TemplateBase {
    // object cache
    static $resource_objects = array();
    static $caching_objects = array();
    static $compiler_objects = array();
    static $write_file_object = null; 
    // Smarty parameter
    public $cache_id = null;
    public $compile_id = null;
    public $caching = null;
    public $caching_lifetime = null;
    public $compiler_class = null;
    public $caching_type = null; 
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
    // Cache file
    private $cached_filepath = null;
    public $cached_template = null;
    private $cached_timestamp = null;
    private $isCached = null; 
    // template variables
    public $tpl_vars = null;

    public function __construct($template_resource, $_parent_tpl_vars = null, $_cache_id = null, $_compile_id = null)
    {
        $this->smarty = Smarty::instance(); 
        // Smarty parameter
        $this->cache_id = $_cache_id === null ? $this->smarty->cache_id : $_cache_id;
        $this->compile_id = $_compile_id === null ? $this->smarty->compile_id : $_compile_id;
        $this->caching = $this->smarty->caching;
        $this->caching_lifetime = $this->smarty->caching_lifetime;
        $this->compiler_class = $this->smarty->compiler_class;
        $this->caching_type = $this->smarty->default_caching_type;
        $this->tpl_vars = new Smarty_Data;
        $this->parent_tpl_vars = $_parent_tpl_vars; 
        // Template resource
        $this->template_resource = $template_resource; 
        // parse resource name
        if (!$this->parseResourceName ($template_resource)) {
            throw new SmartyException ("Unable to parse resource '{$template_resource}'");
        } 
        // if we have a parent copy of the template vars
        if (is_object($_parent_tpl_vars)) {
            // is a Smarty data object
            foreach ($_parent_tpl_vars->tpl_vars as $_key => $_value) {
                $this->tpl_vars->tpl_vars[$_key] = clone $_parent_tpl_vars->tpl_vars[$_key];
            } 
        } elseif (is_array($_parent_tpl_vars)) {
            // is a PHP array
            foreach ($_parent_tpl_vars as $_key => $_value) {
                $this->tpl_vars->tpl_vars[$_key]->data = $_value;
                $this->tpl_vars->tpl_vars[$_key]->caching = false;
                $this->tpl_vars->tpl_vars[$_key]->global = false;
            } 
        } 
    } 

    public function updateGlobalVariables ()
    { 
        // copy global vars back to parent
        if (is_object($this->parent_tpl_vars)) {
            foreach ($this->tpl_vars->tpl_vars as $_key => $_value) {
                if ($this->tpl_vars->tpl_vars[$_key]->global) {
                    if (isset($this->parent_tpl_vars->tpl_vars[$_key])) {
                        $this->parent_tpl_vars->tpl_vars[$_key]->data = $this->tpl_vars->tpl_vars[$_key]->data;
                    } else {
                        $this->parent_tpl_vars->tpl_vars[$_key] = clone $this->tpl_vars->tpl_vars[$_key];
                        $this->parent_tpl_vars->tpl_vars[$_key]->global = false;
                    } 
                } 
            } 
        } 
    } 

    public function getTemplateFilepath ()
    {
        return $this->template_filepath === null ?
        $this->template_filepath = $this->resource_objects[$this->resource_type]->getTemplateFilepath($this) :
        $this->template_filepath;
    } 

    public function getTemplateTimestamp ()
    {
        return $this->template_timestamp === null ?
        $this->template_timestamp = $this->resource_objects[$this->resource_type]->getTemplateTimestamp($this) :
        $this->template_timestamp;
    } 

    public function getTemplateSource ()
    {
        if ($this->template_source === null) {
            if ($this->resource_objects[$this->resource_type]->getTemplateSource($this) === false) {
                throw new SmartyException("Unable to load template {$this->template_resource}");
            } 
        } 
        return $this->template_source;
    } 

    public function usesCompiler ()
    {
        return $this->usesCompiler === null ?
        $this->usesCompiler = $this->resource_objects[$this->resource_type]->usesCompiler() :
        $this->usesCompiler;
    } 

    public function isEvaluated ()
    {
        return $this->isEvaluated === null ?
        $this->isEvaluated = $this->resource_objects[$this->resource_type]->isEvaluated() :
        $this->isEvaluated;
    } 

    public function mustCompile ()
    {
        return $this->mustCompile === null ?
        $this->mustCompile = ($this->usesCompiler() && ($this->smarty->force_compile || !file_exists($this->getCompiledFilepath ()) || $this->getTemplateTimestamp () === false || filemtime($this->getCompiledFilepath ()) !== $this->getTemplateTimestamp ())):
        $this->mustCompile;
    } 

    public function getCompiledFilepath ()
    {
        return $this->compiled_filepath === null ?
        $this->compiled_filepath = $this->buildCompiledFilepath() :
        $this->compiled_filepath;
    } 

    public function getCompiledTimestamp ()
    {
        return $this->compiled_timestamp === null ?
        ($this->compiled_timestamp = file_exists($this->getCompiledFilepath()) ? filemtime($this->getCompiledFilepath()) : 0) :
        $this->compiled_timestamp;
    } 

    public function getCompiledTemplate ()
    {
        if ($this->compiled_template === null) {
            // see if template needs compiling.
            if ($this->mustCompile()) {
                $this->compileTemplateSource();
            } else {
                $this->compiled_template = file_get_contents($this->getCompiledFilepath());
            } 
        } else {
            return $this->compiled_template;
        } 
    } 

    public function compileTemplateSource ()
    { 
        // compile template
        if (!is_object($this->compiler_objects[$this->compiler_class])) {
            $this->smarty->loadPlugin('Smarty_Internal_CompileBase');
            $this->smarty->loadPlugin($this->compiler_class);
            $this->compiler_objects[$this->compiler_class] = new $this->compiler_class;
        } 
        // did compiling succeed?
        if ($this->compiler_objects[$this->compiler_class]->compile($this)) {
            if (!$this->isEvaluated()) {
                if (!is_object($this->write_file_object)) {
                    $this->smarty->loadPlugin("Smarty_Internal_Write_File");
                    $this->write_file_object = new Smarty_Internal_Write_File;
                } 
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
    } 

    public function getCachedFilepath ()
    {
        return $this->cached_filepath === null ?
        $this->cached_filepath = $this->caching_objects[$this->caching_type]->getCachedFilepath($this) :
        $this->cached_filepath;
    } 

    public function getCachedTimestamp ()
    {
        return $this->caching_timestamp === null ?
        $this->cached_timestamp = $this->caching_objects[$this->caching_type]->getCachedTimestamp($this) :
        $this->cached_timestamp;
    } 

    public function getCachedContent ()
    {
        $this->loadCacheHandler();
        return $this->cached_content === null ?
        $this->cached_content = $this->caching_objects[$this->caching_type]->getCachedContent($this) :
        $this->cached_content;
    } 

    public function writeCachedContent ()
    {
        return $this->caching_objects[$this->caching_type]->writeCachedContent($this);
    } 

    public function isCached ()
    {
        if ($this->isCached === null) {
            $this->isCached = false;
            if ($this->caching) {
                $this->loadCacheHandler();
                if (!$this->mustCompile() && filemtime($this->getCompiledFilepath()) < $this->getCachedTimestamp() && ((time() <= $this->getCachedTimestamp() + $this->caching_lifetime) || $this->caching_lifetime < 0)) {
                    $this->cached_template = $this->caching_objects[$this->caching_type]->getCachedContents($this);
                    $this->isCached = true;
                } 
            } 
        } 
        return $this->isCached;
    } 

    private function loadCacheHandler ()
    { 
        // load cache handler if required
        if (!isset($this->caching_objects[$this->caching_type])) {
            // is this an internal or custom cache handler?
            if (in_array($this->caching_type, array('file'))) {
                // internal, get from sysplugins dir
                $_caching_class = "Smarty_Internal_Caching_{$this->caching_type}";
            } else {
                // custom, get from plugins dir
                $_caching_class = "Smarty_Caching_{$this->caching_type}";
            } 
            // load resource plugin, instantiate
            $this->smarty->loadPlugin($_caching_class);
            $this->caching_objects[$this->caching_type] = new $_caching_class;
        } 
    } 

    public function getRenderedTemplate ()
    { 
        // read from cache or render
        if ($this->cached_template === null && !$this->isCached()) {
            // render template (not loaded and not in cache)
            $this->renderTemplate();
        } 
        if ($this->caching && $this->usesCompiler()) {
            // cached output could contain nocache code
            ob_start();
            eval("?>" . $this->cached_template);
            $this->updateGlobalVariables();
            return ob_get_clean();
        } else {
            $this->updateGlobalVariables();
            return $this->cached_template;
        } 
    } 

    public function renderTemplate ()
    {
        if ($this->cached_template === null) {
            if ($this->usesCompiler()) {
                $this->getCompiledTemplate(); 
                // extract($this->smarty->tpl_vars);
                foreach ($this->smarty->tpl_vars as $_smarty_var => $_smarty_value) {
                    if (isset($_smarty_value->data)) {
                        $$_smarty_var = $_smarty_value->data;
                    } 
                } 
                unset ($_smarty_var, $_smarty_value);
                ob_start();
                eval('?>' . $this->compiled_template);
            } else {
                // php template, just include it
                // extract($this->smarty->tpl_vars);
                foreach ($this->smarty->tpl_vars as $_smarty_var => $_smarty_value) {
                    if (isset($_smarty_value->data)) {
                        $$_smarty_var = $_smarty_value->data;
                    } 
                } 
                unset ($_smarty_var, $_smarty_value);
                ob_start();
                include(getTemplateFilepath ());
            } 
            $this->cached_template = ob_get_clean(); 
            // write to cache when nessecary
            if (!$this->isEvaluated() && $this->caching) {
                // write rendered template
                $this->writeCachedContent($this);
            } 
        } 
    } 

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
        $this->smarty->template_objects[$this->buildTemplateId ($this->template_resource, $this->cache_id, $this->compile_id)] = $this;
        return true;
    } 
    // build a unique template ID
    public function buildTemplateId ($_resorce, $_cache_id, $_compile_id)
    {
        return md5($_resorce . md5($_cache_id) . md5($_compile_id));
    } 

    /*
     * get system filepath to template
     */
    public function buildTemplateFilepath ()
    {
        foreach((array)$this->smarty->template_dir as $_template_dir) {
            $_filepath = $_template_dir . $this->resource_name;
            if (file_exists($_filepath))
                return $_filepath;
        } 
        // no tpl file found
        throw new SmartyException("Unable to load template {$this->resource_name}");
        return false;
    } 

    /*
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

        return $this->smarty->compile_dir . $_filepath . '.' . $this->resource_name . $this->smarty->php_ext;
    } 
} 
// wrapper for template class
class Smarty_Template extends Smarty_Internal_Template {
} 

?>
