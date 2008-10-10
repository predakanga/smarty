<?php

/**
* Smarty template class
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Template extends Smarty_Internal_Base {
    static $resource_objects = array();

    public function __construct($template_resource)
    {
        parent::__construct(); 
        // set instance object
        // self::instance($this);
        // initianlize
        $this->template_resource = $template_resource;
        $this->resource_type = null;
        $this->resource_name = null;
        $this->usesCompiler = null;
        $this->isEvaluated = null;
        $this->template_filepath = null;
        $this->compiled_filepath = null;
        $this->template_timestamp = null;
        $this->template_content = null;
        $this->compiled_template = null;
        $this->redered_template = null; 
        // parse resoure name
        if (!$this->parseResourceName ($template_resource)) {
            throw new SmartyException ("Missing template resource");
        } 
        // load resource handler if required
        if (!isset($this->resource_objects[$this->resource_type])) {
            // is this an internal or custom resource?
            if (in_array($this->resource_type, array('file', 'php', 'string'))) {
                // internal, get from sysplugins dir
                $_resource_class = "Smarty_Internal_Resource_{$this->resource_type}";
            } else {
                // custom, get from plugins dir
                $_resource_class = "Smarty_Resource_{$_resource_type}";
            } 
            // load resource plugin, instantiate
            $this->smarty->loadPlugin($_resource_class);
            $this->resource_objects[$this->resource_type] = new $_resource_class;
        } 
    } 

    public function usesCompiler ()
    {
        return $this->usesCompiler == null ?
        $this->usesCompiler = $this->resource_objects[$this->resource_type]->usesCompiler() :
        $this->usesCompiler;
    } 

    public function isEvaluated ()
    {
        return $this->isEvaluated == null ?
        $this->isEvaluated = $this->resource_objects[$this->resource_type]->isEvaluated() :
        $this->isEvaluated;
    } 

    public function getCompiledFilepath ()
    {
        return $this->compiled_filepath == null ?
        $this->compiled_filepath = $this->buildCompiledFilepath() :
        $this->compiled_filepath;
    } 

    public function getTemplateFilepath ()
    {
        return $this->template_filepath == null ?
        $this->template_filepath = $this->resource_objects[$this->resource_type]->getTemplateFilepath($this) :
        $this->template_filepath;
    } 

    public function getTimestamp ()
    {
        return $this->template_timestamp == null ?
        $this->template_timestamp = $this->resource_objects[$this->resource_type]->getTimestamp($this) :
        $this->template_timestamp;
    } 

    public function mustCompile ()
    {
        return ($this->smarty->force_compile || !file_exists($this->getCompiledFilepath ()) || $this->getTimestamp () === false || filemtime($this->getCompiledFilepath ()) !== $this->getTimestamp ());
    } 

    public function getContents ()
    {
        if ($this->template_contents == null) {
            if (($this->template_contents = $this->resource_objects[$this->resource_type]->getContents($this)) === false) {
                throw new SmartyException("Unable to load template {$this->template_resource}");
            } 
        } 
        return $this->template_contents;
    } 

    public function getCompiledTemplate ()
    {
        return $this->compiled_template;
    } 

    public function getRenderedTemplate ()
    {
        if ($this->redered_template == null) {
            extract($this->smarty->tpl_vars);
            ob_start();

            if ($this->usesCompiler()) {
                if ($this->compiled_template == null) {
                    include($this->compiled_filepath);
                } else {
                    eval('?>' . $this->compiled_template);
                } 
            } else {
                include(getTemplateFilepath ());
            } 
            $this->rendered_template = ob_get_contents();
            ob_clean();
        } 
        return $this->rendered_template;
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
        $_filepath = md5($this->resource_name) . $this->smarty->php_ext; 
        // if use_sub_dirs, break file into directories
        if ($this->smarty->use_sub_dirs) {
            $_filepath = substr($_filepath, 0, 3) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 2) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 1) . DIRECTORY_SEPARATOR
             . $_filepath;
        } 

        return $this->smarty->compile_dir . $_filepath;
    } 
} 

?>
