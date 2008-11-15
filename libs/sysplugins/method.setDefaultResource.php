<?php

/**
* Smarty plugin
*
* @ignore 
* @package Smarty
* @subpackage plugins
*/

//  impelemts the $smarty-setDefaultResource methode
class Smarty_Method_setDefaultResource extends Smarty_Internal_PluginBase {
    public function execute($args)
    {
        $this->smarty->default_resource_type = $args[0];
    } 
} 

?>
