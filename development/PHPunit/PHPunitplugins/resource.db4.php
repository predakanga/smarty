<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db3.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */
class Smarty_Resource_Db4 extends Smarty_Resource {
    public function isExisting(Smarty_Internal_Template $template)
    {
        return true;
    }
    
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    {
        return 'db4:';
    }
    
    public function getTemplateTimestamp(Smarty_Internal_Template $_template, $resource_name=null)
    {
        return 0;
    }
    
    public function getTemplateSource(Smarty_Internal_Template $_template)
    {
        return $_template->template_source = '{$x="hello world"}{$x}';
    }
}

?>
