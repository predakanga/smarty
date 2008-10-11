<?php

/**
* Smarty template class
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Template extends Smarty_Internal_Base {
    static $resource_objects = array();
    static $caching_objects = array();
    static $write_file_object = null;

    public function __construct($template_resource, $_cache_id = null, $_compile_id = null)
    {
        parent::__construct(); 
        // set instance object
        // self::instance($this);
        // initianlize
        $this->template_resource = $template_resource;
        $this->template_cache_id = $_cache_id === null ? $this->smarty->cache_id : $_cache_id;
        $this->template_compile_id = $_compile_id === null ? $this->smarty->compile_id : $_compile_id;
        $this->resource_type = null;
        $this->resource_name = null;
        $this->usesCompiler = null;
        $this->isEvaluated = null;
        $this->isCached = null;
        $this->caching = $this->smarty->caching;
        $this->caching_lifetime = $this->smarty->caching_lifetime;
        $this->template_filepath = null;
        $this->compiled_filepath = null;
        $this->template_timestamp = null;
        $this->cached_filepath = null;
        $this->cached_timestamp = null;
        $this->template_contents = null;
        $this->compiled_template = null;
        $this->rendered_template = null;
        $this->compiler_class = $this->smarty->compiler_class;
        $this->caching_type = $this->smarty->default_caching_type; 
        // parse resource name
        if (!$this->parseResourceName ($template_resource)) {
            throw new SmartyException ("Unable to parse resource '{$template_resource}'");
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

    public function getCompiledFilepath ()
    {
        return $this->compiled_filepath === null ?
        $this->compiled_filepath = $this->buildCompiledFilepath() :
        $this->compiled_filepath;
    } 

    public function getTemplateFilepath ()
    {
        return $this->template_filepath === null ?
        $this->template_filepath = $this->resource_objects[$this->resource_type]->getTemplateFilepath($this) :
        $this->template_filepath;
    } 

    public function getTimestamp ()
    {
        return $this->template_timestamp === null ?
        $this->template_timestamp = $this->resource_objects[$this->resource_type]->getTimestamp($this) :
        $this->template_timestamp;
    } 

    public function mustCompile ()
    {
        return $this->mustCompile === null ?
        $this->mustCompile = ($this->usesCompiler() && ($this->smarty->force_compile || !file_exists($this->getCompiledFilepath ()) || $this->getTimestamp () === false || filemtime($this->getCompiledFilepath ()) !== $this->getTimestamp ())):
        $this->mustCompile;
    } 

    public function getContents ()
    {
        if ($this->template_contents === null) {
            if (($this->template_contents = $this->resource_objects[$this->resource_type]->getContents($this)) === false) {
                throw new SmartyException("Unable to load template {$this->template_resource}");
            } 
        } 
        return $this->template_contents;
    } 

    public function compileTemplateSource ()
    {
        $this->smarty->loadPlugin('Smarty_Internal_CompileBase');
        $this->smarty->loadPlugin($this->compiler_class);
        $_compiler = new $this->compiler_class;
        return $_compiler->compile($this);
    } 

    public function processTemplate ()
    { 
        // see if template needs compiling.
        if ($this->mustCompile()) {
            // compile template
            // did compiling succeed?
            if ($this->compileTemplateSource()) {
                if (!$this->isEvaluated()) {
                    if (!is_object($this->write_file_object)) {
                        $this->smarty->loadPlugin("Smarty_Internal_Write_File");
                        $this->write_file_object = new Smarty_Internal_Write_File;
                    } 
                    // write compiled template
                    $this->write_file_object->writeFile($this->getCompiledFilepath(), $this->getCompiledTemplate()); 
                    // make template and compiled file timestamp match
                    touch($this->getCompiledFilepath(), $this->getTimestamp());
                } 
            } else {
                // error compiling template
                throw new SmartyException("Error compiling template {$this->getTemplateFilepath ()}");
                return false;
            } 
        } 
    } 

    public function getCompiledTemplate ()
    {
        return $this->compiled_template;
    } 

    public function getCachedFilepath ()
    {
        return $this->cached_filepath === null ?
        $this->cached_filepath = $this->caching_objects[$this->caching_type]->getCachedFilepath($this) :
        $this->cached_filepath;
    } 

    public function getCachingTimestamp ()
    {
        return $this->caching_timestamp === null ?
        $this->cached_timestamp = $this->caching_objects[$this->caching_type]->getTimestamp($this) :
        $this->cached_timestamp;
    } 

    public function getCachedContent ()
    {
        return $this->cached_content === null ?
        $this->cached_content = $this->caching_objects[$this->caching_type]->getContent($this) :
        $this->cached_content;
    } 

    public function writeCachedContent ()
    {
        return $this->caching_objects[$this->caching_type]->writeContent($this);
    } 

    public function isCached ()
    {
        if ($this->isCached === null) {
            $this->isCached = false;
            if ($this->caching && $this->caching_lifetime != 0) {
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
                if (!$this->mustCompile() && filemtime($this->getCompiledFilepath()) < $this->getCachingTimestamp() && ((time() <= $this->getCachingTimestamp() + $this->caching_lifetime) || $this->caching_lifetime < 0)) {
                    $this->rendered_template = $this->caching_objects[$this->caching_type]->getContents($this);
                    $this->isCached = true;
                } 
            } 
        } 
        return $this->isCached;
    } 

    public function getRenderedTemplate ()
    { 
        // read from cache or render
        if ($this->rendered_template === null && !$this->isCached()) {
            // render template (not loaded and not in cache)
            $this->renderTemplate();
        } 
        return $this->rendered_template;
    } 

    public function renderTemplate ()
    {
        if ($this->rendered_template === null) {
            if ($this->usesCompiler()) {
                $this->processTemplate();
                extract($this->smarty->tpl_vars);
                ob_start();
                if ($this->compiled_template === null) {
                    include($this->compiled_filepath);
                } else {
                    eval('?>' . $this->compiled_template);
                } 
            } else {
                // php template, just include it
                extract($this->smarty->tpl_vars);
                ob_start();
                include(getTemplateFilepath ());
            } 
            $this->rendered_template = ob_get_contents();
            ob_clean(); 
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
            return true;
        } 
        // get type and name from path
        list($this->resource_type, $this->resource_name) = explode(':', $template_resource, 2);
        if (strlen($this->resource_type) == 1) {
            // 1 char is not resource type, but part of filepath
            $this->resource_type = $this->smarty->default_resource_type;
            $rthis->esource_name = $template_resource;
        } else {
            $this->resource_type = strtolower($this->resource_type);
        } 
        return true;
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

        return $this->smarty->compile_dir . $_filepath . '.' . $this->resource_name . $this->smarty->php_ext;
    } 
} 

?>
