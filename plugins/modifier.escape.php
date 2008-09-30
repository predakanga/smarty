<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Modifier_Escape extends Smarty_Internal_PluginBase {

  public function execute($string,$params=null) {
  
    $esc_type = isset($params['esc_type']) ? $params['esc_type'] : 'html';
    $char_set = isset($params['char_set']) ? $params['char_set'] : 'UTF-8';
    
    switch ($esc_type) {
      case 'html':
        return htmlspecialchars($string, ENT_QUOTES, $char_set);

      case 'htmlall':
        return htmlentities($string, ENT_QUOTES, $char_set);

      case 'url':
        return rawurlencode($string);

      case 'urlpathinfo':
        return str_replace('%2F','/',rawurlencode($string));

      case 'quotes':
        // escape unescaped single quotes
        return preg_replace("%(?<!\\\\)'%", "\\'", $string);

      case 'hex':
        // escape every character into hex
        $return = '';
        for ($x=0; $x < strlen($string); $x++) {
            $return .= '%' . bin2hex($string[$x]);
        }
        return $return;

      case 'hexentity':
        $return = '';
        for ($x=0; $x < strlen($string); $x++) {
            $return .= '&#x' . bin2hex($string[$x]) . ';';
        }
        return $return;

      case 'decentity':
        $return = '';
        for ($x=0; $x < strlen($string); $x++) {
            $return .= '&#' . ord($string[$x]) . ';';
        }
            return $return;

      case 'javascript':
        // escape quotes and backslashes, newlines, etc.
        return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

      case 'mail':
        // safe way to display e-mail address on a web page
        return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);

      case 'nonstd':
       // escape non-standard chars, such as ms document quotes
       $_res = '';
       for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
           $_ord = ord(substr($string, $_i, 1));
           // non-standard char, escape it
           if($_ord >= 126){
               $_res .= '&#' . $_ord . ';';
           }
           else {
               $_res .= substr($string, $_i, 1);
           }
       }
       return $_res;

      default:
        return $string;
  }

}

?>