<?php
/**
* Test script for nocache variables
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->assign('t1',time());
$smarty->assign('t2',time(),true);
$smarty->display('test_nocache2.tpl');

?>
