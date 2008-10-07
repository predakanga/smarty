<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_TPL extends Smarty_Internal_DisplayBase {
    public function get_timestamp ($tpl, $_tpl_filepath)
    {
        if (file_exists($_tpl_filepath)) {
            return filemtime($_tpl_filepath);
        } else {
            return false;
        } 
    } 

    public function get_template($tpl, $_tpl_filepath)
    { 
        // read template file
        return file_get_contents($_tpl_filepath);
    } 
} 

?>
