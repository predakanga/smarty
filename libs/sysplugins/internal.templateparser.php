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
    const TP_EQUALS                         = 27;
    const TP_NOTEQUALS                      = 28;
    const TP_GREATERTHAN                    = 29;
    const TP_LESSTHAN                       = 30;
    const TP_GREATEREQUAL                   = 31;
    const TP_LESSEQUAL                      = 32;
    const TP_IDENTITY                       = 33;
    const TP_NOT                            = 34;
    const TP_LAND                           = 35;
    const TP_LOR                            = 36;
    const TP_QUOTE                          = 37;
    const TP_PHP                            = 38;
    const TP_LDEL                           = 39;
    const TP_IFTAG                          = 40;
    const TP_ELSEIFTAG                      = 41;
    const TP_FORTAG                         = 42;
    const YY_NO_ACTION = 228;
    const YY_ACCEPT_ACTION = 227;
    const YY_ERROR_ACTION = 226;

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
    const YY_SZ_ACTTAB = 450;
static public $yy_action = array(
 /*     0 */   116,  126,  111,  117,   94,   44,  121,   32,   46,   90,
 /*    10 */    68,  220,  123,   15,   80,  118,  120,  102,   11,  136,
 /*    20 */    19,  129,  128,  131,  132,  134,  133,  144,  116,  126,
 /*    30 */   111,  117,   93,   83,   27,   93,   83,   68,   68,  123,
 /*    40 */   105,   45,   80,  120,  102,  102,  136,  136,    8,  129,
 /*    50 */   128,  131,  132,  134,  133,  144,   70,  124,  127,   68,
 /*    60 */    24,  115,   55,   68,   44,  123,  102,   46,  136,  120,
 /*    70 */   102,   49,  136,   22,   17,   25,   82,   50,   68,   89,
 /*    80 */   123,   67,   70,  124,  120,  102,   13,  136,   48,   42,
 /*    90 */   116,  126,  111,  117,   89,   66,   88,   49,   25,   22,
 /*   100 */    51,   68,  108,  123,   80,  119,   30,  120,  102,   68,
 /*   110 */   136,  123,   18,  141,   23,  120,  102,   89,  136,    7,
 /*   120 */   110,   17,  142,   47,   85,   58,  141,   23,  137,  125,
 /*   130 */    74,   72,    7,   71,  107,  101,   47,  227,   28,   99,
 /*   140 */   106,   73,  141,   23,   61,   26,   43,  107,    1,  141,
 /*   150 */    23,  103,   47,   93,   83,    7,   59,   91,   26,   47,
 /*   160 */    93,   83,   71,  107,   97,  116,  126,  111,  117,   71,
 /*   170 */   107,   16,   63,  112,   26,   25,   75,   54,   68,   80,
 /*   180 */   123,   26,   67,   11,  120,  102,  140,  136,  104,   10,
 /*   190 */     2,    5,   64,   79,   89,   25,  141,   56,   68,   87,
 /*   200 */   123,  138,   20,   86,  120,  102,   47,  136,    8,   57,
 /*   210 */    91,   81,   77,   45,   89,   98,   71,  107,   44,   82,
 /*   220 */    25,   46,   53,   68,   12,  123,   14,  143,   26,  120,
 /*   230 */   102,   60,  136,   37,    4,   92,   68,    3,  123,   89,
 /*   240 */    52,   91,  120,  102,   62,  136,  116,  126,  111,  117,
 /*   250 */    69,  141,  121,  141,   23,  114,   49,   20,   22,    7,
 /*   260 */    80,   47,   17,   47,   11,   93,   83,  139,   68,   96,
 /*   270 */   130,   71,  107,   78,  107,  102,  101,  136,  116,  126,
 /*   280 */   111,  117,   21,   26,  113,   26,  116,  126,  111,  117,
 /*   290 */   135,   84,   80,  116,  126,  111,  117,  122,  109,    6,
 /*   300 */    80,   36,    9,   87,   68,   65,  123,   80,   76,   33,
 /*   310 */   120,  102,   68,  136,  123,  100,  106,   45,  120,  102,
 /*   320 */   156,  136,  156,  156,  156,  116,  126,  111,  117,  156,
 /*   330 */   156,  121,   40,  156,  156,   68,  156,  123,  156,   80,
 /*   340 */    41,  120,  102,   68,  136,  123,  156,  156,   29,  120,
 /*   350 */   102,   68,  136,  123,  156,  156,   39,  120,  102,   68,
 /*   360 */   136,  123,  156,  156,  156,  120,  102,  156,  136,  156,
 /*   370 */    31,  156,  156,   68,  156,  123,  156,  156,  156,  120,
 /*   380 */   102,  156,  136,  156,   95,  156,  116,  126,  111,  117,
 /*   390 */   156,  156,   38,  156,  156,   68,  156,  123,  156,  156,
 /*   400 */    80,  120,  102,  156,  136,   34,  156,  156,   68,  156,
 /*   410 */   123,  156,  156,  156,  120,  102,  156,  136,  156,   35,
 /*   420 */   156,  156,   68,  156,  123,  156,  156,  156,  120,  102,
 /*   430 */   156,  136,  156,  156,  156,  116,  126,  111,  117,  156,
 /*   440 */   156,  156,  156,  156,  156,  156,  156,  156,  156,   80,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,    7,    8,    9,    4,   13,   12,   48,   16,   12,
 /*    10 */    51,   17,   53,   21,   20,   12,   57,   58,   24,   60,
 /*    20 */    17,   27,   28,   29,   30,   31,   32,   33,    6,    7,
 /*    30 */     8,    9,   35,   36,   48,   35,   36,   51,   51,   53,
 /*    40 */    53,   15,   20,   57,   58,   58,   60,   60,   11,   27,
 /*    50 */    28,   29,   30,   31,   32,   33,   70,   71,    4,   51,
 /*    60 */    48,   53,   50,   51,   13,   53,   58,   16,   60,   57,
 /*    70 */    58,   54,   60,   56,   11,   48,   22,   50,   51,   67,
 /*    80 */    53,   51,   70,   71,   57,   58,   69,   60,   25,   59,
 /*    90 */     6,    7,    8,    9,   67,   51,   12,   54,   48,   56,
 /*   100 */    50,   51,   72,   53,   20,   25,   48,   57,   58,   51,
 /*   110 */    60,   53,   69,    5,    6,   57,   58,   67,   60,   11,
 /*   120 */    12,   11,    4,   15,   14,   49,    5,    6,   52,   71,
 /*   130 */     9,   25,   11,   25,   26,   25,   15,   44,   45,   46,
 /*   140 */    47,    6,    5,    6,   19,   37,   25,   26,   11,    5,
 /*   150 */     6,    1,   15,   35,   36,   11,   61,   62,   37,   15,
 /*   160 */    35,   36,   25,   26,    4,    6,    7,    8,    9,   25,
 /*   170 */    26,   34,   63,   64,   37,   48,   25,   50,   51,   20,
 /*   180 */    53,   37,   51,   24,   57,   58,    4,   60,   38,   39,
 /*   190 */    40,   41,   42,    7,   67,   48,    5,   50,   51,    1,
 /*   200 */    53,    4,   11,   72,   57,   58,   15,   60,   11,   61,
 /*   210 */    62,    6,    7,   15,   67,    4,   25,   26,   13,   22,
 /*   220 */    48,   16,   50,   51,   21,   53,   21,   64,   37,   57,
 /*   230 */    58,   23,   60,   48,   68,   37,   51,   68,   53,   67,
 /*   240 */    61,   62,   57,   58,   55,   60,    6,    7,    8,    9,
 /*   250 */    65,    5,   12,    5,    6,   66,   54,   11,   56,   11,
 /*   260 */    20,   15,   11,   15,   24,   35,   36,   52,   51,   62,
 /*   270 */    53,   25,   26,   25,   26,   58,   25,   60,    6,    7,
 /*   280 */     8,    9,   18,   37,   12,   37,    6,    7,    8,    9,
 /*   290 */    66,    4,   20,    6,    7,    8,    9,   12,   60,   19,
 /*   300 */    20,   48,   17,    1,   51,   51,   53,   20,   25,   48,
 /*   310 */    57,   58,   51,   60,   53,   46,   47,   15,   57,   58,
 /*   320 */    73,   60,   73,   73,   73,    6,    7,    8,    9,   73,
 /*   330 */    73,   12,   48,   73,   73,   51,   73,   53,   73,   20,
 /*   340 */    48,   57,   58,   51,   60,   53,   73,   73,   48,   57,
 /*   350 */    58,   51,   60,   53,   73,   73,   48,   57,   58,   51,
 /*   360 */    60,   53,   73,   73,   73,   57,   58,   73,   60,   73,
 /*   370 */    48,   73,   73,   51,   73,   53,   73,   73,   73,   57,
 /*   380 */    58,   73,   60,   73,    4,   73,    6,    7,    8,    9,
 /*   390 */    73,   73,   48,   73,   73,   51,   73,   53,   73,   73,
 /*   400 */    20,   57,   58,   73,   60,   48,   73,   73,   51,   73,
 /*   410 */    53,   73,   73,   73,   57,   58,   73,   60,   73,   48,
 /*   420 */    73,   73,   51,   73,   53,   73,   73,   73,   57,   58,
 /*   430 */    73,   60,   73,   73,   73,    6,    7,    8,    9,   73,
 /*   440 */    73,   73,   73,   73,   73,   73,   73,   73,   73,   20,
);
    const YY_SHIFT_USE_DFLT = -9;
    const YY_SHIFT_MAX = 82;
    static public $yy_shift_ofst = array(
 /*     0 */   150,  137,  137,  137,  137,  137,  137,  144,  108,  144,
 /*    10 */   121,  144,  248,  144,  144,  144,  144,  144,  144,  144,
 /*    20 */   144,  191,  246,  246,   -6,   22,  302,  240,  150,  287,
 /*    30 */   159,  319,   84,  272,  380,  280,  429,  429,  429,  429,
 /*    40 */   429,  429,  198,  197,  251,  251,  251,   63,  208,  264,
 /*    50 */   125,    0,  110,   -3,  118,  230,  230,  251,   54,  251,
 /*    60 */   283,   26,  264,  208,   26,  205,   -8,   51,   51,    3,
 /*    70 */   285,   37,  203,  211,  151,  182,   37,  186,   37,  160,
 /*    80 */    80,  135,  106,
);
    const YY_REDUCE_USE_DFLT = -42;
    const YY_REDUCE_MAX = 64;
    static public $yy_reduce_ofst = array(
 /*     0 */    93,   12,  127,  172,  147,   50,   27,  -14,  185,   58,
 /*    10 */   300,  292,  253,  -41,  357,  371,  284,  261,  308,  344,
 /*    20 */   322,    8,  -13,  217,   17,   43,   30,  202,  269,  202,
 /*    30 */   202,  202,  202,  202,  202,  202,  202,  202,  202,  202,
 /*    40 */   202,  202,  131,   76,  179,   95,  148,   95,  109,  189,
 /*    50 */   166,  166,  207,  166,  166,  169,  166,  207,  215,  207,
 /*    60 */   238,  254,  224,  163,   44,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 38, 39, 40, 41, 42, ),
        /* 1 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 2 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 3 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 4 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 5 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 6 */ array(5, 6, 11, 15, 25, 26, 34, 37, ),
        /* 7 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 8 */ array(5, 6, 11, 12, 15, 25, 26, 37, ),
        /* 9 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 10 */ array(5, 6, 9, 11, 15, 25, 26, 37, ),
        /* 11 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 12 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 13 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 14 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 15 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 16 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 17 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 18 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 19 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 20 */ array(5, 6, 11, 15, 25, 26, 37, ),
        /* 21 */ array(5, 11, 15, 25, 26, 37, ),
        /* 22 */ array(5, 11, 15, 25, 26, 37, ),
        /* 23 */ array(5, 11, 15, 25, 26, 37, ),
        /* 24 */ array(6, 7, 8, 9, 12, 17, 20, 24, 27, 28, 29, 30, 31, 32, 33, ),
        /* 25 */ array(6, 7, 8, 9, 20, 27, 28, 29, 30, 31, 32, 33, ),
        /* 26 */ array(1, 15, ),
        /* 27 */ array(6, 7, 8, 9, 12, 20, 24, ),
        /* 28 */ array(1, 38, 39, 40, 41, 42, ),
        /* 29 */ array(4, 6, 7, 8, 9, 20, ),
        /* 30 */ array(6, 7, 8, 9, 20, 24, ),
        /* 31 */ array(6, 7, 8, 9, 12, 20, ),
        /* 32 */ array(6, 7, 8, 9, 12, 20, ),
        /* 33 */ array(6, 7, 8, 9, 12, 20, ),
        /* 34 */ array(4, 6, 7, 8, 9, 20, ),
        /* 35 */ array(6, 7, 8, 9, 19, 20, ),
        /* 36 */ array(6, 7, 8, 9, 20, ),
        /* 37 */ array(6, 7, 8, 9, 20, ),
        /* 38 */ array(6, 7, 8, 9, 20, ),
        /* 39 */ array(6, 7, 8, 9, 20, ),
        /* 40 */ array(6, 7, 8, 9, 20, ),
        /* 41 */ array(6, 7, 8, 9, 20, ),
        /* 42 */ array(1, 15, 37, ),
        /* 43 */ array(4, 11, 22, ),
        /* 44 */ array(11, 25, ),
        /* 45 */ array(11, 25, ),
        /* 46 */ array(11, 25, ),
        /* 47 */ array(11, 25, ),
        /* 48 */ array(23, ),
        /* 49 */ array(18, ),
        /* 50 */ array(19, 35, 36, ),
        /* 51 */ array(4, 35, 36, ),
        /* 52 */ array(11, 14, 25, ),
        /* 53 */ array(12, 35, 36, ),
        /* 54 */ array(4, 35, 36, ),
        /* 55 */ array(35, 36, ),
        /* 56 */ array(35, 36, ),
        /* 57 */ array(11, 25, ),
        /* 58 */ array(4, 22, ),
        /* 59 */ array(11, 25, ),
        /* 60 */ array(25, ),
        /* 61 */ array(15, ),
        /* 62 */ array(18, ),
        /* 63 */ array(23, ),
        /* 64 */ array(15, ),
        /* 65 */ array(6, 7, 13, 16, 21, ),
        /* 66 */ array(13, 16, 21, ),
        /* 67 */ array(13, 16, ),
        /* 68 */ array(13, 16, ),
        /* 69 */ array(12, 17, ),
        /* 70 */ array(12, 17, ),
        /* 71 */ array(11, ),
        /* 72 */ array(21, ),
        /* 73 */ array(4, ),
        /* 74 */ array(25, ),
        /* 75 */ array(4, ),
        /* 76 */ array(11, ),
        /* 77 */ array(7, ),
        /* 78 */ array(11, ),
        /* 79 */ array(4, ),
        /* 80 */ array(25, ),
        /* 81 */ array(6, ),
        /* 82 */ array(25, ),
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
        /* 139 */ array(),
        /* 140 */ array(),
        /* 141 */ array(),
        /* 142 */ array(),
        /* 143 */ array(),
        /* 144 */ array(),
);
    static public $yy_default = array(
 /*     0 */   226,  226,  226,  226,  226,  226,  226,  226,  226,  226,
 /*    10 */   226,  226,  226,  226,  226,  226,  226,  226,  226,  226,
 /*    20 */   226,  226,  226,  226,  204,  204,  226,  220,  145,  226,
 /*    30 */   220,  226,  206,  226,  226,  226,  162,  195,  196,  206,
 /*    40 */   205,  221,  226,  226,  226,  226,  226,  226,  186,  166,
 /*    50 */   226,  226,  226,  226,  226,  226,  202,  182,  226,  181,
 /*    60 */   226,  226,  167,  188,  226,  226,  226,  224,  176,  226,
 /*    70 */   226,  226,  226,  226,  226,  226,  191,  226,  163,  226,
 /*    80 */   226,  226,  226,  216,  151,  183,  222,  225,  207,  201,
 /*    90 */   203,  185,  179,  215,  156,  157,  184,  158,  159,  146,
 /*   100 */   147,  186,  177,  150,  149,  168,  148,  178,  223,  192,
 /*   110 */   194,  172,  189,  187,  198,  200,  171,  173,  193,  197,
 /*   120 */   169,  175,  217,  164,  218,  219,  170,  152,  209,  208,
 /*   130 */   165,  210,  211,  213,  212,  199,  180,  160,  153,  161,
 /*   140 */   154,  174,  155,  190,  214,
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
    const YYNOCODE = 74;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 145;
    const YYNRULE = 81;
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
    public $yyTokenName = array( 
  '$',             'OTHER',         'LDELS',         'RDELS',       
  'RDEL',          'NUMBER',        'MINUS',         'PLUS',        
  'STAR',          'SLASH',         'PERCENT',       'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'SEMICOLON',   
  'VERT',          'EQUAL',         'SPACE',         'PTR',         
  'APTR',          'ID',            'SI_QSTR',       'EQUALS',      
  'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',
  'LESSEQUAL',     'IDENTITY',      'NOT',           'LAND',        
  'LOR',           'QUOTE',         'PHP',           'LDEL',        
  'IFTAG',         'ELSEIFTAG',     'FORTAG',        'error',       
  'start',         'input',         'single',        'smartytag',   
  'expr',          'attributes',    'ifexprs',       'variable',    
  'attribute',     'value',         'modifier',      'modparameters',
  'math',          'array',         'method',        'doublequoted',
  'function',      'varids',        'varid',         'methodchain', 
  'methodelement',  'params',        'modparameter',  'ifexpr',      
  'lop',           'ifcond',        'arrayelements',  'arrayelement',
  'other',       
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
 /*  34 */ "value ::= QUOTE doublequoted QUOTE",
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
 /*  77 */ "doublequoted ::= doublequoted other",
 /*  78 */ "doublequoted ::= other",
 /*  79 */ "other ::= variable",
 /*  80 */ "other ::= OTHER",
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
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 5 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 5 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
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
        78 => 1,
        80 => 1,
        2 => 2,
        77 => 2,
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
        34 => 34,
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
        79 => 79,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 49 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1316 "internal.templateparser.php"
#line 51 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1319 "internal.templateparser.php"
#line 52 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1322 "internal.templateparser.php"
#line 58 "internal.templateparser.y"
    function yy_r6(){ $this->_retvalue = "<?php echo str_replace('\"','&quot;',". $this->yystack[$this->yyidx + -1]->minor .");?>\n";    }
#line 1325 "internal.templateparser.php"
#line 59 "internal.templateparser.y"
    function yy_r7(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1328 "internal.templateparser.php"
#line 60 "internal.templateparser.y"
    function yy_r8(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0))) ."\n ";    }
