<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Compiler extends Smarty_Internal_Base {

    // tag stack
    public $_tag_stack = array();

    public function __construct()
    {
        parent::__construct(); 
        // set instance object
        self::instance($this); 
        // flag for nochache sections
        $this->_compiler_status->nocache = false; 
        $this->_compiler_status->tag_nocache = false; 
        // current template file
        $this->_compiler_status->current_tpl_filepath = "";
    } 

    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    } 
    // public function compile($_content, $tpl_filepath)
    public function compile($_template)
    {
        /* here is where the compiling takes place. Smarty
       tags in the templates are replaces with PHP code,
       then written to compiled files. */ 

        // save template object for compile class

        $this->template = $_template;
        
        // get template filepath for error messages
        $tpl_filepath = $_template->getTemplateFilepath(); 
        // get template
        if (($_content = $_template->getTemplateSource($_resource_name)) === false) {
            throw new SmartyException("Unable to load template {$tpl_filepath}");
        } 

        $template_header = "<?php /* Smarty version " . $this->smarty->_version . ", created on " . strftime("%Y-%m-%d %H:%M:%S") . "\n";
        $template_header .= "         compiled from \"" . $tpl_filepath . "\" */ ?>\n"; 

        // if no content just return header
        if ($_content == '') {
            $_template->compiled_template = $template_header;
            return true;
        } 

        $this->_compiler_status->current_tpl_filepath = $tpl_filepath; 
        // call the lexer/parser to compile the template
        $this->smarty->loadPlugin('Smarty_Internal_Templatelexer');
        $lex = new Smarty_Internal_Templatelexer($_content);
        $this->smarty->loadPlugin('Smarty_Internal_Templateparser');
        $parser = new Smarty_Internal_Templateparser($lex,$_template->tpl_vars);

        while ($lex->yylex()) {
            // echo "Parsing  {$lex->token} Token {$lex->value} \n";
            $parser->doParse($lex->token, $lex->value);
        } 
        $parser->doParse(0, 0);

        if (!$this->smarty->compile_error) {
            // return compiled template
            $_template->compiled_template =  $template_header  . $parser->retvalue;
            return true;
        } else {
            return false;
        } 
    } 

    public function trigger_template_error($args=null)
    {
        $this->lex = Smarty_Internal_Templatelexer::instance();
        $this->parser = Smarty_Internal_Templateparser::instance();

        $match = preg_split("/\n/", $this->lex->data);
        echo "<br>Syntax Error on line " . $this->lex->line . " template " . $this->_compiler_status->current_tpl_filepath . '<p style="font-family:courier">' . $match[$this->lex->line-1] . "<br>";

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

        if (isset($args)) {
            // individual error message
            echo $args;
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
