<?php
/**
* Test script for the Smarty compiler
* 
* It displays a form in which a template source code can be entered.
* The template source will be compiled, rendered and the result is displayed.
* The compiled code is displayed as well
* 
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('../../distribution/libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = false;
$smarty->caching_lifetime = -1;
//$smarty->enableSecurity();
$smarty->security_policy->php_handling = SMARTY_PHP_QUOTE;
if (isset($_POST['template'])) {
//var_dump($_POST['template']);
    $template = str_replace("\'","\\'",$_POST['template']); 
    // $template = stripslashes($_POST['template']);
//    var_dump($template);
} else {
    $template = null;
} 
$smarty->assign('template', $_POST['template'], true);

$smarty->display('test_parser.tpl');

if ($template != "") {
    $tpl = $smarty->createTemplate ('String:' . $template, null);
    $smarty->display($tpl);
    echo '<pre><br><br>' . htmlentities($tpl->getCompiledTemplate()) . '</pre>';
} 

?>