#line 1331 "internal.templateparser.php"
#line 61 "internal.templateparser.y"
    function yy_r9(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor)) ."\n ";    }
#line 1334 "internal.templateparser.php"
#line 62 "internal.templateparser.y"
    function yy_r10(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'if'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1337 "internal.templateparser.php"
#line 63 "internal.templateparser.y"
    function yy_r11(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'elseif'),array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1340 "internal.templateparser.php"
#line 64 "internal.templateparser.y"
    function yy_r12(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'='.$this->yystack[$this->yyidx + -1]->minor))) ."\n ";    }
#line 1343 "internal.templateparser.php"
#line 65 "internal.templateparser.y"
    function yy_r13(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'++'))) ."\n ";    }
#line 1346 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r14(){$this->smarty = Smarty::instance(); $this->_retvalue =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>$this->yystack[$this->yyidx + -9]->minor.'='.$this->yystack[$this->yyidx + -7]->minor),array('ifexp'=>$this->yystack[$this->yyidx + -5]->minor),array('loop'=>$this->yystack[$this->yyidx + -3]->minor.'--'))) ."\n ";    }
#line 1349 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1352 "internal.templateparser.php"
#line 70 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1355 "internal.templateparser.php"
#line 72 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1358 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = "-".$this->yystack[$this->yyidx + 0]->minor;     }
#line 1361 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1364 "internal.templateparser.php"
#line 78 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1367 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1370 "internal.templateparser.php"
#line 82 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "+";    }
#line 1373 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = "-";    }
#line 1376 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = "*";    }
#line 1379 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = "/";    }
#line 1382 "internal.templateparser.php"
#line 88 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1385 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = '"'.$this->yystack[$this->yyidx + -1]->minor.'"';     }
#line 1388 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']';    }
#line 1391 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor ."[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1394 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor ."[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1397 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r39(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1400 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r42(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;    }
#line 1403 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1406 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1409 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1412 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1415 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor."(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1418 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1421 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1424 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1427 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor .",". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1430 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1433 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1436 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1439 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1442 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor;    }
#line 1445 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '==';    }
#line 1448 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!=';    }
#line 1451 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '>';    }
#line 1454 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '<';    }
#line 1457 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '>=';    }
#line 1460 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '<=';    }
#line 1463 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '===';    }
#line 1466 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '&&';    }
#line 1469 "internal.templateparser.php"
#line 149 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '||';    }
#line 1472 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1475 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1478 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1481 "internal.templateparser.php"
#line 159 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '".'.$this->yystack[$this->yyidx + 0]->minor.'."';    }
#line 1484 "internal.templateparser.php"

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
#line 40 "internal.templateparser.y"

    $this->internalError = true;
    $this->smarty->trigger_template_error();
#line 1601 "internal.templateparser.php"
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
#line 32 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //    echo $this->retvalue."\n\n";
#line 1626 "internal.templateparser.php"
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
