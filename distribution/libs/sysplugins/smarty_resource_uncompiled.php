<?php

/**
 * Smarty Resource Plugin
 * 
 * Base implementation for resource plugins that don't use the compiler
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource_Uncompiled extends Smarty_Resource {
    /**
     * Render and output the template (without using the compiler)
     *
     * @param Smarty_Template_Source $source source object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     * @throws SmartyException on failure
     */
    public abstract function renderUncompiled(Smarty_Template_Source $source, Smarty_Internal_Template $_template);
    
    /**
     * populate Compiled Object with compiled filepath
     *
     * @param Smarty_Template_Compiled $compiled compiled object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populateCompiledFilepath(Smarty_Template_Compiled $compiled, Smarty_Internal_Template $_template)
    {
        $compiled->filepath = false;
        $compiled->timestamp = false;
        $compiled->exists = false;
    }
}

?>