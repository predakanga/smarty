<?php
$_smarty->loadPlugin('smarty_function_chain3');
function smarty_function_chain2($params,$smarty){
    return smarty_function_chain3($params,$smarty);
}
?>
