<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->caching_lifetime = 10;
$smarty->assign('foo',1,false,true);
//var_dump($smarty->tpl_vars);
//$data = new Smarty_Data;
//$data->assign('foo',1,false,true);
//var_dump($data);

// example of executing a compiled template
$smarty->display('test_inc.tpl');
//$smarty->display('test_inc.tpl',null,null,$data);

echo '<br>before exit';

?>
