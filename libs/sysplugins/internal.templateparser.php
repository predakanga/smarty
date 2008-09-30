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
#line 3 "internal.templateparser.y"
class Smarty_Internal_Templateparser#line 102 "internal.templateparser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 5 "internal.templateparser.y"

    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex) {
        $this->lex = $lex;
    }
#line 118 "internal.templateparser.php"

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const TP_LDEL                           =  1;
    const TP_RDEL                           =  2;
    const TP_ID                             =  3;
    const TP_SLASH                          =  4;
    const TP_IFTAG                          =  5;
    const TP_ELSEIFTAG                      =  6;
    const TP_SPACE                          =  7;
    const TP_EQUAL                          =  8;
    const TP_MINUS                          =  9;
    const TP_PLUS                           = 10;
    const TP_STAR                           = 11;
    const TP_NUMBER                         = 12;
    const TP_OPENP                          = 13;
    const TP_CLOSEP                         = 14;
    const TP_SI_QSTR                        = 15;
    const TP_DB_QSTR                        = 16;
    const TP_DOLLAR                         = 17;
    const TP_DOT                            = 18;
    const TP_OPENB                          = 19;
    const TP_CLOSEB                         = 20;
    const TP_PTR                            = 21;
    const TP_COMMA                          = 22;
    const TP_VERT                           = 23;
    const TP_COLON                          = 24;
    const TP_NOT                            = 25;
    const TP_EQUALS                         = 26;
    const TP_NOTEQUALS                      = 27;
    const TP_GREATERTHAN                    = 28;
    const TP_LESSTHAN                       = 29;
    const TP_GREATEREQUAL                   = 30;
    const TP_LESSEQUAL                      = 31;
    const TP_IDENTITY                       = 32;
    const TP_LAND                           = 33;
    const TP_LOR                            = 34;
    const TP_APTR                           = 35;
    const TP_OTHER                          = 36;
    const YY_NO_ACTION = 188;
    const YY_ACCEPT_ACTION = 187;
    const YY_ERROR_ACTION = 186;

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
    const YY_SZ_ACTTAB = 399;
