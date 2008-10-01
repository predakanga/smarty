<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Compiler extends Smarty_Internal_PluginBase {
    // loaded compuler classes
    public $_compiler_class = array(); 
    // compiler status stack
    public $_compiler_status_stack = array(); 
    // tag stack
    public $_tag_stack = array();

    public function __construct()
    { 
        // set instance object
        self::instance($this); 
        // set smarty object
        $this->smarty = Smarty::instance(); 
        // flag for nochache sections
        $this->_compiler_status->nocache = false; 
        // current template file
        $this->_compiler_status->current_tpl_filepath = ""; 
        // current compiled template file
        $this->_compiler_status->current_compiled_path = ""; 
        // output buffe
        $this->_compiler_status->output = "";
    } 

    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    } 

    public function compile($_content, $tpl_filepath, $compiled_path)
    {
        /* here is where the compiling takes place. Smarty
       tags in the templates are replaces with PHP code,
       then written to compiled files. For now, we just
       copy the template to the compiled file. */ 

        // save compiler state when it's called recursively when pocessing {include} tags
        array_push($this->_compiler_status_stack, $this->_compiler_status);

        $this->_compiler_status->current_tpl_filepath = $tpl_filepath;
        $this->_compiler_status->current_line = 0;
        $this->_compiler_status->current_compiled_path = $compiled_path;
        $this->_compiler_status->output = ""; 

        // call the lexer/parser to compile the template
        $lex = new Smarty_Internal_Templatelexer($_content);
        $parser = new Smarty_Internal_Templateparser($lex);
        while ($lex->yylex()) {
            // echo "Parsing  {$lex->token} Token {$lex->value} \n";
            $parser->doParse($lex->token, $lex->value);
        } 
        $parser->doParse(0, 0); 
        // now we are done with the lexer / parser. Get code and get rid of extra charcters
        $compiled_tag = substr($parser->retvalue, 0, -1);

        if ($this->_compiler_status->nocache && $this->smarty->caching && $this->smarty->cache_lifetime != 0) {
            // If we have a ncocache section and caching enabled make the compiled template to inject the compiled code into the cache file
            $this->_compiler_status->output .= "<?php echo '$compiled_tag';?>\n";
        } else {
            // add compiled code of tag to output
            if ($this->smarty->internal_debugging) {
                // echo "<br>compiled tag '".$compiled_tag."'<br>";
            } 
            $this->_compiler_status->output .= $compiled_tag;
        } 

        if ($this->smarty->internal_debugging) {
            echo "<br>compiled code '" . $this->_compiler_status->output . "'<br>";
        } 

        // write compiled template file
        return file_put_contents($compiled_path, $this->_compiler_status->output); 

        // restore last compiler status
        $this->_compiler_status = array_pop($this->_compiler_status_stack);
    } 
} 

?>
