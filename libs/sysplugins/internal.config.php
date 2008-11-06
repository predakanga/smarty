<?php
/**
* Smarty Internal Plugin Config
* 
* Main class for config variables
* @ignore
* @package Smarty
* @subpackage config
* @author Uwe Tews
*/
class Smarty_Internal_Config extends Smarty_Internal_Base {
    static $config_objects = array();

    public function __construct($config_resource)
    {
        parent::__construct(); 
        // set instance object
        // self::instance($this);
        // initianlize
        $this->config_resource = $conig_resource;
        $this->config_resource_type = null;
        $this->config_resource_name = null;
        $this->config_filepath = null;
        $this->config_timestamp = null;
        // parse config resource name
        if (!$this->parseConfigResourceName ($$config_resource)) {
            throw new SmartyException ("Unable to parse config resource '{$config_resource}'");
        } 
    } 


    public function getConfigFilepath ()
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


    private function parseConfigResourceName($config_resource)
    {
        if (empty($config_resource))
            return false;
        if (strpos($config_resource, ':') === false) {
            // no resource given, use default
            $this->config_resource_type = $this->smarty->default_config_type;
            $this->config_resource_name = $template_resource;
        }  else {
            // get type and name from path
            list($this->config_resource_type, $this->config_resource_name) = explode(':', $config_resource, 2);
            if (strlen($this->config_resource_type) == 1) {
                  // 1 char is not resource type, but part of filepath
                  $this->config_resource_type = $this->smarty->default_config_type;
                  $this->config_resource_name = $config_resource;
            } else {
                  $this->config_resource_type = strtolower($this->config_resource_type);
            }
        } 
        return true;
    }
    
    // build a unique template ID
    public function buildTemplateId ($_resorce, $_cache_id, $_compile_id)
    {
            return md5($_resorce.md5($_cache_id).md5($_compile_id));
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
        $_compile_dir_sep =  $this->smarty->use_sub_dirs ? DIRECTORY_SEPARATOR : '^';
        if (isset($this->compile_id)) {
            $_filepath = $this->compile_id . $_compile_dir_sep . $_filepath;
        }

        return $this->smarty->compile_dir . $_filepath . '.' . $this->resource_name . $this->smarty->php_ext;
    } 
} 

?>
