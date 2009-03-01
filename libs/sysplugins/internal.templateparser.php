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
    const TP_RDEL                           =  3;
    const TP_COMMENTSTART                   =  4;
    const TP_COMMENTEND                     =  5;
    const TP_NUMBER                         =  6;
    const TP_MATH                           =  7;
    const TP_UNIMATH                        =  8;
    const TP_INCDEC                         =  9;
    const TP_OPENP                          = 10;
    const TP_CLOSEP                         = 11;
    const TP_OPENB                          = 12;
    const TP_CLOSEB                         = 13;
    const TP_DOLLAR                         = 14;
    const TP_DOT                            = 15;
    const TP_COMMA                          = 16;
    const TP_COLON                          = 17;
    const TP_SEMICOLON                      = 18;
    const TP_VERT                           = 19;
    const TP_EQUAL                          = 20;
    const TP_SPACE                          = 21;
    const TP_PTR                            = 22;
    const TP_APTR                           = 23;
    const TP_ID                             = 24;
    const TP_EQUALS                         = 25;
    const TP_NOTEQUALS                      = 26;
    const TP_GREATERTHAN                    = 27;
    const TP_LESSTHAN                       = 28;
    const TP_GREATEREQUAL                   = 29;
    const TP_LESSEQUAL                      = 30;
    const TP_IDENTITY                       = 31;
    const TP_NONEIDENTITY                   = 32;
    const TP_NOT                            = 33;
    const TP_LAND                           = 34;
    const TP_LOR                            = 35;
    const TP_QUOTE                          = 36;
    const TP_SINGLEQUOTE                    = 37;
    const TP_BOOLEAN                        = 38;
    const TP_IN                             = 39;
    const TP_ANDSYM                         = 40;
    const TP_BACKTICK                       = 41;
    const TP_HATCH                          = 42;
    const TP_AT                             = 43;
    const TP_LITERALSTART                   = 44;
    const TP_LITERALEND                     = 45;
    const TP_LDELIMTAG                      = 46;
    const TP_RDELIMTAG                      = 47;
    const TP_PHP                            = 48;
    const TP_PHPSTART                       = 49;
    const TP_PHPEND                         = 50;
    const TP_XML                            = 51;
    const TP_LDEL                           = 52;
    const YY_NO_ACTION = 336;
    const YY_ACCEPT_ACTION = 335;
    const YY_ERROR_ACTION = 334;

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
    const YY_SZ_ACTTAB = 539;
