<?php
/**
* Test script for the {insert} tag
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('../../distribution/libs/Smarty.class.php');

$smarty = new Smarty;

function insert_test($params) {
      var_dump($params);
      return 'insert function called';
}

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->display('test_insert.tpl');


?>
