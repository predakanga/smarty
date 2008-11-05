<?php
// Create Parser
passthru('C:\wamp\bin\php\php5.2.6\php ./ParserGenerator/cli.php internal.templateparser.y');

// Create Lexer
require_once './LexerGenerator.php';
$lex = new PHP_LexerGenerator('internal.templatelexer.plex');
copy('internal.templatelexer.php','../sysplugins/internal.templatelexer.php');
copy('internal.templateparser.php','../sysplugins/internal.templateparser.php');

?>
