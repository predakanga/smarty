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
    const TP_OTHER                          =  1;
    const TP_LDELS                          =  2;
    const TP_RDELS                          =  3;
    const TP_RDEL                           =  4;
    const TP_NUMBER                         =  5;
    const TP_MINUS                          =  6;
    const TP_PLUS                           =  7;
    const TP_STAR                           =  8;
    const TP_SLASH                          =  9;
    const TP_PERCENT                        = 10;
    const TP_OPENP                          = 11;
    const TP_CLOSEP                         = 12;
    const TP_OPENB                          = 13;
    const TP_CLOSEB                         = 14;
    const TP_DOLLAR                         = 15;
    const TP_DOT                            = 16;
    const TP_COMMA                          = 17;
    const TP_COLON                          = 18;
    const TP_SEMICOLON                      = 19;
    const TP_VERT                           = 20;
    const TP_EQUAL                          = 21;
    const TP_SPACE                          = 22;
    const TP_PTR                            = 23;
    const TP_APTR                           = 24;
    const TP_ID                             = 25;
    const TP_SI_QSTR                        = 26;
    const TP_DB_QSTR                        = 27;
    const TP_EQUALS                         = 28;
    const TP_NOTEQUALS                      = 29;
    const TP_GREATERTHAN                    = 30;
    const TP_LESSTHAN                       = 31;
    const TP_GREATEREQUAL                   = 32;
    const TP_LESSEQUAL                      = 33;
    const TP_IDENTITY                       = 34;
    const TP_NOT                            = 35;
    const TP_LAND                           = 36;
    const TP_LOR                            = 37;
    const TP_PHP                            = 38;
    const TP_LDEL                           = 39;
    const TP_IFTAG                          = 40;
    const TP_ELSEIFTAG                      = 41;
    const TP_FORTAG                         = 42;
    const YY_NO_ACTION = 218;
    const YY_ACCEPT_ACTION = 217;
    const YY_ERROR_ACTION = 216;

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
 /*     0 */   110,  121,  107,  104,   59,  106,  114,  110,  121,  107,
 /*    10 */   104,  214,   42,   45,   77,   44,  136,   63,   12,  133,
 /*    20 */    18,   77,  103,  123,  122,  125,  126,  128,  127,  103,
 /*    30 */   123,  122,  125,  126,  128,  127,   24,   73,   53,   67,
 /*    40 */    84,   98,   11,  100,  131,  113,   99,  129,   86,   87,
 /*    50 */    25,    8,   51,   67,   83,   98,   81,   66,  117,  113,
 /*    60 */    99,  129,   73,   42,   86,   87,   44,   25,   83,   50,
 /*    70 */    67,   62,   98,   97,   23,   15,  113,   99,  129,    7,
 /*    80 */   112,   61,   68,   43,  118,   83,   97,   23,   86,   87,
 /*    90 */    70,  120,    7,   74,  138,  124,   43,   75,  115,   97,
 /*   100 */    23,   92,   47,    9,   21,    1,   41,  138,  124,   43,
 /*   110 */    94,   90,  110,  121,  107,  104,   17,   79,  114,   74,
 /*   120 */   138,  124,   26,   96,   69,   67,   77,   98,   11,   14,
 /*   130 */    12,  113,   99,  129,   91,  119,   25,    8,   57,   67,
 /*   140 */    13,   98,   46,   66,  117,  113,   99,  129,   25,  135,
 /*   150 */    48,   67,   54,   98,   83,  134,   88,  113,   99,  129,
 /*   160 */    95,   10,    5,    3,   60,   85,   83,   47,   25,   21,
 /*   170 */    49,   67,    2,   98,   97,   23,  132,  113,   99,  129,
 /*   180 */     7,   97,   23,   22,   43,    4,   83,    7,   86,   87,
 /*   190 */    11,   43,   72,  108,   74,  138,  124,  137,   76,   78,
 /*   200 */    58,   71,  138,  124,   81,   42,   30,   64,   44,   67,
 /*   210 */   111,   98,  150,   19,  150,  113,   99,  129,   86,   87,
 /*   220 */    35,   55,  130,   67,  150,   98,  150,  150,  116,  113,
 /*   230 */    99,  129,  110,  121,  107,  104,   65,   97,  150,  110,
 /*   240 */   121,  107,  104,   20,  150,  105,   77,   43,   56,  130,
 /*   250 */    12,   36,  150,   77,   67,  150,   98,   74,  138,  124,
 /*   260 */   113,   99,  129,  101,  150,  110,  121,  107,  104,  110,
 /*   270 */   121,  107,  104,  150,  110,  121,  107,  104,  150,   77,
 /*   280 */    82,  150,    6,   77,   39,  150,  150,   67,   77,   98,
 /*   290 */    52,  130,  150,  113,   99,  129,   29,  150,   47,   67,
 /*   300 */    21,   98,  150,  150,  150,  113,   99,  129,  110,  121,
 /*   310 */   107,  104,   16,  150,  114,   40,  150,  150,   67,  150,
 /*   320 */    98,  150,   77,  150,  113,   99,  129,   67,   32,   80,
 /*   330 */   150,   67,  150,   98,   99,  129,  150,  113,   99,  129,
 /*   340 */   150,   67,   27,  102,  150,   67,  150,   98,   99,  129,
 /*   350 */   150,  113,   99,  129,   28,  150,  150,   67,  150,   98,
 /*   360 */   150,  150,  150,  113,   99,  129,   67,   31,  109,  150,
 /*   370 */    67,  150,   98,   99,  129,  150,  113,   99,  129,  150,
 /*   380 */   150,   89,  150,  110,  121,  107,  104,  150,  150,  150,
 /*   390 */   150,   34,  150,  150,   67,  150,   98,   77,  150,  150,
 /*   400 */   113,   99,  129,   37,  150,  150,   67,  150,   98,  150,
 /*   410 */   150,  150,  113,   99,  129,  150,   38,  150,  150,   67,
 /*   420 */   150,   98,  150,  150,  150,  113,   99,  129,  110,  121,
 /*   430 */   107,  104,  150,  150,  150,  150,  217,   33,   93,   90,
 /*   440 */   150,  150,   77,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,    7,    8,    9,   62,   63,   12,    6,    7,    8,
 /*    10 */     9,   17,   13,   15,   20,   16,    4,   51,   24,    4,
 /*    20 */    21,   20,   28,   29,   30,   31,   32,   33,   34,   28,
 /*    30 */    29,   30,   31,   32,   33,   34,   48,   22,   50,   51,
 /*    40 */    12,   53,   11,   65,    4,   57,   58,   59,   36,   37,
 /*    50 */    48,   11,   50,   51,   66,   53,   25,   69,   70,   57,
 /*    60 */    58,   59,   22,   13,   36,   37,   16,   48,   66,   50,
 /*    70 */    51,   19,   53,    5,    6,   21,   57,   58,   59,   11,
 /*    80 */    12,   55,   25,   15,   25,   66,    5,    6,   36,   37,
 /*    90 */     9,   65,   11,   25,   26,   27,   15,    7,   12,    5,
 /*   100 */     6,    4,   54,   17,   56,   11,   25,   26,   27,   15,
 /*   110 */    46,   47,    6,    7,    8,    9,   68,    6,   12,   25,
 /*   120 */    26,   27,   48,    1,   25,   51,   20,   53,   11,   35,
 /*   130 */    24,   57,   58,   59,    4,   12,   48,   11,   50,   51,
 /*   140 */    17,   53,   25,   69,   70,   57,   58,   59,   48,    4,
 /*   150 */    50,   51,   49,   53,   66,   52,    4,   57,   58,   59,
 /*   160 */    38,   39,   40,   41,   42,   61,   66,   54,   48,   56,
 /*   170 */    50,   51,   67,   53,    5,    6,   52,   57,   58,   59,
 /*   180 */    11,    5,    6,   18,   15,   67,   66,   11,   36,   37,
 /*   190 */    11,   15,   25,   14,   25,   26,   27,   63,    6,    7,
 /*   200 */    23,   25,   26,   27,   25,   13,   48,   51,   16,   51,
 /*   210 */    59,   53,   71,   21,   71,   57,   58,   59,   36,   37,
 /*   220 */    48,   60,   61,   51,   71,   53,   71,   71,   70,   57,
 /*   230 */    58,   59,    6,    7,    8,    9,   64,    5,   71,    6,
 /*   240 */     7,    8,    9,   11,   71,   12,   20,   15,   60,   61,
 /*   250 */    24,   48,   71,   20,   51,   71,   53,   25,   26,   27,
 /*   260 */    57,   58,   59,    4,   71,    6,    7,    8,    9,    6,
 /*   270 */     7,    8,    9,   71,    6,    7,    8,    9,   71,   20,
 /*   280 */    12,   71,   19,   20,   48,   71,   71,   51,   20,   53,
 /*   290 */    60,   61,   71,   57,   58,   59,   48,   71,   54,   51,
 /*   300 */    56,   53,   71,   71,   71,   57,   58,   59,    6,    7,
 /*   310 */     8,    9,   68,   71,   12,   48,   71,   71,   51,   71,
 /*   320 */    53,   71,   20,   71,   57,   58,   59,   51,   48,   53,
 /*   330 */    71,   51,   71,   53,   58,   59,   71,   57,   58,   59,
 /*   340 */    71,   51,   48,   53,   71,   51,   71,   53,   58,   59,
 /*   350 */    71,   57,   58,   59,   48,   71,   71,   51,   71,   53,
 /*   360 */    71,   71,   71,   57,   58,   59,   51,   48,   53,   71,
 /*   370 */    51,   71,   53,   58,   59,   71,   57,   58,   59,   71,
 /*   380 */    71,    4,   71,    6,    7,    8,    9,   71,   71,   71,
 /*   390 */    71,   48,   71,   71,   51,   71,   53,   20,   71,   71,
 /*   400 */    57,   58,   59,   48,   71,   71,   51,   71,   53,   71,
 /*   410 */    71,   71,   57,   58,   59,   71,   48,   71,   71,   51,
 /*   420 */    71,   53,   71,   71,   71,   57,   58,   59,    6,    7,
 /*   430 */     8,    9,   71,   71,   71,   71,   44,   45,   46,   47,
 /*   440 */    71,   71,   20,
);
    const YY_SHIFT_USE_DFLT = -7;
    const YY_SHIFT_MAX = 79;
    static public $yy_shift_ofst = array(
 /*     0 */   122,   94,   94,   94,   94,   94,   94,  169,   68,  169,
 /*    10 */    81,  169,  169,  169,  169,  176,  169,  169,  169,  169,
 /*    20 */   169,  232,  232,  232,   -6,    1,  106,  259,  263,  268,
 /*    30 */   226,  302,  233,  122,  377,  422,  422,  422,  422,  422,
 /*    40 */   422,   40,   31,  117,   31,   31,  177,  165,   28,  152,
 /*    50 */    12,   52,  179,  182,   15,   31,   31,  182,  167,  177,
 /*    60 */    -2,  165,   -2,  192,   -1,  123,   86,   50,   54,  145,
 /*    70 */    99,  126,  126,   57,  126,  130,  111,   59,   90,   97,
);
    const YY_REDUCE_USE_DFLT = -59;
    const YY_REDUCE_MAX = 62;
    static public $yy_reduce_ofst = array(
 /*     0 */   392,  -12,   88,  120,  100,   19,    2,   74,  172,  158,
 /*    10 */   294,  280,  236,  203,  355,  368,  267,  248,  306,  343,
 /*    20 */   319,  276,  315,  290,   48,  244,  113,  113,  113,  113,
 /*    30 */   113,  113,  113,   64,  113,  113,  113,  113,  113,  113,
 /*    40 */   113,  103,  230,  161,  188,  161,  -58,   26,  105,  105,
 /*    50 */   105,  105,  104,  118,  124,  104,  104,  105,  151,  134,
 /*    60 */   156,  -22,  -34,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 38, 39, 40, 41, 42, ),
        /* 1 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 2 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 3 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 4 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 5 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 6 */ array(5, 6, 11, 15, 25, 26, 27, 35, ),
        /* 7 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 8 */ array(5, 6, 11, 12, 15, 25, 26, 27, ),
        /* 9 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 10 */ array(5, 6, 9, 11, 15, 25, 26, 27, ),
        /* 11 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 12 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 13 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 14 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 15 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 16 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 17 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 18 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 19 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 20 */ array(5, 6, 11, 15, 25, 26, 27, ),
        /* 21 */ array(5, 11, 15, 25, 26, 27, ),
        /* 22 */ array(5, 11, 15, 25, 26, 27, ),
        /* 23 */ array(5, 11, 15, 25, 26, 27, ),
        /* 24 */ array(6, 7, 8, 9, 12, 17, 20, 24, 28, 29, 30, 31, 32, 33, 34, ),
        /* 25 */ array(6, 7, 8, 9, 20, 28, 29, 30, 31, 32, 33, 34, ),
        /* 26 */ array(6, 7, 8, 9, 12, 20, 24, ),
        /* 27 */ array(4, 6, 7, 8, 9, 20, ),
        /* 28 */ array(6, 7, 8, 9, 19, 20, ),
        /* 29 */ array(6, 7, 8, 9, 12, 20, ),
        /* 30 */ array(6, 7, 8, 9, 20, 24, ),
        /* 31 */ array(6, 7, 8, 9, 12, 20, ),
        /* 32 */ array(6, 7, 8, 9, 12, 20, ),
        /* 33 */ array(1, 38, 39, 40, 41, 42, ),
        /* 34 */ array(4, 6, 7, 8, 9, 20, ),
        /* 35 */ array(6, 7, 8, 9, 20, ),
        /* 36 */ array(6, 7, 8, 9, 20, ),
        /* 37 */ array(6, 7, 8, 9, 20, ),
        /* 38 */ array(6, 7, 8, 9, 20, ),
        /* 39 */ array(6, 7, 8, 9, 20, ),
        /* 40 */ array(6, 7, 8, 9, 20, ),
        /* 41 */ array(4, 11, 22, ),
        /* 42 */ array(11, 25, ),
        /* 43 */ array(11, 25, ),
        /* 44 */ array(11, 25, ),
        /* 45 */ array(11, 25, ),
        /* 46 */ array(23, ),
        /* 47 */ array(18, ),
        /* 48 */ array(12, 36, 37, ),
        /* 49 */ array(4, 36, 37, ),
        /* 50 */ array(4, 36, 37, ),
        /* 51 */ array(19, 36, 37, ),
        /* 52 */ array(11, 14, 25, ),
        /* 53 */ array(36, 37, ),
        /* 54 */ array(4, 22, ),
        /* 55 */ array(11, 25, ),
        /* 56 */ array(11, 25, ),
        /* 57 */ array(36, 37, ),
        /* 58 */ array(25, ),
        /* 59 */ array(23, ),
        /* 60 */ array(15, ),
        /* 61 */ array(18, ),
        /* 62 */ array(15, ),
        /* 63 */ array(6, 7, 13, 16, 21, ),
        /* 64 */ array(13, 16, 21, ),
        /* 65 */ array(12, 17, ),
        /* 66 */ array(12, 17, ),
        /* 67 */ array(13, 16, ),
        /* 68 */ array(21, ),
        /* 69 */ array(4, ),
        /* 70 */ array(25, ),
        /* 71 */ array(11, ),
        /* 72 */ array(11, ),
        /* 73 */ array(25, ),
        /* 74 */ array(11, ),
        /* 75 */ array(4, ),
        /* 76 */ array(6, ),
        /* 77 */ array(25, ),
        /* 78 */ array(7, ),
        /* 79 */ array(4, ),
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
        /* 134 */ array(),
        /* 135 */ array(),
        /* 136 */ array(),
        /* 137 */ array(),
        /* 138 */ array(),
);
    static public $yy_default = array(
 /*     0 */   216,  216,  216,  216,  216,  216,  216,  216,  216,  216,
 /*    10 */   216,  216,  216,  216,  216,  216,  216,  216,  216,  216,
 /*    20 */   216,  216,  216,  216,  198,  198,  214,  216,  216,  200,
 /*    30 */   214,  216,  216,  139,  216,  189,  190,  199,  156,  215,
 /*    40 */   200,  216,  216,  216,  216,  216,  180,  160,  216,  216,
 /*    50 */   216,  216,  216,  216,  216,  175,  176,  196,  216,  182,
 /*    60 */   216,  161,  216,  216,  216,  216,  216,  170,  216,  216,
 /*    70 */   216,  157,  185,  216,  216,  216,  216,  216,  216,  216,
 /*    80 */   162,  180,  201,  195,  197,  178,  209,  210,  150,  151,
 /*    90 */   142,  152,  153,  140,  141,  143,  144,  168,  158,  171,
 /*   100 */   193,  145,  159,  202,  167,  181,  183,  166,  177,  194,
 /*   110 */   165,  186,  188,  163,  169,  211,  213,  212,  191,  187,
 /*   120 */   192,  164,  204,  203,  173,  205,  206,  208,  207,  174,
 /*   130 */   179,  147,  155,  146,  154,  148,  149,  184,  172,
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
    const YYNOCODE = 72;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 139;
    const YYNRULE = 77;
    const YYERRORSYMBOL = 43;
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
    1,  /*      RDELS => OTHER */
    1,  /*       RDEL => OTHER */
    1,  /*     NUMBER => OTHER */
    1,  /*      MINUS => OTHER */
    1,  /*       PLUS => OTHER */
    1,  /*       STAR => OTHER */
    1,  /*      SLASH => OTHER */
    1,  /*    PERCENT => OTHER */
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
    1,  /*    DB_QSTR => OTHER */
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
    0,  /*        PHP => nothing */
    0,  /*       LDEL => nothing */
    0,  /*      IFTAG => nothing */
    0,  /*  ELSEIFTAG => nothing */
    0,  /*     FORTAG => nothing */
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
  '$',             'OTHER',         'LDELS',         'RDELS',       
  'RDEL',          'NUMBER',        'MINUS',         'PLUS',        
  'STAR',          'SLASH',         'PERCENT',       'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'SEMICOLON',   
  'VERT',          'EQUAL',         'SPACE',         'PTR',         
  'APTR',          'ID',            'SI_QSTR',       'DB_QSTR',     
  'EQUALS',        'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',    
  'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',      'NOT',         
  'LAND',          'LOR',           'PHP',           'LDEL',        
  'IFTAG',         'ELSEIFTAG',     'FORTAG',        'error',       
  'start',         'input',         'single',        'smartytag',   
  'expr',          'attributes',    'ifexprs',       'variable',    
  'attribute',     'value',         'modifier',      'modparameters',
  'math',          'array',         'method',        'function',    
  'varids',        'varid',         'methodchain',   'methodelement',
  'params',        'modparameter',  'ifexpr',        'lop',         
  'ifcond',        'arrayelements',  'arrayelement',
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "start ::= input",
 /*   1 */ "input ::= single",
 /*   2 */ "input ::= input single",
 /*   3 */ "single ::= smartytag",
 /*   4 */ "single ::= PHP",
 /*   5 */ "single ::= OTHER",
 /*   6 */ "smartytag ::= LDEL expr RDEL",
 /*   7 */ "smartytag ::= LDEL ID attributes RDEL",
 /*   8 */ "smartytag ::= LDEL ID RDEL",
 /*   9 */ "smartytag ::= LDEL SLASH ID RDEL",
 /*  10 */ "smartytag ::= IFTAG ifexprs RDEL",
 /*  11 */ "smartytag ::= ELSEIFTAG ifexprs RDEL",
 /*  12 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable EQUAL expr RDEL",
 /*  13 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable PLUS PLUS RDEL",
 /*  14 */ "smartytag ::= FORTAG variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable MINUS MINUS RDEL",
 /*  15 */ "attributes ::= attribute",
 /*  16 */ "attributes ::= attributes attribute",
 /*  17 */ "attribute ::= SPACE ID EQUAL expr",
 /*  18 */ "attribute ::= SPACE ID EQUAL ID",
 /*  19 */ "expr ::= value",
 /*  20 */ "expr ::= MINUS value",
 /*  21 */ "expr ::= expr modifier",
 /*  22 */ "expr ::= expr modifier modparameters",
 /*  23 */ "expr ::= expr math value",
 /*  24 */ "expr ::= array",
 /*  25 */ "math ::= PLUS",
 /*  26 */ "math ::= MINUS",
 /*  27 */ "math ::= STAR",
 /*  28 */ "math ::= SLASH",
 /*  29 */ "value ::= NUMBER",
 /*  30 */ "value ::= OPENP expr CLOSEP",
 /*  31 */ "value ::= variable",
 /*  32 */ "value ::= method",
 /*  33 */ "value ::= SI_QSTR",
 /*  34 */ "value ::= DB_QSTR",
 /*  35 */ "value ::= function",
 /*  36 */ "variable ::= DOLLAR varids",
 /*  37 */ "variable ::= variable DOT varids",
 /*  38 */ "variable ::= variable OPENB varids CLOSEB",
 /*  39 */ "varids ::= varids varid",
 /*  40 */ "varids ::= varid",
 /*  41 */ "varid ::= ID",
 /*  42 */ "varid ::= OPENP expr CLOSEP",
 /*  43 */ "method ::= DOLLAR ID methodchain",
 /*  44 */ "methodchain ::= methodelement",
 /*  45 */ "methodchain ::= methodchain methodelement",
 /*  46 */ "methodelement ::= PTR ID",
 /*  47 */ "methodelement ::= PTR function",
 /*  48 */ "function ::= ID OPENP params CLOSEP",
 /*  49 */ "function ::= ID OPENP CLOSEP",
 /*  50 */ "params ::= expr",
 /*  51 */ "params ::= params COMMA expr",
 /*  52 */ "modifier ::= VERT ID",
 /*  53 */ "modparameters ::= modparameter",
 /*  54 */ "modparameters ::= modparameters modparameter",
 /*  55 */ "modparameter ::= COLON value",
 /*  56 */ "ifexprs ::= ifexpr",
 /*  57 */ "ifexprs ::= ifexprs lop ifexprs",
 /*  58 */ "ifexprs ::= OPENP ifexprs lop ifexprs CLOSEP",
 /*  59 */ "ifexpr ::= expr",
 /*  60 */ "ifexpr ::= NOT expr",
 /*  61 */ "ifexpr ::= expr ifcond expr",
 /*  62 */ "ifexpr ::= OPENP expr ifcond expr CLOSEP",
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
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 11 ),
  array( 'lhs' => 47, 'rhs' => 11 ),
  array( 'lhs' => 47, 'rhs' => 11 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 5 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 5 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
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
        24 => 0,
        29 => 0,
        31 => 0,
        32 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        73 => 0,
        1 => 1,
        3 => 1,
        4 => 1,
        5 => 1,
        40 => 1,
        41 => 1,
        53 => 1,
        55 => 1,
        56 => 1,
        2 => 2,
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
        50 => 15,
        75 => 15,
        16 => 16,
        17 => 17,
        18 => 17,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        30 => 30,
        36 => 36,
        37 => 37,
        38 => 38,
        39 => 39,
        42 => 42,
        43 => 43,
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
        61 => 57,
        58 => 58,
        59 => 59,
        60 => 60,
        62 => 62,
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
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 56 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1284 "internal.templateparser.php"
#line 58 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1287 "internal.templateparser.php"
#line 59 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1290 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r6(){ $this->_retvalue = "<?php echo ". $this->yystack[$this->yyidx + -1]->minor .";?>\n";    }
#line 1293 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r7(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1296 "internal.templateparser.php"
#line 67 "internal.templateparser.y"
    function yy_r8(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0))) ."\n ";    }
