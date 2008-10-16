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
    const TP_AS                             = 38;
    const TP_COMMENTSTART                   = 39;
    const TP_COMMENTEND                     = 40;
    const TP_PHP                            = 41;
    const TP_LDEL                           = 42;
    const TP_NOCACHE                        = 43;
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
    const YY_SZ_ACTTAB = 459;
static public $yy_action = array(
 /*     0 */   144,  140,   36,   11,  168,  149,   17,  126,   27,   10,
 /*    10 */   159,   26,   86,   79,  142,  139,   70,  154,  133,   99,
 /*    20 */   104,  105,   97,   98,  120,  116,  130,  144,  140,  150,
 /*    30 */   144,  140,   45,   73,  168,   27,   18,  126,   27,   86,
 /*    40 */   159,  103,   86,  157,  142,  139,   99,  104,  105,   97,
 /*    50 */    98,  120,  116,  161,  110,   29,  126,    6,  161,  125,
 /*    60 */    29,   55,    6,  142,  139,   13,   55,  267,   38,  102,
 /*    70 */   109,   74,  136,  100,   32,   89,   94,  136,   58,  126,
 /*    80 */     3,  162,  159,   33,  127,    3,  142,  139,   33,  127,
 /*    90 */   148,  161,  165,   29,  133,   16,  128,  107,  161,   55,
 /*   100 */    29,   95,   16,  145,  163,   54,   55,  144,  140,   94,
 /*   110 */   136,   66,   18,  115,   23,   27,   94,  136,    7,   86,
 /*   120 */   148,   33,  127,  161,  155,   29,   93,    4,   33,  127,
 /*   130 */   161,   55,   29,   15,   16,  144,  140,   96,   55,  168,
 /*   140 */    73,   94,  136,   27,  144,  140,   67,   86,   94,  136,
 /*   150 */     3,   14,   27,   33,  127,  161,   86,   29,  167,    1,
 /*   160 */    33,  127,  161,   55,   29,   10,    1,  111,  119,  126,
 /*   170 */    55,  161,  160,   94,  136,   16,  142,  139,   19,   55,
 /*   180 */    80,  136,   68,   31,   82,   33,  127,   62,  126,   94,
 /*   190 */   136,  159,   33,  127,   92,  142,  139,  143,  113,  108,
 /*   200 */    35,   33,  127,  149,  156,  126,   84,  114,  159,   10,
 /*   210 */   144,  140,  142,  139,   87,  161,  146,   29,   27,   16,
 /*   220 */     2,   24,   86,   56,   32,  170,   78,  121,   62,  126,
 /*   230 */    44,   10,  159,   53,  136,  126,  142,  139,  159,   88,
 /*   240 */   165,   11,  142,  139,   31,   33,  127,   75,   62,  126,
 /*   250 */     9,   77,  159,   54,  113,  108,  142,  139,   69,  141,
 /*   260 */   134,   65,  131,   57,   32,   28,   32,   81,   64,  126,
 /*   270 */    59,   71,  159,  106,  159,  137,  142,  139,  142,  139,
 /*   280 */   101,   15,  144,  140,   57,  126,   28,  107,  135,  107,
 /*   290 */    27,  117,  142,  139,   86,   49,   73,   44,  166,   22,
 /*   300 */   126,  123,  126,  159,   25,  159,   20,  142,  139,  142,
 /*   310 */   139,   34,  162,  153,  158,  144,  140,   40,   76,  130,
 /*   320 */    83,   52,  126,   27,   85,  159,  130,   86,   17,  142,
 /*   330 */   139,  124,  151,  144,  140,  169,   12,    8,   70,   54,
 /*   340 */   133,   27,   69,  141,  134,   86,    5,   39,  112,  109,
 /*   350 */    60,  164,  126,  144,  140,  159,  113,  108,   18,  142,
 /*   360 */   139,   27,  122,  129,   37,   86,  236,  236,   20,  126,
 /*   370 */   238,  238,  159,   43,  118,   70,  142,  139,  126,   42,
 /*   380 */    21,  159,   63,   72,  126,  142,  139,  159,  147,   47,
 /*   390 */   152,  142,  139,   17,  126,   91,   90,  159,   48,  176,
 /*   400 */   132,  142,  139,  126,   50,  176,  159,  138,  176,  126,
 /*   410 */   142,  139,  159,   61,  144,  140,  142,  139,  176,  147,
 /*   420 */    46,   57,   27,   28,   17,  126,   86,  176,  159,   41,
 /*   430 */    14,  176,  142,  139,  126,   51,  133,  159,  176,  176,
 /*   440 */   126,  142,  139,  159,  176,   30,  176,  142,  139,  176,
 /*   450 */   126,  176,  176,  159,   18,  176,  176,  142,  139,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   50,   20,   11,   53,   12,   55,   15,   10,
 /*    10 */    58,   52,   19,   11,   62,   63,   22,    5,   24,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   67,    7,    8,   77,
 /*    30 */     7,    8,   50,   21,   11,   15,   42,   55,   15,   19,
 /*    40 */    58,    5,   19,    5,   62,   63,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,    9,    8,   55,   10,    6,   58,
 /*    60 */     8,   14,   10,   62,   63,   20,   14,   45,   46,   47,
 /*    70 */    48,   24,   25,    1,   50,    3,   24,   25,   54,   55,
 /*    80 */    33,   55,   58,   36,   37,   33,   62,   63,   36,   37,
 /*    90 */    43,    6,    1,    8,   24,   10,   11,   73,    6,   14,
 /*   100 */     8,   24,   10,   11,   78,   14,   14,    7,    8,   24,
 /*   110 */    25,   39,   42,   41,   42,   15,   24,   25,   18,   19,
 /*   120 */    43,   36,   37,    6,    5,    8,   14,   10,   36,   37,
 /*   130 */     6,   14,    8,   42,   10,    7,    8,   24,   14,   11,
 /*   140 */    21,   24,   25,   15,    7,    8,   60,   19,   24,   25,
 /*   150 */    33,   23,   15,   36,   37,    6,   19,    8,   72,   10,
 /*   160 */    36,   37,    6,   14,    8,   10,   10,    5,    1,   55,
 /*   170 */    14,    6,   58,   24,   25,   10,   62,   63,   23,   14,
 /*   180 */    24,   25,   18,   50,   14,   36,   37,   54,   55,   24,
 /*   190 */    25,   58,   36,   37,   23,   62,   63,   24,   34,   35,
 /*   200 */    50,   36,   37,   53,    5,   55,   73,   40,   58,   10,
 /*   210 */     7,    8,   62,   63,   24,    6,   13,    8,   15,   10,
 /*   220 */    21,   20,   19,   14,   50,    5,   76,   77,   54,   55,
 /*   230 */    50,   10,   58,   24,   25,   55,   62,   63,   58,   38,
 /*   240 */     1,   20,   62,   63,   50,   36,   37,   73,   54,   55,
 /*   250 */    10,   71,   58,   14,   34,   35,   62,   63,   65,   66,
 /*   260 */    67,   68,   69,   59,   50,   61,   50,   73,   54,   55,
 /*   270 */    54,   55,   58,   11,   58,   36,   62,   63,   62,   63,
 /*   280 */     5,   42,    7,    8,   59,   55,   61,   73,   58,   73,
 /*   290 */    15,    5,   62,   63,   19,   50,   21,   50,   53,   74,
 /*   300 */    55,   11,   55,   58,   52,   58,   16,   62,   63,   62,
 /*   310 */    63,   52,   55,    5,   72,    7,    8,   50,   71,   67,
 /*   320 */    53,   64,   55,   15,   24,   58,   67,   19,   12,   62,
 /*   330 */    63,    5,   11,    7,    8,   78,   20,   16,   22,   14,
 /*   340 */    24,   15,   65,   66,   67,   19,   75,   50,   47,   48,
 /*   350 */    55,    5,   55,    7,    8,   58,   34,   35,   42,   62,
 /*   360 */    63,   15,   69,   11,   50,   19,   34,   35,   16,   55,
 /*   370 */    34,   35,   58,   50,    1,   22,   62,   63,   55,   50,
 /*   380 */    17,   58,   51,   49,   55,   62,   63,   58,   57,   50,
 /*   390 */    57,   62,   63,   12,   55,   24,   56,   58,   50,   79,
 /*   400 */    70,   62,   63,   55,   50,   79,   58,   66,   79,   55,
 /*   410 */    62,   63,   58,   51,    7,    8,   62,   63,   79,   57,
 /*   420 */    50,   59,   15,   61,   12,   55,   19,   79,   58,   50,
 /*   430 */    23,   79,   62,   63,   55,   50,   24,   58,   79,   79,
 /*   440 */    55,   62,   63,   58,   79,   50,   79,   62,   63,   79,
 /*   450 */    55,   79,   79,   58,   42,   79,   79,   62,   63,
);
    const YY_SHIFT_USE_DFLT = -18;
    const YY_SHIFT_MAX = 96;
    static public $yy_shift_ofst = array(
 /*     0 */    72,  156,   47,  117,   52,   52,   52,   52,  156,   85,
 /*    10 */    92,  149,  149,  124,  124,  124,  124,  124,  124,  124,
 /*    20 */   124,  124,  124,  209,  124,  316,   -6,  165,  165,  165,
 /*    30 */   275,   -7,   20,   91,  412,  128,  407,  203,   72,  326,
 /*    40 */   308,  100,  346,   23,  137,  137,  137,  137,  137,  137,
 /*    50 */   137,  137,  239,  199,   70,   70,   70,  363,  164,  220,
 /*    60 */    45,   12,  322,  119,  322,  353,  373,  363,  325,  381,
 /*    70 */   371,  201,  167,   77,  221,  332,  352,  290,  321,  336,
 /*    80 */   155,    2,  300,   38,  262,  286,  173,  171,  112,  113,
 /*    90 */   162,  240,  170,  190,   -1,  -17,   36,
);
    const YY_REDUCE_USE_DFLT = -49;
    const YY_REDUCE_MAX = 70;
    static public $yy_reduce_ofst = array(
 /*     0 */    22,  150,  216,  174,  194,  214,  133,   24,  -48,  180,
 /*    10 */   247,  245,  267,  339,  -18,  329,  323,  314,  297,  354,
 /*    20 */   348,  370,  385,  395,  379,  193,  193,  230,    1,  114,
 /*    30 */   362,  225,  225,  257,  277,  204,  204,  204,  301,  204,
 /*    40 */   204,  204,  204,  204,  204,  204,  204,  204,  204,  204,
 /*    50 */   204,  204,   26,  331,  259,  -41,  252,   86,  271,  271,
 /*    60 */   340,  333,  271,  333,  271,  293,  334,  242,  295,  341,
 /*    70 */   330,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 39, 41, 42, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, 43, ),
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
        /* 33 */ array(1, 14, 42, ),
        /* 34 */ array(12, 24, 42, ),
        /* 35 */ array(7, 8, 11, 15, 19, 23, ),
        /* 36 */ array(7, 8, 15, 19, 23, ),
        /* 37 */ array(7, 8, 13, 15, 19, ),
        /* 38 */ array(1, 3, 39, 41, 42, ),
        /* 39 */ array(5, 7, 8, 15, 19, ),
        /* 40 */ array(5, 7, 8, 15, 19, ),
        /* 41 */ array(7, 8, 15, 18, 19, ),
        /* 42 */ array(5, 7, 8, 15, 19, ),
        /* 43 */ array(7, 8, 11, 15, 19, ),
        /* 44 */ array(7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(7, 8, 15, 19, ),
        /* 47 */ array(7, 8, 15, 19, ),
        /* 48 */ array(7, 8, 15, 19, ),
        /* 49 */ array(7, 8, 15, 19, ),
        /* 50 */ array(7, 8, 15, 19, ),
        /* 51 */ array(7, 8, 15, 19, ),
        /* 52 */ array(1, 14, 36, 42, ),
        /* 53 */ array(5, 10, 21, ),
        /* 54 */ array(24, 42, ),
        /* 55 */ array(24, 42, ),
        /* 56 */ array(24, 42, ),
        /* 57 */ array(17, ),
        /* 58 */ array(18, 34, 35, ),
        /* 59 */ array(5, 34, 35, ),
        /* 60 */ array(9, 20, ),
        /* 61 */ array(5, 21, ),
        /* 62 */ array(34, 35, ),
        /* 63 */ array(5, 21, ),
        /* 64 */ array(34, 35, ),
        /* 65 */ array(22, ),
        /* 66 */ array(1, ),
        /* 67 */ array(17, ),
        /* 68 */ array(14, ),
        /* 69 */ array(12, ),
        /* 70 */ array(24, ),
        /* 71 */ array(20, 38, ),
        /* 72 */ array(1, 40, ),
        /* 73 */ array(24, 43, ),
        /* 74 */ array(10, 20, ),
        /* 75 */ array(34, 35, ),
        /* 76 */ array(11, 16, ),
        /* 77 */ array(11, 16, ),
        /* 78 */ array(11, 16, ),
        /* 79 */ array(34, 35, ),
        /* 80 */ array(10, 23, ),
        /* 81 */ array(11, ),
        /* 82 */ array(24, ),
        /* 83 */ array(5, ),
        /* 84 */ array(11, ),
        /* 85 */ array(5, ),
        /* 86 */ array(24, ),
        /* 87 */ array(23, ),
        /* 88 */ array(14, ),
        /* 89 */ array(24, ),
        /* 90 */ array(5, ),
        /* 91 */ array(10, ),
        /* 92 */ array(14, ),
        /* 93 */ array(24, ),
        /* 94 */ array(10, ),
        /* 95 */ array(20, ),
        /* 96 */ array(5, ),
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
        /* 170 */ array(),
);
    static public $yy_default = array(
 /*     0 */   266,  266,  266,  266,  266,  266,  266,  266,  266,  266,
 /*    10 */   266,  266,  266,  266,  266,  266,  266,  266,  266,  266,
 /*    20 */   266,  266,  266,  266,  266,  212,  212,  266,  266,  266,
 /*    30 */   266,  240,  240,  266,  212,  255,  255,  266,  171,  266,
 /*    40 */   266,  266,  266,  266,  230,  256,  235,  188,  231,  193,
 /*    50 */   257,  241,  266,  211,  266,  266,  266,  197,  266,  266,
 /*    60 */   266,  266,  266,  266,  242,  221,  266,  198,  266,  213,
 /*    70 */   266,  206,  266,  266,  211,  237,  266,  266,  266,  239,
 /*    80 */   211,  236,  266,  266,  236,  266,  266,  266,  266,  266,
 /*    90 */   266,  224,  266,  266,  211,  266,  266,  246,  247,  243,
 /*   100 */   177,  178,  172,  184,  244,  245,  238,  236,  251,  174,
 /*   110 */   189,  186,  173,  250,  175,  176,  249,  187,  265,  264,
 /*   120 */   248,  253,  223,  228,  220,  199,  206,  204,  229,  226,
 /*   130 */   217,  222,  225,  219,  218,  200,  209,  210,  215,  208,
 /*   140 */   201,  214,  207,  232,  202,  227,  216,  190,  192,  258,
 /*   150 */   254,  252,  191,  180,  179,  183,  182,  181,  234,  195,
 /*   160 */   196,  203,  261,  259,  262,  263,  194,  233,  205,  260,
 /*   170 */   185,
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
    const YYNOCODE = 80;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 171;
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
  'QUOTE',         'BOOLEAN',       'AS',            'COMMENTSTART',
  'COMMENTEND',    'PHP',           'LDEL',          'NOCACHE',     
  'error',         'start',         'template',      'template_element',
  'smartytag',     'commenttext',   'expr',          'attributes',  
  'varvar',        'array',         'ifexprs',       'variable',    
  'foraction',     'attribute',     'value',         'modifier',    
  'modparameters',  'math',          'object',        'function',    
  'doublequoted',  'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'method',        'params',      
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'other',       
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
 /*  21 */ "attribute ::= SPACE NOCACHE",
 /*  22 */ "attribute ::= SPACE ID EQUAL expr",
 /*  23 */ "attribute ::= SPACE ID EQUAL array",
 /*  24 */ "expr ::= value",
 /*  25 */ "expr ::= UNIMATH value",
 /*  26 */ "expr ::= expr modifier",
 /*  27 */ "expr ::= expr modifier modparameters",
 /*  28 */ "expr ::= expr math value",
 /*  29 */ "expr ::= expr DOT value",
 /*  30 */ "math ::= UNIMATH",
 /*  31 */ "math ::= MATH",
 /*  32 */ "value ::= NUMBER",
 /*  33 */ "value ::= BOOLEAN",
 /*  34 */ "value ::= OPENP expr CLOSEP",
 /*  35 */ "value ::= variable",
 /*  36 */ "value ::= object",
 /*  37 */ "value ::= function",
 /*  38 */ "value ::= SI_QSTR",
 /*  39 */ "value ::= QUOTE doublequoted QUOTE",
 /*  40 */ "value ::= ID",
 /*  41 */ "variable ::= DOLLAR varvar",
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
 /*  56 */ "function ::= ID OPENP CLOSEP",
 /*  57 */ "method ::= ID OPENP params CLOSEP",
 /*  58 */ "method ::= ID OPENP CLOSEP",
 /*  59 */ "params ::= expr",
 /*  60 */ "params ::= params COMMA expr",
 /*  61 */ "modifier ::= VERT ID",
 /*  62 */ "modparameters ::= modparameter",
 /*  63 */ "modparameters ::= modparameters modparameter",
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
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 6 ),
  array( 'lhs' => 48, 'rhs' => 6 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 5 ),
  array( 'lhs' => 48, 'rhs' => 12 ),
  array( 'lhs' => 48, 'rhs' => 11 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
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
        24 => 0,
        32 => 0,
        33 => 0,
        35 => 0,
        36 => 0,
        37 => 0,
        38 => 0,
        82 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        30 => 1,
        31 => 1,
        43 => 1,
        46 => 1,
        62 => 1,
        64 => 1,
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
        84 => 18,
        87 => 18,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 22,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        29 => 29,
        34 => 34,
        39 => 39,
        40 => 40,
        41 => 41,
        42 => 42,
        50 => 42,
        45 => 45,
        47 => 47,
        48 => 48,
        49 => 49,
        67 => 49,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 53,
        55 => 55,
        56 => 56,
        57 => 57,
        58 => 58,
        60 => 60,
        61 => 61,
        63 => 63,
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
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1400 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1403 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1406 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1409 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -1]->minor),array('_smarty_nocache'=>$this->nocache)));$this->nocache=false;    }
#line 1412 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1415 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue = $this->smarty->compile_tag->execute(array('_smarty_tag'=>'assign','var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor,'_smarty_nocache'=>$this->nocache));$this->nocache=false;    }
#line 1418 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1421 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1424 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1427 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1430 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1433 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -9]->minor,'from'=>$this->yystack[$this->yyidx + -7]->minor,'key'=>$this->yystack[$this->yyidx + -4]->minor,'item'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1436 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1439 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1442 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1445 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1448 "internal.templateparser.php"
#line 125 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1451 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1454 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1457 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r27(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1460 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1463 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1466 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1469 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1472 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1475 "internal.templateparser.php"
#line 179 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->nocache) $this->nocache=true;    }
#line 1478 "internal.templateparser.php"
#line 181 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->nocache) $this->nocache=true;    }
#line 1481 "internal.templateparser.php"
#line 189 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1484 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1487 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1490 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1493 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1496 "internal.templateparser.php"
#line 208 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1499 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1502 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1505 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1508 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1511 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1514 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1517 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1520 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1523 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1526 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1529 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1532 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1535 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '==';    }
#line 1538 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '!=';    }
#line 1541 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '>';    }
#line 1544 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '<';    }
#line 1547 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '>=';    }
#line 1550 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '<=';    }
#line 1553 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '===';    }
#line 1556 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '&&';    }
#line 1559 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '||';    }
#line 1562 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1565 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1568 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1571 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1574 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1577 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1580 "internal.templateparser.php"

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
#line 1697 "internal.templateparser.php"
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
#line 1722 "internal.templateparser.php"
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
