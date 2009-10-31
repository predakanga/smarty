<?php
// Create Parser
passthru('C:\wamp\bin\php\php5.2.9-1\php ./ParserGenerator/cli.php smarty_internal_configfileparser.y');

// Create Lexer
require_once './LexerGenerator.php';
$lex = new PHP_LexerGenerator('smarty_internal_configfilelexer.plex');
copy('smarty_internal_configfilelexer.php','../../distribution/libs/sysplugins/smarty_internal_configfilelexer.php');
copy('smarty_internal_configfileparser.php','../../distribution/libs/sysplugins/smarty_internal_configfileparser.php');

?>
