<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = false;
$smarty->caching_lifetime = 10;

$smarty->assign('foo',1);

// example of executing a compiled template
$smarty->display('test_inc.tpl');

?>
