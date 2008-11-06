<?php
/**
* Test script for PHP template
* @author Monte Ohrt <monte at ohrt dot com> 
* @package SmartyTestScripts
*/
require('./libs/Smarty.class.php');
ini_set('short_open_tag','1');
$smarty = new Smarty();
$smarty->force_compile = false;
$smarty->caching = false;
$smarty->caching_lifetime = 10;

$smarty->assign('foo','bar');

$smarty->display('php:index_view.php');

?>
