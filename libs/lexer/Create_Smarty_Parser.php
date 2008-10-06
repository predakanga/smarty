<?php
@copy('internal.templatelexer.php','internal.templatelexer.php.bak');
@copy('internal.templateparser.php','internal.templateparser.php.bak');
// Create Parser
passthru('C:/wamp/php/php ./ParserGenerator/cli.php internal.templateparser.y');

// Create Lexer
require_once './LexerGenerator.php';
$lex = new PHP_LexerGenerator('internal.templatelexer.plex');
copy('internal.templatelexer.php','../sysplugins/internal.templatelexer.php');
copy('internal.templateparser.php','../sysplugins/internal.templateparser.php');

?>
