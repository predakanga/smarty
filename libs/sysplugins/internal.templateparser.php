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
 //       $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Variable");
 //       $this->smarty->compile_variable = new Smarty_Internal_Compile_Smarty_Variable;
				$this->nocache = false;
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
    const TP_BOOLEAN                        = 37;
    const TP_COMMENTSTART                   = 38;
    const TP_COMMENTEND                     = 39;
    const TP_PHP                            = 40;
    const TP_LDEL                           = 41;
    const TP_NOCACHE                        = 42;
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
    const YY_SZ_ACTTAB = 457;
static public $yy_action = array(
 /*     0 */   149,  148,  144,   33,  121,  119,  143,   23,   25,  153,
 /*    10 */    97,   10,   80,   31,  145,  142,  131,   71,   52,   89,
 /*    20 */    90,   96,   93,   98,   92,   94,   78,  149,  148,   68,
 /*    30 */   147,   30,  143,   54,   76,   25,  156,  107,   97,   80,
 /*    40 */   145,  142,  145,  142,   87,   18,   89,   90,   96,   93,
 /*    50 */    98,   92,   94,   88,   99,   27,   84,    5,  149,  148,
 /*    60 */    88,   51,   27,  127,    5,   53,   25,   26,   51,    6,
 /*    70 */    80,   67,  138,   46,   30,  102,   55,  143,   79,  138,
 /*    80 */     3,   97,   66,   32,  109,  145,  142,    3,  118,   87,
 /*    90 */    32,  109,  221,  221,   88,  125,   27,   99,   15,  135,
 /*   100 */    86,   88,   51,   27,   48,   15,  130,  143,   10,   51,
 /*   110 */   124,   97,   79,  138,  143,  145,  142,   17,   95,   79,
 /*   120 */   138,   22,  145,  142,   32,  109,   88,   61,   27,  123,
 /*   130 */     7,   32,  109,   88,   51,   27,   29,   15,   58,  143,
 /*   140 */   106,   51,   16,   97,   79,  138,   20,  145,  142,  127,
 /*   150 */   117,   79,  138,    3,   62,  123,   32,  109,   88,   77,
 /*   160 */    27,  150,   15,   32,  109,   88,   51,   27,  101,    1,
 /*   170 */   116,  111,   20,   51,  133,   35,   50,  138,  143,  116,
 /*   180 */   111,  153,   97,   79,  138,  112,  145,  142,   32,  109,
 /*   190 */    88,   53,   27,   26,    1,   32,  109,    9,   51,  132,
 /*   200 */   119,   74,  154,   30,   23,   58,  143,   14,   70,  138,
 /*   210 */    97,  103,  151,   52,  145,  142,   29,    8,   58,  143,
 /*   220 */    32,  109,   19,   97,   91,   88,   69,  145,  142,   15,
 /*   230 */    64,  137,  136,   51,   30,  140,   59,  143,   17,   81,
 /*   240 */    18,   97,   11,   79,  138,  145,  142,   82,   10,  113,
 /*   250 */   123,  149,  148,  104,  100,   32,  109,   99,   11,   25,
 /*   260 */   149,  148,  110,   80,  121,   74,   43,   20,   25,  143,
 /*   270 */   219,  219,   80,   97,  116,  111,   12,  145,  142,   43,
 /*   280 */   149,  148,  143,  115,  121,   85,   97,   73,   25,  143,
 /*   290 */   145,  142,   80,  152,  139,  149,  148,  145,  142,   10,
 /*   300 */    72,  134,  122,   25,  149,  148,   40,   80,   24,  143,
 /*   310 */     2,  131,   25,   97,   52,   60,   80,  145,  142,  146,
 /*   320 */    63,   13,  114,   21,  120,  129,  149,  148,   41,    4,
 /*   330 */   141,  143,   57,  155,   25,   97,  149,  148,   80,  145,
 /*   340 */   142,  108,   38,   83,   25,  143,   74,   17,   80,   97,
 /*   350 */    61,   75,   12,  145,  142,   42,  172,   34,  143,  126,
 /*   360 */   143,  172,   97,  172,   97,  172,  145,  142,  145,  142,
 /*   370 */    37,  172,   45,  143,  172,  143,  172,   97,  172,   97,
 /*   380 */   172,  145,  142,  145,  142,   36,  172,   47,  143,  172,
 /*   390 */   143,  172,   97,  172,   97,  172,  145,  142,  145,  142,
 /*   400 */   172,  172,   28,  172,   49,  143,  172,  143,  172,   97,
 /*   410 */   172,   97,  172,  145,  142,  145,  142,   44,  172,  172,
 /*   420 */   143,  172,  172,  172,   97,  172,  172,  172,  145,  142,
 /*   430 */   172,   64,  137,  136,   65,  128,  172,  250,   39,  105,
 /*   440 */   100,  149,  148,  172,  172,  172,  172,   56,  172,   25,
 /*   450 */   172,  146,  172,   80,   53,  172,   26,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   11,   49,   11,    1,   52,   16,   15,   55,
 /*    10 */    56,   10,   19,   63,   60,   61,   66,   11,   14,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   24,    7,    8,   75,
 /*    30 */    76,   49,   52,   51,   52,   15,   56,    1,   56,   19,
 /*    40 */    60,   61,   60,   61,   42,   41,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   72,    8,   24,   10,    7,    8,
 /*    60 */     6,   14,    8,   52,   10,   57,   15,   59,   14,   18,
 /*    70 */    19,   24,   25,   62,   49,   39,   51,   52,   24,   25,
 /*    80 */    33,   56,   58,   36,   37,   60,   61,   33,   77,   42,
 /*    90 */    36,   37,   34,   35,    6,   71,    8,   72,   10,   11,
 /*   100 */    71,    6,   14,    8,   49,   10,   11,   52,   10,   14,
 /*   110 */    55,   56,   24,   25,   52,   60,   61,   12,   56,   24,
 /*   120 */    25,   23,   60,   61,   36,   37,    6,   22,    8,   24,
 /*   130 */    10,   36,   37,    6,   14,    8,   49,   10,   51,   52,
 /*   140 */     5,   14,   20,   56,   24,   25,   41,   60,   61,   52,
 /*   150 */     5,   24,   25,   33,   18,   24,   36,   37,    6,   72,
 /*   160 */     8,   24,   10,   36,   37,    6,   14,    8,    5,   10,
 /*   170 */    34,   35,   41,   14,   77,   49,   24,   25,   52,   34,
 /*   180 */    35,   55,   56,   24,   25,    5,   60,   61,   36,   37,
 /*   190 */     6,   57,    8,   59,   10,   36,   37,   10,   14,   11,
 /*   200 */     1,   21,   76,   49,   16,   51,   52,   73,   24,   25,
 /*   210 */    56,    9,   11,   14,   60,   61,   49,   16,   51,   52,
 /*   220 */    36,   37,   20,   56,   11,    6,   72,   60,   61,   10,
 /*   230 */    64,   65,   66,   14,   49,   36,   51,   52,   12,   72,
 /*   240 */    41,   56,   20,   24,   25,   60,   61,   24,   10,    5,
 /*   250 */    24,    7,    8,   46,   47,   36,   37,   72,   20,   15,
 /*   260 */     7,    8,   54,   19,   11,   21,   49,   41,   15,   52,
 /*   270 */    34,   35,   19,   56,   34,   35,   23,   60,   61,   49,
 /*   280 */     7,    8,   52,    1,   11,    3,   56,   70,   15,   52,
 /*   290 */    60,   61,   19,   56,    5,    7,    8,   60,   61,   10,
 /*   300 */    70,   13,    5,   15,    7,    8,   49,   19,   63,   52,
 /*   310 */    21,   66,   15,   56,   14,   50,   19,   60,   61,   54,
 /*   320 */    38,   17,   40,   41,    5,   69,    7,    8,   49,   74,
 /*   330 */     5,   52,   52,   65,   15,   56,    7,    8,   19,   60,
 /*   340 */    61,    1,   49,   53,   15,   52,   21,   12,   19,   56,
 /*   350 */    22,   48,   23,   60,   61,   49,   78,   49,   52,   68,
 /*   360 */    52,   78,   56,   78,   56,   78,   60,   61,   60,   61,
 /*   370 */    49,   78,   49,   52,   78,   52,   78,   56,   78,   56,
 /*   380 */    78,   60,   61,   60,   61,   49,   78,   49,   52,   78,
 /*   390 */    52,   78,   56,   78,   56,   78,   60,   61,   60,   61,
 /*   400 */    78,   78,   49,   78,   49,   52,   78,   52,   78,   56,
 /*   410 */    78,   56,   78,   60,   61,   60,   61,   49,   78,   78,
 /*   420 */    52,   78,   78,   78,   56,   78,   78,   78,   60,   61,
 /*   430 */    78,   64,   65,   66,   67,   68,   78,   44,   45,   46,
 /*   440 */    47,    7,    8,   78,   78,   78,   78,   50,   78,   15,
 /*   450 */    78,   54,   78,   19,   57,   78,   59,
);
    const YY_SHIFT_USE_DFLT = -10;
    const YY_SHIFT_MAX = 85;
    static public $yy_shift_ofst = array(
 /*     0 */   282,  184,   47,  120,   54,   54,   54,   54,  184,   95,
 /*    10 */    88,  159,  127,  127,  127,  127,  127,  127,  127,  127,
 /*    20 */   127,  152,  127,  127,  105,  219,  219,  219,  244,   -7,
 /*    30 */    20,  226,    4,  253,   51,  329,  288,  297,  273,  282,
 /*    40 */   319,  434,  434,  434,  434,  434,  199,  434,  434,  434,
 /*    50 */   289,  131,  131,  304,  145,  136,  180,  202,  240,  240,
 /*    60 */   325,  223,  300,  340,  335,  328,  304,  238,  201,  236,
 /*    70 */    98,   58,  188,   -9,    2,   36,  122,  213,  222,    1,
 /*    80 */   137,    6,  187,  163,  135,   32,
);
    const YY_REDUCE_USE_DFLT = -51;
    const YY_REDUCE_MAX = 66;
    static public $yy_reduce_ofst = array(
 /*     0 */   393,  -46,  -18,  154,  185,   87,   25,  167,  126,  217,
 /*    10 */   230,   55,  279,  355,  338,  293,  308,  336,  257,  323,
 /*    20 */   321,  353,  306,  368,  367,  237,  -20,   62,  397,  134,
 /*    30 */   134,  166,   11,    8,    8,    8,    8,    8,    8,  207,
 /*    40 */     8,    8,    8,    8,    8,    8,   97,    8,    8,    8,
 /*    50 */   265,  245,  -50,   24,  255,  255,  208,  290,  255,  255,
 /*    60 */   208,  256,  280,  303,  268,  291,   29,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 38, 40, 41, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, 42, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 9 */ array(6, 8, 10, 11, 14, 24, 25, 36, 37, ),
        /* 10 */ array(6, 8, 10, 11, 14, 24, 25, 36, 37, ),
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
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(12, 22, 24, 41, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 26 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 27 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 28 */ array(5, 7, 8, 15, 19, 21, ),
        /* 29 */ array(7, 8, 11, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 30 */ array(7, 8, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 31 */ array(12, 24, 41, ),
        /* 32 */ array(1, 14, 41, ),
        /* 33 */ array(7, 8, 11, 15, 19, 23, ),
        /* 34 */ array(7, 8, 15, 18, 19, ),
        /* 35 */ array(7, 8, 15, 19, 23, ),
        /* 36 */ array(7, 8, 13, 15, 19, ),
        /* 37 */ array(5, 7, 8, 15, 19, ),
        /* 38 */ array(7, 8, 11, 15, 19, ),
        /* 39 */ array(1, 3, 38, 40, 41, ),
        /* 40 */ array(5, 7, 8, 15, 19, ),
        /* 41 */ array(7, 8, 15, 19, ),
        /* 42 */ array(7, 8, 15, 19, ),
        /* 43 */ array(7, 8, 15, 19, ),
        /* 44 */ array(7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(1, 14, 36, 41, ),
        /* 47 */ array(7, 8, 15, 19, ),
        /* 48 */ array(7, 8, 15, 19, ),
        /* 49 */ array(7, 8, 15, 19, ),
        /* 50 */ array(5, 10, 21, ),
        /* 51 */ array(24, 41, ),
        /* 52 */ array(24, 41, ),
        /* 53 */ array(17, ),
        /* 54 */ array(5, 34, 35, ),
        /* 55 */ array(18, 34, 35, ),
        /* 56 */ array(5, 21, ),
        /* 57 */ array(9, 20, ),
        /* 58 */ array(34, 35, ),
        /* 59 */ array(34, 35, ),
        /* 60 */ array(5, 21, ),
        /* 61 */ array(24, ),
        /* 62 */ array(14, ),
        /* 63 */ array(1, ),
        /* 64 */ array(12, ),
        /* 65 */ array(22, ),
        /* 66 */ array(17, ),
        /* 67 */ array(10, 20, ),
        /* 68 */ array(11, 16, ),
        /* 69 */ array(34, 35, ),
        /* 70 */ array(10, 23, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(11, 16, ),
        /* 73 */ array(11, 16, ),
        /* 74 */ array(24, 42, ),
        /* 75 */ array(1, 39, ),
        /* 76 */ array(20, ),
        /* 77 */ array(11, ),
        /* 78 */ array(20, ),
        /* 79 */ array(10, ),
        /* 80 */ array(24, ),
        /* 81 */ array(11, ),
        /* 82 */ array(10, ),
        /* 83 */ array(5, ),
        /* 84 */ array(5, ),
        /* 85 */ array(24, ),
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
);
    static public $yy_default = array(
 /*     0 */   249,  249,  249,  249,  249,  249,  249,  249,  249,  249,
 /*    10 */   249,  249,  249,  249,  249,  249,  249,  249,  249,  249,
 /*    20 */   249,  249,  249,  249,  195,  249,  249,  249,  249,  223,
 /*    30 */   223,  195,  249,  238,  249,  238,  249,  249,  249,  157,
 /*    40 */   249,  239,  240,  213,  214,  171,  249,  224,  176,  218,
 /*    50 */   194,  249,  249,  180,  249,  249,  249,  249,  249,  225,
 /*    60 */   249,  249,  249,  249,  196,  204,  181,  194,  249,  220,
 /*    70 */   194,  222,  249,  249,  249,  249,  189,  219,  249,  194,
 /*    80 */   249,  219,  207,  249,  249,  249,  217,  175,  186,  226,
 /*    90 */   227,  221,  231,  229,  232,  179,  228,  178,  230,  219,
 /*   100 */   160,  170,  161,  172,  159,  158,  168,  247,  248,  187,
 /*   110 */   174,  234,  165,  164,  162,  163,  233,  169,  243,  246,
 /*   120 */   245,  188,  203,  202,  177,  216,  206,  244,  205,  208,
 /*   130 */   212,  200,  209,  242,  199,  210,  201,  197,  192,  166,
 /*   140 */   193,  167,  191,  189,  211,  190,  173,  236,  184,  185,
 /*   150 */   215,  235,  183,  241,  237,  198,  182,
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
    const YYNOCODE = 79;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 157;
    const YYNRULE = 92;
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
    1,  /*    BOOLEAN => OTHER */
    0,  /* COMMENTSTART => nothing */
    0,  /* COMMENTEND => nothing */
    0,  /*        PHP => nothing */
    0,  /*       LDEL => nothing */
    0,  /*    NOCACHE => nothing */
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
  'QUOTE',         'BOOLEAN',       'COMMENTSTART',  'COMMENTEND',  
  'PHP',           'LDEL',          'NOCACHE',       'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'commenttext',   'expr',          'attributes',    'ifexprs',     
  'variable',      'foraction',     'attribute',     'array',       
  'value',         'modifier',      'modparameters',  'math',        
  'object',        'function',      'doublequoted',  'varvar',      
  'vararraydefs',  'vararraydef',   'varvarele',     'objectchain', 
  'objectelement',  'method',        'params',        'modparameter',
  'ifexpr',        'ifcond',        'lop',           'arrayelements',
  'arrayelement',  'other',       
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
 /*   4 */ "template_element ::= COMMENTSTART commenttext COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr RDEL",
 /*   8 */ "smartytag ::= LDEL expr attributes RDEL",
 /*   9 */ "smartytag ::= LDEL ID RDEL",
 /*  10 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  11 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  12 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  14 */ "foraction ::= EQUAL expr",
 /*  15 */ "foraction ::= INCDEC",
 /*  16 */ "attributes ::= attribute",
 /*  17 */ "attributes ::= attributes attribute",
 /*  18 */ "attribute ::= SPACE NOCACHE",
 /*  19 */ "attribute ::= SPACE ID EQUAL expr",
 /*  20 */ "attribute ::= SPACE ID EQUAL array",
 /*  21 */ "expr ::= value",
 /*  22 */ "expr ::= UNIMATH value",
 /*  23 */ "expr ::= expr modifier",
 /*  24 */ "expr ::= expr modifier modparameters",
 /*  25 */ "expr ::= expr math value",
 /*  26 */ "expr ::= expr DOT value",
 /*  27 */ "math ::= UNIMATH",
 /*  28 */ "math ::= MATH",
 /*  29 */ "value ::= NUMBER",
 /*  30 */ "value ::= BOOLEAN",
 /*  31 */ "value ::= OPENP expr CLOSEP",
 /*  32 */ "value ::= variable",
 /*  33 */ "value ::= object",
 /*  34 */ "value ::= function",
 /*  35 */ "value ::= SI_QSTR",
 /*  36 */ "value ::= QUOTE doublequoted QUOTE",
 /*  37 */ "value ::= ID",
 /*  38 */ "variable ::= DOLLAR varvar",
 /*  39 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  40 */ "vararraydefs ::= vararraydef",
 /*  41 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  42 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  43 */ "varvar ::= varvarele",
 /*  44 */ "varvar ::= varvar varvarele",
 /*  45 */ "varvarele ::= ID",
 /*  46 */ "varvarele ::= LDEL expr RDEL",
 /*  47 */ "object ::= DOLLAR varvar objectchain",
 /*  48 */ "objectchain ::= objectelement",
 /*  49 */ "objectchain ::= objectchain objectelement",
 /*  50 */ "objectelement ::= PTR ID",
 /*  51 */ "objectelement ::= PTR method",
 /*  52 */ "function ::= ID OPENP params CLOSEP",
 /*  53 */ "function ::= ID OPENP CLOSEP",
 /*  54 */ "method ::= ID OPENP params CLOSEP",
 /*  55 */ "method ::= ID OPENP CLOSEP",
 /*  56 */ "params ::= expr",
 /*  57 */ "params ::= params COMMA expr",
 /*  58 */ "modifier ::= VERT ID",
 /*  59 */ "modparameters ::= modparameter",
 /*  60 */ "modparameters ::= modparameters modparameter",
 /*  61 */ "modparameter ::= COLON expr",
 /*  62 */ "ifexprs ::= ifexpr",
 /*  63 */ "ifexprs ::= NOT ifexpr",
 /*  64 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  65 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  66 */ "ifexpr ::= expr",
 /*  67 */ "ifexpr ::= expr ifcond expr",
 /*  68 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  69 */ "ifcond ::= EQUALS",
 /*  70 */ "ifcond ::= NOTEQUALS",
 /*  71 */ "ifcond ::= GREATERTHAN",
 /*  72 */ "ifcond ::= LESSTHAN",
 /*  73 */ "ifcond ::= GREATEREQUAL",
 /*  74 */ "ifcond ::= LESSEQUAL",
 /*  75 */ "ifcond ::= IDENTITY",
 /*  76 */ "lop ::= LAND",
 /*  77 */ "lop ::= LOR",
 /*  78 */ "array ::= OPENP arrayelements CLOSEP",
 /*  79 */ "arrayelements ::= arrayelement",
 /*  80 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  81 */ "arrayelement ::= expr",
 /*  82 */ "arrayelement ::= expr APTR expr",
 /*  83 */ "arrayelement ::= ID APTR expr",
 /*  84 */ "arrayelement ::= array",
 /*  85 */ "doublequoted ::= doublequoted other",
 /*  86 */ "doublequoted ::= other",
 /*  87 */ "other ::= variable",
 /*  88 */ "other ::= LDEL expr RDEL",
 /*  89 */ "other ::= OTHER",
 /*  90 */ "commenttext ::= commenttext OTHER",
 /*  91 */ "commenttext ::= OTHER",
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
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 5 ),
  array( 'lhs' => 47, 'rhs' => 12 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        21 => 0,
        29 => 0,
        30 => 0,
        32 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        79 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        27 => 1,
        28 => 1,
        40 => 1,
        43 => 1,
        59 => 1,
        61 => 1,
        62 => 1,
        86 => 1,
        89 => 1,
        91 => 1,
        2 => 2,
        41 => 2,
        85 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 15,
        56 => 15,
        81 => 15,
        84 => 15,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 19,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        31 => 31,
        36 => 36,
        37 => 37,
        38 => 38,
        39 => 39,
        47 => 39,
        42 => 42,
        44 => 44,
        45 => 45,
        46 => 46,
        64 => 46,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        57 => 57,
        58 => 58,
        60 => 60,
        63 => 63,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 67,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        80 => 80,
        82 => 82,
        83 => 82,
        87 => 87,
        88 => 88,
        90 => 90,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1371 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1374 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1377 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1380 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -1]->minor),array('_smarty_nocache'=>$this->nocache)));$this->nocache=false;    }
#line 1383 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1386 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1389 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1392 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1395 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1398 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1401 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1404 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1407 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1410 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1413 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1416 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1419 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1422 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r24(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1425 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1428 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1431 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1434 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1437 "internal.templateparser.php"
#line 168 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1440 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->nocache) $this->nocache=true;    }
#line 1443 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->nocache) $this->nocache=true;    }
#line 1446 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1449 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1452 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1455 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1458 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1461 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1464 "internal.templateparser.php"
#line 205 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1467 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1470 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1473 "internal.templateparser.php"
#line 222 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1476 "internal.templateparser.php"
#line 224 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1479 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1482 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1485 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1488 "internal.templateparser.php"
#line 249 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1491 "internal.templateparser.php"
#line 251 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1494 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1497 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1500 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '==';    }
#line 1503 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '!=';    }
#line 1506 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '>';    }
#line 1509 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '<';    }
#line 1512 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '>=';    }
#line 1515 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '<=';    }
#line 1518 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '===';    }
#line 1521 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '&&';    }
#line 1524 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '||';    }
#line 1527 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1530 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1533 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1536 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1539 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1542 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1545 "internal.templateparser.php"

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
#line 1662 "internal.templateparser.php"
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
#line 1687 "internal.templateparser.php"
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
