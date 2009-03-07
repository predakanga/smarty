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
    const TP_XMLSTART                       =  5;
    const TP_XMLEND                         =  6;
    const TP_NUMBER                         =  7;
    const TP_MATH                           =  8;
    const TP_UNIMATH                        =  9;
    const TP_INCDEC                         = 10;
    const TP_OPENP                          = 11;
    const TP_CLOSEP                         = 12;
    const TP_OPENB                          = 13;
    const TP_CLOSEB                         = 14;
    const TP_DOLLAR                         = 15;
    const TP_DOT                            = 16;
    const TP_COMMA                          = 17;
    const TP_COLON                          = 18;
    const TP_DOUBLECOLON                    = 19;
    const TP_SEMICOLON                      = 20;
    const TP_VERT                           = 21;
    const TP_EQUAL                          = 22;
    const TP_SPACE                          = 23;
    const TP_PTR                            = 24;
    const TP_APTR                           = 25;
    const TP_ID                             = 26;
    const TP_EQUALS                         = 27;
    const TP_NOTEQUALS                      = 28;
    const TP_GREATERTHAN                    = 29;
    const TP_LESSTHAN                       = 30;
    const TP_GREATEREQUAL                   = 31;
    const TP_LESSEQUAL                      = 32;
    const TP_IDENTITY                       = 33;
    const TP_NONEIDENTITY                   = 34;
    const TP_NOT                            = 35;
    const TP_LAND                           = 36;
    const TP_LOR                            = 37;
    const TP_QUOTE                          = 38;
    const TP_SINGLEQUOTE                    = 39;
    const TP_BOOLEAN                        = 40;
    const TP_NULL                           = 41;
    const TP_IN                             = 42;
    const TP_ANDSYM                         = 43;
    const TP_BACKTICK                       = 44;
    const TP_HATCH                          = 45;
    const TP_AT                             = 46;
    const TP_COMMENT                        = 47;
    const TP_LITERALSTART                   = 48;
    const TP_LITERALEND                     = 49;
    const TP_LDELIMTAG                      = 50;
    const TP_RDELIMTAG                      = 51;
    const TP_PHP                            = 52;
    const TP_PHPSTART                       = 53;
    const TP_PHPEND                         = 54;
    const YY_NO_ACTION = 339;
    const YY_ACCEPT_ACTION = 338;
    const YY_ERROR_ACTION = 337;

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
    const YY_SZ_ACTTAB = 567;
