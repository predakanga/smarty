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
 * @author Rodney Rehm
 */
class Smarty_Internal_Resource_Eval extends Smarty_Resource_Recompiled {
    
    /**
     * populate Source Object with meta data from Resource
     *
     * @param Smarty_Template_Source $source source object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
    {
        $source->uid = $source->filepath = sha1($source->name);
    	$source->timestamp = false;
    	$source->exists = true;
    }

    /**
     * Load template's source from $resource_name into current template object
     * 
     * @param Smarty_Template_Source $source source object
     * @return string template source
     * @throws SmartyException if source cannot be loaded
     */
    public function getContent(Smarty_Template_Source $source)
    { 
        // return template string
        return $source->name;
    } 
    
    /**
     * Determine basename for compiled filename
     *
     * @param Smarty_Template_Source $source source object
     * @return string resource's basename
     */
    protected function getBasename(Smarty_Template_Source $source)
    {
        return '';
    }
} 
?>