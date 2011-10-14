<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty default modifier plugin
 *
 * Type:     modifier<br>
 * Name:     default<br>
 * Purpose:  designate default value for empty variables
 *
 * @link http://www.smarty.net/manual/en/language.modifier.default.php default (Smarty online manual)
 * @author Uwe Tews
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifiercompiler_default ($params, $compiler)
{
    $output = $params[0];
    if (!isset($params[1])) {
        $params[1] = "''";
    }

    array_shift($params);
    foreach ($params as $param) {
        $output = '(($tmp = @' . $output . ')===null||$tmp===\'\' ? ' . $param . ' : $tmp)';
    }
    $output = preg_replace(array('/\$_smarty_tpl->tpl_vars->([0-9]*[a-zA-Z_]\w*)/','/\$_smarty_tpl->config_vars->([0-9]*[a-zA-Z_]\w*)/'),array('$_smarty_tpl->getVariable(\'\1\', null, true, false)','$_smarty_tpl->getConfigVariable(\'\1\', null, true, false)'),$output);
    return $output;
}
?>