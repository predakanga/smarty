<?php
// Create Parser
passthru('C:\wamp\bin\php\php5.2.9-1\php ./ParserGenerator/cli.php smarty_internal_configfileparser.y');

// Create Lexer
require_once './LexerGenerator.php';
$lex = new PHP_LexerGenerator('smarty_internal_configfilelexer.plex');
$contents = file_get_contents('smarty_internal_configfilelexer.php');
file_put_contents('smarty_internal_configfilelexer.php', $contents.'?>');
$contents = file_get_contents('smarty_internal_configfileparser.php');
$contents = '<?php
/**
* Smarty Internal Plugin Configfileparser
*
* This is the config file parser.
* It is generated from the internal.configfileparser.y file
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
'.substr($contents,6);
file_put_contents('smarty_internal_configfileparser.php', $contents.'?>');
copy('smarty_internal_configfilelexer.php','../../distribution/libs/sysplugins/smarty_internal_configfilelexer.php');
copy('smarty_internal_configfileparser.php','../../distribution/libs/sysplugins/smarty_internal_configfileparser.php');

?>
