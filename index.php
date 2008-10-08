<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty();
$smarty->force_compile = true;

$smarty->assign('foo',array('name'=>'A & B'));

$smarty->setDefaultResource = 'php';
//$smarty->display('index.tpl');
$smarty->display('index_view.php');

?>
