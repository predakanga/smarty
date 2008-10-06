<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// impelemts the $smarty-fetch methode
class Smarty_Method_Trigger_Fatal_Error extends Smarty_Internal_PluginBase {
    public function execute($args)
    {
        echo "<br>" . $args[0] . "<br>Smarty terminated<br>";
        die();
    } 
} 

?>
