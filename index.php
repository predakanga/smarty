<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty();
$smarty->force_compile = true;

$smarty->assign('foo',array('name'=>'A & B'));

//$smarty->display('index.tpl');
//$smarty->display('index_view.php');
$smarty->display('index_view2.php');

?>
