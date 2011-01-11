<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty count_characters modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     count_characteres<br>
 * Purpose:  count the number of characters in a text
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.count.characters.php count_characters (Smarty online manual)
 * @author Uwe Tews 
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifiercompiler_count_characters($params, $compiler)
{
    // mb_ functions available?
    if (SMARTY_MBSTRING /* ^phpunit */&&empty($_SERVER['SMARTY_PHPUNIT_DISABLE_MBSTRING'])/* phpunit$ */) {
        $return = 'mb_strlen(' . $params[0] . ', SMARTY_RESOURCE_CHAR_SET)';
        if (!isset($params[1]) || $params[1] != 'true') {
            $return = '(' . $return . ' - preg_match_all(\'/\s/u\',' . $params[0] . ', $tmp))';
        } 
        return $return;
    }
    // count also spaces?
    if (isset($params[1]) && $params[1] == 'true') {
       return 'strlen(' . $params[0] . ')';
    } 
    return 'preg_match_all(\'/[^\s]/\',' . $params[0] . ', $tmp)';
}

?>