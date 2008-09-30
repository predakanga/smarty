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
    const TP_FORTAG                         =  7;
    const TP_EQUAL                          =  8;
    const TP_SEMICOLON                      =  9;
    const TP_PLUS                           = 10;
    const TP_MINUS                          = 11;
    const TP_SPACE                          = 12;
    const TP_STAR                           = 13;
    const TP_NUMBER                         = 14;
    const TP_OPENP                          = 15;
    const TP_CLOSEP                         = 16;
    const TP_SI_QSTR                        = 17;
    const TP_DB_QSTR                        = 18;
    const TP_DOLLAR                         = 19;
    const TP_DOT                            = 20;
    const TP_OPENB                          = 21;
    const TP_CLOSEB                         = 22;
    const TP_PTR                            = 23;
    const TP_COMMA                          = 24;
    const TP_VERT                           = 25;
    const TP_COLON                          = 26;
    const TP_NOT                            = 27;
    const TP_EQUALS                         = 28;
    const TP_NOTEQUALS                      = 29;
    const TP_GREATERTHAN                    = 30;
    const TP_LESSTHAN                       = 31;
    const TP_GREATEREQUAL                   = 32;
    const TP_LESSEQUAL                      = 33;
    const TP_IDENTITY                       = 34;
    const TP_LAND                           = 35;
    const TP_LOR                            = 36;
    const TP_APTR                           = 37;
    const TP_OTHER                          = 38;
    const YY_NO_ACTION = 209;
    const YY_ACCEPT_ACTION = 208;
    const YY_ERROR_ACTION = 207;

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
    const YY_SZ_ACTTAB = 445;
