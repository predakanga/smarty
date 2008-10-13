<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = -1;

$template = stripslashes($_POST['template']);

$smarty->internal_debugging = false;
$smarty->assign('template', $template,false);

$smarty->display('test_parser.tpl');

//$smarty->internal_debugging = true;
if ($template != "") {
    $smarty->display('String:' . $template);
} 

?>
