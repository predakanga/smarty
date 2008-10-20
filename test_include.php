<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->caching_lifetime = 10;

// standard
$smarty->assign('foo',1);
$smarty->display('test_inc.tpl');

// data object
//$data = new Smarty_Data;
//$data->assign('foo',1,false,true);
//$smarty->display('test_inc.tpl',null,null,$data);

// template object
//$myvars = array('foo' => 1);
//$template = new Smarty_Template ('test_inc.tpl',$myvars);
//$template->assign('foo',1);
//$smarty->display($template);


?>
