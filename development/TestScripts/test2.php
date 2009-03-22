<?php
/**
* Test script for the {debug} tag
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

$smarty = new Smarty;
$smarty->left_delimiter='{';
$smarty->right_delimiter='}';
$smarty->force_compile = true;


$smarty->display('test2.tpl');


?>
