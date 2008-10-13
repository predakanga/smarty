<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = false;
$smarty->caching_lifetime = -1;

$template = $_POST['template'];

$smarty->internal_debugging = false;
$smarty->assign('template', $template,false);

$smarty->assign('foo','bar');
$smarty->assign('baz',array(1,2,3));

$smarty->display('test_parser.tpl');

//$smarty->internal_debugging = true;
if ($template != "") {
    $smarty->display('String:' . $template);
} 

?>
