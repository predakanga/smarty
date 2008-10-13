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
        $this->compiler = Smarty_Internal_Compiler::instance(); 
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Tag");
        $this->smarty->compile_tag = new Smarty_Internal_Compile_Smarty_Tag;
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Variable");
        $this->smarty->compile_variable = new Smarty_Internal_Compile_Smarty_Variable;
				$this->caching = true;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 135 "internal.templateparser.php"

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
    const TP_NOCACHE                        = 37;
    const TP_COMMENTSTART                   = 38;
    const TP_COMMENTEND                     = 39;
    const TP_PHP                            = 40;
    const TP_LDEL                           = 41;
    const YY_NO_ACTION = 251;
    const YY_ACCEPT_ACTION = 250;
    const YY_ERROR_ACTION = 249;

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
    const YY_SZ_ACTTAB = 434;
static public $yy_action = array(
 /*     0 */   147,   98,  157,   35,  133,  107,  138,    8,   25,  149,
 /*    10 */   142,   89,   76,   57,  150,   90,   52,  115,   26,  146,
 /*    20 */   145,  158,  104,   97,   96,   95,   55,  147,   98,  155,
 /*    30 */    45,   17,  136,  138,  140,   25,  139,  142,  120,   76,
 /*    40 */   141,  150,   90,   93,   21,   62,  146,  145,  158,  104,
 /*    50 */    97,   96,   95,  153,  109,   24,   91,    6,  153,   19,
 /*    60 */    24,   51,    6,  100,  117,   53,   51,  250,   33,   99,
 /*    70 */   105,   69,  110,   14,  147,   98,   77,  110,  133,   52,
 /*    80 */     4,   26,   25,   48,  126,    4,   76,  153,   48,   24,
 /*    90 */    18,   22,  128,  109,  153,   51,   24,   61,   22,  131,
 /*   100 */   151,  107,   51,  134,   31,   77,  110,  138,   65,  123,
 /*   110 */   149,  142,   77,  110,   10,  150,   90,   48,   10,  153,
 /*   120 */   130,   24,  113,   22,   48,    2,   94,   51,   11,   73,
 /*   130 */   148,  147,   98,   64,  111,  114,  152,   47,  110,   25,
 /*   140 */    63,   62,    7,   76,  153,  107,   24,   11,    5,   48,
 /*   150 */    84,  153,   51,   24,   74,    1,  100,  117,  144,   51,
 /*   160 */   147,   98,   77,  110,   23,   91,  116,  151,   25,   77,
 /*   170 */   110,    4,   76,   92,   48,    9,  153,   13,   24,  103,
 /*   180 */    22,   48,   14,   86,   51,   62,   81,   64,  111,  114,
 /*   190 */    66,  143,  222,  222,   77,  110,   85,   28,   10,   60,
 /*   200 */   138,  224,  224,   14,  142,  119,   48,  102,  150,   90,
 /*   210 */    15,   27,  138,   60,  138,  124,  156,   88,  142,   67,
 /*   220 */   150,   90,  150,   90,  132,   28,  138,   58,  138,   15,
 /*   230 */   122,   71,  142,   80,  150,   90,  150,   90,  108,  105,
 /*   240 */   153,    3,   24,   28,    1,   56,   78,  112,   51,   27,
 /*   250 */   142,   60,  138,  114,  150,   90,  142,   49,   68,  110,
 /*   260 */   150,   90,  100,  117,   29,  112,  121,  151,  147,   98,
 /*   270 */    48,   79,   28,   82,   54,  138,   25,  153,   10,  142,
 /*   280 */    76,   22,   87,  150,   90,   51,   46,   83,  129,  138,
 /*   290 */    72,   20,  127,  142,  112,   77,  110,  150,   90,   16,
 /*   300 */   126,   46,  147,   98,  138,  101,   75,   48,  142,   59,
 /*   310 */    25,  138,  150,   90,   76,  106,   30,  118,   18,  150,
 /*   320 */    90,   70,  147,   98,  125,   50,  133,  135,   12,   41,
 /*   330 */    25,  154,  138,   21,   76,  168,  142,  168,  168,   37,
 /*   340 */   150,   90,  138,  168,  168,   38,  142,  168,  138,  168,
 /*   350 */   150,   90,  142,  147,   98,   40,  150,   90,  138,  137,
 /*   360 */   168,   25,  142,  168,   21,   76,  150,   90,  168,  168,
 /*   370 */    42,  168,   39,  138,   30,  138,   91,  142,  168,  142,
 /*   380 */   168,  150,   90,  150,   90,   43,  168,  168,  138,  168,
 /*   390 */   168,   36,  142,   14,  138,  168,  150,   90,  142,  168,
 /*   400 */   168,   44,  150,   90,  138,  168,  168,   32,  142,  168,
 /*   410 */   138,  168,  150,   90,  142,  147,   98,  168,  150,   90,
 /*   420 */   168,   34,  168,   25,  138,  168,  168,   76,  142,  168,
 /*   430 */   168,  168,  150,   90,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   11,   48,   11,    1,   51,   16,   15,   54,
 /*    10 */    55,   37,   19,   49,   59,   60,   56,   53,   58,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   47,    7,    8,   74,
 /*    30 */    48,   71,    5,   51,    5,   15,   54,   55,    5,   19,
 /*    40 */    24,   59,   60,   39,   12,   41,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   75,    8,   24,   10,    6,   20,
 /*    60 */     8,   14,   10,   34,   35,   47,   14,   43,   44,   45,
 /*    70 */    46,   24,   25,   41,    7,    8,   24,   25,   11,   56,
 /*    80 */    33,   58,   15,   36,   37,   33,   19,    6,   36,    8,
 /*    90 */    23,   10,   11,   75,    6,   14,    8,   61,   10,   11,
 /*   100 */    64,    1,   14,   67,   48,   24,   25,   51,   57,    5,
 /*   110 */    54,   55,   24,   25,   10,   59,   60,   36,   10,    6,
 /*   120 */    69,    8,    5,   10,   36,   21,    5,   14,   20,   73,
 /*   130 */    74,    7,    8,   62,   63,   64,   36,   24,   25,   15,
 /*   140 */    18,   41,   18,   19,    6,    1,    8,   20,   10,   36,
 /*   150 */    37,    6,   14,    8,   11,   10,   34,   35,    5,   14,
 /*   160 */     7,    8,   24,   25,   61,   24,    9,   64,   15,   24,
 /*   170 */    25,   33,   19,    5,   36,   10,    6,   20,    8,    5,
 /*   180 */    10,   36,   41,   24,   14,   41,   24,   62,   63,   64,
 /*   190 */    65,   66,   34,   35,   24,   25,   37,   48,   10,   50,
 /*   200 */    51,   34,   35,   41,   55,   11,   36,   11,   59,   60,
 /*   210 */    16,   48,   51,   50,   51,    5,   55,   51,   55,   70,
 /*   220 */    59,   60,   59,   60,   11,   48,   51,   50,   51,   16,
 /*   230 */    55,   21,   55,   70,   59,   60,   59,   60,   45,   46,
 /*   240 */     6,   72,    8,   48,   10,   50,   51,   70,   14,   48,
 /*   250 */    55,   50,   51,   64,   59,   60,   55,   14,   24,   25,
 /*   260 */    59,   60,   34,   35,   61,   70,    5,   64,    7,    8,
 /*   270 */    36,   70,   48,   52,   50,   51,   15,    6,   10,   55,
 /*   280 */    19,   10,   21,   59,   60,   14,   48,   24,    1,   51,
 /*   290 */     3,   23,   53,   55,   70,   24,   25,   59,   60,   17,
 /*   300 */    37,   48,    7,    8,   51,   75,   68,   36,   55,   51,
 /*   310 */    15,   51,   59,   60,   19,   55,   22,   63,   23,   59,
 /*   320 */    60,   68,    7,    8,   69,   38,   11,   40,   41,   48,
 /*   330 */    15,   66,   51,   12,   19,   76,   55,   76,   76,   48,
 /*   340 */    59,   60,   51,   76,   76,   48,   55,   76,   51,   76,
 /*   350 */    59,   60,   55,    7,    8,   48,   59,   60,   51,   13,
 /*   360 */    76,   15,   55,   76,   12,   19,   59,   60,   76,   76,
 /*   370 */    48,   76,   48,   51,   22,   51,   24,   55,   76,   55,
 /*   380 */    76,   59,   60,   59,   60,   48,   76,   76,   51,   76,
 /*   390 */    76,   48,   55,   41,   51,   76,   59,   60,   55,   76,
 /*   400 */    76,   48,   59,   60,   51,   76,   76,   48,   55,   76,
 /*   410 */    51,   76,   59,   60,   55,    7,    8,   76,   59,   60,
 /*   420 */    76,   48,   76,   15,   51,   76,   76,   19,   55,   76,
 /*   430 */    76,   76,   59,   60,
);
    const YY_SHIFT_USE_DFLT = -27;
    const YY_SHIFT_MAX = 89;
    static public $yy_shift_ofst = array(
 /*     0 */   287,  234,   47,   52,  138,   52,   52,   52,  234,   81,
 /*    10 */    88,  145,  113,  170,  170,  170,  170,  170,  170,  170,
 /*    20 */   170,  170,  170,  352,  271,  271,  271,   -7,   20,   32,
 /*    30 */   162,   67,  261,  287,  346,  295,  315,  124,  153,  408,
 /*    40 */   408,  408,  408,  408,  408,  408,  408,  104,  144,  141,
 /*    50 */   144,  141,  282,  100,  122,    4,   29,  210,  228,  157,
 /*    60 */   228,  141,  243,  243,  321,  282,  294,  158,  268,  108,
 /*    70 */   194,  263,  159,   -9,  167,  213,   16,  188,   39,  196,
 /*    80 */   143,  165,  117,  127,  174,  121,  168,  -26,   27,   33,
);
    const YY_REDUCE_USE_DFLT = -46;
    const YY_REDUCE_MAX = 66;
    static public $yy_reduce_ofst = array(
 /*     0 */    24,   56,  195,  177,  149,  163,  201,  224,  -45,  253,
 /*    10 */   238,  -18,  359,  337,  297,  307,  281,  353,  322,  291,
 /*    20 */   324,  373,  343,  125,  161,  260,  175,  -40,  -40,   71,
 /*    30 */    36,   23,   23,  193,   23,   23,   23,   23,   23,   23,
 /*    40 */    23,   23,   23,   23,   23,   23,   23,  -36,   18,  203,
 /*    50 */   -21,  103,   51,  230,  169,  230,  169,  239,  169,  221,
 /*    60 */   169,  189,  166,  258,  254,  255,  265,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 38, 40, 41, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 9 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 10 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
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
        /* 23 */ array(12, 22, 24, 41, ),
        /* 24 */ array(6, 10, 14, 24, 25, 36, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, ),
        /* 26 */ array(6, 10, 14, 24, 25, 36, ),
        /* 27 */ array(7, 8, 11, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 28 */ array(7, 8, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 29 */ array(12, 24, 41, ),
        /* 30 */ array(24, 41, ),
        /* 31 */ array(7, 8, 11, 15, 19, 23, ),
        /* 32 */ array(5, 7, 8, 15, 19, 21, ),
        /* 33 */ array(1, 3, 38, 40, 41, ),
        /* 34 */ array(7, 8, 13, 15, 19, ),
        /* 35 */ array(7, 8, 15, 19, 23, ),
        /* 36 */ array(7, 8, 11, 15, 19, ),
        /* 37 */ array(7, 8, 15, 18, 19, ),
        /* 38 */ array(5, 7, 8, 15, 19, ),
        /* 39 */ array(7, 8, 15, 19, ),
        /* 40 */ array(7, 8, 15, 19, ),
        /* 41 */ array(7, 8, 15, 19, ),
        /* 42 */ array(7, 8, 15, 19, ),
        /* 43 */ array(7, 8, 15, 19, ),
        /* 44 */ array(7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(7, 8, 15, 19, ),
        /* 47 */ array(5, 10, 21, ),
        /* 48 */ array(1, 41, ),
        /* 49 */ array(24, 41, ),
        /* 50 */ array(1, 41, ),
        /* 51 */ array(24, 41, ),
        /* 52 */ array(17, ),
        /* 53 */ array(1, 36, 41, ),
        /* 54 */ array(18, 34, 35, ),
        /* 55 */ array(1, 39, 41, ),
        /* 56 */ array(5, 34, 35, ),
        /* 57 */ array(5, 21, ),
        /* 58 */ array(34, 35, ),
        /* 59 */ array(9, 20, ),
        /* 60 */ array(34, 35, ),
        /* 61 */ array(24, 41, ),
        /* 62 */ array(14, ),
        /* 63 */ array(14, ),
        /* 64 */ array(12, ),
        /* 65 */ array(17, ),
        /* 66 */ array(22, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(10, 23, ),
        /* 69 */ array(10, 20, ),
        /* 70 */ array(11, 16, ),
        /* 71 */ array(24, 37, ),
        /* 72 */ array(24, 37, ),
        /* 73 */ array(11, 16, ),
        /* 74 */ array(34, 35, ),
        /* 75 */ array(11, 16, ),
        /* 76 */ array(24, ),
        /* 77 */ array(10, ),
        /* 78 */ array(20, ),
        /* 79 */ array(11, ),
        /* 80 */ array(11, ),
        /* 81 */ array(10, ),
        /* 82 */ array(5, ),
        /* 83 */ array(20, ),
        /* 84 */ array(5, ),
        /* 85 */ array(5, ),
        /* 86 */ array(5, ),
        /* 87 */ array(37, ),
        /* 88 */ array(5, ),
        /* 89 */ array(5, ),
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
);
    static public $yy_default = array(
 /*     0 */   249,  249,  249,  249,  249,  249,  249,  249,  249,  249,
 /*    10 */   249,  249,  249,  249,  249,  249,  249,  249,  249,  249,
 /*    20 */   249,  249,  249,  198,  249,  249,  249,  226,  226,  198,
 /*    30 */   249,  241,  249,  159,  249,  241,  249,  249,  249,  243,
 /*    40 */   217,  221,  242,  175,  227,  180,  216,  197,  249,  249,
 /*    50 */   249,  249,  184,  249,  249,  249,  249,  249,  228,  249,
 /*    60 */   249,  210,  249,  249,  199,  185,  207,  223,  197,  197,
 /*    70 */   249,  249,  249,  249,  225,  249,  249,  197,  192,  222,
 /*    80 */   222,  205,  249,  249,  249,  249,  249,  249,  249,  249,
 /*    90 */   194,  205,  171,  163,  172,  235,  234,  233,  188,  160,
 /*   100 */   236,  245,  224,  169,  232,  162,  187,  248,  161,  246,
 /*   110 */   195,  200,  222,  174,  204,  177,  176,  237,  201,  214,
 /*   120 */   167,  166,  186,  168,  170,  220,  179,  178,  215,  165,
 /*   130 */   219,  213,  212,  191,  211,  164,  247,  202,  192,  181,
 /*   140 */   173,  218,  182,  208,  206,  230,  229,  189,  239,  244,
 /*   150 */   193,  203,  196,  190,  209,  240,  183,  238,  231,
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
    const YYNOCODE = 77;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 159;
    const YYNRULE = 90;
    const YYERRORSYMBOL = 42;
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
    1,  /*    NOCACHE => OTHER */
    0,  /* COMMENTSTART => nothing */
    0,  /* COMMENTEND => nothing */
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
  'QUOTE',         'NOCACHE',       'COMMENTSTART',  'COMMENTEND',  
  'PHP',           'LDEL',          'error',         'start',       
  'template',      'template_element',  'smartytag',     'doublequoted',
  'expr',          'attributes',    'ifexprs',       'variable',    
  'foraction',     'attribute',     'array',         'value',       
  'modifier',      'modparameters',  'math',          'object',      
  'function',      'varvar',        'vararraydefs',  'vararraydef', 
  'varvarele',     'objectchain',   'objectelement',  'method',      
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
 /*   4 */ "template_element ::= COMMENTSTART doublequoted COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr RDEL",
 /*   8 */ "smartytag ::= LDEL expr SPACE NOCACHE RDEL",
 /*   9 */ "smartytag ::= LDEL ID RDEL",
 /*  10 */ "smartytag ::= LDEL NOCACHE RDEL",
 /*  11 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  12 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  13 */ "smartytag ::= LDELSLASH NOCACHE RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  16 */ "foraction ::= EQUAL expr",
 /*  17 */ "foraction ::= INCDEC",
 /*  18 */ "attributes ::= attribute",
 /*  19 */ "attributes ::= attributes attribute",
 /*  20 */ "attribute ::= SPACE NOCACHE",
 /*  21 */ "attribute ::= SPACE ID EQUAL expr",
 /*  22 */ "attribute ::= SPACE ID EQUAL array",
 /*  23 */ "expr ::= value",
 /*  24 */ "expr ::= UNIMATH value",
 /*  25 */ "expr ::= expr modifier",
 /*  26 */ "expr ::= expr modifier modparameters",
 /*  27 */ "expr ::= expr math value",
 /*  28 */ "expr ::= expr DOT value",
 /*  29 */ "math ::= UNIMATH",
 /*  30 */ "math ::= MATH",
 /*  31 */ "value ::= NUMBER",
 /*  32 */ "value ::= OPENP expr CLOSEP",
 /*  33 */ "value ::= variable",
 /*  34 */ "value ::= object",
 /*  35 */ "value ::= function",
 /*  36 */ "value ::= SI_QSTR",
 /*  37 */ "value ::= QUOTE doublequoted QUOTE",
 /*  38 */ "value ::= ID",
 /*  39 */ "variable ::= DOLLAR varvar",
 /*  40 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  41 */ "vararraydefs ::= vararraydef",
 /*  42 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  43 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  44 */ "varvar ::= varvarele",
 /*  45 */ "varvar ::= varvar varvarele",
 /*  46 */ "varvarele ::= ID",
 /*  47 */ "varvarele ::= LDEL expr RDEL",
 /*  48 */ "object ::= DOLLAR varvar objectchain",
 /*  49 */ "objectchain ::= objectelement",
 /*  50 */ "objectchain ::= objectchain objectelement",
 /*  51 */ "objectelement ::= PTR varvar",
 /*  52 */ "objectelement ::= PTR method",
 /*  53 */ "function ::= ID OPENP params CLOSEP",
 /*  54 */ "function ::= ID OPENP CLOSEP",
 /*  55 */ "method ::= ID OPENP params CLOSEP",
 /*  56 */ "method ::= ID OPENP CLOSEP",
 /*  57 */ "params ::= expr",
 /*  58 */ "params ::= params COMMA expr",
 /*  59 */ "modifier ::= VERT ID",
 /*  60 */ "modparameters ::= modparameter",
 /*  61 */ "modparameters ::= modparameters modparameter",
 /*  62 */ "modparameter ::= COLON expr",
 /*  63 */ "ifexprs ::= ifexpr",
 /*  64 */ "ifexprs ::= NOT ifexpr",
 /*  65 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  66 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  67 */ "ifexpr ::= expr",
 /*  68 */ "ifexpr ::= expr ifcond expr",
 /*  69 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  70 */ "ifcond ::= EQUALS",
 /*  71 */ "ifcond ::= NOTEQUALS",
 /*  72 */ "ifcond ::= GREATERTHAN",
 /*  73 */ "ifcond ::= LESSTHAN",
 /*  74 */ "ifcond ::= GREATEREQUAL",
 /*  75 */ "ifcond ::= LESSEQUAL",
 /*  76 */ "ifcond ::= IDENTITY",
 /*  77 */ "lop ::= LAND",
 /*  78 */ "lop ::= LOR",
 /*  79 */ "array ::= OPENP arrayelements CLOSEP",
 /*  80 */ "arrayelements ::= arrayelement",
 /*  81 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  82 */ "arrayelement ::= expr",
 /*  83 */ "arrayelement ::= expr APTR expr",
 /*  84 */ "arrayelement ::= ID APTR expr",
 /*  85 */ "arrayelement ::= array",
 /*  86 */ "doublequoted ::= doublequoted other",
 /*  87 */ "doublequoted ::= other",
 /*  88 */ "other ::= LDEL variable RDEL",
 /*  89 */ "other ::= OTHER",
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
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 3 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 5 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 4 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 5 ),
  array( 'lhs' => 46, 'rhs' => 12 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        23 => 0,
        31 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        38 => 0,
        80 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        29 => 1,
        30 => 1,
        41 => 1,
        44 => 1,
        46 => 1,
        60 => 1,
        62 => 1,
        63 => 1,
        87 => 1,
        89 => 1,
        2 => 2,
        42 => 2,
        86 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 9,
        11 => 11,
        12 => 12,
        13 => 12,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 17,
        57 => 17,
        82 => 17,
        85 => 17,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 21,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        32 => 32,
        37 => 37,
        39 => 39,
        40 => 40,
        43 => 43,
        45 => 45,
        47 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 51,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        58 => 58,
        59 => 59,
        61 => 61,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 68,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        81 => 81,
        83 => 83,
        84 => 83,
        88 => 88,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1360 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1363 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1366 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /*'.$this->yystack[$this->yyidx + -1]->minor.'*/?>';     }
#line 1369 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -1]->minor,'caching'=>$this->caching));$this->caching=true;    }
#line 1372 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -3]->minor,'caching'=>false));    }
#line 1375 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1378 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_caching'=>$this->caching),$this->yystack[$this->yyidx + -1]->minor));$this->caching=true;    }
#line 1381 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1384 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1387 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1390 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1393 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1396 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1399 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1402 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1405 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1408 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1411 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r26(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1414 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1417 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1420 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1423 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1426 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '$this->smarty->tpl_vars[\''. $this->yystack[$this->yyidx + 0]->minor .'\']->data'; if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->caching) $this->caching=false;    }
#line 1429 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->smarty->tpl_vars[\''. $this->yystack[$this->yyidx + -1]->minor .'\']->data'.$this->yystack[$this->yyidx + 0]->minor;if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->caching) $this->caching=false;    }
#line 1432 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1435 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1438 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = "(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1441 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->caching) $this->caching=false;    }
#line 1444 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1447 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1450 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1453 "internal.templateparser.php"
#line 209 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1456 "internal.templateparser.php"
#line 211 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1459 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1462 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1465 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1468 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1471 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1474 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1477 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1480 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1483 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1486 "internal.templateparser.php"
#line 251 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1489 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '==';    }
#line 1492 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '!=';    }
#line 1495 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '>';    }
#line 1498 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '<';    }
#line 1501 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '>=';    }
#line 1504 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '<=';    }
#line 1507 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '===';    }
#line 1510 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '&&';    }
#line 1513 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '||';    }
#line 1516 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1519 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1522 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1525 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1528 "internal.templateparser.php"

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
#line 47 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1645 "internal.templateparser.php"
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
#line 39 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1670 "internal.templateparser.php"
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
