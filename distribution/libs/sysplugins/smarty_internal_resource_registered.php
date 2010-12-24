<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Internal Plugin Resource Registered
 * 
 * Implements the registered resource for Smarty template
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
 
/**
 * Smarty Internal Plugin Resource Registered
 */
class Smarty_Internal_Resource_Registered extends Smarty_Resource {
    
    // FIXME: (rodneyrehm) waaaah why am I getting a template instead of smarty here?
    public function __construct(Smarty_Internal_Template $template, $resource_type = null)
    {
        $this->smarty = $template->smarty;
        if (isset($resource_type)) {
        	$template->smarty->registerResource($resource_type,
        		array("smarty_resource_{$resource_type}_source",
            		"smarty_resource_{$resource_type}_timestamp",
                	"smarty_resource_{$resource_type}_secure",
                	"smarty_resource_{$resource_type}_trusted"));
        }
    } 

    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public function isExisting(Smarty_Internal_Template $_template)
    {
        if (is_integer($_template->getTemplateTimestamp())) {
            return true;
        } else {
            return false;
        } 
    } 

    /**
     * Get filepath to template source
     *
     * @note Templates loaded by this Resource type are not verified with Smarty_Security 
     * @param Smarty_Internal_Template $_template template object
     * @return string filepath to template source file
     */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    { 
        $_filepath = $_template->resource_type .':'.$_template->resource_name;
        $_template->templateUid = sha1($_filepath);
        return $_filepath;
    } 

    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return integer|boolean timestamp (epoch) the template was modified, false if resources has no timestamp
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template)
    { 
        // return timestamp
        $time_stamp = false;
        call_user_func_array($this->smarty->registered_resources[$_template->resource_type][0][1],
            array($_template->resource_name, &$time_stamp, $this->smarty));
        return is_numeric($time_stamp) ? (int)$time_stamp : $time_stamp;
    }
     
    /**
     * Get timestamp of template source by type and name
     * 
     * @param object $_template template object
     * @return int  timestamp
     */
    public function getTemplateTimestampTypeName($_resource_type, $_resource_name)
    { 
        // TODO: (rodneyrehm) getTemplateTimestampTypeName() needs an interface or something
        // return timestamp
        $time_stamp = false;
        call_user_func_array($this->smarty->registered_resources[$_resource_type][0][1],
            array($_resource_name, &$time_stamp, $this->smarty));
        return is_numeric($time_stamp) ? (int)$time_stamp : $time_stamp;
    } 

    /**
     * Load template's source by invoking the registered callback into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    { 
        // return template string
        return call_user_func_array($this->smarty->registered_resources[$_template->resource_type][0][0],
            array($_template->resource_name, &$_template->template_source, $this->smarty));
    } 

    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string path to compiled template
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    { 
        return $this->buildCompiledFilepath($_template, basename($_template->resource_name));
    } 
} 

?>