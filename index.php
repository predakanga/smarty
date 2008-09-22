<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty('foo');

$smarty->assign('foo','a & b');
echo "foo is: " . $smarty->tpl_vars['foo'] . "\n";

$smarty = new Smarty('bar');

$smarty->assign('foo','c & d');
echo "foo is: " . $smarty->tpl_vars['foo'] . "\n";

$smarty = Smarty::instance('foo');
echo "foo is: " . $smarty->tpl_vars['foo'] . "\n";

$smarty = Smarty::instance('bar');
echo "foo is: " . $smarty->tpl_vars['foo'] . "\n";

?>
