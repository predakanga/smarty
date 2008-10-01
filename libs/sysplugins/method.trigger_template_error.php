<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// impelemts the $smarty-fetch methode
class Smarty_Method_Trigger_Template_Error {
    function __construct()
    { 
        // get instance of lexer;
        $this->lex = Smarty_Internal_Templatelexer::instance();
        $this->parser = Smarty_Internal_Templateparser::instance();
        $this->compiler = Smarty_Internal_Compiler::instance();
    } 

    public function execute($args)
    {
        $match = preg_split("/\n/", $this->lex->data);
        echo "<br>Syntax Error on line " . $this->lex->line . " template " . $this->compiler->_compiler_status->current_tpl_filepath . '<p style="font-family:courier">' . $match[$this->lex->line-1] . "<br>";
        echo '</p>';
        if (isset($args[0])) {
            echo $args[0];
        } else {
            foreach ($this->parser->yy_get_expected_tokens($yymajor) as $token) {
                $expect[] = $this->parser->yyTokenName[$token];
            } 
            echo 'Unexpected "' . $this->lex->value . '", expected one of: ' . implode(',', $expect);
        } 
        echo "<br>Compilation terminated";
        die();
    } 
} 

?>