static public $yy_action = array(
 /*     0 */    97,   58,   76,   85,   56,   99,   95,   94,  112,   93,
 /*    10 */    23,  113,   91,   80,    7,  105,  115,   86,   43,    8,
 /*    20 */   204,   75,   87,   88,  129,  128,  130,  131,  132,  127,
 /*    30 */   126,   97,  119,   13,   40,   68,   59,   95,   94,   46,
 /*    40 */    93,   21,   23,   74,   15,   80,    7,   73,  115,   86,
 /*    50 */    43,  120,   75,   16,  117,  129,  128,  130,  131,  132,
 /*    60 */   127,  126,   70,   24,   77,   53,   64,    8,   84,   12,
 /*    70 */    97,  119,  101,  106,   81,   45,   95,   94,   76,   93,
 /*    80 */    89,  133,   91,   15,   66,   79,   23,   15,    9,   80,
 /*    90 */     1,   75,  115,   86,   43,   25,   57,   47,   64,  102,
 /*   100 */    84,  107,   19,   13,  101,  106,   81,   61,  123,   25,
 /*   110 */    69,   54,   64,  133,   84,   41,   42,   90,  101,  106,
 /*   120 */    81,   26,   87,   88,   64,   14,   84,  133,   96,  116,
 /*   130 */   101,  106,   81,   92,   46,   25,   21,   51,   64,   77,
 /*   140 */    84,   82,   66,   79,  101,  106,   81,   25,   11,   50,
 /*   150 */    64,   55,   84,  133,  118,    6,  101,  106,   81,  122,
 /*   160 */    64,   25,  108,   49,   64,  133,   84,  106,   81,   76,
 /*   170 */   101,  106,   81,    2,   87,   88,   46,   23,   21,  133,
 /*   180 */    80,    7,  114,  115,   86,   43,   29,   52,   99,   64,
 /*   190 */    67,   84,   64,   63,  111,  101,  106,   81,   23,  106,
 /*   200 */    81,   80,    7,   22,  115,   86,   43,   39,   83,   44,
 /*   210 */    64,  103,   84,   97,   76,   78,  101,  106,   81,   95,
 /*   220 */    94,  124,   93,   65,   62,   80,   20,  141,  115,   86,
 /*   230 */    43,   97,   87,   88,   75,   97,  141,   95,   94,   97,
 /*   240 */    93,   95,   94,  104,   93,   95,   94,  121,   93,   10,
 /*   250 */   141,   91,   75,    3,    5,   60,   75,  141,  109,  141,
 /*   260 */    75,   97,  141,   98,   13,   97,    4,   95,   94,   97,
 /*   270 */    93,   95,   94,  141,   93,   95,   94,  141,   93,  208,
 /*   280 */   110,  125,   75,  141,   38,  141,   75,   64,  141,   84,
 /*   290 */    75,   87,   88,  101,  106,   81,  141,  141,   18,  141,
 /*   300 */    71,   72,  141,  141,   30,  141,  141,   64,  141,   84,
 /*   310 */    41,   42,   97,  101,  106,   81,  141,   31,   95,   94,
 /*   320 */    64,   93,   84,   17,   48,   99,  101,  106,   81,   32,
 /*   330 */   141,  141,   64,   75,   84,   41,   42,  141,  101,  106,
 /*   340 */    81,   64,  141,  100,  141,  141,  141,   28,  106,   81,
 /*   350 */    64,  141,   84,  141,  141,  141,  101,  106,   81,  141,
 /*   360 */    34,  141,  141,   64,  141,   84,  141,  141,  141,  101,
 /*   370 */   106,   81,   37,  141,  141,   64,  141,   84,  141,  141,
 /*   380 */   141,  101,  106,   81,  141,  141,  141,  141,  141,  141,
 /*   390 */    35,  141,  141,   64,  141,   84,  141,  141,  141,  101,
 /*   400 */   106,   81,  141,   36,  141,  141,   64,  141,   84,  141,
 /*   410 */   141,  141,  101,  106,   81,   27,  141,  141,   64,  141,
 /*   420 */    84,  141,  141,  141,  101,  106,   81,  141,  141,  141,
 /*   430 */   141,  141,  141,   33,  141,  141,   64,  141,   84,  141,
 /*   440 */   141,  141,  101,  106,   81,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,   49,    3,   16,   54,   55,   10,   11,   57,   13,
 /*    10 */    11,   59,   16,   14,   15,   16,   17,   18,   19,   15,
 /*    20 */    24,   25,   35,   36,   28,   29,   30,   31,   32,   33,
 /*    30 */    34,    4,    3,   37,    3,    4,   23,   10,   11,   48,
 /*    40 */    13,   50,   11,    3,   15,   14,   15,   11,   17,   18,
 /*    50 */    19,   22,   25,   62,    2,   28,   29,   30,   31,   32,
 /*    60 */    33,   34,    3,   42,   12,   44,   45,   15,   47,    8,
 /*    70 */     4,    3,   51,   52,   53,    3,   10,   11,    3,   13,
 /*    80 */    16,   60,   16,   15,   63,   64,   11,   15,   24,   14,
 /*    90 */    15,   25,   17,   18,   19,   42,    9,   44,   45,    3,
 /*   100 */    47,    2,   27,   37,   51,   52,   53,   56,   57,   42,
 /*   110 */    10,   44,   45,   60,   47,   20,   21,   16,   51,   52,
 /*   120 */    53,   42,   35,   36,   45,   24,   47,   60,    2,    2,
 /*   130 */    51,   52,   53,    2,   48,   42,   50,   44,   45,   12,
 /*   140 */    47,    2,   63,   64,   51,   52,   53,   42,   62,   44,
 /*   150 */    45,   43,   47,   60,   46,   61,   51,   52,   53,   55,
 /*   160 */    45,   42,   47,   44,   45,   60,   47,   52,   53,    3,
 /*   170 */    51,   52,   53,   61,   35,   36,   48,   11,   50,   60,
 /*   180 */    14,   15,   46,   17,   18,   19,   42,   54,   55,   45,
 /*   190 */     3,   47,   45,   45,   47,   51,   52,   53,   11,   52,
 /*   200 */    53,   14,   15,   26,   17,   18,   19,   42,   64,   19,
 /*   210 */    45,    2,   47,    4,    3,    3,   51,   52,   53,   10,
 /*   220 */    11,   53,   13,   58,   45,   14,   15,   66,   17,   18,
 /*   230 */    19,    4,   35,   36,   25,    4,   66,   10,   11,    4,
 /*   240 */    13,   10,   11,   59,   13,   10,   11,   16,   13,    1,
 /*   250 */    66,   16,   25,    5,    6,    7,   25,   66,    2,   66,
 /*   260 */    25,    4,   66,    2,   37,    4,    9,   10,   11,    4,
 /*   270 */    13,   10,   11,   66,   13,   10,   11,   66,   13,   40,
 /*   280 */    41,   16,   25,   66,   42,   66,   25,   45,   66,   47,
 /*   290 */    25,   35,   36,   51,   52,   53,   66,   66,    8,   66,
 /*   300 */    10,   11,   66,   66,   42,   66,   66,   45,   66,   47,
 /*   310 */    20,   21,    4,   51,   52,   53,   66,   42,   10,   11,
 /*   320 */    45,   13,   47,    8,   54,   55,   51,   52,   53,   42,
 /*   330 */    66,   66,   45,   25,   47,   20,   21,   66,   51,   52,
 /*   340 */    53,   45,   66,   47,   66,   66,   66,   42,   52,   53,
 /*   350 */    45,   66,   47,   66,   66,   66,   51,   52,   53,   66,
 /*   360 */    42,   66,   66,   45,   66,   47,   66,   66,   66,   51,
 /*   370 */    52,   53,   42,   66,   66,   45,   66,   47,   66,   66,
 /*   380 */    66,   51,   52,   53,   66,   66,   66,   66,   66,   66,
 /*   390 */    42,   66,   66,   45,   66,   47,   66,   66,   66,   51,
 /*   400 */    52,   53,   66,   42,   66,   66,   45,   66,   47,   66,
 /*   410 */    66,   66,   51,   52,   53,   42,   66,   66,   45,   66,
 /*   420 */    47,   66,   66,   66,   51,   52,   53,   66,   66,   66,
 /*   430 */    66,   66,   66,   42,   66,   66,   45,   66,   47,   66,
 /*   440 */    66,   66,   51,   52,   53,
);
    const YY_SHIFT_USE_DFLT = -14;
    const YY_SHIFT_MAX = 78;
    static public $yy_shift_ofst = array(
 /*     0 */   248,   75,   75,   75,   75,   75,   75,  166,   -1,  166,
 /*    10 */    31,  166,  187,  166,  166,  166,  166,  166,  166,  166,
 /*    20 */   166,  211,  211,  211,   -4,   27,   66,  261,  257,  227,
 /*    30 */   265,  235,  231,  209,  308,  308,  308,  308,  308,  308,
 /*    40 */    52,   68,   68,   72,   68,   13,  177,  139,   29,  256,
 /*    50 */   -13,   87,   68,  197,  197,  127,   68,  190,  177,  212,
 /*    60 */   190,   13,  290,  315,   95,  101,   64,    4,   40,  131,
 /*    70 */    61,  100,   36,  126,   99,   96,    4,   59,    4,
);
    const YY_REDUCE_USE_DFLT = -51;
    const YY_REDUCE_MAX = 61;
    static public $yy_reduce_ofst = array(
 /*     0 */   239,   21,  105,  119,   93,   53,   67,   79,  165,  144,
 /*    10 */   391,  330,  242,  361,  318,  287,  262,  305,  373,  348,
 /*    20 */   275,  147,  115,  296,   -9,   86,  128,  128,  128,  128,
 /*    30 */   128,  128,  128,  128,  128,  128,  128,  128,  128,  128,
 /*    40 */   108,  133,  270,  -50,  -50,   51,  -48,   94,  104,   94,
 /*    50 */    94,   94,  104,  112,   94,  136,  104,  179,  184,  168,
 /*    60 */   148,  -49,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 5, 6, 7, ),
        /* 1 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 2 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 3 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 4 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 5 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 6 */ array(3, 11, 14, 15, 17, 18, 19, 27, ),
        /* 7 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 8 */ array(3, 11, 14, 15, 16, 17, 18, 19, ),
        /* 9 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 10 */ array(3, 4, 11, 14, 15, 17, 18, 19, ),
        /* 11 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 12 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 13 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 14 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 15 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 16 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 17 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 18 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 19 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 20 */ array(3, 11, 14, 15, 17, 18, 19, ),
        /* 21 */ array(3, 14, 15, 17, 18, 19, ),
        /* 22 */ array(3, 14, 15, 17, 18, 19, ),
        /* 23 */ array(3, 14, 15, 17, 18, 19, ),
        /* 24 */ array(4, 10, 11, 13, 16, 24, 25, 28, 29, 30, 31, 32, 33, 34, 37, ),
        /* 25 */ array(4, 10, 11, 13, 25, 28, 29, 30, 31, 32, 33, 34, ),
        /* 26 */ array(4, 10, 11, 13, 16, 25, 37, ),
        /* 27 */ array(2, 4, 10, 11, 13, 25, ),
        /* 28 */ array(4, 9, 10, 11, 13, 25, ),
        /* 29 */ array(4, 10, 11, 13, 25, 37, ),
        /* 30 */ array(4, 10, 11, 13, 16, 25, ),
        /* 31 */ array(4, 10, 11, 13, 16, 25, ),
        /* 32 */ array(4, 10, 11, 13, 16, 25, ),
        /* 33 */ array(2, 4, 10, 11, 13, 25, ),
        /* 34 */ array(4, 10, 11, 13, 25, ),
        /* 35 */ array(4, 10, 11, 13, 25, ),
        /* 36 */ array(4, 10, 11, 13, 25, ),
        /* 37 */ array(4, 10, 11, 13, 25, ),
        /* 38 */ array(4, 10, 11, 13, 25, ),
        /* 39 */ array(4, 10, 11, 13, 25, ),
        /* 40 */ array(2, 12, 15, ),
        /* 41 */ array(3, 15, ),
        /* 42 */ array(3, 15, ),
        /* 43 */ array(3, 15, ),
        /* 44 */ array(3, 15, ),
        /* 45 */ array(23, ),
        /* 46 */ array(26, ),
        /* 47 */ array(2, 35, 36, ),
        /* 48 */ array(3, 15, 22, ),
        /* 49 */ array(2, 35, 36, ),
        /* 50 */ array(16, 35, 36, ),
        /* 51 */ array(9, 35, 36, ),
        /* 52 */ array(3, 15, ),
        /* 53 */ array(35, 36, ),
        /* 54 */ array(35, 36, ),
        /* 55 */ array(2, 12, ),
        /* 56 */ array(3, 15, ),
        /* 57 */ array(19, ),
        /* 58 */ array(26, ),
        /* 59 */ array(3, ),
        /* 60 */ array(19, ),
        /* 61 */ array(23, ),
        /* 62 */ array(8, 10, 11, 20, 21, ),
        /* 63 */ array(8, 20, 21, ),
        /* 64 */ array(20, 21, ),
        /* 65 */ array(16, 24, ),
        /* 66 */ array(16, 24, ),
        /* 67 */ array(15, ),
        /* 68 */ array(3, ),
        /* 69 */ array(2, ),
        /* 70 */ array(8, ),
        /* 71 */ array(10, ),
        /* 72 */ array(11, ),
        /* 73 */ array(2, ),
        /* 74 */ array(2, ),
        /* 75 */ array(3, ),
        /* 76 */ array(15, ),
        /* 77 */ array(3, ),
        /* 78 */ array(15, ),
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
);
    static public $yy_default = array(
 /*     0 */   207,  207,  207,  207,  207,  207,  207,  207,  207,  207,
 /*    10 */   207,  207,  207,  207,  207,  207,  207,  207,  207,  207,
 /*    20 */   207,  207,  207,  207,  188,  188,  204,  207,  207,  204,
 /*    30 */   190,  207,  207,  207,  180,  189,  205,  190,  146,  179,
 /*    40 */   207,  207,  207,  207,  207,  170,  150,  207,  207,  207,
 /*    50 */   207,  207,  166,  207,  186,  207,  165,  207,  151,  207,
 /*    60 */   207,  172,  207,  207,  160,  207,  207,  147,  207,  207,
 /*    70 */   207,  207,  207,  207,  207,  207,  207,  207,  175,  202,
 /*    80 */   158,  164,  140,  203,  148,  187,  163,  199,  200,  201,
 /*    90 */   177,  159,  142,  156,  155,  154,  143,  157,  141,  169,
 /*   100 */   149,  153,  181,  135,  183,  178,  161,  138,  184,  139,
 /*   110 */   134,  152,  174,  182,  145,  162,  136,  137,  144,  170,
 /*   120 */   167,  171,  168,  173,  176,  191,  198,  197,  193,  192,
 /*   130 */   194,  195,  196,  185,
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
    const YYNOCODE = 67;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 134;
    const YYNRULE = 73;
    const YYERRORSYMBOL = 39;
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
  'SLASH',         'IFTAG',         'ELSEIFTAG',     'FORTAG',      
  'EQUAL',         'SEMICOLON',     'PLUS',          'MINUS',       
  'SPACE',         'STAR',          'NUMBER',        'OPENP',       
  'CLOSEP',        'SI_QSTR',       'DB_QSTR',       'DOLLAR',      
  'DOT',           'OPENB',         'CLOSEB',        'PTR',         
  'COMMA',         'VERT',          'COLON',         'NOT',         
  'EQUALS',        'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',    
  'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',      'LAND',        
  'LOR',           'APTR',          'OTHER',         'error',       
  'start',         'smartytag',     'expr',          'attributes',  
  'ifexprs',       'variable',      'attribute',     'value',       
  'modifier',      'modparameters',  'math',          'array',       
  'method',        'function',      'varids',        'varid',       
  'methodchain',   'methodelement',  'params',        'modparameter',
  'ifexpr',        'lop',           'ifcond',        'arrayelements',
  'arrayelement',  'other',       
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
 /*   7 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable EQUAL expr RDEL",
 /*   8 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable PLUS PLUS RDEL",
 /*   9 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable MINUS MINUS RDEL",
 /*  10 */ "attributes ::= attribute",
 /*  11 */ "attributes ::= attributes attribute",
 /*  12 */ "attribute ::= SPACE ID EQUAL expr",
 /*  13 */ "attribute ::= SPACE ID EQUAL ID",
 /*  14 */ "expr ::= value",
 /*  15 */ "expr ::= MINUS value",
 /*  16 */ "expr ::= expr modifier",
 /*  17 */ "expr ::= expr modifier modparameters",
 /*  18 */ "expr ::= expr math value",
 /*  19 */ "expr ::= array",
 /*  20 */ "math ::= PLUS",
 /*  21 */ "math ::= MINUS",
 /*  22 */ "math ::= STAR",
 /*  23 */ "math ::= SLASH",
 /*  24 */ "value ::= NUMBER",
 /*  25 */ "value ::= OPENP expr CLOSEP",
 /*  26 */ "value ::= variable",
 /*  27 */ "value ::= method",
 /*  28 */ "value ::= SI_QSTR",
 /*  29 */ "value ::= DB_QSTR",
 /*  30 */ "value ::= function",
 /*  31 */ "variable ::= DOLLAR varids",
 /*  32 */ "variable ::= variable DOT varids",
 /*  33 */ "variable ::= variable OPENB varids CLOSEB",
 /*  34 */ "varids ::= varids varid",
 /*  35 */ "varids ::= varid",
 /*  36 */ "varid ::= ID",
 /*  37 */ "varid ::= OPENP expr CLOSEP",
 /*  38 */ "method ::= DOLLAR ID methodchain",
 /*  39 */ "methodchain ::= methodelement",
 /*  40 */ "methodchain ::= methodchain methodelement",
 /*  41 */ "methodelement ::= PTR ID",
 /*  42 */ "methodelement ::= PTR function",
 /*  43 */ "function ::= ID OPENP params CLOSEP",
 /*  44 */ "function ::= ID OPENP CLOSEP",
 /*  45 */ "params ::= expr",
 /*  46 */ "params ::= params COMMA expr",
 /*  47 */ "modifier ::= VERT ID",
 /*  48 */ "modparameters ::= modparameter",
 /*  49 */ "modparameters ::= modparameters modparameter",
 /*  50 */ "modparameter ::= COLON value",
 /*  51 */ "ifexprs ::= ifexpr",
 /*  52 */ "ifexprs ::= ifexprs lop ifexprs",
 /*  53 */ "ifexprs ::= OPENP ifexprs lop ifexprs CLOSEP",
 /*  54 */ "ifexpr ::= expr",
 /*  55 */ "ifexpr ::= NOT expr",
 /*  56 */ "ifexpr ::= expr ifcond expr",
 /*  57 */ "ifexpr ::= OPENP expr ifcond expr CLOSEP",
 /*  58 */ "ifcond ::= EQUALS",
 /*  59 */ "ifcond ::= NOTEQUALS",
 /*  60 */ "ifcond ::= GREATERTHAN",
 /*  61 */ "ifcond ::= LESSTHAN",
 /*  62 */ "ifcond ::= GREATEREQUAL",
 /*  63 */ "ifcond ::= LESSEQUAL",
 /*  64 */ "ifcond ::= IDENTITY",
 /*  65 */ "lop ::= LAND",
 /*  66 */ "lop ::= LOR",
 /*  67 */ "array ::= OPENP arrayelements CLOSEP",
 /*  68 */ "arrayelements ::= arrayelement",
 /*  69 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  70 */ "arrayelement ::= expr",
 /*  71 */ "arrayelement ::= expr APTR expr",
 /*  72 */ "other ::= OTHER",
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
  array( 'lhs' => 41, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 4 ),
  array( 'lhs' => 41, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 4 ),
  array( 'lhs' => 41, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 11 ),
  array( 'lhs' => 41, 'rhs' => 11 ),
  array( 'lhs' => 41, 'rhs' => 11 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 4 ),
  array( 'lhs' => 46, 'rhs' => 4 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 3 ),
  array( 'lhs' => 42, 'rhs' => 3 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 3 ),
  array( 'lhs' => 45, 'rhs' => 4 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 5 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 5 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        14 => 0,
        19 => 0,
        24 => 0,
        26 => 0,
        27 => 0,
        28 => 0,
        29 => 0,
        30 => 0,
        68 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        45 => 10,
        70 => 10,
        11 => 11,
        12 => 12,
        13 => 12,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        25 => 25,
        31 => 31,
        32 => 32,
        33 => 33,
        34 => 34,
        35 => 35,
        36 => 35,
        48 => 35,
        50 => 35,
        51 => 35,
        37 => 37,
        38 => 38,
        39 => 39,
        40 => 40,
        41 => 41,
        42 => 41,
        43 => 43,
        44 => 44,
        46 => 46,
        47 => 47,
        49 => 49,
        52 => 52,
        56 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        57 => 57,
        58 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        69 => 69,
        71 => 71,
        72 => 72,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 52 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1219 "internal.templateparser.php"
#line 54 "internal.templateparser.y"
    function yy_r1(){ $this->_retvalue = "<?php echo ". $this->yystack[$this->yyidx + -1]->minor .";?>\n";    }
#line 1222 "internal.templateparser.php"
#line 55 "internal.templateparser.y"
    function yy_r2(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1225 "internal.templateparser.php"
#line 56 "internal.templateparser.y"
    function yy_r3(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0))) ."\n ";    }