#line 1299 "internal.templateparser.php"
#line 68 "internal.templateparser.y"
    function yy_r9(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1302 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r10(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'if'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1305 "internal.templateparser.php"
#line 70 "internal.templateparser.y"
    function yy_r11(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'elseif'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1308 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r12(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'='.$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1311 "internal.templateparser.php"
#line 72 "internal.templateparser.y"
    function yy_r13(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'++'))) ."\n ";    }
#line 1314 "internal.templateparser.php"
#line 73 "internal.templateparser.y"
    function yy_r14(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'--'))) ."\n ";    }
#line 1317 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1320 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1323 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1326 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = "-".$this->yystack[$this->yyidx + 0]->minor;     }
#line 1329 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1332 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1335 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1338 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "+";    }
#line 1341 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = "-";    }
#line 1344 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = "*";    }
#line 1347 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = "/";    }
#line 1350 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1353 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1356 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor ."[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1359 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor ."[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1362 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r39(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1365 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r42(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;    }
#line 1368 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1371 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1374 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1377 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1380 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1383 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1386 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1389 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1392 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1395 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1398 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1401 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1404 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1407 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor;    }
#line 1410 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '==';    }
#line 1413 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!=';    }
#line 1416 "internal.templateparser.php"
#line 149 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '>';    }
#line 1419 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '<';    }
#line 1422 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '>=';    }
#line 1425 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '<=';    }
#line 1428 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '===';    }
#line 1431 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '&&';    }
#line 1434 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '||';    }
#line 1437 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1440 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1443 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1446 "internal.templateparser.php"

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
   $match = preg_split("/\n/", $this->lex->data); 
    echo "<br>Syntax Error on line " . $this->lex->line ." template ".$compiler->_compiler_status->current_tpl_filepath.'<p style="font-family:courier">'.$match[$this->lex->line-1]."<br>";
//    for ($i=1;$i<$this->lex->counter;$i++) echo '&nbsp';
    echo '</p>';    
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
#line 1582 "internal.templateparser.php"
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
#line 1607 "internal.templateparser.php"
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