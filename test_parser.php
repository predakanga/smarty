<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = -1;

$template = stripslashes($_POST['template']);

$smarty->assign('template', $template, true);

$smarty->display('test_parser.tpl');

if ($template != "") {
    $tpl = $smarty->createTemplate ('String:' . $template, null);
    $smarty->display($tpl);
    echo '<pre><br><br>' . htmlentities($tpl->getCompiledTemplate()) . '</pre>';
} 

?>
