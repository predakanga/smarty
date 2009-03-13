<?php
/**
* Smarty Internal Plugin Templateparser
*
* This is the template parser.
* It is generated from the internal.templateparser.y file
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/

/**
 * This can be used to store both the string representation of
 * a token, and any useful meta-data associated with the token.
 *
 * meta-data should be stored as an array
 */
class TP_yyToken implements ArrayAccess
{
    public $string = '';
    public $metadata = array();

    function __construct($s, $m = array())
    {
        if ($s instanceof TP_yyToken) {
            $this->string = $s->string;
            $this->metadata = $s->metadata;
        } else {
            $this->string = (string) $s;
            if ($m instanceof TP_yyToken) {
                $this->metadata = $m->metadata;
            } elseif (is_array($m)) {
                $this->metadata = $m;
            }
        }
    }

    function __toString()
    {
        return $this->_string;
    }

    function offsetExists($offset)
    {
        return isset($this->metadata[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value)
    {
        if ($offset === null) {
            if (isset($value[0])) {
                $x = ($value instanceof TP_yyToken) ?
                    $value->metadata : $value;
                $this->metadata = array_merge($this->metadata, $x);
                return;
            }
            $offset = count($this->metadata);
        }
        if ($value === null) {
            return;
        }
        if ($value instanceof TP_yyToken) {
            if ($value->metadata) {
                $this->metadata[$offset] = $value->metadata;
            }
        } elseif ($value) {
            $this->metadata[$offset] = $value;
        }
    }

    function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }
}

/** The following structure represents a single element of the
 * parser's stack.  Information stored includes:
 *
 *   +  The state number for the parser at this level of the stack.
 *
 *   +  The value of the token stored at this level of the stack.
 *      (In other words, the "major" token.)
 *
 *   +  The semantic value stored at this level of the stack.  This is
 *      the information used by the action routines in the grammar.
 *      It is sometimes called the "minor" token.
 */
class TP_yyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
                     ** is the value of the token  */
};

// code external to the class is included here

// declare_class is output here
#line 12 "internal.templateparser.y"
class Smarty_Internal_Templateparser#line 109 "internal.templateparser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 14 "internal.templateparser.y"

    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex, $compiler) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->smarty = Smarty::instance(); 
        $this->compiler = $compiler;
        $this->template = $this->compiler->template;
        $this->cacher = $this->template->cacher_object; 
				$this->nocache = false;
				$this->prefix_code = array();
				$this->prefix_number = 0;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 142 "internal.templateparser.php"

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const TP_OTHER                          =  1;
    const TP_LDELSLASH                      =  2;
    const TP_LDEL                           =  3;
    const TP_RDEL                           =  4;
    const TP_XML                            =  5;
    const TP_PHP                            =  6;
    const TP_SHORTTAGSTART                  =  7;
    const TP_SHORTTAGEND                    =  8;
    const TP_COMMENTEND                     =  9;
    const TP_COMMENTSTART                   = 10;
    const TP_NUMBER                         = 11;
    const TP_MATH                           = 12;
    const TP_UNIMATH                        = 13;
    const TP_INCDEC                         = 14;
    const TP_OPENP                          = 15;
    const TP_CLOSEP                         = 16;
    const TP_OPENB                          = 17;
    const TP_CLOSEB                         = 18;
    const TP_DOLLAR                         = 19;
    const TP_DOT                            = 20;
    const TP_COMMA                          = 21;
    const TP_COLON                          = 22;
    const TP_DOUBLECOLON                    = 23;
    const TP_SEMICOLON                      = 24;
    const TP_VERT                           = 25;
    const TP_EQUAL                          = 26;
    const TP_SPACE                          = 27;
    const TP_PTR                            = 28;
    const TP_APTR                           = 29;
    const TP_ID                             = 30;
    const TP_EQUALS                         = 31;
    const TP_NOTEQUALS                      = 32;
    const TP_GREATERTHAN                    = 33;
    const TP_LESSTHAN                       = 34;
    const TP_GREATEREQUAL                   = 35;
    const TP_LESSEQUAL                      = 36;
    const TP_IDENTITY                       = 37;
    const TP_NONEIDENTITY                   = 38;
    const TP_NOT                            = 39;
    const TP_LAND                           = 40;
    const TP_LOR                            = 41;
    const TP_QUOTE                          = 42;
    const TP_SINGLEQUOTE                    = 43;
    const TP_BOOLEAN                        = 44;
    const TP_NULL                           = 45;
    const TP_IN                             = 46;
    const TP_ANDSYM                         = 47;
    const TP_BACKTICK                       = 48;
    const TP_HATCH                          = 49;
    const TP_AT                             = 50;
    const TP_ISODD                          = 51;
    const TP_ISNOTODD                       = 52;
    const TP_ISEVEN                         = 53;
    const TP_ISNOTEVEN                      = 54;
    const TP_ISODDBY                        = 55;
    const TP_ISNOTODDBY                     = 56;
    const TP_ISEVENBY                       = 57;
    const TP_ISNOTEVENBY                    = 58;
    const TP_ISDIVBY                        = 59;
    const TP_ISNOTDIVBY                     = 60;
    const TP_LITERALSTART                   = 61;
    const TP_LITERALEND                     = 62;
    const TP_LDELIMTAG                      = 63;
    const TP_RDELIMTAG                      = 64;
    const TP_PHPSTART                       = 65;
    const TP_PHPEND                         = 66;
    const YY_NO_ACTION = 369;
    const YY_ACCEPT_ACTION = 368;
    const YY_ERROR_ACTION = 367;

/* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.  
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < self::YYNSTATE                              Shift N.  That is,
**                                                        push the lookahead
**                                                        token onto the stack
**                                                        and goto state N.
**
**   self::YYNSTATE <= N < self::YYNSTATE+self::YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == self::YYNSTATE+self::YYNRULE                    A syntax error has occurred.
**
**   N == self::YYNSTATE+self::YYNRULE+1                  The parser accepts its
**                                                        input. (and concludes parsing)
**
**   N == self::YYNSTATE+self::YYNRULE+2                  No such action.  Denotes unused
**                                                        slots in the yy_action[] table.
**
** The action table is constructed as a single large static array $yy_action.
** Given state S and lookahead X, the action is computed as
**
**      self::$yy_action[self::$yy_shift_ofst[S] + X ]
**
** If the index value self::$yy_shift_ofst[S]+X is out of range or if the value
** self::$yy_lookahead[self::$yy_shift_ofst[S]+X] is not equal to X or if
** self::$yy_shift_ofst[S] is equal to self::YY_SHIFT_USE_DFLT, it means that
** the action is not in the table and that self::$yy_default[S] should be used instead.  
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the static $yy_reduce_ofst array is used in place of
** the static $yy_shift_ofst array and self::YY_REDUCE_USE_DFLT is used in place of
** self::YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  self::$yy_action        A single table containing all actions.
**  self::$yy_lookahead     A table containing the lookahead for each entry in
**                          yy_action.  Used to detect hash collisions.
**  self::$yy_shift_ofst    For each state, the offset into self::$yy_action for
**                          shifting terminals.
**  self::$yy_reduce_ofst   For each state, the offset into self::$yy_action for
**                          shifting non-terminals after a reduce.
**  self::$yy_default       Default action for each state.
*/
    const YY_SZ_ACTTAB = 836;
