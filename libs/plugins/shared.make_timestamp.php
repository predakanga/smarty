<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Function: smarty_make_timestamp<br>
 * Purpose:  used by other smarty functions to make a timestamp
 *           from a string.
 * @param string
 * @return string
 */
function smarty_make_timestamp($string)
{
    if(empty($string)) {
        // now
        $time = -1;

    } elseif (preg_match('/^\d{14}$/', $string)) {
        // is mysql timestamp format of YYYYMMDDHHMMSS?
        $time = mktime(substr($string,8,2),substr($string,10,2),substr($string,12,2),
               substr($string,4,2),substr($string,6,2),substr($string,0,4));

        return $time;

    } else {
        $time = strtotime($string);
        if (is_numeric($time) && $time != -1)
            return $time;
    }

    // couldn't recognize it, try to return a time
    $time = (int) $string;
    if ($time > 0)
        return $time;
    else
        return time();
}

/* vim: set expandtab: */

?>
