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
    for ($i = 1, $cnt = count($params); $i < $cnt; $i++) {
        $output = preg_replace('/\$_smarty_tpl->tpl_vars->([0-9]*[a-zA-Z_]\w*)/','$_smarty_tpl->getVariable(\'\1\', null, true, false)',$output);
        $output = '(($tmp = @' . $output . ')===null||$tmp===\'\' ? ' . $params[$i] . ' : $tmp)';
    }
    return $output;
}

?>