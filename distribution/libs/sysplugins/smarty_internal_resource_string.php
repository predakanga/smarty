<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Internal Plugin Resource String
 * 
 * Implements the strings as resource for Smarty template
 * 
 * @note unlike eval-resources the compiled state of string-resources is saved for subsequent access
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_String extends Smarty_Resource {

    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean always true as template source is not a file
     */
    public function isExisting(Smarty_Internal_Template $template)
    {
        return true;
    } 

    /**
     * Get filepath to template source
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string always 'string:' as template source is not a file
     */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    { 
        $_template->templateUid = sha1($_template->resource_name);
        // no filepath for strings
        // return "string" for compiler error messages
        return 'string:';
    } 

    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return integer|boolean 0 if the template has been evaluated, false otherwise
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template)
    { 
        if ($this->isEvaluated) {
        	//must always be compiled and have no timestamp
        	return false;
        } else {
        	return 0;
        }
    } 

    /**
     * Get timestamp of template source by type and name
     * 
     * @param object $_template template object
     * @return int  timestamp (always 0)
     */
    public function getTemplateTimestampTypeName($_resource_type, $_resource_name)
    { 
        // TODO: (rodneyrehm) getTemplateTimestampTypeName() needs an interface or something
        // return timestamp 0
        return 0;
    } 

    /**
     * Load template's source from $resource_name into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    { 
        // return template string
        $_template->template_source = $_template->resource_name;
        return true;
    } 

    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string path to compiled template
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return $this->buildCompiledFilepath($_template, '');
    } 
} 

?>