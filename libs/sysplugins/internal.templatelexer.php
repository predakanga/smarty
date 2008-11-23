<?php
/**
* Smarty Internal Plugin Templatelexer
*
* This is the lexer to break the template source into tokens 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Templatelexer
*/
class Smarty_Internal_Templatelexer
{

    public $data;
    public $counter;
    public $token;
    public $value;
    public $node;
    public $line;
    private $state = 1;
    public $smarty_token_names = array (		// Text for parser error messages
    				'SI_QSTR' => 'string',
    				'LDEL'		=> '{',
    				'RDEL'		=> '}',
    				'IDENTITY'	=> '===',
    				'EQUALS'	=> '==',
    				'NOTEQUALS'	=> '!=',
    				'GREATEREQUAL' => '(>=,GE)',
    				'LESSEQUAL' => '(<=,LE)',
    				'GREATERTHAN' => '(>,GT)',
    				'LESSTHAN' => '(<,LT)',
    				'NOT'			=> '(!,NOT)',
    				'LAND'		=> '(&&,AND)',
    				'LOR'			=> '(||,OR)',
    				'OPENP'		=> '(',
    				'CLOSEP'	=> ')',
    				'OPENB'		=> '[',
    				'CLOSEB'	=> ']',
    				'PTR'			=> '->',
    				'APTR'		=> '=>',
    				'EQUAL'		=> '=',
    				'NUMBER'	=> 'number',
    				'UNIMATH'	=> '+" , "-',
    				'MATH'		=> '*" , "/" , "%',
    				'INCDEC'	=> '++" , "--',
    				'SPACE'		=> ' ',
    				'DOLLAR'	=> '$',
    				'SEMICOLON' => ';',
    				'COLON'		=> ':',
    				'QUOTE'		=> '"',
    				'VERT'		=> '|',
    				'DOT'			=> '.',
    				'COMMA'		=> '","',
    				'ANDSYM'		=> '"&"',
    				'ID'			=> 'identifier',
    				'OTHER'		=> 'text',
    				'PHP'			=> 'PHP code',
    				'LDELSLASH' => 'closing tag',
    				'COMMENTSTART' => '{*',
    				'COMMENTEND' => '*}',
    				'AS' => 'as',
    				'IN' => 'in',
    				'BOOLEAN' => 'boolean'
    				);
    				
    				
    function __construct($data)
    {
        // set instance object
        self::instance($this); 
        $this->data = $data;
        $this->counter = 0;
        $this->line = 1;
        $this->smarty = Smarty::instance(); 
        $this->ldel = preg_quote($this->smarty->left_delimiter); 
        $this->rdel = preg_quote($this->smarty->right_delimiter);
     }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    } 



    private $_yy_state = 1;
    private $_yy_stack = array();

    function yylex()
    {
        return $this->{'yylex' . $this->_yy_state}();
    }

    function yypushstate($state)
    {
        array_push($this->_yy_stack, $this->_yy_state);
        $this->_yy_state = $state;
    }

    function yypopstate()
    {
        $this->_yy_state = array_pop($this->_yy_stack);
    }

    function yybegin($state)
    {
        $this->_yy_state = $state;
    }



    function yylex1()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 0,
              4 => 0,
              5 => 0,
              6 => 0,
              7 => 0,
              8 => 0,
              9 => 0,
              10 => 1,
              12 => 0,
              13 => 0,
              14 => 1,
              16 => 1,
              18 => 1,
              20 => 1,
              22 => 1,
              24 => 1,
              26 => 1,
              28 => 1,
              30 => 1,
              32 => 0,
              33 => 0,
              34 => 0,
              35 => 0,
              36 => 0,
              37 => 0,
              38 => 0,
              39 => 0,
              40 => 1,
              42 => 0,
              43 => 1,
              45 => 1,
              47 => 0,
              48 => 0,
              49 => 0,
              50 => 0,
              51 => 0,
              52 => 0,
              53 => 0,
              54 => 0,
              55 => 0,
              56 => 0,
              57 => 0,
              58 => 0,
            );
        if ($this->counter >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(<\\?php.*\\?>)|^(\\*".$this->rdel.")|^(".$this->ldel."\\*)|^('[^'\\\\\\\\]*(?:\\\\\\\\.[^'\\\\\\\\]*)*')|^(".$this->ldel."\\s{1,})|^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->counter), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->data,
                        $this->counter, 5) . '... state START');
                }
                next($yymatches); // skip global match
                $this->token = key($yymatches); // token number
                if ($tokenMap[$this->token]) {
                    // extract sub-patterns for passing to lex function
                    $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                        $tokenMap[$this->token]);
                } else {
                    $yysubmatches = array();
                }
                $this->value = current($yymatches); // token value
                $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    if ($this->counter >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => array(0, "^(\\*".$this->rdel.")|^(".$this->ldel."\\*)|^('[^'\\\\\\\\]*(?:\\\\\\\\.[^'\\\\\\\\]*)*')|^(".$this->ldel."\\s{1,})|^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        2 => array(0, "^(".$this->ldel."\\*)|^('[^'\\\\\\\\]*(?:\\\\\\\\.[^'\\\\\\\\]*)*')|^(".$this->ldel."\\s{1,})|^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        3 => array(0, "^('[^'\\\\\\\\]*(?:\\\\\\\\.[^'\\\\\\\\]*)*')|^(".$this->ldel."\\s{1,})|^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        4 => array(0, "^(".$this->ldel."\\s{1,})|^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        5 => array(0, "^(\\s{1,}".$this->rdel.")|^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        6 => array(0, "^(".$this->ldel."\/)|^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        7 => array(0, "^(".$this->ldel.")|^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        8 => array(0, "^(".$this->rdel.")|^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        9 => array(0, "^(\\s+(IN|in)\\s+)|^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        10 => array(1, "^(true|false)|^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        12 => array(1, "^(\\s*===\\s*)|^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        13 => array(1, "^(\\s*==\\s*|\\s+(EQ|eq)\\s+)|^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        14 => array(2, "^(\\s*!=\\s*|\\s+(NE|ne)\\s+)|^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        16 => array(3, "^(\\s*>=\\s*|\\s+(GE|ge)\\s+)|^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        18 => array(4, "^(\\s*<=\\s*|\\s+(LE|le)\\s+)|^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        20 => array(5, "^(\\s*>\\s*|\\s+(GT|gt)\\s+)|^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        22 => array(6, "^(\\s*<\\s*|\\s+(LT|lt)\\s+)|^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        24 => array(7, "^(\\s*!|\\s+(NOT|not)\\s+)|^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        26 => array(8, "^(\\s*&&\\s*|\\s+(AND|and)\\s+)|^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        28 => array(9, "^(\\s*\\|\\|\\s*|\\s+(OR|or)\\s+)|^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        30 => array(10, "^(\\()|^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        32 => array(10, "^(\\))|^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        33 => array(10, "^(\\[)|^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        34 => array(10, "^(])|^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        35 => array(10, "^(_)|^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        36 => array(10, "^(->)|^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        37 => array(10, "^(\\s?=>\\s?)|^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        38 => array(10, "^(=)|^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        39 => array(10, "^(\\d+(\\.\\d+)?)|^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        40 => array(11, "^(\\+\\+|--)|^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        42 => array(11, "^(\\s?(\\+|-)\\s?)|^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        43 => array(12, "^(\\s?\\*(?!\\})\\s?|\\s?(\/|%)\\s?)|^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        45 => array(13, "^([\s]+)|^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        47 => array(13, "^(\\$)|^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        48 => array(13, "^(;)|^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        49 => array(13, "^(:)|^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        50 => array(13, "^(\")|^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        51 => array(13, "^(\\|)|^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        52 => array(13, "^(\\.)|^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        53 => array(13, "^(,)|^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        54 => array(13, "^(\\s?&\\s?)|^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        55 => array(13, "^(\\w+)|^(<\\?php.*\\?>)|^(.)"),
        56 => array(13, "^(<\\?php.*\\?>)|^(.)"),
        57 => array(13, "^(.)"),
        58 => array(13, ""),
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token][1])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        $yysubmatches = array();
                        if (preg_match('/' . $yy_yymore_patterns[$this->token][1] . '/',
                              substr($this->data, $this->counter), $yymatches)) {
                            $yysubmatches = $yymatches;
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token += key($yymatches) + $yy_yymore_patterns[$this->token][0]; // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count($this->value, "\n");
                            if ($tokenMap[$this->token]) {
                                // extract sub-patterns for passing to lex function
                                $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                                    $tokenMap[$this->token]);
                            } else {
                                $yysubmatches = array();
                            }
                        }
                    	$r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                    } while ($r !== null && !is_bool($r));
			        if ($r === true) {
			            // we have changed state
			            // process this token in the new state
			            return $this->yylex();
                    } elseif ($r === false) {
                        $this->counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        if ($this->counter >= strlen($this->data)) {
                            return false; // end of input
                        }
                        // skip this token
                        continue;
			        } else {
	                    // accept
	                    $this->counter += strlen($this->value);
	                    $this->line += substr_count($this->value, "\n");
	                    return true;
			        }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->counter]);
            }
            break;
        } while (true);

    } // end function


    const START = 1;
    function yy_r1_1($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PHP;
    }
    function yy_r1_2($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COMMENTEND;
    }
    function yy_r1_3($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COMMENTSTART;
    }
    function yy_r1_4($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SI_QSTR;
    }
    function yy_r1_5($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LDELS;
    }
    function yy_r1_6($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_RDELS;
    }
    function yy_r1_7($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LDELSLASH;
    }
    function yy_r1_8($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LDEL;
    }
    function yy_r1_9($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_RDEL;
    }
    function yy_r1_10($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_IN;
    }
    function yy_r1_12($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_BOOLEAN;
    }
    function yy_r1_13($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_IDENTITY;
    }
    function yy_r1_14($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_EQUALS;
    }
    function yy_r1_16($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NOTEQUALS;
    }
    function yy_r1_18($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_GREATEREQUAL;
    }
    function yy_r1_20($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LESSEQUAL;
    }
    function yy_r1_22($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_GREATERTHAN;
    }
    function yy_r1_24($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LESSTHAN;
    }
    function yy_r1_26($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NOT;
    }
    function yy_r1_28($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LAND;
    }
    function yy_r1_30($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LOR;
    }
    function yy_r1_32($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OPENP;
    }
    function yy_r1_33($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_CLOSEP;
    }
    function yy_r1_34($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OPENB;
    }
    function yy_r1_35($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_CLOSEB;
    }
    function yy_r1_36($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_UNDERL;
    }
    function yy_r1_37($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PTR; 
    }
    function yy_r1_38($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_APTR;
    }
    function yy_r1_39($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_EQUAL;
    }
    function yy_r1_40($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NUMBER;
    }
    function yy_r1_42($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_INCDEC;
    }
    function yy_r1_43($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_UNIMATH;
    }
    function yy_r1_45($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_MATH;
    }
    function yy_r1_47($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SPACE;
    }
    function yy_r1_48($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_DOLLAR;
    }
    function yy_r1_49($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SEMICOLON;
    }
    function yy_r1_50($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COLON;
    }
    function yy_r1_51($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_QUOTE;
    }
    function yy_r1_52($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_VERT;
    }
    function yy_r1_53($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_DOT;
    }
    function yy_r1_54($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COMMA;
    }
    function yy_r1_55($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_ANDSYM;
    }
    function yy_r1_56($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_ID;
    }
    function yy_r1_57($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PHP;
    }
    function yy_r1_58($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OTHER;
    }

}
