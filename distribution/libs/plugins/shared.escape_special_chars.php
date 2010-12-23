<?php
/**
 * Smarty shared plugin
 *
 * @package Smarty
 * @subpackage PluginsShared
 */

/**
 * escape_special_chars common function
 *
 * Function: smarty_function_escape_special_chars<br>
 * Purpose:  used by other smarty functions to escape
 *           special chars except for already escaped ones
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_function_escape_special_chars($string)
{
	// NOTE: optimization through conditinal function definition possible
    if(!is_array($string)) {
		if (version_compare( PHP_VERSION, '5.2.3', '>=')) {
	        // Available since 5.2.3 - charset doesn't really matter here
			$string = htmlspecialchars( $string, ENT_COMPAT, 'UTF-8', false );
		} else {
	        $string = preg_replace('!&(#?\w+);!', '%%%SMARTY_START%%%\\1%%%SMARTY_END%%%', $string);
	        $string = htmlspecialchars($string);
	        $string = str_replace(array('%%%SMARTY_START%%%','%%%SMARTY_END%%%'), array('&',';'), $string);
		}
    }
    return $string;
}

?>