static public $yy_action = array(
 /*     0 */   153,  147,   27,  169,    4,   72,    6,   38,   45,  141,
 /*    10 */   197,   22,   59,  106,   24,  195,  155,  206,  102,   61,
 /*    20 */   158,   87,  154,   45,  149,  148,  212,    5,  204,  157,
 /*    30 */    28,   43,  183,  145,  116,  153,  122,   27,  153,    4,
 /*    40 */    27,    6,   18,   48,    6,  165,   45,  176,    7,  121,
 /*    50 */    52,  186,  137,  100,  170,  120,  102,  116,   29,    1,
 /*    60 */   110,   13,    5,  204,  189,   28,   43,  183,   28,   43,
 /*    70 */   183,  122,  163,  196,  122,  155,  155,  212,   60,  158,
 /*    80 */   156,  154,  154,  149,  148,  116,   10,  204,  204,  116,
 /*    90 */   169,  140,  142,  143,  144,  151,  150,  131,  138,  153,
 /*   100 */   116,   27,  169,   18,   21,    6,   36,   45,   87,  178,
 /*   110 */   112,   58,   47,   83,   82,  155,  206,   99,   61,  158,
 /*   120 */   169,  154,  155,  206,  172,   61,  158,  204,  154,   28,
 /*   130 */    43,  183,  145,    7,  204,  122,  116,   33,  197,  179,
 /*   140 */   120,  170,  140,  142,  143,  144,  151,  150,  131,  138,
 /*   150 */   186,   45,   49,  170,  181,  182,  180,   44,   42,  177,
 /*   160 */     8,  153,  204,   27,  155,   18,  129,    6,  164,   51,
 /*   170 */   154,  170,  185,  198,  187,   38,  204,   19,   52,   30,
 /*   180 */    75,  190,   14,  162,  155,  206,  190,   61,  158,   13,
 /*   190 */   154,   28,   43,  183,   17,   38,  204,  122,  205,  184,
 /*   200 */    57,  145,  126,   32,  155,  206,  189,   61,  158,   20,
 /*   210 */   154,  153,  107,   27,   20,   18,  204,  130,  153,   45,
 /*   220 */    27,  145,   18,  149,  148,   38,   45,  205,  184,  101,
 /*   230 */    73,   25,  169,  211,  155,  206,  104,   61,  158,  169,
 /*   240 */   154,   28,   43,  183,  124,  128,  204,  122,   28,   43,
 /*   250 */   183,  145,  116,  190,  122,  153,  204,   27,  155,   18,
 /*   260 */    25,   56,  158,   45,  154,   96,   22,   69,  159,   24,
 /*   270 */   204,    7,  114,   98,  139,  160,  155,  206,  120,   61,
 /*   280 */   158,   20,  154,  170,   16,   28,   43,  183,  204,  190,
 /*   290 */   170,  122,    7,  108,  153,  209,  103,  208,   18,  120,
 /*   300 */   132,   34,   45,  109,  335,   37,  136,  174,    7,  134,
 /*   310 */   155,  206,  101,   61,  158,  120,  154,   20,   17,   68,
 /*   320 */   192,   50,  204,  214,   28,   43,  183,  118,  155,  206,
 /*   330 */   122,   61,  158,   68,  154,   14,  116,  127,  113,  190,
 /*   340 */   204,  118,  155,  206,  202,   61,  158,   12,  154,  155,
 /*   350 */   204,  203,   62,  158,  204,  154,   68,   89,  114,  123,
 /*   360 */   166,  204,  200,   69,  199,  155,  206,   20,   61,  158,
 /*   370 */    68,  154,  155,  206,  117,   61,  158,  204,  154,  155,
 /*   380 */   206,  188,   61,  158,  204,  154,   80,   66,  115,  119,
 /*   390 */   194,  204,   79,  207,   97,  155,  206,  116,   61,  158,
 /*   400 */    74,  154,  195,   41,  168,  149,  148,  204,  118,  155,
 /*   410 */   206,  146,   61,  158,   85,  154,   93,  213,    2,  187,
 /*   420 */   175,  204,  118,  155,  206,  193,   61,  158,   94,  154,
 /*   430 */    39,   64,  173,  174,   78,  204,   96,  155,  206,   65,
 /*   440 */    61,  158,   90,  154,  195,   92,  187,  201,  166,  204,
 /*   450 */   200,  155,  206,   91,   61,  158,   70,  154,  200,    6,
 /*   460 */   116,   45,  118,  204,  175,  155,  206,  111,   61,  158,
 /*   470 */    81,  154,  175,   63,   23,  152,   96,  204,  118,  155,
 /*   480 */   206,   95,   61,  158,   88,  154,  200,   40,  133,  125,
 /*   490 */   105,  204,  191,  155,  206,   67,   61,  158,   14,  154,
 /*   500 */    15,  135,  190,  187,  155,  204,  175,   86,  161,   77,
 /*   510 */   154,  187,   76,   53,   46,   54,  204,   35,    9,  195,
 /*   520 */   189,  114,  171,  166,    3,  167,   55,   26,   45,   23,
 /*   530 */    20,  118,  157,   84,  210,   31,   11,  213,   71,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   11,    8,    1,   10,   60,   12,   59,   14,   61,
 /*    10 */     1,   12,   64,   65,   15,   70,   68,   69,   24,   71,
 /*    20 */    72,   22,   74,   14,   34,   35,   11,   33,   80,   84,
 /*    30 */    36,   37,   38,   85,   19,    6,   42,    8,    6,   10,
 /*    40 */     8,   12,   10,   14,   12,   36,   14,   45,   10,   67,
 /*    50 */    41,   68,    3,   24,   52,   17,   24,   19,   75,   21,
 /*    60 */    22,   52,   33,   80,   82,   36,   37,   38,   36,   37,
 /*    70 */    38,   42,   83,   90,   42,   68,   68,   11,   71,   72,
 /*    80 */    72,   74,   74,   34,   35,   19,   16,   80,   80,   19,
 /*    90 */     1,   25,   26,   27,   28,   29,   30,   31,   32,    6,
 /*   100 */    19,    8,    1,   10,   23,   12,   59,   14,   22,    1,
 /*   110 */     2,   64,    4,   59,   63,   68,   69,   24,   71,   72,
 /*   120 */     1,   74,   68,   69,    5,   71,   72,   80,   74,   36,
 /*   130 */    37,   38,   85,   10,   80,   42,   19,   63,    1,   50,
 /*   140 */    17,   52,   25,   26,   27,   28,   29,   30,   31,   32,
 /*   150 */    68,   14,   44,   52,   46,   47,   48,   49,   66,   51,
 /*   160 */    52,    6,   80,    8,   68,   10,    9,   12,   72,   14,
 /*   170 */    74,   52,   90,   36,   82,   59,   80,   20,   41,   24,
 /*   180 */    64,   24,   20,   42,   68,   69,   24,   71,   72,   52,
 /*   190 */    74,   36,   37,   38,   20,   59,   80,   42,    7,    8,
 /*   200 */    64,   85,   14,   79,   68,   69,   82,   71,   72,   52,
 /*   210 */    74,    6,   24,    8,   52,   10,   80,    3,    6,   14,
 /*   220 */     8,   85,   10,   34,   35,   59,   14,    7,    8,   24,
 /*   230 */    64,   40,    1,   13,   68,   69,   24,   71,   72,    1,
 /*   240 */    74,   36,   37,   38,   68,   69,   80,   42,   36,   37,
 /*   250 */    38,   85,   19,   24,   42,    6,   80,    8,   68,   10,
 /*   260 */    40,   71,   72,   14,   74,   62,   12,   59,   37,   15,
 /*   270 */    80,   10,   43,   24,    3,   37,   68,   69,   17,   71,
 /*   280 */    72,   52,   74,   52,   23,   36,   37,   38,   80,   24,
 /*   290 */    52,   42,   10,   22,    6,   13,   88,   89,   10,   17,
 /*   300 */    61,   59,   14,   61,   54,   55,   56,   57,   10,    3,
 /*   310 */    68,   69,   24,   71,   72,   17,   74,   52,   20,   59,
 /*   320 */     3,   14,   80,    3,   36,   37,   38,   21,   68,   69,
 /*   330 */    42,   71,   72,   59,   74,   20,   19,   77,   68,   24,
 /*   340 */    80,   21,   68,   69,   13,   71,   72,   16,   74,   68,
 /*   350 */    80,   77,   71,   72,   80,   74,   59,   78,   43,   24,
 /*   360 */    81,   80,   83,   59,   24,   68,   69,   52,   71,   72,
 /*   370 */    59,   74,   68,   69,   77,   71,   72,   80,   74,   68,
 /*   380 */    69,    3,   71,   72,   80,   74,   59,   58,   77,   18,
 /*   390 */     3,   80,   60,   89,   62,   68,   69,   19,   71,   72,
 /*   400 */    59,   74,   70,   66,    3,   34,   35,   80,   21,   68,
 /*   410 */    69,    3,   71,   72,   59,   74,   16,   11,   18,   82,
 /*   420 */    91,   80,   21,   68,   69,    3,   71,   72,   59,   74,
 /*   430 */    66,   58,   56,   57,   60,   80,   62,   68,   69,   58,
 /*   440 */    71,   72,   59,   74,   70,   78,   82,   24,   81,   80,
 /*   450 */    83,   68,   69,   78,   71,   72,   59,   74,   83,   12,
 /*   460 */    19,   14,   21,   80,   91,   68,   69,   24,   71,   72,
 /*   470 */    59,   74,   91,   58,   17,   11,   62,   80,   21,   68,
 /*   480 */    69,   78,   71,   72,   59,   74,   83,   66,    3,   24,
 /*   490 */    24,   80,   41,   68,   69,   66,   71,   72,   20,   74,
 /*   500 */    86,    3,   24,   82,   68,   80,   91,   24,   72,   60,
 /*   510 */    74,   82,   17,   11,   14,   24,   80,   39,   10,   70,
 /*   520 */    82,   43,   91,   81,   87,   70,   76,   73,   14,   17,
 /*   530 */    52,   21,   84,   24,   76,   79,   10,   92,   79,
);
    const YY_SHIFT_USE_DFLT = -11;
    const YY_SHIFT_MAX = 128;
    static public $yy_shift_ofst = array(
 /*     0 */   108,   29,   -6,   -6,   -6,   -6,   93,   32,  155,   32,
 /*    10 */    32,   32,   93,   32,   32,   32,   32,   32,   32,   32,
 /*    20 */    32,   32,  249,  205,  212,  288,  288,  288,  137,    9,
 /*    30 */    38,   -1,   -1,  457,  441,  447,   66,  108,  117,  478,
 /*    40 */   315,  157,  229,  238,  101,  265,  265,  101,  265,  101,
 /*    50 */   265,  265,  514,   86,  510,   86,  220,  371,  -10,   49,
 /*    60 */   191,  191,  191,  119,  231,    2,   89,  162,   70,   81,
 /*    70 */   317,  254,  306,  189,   15,  189,  188,  320,  401,  387,
 /*    80 */   378,  233,  512,  233,  508,  233,  526,  509,  233,   86,
 /*    90 */   233,   86,   86,  307,  233,   86,  -11,  -11,  282,  261,
 /*   100 */   298,  123,  123,  331,  123,  271,  400,  508,  443,  422,
 /*   110 */   491,  485,  466,  451,  340,  464,  423,  406,  465,  500,
 /*   120 */   495,  498,  335,  141,  214,  174,  483,  502,  408,
);
    const YY_REDUCE_USE_DFLT = -56;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */   250,  -52,  136,  166,   47,  116,  208,  297,  242,  311,
 /*    10 */   274,  260,  304,  397,  369,  355,  425,   54,  341,  383,
 /*    20 */   327,  411,  190,    7,  281,  436,   96,    8,  -17,   82,
 /*    30 */   332,  279,  367,  -55,  374,  176,  414,  376,  414,  124,
 /*    40 */   124,  -18,  124,  373,  329,   92,  337,  415,  364,  381,
 /*    50 */   429,  421,  270,  403,  449,  375,  454,  437,  437,  437,
 /*    60 */   454,  454,  454,  431,  431,  431,  431,  438,  203,  203,
 /*    70 */   203,  442,  455,  437,  203,  437,  450,  455,  455,  455,
 /*    80 */   203,  203,  448,  203,  459,  203,  456,  458,  203,  -11,
 /*    90 */   203,  -11,  -11,  239,  203,  -11,   51,   74,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 21 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 22 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 23 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 24 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 26 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 27 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 28 */ array(1, 14, 36, 41, 52, ),
        /* 29 */ array(1, 14, 36, 41, 52, ),
        /* 30 */ array(10, 17, 19, 21, 22, ),
        /* 31 */ array(12, 15, 22, ),
        /* 32 */ array(12, 15, 22, ),
        /* 33 */ array(17, 21, ),
        /* 34 */ array(19, 21, ),
        /* 35 */ array(12, 14, ),
        /* 36 */ array(11, 19, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 37 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 38 */ array(19, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 39 */ array(20, 24, 39, 43, 52, ),
        /* 40 */ array(20, 24, 43, 52, ),
        /* 41 */ array(9, 20, 24, 52, ),
        /* 42 */ array(24, 43, 52, ),
        /* 43 */ array(1, 37, 52, ),
        /* 44 */ array(1, 52, ),
        /* 45 */ array(24, 52, ),
        /* 46 */ array(24, 52, ),
        /* 47 */ array(1, 52, ),
        /* 48 */ array(24, 52, ),
        /* 49 */ array(1, 52, ),
        /* 50 */ array(24, 52, ),
        /* 51 */ array(24, 52, ),
        /* 52 */ array(14, ),
        /* 53 */ array(22, ),
        /* 54 */ array(21, ),
        /* 55 */ array(22, ),
        /* 56 */ array(7, 8, 13, 40, ),
        /* 57 */ array(18, 34, 35, ),
        /* 58 */ array(11, 34, 35, ),
        /* 59 */ array(3, 34, 35, ),
        /* 60 */ array(7, 8, 40, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(7, 8, 40, ),
        /* 63 */ array(1, 5, 52, ),
        /* 64 */ array(1, 37, 52, ),
        /* 65 */ array(1, 45, 52, ),
        /* 66 */ array(1, 50, 52, ),
        /* 67 */ array(20, 24, 52, ),
        /* 68 */ array(16, 19, ),
        /* 69 */ array(19, 23, ),
        /* 70 */ array(3, 19, ),
        /* 71 */ array(12, 15, ),
        /* 72 */ array(3, 21, ),
        /* 73 */ array(34, 35, ),
        /* 74 */ array(11, 19, ),
        /* 75 */ array(34, 35, ),
        /* 76 */ array(14, 24, ),
        /* 77 */ array(3, 21, ),
        /* 78 */ array(3, 21, ),
        /* 79 */ array(3, 21, ),
        /* 80 */ array(3, 19, ),
        /* 81 */ array(19, ),
        /* 82 */ array(17, ),
        /* 83 */ array(19, ),
        /* 84 */ array(10, ),
        /* 85 */ array(19, ),
        /* 86 */ array(10, ),
        /* 87 */ array(24, ),
        /* 88 */ array(19, ),
        /* 89 */ array(22, ),
        /* 90 */ array(19, ),
        /* 91 */ array(22, ),
        /* 92 */ array(22, ),
        /* 93 */ array(14, ),
        /* 94 */ array(19, ),
        /* 95 */ array(22, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(10, 13, 17, ),
        /* 99 */ array(10, 17, 23, ),
        /* 100 */ array(10, 17, 20, ),
        /* 101 */ array(10, 17, ),
        /* 102 */ array(10, 17, ),
        /* 103 */ array(13, 16, ),
        /* 104 */ array(10, 17, ),
        /* 105 */ array(3, 22, ),
        /* 106 */ array(16, 18, ),
        /* 107 */ array(10, ),
        /* 108 */ array(24, ),
        /* 109 */ array(3, ),
        /* 110 */ array(24, ),
        /* 111 */ array(3, ),
        /* 112 */ array(24, ),
        /* 113 */ array(41, ),
        /* 114 */ array(24, ),
        /* 115 */ array(11, ),
        /* 116 */ array(24, ),
        /* 117 */ array(11, ),
        /* 118 */ array(24, ),
        /* 119 */ array(14, ),
        /* 120 */ array(17, ),
        /* 121 */ array(3, ),
        /* 122 */ array(24, ),
        /* 123 */ array(42, ),
        /* 124 */ array(3, ),
        /* 125 */ array(20, ),
        /* 126 */ array(24, ),
        /* 127 */ array(11, ),
        /* 128 */ array(3, ),
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
 /*     0 */   334,  334,  334,  334,  334,  334,  320,  296,  334,  296,
 /*    10 */   296,  296,  334,  334,  334,  334,  334,  334,  334,  334,
 /*    20 */   334,  334,  334,  334,  334,  334,  334,  334,  334,  334,
 /*    30 */   242,  269,  274,  242,  242,  334,  304,  215,  304,  278,
 /*    40 */   278,  334,  278,  334,  334,  334,  334,  334,  334,  334,
 /*    50 */   334,  334,  334,  265,  242,  264,  334,  334,  334,  334,
 /*    60 */   300,  248,  280,  334,  334,  334,  334,  334,  295,  321,
 /*    70 */   334,  290,  334,  306,  334,  302,  334,  334,  334,  334,
 /*    80 */   334,  322,  249,  243,  278,  305,  278,  334,  323,  270,
 /*    90 */   238,  266,  287,  334,  246,  267,  299,  299,  334,  247,
 /*   100 */   247,  334,  247,  334,  279,  334,  334,  268,  334,  334,
 /*   110 */   334,  334,  334,  334,  334,  334,  334,  334,  334,  334,
 /*   120 */   334,  334,  334,  334,  334,  334,  334,  334,  334,  239,
 /*   130 */   236,  313,  245,  233,  231,  235,  216,  234,  314,  232,
 /*   140 */   307,  244,  308,  309,  310,  301,  237,  303,  316,  315,
 /*   150 */   312,  311,  293,  258,  259,  257,  252,  298,  251,  260,
 /*   160 */   261,  254,  271,  289,  253,  262,  277,  240,  227,  332,
 /*   170 */   333,  330,  219,  217,  218,  331,  220,  225,  226,  224,
 /*   180 */   223,  221,  222,  272,  255,  324,  326,  283,  286,  284,
 /*   190 */   285,  327,  328,  228,  229,  241,  325,  329,  263,  275,
 /*   200 */   288,  297,  317,  294,  276,  256,  250,  319,  318,  281,
 /*   210 */   291,  282,  273,  292,  230,
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
    const YYNOCODE = 93;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 215;
    const YYNRULE = 119;
    const YYERRORSYMBOL = 53;
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
    1,  /*       RDEL => OTHER */
    1,  /* COMMENTSTART => OTHER */
    1,  /* COMMENTEND => OTHER */
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
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*   BACKTICK => OTHER */
    1,  /*      HATCH => OTHER */
    1,  /*         AT => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*        PHP => nothing */
    0,  /*   PHPSTART => nothing */
    0,  /*     PHPEND => nothing */
    0,  /*        XML => nothing */
    0,  /*       LDEL => nothing */
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
  '$',             'OTHER',         'LDELSLASH',     'RDEL',        
  'COMMENTSTART',  'COMMENTEND',    'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'EQUALS',        'NOTEQUALS',     'GREATERTHAN', 
  'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',    
  'NONEIDENTITY',  'NOT',           'LAND',          'LOR',         
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'IN',          
  'ANDSYM',        'BACKTICK',      'HATCH',         'AT',          
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'XML',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'modifier',      'modparameters',
  'ifexprs',       'statements',    'varvar',        'foraction',   
  'variable',      'array',         'attribute',     'exprs',       
  'value',         'math',          'function',      'doublequoted',
  'method',        'params',        'objectchain',   'vararraydefs',
  'object',        'vararraydef',   'varvarele',     'objectelement',
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'doublequotedcontent',  'textelement', 
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
 /*  10 */ "template_element ::= XML",
 /*  11 */ "template_element ::= OTHER",
 /*  12 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  13 */ "smartytag ::= LDEL statement RDEL",
 /*  14 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  15 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  16 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  17 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  18 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  19 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  20 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  21 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  22 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  23 */ "foraction ::= EQUAL expr",
 /*  24 */ "foraction ::= INCDEC",
 /*  25 */ "attributes ::= attributes attribute",
 /*  26 */ "attributes ::= attribute",
 /*  27 */ "attributes ::=",
 /*  28 */ "attribute ::= SPACE ID EQUAL expr",
 /*  29 */ "statements ::= statement",
 /*  30 */ "statements ::= statements COMMA statement",
 /*  31 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  32 */ "expr ::= ID",
 /*  33 */ "expr ::= exprs",
 /*  34 */ "expr ::= expr modifier modparameters",
 /*  35 */ "expr ::= array",
 /*  36 */ "exprs ::= value",
 /*  37 */ "exprs ::= UNIMATH value",
 /*  38 */ "exprs ::= exprs math value",
 /*  39 */ "exprs ::= exprs ANDSYM value",
 /*  40 */ "math ::= UNIMATH",
 /*  41 */ "math ::= MATH",
 /*  42 */ "value ::= variable",
 /*  43 */ "value ::= NUMBER",
 /*  44 */ "value ::= function",
 /*  45 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  46 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  47 */ "value ::= QUOTE doublequoted QUOTE",
 /*  48 */ "value ::= QUOTE QUOTE",
 /*  49 */ "value ::= ID COLON COLON method",
 /*  50 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP",
 /*  51 */ "value ::= ID COLON COLON method objectchain",
 /*  52 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  53 */ "value ::= ID COLON COLON ID",
 /*  54 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  55 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
 /*  56 */ "value ::= HATCH ID HATCH",
 /*  57 */ "value ::= BOOLEAN",
 /*  58 */ "value ::= OPENP expr CLOSEP",
 /*  59 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  60 */ "variable ::= DOLLAR varvar AT ID",
 /*  61 */ "variable ::= object",
 /*  62 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  63 */ "vararraydefs ::=",
 /*  64 */ "vararraydef ::= DOT ID",
 /*  65 */ "vararraydef ::= DOT exprs",
 /*  66 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  67 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  68 */ "varvar ::= varvarele",
 /*  69 */ "varvar ::= varvar varvarele",
 /*  70 */ "varvarele ::= ID",
 /*  71 */ "varvarele ::= LDEL expr RDEL",
 /*  72 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  73 */ "objectchain ::= objectelement",
 /*  74 */ "objectchain ::= objectchain objectelement",
 /*  75 */ "objectelement ::= PTR ID vararraydefs",
 /*  76 */ "objectelement ::= PTR method",
 /*  77 */ "function ::= ID OPENP params CLOSEP",
 /*  78 */ "method ::= ID OPENP params CLOSEP",
 /*  79 */ "params ::= expr COMMA params",
 /*  80 */ "params ::= expr",
 /*  81 */ "params ::=",
 /*  82 */ "modifier ::= VERT ID",
 /*  83 */ "modparameters ::= modparameters modparameter",
 /*  84 */ "modparameters ::=",
 /*  85 */ "modparameter ::= COLON exprs",
 /*  86 */ "ifexprs ::= ifexpr",
 /*  87 */ "ifexprs ::= NOT ifexprs",
 /*  88 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  89 */ "ifexpr ::= expr",
 /*  90 */ "ifexpr ::= expr ifcond expr",
 /*  91 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  92 */ "ifcond ::= EQUALS",
 /*  93 */ "ifcond ::= NOTEQUALS",
 /*  94 */ "ifcond ::= GREATERTHAN",
 /*  95 */ "ifcond ::= LESSTHAN",
 /*  96 */ "ifcond ::= GREATEREQUAL",
 /*  97 */ "ifcond ::= LESSEQUAL",
 /*  98 */ "ifcond ::= IDENTITY",
 /*  99 */ "ifcond ::= NONEIDENTITY",
 /* 100 */ "lop ::= LAND",
 /* 101 */ "lop ::= LOR",
 /* 102 */ "array ::= OPENB arrayelements CLOSEB",
 /* 103 */ "arrayelements ::= arrayelement",
 /* 104 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 105 */ "arrayelements ::=",
 /* 106 */ "arrayelement ::= expr",
 /* 107 */ "arrayelement ::= expr APTR expr",
 /* 108 */ "arrayelement ::= ID APTR expr",
 /* 109 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 110 */ "doublequoted ::= doublequotedcontent",
 /* 111 */ "doublequotedcontent ::= variable",
 /* 112 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 113 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 114 */ "doublequotedcontent ::= OTHER",
 /* 115 */ "text ::= text textelement",
 /* 116 */ "text ::= textelement",
 /* 117 */ "textelement ::= OTHER",
 /* 118 */ "textelement ::= LDEL",
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
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 11 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 0 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 8 ),
  array( 'lhs' => 72, 'rhs' => 5 ),
  array( 'lhs' => 72, 'rhs' => 9 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 6 ),
  array( 'lhs' => 72, 'rhs' => 7 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 0 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 0 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 0 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        32 => 0,
        36 => 0,
        42 => 0,
        43 => 0,
        44 => 0,
        57 => 0,
        61 => 0,
        103 => 0,
        1 => 1,
        33 => 1,
        35 => 1,
        40 => 1,
        41 => 1,
        68 => 1,
        86 => 1,
        110 => 1,
        116 => 1,
        117 => 1,
        118 => 1,
        2 => 2,
        62 => 2,
        109 => 2,
        115 => 2,
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
        22 => 21,
        23 => 23,
        24 => 24,
        26 => 24,
        80 => 24,
        106 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        34 => 34,
        37 => 37,
        38 => 38,
        39 => 39,
        45 => 45,
        47 => 45,
        46 => 46,
        48 => 46,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        58 => 58,
        59 => 59,
        60 => 60,
        63 => 63,
        84 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        69 => 69,
        70 => 70,
        71 => 71,
        88 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        81 => 81,
        82 => 82,
        83 => 83,
        85 => 85,
        87 => 87,
        89 => 89,
        90 => 90,
        91 => 90,
        92 => 92,
        93 => 93,
        94 => 94,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        99 => 99,
        100 => 100,
        101 => 101,
        102 => 102,
        104 => 104,
        105 => 105,
        107 => 107,
        108 => 108,
        111 => 111,
        112 => 112,
        113 => 113,
        114 => 114,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1569 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1572 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1575 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1581 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1584 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1587 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1590 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1593 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1599 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1605 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1608 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1611 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1614 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1617 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1620 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1623 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
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
#line 1638 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1641 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1644 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1647 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1650 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1653 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1656 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1659 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1662 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1665 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1668 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1671 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1674 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1677 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r34(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
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
#line 1691 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1694 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1697 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1700 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1703 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "''";     }
#line 1706 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1709 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r50(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1712 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1715 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1718 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1721 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1724 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1727 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1730 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1733 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r59(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1737 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1740 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r63(){return;    }
#line 1743 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1746 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1749 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1752 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1755 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1758 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1761 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1764 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1767 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1770 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1773 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1776 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1779 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r77(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1788 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1791 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1794 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r81(){ return;    }
#line 1797 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1800 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1803 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1806 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1809 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1812 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1815 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '==';    }
#line 1818 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '!=';    }
#line 1821 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '>';    }
#line 1824 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '<';    }
#line 1827 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '>=';    }
#line 1830 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '<=';    }
#line 1833 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '===';    }
#line 1836 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '!==';    }
#line 1839 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '&&';    }
#line 1842 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '||';    }
#line 1845 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r102(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1848 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r104(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1851 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r105(){ return;     }
#line 1854 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1857 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r108(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1860 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1863 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1866 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1869 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1872 "internal.templateparser.php"

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
#line 1989 "internal.templateparser.php"
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
#line 2014 "internal.templateparser.php"
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
