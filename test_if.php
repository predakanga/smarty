<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->cache_lifetime = 10;

$smarty->assign('values',array(1,5,60,203,2,3,4,900));

// example of executing a compiled template
$smarty->display('test_if.tpl');

?>