#line 1228 "internal.templateparser.php"
#line 57 "internal.templateparser.y"
    function yy_r4(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1231 "internal.templateparser.php"
#line 58 "internal.templateparser.y"
    function yy_r5(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'if'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1234 "internal.templateparser.php"
#line 59 "internal.templateparser.y"
    function yy_r6(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'elseif'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1237 "internal.templateparser.php"
#line 60 "internal.templateparser.y"
    function yy_r7(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'='.$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1240 "internal.templateparser.php"
#line 61 "internal.templateparser.y"
    function yy_r8(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'++'))) ."\n ";    }
#line 1243 "internal.templateparser.php"
#line 62 "internal.templateparser.y"
    function yy_r9(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'--'))) ."\n ";    }
#line 1246 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1249 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1252 "internal.templateparser.php"
#line 68 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1255 "internal.templateparser.php"
#line 72 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = "-".$this->yystack[$this->yyidx + 0]->minor;     }
#line 1258 "internal.templateparser.php"
#line 73 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1261 "internal.templateparser.php"
#line 74 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1264 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1267 "internal.templateparser.php"
#line 78 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = "+";    }
#line 1270 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = "-";    }
#line 1273 "internal.templateparser.php"
#line 80 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = "*";    }
#line 1276 "internal.templateparser.php"
#line 81 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = "/";    }
#line 1279 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1282 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1285 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor ."[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1288 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor ."[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1291 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r34(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1294 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r35(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1297 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r37(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;    }
#line 1300 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1303 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r39(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1306 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r40(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1309 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1312 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1315 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1318 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1321 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1324 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1327 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1330 "internal.templateparser.php"
#line 129 "internal.templateparser.y"
    function yy_r53(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1333 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1336 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r55(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1339 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor;    }
#line 1342 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '==';    }
#line 1345 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = '!=';    }
#line 1348 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '>';    }
#line 1351 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '<';    }
#line 1354 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = '>=';    }
#line 1357 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '<=';    }
#line 1360 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '===';    }
#line 1363 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '&&';    }
#line 1366 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '||';    }
#line 1369 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1372 "internal.templateparser.php"
#line 149 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1375 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1378 "internal.templateparser.php"

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
#line 1513 "internal.templateparser.php"
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
#line 1538 "internal.templateparser.php"
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