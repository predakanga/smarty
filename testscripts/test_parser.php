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
header( 'Content-Type: text/html; charset=UTF-8' );
function _get_time()
{
    $_mtime = microtime();
    $_mtime = explode(" ", $_mtime);
    return (double)($_mtime[1]) + (double)($_mtime[0]);
} 
error_reporting((E_ALL | E_STRICT));
require('../distribution/libs/Smarty.class.php');
ini_set('pcre.backtrack_limit', -1); 
// ini_set('asp_tags','1');
$smarty = new Smarty;
$smarty->addPluginsDir('./plugins');
$smarty2 = new Smarty();
$smarty->caching = true;
$smarty->cache_lifetime = 100000;
//$smarty->error_reporting = E_ALL | E_STRICT;
if (isset($_POST['template'])) {
    $template = str_replace("\'", "\\'", $_POST['template']); 
} else {
    $template = null;
} 
if (isset($_POST['debug']) && $_POST['debug'][0] == 1) {
	$smarty2->_parserdebug= true;
}
$smarty->assign('template', $template, true);
$smarty->display('test_parser.tpl');

$smarty2->addPluginsDir('./plugins');
//$smarty2->error_reporting = E_ALL | E_STRICT;
$smarty2->php_handling = Smarty::PHP_ALLOW;
//$smarty2->php_handling = Smarty::PHP_REMOVE;
// $smarty2->php_handling = Smarty::PHP_PASSTHRU;
// $smarty2->php_handling = Smarty::PHP_QUOTE;
//$smarty2->allow_php_tag = true;
$smarty2->debugging = !empty($_POST['debug']);
// $smarty2->auto_literal = false;
// $smarty2->loadFilter('variable','htmlspecialchars');
// $smarty2->default_modifiers = array('escape:"htmlall"','strlen');
// $smarty2->left_delimiter = '<-';
// $smarty2->right_delimiter = '->';
// $smarty2->error_unassigned = true;
//$smarty2->compile_check = false;
//$smarty2->merge_compiled_includes = true;
//$smarty2->enableSecurity();
//$smarty2->deprecation_notices = false;
$tpl = $smarty2->createTemplate ('eval:' . str_replace("\r", '', $template), null, null, $smarty2);
$start = _get_time();
$i=1;
//echo '<pre><br><br>' . htmlentities($tpl->getCompiledTemplate()) . '</pre>';
$smarty2->display($tpl);
echo '<br><br>' . (_get_time() - $start);
echo '<br>' . memory_get_peak_usage(true);


?>