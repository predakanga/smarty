<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_PHP extends Smarty_Internal_Base {

    public function getTemplateFilepath($template_resource)
    {
        return $this->smarty->getTemplateFilepath($template_resource);
    }

    public function getTimestamp ($_tpl_filepath)
    {
            // no time stamps for PHP templates
            return false;
    } 

    public function getTemplate($_tpl_filepath)
    { 
        // is no template source to compile
        return false;
    } 

    public function usesCompiler()
    { 
        // does not use compiler, template is PHP
        return false;
    } 

    public function isEvaluated()
    { 
        // does not use compiler
        return false;
    } 
    

} 

?>
