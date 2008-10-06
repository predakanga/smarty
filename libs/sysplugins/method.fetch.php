<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

//  impelemts the $smarty-fetch methode
class Smarty_Method_Fetch extends Smarty_Internal_PluginBase {
    public function execute($args)
    {
        ob_start();
        $this->smarty->display($args[0]);
        $_content = ob_get_contents();
        ob_clean();

        return $_content;
    } 
} 

?>
