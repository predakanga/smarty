<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Compiler extends Smarty_Internal_Base {
    // loaded compuler classes
    public $_compiler_class = array(); 
    // tag stack
    public $_tag_stack = array();

    public function __construct()
    {
        parent::__construct(); 
        // set instance object
        self::instance($this); 
        // flag for nochache sections
        $this->_compiler_status->nocache = false; 
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

    public function compile($_content, $tpl_filepath)
    {
        /* here is where the compiling takes place. Smarty
       tags in the templates are replaces with PHP code,
       then written to compiled files. */ 

        $template_header = "<?php /* Smarty version ".$this->smarty->_version.", created on ".strftime("%Y-%m-%d %H:%M:%S")."\n";
        $template_header .= "         compiled from ".strtr(urlencode($tpl_filepath), array('%2F'=>'/', '%3A'=>':'))." */ ?>\n";
       
       // if no content just return
       if ($_content == '') return $template_header;
       
        $this->_compiler_status->current_tpl_filepath = $tpl_filepath;

        // call the lexer/parser to compile the template
        $this->smarty->loadPlugin('Smarty_Internal_Templatelexer');
        $lex = new Smarty_Internal_Templatelexer($_content);
        $this->smarty->loadPlugin('Smarty_Internal_Templateparser');
        $parser = new Smarty_Internal_Templateparser($lex);

        while ($lex->yylex()) {
            // echo "Parsing  {$lex->token} Token {$lex->value} \n";
            $parser->doParse($lex->token, $lex->value);
        } 
        $parser->doParse(0, 0); 

        if (!$this->smarty->compile_error) {
            // return compiled template
            return $template_header."<?php \$_smarty = Smarty::instance();?>\n" . $parser->retvalue;
        } else {
            return false;
        } 
    } 
} 

?>
