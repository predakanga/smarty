<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->assign('foo','a & b');

// example of executing a PHP template (non-compiled)
$smarty->display('index_view.php');

// example of executing a compiled template
$smarty->display('index.tpl');

?>
