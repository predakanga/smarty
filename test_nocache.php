<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;

$smarty->display('test_nocache.tpl');

?>
