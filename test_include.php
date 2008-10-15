<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->caching_lifetime = 10;

$smarty->assign('foo',1,false,true);

// example of executing a compiled template
$smarty->display('test_inc.tpl');

echo '<br>before exit';

?>
