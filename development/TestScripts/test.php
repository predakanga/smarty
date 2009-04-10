<?php
/**
* Test script for the {debug} tag
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('../../distribution/libs/Smarty.class.php');

$smarty = new Smarty;
$smarty->force_compile = true;
        $tpl = $this->smarty->createTemplate('test_if.tpl');
        $result = $tpl->getCompiledTemplate();


$smarty->display('test.tpl');


?>
