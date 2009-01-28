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
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 140 "internal.templateparser.php"

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
    const TP_SI_QSTR                        = 25;
    const TP_EQUALS                         = 26;
    const TP_NOTEQUALS                      = 27;
    const TP_GREATERTHAN                    = 28;
    const TP_LESSTHAN                       = 29;
    const TP_GREATEREQUAL                   = 30;
    const TP_LESSEQUAL                      = 31;
    const TP_IDENTITY                       = 32;
    const TP_NONEIDENTITY                   = 33;
    const TP_NOT                            = 34;
    const TP_LAND                           = 35;
    const TP_LOR                            = 36;
    const TP_QUOTE                          = 37;
    const TP_BOOLEAN                        = 38;
    const TP_IN                             = 39;
    const TP_ANDSYM                         = 40;
    const TP_UNDERL                         = 41;
    const TP_BACKTICK                       = 42;
    const TP_LITERALSTART                   = 43;
    const TP_LITERALEND                     = 44;
    const TP_LDELIMTAG                      = 45;
    const TP_RDELIMTAG                      = 46;
    const TP_PHP                            = 47;
    const TP_LDEL                           = 48;
    const YY_NO_ACTION = 305;
    const YY_ACCEPT_ACTION = 304;
    const YY_ERROR_ACTION = 303;

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
    const YY_SZ_ACTTAB = 505;
