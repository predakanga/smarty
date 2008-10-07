<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_TPL extends Smarty_Internal_DisplayBase {
    public function compile_check ($tpl, $_tpl_filepath, $_compiled_filepath) 
    {

        if (!file_exists($_tpl_filepath)) {
                throw new SmartyException ("Template file ".$_tpl_filepath." does not exist");
         }

         // check if we need a recompile
         return (!file_exists($_compiled_filepath) 
                  || filemtime($_compiled_filepath) !== filemtime($_tpl_filepath) 
                  || $this->smarty->force_compile);
    }
    
    public function get_template($_tpl_filepath) 
    {
            // read template file
            return  file_get_contents($_tpl_filepath);
    }

} 

?>
