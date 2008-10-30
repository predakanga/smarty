<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty();
$smarty->force_compile = false;
$smarty->caching = false;
$smarty->caching_lifetime = 10;

$smarty->assign('foo',array('a','b','c'));

$smarty->setDefaultResource = 'php';
//$smarty->display('index.tpl');
$smarty->display('index_view.php');

?>