static public $yy_action = array(
 /*     0 */   140,  146,  168,   18,  177,  172,   12,    7,  110,  290,
 /*    10 */   106,   20,  120,  137,  107,  160,   13,   15,   39,  178,
 /*    20 */   182,  181,  180,  179,  194,  145,  123,  122,  125,   36,
 /*    30 */    50,  129,  114,   23,   59,   95,  122,  125,  116,   17,
 /*    40 */   127,  138,   87,  116,  131,   35,   49,   86,  191,  131,
 /*    50 */    65,  155,   19,  191,  116,  117,  127,  126,   87,  176,
 /*    60 */   131,  191,  140,  146,  191,   20,  140,  146,   16,  160,
 /*    70 */   177,  117,  111,  154,   93,  167,  304,   37,  135,  133,
 /*    80 */    45,  178,  182,  181,  180,  179,  194,  145,  123,   36,
 /*    90 */     7,  122,  125,   17,   75,   23,  155,  107,  116,   23,
 /*   100 */   127,  138,   87,   31,  131,  132,  191,   24,  191,    1,
 /*   110 */   144,  140,  146,   39,   99,  117,   52,  165,  188,  140,
 /*   120 */   146,  140,  146,   94,  156,  116,  100,  127,  126,   87,
 /*   130 */    10,  131,   88,    3,    5,  191,   26,  124,   36,  140,
 /*   140 */   146,  148,   90,   70,   23,   93,  167,  116,   43,  127,
 /*   150 */   138,   87,   23,  131,   23,   13,   36,  191,  132,  143,
 /*   160 */    24,   78,    1,  148,  117,  116,   40,  127,  138,   87,
 /*   170 */    22,  131,   23,  121,  160,  191,   92,  156,   33,   60,
 /*   180 */   164,  161,  117,  132,  173,   24,    3,    6,  119,   26,
 /*   190 */   124,   39,  192,  109,   18,   46,   18,   12,   17,   12,
 /*   200 */   132,   94,  156,  148,   14,  166,   82,  128,   39,  132,
 /*   210 */   119,   24,  118,    6,   26,  124,   80,   41,   94,  156,
 /*   220 */   116,  140,  146,   48,   89,  187,  131,   29,  156,   25,
 /*   230 */   191,   26,  124,   56,   44,  111,  174,  175,  149,    8,
 /*   240 */    26,  124,  116,  193,  127,  138,   87,   32,  131,  104,
 /*   250 */   119,  102,  191,  184,   23,   16,  116,   97,  127,  138,
 /*   260 */    87,  111,  131,  171,  161,   56,  191,  140,  146,  116,
 /*   270 */   157,  111,   96,   83,  116,  131,  127,  138,   87,  191,
 /*   280 */   131,   77,  151,   56,  191,  189,  140,  146,  111,  163,
 /*   290 */   187,  101,  116,   81,  127,  138,   87,   53,  131,  185,
 /*   300 */    23,  103,  191,  111,   11,  121,  116,  115,  127,  126,
 /*   310 */    87,  136,  131,   67,   47,   28,  191,  140,  146,   23,
 /*   320 */   160,  177,  116,   71,  127,  138,   87,  152,  131,  150,
 /*   330 */    64,   51,  191,   13,  159,   18,   84,  169,   12,  116,
 /*   340 */   110,  127,  138,   87,   17,  131,  112,  160,   66,  191,
 /*   350 */    23,  134,  133,   61,  122,  125,  118,  116,   15,  127,
 /*   360 */   138,   87,  116,  131,  127,  138,   87,  191,  131,   63,
 /*   370 */   162,   17,  191,   76,    9,  164,   18,  190,  116,   12,
 /*   380 */   127,  138,   87,   62,  131,  142,   25,   38,  191,  130,
 /*   390 */   160,   79,  116,   91,  127,  138,   87,   55,  131,   21,
 /*   400 */   187,  150,  191,   73,   85,  164,  116,  105,  127,  138,
 /*   410 */    87,   74,  131,   54,   17,  158,  191,   72,   58,  164,
 /*   420 */   187,   82,  116,    9,  127,  138,   87,  116,  131,  127,
 /*   430 */   138,   87,  191,  131,   68,   34,  186,  191,  183,   57,
 /*   440 */    25,  161,   39,  116,    4,  127,  138,   87,  116,  131,
 /*   450 */   127,  138,   87,  191,  131,  219,   18,  172,  191,   12,
 /*   460 */   153,  110,    7,   27,   20,   30,   69,  141,  160,  107,
 /*   470 */    39,   99,   98,    2,  108,  113,  111,  150,   42,  150,
 /*   480 */   150,  159,  191,  198,  139,  191,  170,  198,  198,  198,
 /*   490 */   198,  198,   17,  147,  198,  198,  198,  198,   49,  198,
 /*   500 */   198,  198,  198,  198,   19,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   24,   12,   11,    1,   15,   10,   17,   16,
 /*    10 */    18,   20,   11,    3,   17,   24,   23,   20,   14,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   33,   35,   36,   55,
 /*    30 */    39,   57,   22,   40,   60,   61,   35,   36,   64,   48,
 /*    40 */    66,   67,   68,   64,   70,   55,   42,   68,   74,   70,
 /*    50 */    60,   64,   48,   74,   64,   81,   66,   67,   68,    3,
 /*    60 */    70,   74,    7,    8,   74,   20,    7,    8,   17,   24,
 /*    70 */    11,   81,   21,   86,   84,   85,   50,   51,   52,   53,
 /*    80 */    14,   26,   27,   28,   29,   30,   31,   32,   33,   55,
 /*    90 */    10,   35,   36,   48,   60,   40,   64,   17,   64,   40,
 /*   100 */    66,   67,   68,   71,   70,    6,   74,    8,   74,   10,
 /*   110 */    57,    7,    8,   14,   19,   81,   55,   13,   86,    7,
 /*   120 */     8,    7,    8,   24,   25,   64,   24,   66,   67,   68,
 /*   130 */    16,   70,   16,   34,   18,   74,   37,   38,   55,    7,
 /*   140 */     8,    1,   58,   60,   40,   84,   85,   64,   14,   66,
 /*   150 */    67,   68,   40,   70,   40,   23,   55,   74,    6,    9,
 /*   160 */     8,   60,   10,    1,   81,   64,   14,   66,   67,   68,
 /*   170 */    20,   70,   40,   80,   24,   74,   24,   25,   73,   54,
 /*   180 */    75,   76,   81,    6,   44,    8,   34,   10,   48,   37,
 /*   190 */    38,   14,    1,    2,   12,    4,   12,   15,   48,   15,
 /*   200 */     6,   24,   25,    1,   10,   78,   22,    5,   14,    6,
 /*   210 */    48,    8,   87,   10,   37,   38,   56,   14,   24,   25,
 /*   220 */    64,    7,    8,   24,   68,   65,   70,   24,   25,   69,
 /*   230 */    74,   37,   38,   55,   43,   21,   45,   46,   47,   48,
 /*   240 */    37,   38,   64,    3,   66,   67,   68,   55,   70,   57,
 /*   250 */    48,   63,   74,    3,   40,   17,   64,   79,   66,   67,
 /*   260 */    68,   21,   70,    3,   76,   55,   74,    7,    8,   64,
 /*   270 */     3,   21,   24,   68,   64,   70,   66,   67,   68,   74,
 /*   280 */    70,   56,    3,   55,   74,    3,    7,    8,   21,   79,
 /*   290 */    65,   14,   64,   17,   66,   67,   68,   55,   70,   11,
 /*   300 */    40,   24,   74,   21,   16,   80,   64,   79,   66,   67,
 /*   310 */    68,    3,   70,   55,   24,   62,   74,    7,    8,   40,
 /*   320 */    24,   11,   64,   54,   66,   67,   68,   85,   70,   76,
 /*   330 */    55,   24,   74,   23,   75,   12,   77,   78,   15,   64,
 /*   340 */    17,   66,   67,   68,   48,   70,   24,   24,   55,   74,
 /*   350 */    40,   52,   53,   55,   35,   36,   87,   64,   20,   66,
 /*   360 */    67,   68,   64,   70,   66,   67,   68,   74,   70,   55,
 /*   370 */    11,   48,   74,   73,   10,   75,   12,   24,   64,   15,
 /*   380 */    66,   67,   68,   55,   70,    3,   69,   62,   74,    3,
 /*   390 */    24,   56,   64,   58,   66,   67,   68,   55,   70,   82,
 /*   400 */    65,   76,   74,   73,   59,   75,   64,   41,   66,   67,
 /*   410 */    68,   56,   70,   55,   48,   42,   74,   73,   55,   75,
 /*   420 */    65,   22,   64,   10,   66,   67,   68,   64,   70,   66,
 /*   430 */    67,   68,   74,   70,   55,   59,    3,   74,   11,   55,
 /*   440 */    69,   76,   14,   64,   83,   66,   67,   68,   64,   70,
 /*   450 */    66,   67,   68,   74,   70,    3,   12,    1,   74,   15,
 /*   460 */    65,   17,   10,   62,   20,   62,   62,   87,   24,   17,
 /*   470 */    14,   19,   64,   21,   22,   64,   21,   76,   24,   76,
 /*   480 */    76,   75,   74,   88,   72,   74,   72,   88,   88,   88,
 /*   490 */    88,   88,   48,   37,   88,   88,   88,   88,   42,   88,
 /*   500 */    88,   88,   88,   88,   48,
);
    const YY_SHIFT_USE_DFLT = -23;
    const YY_SHIFT_MAX = 115;
    static public $yy_shift_ofst = array(
 /*     0 */   191,   99,  152,   99,   99,   99,  177,  177,  203,  177,
 /*    10 */   177,  177,  177,  177,  177,  177,  177,  177,  177,  177,
 /*    20 */   177,  177,  177,  194,  194,  194,    4,   -9,  444,  452,
 /*    30 */   323,  456,  214,  184,   51,   -7,   55,  191,  150,  366,
 /*    40 */   366,  366,  364,  296,  162,  296,  162,  182,  182,  428,
 /*    50 */   428,  455,  310,  132,   59,  279,  114,  104,  260,   56,
 /*    60 */   140,  112,  112,  112,  112,    1,  112,  112,  112,   45,
 /*    70 */    -8,  202,  182,  182,  240,  319,  182,  250,  319,  282,
 /*    80 */   267,  277,  454,   95,  399,  238,   95,   95,   66,   95,
 /*    90 */   -23,  -23,   -3,  288,   80,  116,   10,  427,  373,  353,
 /*   100 */   338,  290,  382,  413,  433,  199,  134,  276,  307,  248,
 /*   110 */   -22,  102,  308,  386,  322,  359,
);
    const YY_REDUCE_USE_DFLT = -27;
    const YY_REDUCE_MAX = 91;
    static public $yy_reduce_ofst = array(
 /*     0 */    26,  -10,  -26,  101,   34,   83,   61,  228,  192,  178,
 /*    10 */   210,  242,  275,  298,  358,  314,  328,  342,  384,  363,
 /*    20 */   379,  293,  258,  -21,  205,  156,   32,  105,  105,  335,
 /*    30 */   105,  -13,  160,  259,  225,  317,  317,  299,  188,  403,
 /*    40 */   401,  253,  330,  325,  125,  404,  269,  300,  344,  408,
 /*    50 */   411,  355,  371,  371,  371,  371,  371,  371,  371,  361,
 /*    60 */   380,  371,  371,  371,  371,  361,  371,  371,  371,  365,
 /*    70 */   361,  380,  406,  406,  395,  361,  406,  395,  361,  395,
 /*    80 */   395,  412,  414,   84,  127,   93,   84,   84,   53,   84,
 /*    90 */   345,  376,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 43, 45, 46, 47, 48, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 34, 37, 38, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 34, 37, 38, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 34, 37, 38, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 34, 37, 38, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 34, 37, 38, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 9 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 10 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 13 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 14 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 15 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 16 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 17 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 18 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 19 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 20 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 21 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 22 */ array(6, 8, 10, 14, 24, 25, 37, 38, ),
        /* 23 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 24 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 25 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 26 */ array(1, 14, 42, 48, ),
        /* 27 */ array(12, 15, 17, 20, 24, 39, 48, ),
        /* 28 */ array(12, 15, 17, 20, 24, 48, ),
        /* 29 */ array(3, 10, 17, 19, 21, 22, ),
        /* 30 */ array(12, 15, 17, 24, 48, ),
        /* 31 */ array(1, 14, 37, 42, 48, ),
        /* 32 */ array(7, 8, 21, 40, ),
        /* 33 */ array(12, 15, 22, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 36 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 37 */ array(1, 2, 4, 43, 45, 46, 47, 48, ),
        /* 38 */ array(9, 20, 24, 48, ),
        /* 39 */ array(24, 41, 48, ),
        /* 40 */ array(24, 41, 48, ),
        /* 41 */ array(24, 41, 48, ),
        /* 42 */ array(10, 12, 15, ),
        /* 43 */ array(24, 48, ),
        /* 44 */ array(1, 48, ),
        /* 45 */ array(24, 48, ),
        /* 46 */ array(1, 48, ),
        /* 47 */ array(12, 15, ),
        /* 48 */ array(12, 15, ),
        /* 49 */ array(14, ),
        /* 50 */ array(14, ),
        /* 51 */ array(21, ),
        /* 52 */ array(7, 8, 11, 23, 40, ),
        /* 53 */ array(7, 8, 23, 40, ),
        /* 54 */ array(7, 8, 11, 40, ),
        /* 55 */ array(3, 7, 8, 40, ),
        /* 56 */ array(7, 8, 16, 40, ),
        /* 57 */ array(7, 8, 13, 40, ),
        /* 58 */ array(3, 7, 8, 40, ),
        /* 59 */ array(3, 35, 36, ),
        /* 60 */ array(1, 44, 48, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(7, 8, 40, ),
        /* 63 */ array(7, 8, 40, ),
        /* 64 */ array(7, 8, 40, ),
        /* 65 */ array(11, 35, 36, ),
        /* 66 */ array(7, 8, 40, ),
        /* 67 */ array(7, 8, 40, ),
        /* 68 */ array(7, 8, 40, ),
        /* 69 */ array(20, 24, 48, ),
        /* 70 */ array(18, 35, 36, ),
        /* 71 */ array(1, 5, 48, ),
        /* 72 */ array(12, 15, ),
        /* 73 */ array(12, 15, ),
        /* 74 */ array(3, 21, ),
        /* 75 */ array(35, 36, ),
        /* 76 */ array(12, 15, ),
        /* 77 */ array(3, 21, ),
        /* 78 */ array(35, 36, ),
        /* 79 */ array(3, 21, ),
        /* 80 */ array(3, 21, ),
        /* 81 */ array(14, 24, ),
        /* 82 */ array(24, ),
        /* 83 */ array(19, ),
        /* 84 */ array(22, ),
        /* 85 */ array(17, ),
        /* 86 */ array(19, ),
        /* 87 */ array(19, ),
        /* 88 */ array(14, ),
        /* 89 */ array(19, ),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(10, 17, 20, ),
        /* 93 */ array(11, 16, ),
        /* 94 */ array(10, 17, ),
        /* 95 */ array(16, 18, ),
        /* 96 */ array(3, 22, ),
        /* 97 */ array(11, ),
        /* 98 */ array(42, ),
        /* 99 */ array(24, ),
        /* 100 */ array(20, ),
        /* 101 */ array(24, ),
        /* 102 */ array(3, ),
        /* 103 */ array(10, ),
        /* 104 */ array(3, ),
        /* 105 */ array(24, ),
        /* 106 */ array(14, ),
        /* 107 */ array(17, ),
        /* 108 */ array(24, ),
        /* 109 */ array(24, ),
        /* 110 */ array(24, ),
        /* 111 */ array(24, ),
        /* 112 */ array(3, ),
        /* 113 */ array(3, ),
        /* 114 */ array(24, ),
        /* 115 */ array(11, ),
        /* 116 */ array(),
        /* 117 */ array(),
        /* 118 */ array(),
        /* 119 */ array(),
        /* 120 */ array(),
        /* 121 */ array(),
        /* 122 */ array(),
        /* 123 */ array(),
        /* 124 */ array(),
        /* 125 */ array(),
        /* 126 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   303,  303,  303,  303,  303,  303,  303,  266,  303,  266,
 /*    10 */   266,  303,  303,  303,  303,  303,  303,  303,  303,  303,
 /*    20 */   303,  303,  303,  303,  303,  303,  303,  250,  250,  241,
 /*    30 */   250,  303,  219,  244,  219,  274,  274,  195,  303,  303,
 /*    40 */   303,  303,  250,  303,  303,  303,  303,  250,  250,  303,
 /*    50 */   303,  219,  290,  290,  303,  303,  265,  303,  303,  303,
 /*    60 */   303,  291,  270,  220,  251,  303,  275,  215,  223,  303,
 /*    70 */   303,  303,  246,  260,  303,  276,  240,  303,  272,  303,
 /*    80 */   303,  303,  303,  227,  257,  232,  229,  226,  303,  228,
 /*    90 */   269,  269,  241,  303,  241,  303,  303,  303,  303,  303,
 /*   100 */   303,  303,  303,  239,  303,  303,  303,  303,  303,  303,
 /*   110 */   303,  303,  303,  303,  303,  303,  233,  271,  300,  302,
 /*   120 */   273,  268,  285,  284,  242,  286,  225,  224,  199,  221,
 /*   130 */   214,  235,  234,  198,  197,  196,  211,  210,  225,  238,
 /*   140 */   231,  299,  213,  216,  222,  283,  230,  237,  301,  203,
 /*   150 */   253,  256,  289,  217,  293,  295,  236,  205,  296,  249,
 /*   160 */   255,  254,  262,  264,  248,  252,  259,  288,  245,  258,
 /*   170 */   261,  297,  298,  200,  201,  202,  212,  243,  277,  281,
 /*   180 */   280,  279,  278,  263,  209,  287,  206,  218,  294,  207,
 /*   190 */   267,  247,  204,  208,  282,
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
    const YYNOCODE = 89;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 195;
    const YYNRULE = 108;
    const YYERRORSYMBOL = 49;
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
    1,  /*    SI_QSTR => OTHER */
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
    1,  /*    BOOLEAN => OTHER */
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*     UNDERL => OTHER */
    1,  /*   BACKTICK => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*        PHP => nothing */
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
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NONEIDENTITY',  'NOT',           'LAND',        
  'LOR',           'QUOTE',         'BOOLEAN',       'IN',          
  'ANDSYM',        'UNDERL',        'BACKTICK',      'LITERALSTART',
  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',     'PHP',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'modifier',      'modparameters',
  'ifexprs',       'statements',    'varvar',        'foraction',   
  'variable',      'attribute',     'exprs',         'array',       
  'value',         'math',          'function',      'doublequoted',
  'method',        'vararraydefs',  'object',        'vararraydef', 
  'varvarele',     'objectchain',   'objectelement',  'params',      
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
 /*   9 */ "template_element ::= OTHER",
 /*  10 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  11 */ "smartytag ::= LDEL statement RDEL",
 /*  12 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  13 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  14 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  15 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  16 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  17 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  18 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  19 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  20 */ "foraction ::= EQUAL expr",
 /*  21 */ "foraction ::= INCDEC",
 /*  22 */ "attributes ::= attributes attribute",
 /*  23 */ "attributes ::= attribute",
 /*  24 */ "attributes ::=",
 /*  25 */ "attribute ::= SPACE ID EQUAL expr",
 /*  26 */ "statements ::= statement",
 /*  27 */ "statements ::= statements COMMA statement",
 /*  28 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  29 */ "expr ::= exprs",
 /*  30 */ "expr ::= array",
 /*  31 */ "exprs ::= value",
 /*  32 */ "exprs ::= UNIMATH value",
 /*  33 */ "exprs ::= expr math value",
 /*  34 */ "exprs ::= expr ANDSYM value",
 /*  35 */ "math ::= UNIMATH",
 /*  36 */ "math ::= MATH",
 /*  37 */ "value ::= value modifier modparameters",
 /*  38 */ "value ::= variable",
 /*  39 */ "value ::= NUMBER",
 /*  40 */ "value ::= function",
 /*  41 */ "value ::= SI_QSTR",
 /*  42 */ "value ::= QUOTE doublequoted QUOTE",
 /*  43 */ "value ::= ID COLON COLON method",
 /*  44 */ "value ::= ID COLON COLON ID",
 /*  45 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  46 */ "value ::= ID",
 /*  47 */ "value ::= BOOLEAN",
 /*  48 */ "value ::= OPENP expr CLOSEP",
 /*  49 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  50 */ "variable ::= DOLLAR varvar COLON ID",
 /*  51 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  52 */ "variable ::= object",
 /*  53 */ "vararraydefs ::= vararraydef",
 /*  54 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  55 */ "vararraydefs ::=",
 /*  56 */ "vararraydef ::= DOT expr",
 /*  57 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  58 */ "varvar ::= varvarele",
 /*  59 */ "varvar ::= varvar varvarele",
 /*  60 */ "varvarele ::= ID",
 /*  61 */ "varvarele ::= LDEL expr RDEL",
 /*  62 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  63 */ "objectchain ::= objectelement",
 /*  64 */ "objectchain ::= objectchain objectelement",
 /*  65 */ "objectelement ::= PTR ID vararraydefs",
 /*  66 */ "objectelement ::= PTR method",
 /*  67 */ "function ::= ID OPENP params CLOSEP",
 /*  68 */ "method ::= ID OPENP params CLOSEP",
 /*  69 */ "params ::= expr COMMA params",
 /*  70 */ "params ::= expr",
 /*  71 */ "params ::=",
 /*  72 */ "modifier ::= VERT ID",
 /*  73 */ "modparameters ::= modparameters modparameter",
 /*  74 */ "modparameters ::=",
 /*  75 */ "modparameter ::= COLON expr",
 /*  76 */ "ifexprs ::= ifexpr",
 /*  77 */ "ifexprs ::= NOT ifexprs",
 /*  78 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  79 */ "ifexpr ::= expr",
 /*  80 */ "ifexpr ::= expr ifcond expr",
 /*  81 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  82 */ "ifcond ::= EQUALS",
 /*  83 */ "ifcond ::= NOTEQUALS",
 /*  84 */ "ifcond ::= GREATERTHAN",
 /*  85 */ "ifcond ::= LESSTHAN",
 /*  86 */ "ifcond ::= GREATEREQUAL",
 /*  87 */ "ifcond ::= LESSEQUAL",
 /*  88 */ "ifcond ::= IDENTITY",
 /*  89 */ "ifcond ::= NONEIDENTITY",
 /*  90 */ "lop ::= LAND",
 /*  91 */ "lop ::= LOR",
 /*  92 */ "array ::= OPENP arrayelements CLOSEP",
 /*  93 */ "arrayelements ::= arrayelement",
 /*  94 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  95 */ "arrayelement ::= expr",
 /*  96 */ "arrayelement ::= expr APTR expr",
 /*  97 */ "arrayelement ::= array",
 /*  98 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  99 */ "doublequoted ::= doublequotedcontent",
 /* 100 */ "doublequotedcontent ::= variable",
 /* 101 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 102 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 103 */ "doublequotedcontent ::= OTHER",
 /* 104 */ "text ::= text textelement",
 /* 105 */ "text ::= textelement",
 /* 106 */ "textelement ::= OTHER",
 /* 107 */ "textelement ::= LDEL",
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
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 53, 'rhs' => 6 ),
  array( 'lhs' => 53, 'rhs' => 6 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 5 ),
  array( 'lhs' => 53, 'rhs' => 5 ),
  array( 'lhs' => 53, 'rhs' => 11 ),
  array( 'lhs' => 53, 'rhs' => 8 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 0 ),
  array( 'lhs' => 65, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 6 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 0 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        31 => 0,
        38 => 0,
        39 => 0,
        40 => 0,
        41 => 0,
        47 => 0,
        52 => 0,
        93 => 0,
        1 => 1,
        29 => 1,
        30 => 1,
        35 => 1,
        36 => 1,
        53 => 1,
        58 => 1,
        76 => 1,
        99 => 1,
        103 => 1,
        105 => 1,
        106 => 1,
        107 => 1,
        2 => 2,
        54 => 2,
        98 => 2,
        104 => 2,
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
        23 => 21,
        70 => 21,
        95 => 21,
        97 => 21,
        22 => 22,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        32 => 32,
        33 => 33,
        34 => 34,
        37 => 37,
        42 => 42,
        43 => 43,
        44 => 44,
        45 => 45,
        46 => 46,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        55 => 55,
        74 => 55,
        56 => 56,
        57 => 57,
        59 => 59,
        60 => 60,
        61 => 61,
        78 => 61,
        62 => 62,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        75 => 75,
        77 => 77,
        79 => 79,
        80 => 80,
        81 => 80,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        86 => 86,
        87 => 87,
        88 => 88,
        89 => 89,
        90 => 90,
        91 => 91,
        92 => 92,
        94 => 94,
        96 => 96,
        100 => 100,
        101 => 101,
        102 => 102,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1496 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1499 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1502 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1507 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1510 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1513 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1516 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1519 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1525 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1528 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1531 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1534 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1537 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1540 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
                                                                if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					                           $this->_retvalue .= $this->yystack[$this->yyidx + -3]->minor . "(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor .");?>";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -3]->minor,'modifier')) {
                                                                      $this->_retvalue .= "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -3]->minor . "(array(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor ."),'modifier');?>";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1555 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1558 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1561 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1564 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1567 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1570 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1573 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1576 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1579 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = array();    }
#line 1582 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1585 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1588 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1591 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1594 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1597 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1600 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1603 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r37(){if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                           $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
                                                                      $this->_retvalue = "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -1]->minor . "(array(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor ."),'modifier')";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . $this->yystack[$this->yyidx + -1]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1617 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1620 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1623 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1626 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1629 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1632 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1635 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1638 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1641 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1644 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r55(){return;    }
#line 1647 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1650 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1653 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1656 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1659 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1662 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1665 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1668 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1671 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1674 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1677 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r67(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1686 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1689 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1692 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r71(){ return;    }
#line 1695 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1698 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1701 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1704 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1707 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1710 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1713 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '==';    }
#line 1716 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '!=';    }
#line 1719 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '>';    }
#line 1722 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '<';    }
#line 1725 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '>=';    }
#line 1728 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '<=';    }
#line 1731 "internal.templateparser.php"
#line 335 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '===';    }
#line 1734 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '!==';    }
#line 1737 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '&&';    }
#line 1740 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '||';    }
#line 1743 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r92(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1746 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r94(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1749 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r96(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1752 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1755 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1758 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1761 "internal.templateparser.php"

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
#line 53 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1878 "internal.templateparser.php"
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
#line 45 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1903 "internal.templateparser.php"
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
