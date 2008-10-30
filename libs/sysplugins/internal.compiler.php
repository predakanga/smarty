<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Compiler extends Smarty_Internal_Base {
    // compile tag objects
    static $_tag_objects = array();

    // tag stack
    public $_tag_stack = array();
    // current template
    public $template = null;

    public function __construct()
    {
        parent::__construct(); 
        // set instance object
        self::instance($this); 

        // get required plugins
        $this->smarty->loadPlugin('Smarty_Internal_Templatelexer');
        $this->smarty->loadPlugin('Smarty_Internal_Templateparser');

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


    /* here is where the compiling takes place. Smarty
     *  tags in the templates are replaces with PHP code
     */ 
    public function compileTemplate($_template)
    {
        /* here is where the compiling takes place. Smarty
       tags in the templates are replaces with PHP code,
       then written to compiled files. */ 

        // save template object for compile class
        $this->template = $_template;
        
        // get template filepath for error messages
        $this->tpl_filepath = $_template->getTemplateFilepath(); 
        // get template
        if (($_content = $_template->getTemplateSource($_resource_name)) === false) {
            throw new SmartyException("Unable to load template {$tpl_filepath}");
        } 

        $template_header = "<?php /* Smarty version " . Smarty::$_version . ", created on " . strftime("%Y-%m-%d %H:%M:%S") . "\n";
        $template_header .= "         compiled from \"" . $this->tpl_filepath . "\" */ ?>\n"; 

        // if no content just return header
        if ($_content == '') {
            $_template->compiled_template = $template_header;
            return true;
        } 

        $this->_compiler_status->current_tpl_filepath = $tpl_filepath;
        
        //Init cacher
        $_template->cacher_object->initCacher($this); 

        // call the lexer/parser to compile the template
        $lex = new Smarty_Internal_Templatelexer($_content);
        $parser = new Smarty_Internal_Templateparser($lex);

        while ($lex->yylex()) {
            // echo "<br>Parsing  {$lex->token} Token {$lex->value} \n";
            $parser->doParse($lex->token, $lex->value);
        } 
        $parser->doParse(0, 0);

        $_template->cacher_object->closeCacher($this); 

        if (!$this->smarty->compile_error) {
            // return compiled template
            $_template->compiled_template =  $template_header  . $parser->retvalue;
            return true;
        } else {
            return false;
        } 
    } 

    /*
    *  Compile Tag
    *
    *  This is a call back from the lexer/parser
    *  If required it executes the required compile plugin for the Smarty tag
    *
    */
    public function compileTag($tag, $args)
    { 
        // $args contains the attributes parsed and compiled by the lexer/parser

        // assume that tag does compile into code, but creates no HTML output 
        $this->has_code = true; 
        $this->has_output = false; 
        // compile the smarty tag
        if (!($_output = $this->$tag($args)) === false) {
            // did we get compiled code
            if ($this->has_code) {
                // Does it create output?
                if ($this->has_output) {
                  $_output .= "\n";
                } 
                // just for debugging
                if ($this->smarty->internal_debugging) {
                    echo "<br>compiled tag '" . htmlentities($_output) . "'<br>";
                } 

                return $_output;
            } 
            return '';
        } else {
            $this->trigger_template_error ("missing compiler module for tag \"" . $tag . "\"");
        } 
    } 

    /**
    * Takes unknown class methods and lazy loads plugin files for them
    * class name format:  Smarty_Compile_TagName or Smarty_Internal_Compile_TagName
    * plugin filename format: compile.tagname.php  or internal.compile_tagname.php
    * 
    */
    public function __call($name, $args)
    {
        $ucname = ucfirst($name);
        $classes = array("Smarty_Internal_Compile_{$name}", "Smarty_Compile_{$name}");

        foreach ($classes as $class_name) {
            // re-use object if already instantiated
            if (!isset(self::$_tag_objects[$name])) {
                if ($this->smarty->loadPlugin($class_name)) {
                    // use plugin if found
                    self::$_tag_objects[$name] = new $class_name;
                    return call_user_func_array(array(self::$_tag_objects[$name], 'compile'), $args);
                } 
            } else {
                return call_user_func_array(array(self::$_tag_objects[$name], 'compile'), $args);
            } 
        } 
        return false;
    } 

    public function trigger_template_error($args=null)
    {
        $this->lex = Smarty_Internal_Templatelexer::instance();
        $this->parser = Smarty_Internal_Templateparser::instance();

        $match = preg_split("/\n/", $this->lex->data);
        echo '<br>Syntax Error on line ' . $this->lex->line . ' in template "' . $this->tpl_filepath . '"<p style="font-family:courier">' . $match[$this->lex->line-1] . "<br>";

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
