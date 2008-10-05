<?php
/* Driver template for the PHP_TP_rGenerator parser generator. (PHP port of LEMON)
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
#line 4 "internal.templateparser.y"
class Smarty_Internal_Templateparser#line 102 "internal.templateparser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 6 "internal.templateparser.y"

    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->smarty = Smarty::instance(); 
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 129 "internal.templateparser.php"

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
    const TP_LDELS                          =  2;
    const TP_LDELSLASH                      =  3;
    const TP_RDELS                          =  4;
    const TP_RDEL                           =  5;
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
    const TP_PHP                            = 37;
    const TP_LDEL                           = 38;
    const YY_NO_ACTION = 242;
    const YY_ACCEPT_ACTION = 241;
    const YY_ERROR_ACTION = 240;

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
    const YY_SZ_ACTTAB = 443;
static public $yy_action = array(
 /*     0 */   118,  114,   33,   38,  143,  149,  120,  117,   27,  142,
 /*    10 */   130,  149,   80,   10,  119,  112,   71,  116,  148,  139,
 /*    20 */   154,  101,   94,   95,   91,  107,   25,  118,  114,  138,
 /*    30 */    45,   56,  121,  120,   81,   85,  133,  130,  135,   80,
 /*    40 */   104,  119,  112,   10,  118,  114,  139,  154,  101,   94,
 /*    50 */    95,   91,  107,  128,    7,   29,   80,    2,  128,  108,
 /*    60 */    29,   57,    2,  106,   96,   10,   57,   35,  122,   20,
 /*    70 */   120,   83,  111,  142,  130,   87,   77,  111,  119,  112,
 /*    80 */    14,   92,  150,   58,   66,   14,   18,  149,   58,   17,
 /*    90 */    69,  147,   79,  141,   98,  128,   34,   29,  147,   15,
 /*   100 */   132,   63,  128,   57,   29,   13,    3,  118,  114,  145,
 /*   110 */    57,  143,   13,   83,  111,  144,  118,  114,   73,   80,
 /*   120 */    83,  111,  115,   24,   59,   58,   28,  128,   80,   29,
 /*   130 */   136,   15,   58,   10,  128,   57,   29,   51,   15,  152,
 /*   140 */   120,   84,   57,   11,  130,   83,  111,  100,  119,  112,
 /*   150 */   118,  114,   53,  111,   93,   72,  151,   58,   26,  128,
 /*   160 */   131,   29,   80,    1,   58,   21,  128,   57,   29,   88,
 /*   170 */     1,  106,   96,  125,   57,  118,  114,   76,  111,   32,
 /*   180 */   105,   61,  120,   13,   90,  111,  130,   80,  140,   58,
 /*   190 */   119,  112,  146,   75,  118,  114,   58,  151,   11,  103,
 /*   200 */    82,  102,   32,   34,   62,  120,   80,    8,   31,  130,
 /*   210 */    65,  120,   12,  119,  112,  130,  137,  106,   96,  119,
 /*   220 */   112,    9,  106,   96,  102,   89,   30,  128,   70,  120,
 /*   230 */   102,   15,  110,  130,   75,   57,    4,  119,  112,   71,
 /*   240 */   116,  148,   74,  153,  109,   83,  111,   32,  102,   64,
 /*   250 */   120,  118,  114,   32,  130,   60,   86,   58,  119,  112,
 /*   260 */   130,  124,  123,   80,  119,  112,   23,   24,   59,  102,
 /*   270 */    28,   32,  120,   67,  120,  102,  129,  134,  130,  148,
 /*   280 */   119,  112,  119,  112,   16,  126,  241,   39,   97,  123,
 /*   290 */   106,   96,   54,  102,   50,  120,  113,  120,    5,  130,
 /*   300 */    68,  130,  159,  119,  112,  119,  112,  159,   18,  159,
 /*   310 */    48,   17,   78,  120,  118,  114,   46,  130,  159,  120,
 /*   320 */   147,  119,  112,  130,  159,    6,   80,  119,  112,  118,
 /*   330 */   114,  118,  114,  143,   13,   99,   59,   49,   28,   40,
 /*   340 */   120,   80,  120,   80,  130,  159,  130,  159,  119,  112,
 /*   350 */   119,  112,   19,  159,   59,   44,   28,   47,  120,  159,
 /*   360 */   120,  159,  130,  159,  130,  159,  119,  112,  119,  112,
 /*   370 */    22,   18,   52,  159,   17,  120,  159,  159,  159,  130,
 /*   380 */   159,  159,   42,  119,  112,  120,  159,  159,  120,  130,
 /*   390 */   159,  159,  127,  119,  112,   55,  119,  112,  120,  159,
 /*   400 */   159,  159,  130,  159,  159,   37,  119,  112,  120,  159,
 /*   410 */   159,   41,  130,  159,  120,  159,  119,  112,  130,  159,
 /*   420 */   159,  159,  119,  112,   43,  159,  159,  120,  159,  159,
 /*   430 */    36,  130,  159,  120,  159,  119,  112,  130,  159,  159,
 /*   440 */   159,  119,  112,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   58,   44,   11,   61,   47,   24,   58,   50,
 /*    10 */    51,   61,   19,   10,   55,   56,   59,   60,   61,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   23,    7,    8,   70,
 /*    30 */    44,   14,    1,   47,    3,   47,   50,   51,    5,   19,
 /*    40 */    11,   55,   56,   10,    7,    8,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   21,    8,   19,   10,    6,    5,
 /*    60 */     8,   14,   10,   34,   35,   10,   14,   44,   37,   38,
 /*    70 */    47,   24,   25,   50,   51,   21,   24,   25,   55,   56,
 /*    80 */    33,    5,   56,   36,   58,   33,   12,   61,   36,   15,
 /*    90 */    45,   24,   69,   70,   49,    6,   22,    8,   24,   10,
 /*   100 */    11,   57,    6,   14,    8,   38,   10,    7,    8,   63,
 /*   110 */    14,   11,   38,   24,   25,   71,    7,    8,   53,   19,
 /*   120 */    24,   25,   13,   23,   52,   36,   54,    6,   19,    8,
 /*   130 */    65,   10,   36,   10,    6,   14,    8,   44,   10,    5,
 /*   140 */    47,   24,   14,   20,   51,   24,   25,    9,   55,   56,
 /*   150 */     7,    8,   24,   25,   11,   18,    1,   36,   20,    6,
 /*   160 */    11,    8,   19,   10,   36,   16,    6,   14,    8,   24,
 /*   170 */    10,   34,   35,    5,   14,    7,    8,   24,   25,   44,
 /*   180 */     5,   46,   47,   38,   24,   25,   51,   19,    5,   36,
 /*   190 */    55,   56,    5,   38,    7,    8,   36,    1,   20,   11,
 /*   200 */    24,   66,   44,   22,   46,   47,   19,   67,   44,   51,
 /*   210 */    46,   47,   20,   55,   56,   51,   11,   34,   35,   55,
 /*   220 */    56,   16,   34,   35,   66,   48,   44,    6,   46,   47,
 /*   230 */    66,   10,   36,   51,   38,   14,   67,   55,   56,   59,
 /*   240 */    60,   61,   62,   63,   71,   24,   25,   44,   66,   46,
 /*   250 */    47,    7,    8,   44,   51,   46,   47,   36,   55,   56,
 /*   260 */    51,   42,   43,   19,   55,   56,   17,   23,   52,   66,
 /*   270 */    54,   44,   47,   46,   47,   66,   51,   49,   51,   61,
 /*   280 */    55,   56,   55,   56,   68,   65,   40,   41,   42,   43,
 /*   290 */    34,   35,   44,   66,   44,   47,   60,   47,   67,   51,
 /*   300 */    47,   51,   72,   55,   56,   55,   56,   72,   12,   72,
 /*   310 */    44,   15,   64,   47,    7,    8,   44,   51,   72,   47,
 /*   320 */    24,   55,   56,   51,   72,   18,   19,   55,   56,    7,
 /*   330 */     8,    7,    8,   11,   38,   11,   52,   44,   54,   44,
 /*   340 */    47,   19,   47,   19,   51,   72,   51,   72,   55,   56,
 /*   350 */    55,   56,   68,   72,   52,   44,   54,   44,   47,   72,
 /*   360 */    47,   72,   51,   72,   51,   72,   55,   56,   55,   56,
 /*   370 */    68,   12,   44,   72,   15,   47,   72,   72,   72,   51,
 /*   380 */    72,   72,   44,   55,   56,   47,   72,   72,   47,   51,
 /*   390 */    72,   72,   51,   55,   56,   44,   55,   56,   47,   72,
 /*   400 */    72,   72,   51,   72,   72,   44,   55,   56,   47,   72,
 /*   410 */    72,   44,   51,   72,   47,   72,   55,   56,   51,   72,
 /*   420 */    72,   72,   55,   56,   44,   72,   72,   47,   72,   72,
 /*   430 */    44,   51,   72,   47,   72,   55,   56,   51,   72,   72,
 /*   440 */    72,   55,   56,
);
    const YY_SHIFT_USE_DFLT = -18;
    const YY_SHIFT_MAX = 90;
    static public $yy_shift_ofst = array(
 /*     0 */    31,  153,   47,   47,   47,   47,   47,   52,   47,  153,
 /*    10 */    89,  160,  121,  121,   96,  121,  121,  121,  121,  121,
 /*    20 */   128,  121,  121,  121,  121,  121,  121,   74,  221,  221,
 /*    30 */    -7,   -7,   20,  296,  145,  100,  109,  324,  244,   31,
 /*    40 */   187,  143,  322,  168,  307,   37,   37,   37,   37,   37,
 /*    50 */    37,   37,   37,   33,   37,   37,   67,   67,  155,  249,
 /*    60 */   183,   29,  188,  196,  137,  256,   67,  256,  138,   54,
 /*    70 */   256,  359,   17,  249,  181,   17,    3,  123,  149,  205,
 /*    80 */   -17,  176,   76,   55,  178,  134,  192,  117,   55,  175,
 /*    90 */    55,
);
    const YY_REDUCE_USE_DFLT = -57;
    const YY_REDUCE_MAX = 75;
    static public $yy_reduce_ofst = array(
 /*     0 */   246,   23,  182,  164,  135,  158,  203,  209,  227,  -41,
 /*    10 */   248,  -14,  311,  295,  272,  338,  293,  328,  386,  367,
 /*    20 */   380,  351,  361,  313,   93,  250,  266,  180,  341,  225,
 /*    30 */   302,  284,  216,  -43,   26,   72,   72,   72,   72,  219,
 /*    40 */    72,   72,   72,   72,   72,   72,   72,   72,   72,   72,
 /*    50 */    72,   72,   72,   45,   72,   72,  -56,  -50,   44,   65,
 /*    60 */   140,  140,  140,  173,  140,  169,  218,  140,  177,  228,
 /*    70 */   231,  236,  253,  220,   46,  -12,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 37, 38, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 9 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 10 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 13 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 14 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 15 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 16 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 17 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 18 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 19 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 20 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 21 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 22 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 24 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 25 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 26 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 27 */ array(12, 15, 22, 24, 38, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, ),
        /* 30 */ array(7, 8, 11, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 31 */ array(7, 8, 11, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 32 */ array(7, 8, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 33 */ array(12, 15, 24, 38, ),
        /* 34 */ array(24, 38, ),
        /* 35 */ array(7, 8, 11, 19, 23, ),
        /* 36 */ array(7, 8, 13, 19, ),
        /* 37 */ array(7, 8, 11, 19, ),
        /* 38 */ array(7, 8, 19, 23, ),
        /* 39 */ array(1, 3, 37, 38, ),
        /* 40 */ array(5, 7, 8, 19, ),
        /* 41 */ array(7, 8, 11, 19, ),
        /* 42 */ array(7, 8, 11, 19, ),
        /* 43 */ array(5, 7, 8, 19, ),
        /* 44 */ array(7, 8, 18, 19, ),
        /* 45 */ array(7, 8, 19, ),
        /* 46 */ array(7, 8, 19, ),
        /* 47 */ array(7, 8, 19, ),
        /* 48 */ array(7, 8, 19, ),
        /* 49 */ array(7, 8, 19, ),
        /* 50 */ array(7, 8, 19, ),
        /* 51 */ array(7, 8, 19, ),
        /* 52 */ array(7, 8, 19, ),
        /* 53 */ array(5, 10, 21, ),
        /* 54 */ array(7, 8, 19, ),
        /* 55 */ array(7, 8, 19, ),
        /* 56 */ array(24, 38, ),
        /* 57 */ array(24, 38, ),
        /* 58 */ array(1, 38, ),
        /* 59 */ array(17, ),
        /* 60 */ array(5, 34, 35, ),
        /* 61 */ array(11, 34, 35, ),
        /* 62 */ array(11, 34, 35, ),
        /* 63 */ array(1, 36, 38, ),
        /* 64 */ array(18, 34, 35, ),
        /* 65 */ array(34, 35, ),
        /* 66 */ array(24, 38, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(9, 20, ),
        /* 69 */ array(5, 21, ),
        /* 70 */ array(34, 35, ),
        /* 71 */ array(12, 15, ),
        /* 72 */ array(14, ),
        /* 73 */ array(17, ),
        /* 74 */ array(22, ),
        /* 75 */ array(14, ),
        /* 76 */ array(10, 23, ),
        /* 77 */ array(10, 20, ),
        /* 78 */ array(11, 16, ),
        /* 79 */ array(11, 16, ),
        /* 80 */ array(24, ),
        /* 81 */ array(24, ),
        /* 82 */ array(5, ),
        /* 83 */ array(10, ),
        /* 84 */ array(20, ),
        /* 85 */ array(5, ),
        /* 86 */ array(20, ),
        /* 87 */ array(24, ),
        /* 88 */ array(10, ),
        /* 89 */ array(5, ),
        /* 90 */ array(10, ),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(),
        /* 99 */ array(),
        /* 100 */ array(),
        /* 101 */ array(),
        /* 102 */ array(),
        /* 103 */ array(),
        /* 104 */ array(),
        /* 105 */ array(),
        /* 106 */ array(),
        /* 107 */ array(),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(),
        /* 112 */ array(),
        /* 113 */ array(),
        /* 114 */ array(),
        /* 115 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   240,  240,  240,  240,  240,  240,  240,  240,  240,  240,
 /*    10 */   240,  240,  240,  240,  240,  240,  240,  240,  240,  240,
 /*    20 */   240,  240,  240,  240,  240,  240,  240,  188,  240,  240,
 /*    30 */   215,  215,  215,  188,  240,  232,  240,  217,  232,  155,
 /*    40 */   240,  217,  240,  240,  240,  171,  216,  210,  167,  217,
 /*    50 */   234,  233,  192,  240,  205,  206,  240,  240,  240,  176,
 /*    60 */   240,  240,  240,  240,  240,  240,  201,  212,  240,  240,
 /*    70 */   240,  189,  240,  177,  198,  240,  240,  240,  240,  240,
 /*    80 */   240,  240,  240,  240,  240,  240,  183,  240,  196,  240,
 /*    90 */   172,  225,  164,  219,  223,  224,  228,  156,  169,  218,
 /*   100 */   168,  222,  211,  213,  214,  166,  227,  226,  163,  236,
 /*   110 */   187,  186,  185,  191,  179,  193,  190,  207,  180,  184,
 /*   120 */   183,  160,  159,  158,  157,  161,  209,  178,  181,  175,
 /*   130 */   174,  203,  204,  173,  170,  162,  208,  229,  231,  220,
 /*   140 */   165,  230,  235,  182,  237,  200,  197,  196,  195,  194,
 /*   150 */   202,  239,  238,  199,  221,
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
    const YYNOCODE = 73;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 155;
    const YYNRULE = 85;
    const YYERRORSYMBOL = 39;
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
    1,  /*      LDELS => OTHER */
    1,  /*  LDELSLASH => OTHER */
    1,  /*      RDELS => OTHER */
    1,  /*       RDEL => OTHER */
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
  '$',             'OTHER',         'LDELS',         'LDELSLASH',   
  'RDELS',         'RDEL',          'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NOT',           'LAND',          'LOR',         
  'QUOTE',         'PHP',           'LDEL',          'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'expr',          'attributes',    'ifexprs',       'variable',    
  'foraction',     'attribute',     'array',         'value',       
  'modifier',      'modparameters',  'math',          'object',      
  'function',      'doublequoted',  'varvar',        'vararraydefs',
  'vararraydef',   'varvarele',     'objectchain',   'objectelement',
  'params',        'modparameter',  'ifexpr',        'lop',         
  'ifcond',        'arrayelements',  'arrayelement',  'other',       
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
 /*   4 */ "template_element ::= PHP",
 /*   5 */ "template_element ::= OTHER",
 /*   6 */ "smartytag ::= LDEL expr RDEL",
 /*   7 */ "smartytag ::= LDEL ID RDEL",
 /*   8 */ "smartytag ::= LDEL ID attributes RDEL",
 /*   9 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  10 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  11 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  12 */ "foraction ::= EQUAL expr",
 /*  13 */ "foraction ::= INCDEC",
 /*  14 */ "attributes ::= attribute",
 /*  15 */ "attributes ::= attributes attribute",
 /*  16 */ "attribute ::= SPACE ID EQUAL expr",
 /*  17 */ "attribute ::= SPACE ID EQUAL ID",
 /*  18 */ "attribute ::= SPACE ID EQUAL array",
 /*  19 */ "expr ::= value",
 /*  20 */ "expr ::= UNIMATH value",
 /*  21 */ "expr ::= expr modifier",
 /*  22 */ "expr ::= expr modifier modparameters",
 /*  23 */ "expr ::= expr math value",
 /*  24 */ "math ::= UNIMATH",
 /*  25 */ "math ::= MATH",
 /*  26 */ "value ::= NUMBER",
 /*  27 */ "value ::= OPENP expr CLOSEP",
 /*  28 */ "value ::= variable",
 /*  29 */ "value ::= object",
 /*  30 */ "value ::= function",
 /*  31 */ "value ::= SI_QSTR",
 /*  32 */ "value ::= QUOTE doublequoted QUOTE",
 /*  33 */ "variable ::= DOLLAR varvar",
 /*  34 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  35 */ "vararraydefs ::= vararraydef",
 /*  36 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  37 */ "vararraydef ::= DOT expr",
 /*  38 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  39 */ "varvar ::= varvarele",
 /*  40 */ "varvar ::= varvar varvarele",
 /*  41 */ "varvarele ::= ID",
 /*  42 */ "varvarele ::= LDEL expr RDEL",
 /*  43 */ "object ::= DOLLAR varvar objectchain",
 /*  44 */ "objectchain ::= objectelement",
 /*  45 */ "objectchain ::= objectchain objectelement",
 /*  46 */ "objectelement ::= PTR varvar",
 /*  47 */ "objectelement ::= PTR function",
 /*  48 */ "function ::= ID OPENP params CLOSEP",
 /*  49 */ "function ::= ID OPENP CLOSEP",
 /*  50 */ "params ::= expr",
 /*  51 */ "params ::= params COMMA expr",
 /*  52 */ "modifier ::= VERT ID",
 /*  53 */ "modparameters ::= modparameter",
 /*  54 */ "modparameters ::= modparameters modparameter",
 /*  55 */ "modparameter ::= COLON expr",
 /*  56 */ "ifexprs ::= ifexpr",
 /*  57 */ "ifexprs ::= ifexprs lop ifexprs",
 /*  58 */ "ifexprs ::= OPENP ifexprs lop ifexprs CLOSEP",
 /*  59 */ "ifexprs ::= NOT OPENP ifexprs lop ifexprs CLOSEP",
 /*  60 */ "ifexpr ::= expr",
 /*  61 */ "ifexpr ::= NOT expr",
 /*  62 */ "ifexpr ::= expr ifcond expr",
 /*  63 */ "ifexpr ::= OPENP expr ifcond expr CLOSEP",
 /*  64 */ "ifexpr ::= NOT OPENP expr ifcond expr CLOSEP",
 /*  65 */ "ifcond ::= EQUALS",
 /*  66 */ "ifcond ::= NOTEQUALS",
 /*  67 */ "ifcond ::= GREATERTHAN",
 /*  68 */ "ifcond ::= LESSTHAN",
 /*  69 */ "ifcond ::= GREATEREQUAL",
 /*  70 */ "ifcond ::= LESSEQUAL",
 /*  71 */ "ifcond ::= IDENTITY",
 /*  72 */ "lop ::= LAND",
 /*  73 */ "lop ::= LOR",
 /*  74 */ "array ::= OPENP arrayelements CLOSEP",
 /*  75 */ "arrayelements ::= arrayelement",
 /*  76 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  77 */ "arrayelement ::= expr",
 /*  78 */ "arrayelement ::= expr APTR expr",
 /*  79 */ "arrayelement ::= ID APTR expr",
 /*  80 */ "arrayelement ::= array",
 /*  81 */ "doublequoted ::= doublequoted other",
 /*  82 */ "doublequoted ::= other",
 /*  83 */ "other ::= LDEL variable RDEL",
 /*  84 */ "other ::= OTHER",
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
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
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
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
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
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
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
                    self::$yyTokenName[$this->yystack[$i]->major]);
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
  array( 'lhs' => 40, 'rhs' => 1 ),
  array( 'lhs' => 41, 'rhs' => 1 ),
  array( 'lhs' => 41, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 5 ),
  array( 'lhs' => 43, 'rhs' => 12 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 2 ),
  array( 'lhs' => 44, 'rhs' => 2 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 5 ),
  array( 'lhs' => 46, 'rhs' => 6 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 5 ),
  array( 'lhs' => 66, 'rhs' => 6 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        19 => 0,
        26 => 0,
        28 => 0,
        29 => 0,
        30 => 0,
        31 => 0,
        75 => 0,
        1 => 1,
        3 => 1,
        4 => 1,
        5 => 1,
        24 => 1,
        25 => 1,
        35 => 1,
        39 => 1,
        41 => 1,
        53 => 1,
        55 => 1,
        56 => 1,
        82 => 1,
        84 => 1,
        2 => 2,
        36 => 2,
        81 => 2,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 13,
        50 => 13,
        77 => 13,
        80 => 13,
        15 => 15,
        16 => 16,
        17 => 16,
        18 => 16,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        27 => 27,
        32 => 32,
        33 => 33,
        34 => 34,
        43 => 34,
        37 => 37,
        38 => 38,
        40 => 40,
        42 => 42,
        44 => 44,
        45 => 45,
        46 => 46,
        47 => 46,
        48 => 48,
        49 => 49,
        51 => 51,
        52 => 52,
        54 => 54,
        57 => 57,
        62 => 57,
        58 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        63 => 63,
        64 => 63,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        76 => 76,
        78 => 78,
        79 => 78,
        83 => 83,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 57 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1332 "internal.templateparser.php"
#line 63 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1335 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1338 "internal.templateparser.php"
#line 82 "internal.templateparser.y"
    function yy_r6(){ $this->_retvalue = $this->smarty->compile_variable_output($this->yystack[$this->yyidx + -1]->minor);    }
#line 1341 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1344 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1347 "internal.templateparser.php"
#line 88 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1350 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1353 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1356 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1359 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1362 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1365 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1368 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1371 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1374 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1377 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1380 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1383 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = '"'.$this->yystack[$this->yyidx + -1]->minor.'"';     }
#line 1386 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1389 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1392 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1395 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1398 "internal.templateparser.php"
#line 168 "internal.templateparser.y"
    function yy_r40(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1401 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r42(){$this->_retvalue = "(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1404 "internal.templateparser.php"
#line 179 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1407 "internal.templateparser.php"
#line 181 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1410 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1413 "internal.templateparser.php"
#line 191 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1416 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1419 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1422 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1425 "internal.templateparser.php"
#line 209 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1428 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1431 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1434 "internal.templateparser.php"
#line 222 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1437 "internal.templateparser.php"
#line 226 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1440 "internal.templateparser.php"
#line 228 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1443 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor;    }
#line 1446 "internal.templateparser.php"
#line 233 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '==';    }
#line 1449 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!=';    }
#line 1452 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '>';    }
#line 1455 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '<';    }
#line 1458 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '>=';    }
#line 1461 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '<=';    }
#line 1464 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '===';    }
#line 1467 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '&&';    }
#line 1470 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '||';    }
#line 1473 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1476 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1479 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1482 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '".'.$this->yystack[$this->yyidx + -1]->minor.'."';    }
#line 1485 "internal.templateparser.php"

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
#line 41 "internal.templateparser.y"

    $this->internalError = true;
    $this->smarty->trigger_template_error();
#line 1602 "internal.templateparser.php"
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
#line 33 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1627 "internal.templateparser.php"
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
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
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
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
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