static public $yy_action = array(
 /*     0 */    86,  111,   60,   38,   37,   84,   85,   83,   19,   16,
 /*    10 */    88,   71,    6,  114,   95,   97,   36,   20,  183,   63,
 /*    20 */    93,   79,   74,   75,   81,   73,   76,   77,   80,   86,
 /*    30 */    14,   10,   35,   56,   84,   85,   83,   82,   19,    7,
 /*    40 */    65,   71,    6,  113,   95,   97,   36,   87,   63,   50,
 /*    50 */   108,   74,   75,   81,   73,   76,   77,   80,   39,   21,
 /*    60 */    18,   47,   68,  106,   49,   91,   15,   89,   53,   94,
 /*    70 */    96,   66,   70,   13,   22,   61,   42,   72,  106,   99,
 /*    80 */    54,  109,   89,   53,   94,   96,   67,    8,   39,   22,
 /*    90 */    18,   43,   72,  106,   66,   70,  103,   89,   53,   94,
 /*   100 */    96,   58,   23,   11,    9,   93,  106,   72,    3,    5,
 /*   110 */    89,   53,   94,   96,  101,   14,   60,   66,   70,   53,
 /*   120 */    94,   96,   19,   54,  109,   71,    1,   93,   95,   97,
 /*   130 */    36,   39,   22,   18,   46,   52,  106,   14,   12,   57,
 /*   140 */    89,   53,   94,   96,  187,  112,  100,   22,   62,   44,
 /*   150 */    72,  106,   45,   91,   86,   89,   53,   94,   96,   84,
 /*   160 */    85,   83,   60,    2,   88,   72,   41,   91,   19,  102,
 /*   170 */    40,   71,    6,   63,   95,   97,   36,   59,   51,   64,
 /*   180 */    14,   90,    4,   19,   25,   10,   71,    6,  106,   95,
 /*   190 */    97,   36,   89,   53,   94,   96,   92,  104,   66,   70,
 /*   200 */    29,   53,   94,   96,  106,  136,  110,  136,   89,   53,
 /*   210 */    94,   96,   48,   86,  115,   60,   55,  136,   84,   85,
 /*   220 */    83,  136,  136,  107,   71,   17,  136,   95,   97,   36,
 /*   230 */    86,  136,   63,  105,  136,   84,   85,   83,   58,   69,
 /*   240 */    78,   86,  136,  136,    7,   86,   84,   85,   83,   63,
 /*   250 */    84,   85,   83,  136,  136,   88,   86,  136,  136,  136,
 /*   260 */    63,   84,   85,   83,   63,  136,  136,  136,   28,  136,
 /*   270 */   136,  136,  106,  136,  136,   63,   89,   53,   94,   96,
 /*   280 */    34,  136,  136,  136,  106,  136,  136,   10,   89,   53,
 /*   290 */    94,   96,  136,   30,  136,  136,  136,  106,  136,  136,
 /*   300 */   136,   89,   53,   94,   96,   24,  136,  136,  136,  106,
 /*   310 */   136,  136,  136,   89,   53,   94,   96,   32,  136,  136,
 /*   320 */   136,  106,  136,  136,  136,   89,   53,   94,   96,   31,
 /*   330 */   136,  136,  136,  106,  136,  136,  136,   89,   53,   94,
 /*   340 */    96,  136,   27,  136,  136,  136,  106,  136,  136,  136,
 /*   350 */    89,   53,   94,   96,   26,  136,  136,  136,  106,  136,
 /*   360 */   136,  136,   89,   53,   94,   96,   33,  136,  136,  136,
 /*   370 */   106,  136,  136,  136,   89,   53,   94,   96,  136,   86,
 /*   380 */   136,  136,   98,  136,   84,   85,   83,   53,   94,   96,
 /*   390 */   136,  136,  136,  136,  136,  136,  136,  136,   63,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,   14,    3,   18,   19,    9,   10,   11,    9,   22,
 /*    10 */    14,   12,   13,   14,   15,   16,   17,   24,   22,   23,
 /*    20 */     3,   57,   26,   27,   28,   29,   30,   31,   32,    4,
 /*    30 */    13,   35,    3,    4,    9,   10,   11,   20,    9,   13,
 /*    40 */     2,   12,   13,   51,   15,   16,   17,    3,   23,   54,
 /*    50 */    55,   26,   27,   28,   29,   30,   31,   32,   45,   40,
 /*    60 */    47,   42,    2,   44,   52,   53,    8,   48,   49,   50,
 /*    70 */    51,   33,   34,   60,   40,    3,   42,   58,   44,   14,
 /*    80 */    61,   62,   48,   49,   50,   51,    2,   22,   45,   40,
 /*    90 */    47,   42,   58,   44,   33,   34,    2,   48,   49,   50,
 /*   100 */    51,    7,   40,   60,    1,    3,   44,   58,    5,    6,
 /*   110 */    48,   49,   50,   51,   44,   13,    3,   33,   34,   49,
 /*   120 */    50,   51,    9,   61,   62,   12,   13,    3,   15,   16,
 /*   130 */    17,   45,   40,   47,   42,   46,   44,   13,   25,    3,
 /*   140 */    48,   49,   50,   51,   38,   39,   57,   40,    3,   42,
 /*   150 */    58,   44,   52,   53,    4,   48,   49,   50,   51,    9,
 /*   160 */    10,   11,    3,   59,   14,   58,   52,   53,    9,   53,
 /*   170 */     3,   12,   13,   23,   15,   16,   17,    3,   21,   14,
 /*   180 */    13,   55,   59,    9,   40,   35,   12,   13,   44,   15,
 /*   190 */    16,   17,   48,   49,   50,   51,   44,   43,   33,   34,
 /*   200 */    40,   49,   50,   51,   44,   64,   62,   64,   48,   49,
 /*   210 */    50,   51,   41,    4,   43,    3,   56,   64,    9,   10,
 /*   220 */    11,   64,   64,   14,   12,   13,   64,   15,   16,   17,
 /*   230 */     4,   64,   23,    2,   64,    9,   10,   11,    7,    2,
 /*   240 */    14,    4,   64,   64,   13,    4,    9,   10,   11,   23,
 /*   250 */     9,   10,   11,   64,   64,   14,    4,   64,   64,   64,
 /*   260 */    23,    9,   10,   11,   23,   64,   64,   64,   40,   64,
 /*   270 */    64,   64,   44,   64,   64,   23,   48,   49,   50,   51,
 /*   280 */    40,   64,   64,   64,   44,   64,   64,   35,   48,   49,
 /*   290 */    50,   51,   64,   40,   64,   64,   64,   44,   64,   64,
 /*   300 */    64,   48,   49,   50,   51,   40,   64,   64,   64,   44,
 /*   310 */    64,   64,   64,   48,   49,   50,   51,   40,   64,   64,
 /*   320 */    64,   44,   64,   64,   64,   48,   49,   50,   51,   40,
 /*   330 */    64,   64,   64,   44,   64,   64,   64,   48,   49,   50,
 /*   340 */    51,   64,   40,   64,   64,   64,   44,   64,   64,   64,
 /*   350 */    48,   49,   50,   51,   40,   64,   64,   64,   44,   64,
 /*   360 */    64,   64,   48,   49,   50,   51,   40,   64,   64,   64,
 /*   370 */    44,   64,   64,   64,   48,   49,   50,   51,   64,    4,
 /*   380 */    64,   64,   44,   64,    9,   10,   11,   49,   50,   51,
 /*   390 */    64,   64,   64,   64,   64,   64,   64,   64,   23,
);
    const YY_SHIFT_USE_DFLT = -16;
    const YY_SHIFT_MAX = 63;
    static public $yy_shift_ofst = array(
 /*     0 */   103,  113,  113,  113,  113,  113,  159,   -1,  159,   29,
 /*    10 */   159,  159,  159,  159,  159,  174,  159,  159,  212,  212,
 /*    20 */   212,   -4,   25,  150,  226,  252,  237,  241,  209,  375,
 /*    30 */   375,  375,  375,  375,  375,  231,  167,  102,  102,   -7,
 /*    40 */   157,   17,  165,   84,   38,  102,   61,   61,   94,  124,
 /*    50 */   157,  145,   -7,  -15,   65,  -13,   72,   58,  136,   26,
 /*    60 */    26,   60,   26,   44,
);
    const YY_REDUCE_USE_DFLT = -37;
    const YY_REDUCE_MAX = 52;
    static public $yy_reduce_ofst = array(
 /*     0 */   106,   19,   92,  107,   34,   49,   62,  160,  144,  314,
 /*    10 */   253,  326,  277,  265,  228,  240,  289,  302,  152,  338,
 /*    20 */    70,   13,   43,   86,   86,   86,   86,   86,   86,   86,
 /*    30 */    86,   86,   86,   86,   86,  171,  100,  114,   12,   89,
 /*    40 */    -5,  116,  104,  104,  104,  116,  104,  123,  154,  116,
 /*    50 */   126,   -8,  -36,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 5, 6, ),
        /* 1 */ array(3, 9, 12, 13, 15, 16, 17, 25, ),
        /* 2 */ array(3, 9, 12, 13, 15, 16, 17, 25, ),
        /* 3 */ array(3, 9, 12, 13, 15, 16, 17, 25, ),
        /* 4 */ array(3, 9, 12, 13, 15, 16, 17, 25, ),
        /* 5 */ array(3, 9, 12, 13, 15, 16, 17, 25, ),
        /* 6 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 7 */ array(3, 9, 12, 13, 14, 15, 16, 17, ),
        /* 8 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 9 */ array(3, 4, 9, 12, 13, 15, 16, 17, ),
        /* 10 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 11 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 12 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 13 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 14 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 15 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 16 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 17 */ array(3, 9, 12, 13, 15, 16, 17, ),
        /* 18 */ array(3, 12, 13, 15, 16, 17, ),
        /* 19 */ array(3, 12, 13, 15, 16, 17, ),
        /* 20 */ array(3, 12, 13, 15, 16, 17, ),
        /* 21 */ array(4, 9, 10, 11, 14, 22, 23, 26, 27, 28, 29, 30, 31, 32, 35, ),
        /* 22 */ array(4, 9, 10, 11, 23, 26, 27, 28, 29, 30, 31, 32, ),
        /* 23 */ array(4, 9, 10, 11, 14, 23, 35, ),
        /* 24 */ array(4, 9, 10, 11, 14, 23, ),
        /* 25 */ array(4, 9, 10, 11, 23, 35, ),
        /* 26 */ array(2, 4, 9, 10, 11, 23, ),
        /* 27 */ array(4, 9, 10, 11, 14, 23, ),
        /* 28 */ array(4, 9, 10, 11, 14, 23, ),
        /* 29 */ array(4, 9, 10, 11, 23, ),
        /* 30 */ array(4, 9, 10, 11, 23, ),
        /* 31 */ array(4, 9, 10, 11, 23, ),
        /* 32 */ array(4, 9, 10, 11, 23, ),
        /* 33 */ array(4, 9, 10, 11, 23, ),
        /* 34 */ array(4, 9, 10, 11, 23, ),
        /* 35 */ array(2, 7, 13, ),
        /* 36 */ array(3, 13, ),
        /* 37 */ array(3, 13, ),
        /* 38 */ array(3, 13, ),
        /* 39 */ array(24, ),
        /* 40 */ array(21, ),
        /* 41 */ array(3, 13, 20, ),
        /* 42 */ array(14, 33, 34, ),
        /* 43 */ array(2, 33, 34, ),
        /* 44 */ array(2, 33, 34, ),
        /* 45 */ array(3, 13, ),
        /* 46 */ array(33, 34, ),
        /* 47 */ array(33, 34, ),
        /* 48 */ array(2, 7, ),
        /* 49 */ array(3, 13, ),
        /* 50 */ array(21, ),
        /* 51 */ array(3, ),
        /* 52 */ array(24, ),
        /* 53 */ array(18, 19, ),
        /* 54 */ array(14, 22, ),
        /* 55 */ array(14, 22, ),
        /* 56 */ array(3, ),
        /* 57 */ array(8, ),
        /* 58 */ array(3, ),
        /* 59 */ array(13, ),
        /* 60 */ array(13, ),
        /* 61 */ array(2, ),
        /* 62 */ array(13, ),
        /* 63 */ array(3, ),
        /* 64 */ array(),
        /* 65 */ array(),
        /* 66 */ array(),
        /* 67 */ array(),
        /* 68 */ array(),
        /* 69 */ array(),
        /* 70 */ array(),
        /* 71 */ array(),
        /* 72 */ array(),
        /* 73 */ array(),
        /* 74 */ array(),
        /* 75 */ array(),
        /* 76 */ array(),
        /* 77 */ array(),
        /* 78 */ array(),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(),
        /* 83 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   186,  186,  186,  186,  186,  186,  186,  186,  186,  186,
 /*    10 */   186,  186,  186,  186,  186,  186,  186,  186,  186,  186,
 /*    20 */   186,  167,  167,  183,  169,  183,  186,  186,  186,  158,
 /*    30 */   184,  159,  168,  169,  125,  186,  186,  186,  186,  129,
 /*    40 */   149,  186,  186,  186,  186,  144,  165,  186,  186,  145,
 /*    50 */   151,  186,  130,  139,  186,  186,  186,  186,  186,  126,
 /*    60 */   186,  186,  154,  186,  166,  121,  178,  122,  120,  117,
 /*    70 */   179,  137,  164,  174,  171,  172,  175,  176,  170,  162,
 /*    80 */   177,  173,  146,  135,  134,  133,  136,  160,  138,  132,
 /*    90 */   153,  148,  131,  149,  140,  141,  143,  142,  128,  180,
 /*   100 */   161,  163,  147,  118,  124,  119,  127,  150,  152,  181,
 /*   110 */   182,  156,  116,  155,  157,  123,
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
    const YYNOCODE = 65;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 116;
    const YYNRULE = 70;
    const YYERRORSYMBOL = 37;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 0;
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
        self::$yyTracePrompt = '';
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
    static public $yyTokenName = array( 
  '$',             'LDEL',          'RDEL',          'ID',          
  'SLASH',         'IFTAG',         'ELSEIFTAG',     'SPACE',       
  'EQUAL',         'MINUS',         'PLUS',          'STAR',        
  'NUMBER',        'OPENP',         'CLOSEP',        'SI_QSTR',     
  'DB_QSTR',       'DOLLAR',        'DOT',           'OPENB',       
  'CLOSEB',        'PTR',           'COMMA',         'VERT',        
  'COLON',         'NOT',           'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'LAND',          'LOR',           'APTR',        
  'OTHER',         'error',         'start',         'smartytag',   
  'expr',          'attributes',    'ifexprs',       'attribute',   
  'value',         'modifier',      'modparameters',  'math',        
  'array',         'variable',      'method',        'function',    
  'varids',        'varid',         'methodchain',   'methodelement',
  'params',        'modparameter',  'ifexpr',        'lop',         
  'ifcond',        'arrayelements',  'arrayelement',  'other',       
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "start ::= smartytag",
 /*   1 */ "smartytag ::= LDEL expr RDEL",
 /*   2 */ "smartytag ::= LDEL ID attributes RDEL",
 /*   3 */ "smartytag ::= LDEL ID RDEL",
 /*   4 */ "smartytag ::= LDEL SLASH ID RDEL",
 /*   5 */ "smartytag ::= IFTAG ifexprs RDEL",
 /*   6 */ "smartytag ::= ELSEIFTAG ifexprs RDEL",
 /*   7 */ "attributes ::= attribute",
 /*   8 */ "attributes ::= attributes attribute",
 /*   9 */ "attribute ::= SPACE ID EQUAL expr",
 /*  10 */ "attribute ::= SPACE ID EQUAL ID",
 /*  11 */ "expr ::= value",
 /*  12 */ "expr ::= MINUS value",
 /*  13 */ "expr ::= expr modifier",
 /*  14 */ "expr ::= expr modifier modparameters",
 /*  15 */ "expr ::= expr math value",
 /*  16 */ "expr ::= array",
 /*  17 */ "math ::= PLUS",
 /*  18 */ "math ::= MINUS",
 /*  19 */ "math ::= STAR",
 /*  20 */ "math ::= SLASH",
 /*  21 */ "value ::= NUMBER",
 /*  22 */ "value ::= OPENP expr CLOSEP",
 /*  23 */ "value ::= variable",
 /*  24 */ "value ::= method",
 /*  25 */ "value ::= SI_QSTR",
 /*  26 */ "value ::= DB_QSTR",
 /*  27 */ "value ::= function",
 /*  28 */ "variable ::= DOLLAR varids",
 /*  29 */ "variable ::= variable DOT varids",
 /*  30 */ "variable ::= variable OPENB varids CLOSEB",
 /*  31 */ "varids ::= varids varid",
 /*  32 */ "varids ::= varid",
 /*  33 */ "varid ::= ID",
 /*  34 */ "varid ::= OPENP expr CLOSEP",
 /*  35 */ "method ::= DOLLAR ID methodchain",
 /*  36 */ "methodchain ::= methodelement",
 /*  37 */ "methodchain ::= methodchain methodelement",
 /*  38 */ "methodelement ::= PTR ID",
 /*  39 */ "methodelement ::= PTR function",
 /*  40 */ "function ::= ID OPENP params CLOSEP",
 /*  41 */ "function ::= ID OPENP CLOSEP",
 /*  42 */ "params ::= expr",
 /*  43 */ "params ::= params COMMA expr",
 /*  44 */ "modifier ::= VERT ID",
 /*  45 */ "modparameters ::= modparameter",
 /*  46 */ "modparameters ::= modparameters modparameter",
 /*  47 */ "modparameter ::= COLON value",
 /*  48 */ "ifexprs ::= ifexpr",
 /*  49 */ "ifexprs ::= ifexprs lop ifexprs",
 /*  50 */ "ifexprs ::= OPENP ifexprs lop ifexprs CLOSEP",
 /*  51 */ "ifexpr ::= expr",
 /*  52 */ "ifexpr ::= NOT expr",
 /*  53 */ "ifexpr ::= expr ifcond expr",
 /*  54 */ "ifexpr ::= OPENP expr ifcond expr CLOSEP",
 /*  55 */ "ifcond ::= EQUALS",
 /*  56 */ "ifcond ::= NOTEQUALS",
 /*  57 */ "ifcond ::= GREATERTHAN",
 /*  58 */ "ifcond ::= LESSTHAN",
 /*  59 */ "ifcond ::= GREATEREQUAL",
 /*  60 */ "ifcond ::= LESSEQUAL",
 /*  61 */ "ifcond ::= IDENTITY",
 /*  62 */ "lop ::= LAND",
 /*  63 */ "lop ::= LOR",
 /*  64 */ "array ::= OPENP arrayelements CLOSEP",
 /*  65 */ "arrayelements ::= arrayelement",
 /*  66 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  67 */ "arrayelement ::= expr",
 /*  68 */ "arrayelement ::= expr APTR expr",
 /*  69 */ "other ::= OTHER",
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
  array( 'lhs' => 38, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 3 ),
  array( 'lhs' => 39, 'rhs' => 4 ),
  array( 'lhs' => 39, 'rhs' => 3 ),
  array( 'lhs' => 39, 'rhs' => 4 ),
  array( 'lhs' => 39, 'rhs' => 3 ),
  array( 'lhs' => 39, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 1 ),
  array( 'lhs' => 41, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 40, 'rhs' => 1 ),
  array( 'lhs' => 40, 'rhs' => 2 ),
  array( 'lhs' => 40, 'rhs' => 2 ),
  array( 'lhs' => 40, 'rhs' => 3 ),
  array( 'lhs' => 40, 'rhs' => 3 ),
  array( 'lhs' => 40, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 4 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 3 ),
  array( 'lhs' => 42, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 5 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        11 => 0,
        16 => 0,
        21 => 0,
        23 => 0,
        24 => 0,
        25 => 0,
        26 => 0,
        27 => 0,
        65 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        42 => 7,
        67 => 7,
        8 => 8,
        9 => 9,
        10 => 9,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        22 => 22,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        33 => 32,
        45 => 32,
        47 => 32,
        48 => 32,
        34 => 34,
        35 => 35,
        36 => 36,
        37 => 37,
        38 => 38,
        39 => 38,
        40 => 40,
        41 => 41,
        43 => 43,
        44 => 44,
        46 => 46,
        49 => 49,
        53 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        58 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        63 => 63,
        64 => 64,
        66 => 66,
        68 => 68,
        69 => 69,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 52 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1175 "internal.templateparser.php"
#line 54 "internal.templateparser.y"
    function yy_r1(){ $this->_retvalue = "<?php echo ". $this->yystack[$this->yyidx + -1]->minor .";?>\n";    }
#line 1178 "internal.templateparser.php"
#line 55 "internal.templateparser.y"
    function yy_r2(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1181 "internal.templateparser.php"
#line 56 "internal.templateparser.y"
    function yy_r3(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0))) ."\n ";    }
