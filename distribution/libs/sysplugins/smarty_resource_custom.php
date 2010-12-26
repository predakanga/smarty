<?php

/**
 * Smarty Resource Plugin
 * 
 * Wrapper Implementation for custom resource plugins
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource_Custom extends Smarty_Resource {
    /**
     * cache results of fetch() calls
     * @var array
     */
    protected $cache = array();
    
    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public function isExisting(Smarty_Internal_Template $template)
    {
        $t = $this->cache($template->resource_name);
        return !empty($t);
    }
    
    /**
     * fetch and cache template source and mtime
     *
     * @param string $name template name
     * @return array template data array('mtime' => …, 'source' => …)
     */
    protected function cache($name)
    {
        if (!isset($this->cache[$name])){
            $this->fetch($name, $source, $mtime);
            $this->cache[$name] = array(
                'mtime' => $mtime,
                'source' => $source,
            );
        }
        return $this->cache[$name];
    }

    /**
     * fetch template and its modification time from data source
     *
     * @param string $name template name
     * @param string $source template source
     * @param integer $mtime template modification timestamp (epoch)
     * @return void
     */
    protected abstract function fetch($name, &$source, &$mtime);
    
    /**
     * Fetch template's modification timestamp from data source
     *
     * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the comple template source.
     * @param string $name template name
     * @return integer|boolean timestamp (epoch) the template was modified, or false if not found
     */
    protected function fetchTimestamp($name)
    {
        return null;
    }
    
    /**
    * Get filepath to template source
    * 
    * @param Smarty_Internal_Template $_template template object
    * @return string the current resource type name
    */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    {
        $_template->templateUid = sha1($_template->resource_type . ':' . $_template->resource_name);
        return strtolower($_template->resource_type . ':' . $_template->resource_name);
    }
    
    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_type type of the resource to get modification time of. 
     * @param string $resource_name name of the resource to get modification time of, if null, $_template->resource_name is used
     * @return integer|boolean timestamp (epoch) the template was modified, false if resources has no timestamp
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template, $resource_name=null)
    {
        $name = $resource_name ? $resource_name : $_template->resource_name;
        $mtime = $this->fetchTimestamp($name);
        if ($mtime !== null)
            return $mtime;
        $t = $this->cache($name);
        return isset($t['mtime']) ? $t['mtime'] : false;
    }
    
    /**
     * Load template's source into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    {
        $t = $this->cache($_template->resource_name);
        if (isset($t['source'])) {
            $_template->template_source = $t['source'];
            return true;
        }
        return false;
    }
    
    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string|boolean path to compiled template or false if not applicable
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return $this->buildCompiledFilepath($_template, basename($_template->resource_name));
    }
}

?>