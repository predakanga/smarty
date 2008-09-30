<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->cache_lifetime = 10;

$template= stripslashes($_POST['template']);

$smarty->internal_debugging = false;
$smarty->assign('template',$template);
$smarty->display('test_parser.tpl');


//$smarty->internal_debugging = true;
$smarty->display('String:'.$template);

?>
