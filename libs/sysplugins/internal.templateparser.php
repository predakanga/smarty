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
    const TP_NOT                            = 33;
    const TP_LAND                           = 34;
    const TP_LOR                            = 35;
    const TP_QUOTE                          = 36;
    const TP_BOOLEAN                        = 37;
    const TP_IN                             = 38;
    const TP_ANDSYM                         = 39;
    const TP_UNDERL                         = 40;
    const TP_BACKTICK                       = 41;
    const TP_LITERALSTART                   = 42;
    const TP_LITERALEND                     = 43;
    const TP_LDELIMTAG                      = 44;
    const TP_RDELIMTAG                      = 45;
    const TP_PHP                            = 46;
    const TP_LDEL                           = 47;
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
    const YY_SZ_ACTTAB = 491;
static public $yy_action = array(
 /*     0 */   166,  148,   36,  155,  186,    7,  192,   77,  126,  290,
 /*    10 */    34,  139,  101,  144,  142,   83,   14,  145,  146,  134,
 /*    20 */   136,  127,  128,  130,  132,  135,   35,  107,  125,   13,
 /*    30 */   155,   61,   24,    7,  156,  139,  168,  144,  187,   83,
 /*    40 */   101,  145,  146,   19,   38,  165,  166,  148,   36,  159,
 /*    50 */   129,   65,  125,   67,   95,   94,  181,  139,  180,  144,
 /*    60 */   142,   83,  176,  145,  146,  134,  136,  127,  128,  130,
 /*    70 */   132,  135,  175,  140,  125,   23,  159,    2,   24,  119,
 /*    80 */   110,   43,  166,  148,  160,   40,   51,   76,  194,  190,
 /*    90 */   183,   96,  151,  166,  148,  139,  110,  144,  187,   83,
 /*   100 */     5,  145,  146,   31,  167,  168,  192,  150,   36,   84,
 /*   110 */   124,  123,   81,   72,   24,   94,  181,  139,   22,  144,
 /*   120 */   142,   83,  117,  145,  146,   24,   36,  170,  140,   13,
 /*   130 */    23,   58,    2,  116,  125,  139,   42,  144,  142,   83,
 /*   140 */   155,  145,  146,  106,  166,  148,   93,  151,  186,  140,
 /*   150 */    26,   23,  125,    6,  137,    5,   47,   43,   31,  167,
 /*   160 */   162,  100,   48,   44,  191,   12,   16,   96,  151,  192,
 /*   170 */   192,  139,   55,  104,   99,   88,   24,  145,  146,   31,
 /*   180 */   167,  139,   80,  144,  142,   83,  159,  145,  146,  124,
 /*   190 */   123,  179,   13,   13,   64,   25,  112,  304,   37,  122,
 /*   200 */   157,   46,   45,  164,  163,  161,    8,   32,  191,  105,
 /*   210 */    39,  121,  153,  140,  131,   23,  139,    6,  144,  142,
 /*   220 */    83,   41,  145,  146,  191,  166,  148,  166,  148,  186,
 /*   230 */   110,   28,  151,  139,  124,  123,   55,   89,   17,  145,
 /*   240 */   146,   14,  110,   31,  167,  139,  177,  144,  142,   83,
 /*   250 */    18,  145,  146,   15,   74,   91,   55,   24,  140,   24,
 /*   260 */   109,  184,   21,  179,  110,  139,   43,  144,  142,   83,
 /*   270 */    53,  145,  146,   29,  124,  123,   96,  151,  143,  139,
 /*   280 */   172,  144,  187,   83,   59,  145,  146,  191,   31,  167,
 /*   290 */    71,  194,  193,   18,   18,  108,   15,   15,   52,  114,
 /*   300 */   182,   87,   16,   87,  195,  113,  192,  139,  193,  144,
 /*   310 */   142,   83,  139,  145,  146,   66,   85,  160,  145,  146,
 /*   320 */    82,  118,  110,   60,  139,  120,  144,  142,   83,   13,
 /*   330 */   145,  146,  139,   30,  144,  142,   83,   57,  145,  146,
 /*   340 */    33,  194,  193,  141,  166,  148,  139,  191,  144,  142,
 /*   350 */    83,  138,  145,  146,   56,   86,   10,    3,   27,   78,
 /*   360 */    14,   92,   62,  139,    9,  144,  142,   83,  179,  145,
 /*   370 */   146,  139,  191,  144,  142,   83,   24,  145,  146,   54,
 /*   380 */    73,  194,   50,  166,  148,  158,  157,   63,  139,  185,
 /*   390 */   144,  142,   83,  103,  145,  146,  139,   79,  144,  142,
 /*   400 */    83,   68,  145,  146,  149,   49,   90,  188,   97,   69,
 /*   410 */   139,  173,  144,  142,   83,   24,  145,  146,  139,  178,
 /*   420 */   144,  142,   83,  189,  145,  146,   70,   25,  174,  220,
 /*   430 */    19,  175,  166,  148,  171,  139,    7,  144,  142,   83,
 /*   440 */    20,  145,  146,  101,   40,  113,  111,    1,  102,   18,
 /*   450 */    18,   75,   15,   15,  114,  114,   25,   16,  166,  148,
 /*   460 */   179,  192,  192,  133,   24,  169,  152,   11,  193,   20,
 /*   470 */   110,   81,   25,  154,    4,  149,  143,   22,  147,   17,
 /*   480 */   115,   40,   98,  196,   13,   13,  196,  196,  196,  196,
 /*   490 */    24,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   54,    1,   11,   10,   24,   59,    3,   16,
 /*    10 */    58,   63,   17,   65,   66,   67,   23,   69,   70,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   54,   22,   80,   47,
 /*    30 */     1,   59,   39,   10,    5,   63,   63,   65,   66,   67,
 /*    40 */    17,   69,   70,   20,   71,   43,    7,    8,   54,   47,
 /*    50 */    56,   53,   80,   59,   60,   83,   84,   63,   85,   65,
 /*    60 */    66,   67,    3,   69,   70,   26,   27,   28,   29,   30,
 /*    70 */    31,   32,    1,    6,   80,    8,   47,   10,   39,    3,
 /*    80 */    21,   14,    7,    8,   86,   14,   54,   73,   74,    3,
 /*    90 */    77,   24,   25,    7,    8,   63,   21,   65,   66,   67,
 /*   100 */    33,   69,   70,   36,   37,   63,   24,   36,   54,   58,
 /*   110 */    34,   35,   41,   59,   39,   83,   84,   63,   47,   65,
 /*   120 */    66,   67,   40,   69,   70,   39,   54,   85,    6,   47,
 /*   130 */     8,   59,   10,   14,   80,   63,   14,   65,   66,   67,
 /*   140 */     1,   69,   70,   24,    7,    8,   24,   25,   11,    6,
 /*   150 */    61,    8,   80,   10,    9,   33,   24,   14,   36,   37,
 /*   160 */     1,    2,   24,    4,   75,   20,   20,   24,   25,   24,
 /*   170 */    24,   63,   54,   18,   24,   67,   39,   69,   70,   36,
 /*   180 */    37,   63,   55,   65,   66,   67,   47,   69,   70,   34,
 /*   190 */    35,   64,   47,   47,   61,   68,   78,   49,   50,   51,
 /*   200 */    52,   42,   14,   44,   45,   46,   47,   54,   75,   56,
 /*   210 */    61,   11,    3,    6,   56,    8,   63,   10,   65,   66,
 /*   220 */    67,   14,   69,   70,   75,    7,    8,    7,    8,   11,
 /*   230 */    21,   24,   25,   63,   34,   35,   54,   67,   17,   69,
 /*   240 */    70,   23,   21,   36,   37,   63,    3,   65,   66,   67,
 /*   250 */    12,   69,   70,   15,   55,   57,   54,   39,    6,   39,
 /*   260 */    78,   72,   10,   64,   21,   63,   14,   65,   66,   67,
 /*   270 */    54,   69,   70,   61,   34,   35,   24,   25,   79,   63,
 /*   280 */    78,   65,   66,   67,   53,   69,   70,   75,   36,   37,
 /*   290 */    73,   74,   75,   12,   12,   62,   15,   15,   54,   17,
 /*   300 */    84,   22,   20,   22,    3,   19,   24,   63,   75,   65,
 /*   310 */    66,   67,   63,   69,   70,   54,   67,   86,   69,   70,
 /*   320 */    38,    3,   21,   54,   63,    3,   65,   66,   67,   47,
 /*   330 */    69,   70,   63,   61,   65,   66,   67,   54,   69,   70,
 /*   340 */    73,   74,   75,   24,    7,    8,   63,   75,   65,   66,
 /*   350 */    67,   11,   69,   70,   54,   16,   16,   18,   61,   55,
 /*   360 */    23,   57,   54,   63,   10,   65,   66,   67,   64,   69,
 /*   370 */    70,   63,   75,   65,   66,   67,   39,   69,   70,   54,
 /*   380 */    73,   74,   24,    7,    8,   51,   52,   54,   63,   13,
 /*   390 */    65,   66,   67,   24,   69,   70,   63,   17,   65,   66,
 /*   400 */    67,   54,   69,   70,   74,   14,   76,   77,   24,   54,
 /*   410 */    63,   11,   65,   66,   67,   39,   69,   70,   63,    3,
 /*   420 */    65,   66,   67,   24,   69,   70,   54,   68,    3,    3,
 /*   430 */    20,    1,    7,    8,   11,   63,   10,   65,   66,   67,
 /*   440 */    81,   69,   70,   17,   14,   19,   24,   21,   22,   12,
 /*   450 */    12,   55,   15,   15,   17,   17,   68,   20,    7,    8,
 /*   460 */    64,   24,   24,    3,   39,   41,   64,   16,   75,   81,
 /*   470 */    21,   41,   68,   86,   82,   74,   79,   47,   72,   17,
 /*   480 */    63,   14,   63,   87,   47,   47,   87,   87,   87,   87,
 /*   490 */    39,
);
    const YY_SHIFT_USE_DFLT = -19;
    const YY_SHIFT_MAX = 117;
    static public $yy_shift_ofst = array(
 /*     0 */   159,  122,   67,   67,   67,   67,  143,  143,  207,  143,
 /*    10 */   143,  143,  143,  143,  143,  143,  143,  143,  143,  143,
 /*    20 */   143,  143,  143,  252,  252,  252,  282,  437,  426,  438,
 /*    30 */   438,  430,   75,  281,  221,   -7,   39,  159,   71,  145,
 /*    40 */    82,   82,   82,   82,  139,  -18,  139,  238,  238,  -18,
 /*    50 */   449,  218,  425,  337,  376,  451,  137,   86,  155,    2,
 /*    60 */   220,  200,  220,  220,  146,   29,  220,   76,  220,  220,
 /*    70 */   220,  238,  240,  238,  301,  243,  238,  240,   59,  119,
 /*    80 */   209,  467,  467,  286,  462,  286,  188,  150,  286,  286,
 /*    90 */   279,  -19,  -19,   23,  340,  339,   -5,    5,  424,  354,
 /*   100 */   384,  380,  358,  322,  391,  416,  354,  369,  318,  423,
 /*   110 */   422,  410,  400,  319,  399,  460,  132,  138,
);
    const YY_REDUCE_USE_DFLT = -53;
    const YY_REDUCE_MAX = 92;
    static public $yy_reduce_ofst = array(
 /*     0 */   148,   -6,  -28,   72,  -52,   54,   32,  182,  153,  118,
 /*    10 */   216,  202,  261,  283,  333,  355,  269,  347,  325,  308,
 /*    20 */   372,  300,  244,  170,  108,  249,  267,  267,  304,  267,
 /*    30 */   217,  -27,  127,  330,  199,  359,  388,  334,   42,  233,
 /*    40 */   272,  297,   89,  212,   -2,  133,  231,   14,  307,  149,
 /*    50 */   396,  404,  404,  404,  404,  404,  404,  404,  392,  387,
 /*    60 */   404,  392,  404,  404,  393,  387,  404,  392,  404,  404,
 /*    70 */   404,  401,  392,  401,  402,  402,  401,  392,  402,  406,
 /*    80 */   402,  419,  417,  198,  397,  198,  158,  189,  198,  198,
 /*    90 */    13,   51,  -48,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 42, 44, 45, 46, 47, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 9 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 10 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 13 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 14 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 15 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 16 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 17 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 18 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 19 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 20 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 21 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 22 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 23 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 26 */ array(12, 15, 17, 20, 24, 38, 47, ),
        /* 27 */ array(12, 15, 17, 20, 24, 47, ),
        /* 28 */ array(3, 10, 17, 19, 21, 22, ),
        /* 29 */ array(12, 15, 17, 24, 47, ),
        /* 30 */ array(12, 15, 17, 24, 47, ),
        /* 31 */ array(1, 14, 41, 47, ),
        /* 32 */ array(7, 8, 21, 39, ),
        /* 33 */ array(12, 15, 22, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 36 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 37 */ array(1, 2, 4, 42, 44, 45, 46, 47, ),
        /* 38 */ array(1, 14, 36, 41, 47, ),
        /* 39 */ array(9, 20, 24, 47, ),
        /* 40 */ array(24, 40, 47, ),
        /* 41 */ array(24, 40, 47, ),
        /* 42 */ array(24, 40, 47, ),
        /* 43 */ array(24, 40, 47, ),
        /* 44 */ array(1, 47, ),
        /* 45 */ array(24, 47, ),
        /* 46 */ array(1, 47, ),
        /* 47 */ array(12, 15, ),
        /* 48 */ array(12, 15, ),
        /* 49 */ array(24, 47, ),
        /* 50 */ array(21, ),
        /* 51 */ array(7, 8, 11, 23, 39, ),
        /* 52 */ array(3, 7, 8, 39, ),
        /* 53 */ array(7, 8, 23, 39, ),
        /* 54 */ array(7, 8, 13, 39, ),
        /* 55 */ array(7, 8, 16, 39, ),
        /* 56 */ array(7, 8, 11, 39, ),
        /* 57 */ array(3, 7, 8, 39, ),
        /* 58 */ array(18, 34, 35, ),
        /* 59 */ array(1, 43, 47, ),
        /* 60 */ array(7, 8, 39, ),
        /* 61 */ array(11, 34, 35, ),
        /* 62 */ array(7, 8, 39, ),
        /* 63 */ array(7, 8, 39, ),
        /* 64 */ array(20, 24, 47, ),
        /* 65 */ array(1, 5, 47, ),
        /* 66 */ array(7, 8, 39, ),
        /* 67 */ array(3, 34, 35, ),
        /* 68 */ array(7, 8, 39, ),
        /* 69 */ array(7, 8, 39, ),
        /* 70 */ array(7, 8, 39, ),
        /* 71 */ array(12, 15, ),
        /* 72 */ array(34, 35, ),
        /* 73 */ array(12, 15, ),
        /* 74 */ array(3, 21, ),
        /* 75 */ array(3, 21, ),
        /* 76 */ array(12, 15, ),
        /* 77 */ array(34, 35, ),
        /* 78 */ array(3, 21, ),
        /* 79 */ array(14, 24, ),
        /* 80 */ array(3, 21, ),
        /* 81 */ array(14, ),
        /* 82 */ array(14, ),
        /* 83 */ array(19, ),
        /* 84 */ array(17, ),
        /* 85 */ array(19, ),
        /* 86 */ array(14, ),
        /* 87 */ array(24, ),
        /* 88 */ array(19, ),
        /* 89 */ array(19, ),
        /* 90 */ array(22, ),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(10, 17, 20, ),
        /* 94 */ array(11, 16, ),
        /* 95 */ array(16, 18, ),
        /* 96 */ array(10, 17, ),
        /* 97 */ array(3, 22, ),
        /* 98 */ array(41, ),
        /* 99 */ array(10, ),
        /* 100 */ array(24, ),
        /* 101 */ array(17, ),
        /* 102 */ array(24, ),
        /* 103 */ array(3, ),
        /* 104 */ array(14, ),
        /* 105 */ array(3, ),
        /* 106 */ array(10, ),
        /* 107 */ array(24, ),
        /* 108 */ array(3, ),
        /* 109 */ array(11, ),
        /* 110 */ array(24, ),
        /* 111 */ array(20, ),
        /* 112 */ array(11, ),
        /* 113 */ array(24, ),
        /* 114 */ array(24, ),
        /* 115 */ array(3, ),
        /* 116 */ array(24, ),
        /* 117 */ array(24, ),
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
);
    static public $yy_default = array(
 /*     0 */   303,  303,  303,  303,  303,  303,  303,  267,  303,  267,
 /*    10 */   303,  267,  303,  303,  303,  303,  303,  303,  303,  303,
 /*    20 */   303,  303,  303,  303,  303,  303,  251,  251,  243,  251,
 /*    30 */   251,  303,  220,  246,  220,  275,  275,  196,  303,  303,
 /*    40 */   303,  303,  303,  303,  303,  303,  303,  251,  251,  303,
 /*    50 */   220,  290,  303,  290,  303,  266,  303,  303,  303,  303,
 /*    60 */   224,  303,  221,  291,  303,  303,  216,  303,  271,  252,
 /*    70 */   276,  246,  273,  248,  303,  303,  242,  277,  303,  303,
 /*    80 */   303,  303,  303,  227,  233,  229,  303,  303,  230,  228,
 /*    90 */   258,  270,  270,  243,  303,  303,  243,  303,  303,  261,
 /*   100 */   303,  303,  303,  303,  303,  303,  241,  303,  303,  303,
 /*   110 */   303,  303,  303,  303,  303,  303,  303,  303,  214,  213,
 /*   120 */   212,  274,  197,  286,  285,  272,  211,  280,  281,  222,
 /*   130 */   282,  223,  283,  215,  278,  284,  279,  217,  287,  234,
 /*   140 */   235,  268,  226,  269,  225,  236,  237,  240,  231,  250,
 /*   150 */   239,  238,  218,  206,  299,  301,  200,  199,  198,  302,
 /*   160 */   300,  204,  205,  203,  202,  201,  232,  244,  295,  296,
 /*   170 */   293,  263,  265,  264,  297,  298,  208,  209,  207,  219,
 /*   180 */   294,  288,  289,  260,  262,  253,  245,  226,  259,  247,
 /*   190 */   257,  254,  256,  255,  249,  210,
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
    const YYNOCODE = 88;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 196;
    const YYNRULE = 107;
    const YYERRORSYMBOL = 48;
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
  'IDENTITY',      'NOT',           'LAND',          'LOR',         
  'QUOTE',         'BOOLEAN',       'IN',            'ANDSYM',      
  'UNDERL',        'BACKTICK',      'LITERALSTART',  'LITERALEND',  
  'LDELIMTAG',     'RDELIMTAG',     'PHP',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'text',          'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'variable',    
  'attribute',     'exprs',         'array',         'value',       
  'math',          'object',        'function',      'doublequoted',
  'method',        'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'params',        'modparameter',
  'ifexpr',        'ifcond',        'lop',           'arrayelements',
  'arrayelement',  'doublequotedcontent',  'textelement', 
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
 /*  40 */ "value ::= object",
 /*  41 */ "value ::= function",
 /*  42 */ "value ::= SI_QSTR",
 /*  43 */ "value ::= QUOTE doublequoted QUOTE",
 /*  44 */ "value ::= ID COLON COLON method",
 /*  45 */ "value ::= ID COLON COLON ID",
 /*  46 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  47 */ "value ::= ID",
 /*  48 */ "value ::= BOOLEAN",
 /*  49 */ "value ::= OPENP expr CLOSEP",
 /*  50 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  51 */ "variable ::= DOLLAR varvar COLON ID",
 /*  52 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
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
 /*  65 */ "objectelement ::= PTR ID",
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
 /*  89 */ "lop ::= LAND",
 /*  90 */ "lop ::= LOR",
 /*  91 */ "array ::= OPENP arrayelements CLOSEP",
 /*  92 */ "arrayelements ::= arrayelement",
 /*  93 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  94 */ "arrayelement ::= expr",
 /*  95 */ "arrayelement ::= expr APTR expr",
 /*  96 */ "arrayelement ::= array",
 /*  97 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  98 */ "doublequoted ::= doublequotedcontent",
 /*  99 */ "doublequotedcontent ::= variable",
 /* 100 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 101 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 102 */ "doublequotedcontent ::= OTHER",
 /* 103 */ "text ::= text textelement",
 /* 104 */ "text ::= textelement",
 /* 105 */ "textelement ::= OTHER",
 /* 106 */ "textelement ::= LDEL",
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
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 5 ),
  array( 'lhs' => 52, 'rhs' => 5 ),
  array( 'lhs' => 52, 'rhs' => 11 ),
  array( 'lhs' => 52, 'rhs' => 8 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 6 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 0 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
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
        42 => 0,
        48 => 0,
        92 => 0,
        1 => 1,
        29 => 1,
        30 => 1,
        35 => 1,
        36 => 1,
        53 => 1,
        58 => 1,
        76 => 1,
        98 => 1,
        102 => 1,
        104 => 1,
        105 => 1,
        106 => 1,
        2 => 2,
        54 => 2,
        97 => 2,
        103 => 2,
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
        94 => 21,
        96 => 21,
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
        43 => 43,
        44 => 44,
        45 => 45,
        46 => 46,
        47 => 47,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
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
        66 => 65,
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
        93 => 93,
        95 => 95,
        99 => 99,
        100 => 100,
        101 => 101,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1490 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1493 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1496 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1501 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1504 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1507 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1510 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1513 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1519 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1522 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1525 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1528 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1531 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1534 "internal.templateparser.php"
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
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1549 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1552 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1555 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1558 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1561 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1564 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1567 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1570 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1573 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = array();    }
#line 1576 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1579 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1582 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1585 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1588 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1591 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1594 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1597 "internal.templateparser.php"
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
#line 1611 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1614 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1617 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1620 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1623 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1626 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1629 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1632 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1635 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1638 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r55(){return;    }
#line 1641 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1644 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1647 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1650 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1653 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1656 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1659 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1662 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1665 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1668 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r67(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1677 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1680 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1683 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r71(){ return;    }
#line 1686 "internal.templateparser.php"
#line 308 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1689 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1692 "internal.templateparser.php"
#line 317 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1695 "internal.templateparser.php"
#line 324 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1698 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1701 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1704 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '==';    }
#line 1707 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '!=';    }
#line 1710 "internal.templateparser.php"
#line 335 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '>';    }
#line 1713 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '<';    }
#line 1716 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '>=';    }
#line 1719 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '<=';    }
#line 1722 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '===';    }
#line 1725 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '&&';    }
#line 1728 "internal.templateparser.php"
#line 342 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '||';    }
#line 1731 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r91(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1734 "internal.templateparser.php"
#line 346 "internal.templateparser.y"
    function yy_r93(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1737 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r95(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1740 "internal.templateparser.php"
#line 353 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1743 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1746 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1749 "internal.templateparser.php"

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
#line 1866 "internal.templateparser.php"
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
#line 1891 "internal.templateparser.php"
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
