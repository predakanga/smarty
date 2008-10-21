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

    function __construct($lex,$tpl_vars) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->tpl_vars = $tpl_vars; 
        $this->smarty = Smarty::instance(); 
        $this->compiler = Smarty_Internal_Compiler::instance(); 
				$this->nocache = false;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 132 "internal.templateparser.php"

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
    const TP_AS                             = 38;
    const TP_IN                             = 39;
    const TP_COMMENTSTART                   = 40;
    const TP_COMMENTEND                     = 41;
    const TP_PHP                            = 42;
    const TP_LDEL                           = 43;
    const YY_NO_ACTION = 271;
    const YY_ACCEPT_ACTION = 270;
    const YY_ERROR_ACTION = 269;

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
    const YY_SZ_ACTTAB = 479;
static public $yy_action = array(
 /*     0 */   130,  110,  239,  239,  149,   69,  173,  162,   28,  130,
 /*    10 */   110,   69,  173,  162,   71,  167,  169,   28,   11,  171,
 /*    20 */   165,  164,  158,  157,  156,  159,  241,  241,  171,  165,
 /*    30 */   164,  158,  157,  156,  159,  128,  127,   30,   81,    6,
 /*    40 */   128,  105,   30,   40,    7,  108,  111,  104,   40,  103,
 /*    50 */   106,   21,   21,  101,  116,  153,   97,   97,  101,  116,
 /*    60 */    10,   73,    4,  163,  163,   33,  117,    4,   42,  137,
 /*    70 */    33,  117,  128,   14,   30,   67,    7,  126,   24,  105,
 /*    80 */    39,   44,   18,   18,  145,  131,  105,  103,  106,   68,
 /*    90 */    76,  116,  102,  140,  103,  106,  128,   22,   30,    4,
 /*   100 */    20,   10,   33,  117,   40,   29,  153,   61,   80,  139,
 /*   110 */   148,    9,  137,  138,  101,  116,  128,  119,   30,   42,
 /*   120 */     1,  128,   37,   30,   40,   20,   33,  117,   82,   41,
 /*   130 */   270,   36,  123,  122,  101,  116,  150,  151,  170,   38,
 /*   140 */   116,  107,    8,  108,  111,   35,   33,  117,   22,   54,
 /*   150 */   105,   33,  117,   68,  130,  110,  102,  121,  103,  106,
 /*   160 */    72,  143,   28,  130,  110,    5,  133,   50,   96,  115,
 /*   170 */   145,   28,  105,   27,   23,   68,  108,  111,  102,  128,
 /*   180 */   103,  106,  128,   20,   30,    9,    1,   40,    2,  155,
 /*   190 */    40,   48,   83,  118,   88,  146,  105,  101,  116,   68,
 /*   200 */    79,  116,  102,   34,  103,  106,  163,   63,  105,   33,
 /*   210 */   117,   68,   33,  117,  102,  144,  103,  106,   34,  194,
 /*   220 */   134,   29,   63,  105,   10,   18,   68,   87,  129,  102,
 /*   230 */   124,  103,  106,   35,   19,    3,   98,   63,  105,   16,
 /*   240 */    78,   68,   86,   64,  102,   26,  103,  106,  142,  138,
 /*   250 */   160,   35,  130,  110,   29,   62,  105,   77,   90,   68,
 /*   260 */    28,  155,  102,   35,  103,  106,  136,   59,   74,  168,
 /*   270 */    10,   68,   92,  105,  102,  115,  103,  106,   46,  109,
 /*   280 */    84,  103,  106,  105,   70,   98,   68,  115,   46,  102,
 /*   290 */    91,  103,  106,  105,  125,  122,   68,  147,   46,  102,
 /*   300 */   154,  103,  106,  105,   89,  113,   68,   95,  166,  102,
 /*   310 */    99,  103,  106,   56,  130,  110,  161,  114,  105,  132,
 /*   320 */   100,   68,   28,   21,  102,   17,  103,  106,   97,   52,
 /*   330 */    15,   12,   42,   73,  105,  163,   21,   68,   85,   31,
 /*   340 */   102,   97,  103,  106,  105,  112,   73,   68,  163,   73,
 /*   350 */   102,   58,  103,  106,   18,  172,  105,  135,   21,   68,
 /*   360 */   130,  110,  102,   66,  103,  106,  141,   18,   28,   55,
 /*   370 */   130,  110,   43,   32,  105,  120,   94,   68,   28,   60,
 /*   380 */   102,   93,  103,  106,  105,   65,   47,   68,   75,  155,
 /*   390 */   102,  105,  103,  106,   68,  177,  177,  102,   49,  103,
 /*   400 */   106,   25,   57,  105,  177,  177,   68,  105,  177,  102,
 /*   410 */    68,  103,  106,  102,   45,  103,  106,  155,   53,  105,
 /*   420 */   177,  177,   68,  105,  177,  102,   68,  103,  106,  102,
 /*   430 */    51,  103,  106,  177,  177,  105,  177,  177,   68,  177,
 /*   440 */   177,  102,  177,  103,  106,  177,  130,  110,  177,  177,
 /*   450 */   149,  130,  110,  152,   28,  130,  110,  177,  177,   28,
 /*   460 */   130,  110,   15,   28,  149,   98,  177,  177,   28,  130,
 /*   470 */   110,  177,  177,  177,  177,  177,  177,   28,   13,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   34,   35,   11,   66,   67,   68,   15,    7,
 /*    10 */     8,   66,   67,   68,   69,   70,    5,   15,   10,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   34,   35,   26,   27,
 /*    30 */    28,   29,   30,   31,   32,    6,    1,    8,    3,   10,
 /*    40 */     6,   55,    8,   14,   10,   34,   35,   61,   14,   63,
 /*    50 */    64,   12,   12,   24,   25,    1,   17,   17,   24,   25,
 /*    60 */    10,   22,   33,   24,   24,   36,   37,   33,   14,   55,
 /*    70 */    36,   37,    6,   23,    8,   40,   10,   42,   43,   55,
 /*    80 */    14,   50,   43,   43,   53,   61,   55,   63,   64,   58,
 /*    90 */    24,   25,   61,   79,   63,   64,    6,   43,    8,   33,
 /*   100 */    10,   10,   36,   37,   14,   62,    1,   51,   77,   78,
 /*   110 */    24,   20,   55,   57,   24,   25,    6,    1,    8,   14,
 /*   120 */    10,    6,   65,    8,   14,   10,   36,   37,   24,   14,
 /*   130 */    45,   46,   47,   48,   24,   25,   79,   11,   11,   24,
 /*   140 */    25,   36,   16,   34,   35,   50,   36,   37,   43,   54,
 /*   150 */    55,   36,   37,   58,    7,    8,   61,   41,   63,   64,
 /*   160 */    18,    5,   15,    7,    8,   18,    5,   50,   24,   74,
 /*   170 */    53,   15,   55,   52,   20,   58,   34,   35,   61,    6,
 /*   180 */    63,   64,    6,   10,    8,   20,   10,   14,   76,   68,
 /*   190 */    14,   50,   38,    5,   53,   78,   55,   24,   25,   58,
 /*   200 */    24,   25,   61,   50,   63,   64,   24,   54,   55,   36,
 /*   210 */    37,   58,   36,   37,   61,   11,   63,   64,   50,    5,
 /*   220 */     5,   62,   54,   55,   10,   43,   58,   74,    9,   61,
 /*   230 */     5,   63,   64,   50,   75,   21,   21,   54,   55,   20,
 /*   240 */    11,   58,   74,   51,   61,   52,   63,   64,    5,   57,
 /*   250 */     5,   50,    7,    8,   62,   54,   55,   74,   24,   58,
 /*   260 */    15,   68,   61,   50,   63,   64,   11,   54,   55,    5,
 /*   270 */    10,   58,   23,   55,   61,   74,   63,   64,   50,   61,
 /*   280 */    14,   63,   64,   55,   60,   21,   58,   74,   50,   61,
 /*   290 */    14,   63,   64,   55,   47,   48,   58,   73,   50,   61,
 /*   300 */    72,   63,   64,   55,   24,    5,   58,   24,   71,   61,
 /*   310 */    72,   63,   64,   50,    7,    8,   53,   24,   55,   57,
 /*   320 */    72,   58,   15,   12,   61,   17,   63,   64,   17,   50,
 /*   330 */    23,   20,   14,   22,   55,   24,   12,   58,   56,   50,
 /*   340 */    61,   17,   63,   64,   55,   67,   22,   58,   24,   22,
 /*   350 */    61,   50,   63,   64,   43,   70,   55,   73,   12,   58,
 /*   360 */     7,    8,   61,   39,   63,   64,   13,   43,   15,   50,
 /*   370 */     7,    8,   59,   52,   55,    1,   19,   58,   15,   50,
 /*   380 */    61,   55,   63,   64,   55,   55,   50,   58,   49,   68,
 /*   390 */    61,   55,   63,   64,   58,   80,   80,   61,   50,   63,
 /*   400 */    64,   52,   50,   55,   80,   80,   58,   55,   80,   61,
 /*   410 */    58,   63,   64,   61,   50,   63,   64,   68,   50,   55,
 /*   420 */    80,   80,   58,   55,   80,   61,   58,   63,   64,   61,
 /*   430 */    50,   63,   64,   80,   80,   55,   80,   80,   58,   80,
 /*   440 */    80,   61,   80,   63,   64,   80,    7,    8,   80,   80,
 /*   450 */    11,    7,    8,    5,   15,    7,    8,   80,   80,   15,
 /*   460 */     7,    8,   23,   15,   11,   21,   80,   80,   15,    7,
 /*   470 */     8,   80,   80,   80,   80,   80,   80,   15,   16,
);
    const YY_SHIFT_USE_DFLT = -33;
    const YY_SHIFT_MAX = 101;
    static public $yy_shift_ofst = array(
 /*     0 */    35,  176,   34,   66,   29,   34,   34,   34,  176,  110,
 /*    10 */    90,   90,  110,   90,   90,   90,   90,   90,   90,   90,
 /*    20 */    90,   90,   90,   90,  115,  324,  311,   39,  173,  173,
 /*    30 */   173,  444,   40,   54,   -7,    2,   35,  105,  214,  182,
 /*    40 */   182,  182,  182,  308,  439,  453,  462,  448,  156,  245,
 /*    50 */   307,  353,  147,  363,  142,  363,  363,  363,  363,   11,
 /*    60 */   363,  264,  109,  109,  215,  219,  318,  374,  357,  346,
 /*    70 */   308,  327,  318,  283,  154,  116,   91,  -32,   -8,   50,
 /*    80 */   126,  104,  225,  266,  280,  300,  255,  229,  243,  249,
 /*    90 */   161,  234,  276,  188,   86,    8,  165,  293,  144,  204,
 /*   100 */   127,  260,
);
    const YY_REDUCE_USE_DFLT = -62;
    const YY_REDUCE_MAX = 73;
    static public $yy_reduce_ofst = array(
 /*     0 */    85,   31,  201,  213,  183,   95,  153,  168,  117,  263,
 /*    10 */   238,  248,  141,  228,  301,  329,  319,  352,  348,  368,
 /*    20 */   364,  380,  336,  279,  289,  -55,  -55,  -55,  218,   24,
 /*    30 */   -14,  192,  -61,   57,  159,  159,  247,   14,   56,  349,
 /*    40 */   121,  193,  321,  224,   43,   43,   43,   43,   43,   43,
 /*    50 */    43,   43,   43,   43,  112,   43,   43,   43,   43,  112,
 /*    60 */    43,  262,  112,  112,  262,  282,  326,  339,  313,  278,
 /*    70 */   284,  285,  330,  237,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 40, 42, 43, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
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
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(12, 17, 22, 24, 39, 43, ),
        /* 26 */ array(12, 17, 20, 22, 24, 43, ),
        /* 27 */ array(12, 17, 22, 24, 43, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 31 */ array(7, 8, 15, 21, ),
        /* 32 */ array(12, 17, 24, 43, ),
        /* 33 */ array(1, 14, 43, ),
        /* 34 */ array(7, 8, 11, 15, 26, 27, 28, 29, 30, 31, 32, ),
        /* 35 */ array(7, 8, 15, 26, 27, 28, 29, 30, 31, 32, ),
        /* 36 */ array(1, 3, 40, 42, 43, ),
        /* 37 */ array(1, 14, 36, 43, ),
        /* 38 */ array(5, 10, 21, ),
        /* 39 */ array(24, 43, ),
        /* 40 */ array(24, 43, ),
        /* 41 */ array(24, 43, ),
        /* 42 */ array(24, 43, ),
        /* 43 */ array(17, ),
        /* 44 */ array(7, 8, 11, 15, 23, ),
        /* 45 */ array(7, 8, 11, 15, ),
        /* 46 */ array(7, 8, 15, 16, ),
        /* 47 */ array(5, 7, 8, 15, ),
        /* 48 */ array(5, 7, 8, 15, ),
        /* 49 */ array(5, 7, 8, 15, ),
        /* 50 */ array(7, 8, 15, 23, ),
        /* 51 */ array(7, 8, 13, 15, ),
        /* 52 */ array(7, 8, 15, 18, ),
        /* 53 */ array(7, 8, 15, ),
        /* 54 */ array(18, 34, 35, ),
        /* 55 */ array(7, 8, 15, ),
        /* 56 */ array(7, 8, 15, ),
        /* 57 */ array(7, 8, 15, ),
        /* 58 */ array(7, 8, 15, ),
        /* 59 */ array(5, 34, 35, ),
        /* 60 */ array(7, 8, 15, ),
        /* 61 */ array(5, 21, ),
        /* 62 */ array(34, 35, ),
        /* 63 */ array(34, 35, ),
        /* 64 */ array(5, 21, ),
        /* 65 */ array(9, 20, ),
        /* 66 */ array(14, ),
        /* 67 */ array(1, ),
        /* 68 */ array(19, ),
        /* 69 */ array(12, ),
        /* 70 */ array(17, ),
        /* 71 */ array(22, ),
        /* 72 */ array(14, ),
        /* 73 */ array(24, ),
        /* 74 */ array(20, 38, ),
        /* 75 */ array(1, 41, ),
        /* 76 */ array(10, 20, ),
        /* 77 */ array(34, 35, ),
        /* 78 */ array(34, 35, ),
        /* 79 */ array(10, 23, ),
        /* 80 */ array(11, 16, ),
        /* 81 */ array(24, ),
        /* 82 */ array(5, ),
        /* 83 */ array(14, ),
        /* 84 */ array(24, ),
        /* 85 */ array(5, ),
        /* 86 */ array(11, ),
        /* 87 */ array(11, ),
        /* 88 */ array(5, ),
        /* 89 */ array(23, ),
        /* 90 */ array(5, ),
        /* 91 */ array(24, ),
        /* 92 */ array(14, ),
        /* 93 */ array(5, ),
        /* 94 */ array(24, ),
        /* 95 */ array(10, ),
        /* 96 */ array(20, ),
        /* 97 */ array(24, ),
        /* 98 */ array(24, ),
        /* 99 */ array(11, ),
        /* 100 */ array(11, ),
        /* 101 */ array(10, ),
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
);
    static public $yy_default = array(
 /*     0 */   269,  269,  269,  269,  269,  269,  269,  269,  269,  269,
 /*    10 */   233,  233,  269,  233,  269,  269,  269,  269,  269,  269,
 /*    20 */   269,  269,  269,  269,  269,  214,  214,  214,  269,  269,
 /*    30 */   269,  194,  214,  269,  243,  243,  174,  269,  213,  269,
 /*    40 */   269,  269,  269,  237,  258,  269,  232,  269,  269,  269,
 /*    50 */   258,  269,  269,  244,  269,  190,  195,  238,  260,  269,
 /*    60 */   259,  269,  245,  269,  269,  269,  269,  269,  197,  216,
 /*    70 */   198,  224,  269,  269,  208,  269,  213,  240,  242,  213,
 /*    80 */   269,  269,  269,  269,  269,  269,  239,  239,  269,  269,
 /*    90 */   269,  269,  269,  269,  269,  227,  269,  269,  269,  269,
 /*   100 */   269,  213,  199,  209,  200,  208,  210,  212,  253,  202,
 /*   110 */   203,  254,  218,  187,  215,  239,  211,  206,  189,  267,
 /*   120 */   268,  178,  177,  175,  185,  176,  179,  180,  205,  191,
 /*   130 */   204,  201,  192,  188,  181,  235,  241,  264,  193,  256,
 /*   140 */   262,  219,  183,  182,  229,  261,  257,  236,  234,  207,
 /*   150 */   263,  255,  265,  266,  231,  220,  251,  250,  249,  252,
 /*   160 */   223,  196,  221,  222,  248,  247,  228,  225,  184,  186,
 /*   170 */   230,  246,  226,  217,
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
    const YYNOCODE = 81;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 174;
    const YYNRULE = 95;
    const YYERRORSYMBOL = 44;
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
    1,  /*         AS => OTHER */
    1,  /*         IN => OTHER */
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
  'QUOTE',         'BOOLEAN',       'AS',            'IN',          
  'COMMENTSTART',  'COMMENTEND',    'PHP',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'commenttext',   'expr',          'attributes',  
  'varvar',        'array',         'ifexprs',       'variable',    
  'foraction',     'attribute',     'exprs',         'modifier',    
  'modparameters',  'value',         'math',          'object',      
  'function',      'doublequoted',  'vararraydefs',  'vararraydef', 
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
 /*   4 */ "template_element ::= COMMENTSTART commenttext COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr attributes RDEL",
 /*   8 */ "smartytag ::= LDEL DOLLAR varvar EQUAL expr RDEL",
 /*   9 */ "smartytag ::= LDEL DOLLAR varvar EQUAL array RDEL",
 /*  10 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  11 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  12 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE variable AS DOLLAR ID APTR DOLLAR ID RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  16 */ "foraction ::= EQUAL expr",
 /*  17 */ "foraction ::= INCDEC",
 /*  18 */ "attributes ::= attributes attribute",
 /*  19 */ "attributes ::= attribute",
 /*  20 */ "attributes ::=",
 /*  21 */ "attribute ::= SPACE ID EQUAL expr",
 /*  22 */ "attribute ::= SPACE ID EQUAL array",
 /*  23 */ "expr ::= exprs",
 /*  24 */ "expr ::= exprs modifier modparameters",
 /*  25 */ "exprs ::= value",
 /*  26 */ "exprs ::= UNIMATH value",
 /*  27 */ "exprs ::= expr math value",
 /*  28 */ "exprs ::= expr DOT value",
 /*  29 */ "math ::= UNIMATH",
 /*  30 */ "math ::= MATH",
 /*  31 */ "value ::= NUMBER",
 /*  32 */ "value ::= BOOLEAN",
 /*  33 */ "value ::= OPENP expr CLOSEP",
 /*  34 */ "value ::= variable",
 /*  35 */ "value ::= object",
 /*  36 */ "value ::= function",
 /*  37 */ "value ::= SI_QSTR",
 /*  38 */ "value ::= QUOTE doublequoted QUOTE",
 /*  39 */ "value ::= ID",
 /*  40 */ "variable ::= DOLLAR varvar",
 /*  41 */ "variable ::= DOLLAR varvar COLON ID",
 /*  42 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  43 */ "vararraydefs ::= vararraydef",
 /*  44 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  45 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  46 */ "varvar ::= varvarele",
 /*  47 */ "varvar ::= varvar varvarele",
 /*  48 */ "varvarele ::= ID",
 /*  49 */ "varvarele ::= LDEL expr RDEL",
 /*  50 */ "object ::= DOLLAR varvar objectchain",
 /*  51 */ "objectchain ::= objectelement",
 /*  52 */ "objectchain ::= objectchain objectelement",
 /*  53 */ "objectelement ::= PTR ID",
 /*  54 */ "objectelement ::= PTR method",
 /*  55 */ "function ::= ID OPENP params CLOSEP",
 /*  56 */ "method ::= ID OPENP params CLOSEP",
 /*  57 */ "params ::= expr COMMA params",
 /*  58 */ "params ::= expr",
 /*  59 */ "params ::=",
 /*  60 */ "modifier ::= VERT ID",
 /*  61 */ "modparameters ::= modparameters modparameter",
 /*  62 */ "modparameters ::= modparameter",
 /*  63 */ "modparameters ::=",
 /*  64 */ "modparameter ::= COLON expr",
 /*  65 */ "ifexprs ::= ifexpr",
 /*  66 */ "ifexprs ::= NOT ifexpr",
 /*  67 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  68 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  69 */ "ifexpr ::= expr",
 /*  70 */ "ifexpr ::= expr ifcond expr",
 /*  71 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  72 */ "ifcond ::= EQUALS",
 /*  73 */ "ifcond ::= NOTEQUALS",
 /*  74 */ "ifcond ::= GREATERTHAN",
 /*  75 */ "ifcond ::= LESSTHAN",
 /*  76 */ "ifcond ::= GREATEREQUAL",
 /*  77 */ "ifcond ::= LESSEQUAL",
 /*  78 */ "ifcond ::= IDENTITY",
 /*  79 */ "lop ::= LAND",
 /*  80 */ "lop ::= LOR",
 /*  81 */ "array ::= OPENP arrayelements CLOSEP",
 /*  82 */ "arrayelements ::= arrayelement",
 /*  83 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  84 */ "arrayelement ::= expr",
 /*  85 */ "arrayelement ::= expr APTR expr",
 /*  86 */ "arrayelement ::= ID APTR expr",
 /*  87 */ "arrayelement ::= array",
 /*  88 */ "doublequoted ::= doublequoted other",
 /*  89 */ "doublequoted ::= other",
 /*  90 */ "other ::= variable",
 /*  91 */ "other ::= LDEL expr RDEL",
 /*  92 */ "other ::= OTHER",
 /*  93 */ "commenttext ::= commenttext OTHER",
 /*  94 */ "commenttext ::= OTHER",
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
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 6 ),
  array( 'lhs' => 48, 'rhs' => 6 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 5 ),
  array( 'lhs' => 48, 'rhs' => 12 ),
  array( 'lhs' => 48, 'rhs' => 11 ),
  array( 'lhs' => 48, 'rhs' => 8 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 0 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 4 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 0 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 0 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        25 => 0,
        31 => 0,
        32 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        37 => 0,
        82 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        23 => 1,
        29 => 1,
        30 => 1,
        43 => 1,
        46 => 1,
        62 => 1,
        65 => 1,
        89 => 1,
        92 => 1,
        94 => 1,
        2 => 2,
        44 => 2,
        88 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 8,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        19 => 17,
        58 => 17,
        84 => 17,
        87 => 17,
        18 => 18,
        20 => 20,
        21 => 21,
        22 => 21,
        24 => 24,
        26 => 26,
        27 => 27,
        28 => 28,
        33 => 33,
        38 => 38,
        39 => 39,
        40 => 40,
        41 => 41,
        42 => 42,
        45 => 45,
        47 => 47,
        48 => 48,
        49 => 49,
        67 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 53,
        55 => 55,
        56 => 56,
        57 => 57,
        59 => 59,
        60 => 60,
        61 => 61,
        63 => 63,
        64 => 64,
        66 => 66,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 70,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        83 => 83,
        85 => 85,
        86 => 85,
        90 => 90,
        91 => 91,
        93 => 93,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 60 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1405 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1408 "internal.templateparser.php"
#line 68 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1411 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1414 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1417 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',array('var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1420 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor,$this->nocache);$this->nocache=false;    }
#line 1423 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->compiler->compileTag('end_'.$this->yystack[$this->yyidx + -1]->minor,array());    }
#line 1426 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1429 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -10]->minor,array('start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1432 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('from'=>$this->yystack[$this->yyidx + -7]->minor,'key'=>$this->yystack[$this->yyidx + -4]->minor,'item'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1435 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag('foreach',array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1438 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1441 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1444 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1447 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array();    }
#line 1450 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1453 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r24(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1456 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1459 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1462 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1465 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1468 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1471 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1474 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + 0]->minor .')->value'; $_v = trim($this->yystack[$this->yyidx + 0]->minor,"'"); if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1477 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->prop['.$this->yystack[$this->yyidx + 0]->minor.']'; $_v = trim($this->yystack[$this->yyidx + -2]->minor,"'"); if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1480 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor;$_v = trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1483 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1486 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1489 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1492 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1495 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_v=trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1498 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1501 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1504 "internal.templateparser.php"
#line 205 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1507 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1510 "internal.templateparser.php"
#line 222 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1513 "internal.templateparser.php"
#line 228 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1516 "internal.templateparser.php"
#line 232 "internal.templateparser.y"
    function yy_r59(){ return;    }
#line 1519 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1522 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1525 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r63(){return;    }
#line 1528 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1531 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1534 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1537 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1540 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1543 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '==';    }
#line 1546 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '!=';    }
#line 1549 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '>';    }
#line 1552 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '<';    }
#line 1555 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '>=';    }
#line 1558 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '<=';    }
#line 1561 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '===';    }
#line 1564 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '&&';    }
#line 1567 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '||';    }
#line 1570 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1573 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1576 "internal.templateparser.php"
#line 278 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1579 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1582 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1585 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1588 "internal.templateparser.php"

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
#line 44 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1705 "internal.templateparser.php"
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
#line 36 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1730 "internal.templateparser.php"
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
