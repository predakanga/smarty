<?php

/**
 * Smarty Resource Plugin
 * 
 * Base implementation for resource plugins that don't compile cache
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource_Recompiled extends Smarty_Resource {
    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean always false as recompiling resources are not saved to compile cache
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return false;
    }
}

?>