#line 1184 "internal.templateparser.php"
#line 57 "internal.templateparser.y"
    function yy_r4(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1187 "internal.templateparser.php"
#line 58 "internal.templateparser.y"
    function yy_r5(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'if'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1190 "internal.templateparser.php"
#line 59 "internal.templateparser.y"
    function yy_r6(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'elseif'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1193 "internal.templateparser.php"
#line 62 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1196 "internal.templateparser.php"
#line 63 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1199 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1202 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = "-".$this->yystack[$this->yyidx + 0]->minor;     }
#line 1205 "internal.templateparser.php"
#line 70 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1208 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1211 "internal.templateparser.php"
#line 72 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1214 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = "+";    }
#line 1217 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = "-";    }
#line 1220 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = "*";    }
#line 1223 "internal.templateparser.php"
#line 78 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = "/";    }
#line 1226 "internal.templateparser.php"
#line 81 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1229 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1232 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor ."[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1235 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor ."[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1238 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r31(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1241 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r32(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1244 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r34(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;    }
#line 1247 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r35(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1250 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r36(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1253 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r37(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1256 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1259 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1262 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1265 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1268 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1271 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1274 "internal.templateparser.php"
#line 125 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1277 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1280 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1283 "internal.templateparser.php"
#line 129 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1286 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor;    }
#line 1289 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r55(){$this->_retvalue = '==';    }
#line 1292 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '!=';    }
#line 1295 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '>';    }
#line 1298 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '<';    }
#line 1301 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = '>=';    }
#line 1304 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '<=';    }
#line 1307 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '===';    }
#line 1310 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = '&&';    }
#line 1313 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '||';    }
#line 1316 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1319 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1322 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1325 "internal.templateparser.php"

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
#line 28 "internal.templateparser.y"

//    var_dump($this);
    $this->internalError = true;
    $compiler = Smarty_Internal_Compiler::instance();
//    echo "<br>Syntax Error on line " . $this->lex->line . ": token '" . 
//        $this->lex->value . "' count ".$this->lex->counter.'<p style="font-family:courier">'.$this->lex->data."<br>";
    echo "<br>Syntax Error on line " . $compiler->_compiler_status->current_line ." template ".$compiler->_compiler_status->current_tpl_filepath.'<p style="font-family:courier">'.$this->lex->data."<br>";
    for ($i=1;$i<$this->lex->counter;$i++) echo '&nbsp';
    echo '^</p>';    
//    echo " while parsing rule: ";
//    foreach ($this->yystack as $entry) {
//        echo $this->tokenName($entry->major) . '->';
//    }
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
//	echo "<br>";	
//    throw new Exception('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN. '), expected one of: ' . implode(',', $expect));
    echo 'Unexpected "' . $TOKEN. '", expected one of: ' . implode(',', $expect)."<br>";
    echo "Compilation terminated";
    die();
#line 1460 "internal.templateparser.php"
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
#line 20 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //    echo $this->retvalue."\n\n";
#line 1485 "internal.templateparser.php"
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