static public $yy_action = array(
 /*     0 */   153,  169,   26,  174,    4,  161,    6,   94,   45,  155,
 /*    10 */   151,  158,   61,   18,  151,  158,  153,  202,   26,  100,
 /*    20 */     4,  202,    6,  169,   46,  174,  145,  149,    5,  205,
 /*    30 */   204,   29,   42,  182,  183,   97,  189,   18,  116,  164,
 /*    40 */   155,  172,  173,   58,    5,  151,  158,   29,   42,  182,
 /*    50 */   183,  185,  202,   30,  116,  153,   15,   26,  202,   20,
 /*    60 */   189,    6,   18,   45,   27,  167,  113,    7,  195,  176,
 /*    70 */   177,  170,  122,  191,  100,  153,   24,   26,  187,   20,
 /*    80 */   106,    6,  211,   45,   83,  189,   29,   42,  182,  183,
 /*    90 */   122,  122,  122,  116,   98,  122,   14,  148,  143,  137,
 /*   100 */   139,  140,  141,  142,  147,  106,   29,   42,  182,  183,
 /*   110 */    11,  171,   50,  116,  180,  181,  179,   48,   76,  153,
 /*   120 */    35,   26,  122,   20,   17,    6,  127,   47,  148,  143,
 /*   130 */   137,  139,  140,  141,  142,  147,   18,   78,   31,   94,
 /*   140 */    10,  153,  160,  129,  122,   20,  108,  194,  126,   45,
 /*   150 */    29,   42,  182,  183,  133,   16,   38,  116,  136,  189,
 /*   160 */   101,   56,   99,  188,   22,  155,  203,   25,   60,   18,
 /*   170 */   151,  158,   29,   42,  182,  183,  153,  202,   26,  116,
 /*   180 */    20,  169,  146,  174,   45,  153,  145,  149,   15,   20,
 /*   190 */    85,    6,  189,   45,  155,  103,  193,   55,   93,  151,
 /*   200 */   158,  163,   18,  199,  101,   11,  202,   29,   42,  182,
 /*   210 */   183,  198,  106,   76,  116,  120,   29,   42,  182,  183,
 /*   220 */   211,   15,  153,  116,   26,  189,   20,   23,   70,  122,
 /*   230 */    45,  153,  120,   26,  178,   20,   41,  155,  203,   45,
 /*   240 */    60,   96,  151,  158,   44,  338,   36,  131,  173,  202,
 /*   250 */   105,  117,  186,   29,   42,  182,  183,  102,  212,  125,
 /*   260 */   116,   52,   29,   42,  182,  183,   38,  145,  149,  116,
 /*   270 */   107,   68,  166,  205,  204,  155,  203,  154,   60,  210,
 /*   280 */   151,  158,  151,  158,  118,  128,  153,  202,   38,  202,
 /*   290 */    20,  120,  146,   67,   45,   62,   22,  155,  203,   25,
 /*   300 */    60,   37,  151,  158,  120,  101,   57,   83,   27,  202,
 /*   310 */   155,  203,  209,   60,  146,  151,  158,   29,   42,  182,
 /*   320 */   183,   77,  202,   95,  116,   38,  185,  146,  175,   81,
 /*   330 */    59,  194,    3,  202,  155,  203,  135,   60,   66,  151,
 /*   340 */   158,  152,  206,  184,   73,    9,  202,  155,  203,  144,
 /*   350 */    60,  146,  151,  158,  194,   34,  200,  111,  201,  202,
 /*   360 */   145,  149,  119,   66,  155,  203,   65,   60,  157,  151,
 /*   370 */   158,   33,  155,  203,  188,   60,  202,  151,  158,   70,
 /*   380 */   162,  124,  186,   94,  202,  151,  158,   66,  155,  203,
 /*   390 */    13,   60,  202,  151,  158,  190,  155,  203,  214,   60,
 /*   400 */   202,  151,  158,   11,  169,  121,  174,   19,  202,  207,
 /*   410 */    66,   76,  169,  122,  174,    1,  110,  120,  213,  155,
 /*   420 */   203,   11,   60,   39,  151,  158,   80,   84,  115,   76,
 /*   430 */    63,  202,   13,  132,   72,  155,  203,   40,   60,  186,
 /*   440 */   151,  158,  159,  155,  203,   43,   60,  202,  151,  158,
 /*   450 */    92,   86,   91,  186,  163,  202,  199,  199,   79,  155,
 /*   460 */   203,  186,   60,  175,  151,  158,   11,  155,  203,  208,
 /*   470 */    60,  202,  151,  158,   76,  122,   74,  120,  109,  202,
 /*   480 */    90,   87,  123,  112,   53,  199,  194,  151,  158,  192,
 /*   490 */   155,  203,  114,   60,  202,  151,  158,   88,  104,  202,
 /*   500 */   130,   64,  202,  138,   12,   89,  155,  203,  196,   60,
 /*   510 */    21,  151,  158,    2,  155,  203,  188,   60,  202,  151,
 /*   520 */   158,   69,   45,  196,  168,   21,  202,   28,  156,   71,
 /*   530 */   155,  203,  163,   60,  175,  151,  158,   45,  155,  203,
 /*   540 */   120,   60,  202,  151,  158,  197,   45,   54,   49,  134,
 /*   550 */   202,   51,   75,  150,   23,    8,   82,   32,  218,  157,
 /*   560 */   165,  218,  218,  218,  218,  218,   51,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    1,    9,    3,   11,   70,   13,   64,   15,   70,
 /*    10 */    75,   76,   73,    3,   75,   76,    7,   82,    9,   26,
 /*    20 */    11,   82,   13,    1,   15,    3,   36,   37,   35,    8,
 /*    30 */     9,   38,   39,   40,   41,   26,   26,    3,   45,   39,
 /*    40 */    70,   58,   59,   73,   35,   75,   76,   38,   39,   40,
 /*    50 */    41,   75,   82,   77,   45,    7,   22,    9,   82,   11,
 /*    60 */    26,   13,    3,   15,   43,    1,    2,    3,   92,    5,
 /*    70 */     6,   49,   21,    4,   26,    7,   42,    9,    4,   11,
 /*    80 */    46,   13,   12,   15,   24,   26,   38,   39,   40,   41,
 /*    90 */    21,   21,   21,   45,   26,   21,   25,   27,   28,   29,
 /*   100 */    30,   31,   32,   33,   34,   46,   38,   39,   40,   41,
 /*   110 */    11,   47,   48,   45,   50,   51,   52,   53,   19,    7,
 /*   120 */    65,    9,   21,   11,   25,   13,    4,   15,   27,   28,
 /*   130 */    29,   30,   31,   32,   33,   34,    3,   62,   26,   64,
 /*   140 */    17,    7,   85,   10,   21,   11,   24,   72,   69,   15,
 /*   150 */    38,   39,   40,   41,    4,   22,   61,   45,   63,   26,
 /*   160 */    26,   66,   67,   84,   13,   70,   71,   16,   73,    3,
 /*   170 */    75,   76,   38,   39,   40,   41,    7,   82,    9,   45,
 /*   180 */    11,    1,   87,    3,   15,    7,   36,   37,   22,   11,
 /*   190 */    65,   13,   26,   15,   70,   26,    4,   73,   80,   75,
 /*   200 */    76,   83,    3,   85,   26,   11,   82,   38,   39,   40,
 /*   210 */    41,   26,   46,   19,   45,   23,   38,   39,   40,   41,
 /*   220 */    12,   22,    7,   45,    9,   26,   11,   18,   61,   21,
 /*   230 */    15,    7,   23,    9,   54,   11,   68,   70,   71,   15,
 /*   240 */    73,   26,   75,   76,   15,   56,   57,   58,   59,   82,
 /*   250 */    26,   20,   84,   38,   39,   40,   41,   90,   91,   15,
 /*   260 */    45,   12,   38,   39,   40,   41,   61,   36,   37,   45,
 /*   270 */    26,   66,    4,    8,    9,   70,   71,   70,   73,   14,
 /*   280 */    75,   76,   75,   76,   26,    4,    7,   82,   61,   82,
 /*   290 */    11,   23,   87,   66,   15,   60,   13,   70,   71,   16,
 /*   300 */    73,   61,   75,   76,   23,   26,   66,   24,   43,   82,
 /*   310 */    70,   71,   12,   73,   87,   75,   76,   38,   39,   40,
 /*   320 */    41,   62,   82,   64,   45,   61,   75,   87,   93,   17,
 /*   330 */    66,   72,   20,   82,   70,   71,   12,   73,   61,   75,
 /*   340 */    76,   45,   14,   92,   62,   17,   82,   70,   71,    4,
 /*   350 */    73,   87,   75,   76,   72,   61,   79,   63,   26,   82,
 /*   360 */    36,   37,   26,   61,   70,   71,   68,   73,   86,   75,
 /*   370 */    76,   81,   70,   71,   84,   73,   82,   75,   76,   61,
 /*   380 */    70,   79,   84,   64,   82,   75,   76,   61,   70,   71,
 /*   390 */    22,   73,   82,   75,   76,   44,   70,   71,    4,   73,
 /*   400 */    82,   75,   76,   11,    1,   79,    3,   88,   82,   91,
 /*   410 */    61,   19,    1,   21,    3,   23,   24,   23,   12,   70,
 /*   420 */    71,   11,   73,   68,   75,   76,   61,   26,   79,   19,
 /*   430 */    60,   82,   22,    4,   61,   70,   71,   68,   73,   84,
 /*   440 */    75,   76,   39,   70,   71,   68,   73,   82,   75,   76,
 /*   450 */    61,   80,   80,   84,   83,   82,   85,   85,   61,   70,
 /*   460 */    71,   84,   73,   93,   75,   76,   11,   70,   71,   14,
 /*   470 */    73,   82,   75,   76,   19,   21,   62,   23,   26,   82,
 /*   480 */    80,   61,   70,   71,   26,   85,   72,   75,   76,    4,
 /*   490 */    70,   71,   75,   73,   82,   75,   76,   61,   26,   82,
 /*   500 */     4,   60,   82,    4,   11,   61,   70,   71,    1,   73,
 /*   510 */     3,   75,   76,   89,   70,   71,   84,   73,   82,   75,
 /*   520 */    76,   61,   15,    1,   93,    3,   82,   74,   72,   61,
 /*   530 */    70,   71,   83,   73,   93,   75,   76,   15,   70,   71,
 /*   540 */    23,   73,   82,   75,   76,   38,   15,   78,   15,   63,
 /*   550 */    82,   44,   81,   78,   18,   11,   26,   81,   94,   86,
 /*   560 */    38,   94,   94,   94,   94,   94,   44,
);
    const YY_SHIFT_USE_DFLT = -11;
    const YY_SHIFT_MAX = 126;
    static public $yy_shift_ofst = array(
 /*     0 */    64,    9,   -7,   -7,   -7,   -7,   68,  112,   48,   68,
 /*    10 */    48,   48,   48,   48,   48,   48,   48,   48,   48,   48,
 /*    20 */    48,   48,  215,  169,  178,  224,  279,  279,  134,  507,
 /*    30 */   522,  392,  283,  283,  454,  209,   64,   70,  101,   34,
 /*    40 */   133,  166,    0,   59,   10,   10,   10,   10,  411,   10,
 /*    50 */   411,  531,   60,  517,   60,  265,  150,  324,   21,  231,
 /*    60 */    21,   21,  180,  403,   22,  199,  123,  -10,  -10,   69,
 /*    70 */    71,  208,   74,  281,  394,  151,  244,  192,  268,   51,
 /*    80 */    51,  533,  493,  530,  544,  536,   60,   51,   51,   51,
 /*    90 */    60,   60,   51,   60,  -11,  -11,  455,  410,   99,  312,
 /*   100 */   194,  194,  328,  194,  122,  194,  185,  493,  452,  429,
 /*   110 */   458,  485,  499,  472,  351,  300,  258,  229,  296,  368,
 /*   120 */   336,  406,  332,  345,  249,  401,  496,
);
    const YY_REDUCE_USE_DFLT = -66;
    const YY_REDUCE_MAX = 95;
    static public $yy_reduce_ofst = array(
 /*     0 */   189,   95,  205,  264,  240,  227,  167,  294,  302,  318,
 /*    10 */   277,  326,  349,  420,  397,  365,  444,  389,  373,  436,
 /*    20 */   468,  460,  124,  -30,  412,  -61,  207,  -65,  310,  -24,
 /*    30 */   251,  259,  118,  371,   75,  282,  -17,  319,  319,  290,
 /*    40 */    79,  290,  370,  290,  369,  377,  355,  168,  235,  298,
 /*    50 */   441,  417,  400,  414,  372,  453,  424,  424,  453,  424,
 /*    60 */   453,  453,  431,  431,  431,  432,  -57,  424,  424,  -57,
 /*    70 */   -57,  -57,  -57,  456,  456,  449,  469,  456,  456,  -57,
 /*    80 */   -57,  486,  471,  475,  476,  473,   57,  -57,  -57,  -57,
 /*    90 */    57,   57,  -57,   57,  125,   55,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 47, 48, 50, 51, 52, 53, ),
        /* 1 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 2 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 3 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 4 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 5 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 6 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 7 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 8 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 9 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 10 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 11 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 12 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 13 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 14 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 15 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 16 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 17 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 18 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 19 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 20 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 21 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 22 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 23 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 24 */ array(7, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 25 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 26 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 27 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 28 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 29 */ array(1, 3, 15, 38, 44, ),
        /* 30 */ array(1, 3, 15, 38, 44, ),
        /* 31 */ array(11, 19, 21, 23, 24, ),
        /* 32 */ array(13, 16, 24, ),
        /* 33 */ array(13, 16, 24, ),
        /* 34 */ array(21, 23, ),
        /* 35 */ array(18, 23, ),
        /* 36 */ array(1, 2, 3, 5, 6, 47, 48, 50, 51, 52, 53, ),
        /* 37 */ array(12, 21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 38 */ array(21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 39 */ array(3, 22, 26, 42, 46, ),
        /* 40 */ array(3, 10, 22, 26, ),
        /* 41 */ array(3, 22, 26, 46, ),
        /* 42 */ array(1, 3, 39, ),
        /* 43 */ array(3, 26, 46, ),
        /* 44 */ array(3, 26, ),
        /* 45 */ array(3, 26, ),
        /* 46 */ array(3, 26, ),
        /* 47 */ array(3, 26, ),
        /* 48 */ array(1, 3, ),
        /* 49 */ array(3, 26, ),
        /* 50 */ array(1, 3, ),
        /* 51 */ array(15, ),
        /* 52 */ array(24, ),
        /* 53 */ array(23, ),
        /* 54 */ array(24, ),
        /* 55 */ array(8, 9, 14, 43, ),
        /* 56 */ array(4, 36, 37, ),
        /* 57 */ array(12, 36, 37, ),
        /* 58 */ array(8, 9, 43, ),
        /* 59 */ array(20, 36, 37, ),
        /* 60 */ array(8, 9, 43, ),
        /* 61 */ array(8, 9, 43, ),
        /* 62 */ array(1, 3, 54, ),
        /* 63 */ array(1, 3, 39, ),
        /* 64 */ array(1, 3, 49, ),
        /* 65 */ array(3, 22, 26, ),
        /* 66 */ array(17, 21, ),
        /* 67 */ array(36, 37, ),
        /* 68 */ array(36, 37, ),
        /* 69 */ array(4, 21, ),
        /* 70 */ array(21, 25, ),
        /* 71 */ array(12, 21, ),
        /* 72 */ array(4, 21, ),
        /* 73 */ array(4, 23, ),
        /* 74 */ array(4, 23, ),
        /* 75 */ array(13, 16, ),
        /* 76 */ array(15, 26, ),
        /* 77 */ array(4, 23, ),
        /* 78 */ array(4, 23, ),
        /* 79 */ array(21, ),
        /* 80 */ array(21, ),
        /* 81 */ array(15, ),
        /* 82 */ array(11, ),
        /* 83 */ array(26, ),
        /* 84 */ array(11, ),
        /* 85 */ array(18, ),
        /* 86 */ array(24, ),
        /* 87 */ array(21, ),
        /* 88 */ array(21, ),
        /* 89 */ array(21, ),
        /* 90 */ array(24, ),
        /* 91 */ array(24, ),
        /* 92 */ array(21, ),
        /* 93 */ array(24, ),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(11, 14, 19, ),
        /* 97 */ array(11, 19, 22, ),
        /* 98 */ array(11, 19, 25, ),
        /* 99 */ array(17, 20, ),
        /* 100 */ array(11, 19, ),
        /* 101 */ array(11, 19, ),
        /* 102 */ array(14, 17, ),
        /* 103 */ array(11, 19, ),
        /* 104 */ array(4, 24, ),
        /* 105 */ array(11, 19, ),
        /* 106 */ array(26, ),
        /* 107 */ array(11, ),
        /* 108 */ array(26, ),
        /* 109 */ array(4, ),
        /* 110 */ array(26, ),
        /* 111 */ array(4, ),
        /* 112 */ array(4, ),
        /* 113 */ array(26, ),
        /* 114 */ array(44, ),
        /* 115 */ array(12, ),
        /* 116 */ array(26, ),
        /* 117 */ array(15, ),
        /* 118 */ array(45, ),
        /* 119 */ array(22, ),
        /* 120 */ array(26, ),
        /* 121 */ array(12, ),
        /* 122 */ array(26, ),
        /* 123 */ array(4, ),
        /* 124 */ array(12, ),
        /* 125 */ array(26, ),
        /* 126 */ array(4, ),
        /* 127 */ array(),
        /* 128 */ array(),
        /* 129 */ array(),
        /* 130 */ array(),
        /* 131 */ array(),
        /* 132 */ array(),
        /* 133 */ array(),
        /* 134 */ array(),
        /* 135 */ array(),
        /* 136 */ array(),
        /* 137 */ array(),
        /* 138 */ array(),
        /* 139 */ array(),
        /* 140 */ array(),
        /* 141 */ array(),
        /* 142 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   337,  337,  337,  337,  337,  337,  323,  337,  298,  337,
 /*    10 */   298,  298,  298,  337,  337,  337,  337,  337,  337,  337,
 /*    20 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*    30 */   337,  243,  271,  276,  243,  243,  215,  307,  307,  280,
 /*    40 */   337,  280,  337,  280,  337,  337,  337,  337,  337,  337,
 /*    50 */   337,  337,  267,  243,  266,  337,  337,  337,  303,  337,
 /*    60 */   249,  282,  337,  337,  337,  337,  297,  305,  309,  337,
 /*    70 */   324,  337,  337,  337,  337,  292,  337,  337,  337,  325,
 /*    80 */   247,  337,  280,  337,  280,  250,  289,  244,  308,  239,
 /*    90 */   269,  268,  326,  272,  301,  301,  337,  248,  248,  337,
 /*   100 */   248,  337,  337,  302,  337,  281,  337,  270,  337,  337,
 /*   110 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*   120 */   337,  337,  337,  337,  337,  337,  337,  233,  232,  240,
 /*   130 */   236,  216,  234,  235,  246,  306,  245,  312,  238,  313,
 /*   140 */   314,  315,  316,  311,  237,  318,  304,  317,  310,  319,
 /*   150 */   293,  258,  259,  260,  253,  252,  241,  300,  261,  262,
 /*   160 */   291,  255,  254,  279,  263,  264,  228,  227,  333,  335,
 /*   170 */   220,  219,  217,  218,  336,  334,  225,  226,  224,  223,
 /*   180 */   221,  222,  273,  274,  327,  329,  285,  288,  286,  287,
 /*   190 */   330,  331,  229,  230,  242,  328,  332,  265,  277,  290,
 /*   200 */   296,  299,  278,  251,  256,  257,  320,  322,  283,  295,
 /*   210 */   284,  275,  321,  294,  231,
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
    const YYNOCODE = 95;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 215;
    const YYNRULE = 122;
    const YYERRORSYMBOL = 55;
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
    1,  /*   XMLSTART => OTHER */
    1,  /*     XMLEND => OTHER */
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
    0,  /*    COMMENT => nothing */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*        PHP => nothing */
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
  'RDEL',          'XMLSTART',      'XMLEND',        'NUMBER',      
  'MATH',          'UNIMATH',       'INCDEC',        'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'DOUBLECOLON', 
  'SEMICOLON',     'VERT',          'EQUAL',         'SPACE',       
  'PTR',           'APTR',          'ID',            'EQUALS',      
  'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',
  'LESSEQUAL',     'IDENTITY',      'NONEIDENTITY',  'NOT',         
  'LAND',          'LOR',           'QUOTE',         'SINGLEQUOTE', 
  'BOOLEAN',       'NULL',          'IN',            'ANDSYM',      
  'BACKTICK',      'HATCH',         'AT',            'COMMENT',     
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'text',          'expr',          'attributes',    'statement',   
  'modifier',      'modparameters',  'ifexprs',       'statements',  
  'varvar',        'foraction',     'value',         'array',       
  'attribute',     'exprs',         'math',          'variable',    
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
 /*   4 */ "template_element ::= COMMENT",
 /*   5 */ "template_element ::= LITERALSTART text LITERALEND",
 /*   6 */ "template_element ::= LDELIMTAG",
 /*   7 */ "template_element ::= RDELIMTAG",
 /*   8 */ "template_element ::= PHP",
 /*   9 */ "template_element ::= PHPSTART text PHPEND",
 /*  10 */ "template_element ::= XMLSTART",
 /*  11 */ "template_element ::= XMLEND",
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
 /*  22 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN value RDEL",
 /*  23 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  24 */ "foraction ::= EQUAL expr",
 /*  25 */ "foraction ::= INCDEC",
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
 /*  95 */ "ifcond ::= EQUALS",
 /*  96 */ "ifcond ::= NOTEQUALS",
 /*  97 */ "ifcond ::= GREATERTHAN",
 /*  98 */ "ifcond ::= LESSTHAN",
 /*  99 */ "ifcond ::= GREATEREQUAL",
 /* 100 */ "ifcond ::= LESSEQUAL",
 /* 101 */ "ifcond ::= IDENTITY",
 /* 102 */ "ifcond ::= NONEIDENTITY",
 /* 103 */ "lop ::= LAND",
 /* 104 */ "lop ::= LOR",
 /* 105 */ "array ::= OPENB arrayelements CLOSEB",
 /* 106 */ "arrayelements ::= arrayelement",
 /* 107 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 108 */ "arrayelements ::=",
 /* 109 */ "arrayelement ::= expr",
 /* 110 */ "arrayelement ::= expr APTR expr",
 /* 111 */ "arrayelement ::= ID APTR expr",
 /* 112 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 113 */ "doublequoted ::= doublequotedcontent",
 /* 114 */ "doublequotedcontent ::= variable",
 /* 115 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 116 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 117 */ "doublequotedcontent ::= OTHER",
 /* 118 */ "text ::= text textelement",
 /* 119 */ "text ::= textelement",
 /* 120 */ "textelement ::= OTHER",
 /* 121 */ "textelement ::= LDEL",
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
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 5 ),
  array( 'lhs' => 59, 'rhs' => 5 ),
  array( 'lhs' => 59, 'rhs' => 11 ),
  array( 'lhs' => 59, 'rhs' => 8 ),
  array( 'lhs' => 59, 'rhs' => 8 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 7 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 8 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 5 ),
  array( 'lhs' => 70, 'rhs' => 6 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 0 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 4 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 0 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 0 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
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
        106 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        89 => 1,
        113 => 1,
        119 => 1,
        120 => 1,
        121 => 1,
        2 => 2,
        64 => 2,
        112 => 2,
        118 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 10,
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
        23 => 22,
        24 => 24,
        25 => 25,
        27 => 25,
        82 => 25,
        109 => 25,
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
        49 => 47,
        48 => 48,
        50 => 48,
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
        98 => 98,
        99 => 99,
        100 => 100,
        101 => 101,
        102 => 102,
        103 => 103,
        104 => 104,
        105 => 105,
        107 => 107,
        108 => 108,
        110 => 110,
        111 => 111,
        114 => 114,
        115 => 115,
        116 => 116,
        117 => 117,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1589 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1592 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1595 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1601 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1604 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1607 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1610 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1613 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1624 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1635 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1638 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1641 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1644 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1647 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1650 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1653 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
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
#line 1668 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1671 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1674 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1677 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1680 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1683 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1686 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1689 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1692 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1695 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1698 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1701 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1704 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1707 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1710 "internal.templateparser.php"
#line 196 "internal.templateparser.y"
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
#line 1724 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1727 "internal.templateparser.php"
#line 215 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1730 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1733 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1736 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1739 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "''";     }
#line 1742 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1745 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1748 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1751 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r54(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1754 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1757 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1760 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1763 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1766 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1770 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1773 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1776 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1779 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1782 "internal.templateparser.php"
#line 303 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1785 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1788 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1791 "internal.templateparser.php"
#line 316 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1794 "internal.templateparser.php"
#line 318 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1797 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1800 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1803 "internal.templateparser.php"
#line 327 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1806 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1809 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1812 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1821 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1824 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1827 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1830 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1833 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1836 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1839 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1842 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1845 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1848 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1851 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '==';    }
#line 1854 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!=';    }
#line 1857 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>';    }
#line 1860 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<';    }
#line 1863 "internal.templateparser.php"
#line 392 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '>=';    }
#line 1866 "internal.templateparser.php"
#line 393 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '<=';    }
#line 1869 "internal.templateparser.php"
#line 394 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '===';    }
#line 1872 "internal.templateparser.php"
#line 395 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '!==';    }
#line 1875 "internal.templateparser.php"
#line 397 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '&&';    }
#line 1878 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = '||';    }
#line 1881 "internal.templateparser.php"
#line 400 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1884 "internal.templateparser.php"
#line 402 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1887 "internal.templateparser.php"
#line 403 "internal.templateparser.y"
    function yy_r108(){ return;     }
#line 1890 "internal.templateparser.php"
#line 405 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1893 "internal.templateparser.php"
#line 407 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1896 "internal.templateparser.php"
#line 411 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1899 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1902 "internal.templateparser.php"
#line 413 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1905 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1908 "internal.templateparser.php"

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
#line 2025 "internal.templateparser.php"
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
#line 2050 "internal.templateparser.php"
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
