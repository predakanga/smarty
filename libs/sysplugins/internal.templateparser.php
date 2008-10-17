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
    
#line 136 "internal.templateparser.php"

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
    const TP_COMMENTSTART                   = 39;
    const TP_COMMENTEND                     = 40;
    const TP_PHP                            = 41;
    const TP_LDEL                           = 42;
    const YY_NO_ACTION = 266;
    const YY_ACCEPT_ACTION = 265;
    const YY_ERROR_ACTION = 264;

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
 /*     0 */   123,  125,   42,   12,  144,  154,   21,  118,   28,   81,
 /*    10 */   138,   26,   94,  143,  116,  117,   64,   82,  143,  114,
 /*    20 */   109,  108,  110,  111,  113,  112,  168,  123,  125,  155,
 /*    30 */   152,   24,  123,  125,  118,   28,   24,  129,  100,   94,
 /*    40 */    28,  116,  117,    9,   94,   83,  114,  109,  108,  110,
 /*    50 */   111,  113,  112,  128,    6,   29,  118,    7,  128,  126,
 /*    60 */    29,   54,    7,  116,  117,    9,   54,  265,   43,  104,
 /*    70 */   133,   74,  121,   78,  123,  125,   77,  121,  144,   87,
 /*    80 */     4,   33,   28,   34,  120,    4,   94,    9,   34,  120,
 /*    90 */   128,  147,   29,  166,   16,  159,  168,  128,   54,   29,
 /*   100 */    18,    3,  123,  125,   55,   54,  144,   62,   77,  121,
 /*   110 */    28,  123,  125,  151,   94,   77,  121,   21,   20,   28,
 /*   120 */    34,  120,    5,   94,    4,  148,  122,   34,  120,  143,
 /*   130 */    25,  128,   19,   29,   51,   16,  165,   10,  128,   54,
 /*   140 */    29,  162,   16,  123,  125,  168,   54,   24,  146,   77,
 /*   150 */   121,   28,  123,  125,  150,   94,   77,  121,  157,    8,
 /*   160 */    28,   34,  120,  128,   94,   29,  130,   16,   34,  120,
 /*   170 */   128,   53,   29,  161,    1,  123,  125,   56,   54,   27,
 /*   180 */   107,   52,  121,   28,   11,   99,   15,   94,   77,  121,
 /*   190 */   123,  125,   22,   34,  120,  128,   68,   29,   28,    1,
 /*   200 */    34,  120,   94,   54,   88,  132,   20,   35,  145,  158,
 /*   210 */   154,   96,  118,   75,  121,  138,   69,  124,  142,  116,
 /*   220 */   117,    9,  236,  236,   32,   34,  120,   80,   57,  118,
 /*   230 */    73,   12,  138,   72,  153,  140,  116,  117,  118,  105,
 /*   240 */   147,  119,   84,   65,   31,  116,  117,   98,   59,  118,
 /*   250 */    32,   80,  138,   55,   59,  118,  116,  117,  138,  106,
 /*   260 */   101,  169,  116,  117,   32,  134,  133,   91,   60,  118,
 /*   270 */   128,   79,  138,   71,   16,  160,  116,  117,   54,  234,
 /*   280 */   234,   19,  115,  103,  123,  125,   31,   98,   77,  121,
 /*   290 */    59,  118,   28,   56,  138,   27,   94,   76,  116,  117,
 /*   300 */    34,  120,   32,  106,  101,  148,   58,   70,   40,   90,
 /*   310 */   138,   64,  102,  118,  116,  117,  138,   93,    2,   44,
 /*   320 */   116,  117,   86,   17,  118,   98,  131,  138,  156,   89,
 /*   330 */   163,  116,  117,  141,   55,  123,  125,   69,  124,  142,
 /*   340 */    67,  164,   61,   28,   97,  127,  139,   94,   48,   80,
 /*   350 */    40,  149,  136,  118,   85,  118,  138,   14,  138,   95,
 /*   360 */   116,  117,  116,  117,   40,  137,  174,   21,  174,  118,
 /*   370 */    47,   92,  138,  106,  101,  118,  116,  117,  138,  174,
 /*   380 */   174,  174,  116,  117,  174,  167,   37,  174,  174,  174,
 /*   390 */    66,  118,  135,   23,  138,  174,  174,   46,  116,  117,
 /*   400 */   174,  174,  118,   36,  174,  138,  174,  174,  118,  116,
 /*   410 */   117,  138,   45,  174,   49,  116,  117,  118,  174,  118,
 /*   420 */   138,  174,  138,  174,  116,  117,  116,  117,   41,  174,
 /*   430 */   174,  174,  174,  118,  174,  174,  138,  174,  174,  174,
 /*   440 */   116,  117,   39,  174,   30,  174,  174,  118,  174,  118,
 /*   450 */   138,  174,  138,   50,  116,  117,  116,  117,  118,  174,
 /*   460 */    21,  138,  174,  123,  125,  116,  117,  174,   13,   38,
 /*   470 */    64,   28,  143,  174,  118,   94,  174,  138,   63,  174,
 /*   480 */   174,  116,  117,  174,  151,  174,   56,  174,   27,  174,
 /*   490 */    24,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   49,   20,   11,   52,   12,   54,   15,   24,
 /*    10 */    57,   51,   19,   24,   61,   62,   22,   14,   24,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   66,    7,    8,   76,
 /*    30 */     5,   42,    7,    8,   54,   15,   42,   57,    5,   19,
 /*    40 */    15,   61,   62,   10,   19,   24,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   21,    8,   54,   10,    6,   57,
 /*    60 */     8,   14,   10,   61,   62,   10,   14,   44,   45,   46,
 /*    70 */    47,   24,   25,   23,    7,    8,   24,   25,   11,   14,
 /*    80 */    33,   51,   15,   36,   37,   33,   19,   10,   36,   37,
 /*    90 */     6,    1,    8,   11,   10,   11,   66,    6,   14,    8,
 /*   100 */    23,   10,    7,    8,   14,   14,   11,   50,   24,   25,
 /*   110 */    15,    7,    8,   56,   19,   24,   25,   12,   23,   15,
 /*   120 */    36,   37,   18,   19,   33,   54,   36,   36,   37,   24,
 /*   130 */    51,    6,   42,    8,   63,   10,   11,   10,    6,   14,
 /*   140 */     8,    5,   10,    7,    8,   66,   14,   42,   77,   24,
 /*   150 */    25,   15,    7,    8,   11,   19,   24,   25,   13,   16,
 /*   160 */    15,   36,   37,    6,   19,    8,    1,   10,   36,   37,
 /*   170 */     6,   14,    8,   24,   10,    7,    8,   58,   14,   60,
 /*   180 */    11,   24,   25,   15,   16,    5,   20,   19,   24,   25,
 /*   190 */     7,    8,   73,   36,   37,    6,   59,    8,   15,   10,
 /*   200 */    36,   37,   19,   14,   38,   40,   23,   49,   71,   11,
 /*   210 */    52,    5,   54,   24,   25,   57,   64,   65,   66,   61,
 /*   220 */    62,   10,   34,   35,   49,   36,   37,   21,   53,   54,
 /*   230 */    48,   20,   57,   75,   76,    5,   61,   62,   54,    5,
 /*   240 */     1,   57,   24,   18,   49,   61,   62,   72,   53,   54,
 /*   250 */    49,   21,   57,   14,   53,   54,   61,   62,   57,   34,
 /*   260 */    35,    5,   61,   62,   49,   46,   47,   72,   53,   54,
 /*   270 */     6,   24,   57,   72,   10,   68,   61,   62,   14,   34,
 /*   280 */    35,   42,    5,    5,    7,    8,   49,   72,   24,   25,
 /*   290 */    53,   54,   15,   58,   57,   60,   19,   11,   61,   62,
 /*   300 */    36,   37,   49,   34,   35,   54,   53,   54,   49,   72,
 /*   310 */    57,   22,    9,   54,   61,   62,   57,   24,   74,   49,
 /*   320 */    61,   62,   52,   20,   54,   72,    1,   57,   77,   70,
 /*   330 */    69,   61,   62,    5,   14,    7,    8,   64,   65,   66,
 /*   340 */    67,   68,   54,   15,    5,   65,   56,   19,   49,   21,
 /*   350 */    49,   52,    1,   54,    3,   54,   57,   17,   57,   55,
 /*   360 */    61,   62,   61,   62,   49,   71,   78,   12,   78,   54,
 /*   370 */    49,   70,   57,   34,   35,   54,   61,   62,   57,   78,
 /*   380 */    78,   78,   61,   62,   78,   70,   49,   78,   78,   78,
 /*   390 */    39,   54,   41,   42,   57,   78,   78,   49,   61,   62,
 /*   400 */    78,   78,   54,   49,   78,   57,   78,   78,   54,   61,
 /*   410 */    62,   57,   49,   78,   49,   61,   62,   54,   78,   54,
 /*   420 */    57,   78,   57,   78,   61,   62,   61,   62,   49,   78,
 /*   430 */    78,   78,   78,   54,   78,   78,   57,   78,   78,   78,
 /*   440 */    61,   62,   49,   78,   49,   78,   78,   54,   78,   54,
 /*   450 */    57,   78,   57,   49,   61,   62,   61,   62,   54,   78,
 /*   460 */    12,   57,   78,    7,    8,   61,   62,   78,   20,   49,
 /*   470 */    22,   15,   24,   78,   54,   19,   78,   57,   50,   78,
 /*   480 */    78,   61,   62,   78,   56,   78,   58,   78,   60,   78,
 /*   490 */    42,
);
    const YY_SHIFT_USE_DFLT = -18;
    const YY_SHIFT_MAX = 95;
    static public $yy_shift_ofst = array(
 /*     0 */   351,  189,   52,   52,   91,   52,   47,   52,  189,  125,
 /*    10 */    84,  132,  164,  164,  132,  132,  132,  132,  132,  132,
 /*    20 */   132,  132,  132,  157,  132,  448,   -6,  264,  264,  264,
 /*    30 */   328,   -7,   20,  105,  239,   95,  277,  145,  136,   67,
 /*    40 */   168,  104,  183,  351,   25,  456,  456,  456,  456,  456,
 /*    50 */   456,   90,   33,  -11,  -11,  -11,  340,  225,  339,  269,
 /*    60 */   269,  303,  206,  230,  293,  320,  325,  289,  340,  355,
 /*    70 */   166,  245,  143,  165,  211,   77,  188,   55,    3,   50,
 /*    80 */   -15,  -17,   21,  278,  234,  218,  256,  247,   65,  198,
 /*    90 */   286,  169,   82,  127,  149,  180,
);
    const YY_REDUCE_USE_DFLT = -48;
    const YY_REDUCE_MAX = 69;
    static public $yy_reduce_ofst = array(
 /*     0 */    23,  158,  215,  237,  201,  175,  253,  195,  -47,  301,
 /*    10 */   259,  315,  299,  270,  365,  379,  393,  404,  363,  354,
 /*    20 */   321,  337,  348,  395,  420,  273,  273,  184,    2,  -20,
 /*    30 */   428,  119,  119,  152,   71,  235,  235,  235,  235,  235,
 /*    40 */   235,  235,  235,  219,  235,  235,  235,  235,  235,  235,
 /*    50 */   235,  251,   57,   79,  -40,   30,  137,  244,  244,  244,
 /*    60 */   244,  304,  290,  290,  261,  288,  182,  207,  294,  280,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 39, 41, 42, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
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
        /* 24 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(12, 20, 22, 24, 42, ),
        /* 26 */ array(12, 22, 24, 42, ),
        /* 27 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(5, 7, 8, 15, 19, 21, ),
        /* 31 */ array(7, 8, 11, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 32 */ array(7, 8, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 33 */ array(12, 24, 42, ),
        /* 34 */ array(1, 14, 42, ),
        /* 35 */ array(7, 8, 11, 15, 19, 23, ),
        /* 36 */ array(5, 7, 8, 15, 19, ),
        /* 37 */ array(7, 8, 13, 15, 19, ),
        /* 38 */ array(5, 7, 8, 15, 19, ),
        /* 39 */ array(7, 8, 11, 15, 19, ),
        /* 40 */ array(7, 8, 15, 16, 19, ),
        /* 41 */ array(7, 8, 15, 18, 19, ),
        /* 42 */ array(7, 8, 15, 19, 23, ),
        /* 43 */ array(1, 3, 39, 41, 42, ),
        /* 44 */ array(5, 7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(7, 8, 15, 19, ),
        /* 47 */ array(7, 8, 15, 19, ),
        /* 48 */ array(7, 8, 15, 19, ),
        /* 49 */ array(7, 8, 15, 19, ),
        /* 50 */ array(7, 8, 15, 19, ),
        /* 51 */ array(1, 14, 36, 42, ),
        /* 52 */ array(5, 10, 21, ),
        /* 53 */ array(24, 42, ),
        /* 54 */ array(24, 42, ),
        /* 55 */ array(24, 42, ),
        /* 56 */ array(17, ),
        /* 57 */ array(18, 34, 35, ),
        /* 58 */ array(5, 34, 35, ),
        /* 59 */ array(34, 35, ),
        /* 60 */ array(34, 35, ),
        /* 61 */ array(9, 20, ),
        /* 62 */ array(5, 21, ),
        /* 63 */ array(5, 21, ),
        /* 64 */ array(24, ),
        /* 65 */ array(14, ),
        /* 66 */ array(1, ),
        /* 67 */ array(22, ),
        /* 68 */ array(17, ),
        /* 69 */ array(12, ),
        /* 70 */ array(20, 38, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(11, 16, ),
        /* 73 */ array(1, 40, ),
        /* 74 */ array(10, 20, ),
        /* 75 */ array(10, 23, ),
        /* 76 */ array(34, 35, ),
        /* 77 */ array(10, ),
        /* 78 */ array(14, ),
        /* 79 */ array(23, ),
        /* 80 */ array(24, ),
        /* 81 */ array(20, ),
        /* 82 */ array(24, ),
        /* 83 */ array(5, ),
        /* 84 */ array(5, ),
        /* 85 */ array(24, ),
        /* 86 */ array(5, ),
        /* 87 */ array(24, ),
        /* 88 */ array(14, ),
        /* 89 */ array(11, ),
        /* 90 */ array(11, ),
        /* 91 */ array(11, ),
        /* 92 */ array(11, ),
        /* 93 */ array(10, ),
        /* 94 */ array(24, ),
        /* 95 */ array(5, ),
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
);
    static public $yy_default = array(
 /*     0 */   264,  264,  264,  264,  264,  264,  264,  264,  264,  264,
 /*    10 */   264,  264,  264,  264,  264,  264,  264,  264,  264,  264,
 /*    20 */   264,  264,  264,  264,  264,  210,  210,  264,  264,  264,
 /*    30 */   264,  238,  238,  210,  264,  253,  264,  264,  264,  264,
 /*    40 */   229,  264,  253,  170,  264,  255,  239,  254,  191,  233,
 /*    50 */   187,  264,  209,  264,  264,  264,  195,  264,  264,  264,
 /*    60 */   240,  264,  264,  264,  264,  264,  264,  219,  196,  211,
 /*    70 */   204,  235,  264,  264,  209,  209,  237,  209,  264,  264,
 /*    80 */   264,  264,  264,  264,  264,  264,  264,  264,  264,  264,
 /*    90 */   234,  234,  264,  222,  264,  264,  182,  184,  234,  185,
 /*   100 */   181,  249,  188,  186,  171,  183,  248,  236,  243,  242,
 /*   110 */   244,  245,  247,  246,  241,  260,  205,  206,  204,  197,
 /*   120 */   202,  207,  208,  200,  212,  199,  198,  213,  201,  194,
 /*   130 */   262,  263,  174,  173,  172,  175,  176,  232,  193,  190,
 /*   140 */   178,  177,  216,  217,  203,  231,  258,  261,  259,  192,
 /*   150 */   250,  189,  179,  251,  256,  252,  257,  214,  226,  227,
 /*   160 */   221,  230,  218,  223,  220,  225,  224,  228,  215,  180,
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
    const YYNSTATE = 170;
    const YYNRULE = 94;
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
    1,  /*         AS => OTHER */
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
  'QUOTE',         'BOOLEAN',       'AS',            'COMMENTSTART',
  'COMMENTEND',    'PHP',           'LDEL',          'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'commenttext',   'expr',          'attributes',    'varvar',      
  'array',         'ifexprs',       'variable',      'foraction',   
  'attribute',     'value',         'modifier',      'modparameters',
  'math',          'object',        'function',      'doublequoted',
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
 /*   9 */ "smartytag ::= LDEL DOLLAR varvar EQUAL expr RDEL",
 /*  10 */ "smartytag ::= LDEL DOLLAR varvar EQUAL array RDEL",
 /*  11 */ "smartytag ::= LDEL ID RDEL",
 /*  12 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  13 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  16 */ "smartytag ::= LDEL ID SPACE variable AS DOLLAR ID APTR DOLLAR ID RDEL",
 /*  17 */ "foraction ::= EQUAL expr",
 /*  18 */ "foraction ::= INCDEC",
 /*  19 */ "attributes ::= attribute",
 /*  20 */ "attributes ::= attributes attribute",
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
 /*  32 */ "value ::= BOOLEAN",
 /*  33 */ "value ::= OPENP expr CLOSEP",
 /*  34 */ "value ::= variable",
 /*  35 */ "value ::= object",
 /*  36 */ "value ::= function",
 /*  37 */ "value ::= SI_QSTR",
 /*  38 */ "value ::= QUOTE doublequoted QUOTE",
 /*  39 */ "value ::= ID",
 /*  40 */ "variable ::= DOLLAR varvar",
 /*  41 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  42 */ "vararraydefs ::= vararraydef",
 /*  43 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  44 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  45 */ "varvar ::= varvarele",
 /*  46 */ "varvar ::= varvar varvarele",
 /*  47 */ "varvarele ::= ID",
 /*  48 */ "varvarele ::= LDEL expr RDEL",
 /*  49 */ "object ::= DOLLAR varvar objectchain",
 /*  50 */ "objectchain ::= objectelement",
 /*  51 */ "objectchain ::= objectchain objectelement",
 /*  52 */ "objectelement ::= PTR ID",
 /*  53 */ "objectelement ::= PTR method",
 /*  54 */ "function ::= ID OPENP params CLOSEP",
 /*  55 */ "function ::= ID OPENP CLOSEP",
 /*  56 */ "method ::= ID OPENP params CLOSEP",
 /*  57 */ "method ::= ID OPENP CLOSEP",
 /*  58 */ "params ::= expr COMMA params",
 /*  59 */ "params ::= expr",
 /*  60 */ "modifier ::= VERT ID",
 /*  61 */ "modparameters ::= modparameter",
 /*  62 */ "modparameters ::= modparameters modparameter",
 /*  63 */ "modparameter ::= COLON expr",
 /*  64 */ "ifexprs ::= ifexpr",
 /*  65 */ "ifexprs ::= NOT ifexpr",
 /*  66 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  67 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  68 */ "ifexpr ::= expr",
 /*  69 */ "ifexpr ::= expr ifcond expr",
 /*  70 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  71 */ "ifcond ::= EQUALS",
 /*  72 */ "ifcond ::= NOTEQUALS",
 /*  73 */ "ifcond ::= GREATERTHAN",
 /*  74 */ "ifcond ::= LESSTHAN",
 /*  75 */ "ifcond ::= GREATEREQUAL",
 /*  76 */ "ifcond ::= LESSEQUAL",
 /*  77 */ "ifcond ::= IDENTITY",
 /*  78 */ "lop ::= LAND",
 /*  79 */ "lop ::= LOR",
 /*  80 */ "array ::= OPENP arrayelements CLOSEP",
 /*  81 */ "arrayelements ::= arrayelement",
 /*  82 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  83 */ "arrayelement ::= expr",
 /*  84 */ "arrayelement ::= expr APTR expr",
 /*  85 */ "arrayelement ::= ID APTR expr",
 /*  86 */ "arrayelement ::= array",
 /*  87 */ "doublequoted ::= doublequoted other",
 /*  88 */ "doublequoted ::= other",
 /*  89 */ "other ::= variable",
 /*  90 */ "other ::= LDEL expr RDEL",
 /*  91 */ "other ::= OTHER",
 /*  92 */ "commenttext ::= commenttext OTHER",
 /*  93 */ "commenttext ::= OTHER",
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
  array( 'lhs' => 47, 'rhs' => 6 ),
  array( 'lhs' => 47, 'rhs' => 6 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 5 ),
  array( 'lhs' => 47, 'rhs' => 12 ),
  array( 'lhs' => 47, 'rhs' => 11 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 4 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
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
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
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
        23 => 0,
        31 => 0,
        32 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        37 => 0,
        81 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        29 => 1,
        30 => 1,
        42 => 1,
        45 => 1,
        61 => 1,
        63 => 1,
        64 => 1,
        88 => 1,
        91 => 1,
        93 => 1,
        2 => 2,
        43 => 2,
        87 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 9,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 18,
        59 => 18,
        83 => 18,
        86 => 18,
        20 => 20,
        21 => 21,
        22 => 21,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        33 => 33,
        38 => 38,
        39 => 39,
        40 => 40,
        41 => 41,
        44 => 44,
        46 => 46,
        47 => 47,
        48 => 48,
        66 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        58 => 58,
        60 => 60,
        62 => 62,
        65 => 65,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        82 => 82,
        84 => 84,
        85 => 84,
        89 => 89,
        90 => 90,
        92 => 92,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 64 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1401 "internal.templateparser.php"
#line 70 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1404 "internal.templateparser.php"
#line 72 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1407 "internal.templateparser.php"
#line 80 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1410 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -1]->minor),array('_smarty_nocache'=>$this->nocache)));$this->nocache=false;    }
#line 1413 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1416 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue = $this->smarty->compile_tag->execute(array('_smarty_tag'=>'assign','var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor,'_smarty_nocache'=>$this->nocache));$this->nocache=false;    }
#line 1419 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1422 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1425 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1428 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1431 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1434 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -9]->minor,'from'=>$this->yystack[$this->yyidx + -7]->minor,'key'=>$this->yystack[$this->yyidx + -4]->minor,'item'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1437 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1440 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1443 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1446 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1449 "internal.templateparser.php"
#line 129 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1452 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1455 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r26(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1458 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1461 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1464 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1467 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1470 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1473 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; $_v = trim($this->yystack[$this->yyidx + 0]->minor,"'"); if($this->tpl_vars->tpl_vars[$_v]->nocache) $this->nocache=true;    }
#line 1476 "internal.templateparser.php"
#line 175 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if($this->tpl_vars->tpl_vars[$_v]->nocache) $this->nocache=true;    }
#line 1479 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1482 "internal.templateparser.php"
#line 189 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1485 "internal.templateparser.php"
#line 191 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1488 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1491 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor; $_v=trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->tpl_vars[$_v]->nocache) $this->nocache=true;    }
#line 1494 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1497 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1500 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1503 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1506 "internal.templateparser.php"
#line 215 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1509 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1512 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1515 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1518 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1521 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1524 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1527 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1530 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1533 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1536 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '==';    }
#line 1539 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '!=';    }
#line 1542 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '>';    }
#line 1545 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '<';    }
#line 1548 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '>=';    }
#line 1551 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '<=';    }
#line 1554 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '===';    }
#line 1557 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '&&';    }
#line 1560 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '||';    }
#line 1563 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1566 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1569 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1572 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1575 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1578 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1581 "internal.templateparser.php"

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
#line 48 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1698 "internal.templateparser.php"
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
#line 40 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1723 "internal.templateparser.php"
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
