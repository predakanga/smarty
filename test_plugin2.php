<?php
 /**
* Test script for the function plugin tag
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

function plugintest($params, &$smarty)
{
    return "plugin test called $params[foo]";
} 

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->register_function('plugintest','plugintest');
$smarty->display('string:{plugintest foo=bar}');


?>
