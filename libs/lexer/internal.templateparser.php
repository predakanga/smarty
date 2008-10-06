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
    const YY_NO_ACTION = 230;
    const YY_ACCEPT_ACTION = 229;
    const YY_ERROR_ACTION = 228;

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
    const YY_SZ_ACTTAB = 403;
static public $yy_action = array(
 /*     0 */   115,  112,  126,   34,  113,   28,  131,    9,  109,  129,
 /*    10 */    89,  108,   83,   58,  127,  106,  109,   54,    2,  144,
 /*    20 */    98,   87,   84,   92,   91,   93,  124,  115,  112,  135,
 /*    30 */    42,  114,  103,  131,   72,  130,  140,   89,   66,   83,
 /*    40 */    11,  127,  106,  100,  115,  112,  144,   98,   87,   84,
 /*    50 */    92,   91,   93,  133,   19,   24,   83,    7,  133,  139,
 /*    60 */    24,   48,   16,  120,   97,  105,   48,   30,  102,   17,
 /*    70 */   131,   75,  136,  129,   89,   76,   75,  136,  127,  106,
 /*    80 */     3,  111,   95,   49,   57,  117,  121,  133,   49,   24,
 /*    90 */    10,    7,   68,  128,  133,   48,   24,   27,    6,   52,
 /*   100 */   131,   51,   48,   25,   89,   67,  136,  133,  127,  106,
 /*   110 */    63,   16,   75,  136,    3,   48,  143,   49,   62,   96,
 /*   120 */    18,    3,  125,   12,   49,   75,  136,   51,  133,   25,
 /*   130 */    24,  133,   16,   24,    9,   16,   48,   49,   64,   48,
 /*   140 */   142,   27,   15,   53,   79,    8,   75,  136,   89,   45,
 /*   150 */   136,  111,  127,  106,   97,  105,  115,  112,   49,   39,
 /*   160 */   113,   49,  131,   96,    9,  133,   89,   24,   83,    1,
 /*   170 */   127,  106,   13,   48,   10,   26,  123,   59,  131,   70,
 /*   180 */   203,  203,   89,   65,  136,  118,  127,  106,   62,   27,
 /*   190 */    21,   59,  131,   82,  131,   49,   89,   74,  132,  110,
 /*   200 */   127,  106,  127,  106,  115,  112,   27,   11,   56,  131,
 /*   210 */   119,   69,   26,   89,   59,  131,   83,  127,  106,   89,
 /*   220 */   115,  112,   20,  127,  106,  133,   60,   24,   96,    1,
 /*   230 */    99,    5,   83,   48,   77,   57,  117,  121,   61,  107,
 /*   240 */    97,  105,   46,   78,  136,  131,  101,   18,   85,   89,
 /*   250 */    12,  201,  201,  127,  106,   49,   71,   29,   37,  124,
 /*   260 */    41,  131,   73,  131,  121,   89,   22,   89,    4,  127,
 /*   270 */   106,  127,  106,   11,   38,   94,   86,  131,  137,   23,
 /*   280 */    31,   89,  109,  131,   50,  127,  106,   89,   29,  116,
 /*   290 */    47,  127,  106,  131,  141,   80,   88,   89,  115,  112,
 /*   300 */    32,  127,  106,  131,  138,   81,   43,   89,    9,  131,
 /*   310 */    83,  127,  106,   89,   13,   55,   36,  127,  106,  131,
 /*   320 */   157,   14,   40,   89,  157,  131,  157,  127,  106,   89,
 /*   330 */   157,  157,   35,  127,  106,  131,  115,  112,   44,   89,
 /*   340 */   113,  131,  157,  127,  106,   89,  157,  157,   83,  127,
 /*   350 */   106,  104,  157,  115,  112,  122,  157,  115,  112,  131,
 /*   360 */   157,  157,  157,  134,  157,   83,  157,  127,  106,   83,
 /*   370 */   229,   33,   90,   86,  157,  157,   18,  157,  157,   12,
 /*   380 */   157,  157,  157,  157,  157,  157,  157,  157,  124,  157,
 /*   390 */   157,  157,  157,  157,  157,  157,  157,  157,  157,  157,
 /*   400 */   157,  157,   11,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,    5,   44,   11,   58,   47,   10,   61,   50,
 /*    10 */    51,   56,   19,   58,   55,   56,   61,   57,   21,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   24,    7,    8,   70,
 /*    30 */    44,   71,    1,   47,    3,    5,   50,   51,   11,   19,
 /*    40 */    38,   55,   56,    9,    7,    8,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   20,    8,   19,   10,    6,    5,
 /*    60 */     8,   14,   10,   11,   34,   35,   14,   44,   37,   38,
 /*    70 */    47,   24,   25,   50,   51,   21,   24,   25,   55,   56,
 /*    80 */    33,    1,    5,   36,   59,   60,   61,    6,   36,    8,
 /*    90 */    20,   10,   69,   70,    6,   14,    8,   44,   10,   46,
 /*   100 */    47,   52,   14,   54,   51,   24,   25,    6,   55,   56,
 /*   110 */    53,   10,   24,   25,   33,   14,   36,   36,   38,   66,
 /*   120 */    12,   33,   65,   15,   36,   24,   25,   52,    6,   54,
 /*   130 */     8,    6,   10,    8,   10,   10,   14,   36,   18,   14,
 /*   140 */    11,   44,   67,   46,   47,   16,   24,   25,   51,   24,
 /*   150 */    25,    1,   55,   56,   34,   35,    7,    8,   36,   44,
 /*   160 */    11,   36,   47,   66,   10,    6,   51,    8,   19,   10,
 /*   170 */    55,   56,   23,   14,   20,   44,   24,   46,   47,   64,
 /*   180 */    34,   35,   51,   24,   25,   11,   55,   56,   38,   44,
 /*   190 */    16,   46,   47,   24,   47,   36,   51,   66,   51,    5,
 /*   200 */    55,   56,   55,   56,    7,    8,   44,   38,   46,   47,
 /*   210 */    13,   66,   44,   51,   46,   47,   19,   55,   56,   51,
 /*   220 */     7,    8,   20,   55,   56,    6,   45,    8,   66,   10,
 /*   230 */    49,   18,   19,   14,   66,   59,   60,   61,   62,   63,
 /*   240 */    34,   35,   44,   24,   25,   47,    5,   12,   11,   51,
 /*   250 */    15,   34,   35,   55,   56,   36,   24,   22,   44,   24,
 /*   260 */    44,   47,   24,   47,   61,   51,   17,   51,   68,   55,
 /*   270 */    56,   55,   56,   38,   44,   42,   43,   47,   60,   58,
 /*   280 */    44,   51,   61,   47,   14,   55,   56,   51,   22,   63,
 /*   290 */    44,   55,   56,   47,   71,   48,   65,   51,    7,    8,
 /*   300 */    44,   55,   56,   47,   49,   47,   44,   51,   10,   47,
 /*   310 */    19,   55,   56,   51,   23,   47,   44,   55,   56,   47,
 /*   320 */    72,   23,   44,   51,   72,   47,   72,   55,   56,   51,
 /*   330 */    72,   72,   44,   55,   56,   47,    7,    8,   44,   51,
 /*   340 */    11,   47,   72,   55,   56,   51,   72,   72,   19,   55,
 /*   350 */    56,    5,   72,    7,    8,    5,   72,    7,    8,   47,
 /*   360 */    72,   72,   72,   51,   72,   19,   72,   55,   56,   19,
 /*   370 */    40,   41,   42,   43,   72,   72,   12,   72,   72,   15,
 /*   380 */    72,   72,   72,   72,   72,   72,   72,   72,   24,   72,
 /*   390 */    72,   72,   72,   72,   72,   72,   72,   72,   72,   72,
 /*   400 */    72,   72,   38,
);
    const YY_SHIFT_USE_DFLT = -8;
    const YY_SHIFT_MAX = 83;
    static public $yy_shift_ofst = array(
 /*     0 */    31,  159,   81,   88,   47,   47,   47,   47,  159,   52,
 /*    10 */   219,  122,  122,  122,  122,  122,  122,  125,  122,  122,
 /*    20 */   122,  122,  122,  235,  101,  101,   -7,   20,  364,  169,
 /*    30 */   149,  197,  350,   31,  291,  329,  346,  213,   37,   37,
 /*    40 */    37,   37,   37,   37,   37,   -3,   37,   37,    2,  150,
 /*    50 */     2,  249,  120,   30,   80,   34,  206,  108,    2,  206,
 /*    60 */    54,  266,  270,  249,  270,  298,  146,  154,  129,  217,
 /*    70 */   174,   70,  238,  241,  237,  124,  232,   27,  124,  202,
 /*    80 */    77,  194,  124,  152,
);
    const YY_REDUCE_USE_DFLT = -54;
    const YY_REDUCE_MAX = 64;
    static public $yy_reduce_ofst = array(
 /*     0 */   330,   23,   97,  145,  162,   53,  168,  131,  -41,  115,
 /*    10 */   -14,  256,  246,  262,  294,  278,  288,  272,  236,  230,
 /*    20 */   214,  216,  198,  176,  147,  312,   75,   75,   25,  -45,
 /*    30 */    49,   49,   49,  233,   49,   49,   49,   49,   49,   49,
 /*    40 */    49,   49,   49,   49,   49,  181,   49,   49,  221,  -40,
 /*    50 */   -53,   57,  200,  200,  223,  247,  200,  218,  203,  200,
 /*    60 */   255,  226,  258,  231,  268,
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
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 9 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 10 */ array(6, 8, 10, 14, 24, 25, 36, ),
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
        /* 23 */ array(12, 15, 22, 24, 38, ),
        /* 24 */ array(6, 10, 14, 24, 25, 36, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, ),
        /* 26 */ array(7, 8, 11, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 27 */ array(7, 8, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 28 */ array(12, 15, 24, 38, ),
        /* 29 */ array(24, 38, ),
        /* 30 */ array(7, 8, 11, 19, 23, ),
        /* 31 */ array(7, 8, 13, 19, ),
        /* 32 */ array(5, 7, 8, 19, ),
        /* 33 */ array(1, 3, 37, 38, ),
        /* 34 */ array(7, 8, 19, 23, ),
        /* 35 */ array(7, 8, 11, 19, ),
        /* 36 */ array(5, 7, 8, 19, ),
        /* 37 */ array(7, 8, 18, 19, ),
        /* 38 */ array(7, 8, 19, ),
        /* 39 */ array(7, 8, 19, ),
        /* 40 */ array(7, 8, 19, ),
        /* 41 */ array(7, 8, 19, ),
        /* 42 */ array(7, 8, 19, ),
        /* 43 */ array(7, 8, 19, ),
        /* 44 */ array(7, 8, 19, ),
        /* 45 */ array(5, 10, 21, ),
        /* 46 */ array(7, 8, 19, ),
        /* 47 */ array(7, 8, 19, ),
        /* 48 */ array(24, 38, ),
        /* 49 */ array(1, 38, ),
        /* 50 */ array(24, 38, ),
        /* 51 */ array(17, ),
        /* 52 */ array(18, 34, 35, ),
        /* 53 */ array(5, 34, 35, ),
        /* 54 */ array(1, 36, 38, ),
        /* 55 */ array(9, 20, ),
        /* 56 */ array(34, 35, ),
        /* 57 */ array(12, 15, ),
        /* 58 */ array(24, 38, ),
        /* 59 */ array(34, 35, ),
        /* 60 */ array(5, 21, ),
        /* 61 */ array(22, ),
        /* 62 */ array(14, ),
        /* 63 */ array(17, ),
        /* 64 */ array(14, ),
        /* 65 */ array(10, 23, ),
        /* 66 */ array(34, 35, ),
        /* 67 */ array(10, 20, ),
        /* 68 */ array(11, 16, ),
        /* 69 */ array(34, 35, ),
        /* 70 */ array(11, 16, ),
        /* 71 */ array(20, ),
        /* 72 */ array(24, ),
        /* 73 */ array(5, ),
        /* 74 */ array(11, ),
        /* 75 */ array(10, ),
        /* 76 */ array(24, ),
        /* 77 */ array(11, ),
        /* 78 */ array(10, ),
        /* 79 */ array(20, ),
        /* 80 */ array(5, ),
        /* 81 */ array(5, ),
        /* 82 */ array(10, ),
        /* 83 */ array(24, ),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(),
        /* 87 */ array(),
        /* 88 */ array(),
        /* 89 */ array(),
        /* 90 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   228,  228,  228,  228,  228,  228,  228,  228,  228,  228,
 /*    10 */   228,  228,  228,  228,  228,  228,  228,  228,  228,  228,
 /*    20 */   228,  228,  228,  178,  228,  228,  205,  205,  178,  228,
 /*    30 */   220,  228,  228,  145,  220,  228,  228,  228,  157,  195,
 /*    40 */   206,  196,  161,  221,  222,  228,  200,  182,  228,  228,
 /*    50 */   228,  166,  228,  228,  228,  228,  207,  179,  191,  228,
 /*    60 */   228,  188,  228,  167,  228,  228,  204,  228,  228,  202,
 /*    70 */   228,  228,  228,  228,  201,  228,  228,  201,  162,  173,
 /*    80 */   228,  228,  186,  228,  211,  203,  148,  210,  199,  164,
 /*    90 */   146,  213,  212,  214,  147,  156,  201,  215,  209,  159,
 /*   100 */   158,  154,  149,  150,  151,  216,  175,  189,  192,  184,
 /*   110 */   226,  227,  169,  172,  225,  170,  190,  180,  193,  183,
 /*   120 */   194,  185,  187,  197,  186,  198,  152,  174,  218,  223,
 /*   130 */   155,  173,  165,  171,  168,  219,  176,  181,  160,  153,
 /*   140 */   163,  224,  217,  177,  208,
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
    const YYNSTATE = 145;
    const YYNRULE = 83;
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
  'params',        'modparameter',  'ifexpr',        'ifcond',      
  'lop',           'arrayelements',  'arrayelement',  'other',       
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
 /*  57 */ "ifexprs ::= NOT ifexpr",
 /*  58 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  59 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  60 */ "ifexpr ::= expr",
 /*  61 */ "ifexpr ::= expr ifcond expr",
 /*  62 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  63 */ "ifcond ::= EQUALS",
 /*  64 */ "ifcond ::= NOTEQUALS",
 /*  65 */ "ifcond ::= GREATERTHAN",
 /*  66 */ "ifcond ::= LESSTHAN",
 /*  67 */ "ifcond ::= GREATEREQUAL",
 /*  68 */ "ifcond ::= LESSEQUAL",
 /*  69 */ "ifcond ::= IDENTITY",
 /*  70 */ "lop ::= LAND",
 /*  71 */ "lop ::= LOR",
 /*  72 */ "array ::= OPENP arrayelements CLOSEP",
 /*  73 */ "arrayelements ::= arrayelement",
 /*  74 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  75 */ "arrayelement ::= expr",
 /*  76 */ "arrayelement ::= expr APTR expr",
 /*  77 */ "arrayelement ::= ID APTR expr",
 /*  78 */ "arrayelement ::= array",
 /*  79 */ "doublequoted ::= doublequoted other",
 /*  80 */ "doublequoted ::= other",
 /*  81 */ "other ::= LDEL variable RDEL",
 /*  82 */ "other ::= OTHER",
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
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 4 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
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
        73 => 0,
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
        80 => 1,
        82 => 1,
        2 => 2,
        36 => 2,
        79 => 2,
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
        75 => 13,
        78 => 13,
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
        58 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        62 => 61,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        74 => 74,
        76 => 76,
        77 => 76,
        81 => 81,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 57 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1305 "internal.templateparser.php"
#line 63 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1308 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1311 "internal.templateparser.php"
#line 82 "internal.templateparser.y"
    function yy_r6(){ $this->_retvalue = $this->smarty->compile_variable_output($this->yystack[$this->yyidx + -1]->minor);    }
#line 1314 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1317 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1320 "internal.templateparser.php"
#line 88 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1323 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1326 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1329 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1332 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1335 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1338 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1341 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1344 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = "\$this->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1347 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1350 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1353 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1356 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1359 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1362 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1365 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1368 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1371 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r40(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1374 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r42(){$this->_retvalue = "(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1377 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1380 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1383 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1386 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1389 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1392 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1395 "internal.templateparser.php"
#line 205 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1398 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1401 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1404 "internal.templateparser.php"
#line 220 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1407 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1410 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1413 "internal.templateparser.php"
#line 226 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1416 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '==';    }
#line 1419 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!=';    }
#line 1422 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '>';    }
#line 1425 "internal.templateparser.php"
#line 232 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '<';    }
#line 1428 "internal.templateparser.php"
#line 233 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '>=';    }
#line 1431 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '<=';    }
#line 1434 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '===';    }
#line 1437 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '&&';    }
#line 1440 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '||';    }
#line 1443 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1446 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1449 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1452 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1455 "internal.templateparser.php"

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
#line 1572 "internal.templateparser.php"
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
#line 1597 "internal.templateparser.php"
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
