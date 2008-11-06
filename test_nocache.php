<?php
/**
* Test script for nocache sections
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->assign('a',array(1,2,3,4,5));

$smarty->display('test_nocache.tpl');

?>
