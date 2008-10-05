<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// impelemts error output during template parsing
// written by Uwe Tews

class Smarty_Method_Trigger_Template_Error {
    function __construct()
    { 
        // get instance of lexer;
        $this->lex = Smarty_Internal_Templatelexer::instance();
        $this->parser = Smarty_Internal_Templateparser::instance();
        $this->compiler = Smarty_Internal_Compiler::instance();
        $this->smarty = Smarty::instance();
    } 

    public function execute($args)
    {
        $match = preg_split("/\n/", $this->lex->data);
        echo "<br>Syntax Error on line " . $this->lex->line . " template " . $this->compiler->_compiler_status->current_tpl_filepath . '<p style="font-family:courier">' . $match[$this->lex->line-1] . "<br>";

        if (false) { // work in progress
            // find position in this line
            $counter = $this->lex->counter;
            for ($i = 0; $i < $this->lex->line-1; $i++) {
                $counter -= strlen($match[$i]);
            } 
            $counter -= ($this->lex->line-1) * 2;
            echo $counter;
            for ($i = 0; $i < $counter-1;$i++) {
                echo "&nbsp";
            } 
        } 
        echo '</p>';

        if (isset($args[0])) {
            // individual error message
            echo $args[0];
        } else {
            // exspected token from parser
            foreach ($this->parser->yy_get_expected_tokens($yymajor) as $token) {
                $exp_token = $this->parser->yyTokenName[$token];
                if (isset($this->lex->smarty_token_names[$exp_token])) {
                    // token type from lexer
                    $expect[] = '"'.$this->lex->smarty_token_names[$exp_token].'"';
                } else {
                    // otherwise internal token name
                    $expect[] = $this->parser->yyTokenName[$token];
                } 
            } 
            echo 'Unexpected "' . $this->lex->value . '", expected one of: ' . implode(' , ', $expect);
        } 

        echo "<br>";

        $this->smarty->compile_error = true;
    } 
} 

?>
