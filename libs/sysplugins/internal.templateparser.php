<?php
/**
* Smarty Internal Plugin Templateparser
*
* This is the template parser.
* It is generated from the internal.templateparser.y file
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
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
#line 12 "internal.templateparser.y"
class Smarty_Internal_Templateparser#line 109 "internal.templateparser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 14 "internal.templateparser.y"

    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex, $compiler) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->smarty = Smarty::instance(); 
        $this->compiler = $compiler;
        $this->template = $this->compiler->template; 
				$this->nocache = false;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 139 "internal.templateparser.php"

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
    const TP_IN                             = 38;
    const TP_ANDSYM                         = 39;
    const TP_UNDERL                         = 40;
    const TP_COMMENTSTART                   = 41;
    const TP_COMMENTEND                     = 42;
    const TP_PHP                            = 43;
    const TP_LDEL                           = 44;
    const YY_NO_ACTION = 268;
    const YY_ACCEPT_ACTION = 267;
    const YY_ERROR_ACTION = 266;

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
    const YY_SZ_ACTTAB = 539;
static public $yy_action = array(
 /*     0 */   139,  140,   18,  161,  145,   14,   65,   99,   10,  255,
 /*    10 */    23,  132,   75,  149,  132,  157,   19,   28,   92,  156,
 /*    20 */   155,  169,  102,  108,  107,  109,   34,  101,  112,   63,
 /*    30 */    85,   13,   29,  128,   13,  166,  160,   73,   11,   45,
 /*    40 */    33,  137,  144,   56,  119,  114,   11,  128,   16,  166,
 /*    50 */   146,   73,  113,   37,  126,  137,  144,  139,  140,   22,
 /*    60 */    69,  129,  131,   77,  163,   81,  113,   38,  162,   86,
 /*    70 */   165,  267,   35,  110,  104,   17,  156,  155,  169,  102,
 /*    80 */   108,  107,  109,  135,   34,  139,  140,   66,  136,   29,
 /*    90 */    27,  128,    1,  166,  160,   73,   40,   12,  120,  137,
 /*   100 */   144,  123,   18,   95,  116,   14,   97,  143,   18,   28,
 /*   110 */   113,   14,   82,   99,   74,    5,    3,   29,   32,  142,
 /*   120 */   132,   46,  126,  117,   21,  128,  139,  140,  128,   72,
 /*   130 */   166,  146,   73,  137,  144,   38,  137,  144,  118,  124,
 /*   140 */    13,   78,   19,  111,    8,   34,  119,  114,   68,   20,
 /*   150 */    86,  165,  128,  132,  166,  160,   73,  138,   29,   34,
 /*   160 */   137,  144,   59,   23,  152,   12,  128,  132,  166,  160,
 /*   170 */    73,  113,  188,   13,  137,  144,  136,   11,   27,   67,
 /*   180 */     1,  154,  119,  114,   42,  113,  149,   13,    2,   31,
 /*   190 */   151,   87,  139,  140,   83,  143,  128,   90,  166,  160,
 /*   200 */    73,  139,  140,    5,  137,  144,   32,  142,  103,  104,
 /*   210 */   136,  122,   27,  136,    1,   27,  100,    6,   40,   91,
 /*   220 */   121,   40,  158,  148,   29,  139,  140,   90,   84,  143,
 /*   230 */   131,   97,  143,   29,   54,  119,  114,    5,   75,   90,
 /*   240 */    32,  142,   18,   32,  142,   14,  159,   99,  134,  162,
 /*   250 */    23,  136,   75,   27,  132,    6,   43,   29,  136,   40,
 /*   260 */    27,  130,    6,  128,  132,   26,   41,   76,   71,   84,
 /*   270 */   143,  137,  144,  147,   13,   94,   39,  143,  153,   89,
 /*   280 */   162,   32,  142,   52,   13,  139,  140,  167,   32,  142,
 /*   290 */   128,  127,  166,  160,   73,   47,   70,  129,  137,  144,
 /*   300 */   139,  140,  128,   25,  166,  146,   73,   98,  133,    9,
 /*   310 */   137,  144,   52,   28,  158,  106,   30,   29,  162,  128,
 /*   320 */    96,  166,  160,   73,   36,  164,  128,  137,  144,   11,
 /*   330 */    79,  162,   29,   52,  137,  144,  168,    7,  150,  125,
 /*   340 */   128,   16,  166,  160,   73,   55,   80,   38,  137,  144,
 /*   350 */     4,  115,  128,   93,  166,  160,   73,   88,   48,  131,
 /*   360 */   137,  144,  136,  105,   44,  128,   15,  166,  160,   73,
 /*   370 */    40,  141,  181,  137,  144,   58,  181,   69,  129,  131,
 /*   380 */    97,  143,  128,  181,  166,  160,   73,  181,   57,  181,
 /*   390 */   137,  144,   32,  142,  181,  128,  181,  166,  160,   73,
 /*   400 */    49,  139,  140,  137,  144,  145,  181,  128,  181,  166,
 /*   410 */   160,   73,   51,  181,   24,  137,  144,   19,  181,  128,
 /*   420 */   181,  166,  160,   73,  181,   61,  181,  137,  144,  162,
 /*   430 */   181,  181,  128,   29,  166,  160,   73,   64,  181,  181,
 /*   440 */   137,  144,  181,  181,  128,  181,  166,  160,   73,   50,
 /*   450 */   181,  181,  137,  144,  181,  181,  128,  181,  166,  160,
 /*   460 */    73,   53,  181,  181,  137,  144,  181,  181,  128,  181,
 /*   470 */   166,  160,   73,   60,  181,  181,  137,  144,  181,  181,
 /*   480 */   128,  181,  166,  160,   73,  181,   62,  181,  137,  144,
 /*   490 */   139,  140,  181,  128,  145,  166,  160,   73,  181,  181,
 /*   500 */   181,  137,  144,  181,  181,  181,   18,  181,  181,   14,
 /*   510 */   181,   99,  181,  181,  181,  181,   75,  181,  132,  181,
 /*   520 */   181,  181,   29,  181,  181,  181,  181,  181,  181,  181,
 /*   530 */   181,  181,  181,  181,  181,  181,  181,  181,   13,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   12,   11,   11,   15,   52,   17,   16,   16,
 /*    10 */    20,   24,   22,   59,   24,    5,   23,   63,   19,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   51,   40,   53,   54,
 /*    30 */    55,   44,   39,   58,   44,   60,   61,   62,   10,   24,
 /*    40 */    51,   66,   67,   54,   34,   35,   10,   58,   20,   60,
 /*    50 */    61,   62,   77,   56,    1,   66,   67,    7,    8,   23,
 /*    60 */    69,   70,   71,   72,   73,   64,   77,   14,   71,   80,
 /*    70 */    81,   46,   47,   48,   49,   17,   26,   27,   28,   29,
 /*    80 */    30,   31,   32,    5,   51,    7,    8,   54,    6,   39,
 /*    90 */     8,   58,   10,   60,   61,   62,   14,   44,    1,   66,
 /*   100 */    67,    1,   12,    3,    5,   15,   24,   25,   12,   63,
 /*   110 */    77,   15,   50,   17,   16,   33,   18,   39,   36,   37,
 /*   120 */    24,   51,    1,   11,   78,   58,    7,    8,   58,   62,
 /*   130 */    60,   61,   62,   66,   67,   14,   66,   67,    9,   42,
 /*   140 */    44,   41,   23,   43,   44,   51,   34,   35,   54,   20,
 /*   150 */    80,   81,   58,   24,   60,   61,   62,   36,   39,   51,
 /*   160 */    66,   67,   54,   20,   76,   44,   58,   24,   60,   61,
 /*   170 */    62,   77,    5,   44,   66,   67,    6,   10,    8,   52,
 /*   180 */    10,    5,   34,   35,   14,   77,   59,   44,   21,   51,
 /*   190 */     5,   53,    7,    8,   24,   25,   58,   21,   60,   61,
 /*   200 */    62,    7,    8,   33,   66,   67,   36,   37,   48,   49,
 /*   210 */     6,    5,    8,    6,   10,    8,   57,   10,   14,   18,
 /*   220 */     1,   14,   58,   74,   39,    7,    8,   21,   24,   25,
 /*   230 */    71,   24,   25,   39,   56,   34,   35,   33,   22,   21,
 /*   240 */    36,   37,   12,   36,   37,   15,   82,   17,   73,   71,
 /*   250 */    20,    6,   22,    8,   24,   10,   14,   39,    6,   14,
 /*   260 */     8,   24,   10,   58,   24,   56,   14,   62,   38,   24,
 /*   270 */    25,   66,   67,   11,   44,   24,   24,   25,    5,   24,
 /*   280 */    71,   36,   37,   51,   44,    7,    8,   11,   36,   37,
 /*   290 */    58,   13,   60,   61,   62,   51,   69,   70,   66,   67,
 /*   300 */     7,    8,   58,   56,   60,   61,   62,   75,   24,   16,
 /*   310 */    66,   67,   51,   63,   58,    5,   56,   39,   71,   58,
 /*   320 */    24,   60,   61,   62,   68,   81,   58,   66,   67,   10,
 /*   330 */    62,   71,   39,   51,   66,   67,   75,   10,   82,    5,
 /*   340 */    58,   20,   60,   61,   62,   51,   65,   14,   66,   67,
 /*   350 */    79,   53,   58,   58,   60,   61,   62,   75,   51,   71,
 /*   360 */    66,   67,    6,   59,   14,   58,   10,   60,   61,   62,
 /*   370 */    14,   70,   83,   66,   67,   51,   83,   69,   70,   71,
 /*   380 */    24,   25,   58,   83,   60,   61,   62,   83,   51,   83,
 /*   390 */    66,   67,   36,   37,   83,   58,   83,   60,   61,   62,
 /*   400 */    51,    7,    8,   66,   67,   11,   83,   58,   83,   60,
 /*   410 */    61,   62,   51,   83,   56,   66,   67,   23,   83,   58,
 /*   420 */    83,   60,   61,   62,   83,   51,   83,   66,   67,   71,
 /*   430 */    83,   83,   58,   39,   60,   61,   62,   51,   83,   83,
 /*   440 */    66,   67,   83,   83,   58,   83,   60,   61,   62,   51,
 /*   450 */    83,   83,   66,   67,   83,   83,   58,   83,   60,   61,
 /*   460 */    62,   51,   83,   83,   66,   67,   83,   83,   58,   83,
 /*   470 */    60,   61,   62,   51,   83,   83,   66,   67,   83,   83,
 /*   480 */    58,   83,   60,   61,   62,   83,   51,   83,   66,   67,
 /*   490 */     7,    8,   83,   58,   11,   60,   61,   62,   83,   83,
 /*   500 */    83,   66,   67,   83,   83,   83,   12,   83,   83,   15,
 /*   510 */    83,   17,   83,   83,   83,   83,   22,   83,   24,   83,
 /*   520 */    83,   83,   39,   83,   83,   83,   83,   83,   83,   83,
 /*   530 */    83,   83,   83,   83,   83,   83,   83,   83,   44,
);
    const YY_SHIFT_USE_DFLT = -14;
    const YY_SHIFT_MAX = 101;
    static public $yy_shift_ofst = array(
 /*     0 */   100,  204,  170,   82,   82,   82,  245,  207,  252,  207,
 /*    10 */   245,  207,  207,  207,  207,  207,  207,  207,  207,  207,
 /*    20 */   207,  207,  207,  207,  230,  -10,  494,  356,  356,  356,
 /*    30 */    96,  218,   53,   -7,   50,  100,  121,  129,  -13,  167,
 /*    40 */   -13,  -13,  -13,  240,  240,   90,  394,  119,   78,  185,
 /*    50 */   483,  278,  293,  194,  143,  194,  112,  194,  194,  201,
 /*    60 */   194,  194,  194,   10,  194,  206,  148,  176,  148,   90,
 /*    70 */    90,  333,   -1,   -1,  350,  251,   -1,  216,  219,   -1,
 /*    80 */    58,  -14,   97,   28,   36,   98,   -8,  273,  276,  321,
 /*    90 */   255,  242,  284,  334,  327,  296,  310,  319,  262,  237,
 /*   100 */    99,   15,
);
    const YY_REDUCE_USE_DFLT = -47;
    const YY_REDUCE_MAX = 81;
    static public $yy_reduce_ofst = array(
 /*     0 */    25,  -11,  -25,  108,   33,   94,   70,  232,  138,  261,
 /*    10 */   244,  282,  349,  307,  435,  398,  410,  374,  361,  386,
 /*    20 */   422,  337,  324,  294,   -9,   -9,   -9,  205,   67,  268,
 /*    30 */   308,  -46,  256,   46,   46,  160,  164,  159,  260,  127,
 /*    40 */   209,  247,  358,   -3,  178,  227,  250,  250,  250,  250,
 /*    50 */   250,  250,  250,  250,  288,  250,  271,  250,  250,  271,
 /*    60 */   250,  250,  250,  271,  250,  304,  271,  304,  271,  301,
 /*    70 */   301,  295,    1,    1,  298,  149,    1,  175,   62,    1,
 /*    80 */    88,  281,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 41, 43, 44, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
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
        /* 24 */ array(12, 15, 17, 20, 22, 24, 38, 44, ),
        /* 25 */ array(12, 15, 17, 20, 22, 24, 44, ),
        /* 26 */ array(12, 15, 17, 22, 24, 44, ),
        /* 27 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(12, 15, 17, 24, 44, ),
        /* 31 */ array(7, 8, 21, 39, ),
        /* 32 */ array(1, 14, 44, ),
        /* 33 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 34 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 35 */ array(1, 3, 41, 43, 44, ),
        /* 36 */ array(1, 14, 36, 44, ),
        /* 37 */ array(9, 20, 24, 44, ),
        /* 38 */ array(24, 40, 44, ),
        /* 39 */ array(5, 10, 21, ),
        /* 40 */ array(24, 40, 44, ),
        /* 41 */ array(24, 40, 44, ),
        /* 42 */ array(24, 40, 44, ),
        /* 43 */ array(24, 44, ),
        /* 44 */ array(24, 44, ),
        /* 45 */ array(12, 15, ),
        /* 46 */ array(7, 8, 11, 23, 39, ),
        /* 47 */ array(7, 8, 23, 39, ),
        /* 48 */ array(5, 7, 8, 39, ),
        /* 49 */ array(5, 7, 8, 39, ),
        /* 50 */ array(7, 8, 11, 39, ),
        /* 51 */ array(7, 8, 13, 39, ),
        /* 52 */ array(7, 8, 16, 39, ),
        /* 53 */ array(7, 8, 39, ),
        /* 54 */ array(20, 24, 44, ),
        /* 55 */ array(7, 8, 39, ),
        /* 56 */ array(11, 34, 35, ),
        /* 57 */ array(7, 8, 39, ),
        /* 58 */ array(7, 8, 39, ),
        /* 59 */ array(18, 34, 35, ),
        /* 60 */ array(7, 8, 39, ),
        /* 61 */ array(7, 8, 39, ),
        /* 62 */ array(7, 8, 39, ),
        /* 63 */ array(5, 34, 35, ),
        /* 64 */ array(7, 8, 39, ),
        /* 65 */ array(5, 21, ),
        /* 66 */ array(34, 35, ),
        /* 67 */ array(5, 21, ),
        /* 68 */ array(34, 35, ),
        /* 69 */ array(12, 15, ),
        /* 70 */ array(12, 15, ),
        /* 71 */ array(14, ),
        /* 72 */ array(19, ),
        /* 73 */ array(19, ),
        /* 74 */ array(14, ),
        /* 75 */ array(24, ),
        /* 76 */ array(19, ),
        /* 77 */ array(22, ),
        /* 78 */ array(1, ),
        /* 79 */ array(19, ),
        /* 80 */ array(17, ),
        /* 81 */ array(),
        /* 82 */ array(1, 42, ),
        /* 83 */ array(10, 20, ),
        /* 84 */ array(10, 23, ),
        /* 85 */ array(16, 18, ),
        /* 86 */ array(11, 16, ),
        /* 87 */ array(5, ),
        /* 88 */ array(11, ),
        /* 89 */ array(20, ),
        /* 90 */ array(24, ),
        /* 91 */ array(14, ),
        /* 92 */ array(24, ),
        /* 93 */ array(5, ),
        /* 94 */ array(10, ),
        /* 95 */ array(24, ),
        /* 96 */ array(5, ),
        /* 97 */ array(10, ),
        /* 98 */ array(11, ),
        /* 99 */ array(24, ),
        /* 100 */ array(5, ),
        /* 101 */ array(24, ),
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
 /*     0 */   266,  266,  266,  266,  266,  266,  266,  232,  266,  232,
 /*    10 */   266,  232,  266,  266,  266,  266,  266,  266,  266,  266,
 /*    20 */   266,  266,  266,  266,  216,  216,  216,  266,  266,  266,
 /*    30 */   216,  188,  266,  240,  240,  170,  266,  266,  266,  208,
 /*    40 */   266,  266,  266,  266,  266,  216,  255,  255,  266,  266,
 /*    50 */   266,  266,  231,  189,  266,  192,  266,  241,  257,  266,
 /*    60 */   184,  236,  217,  266,  256,  266,  242,  266,  238,  211,
 /*    70 */   213,  266,  197,  195,  266,  266,  196,  223,  266,  198,
 /*    80 */   201,  235,  266,  208,  208,  266,  266,  266,  266,  266,
 /*    90 */   266,  266,  266,  266,  226,  266,  266,  208,  266,  266,
 /*   100 */   266,  266,  246,  172,  173,  186,  180,  248,  247,  249,
 /*   110 */   171,  175,  190,  237,  251,  191,  182,  239,  185,  250,
 /*   120 */   264,  265,  177,  176,  174,  183,  263,  218,  202,  214,
 /*   130 */   212,  220,  221,  233,  225,  222,  203,  204,  207,  200,
 /*   140 */   199,  215,  209,  206,  205,  210,  194,  229,  227,  187,
 /*   150 */   260,  262,  234,  178,  179,  244,  243,  181,  261,  259,
 /*   160 */   194,  252,  219,  224,  254,  253,  193,  228,  230,  245,
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
    const YYNOCODE = 84;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 170;
    const YYNRULE = 96;
    const YYERRORSYMBOL = 45;
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
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*     UNDERL => OTHER */
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
  'QUOTE',         'BOOLEAN',       'IN',            'ANDSYM',      
  'UNDERL',        'COMMENTSTART',  'COMMENTEND',    'PHP',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'ifexprs',       'statements',  
  'varvar',        'foraction',     'variable',      'attribute',   
  'exprs',         'array',         'value',         'math',        
  'modifier',      'modparameters',  'object',        'function',    
  'doublequoted',  'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'method',        'params',      
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'doublequotedcontent',
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
 /*   4 */ "template_element ::= COMMENTSTART text COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr attributes RDEL",
 /*   8 */ "smartytag ::= LDEL statement RDEL",
 /*   9 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  10 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  11 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  12 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  14 */ "foraction ::= EQUAL expr",
 /*  15 */ "foraction ::= INCDEC",
 /*  16 */ "attributes ::= attributes attribute",
 /*  17 */ "attributes ::= attribute",
 /*  18 */ "attributes ::=",
 /*  19 */ "attribute ::= SPACE ID EQUAL expr",
 /*  20 */ "statements ::= statement",
 /*  21 */ "statements ::= statements COMMA statement",
 /*  22 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  23 */ "expr ::= exprs",
 /*  24 */ "expr ::= array",
 /*  25 */ "exprs ::= value",
 /*  26 */ "exprs ::= UNIMATH value",
 /*  27 */ "exprs ::= expr math value",
 /*  28 */ "exprs ::= expr ANDSYM value",
 /*  29 */ "math ::= UNIMATH",
 /*  30 */ "math ::= MATH",
 /*  31 */ "value ::= value modifier modparameters",
 /*  32 */ "value ::= variable",
 /*  33 */ "value ::= NUMBER",
 /*  34 */ "value ::= object",
 /*  35 */ "value ::= function",
 /*  36 */ "value ::= SI_QSTR",
 /*  37 */ "value ::= QUOTE doublequoted QUOTE",
 /*  38 */ "value ::= ID",
 /*  39 */ "value ::= BOOLEAN",
 /*  40 */ "value ::= OPENP expr CLOSEP",
 /*  41 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  42 */ "variable ::= DOLLAR varvar COLON ID",
 /*  43 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  44 */ "vararraydefs ::= vararraydef",
 /*  45 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  46 */ "vararraydefs ::=",
 /*  47 */ "vararraydef ::= DOT expr",
 /*  48 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  49 */ "varvar ::= varvarele",
 /*  50 */ "varvar ::= varvar varvarele",
 /*  51 */ "varvarele ::= ID",
 /*  52 */ "varvarele ::= LDEL expr RDEL",
 /*  53 */ "object ::= DOLLAR varvar objectchain",
 /*  54 */ "objectchain ::= objectelement",
 /*  55 */ "objectchain ::= objectchain objectelement",
 /*  56 */ "objectelement ::= PTR ID",
 /*  57 */ "objectelement ::= PTR method",
 /*  58 */ "function ::= ID OPENP params CLOSEP",
 /*  59 */ "method ::= ID OPENP params CLOSEP",
 /*  60 */ "params ::= expr COMMA params",
 /*  61 */ "params ::= expr",
 /*  62 */ "params ::=",
 /*  63 */ "modifier ::= VERT ID",
 /*  64 */ "modparameters ::= modparameters modparameter",
 /*  65 */ "modparameters ::=",
 /*  66 */ "modparameter ::= COLON expr",
 /*  67 */ "ifexprs ::= ifexpr",
 /*  68 */ "ifexprs ::= NOT ifexprs",
 /*  69 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  70 */ "ifexpr ::= expr",
 /*  71 */ "ifexpr ::= expr ifcond expr",
 /*  72 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  73 */ "ifcond ::= EQUALS",
 /*  74 */ "ifcond ::= NOTEQUALS",
 /*  75 */ "ifcond ::= GREATERTHAN",
 /*  76 */ "ifcond ::= LESSTHAN",
 /*  77 */ "ifcond ::= GREATEREQUAL",
 /*  78 */ "ifcond ::= LESSEQUAL",
 /*  79 */ "ifcond ::= IDENTITY",
 /*  80 */ "lop ::= LAND",
 /*  81 */ "lop ::= LOR",
 /*  82 */ "array ::= OPENP arrayelements CLOSEP",
 /*  83 */ "arrayelements ::= arrayelement",
 /*  84 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  85 */ "arrayelement ::= expr",
 /*  86 */ "arrayelement ::= expr APTR expr",
 /*  87 */ "arrayelement ::= ID APTR expr",
 /*  88 */ "arrayelement ::= array",
 /*  89 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  90 */ "doublequoted ::= doublequotedcontent",
 /*  91 */ "doublequotedcontent ::= variable",
 /*  92 */ "doublequotedcontent ::= LDEL expr RDEL",
 /*  93 */ "doublequotedcontent ::= OTHER",
 /*  94 */ "text ::= text OTHER",
 /*  95 */ "text ::= OTHER",
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
        if ($tokenType > 0 && $tokenType < count($this->yyTokenName)) {
            return $this->yyTokenName[$tokenType];
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
                self::$yyTracePrompt . 'Popping ' . $this->yyTokenName[$yytos->major] .
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
                        $this->yyTokenName[$iLookAhead] . " => " .
                        $this->yyTokenName[$iFallback] . "\n");
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
                    $this->yyTokenName[$this->yystack[$i]->major]);
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
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 5 ),
  array( 'lhs' => 49, 'rhs' => 11 ),
  array( 'lhs' => 49, 'rhs' => 8 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 0 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 0 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 0 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
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
        32 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        39 => 0,
        83 => 0,
        1 => 1,
        23 => 1,
        24 => 1,
        29 => 1,
        30 => 1,
        44 => 1,
        49 => 1,
        67 => 1,
        90 => 1,
        93 => 1,
        95 => 1,
        2 => 2,
        45 => 2,
        89 => 2,
        94 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
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
        17 => 15,
        61 => 15,
        85 => 15,
        88 => 15,
        16 => 16,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        26 => 26,
        27 => 27,
        28 => 28,
        31 => 31,
        37 => 37,
        38 => 38,
        40 => 40,
        41 => 41,
        53 => 41,
        42 => 42,
        43 => 43,
        46 => 46,
        65 => 46,
        47 => 47,
        48 => 48,
        50 => 50,
        51 => 51,
        52 => 52,
        69 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 56,
        58 => 58,
        59 => 59,
        60 => 60,
        62 => 62,
        63 => 63,
        64 => 64,
        66 => 66,
        68 => 68,
        70 => 70,
        71 => 71,
        72 => 71,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        84 => 84,
        86 => 86,
        87 => 86,
        91 => 91,
        92 => 92,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 68 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1426 "internal.templateparser.php"
#line 74 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1429 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1432 "internal.templateparser.php"
#line 82 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1437 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->template->cacher_object->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1440 "internal.templateparser.php"
#line 88 "internal.templateparser.y"
    function yy_r5(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->template->cacher_object->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1446 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1449 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1452 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1455 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1458 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1461 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1464 "internal.templateparser.php"
#line 111 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1467 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1470 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1473 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1476 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1479 "internal.templateparser.php"
#line 125 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = array();    }
#line 1482 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1485 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1488 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r21(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1491 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1494 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1497 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1500 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1503 "internal.templateparser.php"
#line 168 "internal.templateparser.y"
    function yy_r31(){if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                           $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
                                                                      $this->_retvalue = "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -1]->minor . "(array(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor ."),'modifier')";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . $this->yystack[$this->yyidx + -1]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1517 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1520 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1523 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1526 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1529 "internal.templateparser.php"
#line 208 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1532 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1535 "internal.templateparser.php"
#line 215 "internal.templateparser.y"
    function yy_r46(){return;    }
#line 1538 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1541 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1544 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1547 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1550 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1553 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1556 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r55(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1559 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1562 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r58(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1571 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1574 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1577 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r62(){ return;    }
#line 1580 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1583 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1586 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1589 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1592 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1595 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1598 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '==';    }
#line 1601 "internal.templateparser.php"
#line 299 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '!=';    }
#line 1604 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '>';    }
#line 1607 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '<';    }
#line 1610 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '>=';    }
#line 1613 "internal.templateparser.php"
#line 303 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '<=';    }
#line 1616 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '===';    }
#line 1619 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '&&';    }
#line 1622 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '||';    }
#line 1625 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1628 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1631 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r86(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1634 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1637 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1640 "internal.templateparser.php"

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
#line 52 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1757 "internal.templateparser.php"
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
#line 44 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1782 "internal.templateparser.php"
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
                self::$yyTracePrompt, $this->yyTokenName[$yymajor]);
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
                                self::$yyTracePrompt, $this->yyTokenName[$yymajor]);
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