static public $yy_action = array(
 /*     0 */   211,  157,  161,  368,   42,  170,  173,  216,  217,  169,
 /*    10 */   172,  173,  154,  153,  187,  219,    7,    6,    5,    8,
 /*    20 */     4,    2,  198,  165,   20,  166,  148,  165,   34,  166,
 /*    30 */    25,   16,   12,  125,   56,  168,  157,  161,   16,   91,
 /*    40 */    56,  133,   33,    1,  124,  118,   91,  154,  153,  187,
 /*    50 */   219,    7,    6,    5,    8,    4,    2,   35,   49,  234,
 /*    60 */   218,  129,  212,  206,  142,  145,  109,  133,  148,   60,
 /*    70 */    34,  133,   25,  133,   12,   61,   56,  157,  161,  224,
 /*    80 */   228,  229,  233,  232,  231,  230,  223,  113,  154,  153,
 /*    90 */   187,  219,    7,    6,    5,    8,    4,    2,  143,   35,
 /*   100 */    49,  234,  218,  197,   21,  159,  142,   29,  127,  131,
 /*   110 */    31,  143,   44,  146,  167,  158,  133,   63,  117,  150,
 /*   120 */    24,  163,  215,  165,   79,  166,  146,   22,  136,  157,
 /*   130 */   161,  200,  150,  165,   21,  166,   21,  214,   40,  132,
 /*   140 */   154,  153,  187,  219,    7,    6,    5,    8,    4,    2,
 /*   150 */   193,  198,  148,   20,   34,   88,    9,   26,   12,   26,
 /*   160 */    56,  200,  103,  200,   29,  199,   28,   31,  148,   56,
 /*   170 */    34,  118,    9,  123,   12,  104,   54,   30,  221,  222,
 /*   180 */    10,  134,   13,   35,   49,  234,  218,  111,  180,   18,
 /*   190 */   142,  205,  162,  133,  182,   45,   10,  152,   60,   35,
 /*   200 */    49,  234,  218,  202,  126,   15,  142,  203,  181,   62,
 /*   210 */   220,  148,   55,   34,  123,   25,  143,   12,  101,   53,
 /*   220 */   148,  177,   34,  188,   25,  143,  163,  190,   56,   80,
 /*   230 */    37,  146,   21,   16,  195,  163,  196,  150,   82,  116,
 /*   240 */   146,   91,   35,   49,  234,  218,  150,   94,  133,  142,
 /*   250 */   156,   35,   49,  234,  218,   26,  148,  123,  142,  200,
 /*   260 */    25,  156,   12,   51,   56,  184,  185,   52,   83,  221,
 /*   270 */   110,  150,  148,  189,   34,  119,   25,   36,  199,  134,
 /*   280 */    56,  160,  150,   99,   75,   21,    3,   35,   49,  234,
 /*   290 */   218,  120,  207,  165,  142,  166,  123,   97,  143,   41,
 /*   300 */   177,  130,  188,   35,   49,  234,  218,  212,  163,  215,
 /*   310 */   142,   79,  200,  146,  216,  217,  133,  174,  201,  150,
 /*   320 */   194,  109,  224,  228,  229,  233,  232,  231,  230,  223,
 /*   330 */   143,   90,  134,   96,  148,  144,   34,   48,   25,  133,
 /*   340 */   163,  215,   56,   79,   27,  146,   87,   16,  109,   33,
 /*   350 */   148,  150,  220,  112,   25,   91,  199,  138,   56,  115,
 /*   360 */   226,   23,   16,  128,   21,   35,   49,  234,  218,  119,
 /*   370 */    91,   47,  142,   19,  133,  192,  123,  204,  150,  143,
 /*   380 */    44,   35,   49,  234,  218,   72,  220,  210,  142,  163,
 /*   390 */   215,  200,   79,  165,  146,  166,   28,  143,   44,  143,
 /*   400 */   150,  123,   81,   68,  104,  214,  183,  163,  215,  178,
 /*   410 */    79,  149,  146,   76,  146,  143,  143,   43,  150,   77,
 /*   420 */   150,   38,   65,  214,  204,  163,  163,  215,   74,   79,
 /*   430 */   146,  146,   46,  114,  220,  174,  150,  150,  135,  143,
 /*   440 */    44,  121,  214,  143,  122,   66,  174,  220,  171,  163,
 /*   450 */   215,   58,   79,  186,  146,   57,  143,   44,  146,  150,
 /*   460 */   150,  213,   73,   78,  150,  214,  163,  215,   86,   79,
 /*   470 */   191,  146,  208,  143,  143,   44,   98,  150,  199,   19,
 /*   480 */    64,  188,  214,  155,  163,  215,  147,   79,  146,  146,
 /*   490 */   141,  143,   44,   17,  150,  150,  174,   71,   95,  175,
 /*   500 */   214,  163,  215,  188,   79,   11,  146,  176,   56,  151,
 /*   510 */   164,   32,  150,  143,   44,  209,  204,  214,  123,   70,
 /*   520 */    14,   39,   92,  163,  215,  179,   79,   50,  146,   59,
 /*   530 */   143,   44,  240,  177,  150,  240,   69,  240,  240,  214,
 /*   540 */   163,  215,  240,   79,  240,  146,  240,  240,  143,   44,
 /*   550 */   240,  150,  240,  240,   67,  240,  214,  240,  163,  215,
 /*   560 */   240,   79,  240,  146,  240,  143,   90,  240,  240,  150,
 /*   570 */   240,  240,  240,  240,  214,  163,  215,  240,   79,  240,
 /*   580 */   146,  240,  240,  240,  240,  240,  150,  143,   85,  240,
 /*   590 */   240,  240,  240,  240,  240,  225,  240,  163,  215,  240,
 /*   600 */    79,  240,  146,  240,  240,  137,  240,  240,  150,  240,
 /*   610 */   143,   85,  240,  240,  240,  240,  240,  240,  240,  240,
 /*   620 */   163,  215,  240,   79,  240,  146,  240,  240,  140,  240,
 /*   630 */   240,  150,  143,   85,  240,  240,  240,  240,  240,  240,
 /*   640 */   240,  240,  163,  215,  240,   79,  240,  146,  143,   85,
 /*   650 */   227,  240,  240,  150,  240,  240,  240,  240,  163,  215,
 /*   660 */   240,   79,  240,  146,  240,  240,  139,  240,  240,  150,
 /*   670 */   240,  143,  107,  240,  240,  240,  240,  240,  240,  240,
 /*   680 */   240,  163,  215,  240,   79,  240,  146,  240,  240,  240,
 /*   690 */   240,  240,  150,  240,  143,   84,  240,  240,  240,  240,
 /*   700 */   240,  240,  240,  240,  163,  215,  240,   79,  240,  146,
 /*   710 */   240,  240,  240,  240,  240,  150,  143,  105,  240,  240,
 /*   720 */   240,  240,  240,  143,   93,  240,  163,  215,  240,   79,
 /*   730 */   240,  146,  240,  163,  215,  240,   79,  150,  146,  240,
 /*   740 */   143,  108,  240,  240,  150,  240,  240,  240,  240,  240,
 /*   750 */   163,  215,  240,   79,  240,  146,  143,  102,  240,  240,
 /*   760 */   240,  150,  240,  240,  240,  240,  163,  215,  240,   79,
 /*   770 */   240,  146,  240,  240,  240,  240,  240,  150,  143,  100,
 /*   780 */   240,  240,  240,  240,  240,  240,  240,  240,  163,  215,
 /*   790 */   240,   79,  240,  146,  240,  240,  240,  143,   89,  150,
 /*   800 */   240,  240,  240,  240,  240,  240,  240,  163,  215,  240,
 /*   810 */    79,  240,  146,  240,  143,  106,  240,  240,  150,  240,
 /*   820 */   240,  240,  240,  240,  163,  215,  240,   79,  240,  146,
 /*   830 */   240,  240,  240,  240,  240,  150,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,   40,   41,   68,   69,   70,   71,   12,   13,    4,
 /*    10 */    70,   71,   51,   52,   53,   54,   55,   56,   57,   58,
 /*    20 */    59,   60,    1,    1,    3,    3,   11,    1,   13,    3,
 /*    30 */    15,   15,   17,   28,   19,    9,   40,   41,   15,   23,
 /*    40 */    19,   25,   47,   27,   28,   30,   23,   51,   52,   53,
 /*    50 */    54,   55,   56,   57,   58,   59,   60,   42,   43,   44,
 /*    60 */    45,   24,   16,   42,   49,   43,   77,   25,   11,   48,
 /*    70 */    13,   25,   15,   25,   17,   16,   19,   40,   41,   31,
 /*    80 */    32,   33,   34,   35,   36,   37,   38,   30,   51,   52,
 /*    90 */    53,   54,   55,   56,   57,   58,   59,   60,   73,   42,
 /*   100 */    43,   44,   45,   16,    3,   16,   49,   17,   83,   84,
 /*   110 */    20,   73,   74,   88,   76,   14,   25,   79,   80,   94,
 /*   120 */    29,   83,   84,    1,   86,    3,   88,   26,   19,   40,
 /*   130 */    41,   30,   94,    1,    3,    3,    3,   99,   78,   30,
 /*   140 */    51,   52,   53,   54,   55,   56,   57,   58,   59,   60,
 /*   150 */     4,    1,   11,    3,   13,   75,   15,   26,   17,   26,
 /*   160 */    19,   30,   78,   30,   17,   85,   22,   20,   11,   19,
 /*   170 */    13,   30,   15,   27,   17,   28,   19,   46,   98,   18,
 /*   180 */    39,   50,   21,   42,   43,   44,   45,   30,   66,   21,
 /*   190 */    49,    4,   42,   25,   62,   81,   39,   48,   48,   42,
 /*   200 */    43,   44,   45,    1,    2,    3,   49,    5,    6,    7,
 /*   210 */    96,   11,   10,   13,   27,   15,   73,   17,   92,   19,
 /*   220 */    11,   95,   13,   97,   15,   73,   83,    4,   19,   86,
 /*   230 */    30,   88,    3,   15,    4,   83,   18,   94,   86,   30,
 /*   240 */    88,   23,   42,   43,   44,   45,   94,   30,   25,   49,
 /*   250 */    73,   42,   43,   44,   45,   26,   11,   27,   49,   30,
 /*   260 */    15,   73,   17,   61,   19,   63,   64,   65,   75,   98,
 /*   270 */    77,   94,   11,    4,   13,   30,   15,   89,   85,   50,
 /*   280 */    19,  104,   94,   21,   72,    3,   24,   42,   43,   44,
 /*   290 */    45,   30,  104,    1,   49,    3,   27,   92,   73,   74,
 /*   300 */    95,   76,   97,   42,   43,   44,   45,   16,   83,   84,
 /*   310 */    49,   86,   30,   88,   12,   13,   25,  105,    4,   94,
 /*   320 */    18,   77,   31,   32,   33,   34,   35,   36,   37,   38,
 /*   330 */    73,   74,   50,   30,   11,   43,   13,   81,   15,   25,
 /*   340 */    83,   84,   19,   86,  100,   88,   75,   15,   77,   47,
 /*   350 */    11,   94,   96,   30,   15,   23,   85,   73,   19,  102,
 /*   360 */   103,   29,   15,   82,    3,   42,   43,   44,   45,   30,
 /*   370 */    23,   81,   49,   26,   25,   90,   27,   96,   94,   73,
 /*   380 */    74,   42,   43,   44,   45,   79,   96,   16,   49,   83,
 /*   390 */    84,   30,   86,    1,   88,    3,   22,   73,   74,   73,
 /*   400 */    94,   27,   72,   79,   28,   99,    4,   83,   84,   83,
 /*   410 */    86,    4,   88,   72,   88,   73,   73,   74,   94,   81,
 /*   420 */    94,   93,   79,   99,   96,   83,   83,   84,   86,   86,
 /*   430 */    88,   88,   81,   30,   96,  105,   94,   94,   73,   73,
 /*   440 */    74,   30,   99,   73,   30,   79,  105,   96,    4,   83,
 /*   450 */    84,   30,   86,   83,   88,   19,   73,   74,   88,   94,
 /*   460 */    94,   30,   79,   72,   94,   99,   83,   84,   75,   86,
 /*   470 */     4,   88,   30,   73,   73,   74,   92,   94,   85,   26,
 /*   480 */    79,   97,   99,   83,   83,   84,   49,   86,   88,   88,
 /*   490 */    30,   73,   74,   15,   94,   94,  105,   79,   92,    4,
 /*   500 */    99,   83,   84,   97,   86,  101,   88,    8,   19,   97,
 /*   510 */   105,   87,   94,   73,   74,   85,   96,   99,   27,   79,
 /*   520 */    15,   93,   93,   83,   84,   76,   86,   19,   88,   90,
 /*   530 */    73,   74,  106,   95,   94,  106,   79,  106,  106,   99,
 /*   540 */    83,   84,  106,   86,  106,   88,  106,  106,   73,   74,
 /*   550 */   106,   94,  106,  106,   79,  106,   99,  106,   83,   84,
 /*   560 */   106,   86,  106,   88,  106,   73,   74,  106,  106,   94,
 /*   570 */   106,  106,  106,  106,   99,   83,   84,  106,   86,  106,
 /*   580 */    88,  106,  106,  106,  106,  106,   94,   73,   74,  106,
 /*   590 */   106,  106,  106,  106,  106,  103,  106,   83,   84,  106,
 /*   600 */    86,  106,   88,  106,  106,   91,  106,  106,   94,  106,
 /*   610 */    73,   74,  106,  106,  106,  106,  106,  106,  106,  106,
 /*   620 */    83,   84,  106,   86,  106,   88,  106,  106,   91,  106,
 /*   630 */   106,   94,   73,   74,  106,  106,  106,  106,  106,  106,
 /*   640 */   106,  106,   83,   84,  106,   86,  106,   88,   73,   74,
 /*   650 */    91,  106,  106,   94,  106,  106,  106,  106,   83,   84,
 /*   660 */   106,   86,  106,   88,  106,  106,   91,  106,  106,   94,
 /*   670 */   106,   73,   74,  106,  106,  106,  106,  106,  106,  106,
 /*   680 */   106,   83,   84,  106,   86,  106,   88,  106,  106,  106,
 /*   690 */   106,  106,   94,  106,   73,   74,  106,  106,  106,  106,
 /*   700 */   106,  106,  106,  106,   83,   84,  106,   86,  106,   88,
 /*   710 */   106,  106,  106,  106,  106,   94,   73,   74,  106,  106,
 /*   720 */   106,  106,  106,   73,   74,  106,   83,   84,  106,   86,
 /*   730 */   106,   88,  106,   83,   84,  106,   86,   94,   88,  106,
 /*   740 */    73,   74,  106,  106,   94,  106,  106,  106,  106,  106,
 /*   750 */    83,   84,  106,   86,  106,   88,   73,   74,  106,  106,
 /*   760 */   106,   94,  106,  106,  106,  106,   83,   84,  106,   86,
 /*   770 */   106,   88,  106,  106,  106,  106,  106,   94,   73,   74,
 /*   780 */   106,  106,  106,  106,  106,  106,  106,  106,   83,   84,
 /*   790 */   106,   86,  106,   88,  106,  106,  106,   73,   74,   94,
 /*   800 */   106,  106,  106,  106,  106,  106,  106,   83,   84,  106,
 /*   810 */    86,  106,   88,  106,   73,   74,  106,  106,   94,  106,
 /*   820 */   106,  106,  106,  106,   83,   84,  106,   86,  106,   88,
 /*   830 */   106,  106,  106,  106,  106,   94,
);
    const YY_SHIFT_USE_DFLT = -40;
    const YY_SHIFT_MAX = 142;
    static public $yy_shift_ofst = array(
 /*     0 */   202,  157,  141,  141,  141,  141,  141,  141,  141,  141,
 /*    10 */   141,  141,   57,   57,   15,  200,   15,   15,   15,   15,
 /*    20 */    15,   15,   15,   15,   15,   15,   15,   15,  261,  323,
 /*    30 */   245,  209,  339,  339,  339,   21,  150,   16,  147,  147,
 /*    40 */   374,  349,  202,  291,   48,  131,  101,  229,  282,  292,
 /*    50 */   361,  392,  392,  361,  361,  392,  361,  361,  491,  376,
 /*    60 */   489,  376,  489,   -4,   37,   89,  -39,  -39,  -39,  -39,
 /*    70 */   -39,  -39,  -39,  -39,  302,   26,  132,  133,  122,   -5,
 /*    80 */    -5,   22,   -5,  269,  223,  168,  146,  187,  230,  314,
 /*    90 */    91,  109,   90,   46,  478,  376,  505,  376,  376,  508,
 /*   100 */    42,  376,   42,  144,  217,   42,   42,   42,   42,  -40,
 /*   110 */   -40,  347,  218,  332,    5,  161,   23,  262,   23,   23,
 /*   120 */    23,  444,  453,  414,  421,  411,  403,  402,  407,  436,
 /*   130 */   466,  495,  478,  431,  442,  499,  303,   59,  149,   87,
 /*   140 */   371,  437,  460,
);
    const YY_REDUCE_USE_DFLT = -66;
    const YY_REDUCE_MAX = 110;
    static public $yy_reduce_ofst = array(
 /*     0 */   -65,   38,  324,  401,  366,  306,  418,  475,  440,  343,
 /*    10 */   457,  383,  257,  492,  514,  225,  537,  575,  559,  683,
 /*    20 */   621,  724,  705,  598,  667,  650,  643,  741,  152,  342,
 /*    30 */    25,  143,  400,  326,  370,  188,  177,  193,  126,  205,
 /*    40 */    80,  271,  -60,  244,  244,  328,  281,  328,  328,  330,
 /*    50 */   338,  341,  391,  290,  114,  212,  256,  351,  393,  384,
 /*    60 */   284,  406,  365,  404,  404,  404,  404,  404,  404,  404,
 /*    70 */   404,  404,  404,  404,  424,  405,  405,  420,  405,  424,
 /*    80 */   424,  405,  424,  430,  -11,  -11,  430,  430,  430,  -11,
 /*    90 */   -11,  439,  438,  -11,  429,  412,  428,  412,  412,  449,
 /*   100 */   -11,  412,  -11,  171,  285,  -11,  -11,  -11,  -11,   84,
 /*   110 */    60,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 7, 10, 61, 63, 64, 65, ),
        /* 1 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 2 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 3 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 4 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 5 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 6 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 7 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 8 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 9 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 10 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 11 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 12 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 13 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 14 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 15 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 16 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 17 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 18 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 19 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 20 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 21 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 22 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 23 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 24 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 25 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 26 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 27 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 28 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 29 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 30 */ array(11, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 31 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 32 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 33 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 34 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 35 */ array(1, 3, 19, 42, 48, ),
        /* 36 */ array(1, 3, 19, 42, 48, ),
        /* 37 */ array(15, 23, 25, 27, 28, ),
        /* 38 */ array(17, 20, 28, ),
        /* 39 */ array(17, 20, 28, ),
        /* 40 */ array(22, 27, ),
        /* 41 */ array(25, 27, ),
        /* 42 */ array(1, 2, 3, 5, 6, 7, 10, 61, 63, 64, 65, ),
        /* 43 */ array(16, 25, 31, 32, 33, 34, 35, 36, 37, 38, ),
        /* 44 */ array(25, 31, 32, 33, 34, 35, 36, 37, 38, ),
        /* 45 */ array(3, 26, 30, 46, 50, ),
        /* 46 */ array(3, 14, 26, 30, ),
        /* 47 */ array(3, 26, 30, 50, ),
        /* 48 */ array(3, 30, 50, ),
        /* 49 */ array(1, 3, 43, ),
        /* 50 */ array(3, 30, ),
        /* 51 */ array(1, 3, ),
        /* 52 */ array(1, 3, ),
        /* 53 */ array(3, 30, ),
        /* 54 */ array(3, 30, ),
        /* 55 */ array(1, 3, ),
        /* 56 */ array(3, 30, ),
        /* 57 */ array(3, 30, ),
        /* 58 */ array(27, ),
        /* 59 */ array(28, ),
        /* 60 */ array(19, ),
        /* 61 */ array(28, ),
        /* 62 */ array(19, ),
        /* 63 */ array(4, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 64 */ array(24, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 65 */ array(16, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 66 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 67 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 68 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 69 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 70 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 71 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 72 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 73 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 74 */ array(12, 13, 18, 47, ),
        /* 75 */ array(1, 3, 9, ),
        /* 76 */ array(1, 3, 62, ),
        /* 77 */ array(3, 26, 30, ),
        /* 78 */ array(1, 3, 66, ),
        /* 79 */ array(12, 13, 47, ),
        /* 80 */ array(12, 13, 47, ),
        /* 81 */ array(1, 3, 43, ),
        /* 82 */ array(12, 13, 47, ),
        /* 83 */ array(4, 27, ),
        /* 84 */ array(4, 25, ),
        /* 85 */ array(21, 25, ),
        /* 86 */ array(4, 27, ),
        /* 87 */ array(4, 27, ),
        /* 88 */ array(4, 27, ),
        /* 89 */ array(4, 25, ),
        /* 90 */ array(25, 29, ),
        /* 91 */ array(19, 30, ),
        /* 92 */ array(17, 20, ),
        /* 93 */ array(16, 25, ),
        /* 94 */ array(15, ),
        /* 95 */ array(28, ),
        /* 96 */ array(15, ),
        /* 97 */ array(28, ),
        /* 98 */ array(28, ),
        /* 99 */ array(19, ),
        /* 100 */ array(25, ),
        /* 101 */ array(28, ),
        /* 102 */ array(25, ),
        /* 103 */ array(22, ),
        /* 104 */ array(30, ),
        /* 105 */ array(25, ),
        /* 106 */ array(25, ),
        /* 107 */ array(25, ),
        /* 108 */ array(25, ),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(15, 23, 26, ),
        /* 112 */ array(15, 18, 23, ),
        /* 113 */ array(15, 23, 29, ),
        /* 114 */ array(4, 28, ),
        /* 115 */ array(18, 21, ),
        /* 116 */ array(15, 23, ),
        /* 117 */ array(21, 24, ),
        /* 118 */ array(15, 23, ),
        /* 119 */ array(15, 23, ),
        /* 120 */ array(15, 23, ),
        /* 121 */ array(4, ),
        /* 122 */ array(26, ),
        /* 123 */ array(30, ),
        /* 124 */ array(30, ),
        /* 125 */ array(30, ),
        /* 126 */ array(30, ),
        /* 127 */ array(4, ),
        /* 128 */ array(4, ),
        /* 129 */ array(19, ),
        /* 130 */ array(4, ),
        /* 131 */ array(4, ),
        /* 132 */ array(15, ),
        /* 133 */ array(30, ),
        /* 134 */ array(30, ),
        /* 135 */ array(8, ),
        /* 136 */ array(30, ),
        /* 137 */ array(16, ),
        /* 138 */ array(48, ),
        /* 139 */ array(16, ),
        /* 140 */ array(16, ),
        /* 141 */ array(49, ),
        /* 142 */ array(30, ),
        /* 143 */ array(),
        /* 144 */ array(),
        /* 145 */ array(),
        /* 146 */ array(),
        /* 147 */ array(),
        /* 148 */ array(),
        /* 149 */ array(),
        /* 150 */ array(),
        /* 151 */ array(),
        /* 152 */ array(),
        /* 153 */ array(),
        /* 154 */ array(),
        /* 155 */ array(),
        /* 156 */ array(),
        /* 157 */ array(),
        /* 158 */ array(),
        /* 159 */ array(),
        /* 160 */ array(),
        /* 161 */ array(),
        /* 162 */ array(),
        /* 163 */ array(),
        /* 164 */ array(),
        /* 165 */ array(),
        /* 166 */ array(),
        /* 167 */ array(),
        /* 168 */ array(),
        /* 169 */ array(),
        /* 170 */ array(),
        /* 171 */ array(),
        /* 172 */ array(),
        /* 173 */ array(),
        /* 174 */ array(),
        /* 175 */ array(),
        /* 176 */ array(),
        /* 177 */ array(),
        /* 178 */ array(),
        /* 179 */ array(),
        /* 180 */ array(),
        /* 181 */ array(),
        /* 182 */ array(),
        /* 183 */ array(),
        /* 184 */ array(),
        /* 185 */ array(),
        /* 186 */ array(),
        /* 187 */ array(),
        /* 188 */ array(),
        /* 189 */ array(),
        /* 190 */ array(),
        /* 191 */ array(),
        /* 192 */ array(),
        /* 193 */ array(),
        /* 194 */ array(),
        /* 195 */ array(),
        /* 196 */ array(),
        /* 197 */ array(),
        /* 198 */ array(),
        /* 199 */ array(),
        /* 200 */ array(),
        /* 201 */ array(),
        /* 202 */ array(),
        /* 203 */ array(),
        /* 204 */ array(),
        /* 205 */ array(),
        /* 206 */ array(),
        /* 207 */ array(),
        /* 208 */ array(),
        /* 209 */ array(),
        /* 210 */ array(),
        /* 211 */ array(),
        /* 212 */ array(),
        /* 213 */ array(),
        /* 214 */ array(),
        /* 215 */ array(),
        /* 216 */ array(),
        /* 217 */ array(),
        /* 218 */ array(),
        /* 219 */ array(),
        /* 220 */ array(),
        /* 221 */ array(),
        /* 222 */ array(),
        /* 223 */ array(),
        /* 224 */ array(),
        /* 225 */ array(),
        /* 226 */ array(),
        /* 227 */ array(),
        /* 228 */ array(),
        /* 229 */ array(),
        /* 230 */ array(),
        /* 231 */ array(),
        /* 232 */ array(),
        /* 233 */ array(),
        /* 234 */ array(),
);
    static public $yy_default = array(
 /*     0 */   367,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*    10 */   367,  367,  353,  367,  318,  367,  318,  318,  318,  367,
 /*    20 */   367,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*    30 */   367,  367,  367,  367,  367,  367,  367,  263,  296,  291,
 /*    40 */   263,  263,  235,  327,  327,  300,  367,  300,  300,  367,
 /*    50 */   367,  367,  367,  367,  367,  367,  367,  367,  263,  286,
 /*    60 */   367,  287,  367,  367,  367,  367,  330,  338,  331,  325,
 /*    70 */   335,  339,  334,  329,  367,  367,  367,  367,  367,  269,
 /*    80 */   302,  367,  323,  367,  367,  317,  367,  367,  367,  367,
 /*    90 */   354,  367,  312,  367,  300,  289,  300,  292,  288,  367,
 /*   100 */   257,  309,  264,  270,  367,  267,  328,  356,  355,  321,
 /*   110 */   321,  268,  367,  268,  367,  367,  301,  367,  268,  367,
 /*   120 */   322,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*   130 */   367,  367,  290,  367,  367,  367,  367,  367,  367,  367,
 /*   140 */   367,  367,  367,  278,  283,  282,  281,  279,  280,  256,
 /*   150 */   298,  311,  360,  337,  336,  274,  359,  348,  258,  326,
 /*   160 */   357,  349,  284,  272,  363,  365,  366,  265,  239,  253,
 /*   170 */   236,  254,  237,  238,  364,  260,  245,  299,  275,  266,
 /*   180 */   244,  243,  240,  259,  241,  242,  273,  332,  310,  250,
 /*   190 */   361,  249,  313,  251,  304,  252,  303,  315,  362,  262,
 /*   200 */   307,  308,  247,  246,  306,  248,  285,  358,  297,  261,
 /*   210 */   314,  255,  295,  319,  324,  271,  277,  276,  294,  333,
 /*   220 */   305,  320,  350,  347,  340,  352,  351,  316,  341,  342,
 /*   230 */   346,  345,  344,  343,  293,
);
/* The next thing included is series of defines which control
** various aspects of the generated parser.
**    self::YYNOCODE      is a number which corresponds
**                        to no legal terminal or nonterminal number.  This
**                        number is used to fill in empty slots of the hash 
**                        table.
**    self::YYFALLBACK    If defined, this indicates that one or more tokens
**                        have fall-back values which should be used if the
**                        original value of the token will not parse.
**    self::YYSTACKDEPTH  is the maximum depth of the parser's stack.
**    self::YYNSTATE      the combined number of states.
**    self::YYNRULE       the number of rules in the grammar
**    self::YYERRORSYMBOL is the code number of the error symbol.  If not
**                        defined, then do no error processing.
*/
    const YYNOCODE = 107;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 235;
    const YYNRULE = 132;
    const YYERRORSYMBOL = 67;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 1;
    /** The next table maps tokens into fallback tokens.  If a construct
     * like the following:
     * 
     *      %fallback ID X Y Z.
     *
     * appears in the grammer, then ID becomes a fallback token for X, Y,
     * and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
     * but it does not parse, the type of the token is changed to ID and
     * the parse is retried before an error is thrown.
     */
    static public $yyFallback = array(
    0,  /*          $ => nothing */
    0,  /*      OTHER => nothing */
    1,  /*  LDELSLASH => OTHER */
    1,  /*       LDEL => OTHER */
    1,  /*       RDEL => OTHER */
    1,  /*        XML => OTHER */
    1,  /*        PHP => OTHER */
    1,  /* SHORTTAGSTART => OTHER */
    1,  /* SHORTTAGEND => OTHER */
    1,  /* COMMENTEND => OTHER */
    1,  /* COMMENTSTART => OTHER */
    1,  /*     NUMBER => OTHER */
    1,  /*       MATH => OTHER */
    1,  /*    UNIMATH => OTHER */
    1,  /*     INCDEC => OTHER */
    1,  /*      OPENP => OTHER */
    1,  /*     CLOSEP => OTHER */
    1,  /*      OPENB => OTHER */
    1,  /*     CLOSEB => OTHER */
    1,  /*     DOLLAR => OTHER */
    1,  /*        DOT => OTHER */
    1,  /*      COMMA => OTHER */
    1,  /*      COLON => OTHER */
    1,  /* DOUBLECOLON => OTHER */
    1,  /*  SEMICOLON => OTHER */
    1,  /*       VERT => OTHER */
    1,  /*      EQUAL => OTHER */
    1,  /*      SPACE => OTHER */
    1,  /*        PTR => OTHER */
    1,  /*       APTR => OTHER */
    1,  /*         ID => OTHER */
    1,  /*     EQUALS => OTHER */
    1,  /*  NOTEQUALS => OTHER */
    1,  /* GREATERTHAN => OTHER */
    1,  /*   LESSTHAN => OTHER */
    1,  /* GREATEREQUAL => OTHER */
    1,  /*  LESSEQUAL => OTHER */
    1,  /*   IDENTITY => OTHER */
    1,  /* NONEIDENTITY => OTHER */
    1,  /*        NOT => OTHER */
    1,  /*       LAND => OTHER */
    1,  /*        LOR => OTHER */
    1,  /*      QUOTE => OTHER */
    1,  /* SINGLEQUOTE => OTHER */
    1,  /*    BOOLEAN => OTHER */
    1,  /*       NULL => OTHER */
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*   BACKTICK => OTHER */
    1,  /*      HATCH => OTHER */
    1,  /*         AT => OTHER */
    1,  /*      ISODD => OTHER */
    1,  /*   ISNOTODD => OTHER */
    1,  /*     ISEVEN => OTHER */
    1,  /*  ISNOTEVEN => OTHER */
    1,  /*    ISODDBY => OTHER */
    1,  /* ISNOTODDBY => OTHER */
    1,  /*   ISEVENBY => OTHER */
    1,  /* ISNOTEVENBY => OTHER */
    1,  /*    ISDIVBY => OTHER */
    1,  /* ISNOTDIVBY => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*   PHPSTART => nothing */
    0,  /*     PHPEND => nothing */
    );
    /**
     * Turn parser tracing on by giving a stream to which to write the trace
     * and a prompt to preface each trace message.  Tracing is turned off
     * by making either argument NULL 
     *
     * Inputs:
     * 
     * - A stream resource to which trace output should be written.
     *   If NULL, then tracing is turned off.
     * - A prefix string written at the beginning of every
     *   line of trace output.  If NULL, then tracing is
     *   turned off.
     *
     * Outputs:
     * 
     * - None.
     * @param resource
     * @param string
     */
    static function Trace($TraceFILE, $zTracePrompt)
    {
        if (!$TraceFILE) {
            $zTracePrompt = 0;
        } elseif (!$zTracePrompt) {
            $TraceFILE = 0;
        }
        self::$yyTraceFILE = $TraceFILE;
        self::$yyTracePrompt = $zTracePrompt;
    }

    /**
     * Output debug information to output (php://output stream)
     */
    static function PrintTrace()
    {
        self::$yyTraceFILE = fopen('php://output', 'w');
        self::$yyTracePrompt = '<br>';
    }

    /**
     * @var resource|0
     */
    static public $yyTraceFILE;
    /**
     * String to prepend to debug output
     * @var string|0
     */
    static public $yyTracePrompt;
    /**
     * @var int
     */
    public $yyidx;                    /* Index of top element in stack */
    /**
     * @var int
     */
    public $yyerrcnt;                 /* Shifts left before out of the error */
    /**
     * @var array
     */
    public $yystack = array();  /* The parser's stack */

    /**
     * For tracing shifts, the names of all terminals and nonterminals
     * are required.  The following table supplies these names
     * @var array
     */
    public $yyTokenName = array( 
  '$',             'OTHER',         'LDELSLASH',     'LDEL',        
  'RDEL',          'XML',           'PHP',           'SHORTTAGSTART',
  'SHORTTAGEND',   'COMMENTEND',    'COMMENTSTART',  'NUMBER',      
  'MATH',          'UNIMATH',       'INCDEC',        'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'DOUBLECOLON', 
  'SEMICOLON',     'VERT',          'EQUAL',         'SPACE',       
  'PTR',           'APTR',          'ID',            'EQUALS',      
  'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',
  'LESSEQUAL',     'IDENTITY',      'NONEIDENTITY',  'NOT',         
  'LAND',          'LOR',           'QUOTE',         'SINGLEQUOTE', 
  'BOOLEAN',       'NULL',          'IN',            'ANDSYM',      
  'BACKTICK',      'HATCH',         'AT',            'ISODD',       
  'ISNOTODD',      'ISEVEN',        'ISNOTEVEN',     'ISODDBY',     
  'ISNOTODDBY',    'ISEVENBY',      'ISNOTEVENBY',   'ISDIVBY',     
  'ISNOTDIVBY',    'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHPSTART',      'PHPEND',        'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'text',          'variable',      'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'value',       
  'array',         'attribute',     'exprs',         'math',        
  'function',      'doublequoted',  'method',        'params',      
  'objectchain',   'vararraydefs',  'object',        'vararraydef', 
  'varvarele',     'objectelement',  'modparameter',  'ifexpr',      
  'ifcond',        'lop',           'arrayelements',  'arrayelement',
  'doublequotedcontent',  'textelement', 
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "start ::= template",
 /*   1 */ "template ::= template_element",
 /*   2 */ "template ::= template template_element",
 /*   3 */ "template_element ::= smartytag",
 /*   4 */ "template_element ::= COMMENTSTART text COMMENTEND",
 /*   5 */ "template_element ::= LITERALSTART text LITERALEND",
 /*   6 */ "template_element ::= LDELIMTAG",
 /*   7 */ "template_element ::= RDELIMTAG",
 /*   8 */ "template_element ::= PHP",
 /*   9 */ "template_element ::= PHPSTART text PHPEND",
 /*  10 */ "template_element ::= SHORTTAGSTART variable SHORTTAGEND",
 /*  11 */ "template_element ::= XML",
 /*  12 */ "template_element ::= OTHER",
 /*  13 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  14 */ "smartytag ::= LDEL statement RDEL",
 /*  15 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  16 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  17 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  18 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  19 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  20 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  21 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  22 */ "foraction ::= EQUAL expr",
 /*  23 */ "foraction ::= INCDEC",
 /*  24 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN value RDEL",
 /*  25 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  26 */ "attributes ::= attributes attribute",
 /*  27 */ "attributes ::= attribute",
 /*  28 */ "attributes ::=",
 /*  29 */ "attribute ::= SPACE ID EQUAL expr",
 /*  30 */ "statements ::= statement",
 /*  31 */ "statements ::= statements COMMA statement",
 /*  32 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  33 */ "expr ::= ID",
 /*  34 */ "expr ::= exprs",
 /*  35 */ "expr ::= expr modifier modparameters",
 /*  36 */ "expr ::= array",
 /*  37 */ "exprs ::= value",
 /*  38 */ "exprs ::= UNIMATH value",
 /*  39 */ "exprs ::= exprs math value",
 /*  40 */ "exprs ::= exprs ANDSYM value",
 /*  41 */ "math ::= UNIMATH",
 /*  42 */ "math ::= MATH",
 /*  43 */ "value ::= variable",
 /*  44 */ "value ::= HATCH ID HATCH",
 /*  45 */ "value ::= NUMBER",
 /*  46 */ "value ::= function",
 /*  47 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  48 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  49 */ "value ::= QUOTE doublequoted QUOTE",
 /*  50 */ "value ::= QUOTE QUOTE",
 /*  51 */ "value ::= ID DOUBLECOLON method",
 /*  52 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP",
 /*  53 */ "value ::= ID DOUBLECOLON method objectchain",
 /*  54 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  55 */ "value ::= ID DOUBLECOLON ID",
 /*  56 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs",
 /*  57 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs objectchain",
 /*  58 */ "value ::= BOOLEAN",
 /*  59 */ "value ::= NULL",
 /*  60 */ "value ::= OPENP expr CLOSEP",
 /*  61 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  62 */ "variable ::= DOLLAR varvar AT ID",
 /*  63 */ "variable ::= object",
 /*  64 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  65 */ "vararraydefs ::=",
 /*  66 */ "vararraydef ::= DOT ID",
 /*  67 */ "vararraydef ::= DOT exprs",
 /*  68 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  69 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  70 */ "varvar ::= varvarele",
 /*  71 */ "varvar ::= varvar varvarele",
 /*  72 */ "varvarele ::= ID",
 /*  73 */ "varvarele ::= LDEL expr RDEL",
 /*  74 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  75 */ "objectchain ::= objectelement",
 /*  76 */ "objectchain ::= objectchain objectelement",
 /*  77 */ "objectelement ::= PTR ID vararraydefs",
 /*  78 */ "objectelement ::= PTR method",
 /*  79 */ "function ::= ID OPENP params CLOSEP",
 /*  80 */ "method ::= ID OPENP params CLOSEP",
 /*  81 */ "params ::= expr COMMA params",
 /*  82 */ "params ::= expr",
 /*  83 */ "params ::=",
 /*  84 */ "modifier ::= VERT ID",
 /*  85 */ "modparameters ::= modparameters modparameter",
 /*  86 */ "modparameters ::=",
 /*  87 */ "modparameter ::= COLON ID",
 /*  88 */ "modparameter ::= COLON exprs",
 /*  89 */ "ifexprs ::= ifexpr",
 /*  90 */ "ifexprs ::= NOT ifexprs",
 /*  91 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  92 */ "ifexpr ::= expr",
 /*  93 */ "ifexpr ::= expr ifcond expr",
 /*  94 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  95 */ "ifexpr ::= ifexprs ISDIVBY ifexprs",
 /*  96 */ "ifexpr ::= ifexprs ISNOTDIVBY ifexprs",
 /*  97 */ "ifexpr ::= ifexprs ISEVEN",
 /*  98 */ "ifexpr ::= ifexprs ISNOTEVEN",
 /*  99 */ "ifexpr ::= ifexprs ISEVENBY ifexprs",
 /* 100 */ "ifexpr ::= ifexprs ISNOTEVENBY ifexprs",
 /* 101 */ "ifexpr ::= ifexprs ISODD",
 /* 102 */ "ifexpr ::= ifexprs ISNOTODD",
 /* 103 */ "ifexpr ::= ifexprs ISODDBY ifexprs",
 /* 104 */ "ifexpr ::= ifexprs ISNOTODDBY ifexprs",
 /* 105 */ "ifcond ::= EQUALS",
 /* 106 */ "ifcond ::= NOTEQUALS",
 /* 107 */ "ifcond ::= GREATERTHAN",
 /* 108 */ "ifcond ::= LESSTHAN",
 /* 109 */ "ifcond ::= GREATEREQUAL",
 /* 110 */ "ifcond ::= LESSEQUAL",
 /* 111 */ "ifcond ::= IDENTITY",
 /* 112 */ "ifcond ::= NONEIDENTITY",
 /* 113 */ "lop ::= LAND",
 /* 114 */ "lop ::= LOR",
 /* 115 */ "array ::= OPENB arrayelements CLOSEB",
 /* 116 */ "arrayelements ::= arrayelement",
 /* 117 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 118 */ "arrayelements ::=",
 /* 119 */ "arrayelement ::= expr",
 /* 120 */ "arrayelement ::= expr APTR expr",
 /* 121 */ "arrayelement ::= ID APTR expr",
 /* 122 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 123 */ "doublequoted ::= doublequotedcontent",
 /* 124 */ "doublequotedcontent ::= variable",
 /* 125 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 126 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 127 */ "doublequotedcontent ::= OTHER",
 /* 128 */ "text ::= text textelement",
 /* 129 */ "text ::= textelement",
 /* 130 */ "textelement ::= OTHER",
 /* 131 */ "textelement ::= LDEL",
    );

    /**
     * This function returns the symbolic name associated with a token
     * value.
     * @param int
     * @return string
     */
    function tokenName($tokenType)
    {
        if ($tokenType === 0) {
            return 'End of Input';
        }
        if ($tokenType > 0 && $tokenType < count($this->yyTokenName)) {
            return $this->yyTokenName[$tokenType];
        } else {
            return "Unknown";
        }
    }

    /**
     * The following function deletes the value associated with a
     * symbol.  The symbol can be either a terminal or nonterminal.
     * @param int the symbol code
     * @param mixed the symbol's value
     */
    static function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
        /* Here is inserted the actions which take place when a
        ** terminal or non-terminal is destroyed.  This can happen
        ** when the symbol is popped from the stack during a
        ** reduce or during error processing or when a parser is 
        ** being destroyed before it is finished parsing.
        **
        ** Note: during a reduce, the only symbols destroyed are those
        ** which appear on the RHS of the rule, but which are not used
        ** inside the C code.
        */
            default:  break;   /* If no destructor action specified: do nothing */
        }
    }

    /**
     * Pop the parser's stack once.
     *
     * If there is a destructor routine associated with the token which
     * is popped from the stack, then call it.
     *
     * Return the major token number for the symbol popped.
     * @param TP_yyParser
     * @return int
     */
    function yy_pop_parser_stack()
    {
        if (!count($this->yystack)) {
            return;
        }
        $yytos = array_pop($this->yystack);
        if (self::$yyTraceFILE && $this->yyidx >= 0) {
            fwrite(self::$yyTraceFILE,
                self::$yyTracePrompt . 'Popping ' . $this->yyTokenName[$yytos->major] .
                    "\n");
        }
        $yymajor = $yytos->major;
        self::yy_destructor($yymajor, $yytos->minor);
        $this->yyidx--;
        return $yymajor;
    }

    /**
     * Deallocate and destroy a parser.  Destructors are all called for
     * all stack elements before shutting the parser down.
     */
    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        if (is_resource(self::$yyTraceFILE)) {
            fclose(self::$yyTraceFILE);
        }
    }

    /**
     * Based on the current state and parser stack, get a list of all
     * possible lookahead tokens
     * @param int
     * @return array
     */
    function yy_get_expected_tokens($token)
    {
        $state = $this->yystack[$this->yyidx]->stateno;
        $expected = self::$yyExpectedTokens[$state];
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return $expected;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return array_unique($expected);
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate])) {
                        $expected += self::$yyExpectedTokens[$nextstate];
                            if (in_array($token,
                                  self::$yyExpectedTokens[$nextstate], true)) {
                            $this->yyidx = $yyidx;
                            $this->yystack = $stack;
                            return array_unique($expected);
                        }
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new TP_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return array_unique($expected);
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return $expected;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return array_unique($expected);
    }

    /**
     * Based on the parser state and current parser stack, determine whether
     * the lookahead token is possible.
     * 
     * The parser will convert the token value to an error token if not.  This
     * catches some unusual edge cases where the parser would fail.
     * @param int
     * @return bool
     */
    function yy_is_expected_token($token)
    {
        if ($token === 0) {
            return true; // 0 is not part of this
        }
        $state = $this->yystack[$this->yyidx]->stateno;
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return true;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return true;
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate]) &&
                          in_array($token, self::$yyExpectedTokens[$nextstate], true)) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        return true;
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new TP_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        if (!$token) {
                            // end of input: this is valid
                            return true;
                        }
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return false;
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return true;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        $this->yyidx = $yyidx;
        $this->yystack = $stack;
        return true;
    }

    /**
     * Find the appropriate action for a parser given the terminal
     * look-ahead token iLookAhead.
     *
     * If the look-ahead token is YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return YY_NO_ACTION.
     * @param int The look-ahead token
     */
    function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;
     
        /* if ($this->yyidx < 0) return self::YY_NO_ACTION;  */
        if (!isset(self::$yy_shift_ofst[$stateno])) {
            // no shift actions
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_shift_ofst[$stateno];
        if ($i === self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            if (count(self::$yyFallback) && $iLookAhead < count(self::$yyFallback)
                   && ($iFallback = self::$yyFallback[$iLookAhead]) != 0) {
                if (self::$yyTraceFILE) {
                    fwrite(self::$yyTraceFILE, self::$yyTracePrompt . "FALLBACK " .
                        $this->yyTokenName[$iLookAhead] . " => " .
                        $this->yyTokenName[$iFallback] . "\n");
                }
                return $this->yy_find_shift_action($iFallback);
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Find the appropriate action for a parser given the non-terminal
     * look-ahead token $iLookAhead.
     *
     * If the look-ahead token is self::YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return self::YY_NO_ACTION.
     * @param int Current state number
     * @param int The look-ahead token
     */
    function yy_find_reduce_action($stateno, $iLookAhead)
    {
        /* $stateno = $this->yystack[$this->yyidx]->stateno; */

        if (!isset(self::$yy_reduce_ofst[$stateno])) {
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_reduce_ofst[$stateno];
        if ($i == self::YY_REDUCE_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Perform a shift action.
     * @param int The new state to shift in
     * @param int The major token to shift in
     * @param mixed the minor token to shift in
     */
    function yy_shift($yyNewState, $yyMajor, $yypMinor)
    {
        $this->yyidx++;
        if ($this->yyidx >= self::YYSTACKDEPTH) {
            $this->yyidx--;
            if (self::$yyTraceFILE) {
                fprintf(self::$yyTraceFILE, "%sStack Overflow!\n", self::$yyTracePrompt);
            }
            while ($this->yyidx >= 0) {
                $this->yy_pop_parser_stack();
            }
            /* Here code is inserted which will execute if the parser
            ** stack ever overflows */
            return;
        }
        $yytos = new TP_yyStackEntry;
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        array_push($this->yystack, $yytos);
        if (self::$yyTraceFILE && $this->yyidx > 0) {
            fprintf(self::$yyTraceFILE, "%sShift %d\n", self::$yyTracePrompt,
                $yyNewState);
            fprintf(self::$yyTraceFILE, "%sStack:", self::$yyTracePrompt);
            for($i = 1; $i <= $this->yyidx; $i++) {
                fprintf(self::$yyTraceFILE, " %s",
                    $this->yyTokenName[$this->yystack[$i]->major]);
            }
            fwrite(self::$yyTraceFILE,"\n");
        }
    }

    /**
     * The following table contains information about every rule that
     * is used during the reduce.
     *
     * <pre>
     * array(
     *  array(
     *   int $lhs;         Symbol on the left-hand side of the rule
     *   int $nrhs;     Number of right-hand side symbols in the rule
     *  ),...
     * );
     * </pre>
     */
    static public $yyRuleInfo = array(
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 11 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 8 ),
  array( 'lhs' => 71, 'rhs' => 8 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 0 ),
  array( 'lhs' => 85, 'rhs' => 4 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 7 ),
  array( 'lhs' => 83, 'rhs' => 4 ),
  array( 'lhs' => 83, 'rhs' => 8 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 5 ),
  array( 'lhs' => 83, 'rhs' => 6 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 2 ),
  array( 'lhs' => 93, 'rhs' => 0 ),
  array( 'lhs' => 95, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 96, 'rhs' => 1 ),
  array( 'lhs' => 96, 'rhs' => 3 ),
  array( 'lhs' => 94, 'rhs' => 4 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 2 ),
  array( 'lhs' => 97, 'rhs' => 3 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 88, 'rhs' => 4 ),
  array( 'lhs' => 90, 'rhs' => 4 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 0 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 0 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 2 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        37 => 0,
        43 => 0,
        45 => 0,
        46 => 0,
        58 => 0,
        59 => 0,
        63 => 0,
        116 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        89 => 1,
        123 => 1,
        129 => 1,
        130 => 1,
        131 => 1,
        2 => 2,
        64 => 2,
        122 => 2,
        128 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        27 => 23,
        82 => 23,
        119 => 23,
        24 => 24,
        25 => 24,
        26 => 26,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        33 => 33,
        35 => 35,
        38 => 38,
        39 => 39,
        40 => 40,
        44 => 44,
        47 => 47,
        48 => 48,
        50 => 48,
        49 => 49,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        60 => 60,
        61 => 61,
        62 => 62,
        65 => 65,
        86 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        91 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        83 => 83,
        84 => 84,
        85 => 85,
        87 => 87,
        88 => 88,
        90 => 90,
        92 => 92,
        93 => 93,
        94 => 93,
        95 => 95,
        96 => 96,
        97 => 97,
        102 => 97,
        98 => 98,
        101 => 98,
        99 => 99,
        104 => 99,
        100 => 100,
        103 => 100,
        105 => 105,
        106 => 106,
        107 => 107,
        108 => 108,
        109 => 109,
        110 => 110,
        111 => 111,
        112 => 112,
        113 => 113,
        114 => 114,
        115 => 115,
        117 => 117,
        118 => 118,
        120 => 120,
        121 => 121,
        124 => 124,
        125 => 125,
        126 => 126,
        127 => 127,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 72 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1726 "internal.templateparser.php"
#line 78 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1729 "internal.templateparser.php"
#line 80 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1732 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1738 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '';    }
#line 1741 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1744 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1747 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1750 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1761 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1772 "internal.templateparser.php"
#line 127 "internal.templateparser.y"
    function yy_r10(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode($this->compiler->compileTag('print_expression',array('value'=>$this->yystack[$this->yyidx + -1]->minor)), $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.t.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".t." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1783 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1786 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1789 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1792 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1795 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1798 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1801 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -3]->minor,'modifier')) {
                                                                      $this->_retvalue .= "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -3]->minor . "(array(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor ."),'modifier');?>";
                                                                 } else {
                                                                   if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					                              $this->_retvalue .= $this->yystack[$this->yyidx + -3]->minor . "(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor .");?>";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1816 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1819 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1822 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1825 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1828 "internal.templateparser.php"
#line 179 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1831 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1834 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1837 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1840 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1843 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1846 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1849 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1852 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1855 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1858 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r35(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
                                                                      $this->_retvalue = "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -1]->minor . "(array(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor ."),'modifier')";
                                                                 } else {
                                                                   if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                               $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -1]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1872 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1875 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1878 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1881 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1884 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1887 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "''";     }
#line 1890 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = "'".str_replace('\"','"',$this->yystack[$this->yyidx + -1]->minor)."'";     }
#line 1893 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1896 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1899 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1902 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r54(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1905 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1908 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1911 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1914 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1917 "internal.templateparser.php"
#line 308 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1921 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1924 "internal.templateparser.php"
#line 321 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1927 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1930 "internal.templateparser.php"
#line 324 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1933 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1936 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1939 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1942 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1945 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1948 "internal.templateparser.php"
#line 346 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1951 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1954 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1957 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1960 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1963 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1972 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1975 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1978 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1981 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1984 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1987 "internal.templateparser.php"
#line 394 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1990 "internal.templateparser.php"
#line 395 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1993 "internal.templateparser.php"
#line 402 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1996 "internal.templateparser.php"
#line 407 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1999 "internal.templateparser.php"
#line 408 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2002 "internal.templateparser.php"
#line 410 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2005 "internal.templateparser.php"
#line 411 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2008 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2011 "internal.templateparser.php"
#line 413 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2014 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2017 "internal.templateparser.php"
#line 415 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2020 "internal.templateparser.php"
#line 421 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = '==';    }
#line 2023 "internal.templateparser.php"
#line 422 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = '!=';    }
#line 2026 "internal.templateparser.php"
#line 423 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = '>';    }
#line 2029 "internal.templateparser.php"
#line 424 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = '<';    }
#line 2032 "internal.templateparser.php"
#line 425 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = '>=';    }
#line 2035 "internal.templateparser.php"
#line 426 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = '<=';    }
#line 2038 "internal.templateparser.php"
#line 427 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = '===';    }
#line 2041 "internal.templateparser.php"
#line 428 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = '!==';    }
#line 2044 "internal.templateparser.php"
#line 430 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = '&&';    }
#line 2047 "internal.templateparser.php"
#line 431 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = '||';    }
#line 2050 "internal.templateparser.php"
#line 433 "internal.templateparser.y"
    function yy_r115(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2053 "internal.templateparser.php"
#line 435 "internal.templateparser.y"
    function yy_r117(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 2056 "internal.templateparser.php"
#line 436 "internal.templateparser.y"
    function yy_r118(){ return;     }
#line 2059 "internal.templateparser.php"
#line 438 "internal.templateparser.y"
    function yy_r120(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2062 "internal.templateparser.php"
#line 440 "internal.templateparser.y"
    function yy_r121(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2065 "internal.templateparser.php"
#line 444 "internal.templateparser.y"
    function yy_r124(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 2068 "internal.templateparser.php"
#line 445 "internal.templateparser.y"
    function yy_r125(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 2071 "internal.templateparser.php"
#line 446 "internal.templateparser.y"
    function yy_r126(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 2074 "internal.templateparser.php"
#line 447 "internal.templateparser.y"
    function yy_r127(){$this->_retvalue = addcslashes($this->yystack[$this->yyidx + 0]->minor,"'");    }
#line 2077 "internal.templateparser.php"

    /**
     * placeholder for the left hand side in a reduce operation.
     * 
     * For a parser with a rule like this:
     * <pre>
     * rule(A) ::= B. { A = 1; }
     * </pre>
     * 
     * The parser will translate to something like:
     * 
     * <code>
     * function yy_r0(){$this->_retvalue = 1;}
     * </code>
     */
    private $_retvalue;

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     * 
     * For a rule such as:
     * 
     * <pre>
     * A ::= B blah C. { dosomething(); }
     * </pre>
     * 
     * This function will first call the action, if any, ("dosomething();" in our
     * example), and then it will pop three states from the stack,
     * one for each entry on the right-hand side of the expression
     * (B, blah, and C in our example rule), and then push the result of the action
     * back on to the stack with the resulting state reduced to (as described in the .out
     * file)
     * @param int Number of the rule by which to reduce
     */
    function yy_reduce($yyruleno)
    {
        //int $yygoto;                     /* The next state */
        //int $yyact;                      /* The next action */
        //mixed $yygotominor;        /* The LHS of the rule reduced */
        //TP_yyStackEntry $yymsp;            /* The top of the parser's stack */
        //int $yysize;                     /* Amount to pop the stack */
        $yymsp = $this->yystack[$this->yyidx];
        if (self::$yyTraceFILE && $yyruleno >= 0 
              && $yyruleno < count(self::$yyRuleName)) {
            fprintf(self::$yyTraceFILE, "%sReduce (%d) [%s].\n",
                self::$yyTracePrompt, $yyruleno,
                self::$yyRuleName[$yyruleno]);
        }

        $this->_retvalue = $yy_lefthand_side = null;
        if (array_key_exists($yyruleno, self::$yyReduceMap)) {
            // call the action
            $this->_retvalue = null;
            $this->{'yy_r' . self::$yyReduceMap[$yyruleno]}();
            $yy_lefthand_side = $this->_retvalue;
        }
        $yygoto = self::$yyRuleInfo[$yyruleno]['lhs'];
        $yysize = self::$yyRuleInfo[$yyruleno]['rhs'];
        $this->yyidx -= $yysize;
        for($i = $yysize; $i; $i--) {
            // pop all of the right-hand side parameters
            array_pop($this->yystack);
        }
        $yyact = $this->yy_find_reduce_action($this->yystack[$this->yyidx]->stateno, $yygoto);
        if ($yyact < self::YYNSTATE) {
            /* If we are not debugging and the reduce action popped at least
            ** one element off the stack, then we can push the new element back
            ** onto the stack here, and skip the stack overflow test in yy_shift().
            ** That gives a significant speed improvement. */
            if (!self::$yyTraceFILE && $yysize) {
                $this->yyidx++;
                $x = new TP_yyStackEntry;
                $x->stateno = $yyact;
                $x->major = $yygoto;
                $x->minor = $yy_lefthand_side;
                $this->yystack[$this->yyidx] = $x;
            } else {
                $this->yy_shift($yyact, $yygoto, $yy_lefthand_side);
            }
        } elseif ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
            $this->yy_accept();
        }
    }

    /**
     * The following code executes when the parse fails
     * 
     * Code from %parse_fail is inserted here
     */
    function yy_parse_failed()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sFail!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser fails */
    }

    /**
     * The following code executes when a syntax error first occurs.
     * 
     * %syntax_error code is inserted here
     * @param int The major type of the error token
     * @param mixed The minor type of the error token
     */
    function yy_syntax_error($yymajor, $TOKEN)
    {
#line 55 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 2194 "internal.templateparser.php"
    }

    /**
     * The following is executed when the parser accepts
     * 
     * %parse_accept code is inserted here
     */
    function yy_accept()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sAccept!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $stack = $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser accepts */
#line 47 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 2219 "internal.templateparser.php"
    }

    /**
     * The main parser program.
     * 
     * The first argument is the major token number.  The second is
     * the token value string as scanned from the input.
     *
     * @param int the token number
     * @param mixed the token value
     * @param mixed any extra arguments that should be passed to handlers
     */
    function doParse($yymajor, $yytokenvalue)
    {
//        $yyact;            /* The parser action. */
//        $yyendofinput;     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */
        
        /* (re)initialize the parser, if necessary */
        if ($this->yyidx === null || $this->yyidx < 0) {
            /* if ($yymajor == 0) return; // not sure why this was here... */
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $x = new TP_yyStackEntry;
            $x->stateno = 0;
            $x->major = 0;
            $this->yystack = array();
            array_push($this->yystack, $x);
        }
        $yyendofinput = ($yymajor==0);
        
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sInput %s\n",
                self::$yyTracePrompt, $this->yyTokenName[$yymajor]);
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL &&
                  !$this->yy_is_expected_token($yymajor)) {
                // force a syntax error
                $yyact = self::YY_ERROR_ACTION;
            }
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yytokenvalue);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } elseif ($yyact < self::YYNSTATE + self::YYNRULE) {
                $this->yy_reduce($yyact - self::YYNSTATE);
            } elseif ($yyact == self::YY_ERROR_ACTION) {
                if (self::$yyTraceFILE) {
                    fprintf(self::$yyTraceFILE, "%sSyntax Error!\n",
                        self::$yyTracePrompt);
                }
                if (self::YYERRORSYMBOL) {
                    /* A syntax error has occurred.
                    ** The response to an error depends upon whether or not the
                    ** grammar defines an error token "ERROR".  
                    **
                    ** This is what we do if the grammar does define ERROR:
                    **
                    **  * Call the %syntax_error function.
                    **
                    **  * Begin popping the stack until we enter a state where
                    **    it is legal to shift the error symbol, then shift
                    **    the error symbol.
                    **
                    **  * Set the error count to three.
                    **
                    **  * Begin accepting and shifting new tokens.  No new error
                    **    processing will occur until three tokens have been
                    **    shifted successfully.
                    **
                    */
                    if ($this->yyerrcnt < 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $yymx = $this->yystack[$this->yyidx]->major;
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ){
                        if (self::$yyTraceFILE) {
                            fprintf(self::$yyTraceFILE, "%sDiscard input token %s\n",
                                self::$yyTracePrompt, $this->yyTokenName[$yymajor]);
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0 &&
                                 $yymx != self::YYERRORSYMBOL &&
        ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                              ){
                            $this->yy_pop_parser_stack();
                        }
                        if ($this->yyidx < 0 || $yymajor==0) {
                            $this->yy_destructor($yymajor, $yytokenvalue);
                            $this->yy_parse_failed();
                            $yymajor = self::YYNOCODE;
                        } elseif ($yymx != self::YYERRORSYMBOL) {
                            $u2 = 0;
                            $this->yy_shift($yyact, self::YYERRORSYMBOL, $u2);
                        }
                    }
                    $this->yyerrcnt = 3;
                    $yyerrorhit = 1;
                } else {
                    /* YYERRORSYMBOL is not defined */
                    /* This is what we do if the grammar does not define ERROR:
                    **
                    **  * Report an error message, and throw away the input token.
                    **
                    **  * If the input token is $, then fail the parse.
                    **
                    ** As before, subsequent error messages are suppressed until
                    ** three input tokens have been successfully shifted.
                    */
                    if ($this->yyerrcnt <= 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $this->yyerrcnt = 3;
                    $this->yy_destructor($yymajor, $yytokenvalue);
                    if ($yyendofinput) {
                        $this->yy_parse_failed();
                    }
                    $yymajor = self::YYNOCODE;
                }
            } else {
                $this->yy_accept();
                $yymajor = self::YYNOCODE;
            }            
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }
}
