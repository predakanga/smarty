<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->assign('t1',time());
$smarty->assign('t2',time(),false);
$smarty->display('test_nocache2.tpl');

?>
