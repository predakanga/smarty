<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->caching = true;
$smarty->cache_lifetime = 10;

$smarty->assign('foo','a & b');
$smarty->assign('aa',array(5,4,3,2,1,0));
$smarty->assign('t1',time());
$smarty->assign('t2',time());

// example of executing a PHP template (non-compiled)
//$smarty->display('index_view.php');
$page = $smarty->fetch('index_view.php');
echo $page;
echo "<br><br>";

// example of executing a compiled template
$smarty->display('test.tpl');
$page = $smarty->fetch('test.tpl');
echo $page;

?>
