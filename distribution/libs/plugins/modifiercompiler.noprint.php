<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty noprint modifier plugin
 *
 * Type:     modifier<br>
 * Name:     noprint<br>
 * Purpose:  return an empty string
 *
 * @link http://www.smarty.net/docs/en/language.modifier.noprint.tpl
 *          regex_replace (Smarty online manual)
 * @author   Uwe Tews
 *
 * @param string $input input string to be ignored
 * @return string with compiled code
 */
// NOTE: The parser does pass all parameter as strings which could be directly inserted into the compiled code string
function smarty_modifiercompiler_noprint($input)
{
    return "''";
}
?>