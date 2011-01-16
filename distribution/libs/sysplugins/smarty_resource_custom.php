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
     * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the complete template source.
     * @param string $name template name
     * @return integer|boolean timestamp (epoch) the template was modified, or false if not found
     */
    protected function fetchTimestamp($name)
    {
        return null;
    }
    
    /**
     * populate Source Object with meta data from Resource
     *
     * @param Smarty_Template_Source $source source object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
    {
        $source->filepath = strtolower($source->type . ':' . $source->name);
        $source->uid = sha1($source->type . ':' . $source->name);

        $mtime = $this->fetchTimestamp($source->name);
        if ($mtime !== null) {
            $source->timestamp = $mtime;
        } else {
            $t = $this->cache($source->name);
            $source->timestamp = isset($t['mtime']) ? $t['mtime'] : false;
            if( isset($t['source']) )
                $source->content = $t['source'];
        }
        $source->exists = !!$source->timestamp;
    }
    
    /**
     * Load template's source into current template object
     * 
     * @param Smarty_Template_Source $source source object
     * @return string template source
     * @throws SmartyException if source cannot be loaded
     */
    public function getTemplateSource(Smarty_Template_Source $source)
    {
        $t = $this->cache($source->name);
        if (isset($t['source'])) {
            return $t['source'];
        }
        
        throw new SmartyException("Unable to read template {$source->type} '{$source->name}'");
    }
    
    /**
     * Determine basename for compiled filename
     *
     * @param Smarty_Template_Source $source source object
     * @return string resource's basename
     */
    protected function getBasename(Smarty_Template_Source $source)
    {
        return basename($source->name);
    }
}

?>