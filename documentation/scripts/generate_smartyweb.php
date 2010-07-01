<?php 
/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2004 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.0 of the PHP license,       |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_0.txt.                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Authors:    Nuno Lopes <nlopess@php.net>                             |
  +----------------------------------------------------------------------+
  | Small hack to generate the manual for the web                        |
  +----------------------------------------------------------------------+

  $Id: generate_web.php 2738 2007-09-17 11:19:26Z messju $
*/

ini_set('pcre.backtrack_limit', 150000); // Default is 100000, available since PHP 5.2.0 
set_time_limit(0);

$search = array(
    '/\{/',
    '/\}/',
    '/%%%tmpldelim%%%/',
    '/%%%tmprdelim%%%/',
    '/HREF="\/?(.*)\.tpl(.*)"/U',
    '/(<HTML.*<META.*HTTP-EQUIV="Content-type".*charset=(.*?)".*?<BODY[^>]+>)/mSs',
    '/(<\/BODY\s*><\/HTML\s*>)/mS'
);

$replace = array(
    '%%%tmpldelim%%%',
    '%%%tmprdelim%%%',
    '{ldelim}',
    '{rdelim}',
    'HREF="${1}${2}"',
    '{extends file="layout.tpl"}
{block name="main_content"}',
    '{/block}'
);

if ($dir = opendir('smartyweb')) {
    echo "Processing the manual...\n";

    while (false !== ($file = readdir($dir))) {
        if(substr($file, -4) == '.tpl') {

            $text = file_get_contents('smartyweb/' . $file);

            $text = preg_replace($search, $replace, $text);

            $handler = fopen('smartyweb/' . $file, 'w+');
            fputs($handler, $text);
            fclose($handler);
        }
    }

   closedir($dir); 
} else {
    die('Could not open the specified dir!');
}

?>
