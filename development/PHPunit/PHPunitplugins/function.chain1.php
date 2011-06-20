<?php
$_smarty->loadPlugin('smarty_function_chain2');
function smarty_function_chain1($params,$smarty){
    return smarty_function_chain2($params,$smarty);
}
?>
