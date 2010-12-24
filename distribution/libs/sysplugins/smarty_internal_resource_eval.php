<?php

/**
 * Smarty Internal Plugin Resource Eval
 * 
 * Implements the strings as resource for Smarty template
 * 
 * @note unlike string-resources the compiled state of eval-resources is NOT saved for subsequent access
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_Eval extends Smarty_Resource {
    /**
	 * eval-resources are evaluated by default in a sense that their compiled state cannot be read from disk
	 * @var boolean
	 */
    public $isEvaluated = true;

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
     * @return string always 'eval:' as template source is not a file
     */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    { 
        // no filepath for evaluated strings
        // return "string" for compiler error messages
        return 'eval:';
    } 

    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_name name of the resource to get modification time of, if null, $_template->resource_name is used
     * @return boolean false as string resources have no timestamp
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template, $resource_name=null)
    { 
        // evaluated strings must always be compiled and have no timestamp
        return false;
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

} 
?>