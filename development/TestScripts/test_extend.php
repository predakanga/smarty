<?php
/**
* Test script for config files
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

$smarty = new Smarty;
$smarty->force_compile = true;
$smarty->caching = true;
$smarty->caching_lifetime = 60;

//$smarty->config_load('test.conf','setup');

$smarty->display('test_replace3.tpl');


?>
