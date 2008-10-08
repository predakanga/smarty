<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_TPL extends Smarty_Internal_Base {
    public function get_timestamp ($tpl, $_tpl_filepath)
    {
        return file_exists($_tpl_filepath) ? filemtime($_tpl_filepath) : false;
    } 

    public function get_template($tpl, $_tpl_filepath)
    { 
        // read template file
        return file_exists($_tpl_filepath) ? file_get_contents($_tpl_filepath) : false;
    } 
} 

?>
