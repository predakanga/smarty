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
    const YY_NO_ACTION = 326;
    const YY_ACCEPT_ACTION = 325;
    const YY_ERROR_ACTION = 324;

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
    const YY_SZ_ACTTAB = 493;
static public $yy_action = array(
 /*     0 */   185,   70,   24,   84,    3,   37,    6,   55,   41,  150,
 /*    10 */    81,  206,   64,   89,  176,  175,  183,  192,   87,   36,
 /*    20 */   189,  166,  187,  149,  148,   21,   94,    2,  186,  163,
 /*    30 */    27,   40,  179,  122,  191,  185,  115,   24,  185,    3,
 /*    40 */    24,    6,   13,   47,    6,  235,   41,   25,   91,   74,
 /*    50 */    21,    5,    7,   85,  163,   76,   87,   20,  200,   92,
 /*    60 */   161,   94,    2,    1,   95,   27,   40,  179,   27,   40,
 /*    70 */   179,  115,  198,  185,  115,   24,   50,   13,  102,    6,
 /*    80 */   173,   42,   20,  130,  114,   41,   48,  176,  175,  163,
 /*    90 */   186,   28,   97,  180,  207,  137,  126,  123,  127,  128,
 /*   100 */   132,  131,   83,   27,   40,  179,   54,  196,   91,  115,
 /*   110 */   193,   62,   53,   26,   33,  183,  192,   20,   36,  189,
 /*   120 */    25,  187,   79,   16,  176,  175,   43,  186,  152,  151,
 /*   130 */   134,   45,  122,  155,   11,  185,  140,   24,    6,   13,
 /*   140 */    41,  133,  117,   41,  207,  137,  126,  123,  127,  128,
 /*   150 */   132,  131,  183,   87,  101,   56,  189,   25,  187,  325,
 /*   160 */    34,  125,  148,   55,  186,   27,   40,  179,   63,  162,
 /*   170 */   198,  115,  183,  192,  119,   36,  189,   77,  187,   73,
 /*   180 */   200,   55,  161,   41,  186,  147,   69,  135,  158,  122,
 /*   190 */   183,  192,  174,   36,  189,    9,  187,  133,  117,  108,
 /*   200 */    99,  140,  186,   23,    8,  204,   22,  122,  183,  192,
 /*   210 */    53,   36,  189,   79,  187,  157,  133,  117,  185,  140,
 /*   220 */   186,   16,   13,  141,  140,   55,   41,  183,   86,  171,
 /*   230 */    72,  160,  108,  187,  183,  192,   87,   36,  189,  186,
 /*   240 */   187,  183,  192,  159,   36,  189,  186,  187,   27,   40,
 /*   250 */   179,  122,  135,  186,  115,  183,  110,  177,   65,  189,
 /*   260 */   194,  187,  172,  195,  120,  183,  192,  186,   36,  189,
 /*   270 */   135,  187,    7,  136,   93,  135,  140,  186,   52,   92,
 /*   280 */    98,   96,   17,  106,   12,  110,  113,  183,  192,  140,
 /*   290 */    36,  189,  110,  187,  183,  192,   90,   36,  189,  186,
 /*   300 */   187,  183,  192,  169,   36,  189,  186,  187,  110,    7,
 /*   310 */   116,   30,  203,  186,  162,   68,   92,  183,  192,  124,
 /*   320 */    36,  189,  190,  187,  183,  206,  104,  135,  181,  186,
 /*   330 */   187,  183,  192,  144,   36,  189,  186,  187,  170,  191,
 /*   340 */   135,  133,  117,  186,   75,  107,   23,  183,  192,   22,
 /*   350 */    36,  189,  139,  187,  183,  192,  202,   36,  189,  186,
 /*   360 */   187,  183,  192,  164,   36,  189,  186,  187,  154,   88,
 /*   370 */    29,  109,  184,  186,  164,  186,   15,  183,  192,   51,
 /*   380 */    36,  189,  129,  187,  121,  197,  186,  105,  111,  186,
 /*   390 */    96,  183,  192,   18,   36,  189,  165,  187,  103,  186,
 /*   400 */    15,  112,   96,  186,   96,  100,   58,  183,  192,  163,
 /*   410 */    36,  189,  201,  187,  183,  192,  173,   36,  189,  186,
 /*   420 */   187,  183,  192,   61,   36,  189,  186,  187,   21,  153,
 /*   430 */   183,  156,  163,  186,  188,   71,  187,   20,   78,  138,
 /*   440 */    19,  199,  186,  161,  163,  206,   59,   32,   38,   96,
 /*   450 */    39,   91,   57,   60,   82,   35,  138,   66,   44,  161,
 /*   460 */    20,  205,  146,  182,  166,  143,  166,  206,  166,  167,
 /*   470 */    17,  166,   20,   26,  178,  200,  118,    4,  142,  138,
 /*   480 */    67,   96,   41,   31,  162,   14,  138,  168,   80,   10,
 /*   490 */    46,   49,  145,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   60,    8,   62,   10,   66,   12,   59,   14,   61,
 /*    10 */    63,   70,   64,   65,    7,    8,   68,   69,   24,   71,
 /*    20 */    72,   82,   74,   56,   57,   20,   19,   33,   80,   24,
 /*    30 */    36,   37,   38,   85,   84,    6,   42,    8,    6,   10,
 /*    40 */     8,   12,   10,   14,   12,    3,   14,   40,   43,   16,
 /*    50 */    20,   18,   10,   24,   24,   78,   24,   52,   81,   17,
 /*    60 */    83,   19,   33,   21,   22,   36,   37,   38,   36,   37,
 /*    70 */    38,   42,    1,    6,   42,    8,   24,   10,   68,   12,
 /*    80 */    11,   14,   52,    1,    2,   14,    4,    7,    8,   24,
 /*    90 */    80,   24,   24,   13,   25,   26,   27,   28,   29,   30,
 /*   100 */    31,   32,   62,   36,   37,   38,   59,   36,   43,   42,
 /*   110 */     3,   64,   41,   73,   63,   68,   69,   52,   71,   72,
 /*   120 */    40,   74,   22,   52,    7,    8,   44,   80,   46,   47,
 /*   130 */    48,   49,   85,   51,   52,    6,    1,    8,   12,   10,
 /*   140 */    14,   34,   35,   14,   25,   26,   27,   28,   29,   30,
 /*   150 */    31,   32,   68,   24,   67,   71,   72,   40,   74,   54,
 /*   160 */    55,   56,   57,   59,   80,   36,   37,   38,   64,   82,
 /*   170 */     1,   42,   68,   69,   11,   71,   72,   78,   74,   17,
 /*   180 */    81,   59,   83,   14,   80,   50,   64,   52,   24,   85,
 /*   190 */    68,   69,   13,   71,   72,   16,   74,   34,   35,   59,
 /*   200 */    18,    1,   80,   12,   10,   36,   15,   85,   68,   69,
 /*   210 */    41,   71,   72,   22,   74,   11,   34,   35,    6,    1,
 /*   220 */    80,   52,   10,    5,    1,   59,   14,   68,   88,   89,
 /*   230 */    64,   72,   59,   74,   68,   69,   24,   71,   72,   80,
 /*   240 */    74,   68,   69,   83,   71,   72,   80,   74,   36,   37,
 /*   250 */    38,   85,   52,   80,   42,   68,   59,   42,   71,   72,
 /*   260 */    37,   74,   89,    3,    3,   68,   69,   80,   71,   72,
 /*   270 */    52,   74,   10,    3,   77,   52,    1,   80,   59,   17,
 /*   280 */    61,   21,   20,   22,   16,   59,   14,   68,   69,    1,
 /*   290 */    71,   72,   59,   74,   68,   69,   24,   71,   72,   80,
 /*   300 */    74,   68,   69,   77,   71,   72,   80,   74,   59,   10,
 /*   310 */    77,   79,   37,   80,   82,   60,   17,   68,   69,    3,
 /*   320 */    71,   72,   59,   74,   68,   70,   77,   52,   72,   80,
 /*   330 */    74,   68,   69,   45,   71,   72,   80,   74,   59,   84,
 /*   340 */    52,   34,   35,   80,   24,   59,   12,   68,   69,   15,
 /*   350 */    71,   72,   59,   74,   68,   69,   24,   71,   72,   80,
 /*   360 */    74,   68,   69,   68,   71,   72,   80,   74,   59,   24,
 /*   370 */    75,   24,    3,   80,   68,   80,   17,   68,   69,   11,
 /*   380 */    71,   72,   59,   74,    3,   90,   80,   68,   69,   80,
 /*   390 */    21,   68,   69,   23,   71,   72,   90,   74,   59,   80,
 /*   400 */    17,   24,   21,   80,   21,   59,   58,   68,   69,   24,
 /*   410 */    71,   72,   59,   74,   68,   69,   11,   71,   72,   80,
 /*   420 */    74,   68,   69,   58,   71,   72,   80,   74,   20,    9,
 /*   430 */    68,    3,   24,   80,   72,   60,   74,   52,   78,   91,
 /*   440 */    20,    3,   80,   83,   24,   70,   58,   39,   66,   21,
 /*   450 */    66,   43,   66,   58,   78,   66,   91,   60,   14,   83,
 /*   460 */    52,    3,    3,   41,   82,    3,   82,   70,   82,    3,
 /*   470 */    20,   82,   52,   73,   11,   81,   70,   87,   91,   91,
 /*   480 */    79,   21,   14,   79,   82,   86,   91,   76,   24,   10,
 /*   490 */    14,   76,   61,
);
    const YY_SHIFT_USE_DFLT = -7;
    const YY_SHIFT_MAX = 116;
    static public $yy_shift_ofst = array(
 /*     0 */    82,   29,   -6,   -6,   -6,   -6,   32,   32,   32,   32,
 /*    10 */    32,   67,   32,   32,   32,   32,   32,   32,   32,   32,
 /*    20 */    32,   32,  129,  129,  212,  212,  212,   71,   42,  169,
 /*    30 */   191,  191,  126,  383,   82,  408,    7,  420,    5,   65,
 /*    40 */   275,  385,  385,  200,  385,  200,  385,  385,  200,  100,
 /*    50 */   460,  100,  460,  468,   69,  119,   80,   30,  288,  135,
 /*    60 */   218,  223,  163,  182,  107,  117,  428,  334,  369,  307,
 /*    70 */   260,  381,  307,  272,  476,  479,  100,  100,  100,  464,
 /*    80 */   194,  359,  100,   -7,   -7,  262,  179,  299,  261,   33,
 /*    90 */   194,  164,  162,  204,  332,   52,   68,  450,  458,  444,
 /*   100 */   438,  459,  422,  466,  463,  462,  347,  405,  370,  316,
 /*   110 */   268,  270,  215,  320,  345,  377,  368,
);
    const YY_REDUCE_USE_DFLT = -62;
    const YY_REDUCE_MAX = 84;
    static public $yy_reduce_ofst = array(
 /*     0 */   105,  -52,  122,   47,  166,  104,  140,  197,  249,  173,
 /*    10 */   233,  219,  226,  286,  263,  353,  346,  323,  279,  309,
 /*    20 */   339,  293,  187,   84,  362,  159,  256,  295,  -59,  306,
 /*    30 */    99,  -23,  319,  255,  -33,  232,   40,   87,  232,  232,
 /*    40 */   365,  384,  382,  348,  -61,  388,  386,  389,  395,  376,
 /*    50 */   397,  360,  375,   10,  399,  399,  400,  402,  387,  387,
 /*    60 */   387,  387,  390,  390,  390,  400,  406,  394,  406,  390,
 /*    70 */   406,  406,  390,  415,  431,  404,  160,  160,  160,  411,
 /*    80 */   401,  -50,  160,  -53,   51,
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
        /* 24 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 26 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 27 */ array(1, 14, 36, 41, 52, ),
        /* 28 */ array(3, 10, 17, 19, 21, 22, ),
        /* 29 */ array(1, 14, 36, 41, 52, ),
        /* 30 */ array(12, 15, 22, ),
        /* 31 */ array(12, 15, 22, ),
        /* 32 */ array(12, 14, ),
        /* 33 */ array(17, 21, ),
        /* 34 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 35 */ array(20, 24, 39, 43, 52, ),
        /* 36 */ array(7, 8, 19, 40, ),
        /* 37 */ array(9, 20, 24, 52, ),
        /* 38 */ array(20, 24, 43, 52, ),
        /* 39 */ array(24, 43, 52, ),
        /* 40 */ array(1, 37, 52, ),
        /* 41 */ array(24, 52, ),
        /* 42 */ array(24, 52, ),
        /* 43 */ array(1, 52, ),
        /* 44 */ array(24, 52, ),
        /* 45 */ array(1, 52, ),
        /* 46 */ array(24, 52, ),
        /* 47 */ array(24, 52, ),
        /* 48 */ array(1, 52, ),
        /* 49 */ array(22, ),
        /* 50 */ array(21, ),
        /* 51 */ array(22, ),
        /* 52 */ array(21, ),
        /* 53 */ array(14, ),
        /* 54 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 55 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 56 */ array(7, 8, 13, 40, ),
        /* 57 */ array(20, 24, 52, ),
        /* 58 */ array(1, 45, 52, ),
        /* 59 */ array(1, 50, 52, ),
        /* 60 */ array(1, 5, 52, ),
        /* 61 */ array(1, 37, 52, ),
        /* 62 */ array(11, 34, 35, ),
        /* 63 */ array(18, 34, 35, ),
        /* 64 */ array(3, 34, 35, ),
        /* 65 */ array(7, 8, 40, ),
        /* 66 */ array(3, 21, ),
        /* 67 */ array(12, 15, ),
        /* 68 */ array(3, 21, ),
        /* 69 */ array(34, 35, ),
        /* 70 */ array(3, 21, ),
        /* 71 */ array(3, 21, ),
        /* 72 */ array(34, 35, ),
        /* 73 */ array(14, 24, ),
        /* 74 */ array(14, ),
        /* 75 */ array(10, ),
        /* 76 */ array(22, ),
        /* 77 */ array(22, ),
        /* 78 */ array(22, ),
        /* 79 */ array(24, ),
        /* 80 */ array(10, ),
        /* 81 */ array(17, ),
        /* 82 */ array(22, ),
        /* 83 */ array(),
        /* 84 */ array(),
        /* 85 */ array(10, 17, 20, ),
        /* 86 */ array(13, 16, ),
        /* 87 */ array(10, 17, ),
        /* 88 */ array(3, 22, ),
        /* 89 */ array(16, 18, ),
        /* 90 */ array(10, ),
        /* 91 */ array(24, ),
        /* 92 */ array(17, ),
        /* 93 */ array(11, ),
        /* 94 */ array(24, ),
        /* 95 */ array(24, ),
        /* 96 */ array(24, ),
        /* 97 */ array(20, ),
        /* 98 */ array(3, ),
        /* 99 */ array(14, ),
        /* 100 */ array(3, ),
        /* 101 */ array(3, ),
        /* 102 */ array(41, ),
        /* 103 */ array(3, ),
        /* 104 */ array(11, ),
        /* 105 */ array(3, ),
        /* 106 */ array(24, ),
        /* 107 */ array(11, ),
        /* 108 */ array(23, ),
        /* 109 */ array(3, ),
        /* 110 */ array(16, ),
        /* 111 */ array(3, ),
        /* 112 */ array(42, ),
        /* 113 */ array(24, ),
        /* 114 */ array(24, ),
        /* 115 */ array(24, ),
        /* 116 */ array(11, ),
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
);
    static public $yy_default = array(
 /*     0 */   324,  324,  324,  324,  324,  324,  311,  287,  287,  324,
 /*    10 */   287,  324,  287,  324,  324,  324,  324,  324,  324,  324,
 /*    20 */   324,  324,  324,  324,  324,  324,  324,  324,  263,  324,
 /*    30 */   267,  261,  324,  235,  208,  271,  240,  324,  271,  271,
 /*    40 */   324,  324,  324,  324,  324,  324,  324,  324,  324,  256,
 /*    50 */   235,  257,  235,  324,  295,  295,  324,  324,  324,  324,
 /*    60 */   324,  324,  324,  324,  324,  272,  324,  281,  324,  293,
 /*    70 */   324,  324,  297,  324,  324,  271,  262,  278,  259,  324,
 /*    80 */   271,  241,  258,  290,  290,  263,  324,  263,  324,  324,
 /*    90 */   260,  324,  324,  324,  324,  324,  324,  324,  324,  324,
 /*   100 */   324,  324,  324,  324,  324,  324,  324,  324,  312,  324,
 /*   110 */   286,  324,  324,  324,  324,  324,  324,  307,  233,  294,
 /*   120 */   225,  220,  292,  301,  226,  209,  300,  302,  303,  236,
 /*   130 */   219,  305,  304,  306,  216,  323,  230,  299,  321,  239,
 /*   140 */   322,  212,  320,  229,  213,  238,  228,  217,  211,  210,
 /*   150 */   237,  215,  214,  232,  231,  218,  223,  283,  268,  280,
 /*   160 */   246,  279,  275,  276,  316,  314,  274,  277,  282,  285,
 /*   170 */   313,  309,  310,  266,  308,  247,  248,  264,  284,  265,
 /*   180 */   273,  245,  317,  249,  224,  250,  269,  251,  244,  243,
 /*   190 */   296,  289,  242,  227,  252,  222,  255,  315,  319,  318,
 /*   200 */   270,  291,  288,  253,  254,  221,  234,  298,
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
    const YYNSTATE = 208;
    const YYNRULE = 116;
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
 /*  32 */ "expr ::= exprs",
 /*  33 */ "expr ::= exprs modifier modparameters",
 /*  34 */ "expr ::= array",
 /*  35 */ "exprs ::= value",
 /*  36 */ "exprs ::= UNIMATH value",
 /*  37 */ "exprs ::= exprs math value",
 /*  38 */ "exprs ::= exprs ANDSYM value",
 /*  39 */ "math ::= UNIMATH",
 /*  40 */ "math ::= MATH",
 /*  41 */ "value ::= variable",
 /*  42 */ "value ::= NUMBER",
 /*  43 */ "value ::= function",
 /*  44 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  45 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  46 */ "value ::= QUOTE doublequoted QUOTE",
 /*  47 */ "value ::= QUOTE QUOTE",
 /*  48 */ "value ::= ID COLON COLON method",
 /*  49 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP",
 /*  50 */ "value ::= ID COLON COLON method objectchain",
 /*  51 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  52 */ "value ::= ID COLON COLON ID",
 /*  53 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  54 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
 /*  55 */ "value ::= ID",
 /*  56 */ "value ::= HATCH ID HATCH",
 /*  57 */ "value ::= BOOLEAN",
 /*  58 */ "value ::= OPENP expr CLOSEP",
 /*  59 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  60 */ "variable ::= DOLLAR varvar AT ID",
 /*  61 */ "variable ::= object",
 /*  62 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  63 */ "vararraydefs ::=",
 /*  64 */ "vararraydef ::= DOT exprs",
 /*  65 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  66 */ "varvar ::= varvarele",
 /*  67 */ "varvar ::= varvar varvarele",
 /*  68 */ "varvarele ::= ID",
 /*  69 */ "varvarele ::= LDEL expr RDEL",
 /*  70 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  71 */ "objectchain ::= objectelement",
 /*  72 */ "objectchain ::= objectchain objectelement",
 /*  73 */ "objectelement ::= PTR ID vararraydefs",
 /*  74 */ "objectelement ::= PTR method",
 /*  75 */ "function ::= ID OPENP params CLOSEP",
 /*  76 */ "method ::= ID OPENP params CLOSEP",
 /*  77 */ "params ::= expr COMMA params",
 /*  78 */ "params ::= expr",
 /*  79 */ "params ::=",
 /*  80 */ "modifier ::= VERT ID",
 /*  81 */ "modparameters ::= modparameters modparameter",
 /*  82 */ "modparameters ::=",
 /*  83 */ "modparameter ::= COLON expr",
 /*  84 */ "ifexprs ::= ifexpr",
 /*  85 */ "ifexprs ::= NOT ifexprs",
 /*  86 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  87 */ "ifexpr ::= expr",
 /*  88 */ "ifexpr ::= expr ifcond expr",
 /*  89 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  90 */ "ifcond ::= EQUALS",
 /*  91 */ "ifcond ::= NOTEQUALS",
 /*  92 */ "ifcond ::= GREATERTHAN",
 /*  93 */ "ifcond ::= LESSTHAN",
 /*  94 */ "ifcond ::= GREATEREQUAL",
 /*  95 */ "ifcond ::= LESSEQUAL",
 /*  96 */ "ifcond ::= IDENTITY",
 /*  97 */ "ifcond ::= NONEIDENTITY",
 /*  98 */ "lop ::= LAND",
 /*  99 */ "lop ::= LOR",
 /* 100 */ "array ::= OPENB arrayelements CLOSEB",
 /* 101 */ "arrayelements ::= arrayelement",
 /* 102 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 103 */ "arrayelements ::=",
 /* 104 */ "arrayelement ::= expr",
 /* 105 */ "arrayelement ::= expr APTR expr",
 /* 106 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 107 */ "doublequoted ::= doublequotedcontent",
 /* 108 */ "doublequotedcontent ::= variable",
 /* 109 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 110 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 111 */ "doublequotedcontent ::= OTHER",
 /* 112 */ "text ::= text textelement",
 /* 113 */ "text ::= textelement",
 /* 114 */ "textelement ::= OTHER",
 /* 115 */ "textelement ::= LDEL",
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
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
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
        35 => 0,
        41 => 0,
        42 => 0,
        43 => 0,
        57 => 0,
        61 => 0,
        101 => 0,
        1 => 1,
        32 => 1,
        34 => 1,
        39 => 1,
        40 => 1,
        66 => 1,
        84 => 1,
        107 => 1,
        113 => 1,
        114 => 1,
        115 => 1,
        2 => 2,
        62 => 2,
        106 => 2,
        112 => 2,
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
        78 => 24,
        104 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        33 => 33,
        36 => 36,
        37 => 37,
        38 => 38,
        44 => 44,
        46 => 44,
        45 => 45,
        47 => 45,
        48 => 48,
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
        82 => 63,
        64 => 64,
        65 => 65,
        67 => 67,
        68 => 68,
        69 => 69,
        86 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        79 => 79,
        80 => 80,
        81 => 81,
        83 => 83,
        85 => 85,
        87 => 87,
        88 => 88,
        89 => 88,
        90 => 90,
        91 => 91,
        92 => 92,
        93 => 93,
        94 => 94,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        99 => 99,
        100 => 100,
        102 => 102,
        103 => 103,
        105 => 105,
        108 => 108,
        109 => 109,
        110 => 110,
        111 => 111,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1540 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1543 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1546 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1551 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1554 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1557 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1560 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1563 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1569 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1575 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1578 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1581 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1584 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1587 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1590 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1593 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
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
#line 1608 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1611 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1614 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1617 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1620 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1623 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1626 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1629 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1632 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1635 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1638 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1641 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1644 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1647 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r33(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
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
#line 1661 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1664 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1667 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1670 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1673 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "''";     }
#line 1676 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1679 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r49(){ $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -3]->minor,"'"))->value; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::'.$_var.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1682 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1685 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r51(){ $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -4]->minor,"'"))->value; echo $_var; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::'.$_var.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1688 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1691 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1694 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1697 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1700 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1703 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1706 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r59(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1710 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1713 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r63(){return;    }
#line 1716 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1719 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1722 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1725 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1728 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1731 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1734 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1737 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1740 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1743 "internal.templateparser.php"
#line 299 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1746 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r75(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1755 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1758 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1761 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r79(){ return;    }
#line 1764 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1767 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1770 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1773 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1776 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1779 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1782 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '==';    }
#line 1785 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '!=';    }
#line 1788 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '>';    }
#line 1791 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '<';    }
#line 1794 "internal.templateparser.php"
#line 358 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '>=';    }
#line 1797 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '<=';    }
#line 1800 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '===';    }
#line 1803 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '!==';    }
#line 1806 "internal.templateparser.php"
#line 363 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '&&';    }
#line 1809 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '||';    }
#line 1812 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r100(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1815 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r102(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1818 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r103(){ return;     }
#line 1821 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1824 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1827 "internal.templateparser.php"
#line 376 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1830 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1833 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1836 "internal.templateparser.php"

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
#line 1953 "internal.templateparser.php"
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
#line 1978 "internal.templateparser.php"
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
