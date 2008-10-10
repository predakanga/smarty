<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_File extends Smarty_Internal_Base {

    public function getTemplateFilepath($_template)
    {
        return $_template->buildTemplateFilepath ();
    }

    public function getTimestamp($_template)
    {
        return filemtime($_template->template_filepath);
    } 

    public function getContents($_template)
    { 
        // read template file
        return file_get_contents($_template->template_filepath);
    }
    
    public function usesCompiler()
    { 
        // template has tags, uses compiler
        return true;
    }
    
    public function isEvaluated()
    { 
        // save the compiled file to disk, do not eval
        return false;
    } 
    
     
} 

?>
