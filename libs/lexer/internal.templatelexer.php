<?php
class Smarty_Internal_Templatelexer extends Smarty_Internal_PluginBase
{

    public $data;
    public $counter;
    public $token;
    public $value;
    public $node;
    public $line;
    public $type;
    private $state = 1;

    function __construct($data)
    {
        $this->data = $data;
        $this->counter = 0;
        $this->line = 1;
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
              10 => 0,
              11 => 0,
              12 => 0,
              13 => 0,
              14 => 0,
              15 => 0,
              16 => 0,
              17 => 0,
              18 => 0,
              19 => 0,
              20 => 0,
              21 => 0,
              22 => 0,
              23 => 0,
              24 => 0,
              25 => 0,
              26 => 0,
              27 => 0,
              28 => 0,
              29 => 0,
              30 => 0,
              31 => 1,
              33 => 0,
              34 => 0,
              35 => 0,
              36 => 0,
              37 => 0,
              38 => 0,
              39 => 0,
              40 => 0,
            );
        if ($this->counter >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^('[^'\\\\\\\\]*(?:\\\\\\\\.[^'\\\\\\\\]*)*')|^(\"[^\"\\\\\\\\]*(?:\\\\\\\\.[^\"\\\\\\\\]*)*\")|^(\\{for[\s\t]+)|^(\\{if[\s\t]+)|^(\\{elseif[\s\t]+)|^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)/";

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
        1 => array(0, "^(\"[^\"\\\\\\\\]*(?:\\\\\\\\.[^\"\\\\\\\\]*)*\")|^(\\{for[\s\t]+)|^(\\{if[\s\t]+)|^(\\{elseif[\s\t]+)|^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        2 => array(0, "^(\\{for[\s\t]+)|^(\\{if[\s\t]+)|^(\\{elseif[\s\t]+)|^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        3 => array(0, "^(\\{if[\s\t]+)|^(\\{elseif[\s\t]+)|^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        4 => array(0, "^(\\{elseif[\s\t]+)|^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        5 => array(0, "^([\s\t]*===[\s\t]*)|^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        6 => array(0, "^([\s\t]*==[\s\t]*|[\s\t]+EQ[\s\t]+)|^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        7 => array(0, "^([\s\t]*!=[\s\t]*|[\s\t]+NE[\s\t]+)|^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        8 => array(0, "^([\s\t]*>=[\s\t]*|[\s\t]+GE[\s\t]+)|^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        9 => array(0, "^([\s\t]*<=[\s\t]*|[\s\t]+LE[\s\t]+)|^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        10 => array(0, "^([\s\t]*>[\s\t]*|[\s\t]+GT[\s\t]+)|^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        11 => array(0, "^([\s\t]*<[\s\t]*|[\s\t]+LT[\s\t]+)|^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        12 => array(0, "^(!|[\s\t]+NOT[\s\t]+)|^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        13 => array(0, "^([\s\t]+AND[\s\t]+|[\s\t]+&&[\s\t]+)|^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        14 => array(0, "^([\s\t]+OR[\s\t]+|[\s\t]+\\|\\|[\s\t]+)|^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        15 => array(0, "^(\\()|^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        16 => array(0, "^(\\))|^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        17 => array(0, "^(\\[)|^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        18 => array(0, "^(])|^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        19 => array(0, "^(->)|^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        20 => array(0, "^(=>)|^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        21 => array(0, "^(=)|^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        22 => array(0, "^(\\+)|^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        23 => array(0, "^(-)|^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        24 => array(0, "^(\\*)|^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        25 => array(0, "^(\/)|^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        26 => array(0, "^(%)|^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        27 => array(0, "^([\s\t]+)|^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        28 => array(0, "^(\\{)|^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        29 => array(0, "^(\\})|^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        30 => array(0, "^([0-9]+(\\.[0-9]+)?)|^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        31 => array(1, "^(\\$)|^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        33 => array(1, "^(;)|^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        34 => array(1, "^(:)|^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        35 => array(1, "^(\\|)|^(\\.)|^(,)|^(\\w+)|^(.)"),
        36 => array(1, "^(\\.)|^(,)|^(\\w+)|^(.)"),
        37 => array(1, "^(,)|^(\\w+)|^(.)"),
        38 => array(1, "^(\\w+)|^(.)"),
        39 => array(1, "^(.)"),
        40 => array(1, ""),
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

  $this->token = Smarty_Internal_Templateparser::TP_SI_QSTR;
  $this->type = 'string';
    }
    function yy_r1_2($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_DB_QSTR;
  $this->type = 'string';
    }
    function yy_r1_3($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_FORTAG;
    }
    function yy_r1_4($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_IFTAG;
    }
    function yy_r1_5($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_ELSEIFTAG;
    }
    function yy_r1_6($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_IDENTITY;
  $this->type = '"==="';
    }
    function yy_r1_7($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_EQUALS;
  $this->type = '"=="';
    }
    function yy_r1_8($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NOTEQUALS;
    }
    function yy_r1_9($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_GREATEREQUAL;
  $this->type = '">="';
    }
    function yy_r1_10($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LESSEQUAL;
  $this->type = '"<="';
    }
    function yy_r1_11($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_GREATERTHAN;
  $this->type = '">"';
    }
    function yy_r1_12($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LESSTHAN;
  $this->type = '"<"';
    }
    function yy_r1_13($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NOT;
  $this->type = '"!"';
    }
    function yy_r1_14($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LAND;
  $this->type = '"&&"';
    }
    function yy_r1_15($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LOR;
  $this->type = '"||"';
    }
    function yy_r1_16($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OPENP;
  $this->type = '"("';
    }
    function yy_r1_17($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_CLOSEP;
  $this->type = '")"';
    }
    function yy_r1_18($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OPENB;
  $this->type = '"["';
    }
    function yy_r1_19($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_CLOSEB;
  $this->type = '"]"';
    }
    function yy_r1_20($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PTR;
  $this->type = '"->"';
    }
    function yy_r1_21($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_APTR;
  $this->type = '"=>"';
    }
    function yy_r1_22($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_EQUAL;
  $this->type = '"="';
    }
    function yy_r1_23($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PLUS;
  $this->type = '"+"';
    }
    function yy_r1_24($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_MINUS;
  $this->type = '"-"';
    }
    function yy_r1_25($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_STAR;
  $this->type = '"*"';
    }
    function yy_r1_26($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SLASH;
  $this->type = '"/"';
    }
    function yy_r1_27($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_PERCENT;
  $this->type = '"%"';
    }
    function yy_r1_28($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SPACE;
  $this->type = '" "';
    }
    function yy_r1_29($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_LDEL;
  $this->type = '"{"';
    }
    function yy_r1_30($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_RDEL;
  $this->type = '"}"';
    }
    function yy_r1_31($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_NUMBER;
  $this->type = 'number';
    }
    function yy_r1_33($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_DOLLAR;
  $this->type = '"$"';
    }
    function yy_r1_34($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_SEMICOLON;
  $this->type = '";"';
    }
    function yy_r1_35($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COLON;
  $this->type = '":"';
    }
    function yy_r1_36($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_VERT;
  $this->type = '"|"';
    }
    function yy_r1_37($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_DOT;
  $this->type = '"."';
    }
    function yy_r1_38($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_COMMA;
  $this->type = '","';
    }
    function yy_r1_39($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_ID;
  $this->type = 'identifier';
    }
    function yy_r1_40($yy_subpatterns)
    {

  $this->token = Smarty_Internal_Templateparser::TP_OTHER;
    }

}
