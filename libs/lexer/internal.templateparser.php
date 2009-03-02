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
        $this->cacher = $this->template->cacher_object; 
				$this->nocache = false;
				$this->prefix_code = array();
				$this->prefix_number = 0;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 142 "internal.templateparser.php"

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
    const TP_LDELSLASH                      =  2;
    const TP_RDEL                           =  3;
    const TP_COMMENTSTART                   =  4;
    const TP_COMMENTEND                     =  5;
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
    const TP_DOUBLECOLON                    = 18;
    const TP_SEMICOLON                      = 19;
    const TP_VERT                           = 20;
    const TP_EQUAL                          = 21;
    const TP_SPACE                          = 22;
    const TP_PTR                            = 23;
    const TP_APTR                           = 24;
    const TP_ID                             = 25;
    const TP_EQUALS                         = 26;
    const TP_NOTEQUALS                      = 27;
    const TP_GREATERTHAN                    = 28;
    const TP_LESSTHAN                       = 29;
    const TP_GREATEREQUAL                   = 30;
    const TP_LESSEQUAL                      = 31;
    const TP_IDENTITY                       = 32;
    const TP_NONEIDENTITY                   = 33;
    const TP_NOT                            = 34;
    const TP_LAND                           = 35;
    const TP_LOR                            = 36;
    const TP_QUOTE                          = 37;
    const TP_SINGLEQUOTE                    = 38;
    const TP_BOOLEAN                        = 39;
    const TP_IN                             = 40;
    const TP_ANDSYM                         = 41;
    const TP_BACKTICK                       = 42;
    const TP_HATCH                          = 43;
    const TP_AT                             = 44;
    const TP_LITERALSTART                   = 45;
    const TP_LITERALEND                     = 46;
    const TP_LDELIMTAG                      = 47;
    const TP_RDELIMTAG                      = 48;
    const TP_PHP                            = 49;
    const TP_PHPSTART                       = 50;
    const TP_PHPEND                         = 51;
    const TP_XML                            = 52;
    const TP_LDEL                           = 53;
    const YY_NO_ACTION = 337;
    const YY_ACCEPT_ACTION = 336;
    const YY_ERROR_ACTION = 335;

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
    const YY_SZ_ACTTAB = 562;
static public $yy_action = array(
 /*     0 */   153,  188,   26,  124,    2,  194,    6,  190,   49,  142,
 /*    10 */   143,  144,  151,  145,  146,  147,  136,    7,  124,  106,
 /*    20 */   336,   36,  134,  174,  118,   78,  149,  124,    5,    1,
 /*    30 */   108,   28,   43,  183,   38,   18,  129,  125,   13,   66,
 /*    40 */   105,  169,  190,  155,  206,    7,   60,  158,  197,  154,
 /*    50 */   153,   80,   26,   78,    2,  204,    6,   94,   51,   19,
 /*    60 */   131,   49,  178,  122,   34,   44,  110,  205,  184,  100,
 /*    70 */    18,  169,    7,  155,  206,  209,   60,  158,    5,  154,
 /*    80 */    78,   28,   43,  183,  198,  204,  127,  125,  153,   55,
 /*    90 */    26,  179,   16,  170,    6,   86,   49,  111,  190,   14,
 /*   100 */    21,   27,   75,  190,   97,  140,   47,  106,  181,  182,
 /*   110 */   180,   48,  195,  177,    8,  197,  176,  109,  212,   28,
 /*   120 */    43,  183,  109,  170,  213,  125,   18,  124,   49,  138,
 /*   130 */   141,   18,   71,  142,  143,  144,  151,  145,  146,  147,
 /*   140 */   136,  153,  195,   26,  153,   16,   26,    6,   16,   49,
 /*   150 */     6,  165,   46,  155,  119,  123,   55,  161,  155,  154,
 /*   160 */    98,   64,  158,   29,  154,  204,  204,   21,   89,   88,
 /*   170 */   204,    3,   28,   43,  183,   28,   43,  183,  125,   38,
 /*   180 */    11,  125,   14,   69,   61,   96,  190,   22,  155,  206,
 /*   190 */    24,   60,  158,  195,  154,    7,   87,   38,   94,  166,
 /*   200 */   204,  200,   73,   78,  169,  131,  155,  206,  113,   60,
 /*   210 */   158,  214,  154,  153,   18,   26,  155,   16,  204,   56,
 /*   220 */   158,   49,  154,  131,  138,  141,  186,  169,  204,   74,
 /*   230 */   118,  155,  103,   30,   65,  158,  169,  154,  204,  195,
 /*   240 */   172,  159,   37,  204,   28,   43,  183,   57,  196,  137,
 /*   250 */   125,  155,  206,  157,   60,  158,  170,  154,  153,   97,
 /*   260 */    26,  155,   16,  204,  139,  156,   49,  154,  131,  121,
 /*   270 */   155,   70,   67,  204,  164,   22,  154,  102,   24,  170,
 /*   280 */   155,  206,  204,   60,  158,  169,  154,  186,  170,   28,
 /*   290 */    43,  183,  204,  138,  141,  125,  138,  141,  212,  204,
 /*   300 */   107,  208,  153,   38,   26,  175,   16,  124,   77,  185,
 /*   310 */    49,  133,  155,  206,  192,   60,  158,    7,  154,    9,
 /*   320 */   124,   99,  160,  124,  204,   78,  173,  174,   20,  131,
 /*   330 */   118,  124,   70,   28,   43,  183,  168,  170,  124,  125,
 /*   340 */   118,  155,  206,   40,   60,  158,  153,  154,   90,  163,
 /*   350 */    16,   76,  124,  204,   49,  118,   17,  152,  210,  187,
 /*   360 */   155,  206,  207,   60,  158,  101,  154,  202,   81,  116,
 /*   370 */    10,  166,  204,  200,   58,  130,   76,   28,   43,  183,
 /*   380 */   115,   31,   23,  125,  189,  155,  206,  118,   60,  158,
 /*   390 */    76,  154,  199,   41,  128,  189,    6,  204,   49,  155,
 /*   400 */   206,   97,   60,  158,   42,  154,   76,  175,  126,  187,
 /*   410 */    14,  204,   63,  193,  190,  155,  206,  148,   60,  158,
 /*   420 */   187,  154,   72,   62,  203,   15,   54,  204,   45,   33,
 /*   430 */   150,  155,  206,  109,   60,  158,  191,  154,   84,   92,
 /*   440 */   205,  184,   18,  204,  200,  175,  211,  155,  206,   91,
 /*   450 */    60,  158,   95,  154,  200,   53,  175,  112,   20,  204,
 /*   460 */   162,  155,  206,  104,   60,  158,   68,  154,   39,  204,
 /*   470 */    59,  201,  120,  204,   27,  155,  206,  135,   60,  158,
 /*   480 */    85,  154,  114,  117,  187,  132,  187,  204,   35,  155,
 /*   490 */   206,   50,   60,  158,  167,  154,   82,  171,   49,    4,
 /*   500 */   118,  204,  189,   25,   23,  155,  206,   12,   60,  158,
 /*   510 */   157,  154,   93,  166,   32,  215,   52,  204,  215,  215,
 /*   520 */   215,  155,  206,  215,   60,  158,   79,  154,  215,  215,
 /*   530 */   215,  215,  215,  204,  215,  155,  206,  215,   60,  158,
 /*   540 */    83,  154,  215,  215,  215,  215,  215,  204,  215,  155,
 /*   550 */   206,  215,   60,  158,  215,  154,  215,  215,  215,  215,
 /*   560 */   215,  204,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,    3,    8,   20,   10,    3,   12,   25,   14,   26,
 /*    10 */    27,   28,   29,   30,   31,   32,   33,   10,   20,   25,
 /*    20 */    55,   56,   57,   58,   22,   18,    9,   20,   34,   22,
 /*    30 */    23,   37,   38,   39,   60,   53,   62,   43,   21,   65,
 /*    40 */    66,    1,   25,   69,   70,   10,   72,   73,    1,   75,
 /*    50 */     6,   80,    8,   18,   10,   81,   12,   23,   14,   24,
 /*    60 */    86,   14,    1,    2,   60,    4,   62,    7,    8,   25,
 /*    70 */    53,    1,   10,   69,   70,   13,   72,   73,   34,   75,
 /*    80 */    18,   37,   38,   39,   37,   81,   14,   43,    6,   42,
 /*    90 */     8,   51,   10,   53,   12,   64,   14,   25,   25,   21,
 /*   100 */    53,   41,   61,   25,   63,   11,   45,   25,   47,   48,
 /*   110 */    49,   50,   71,   52,   53,    1,   46,   44,   11,   37,
 /*   120 */    38,   39,   44,   53,   11,   43,   53,   20,   14,   35,
 /*   130 */    36,   53,   61,   26,   27,   28,   29,   30,   31,   32,
 /*   140 */    33,    6,   71,    8,    6,   10,    8,   12,   10,   14,
 /*   150 */    12,   37,   14,   69,   69,   70,   42,   73,   69,   75,
 /*   160 */    25,   72,   73,   25,   75,   81,   81,   53,   16,   25,
 /*   170 */    81,   19,   37,   38,   39,   37,   38,   39,   43,   60,
 /*   180 */    10,   43,   21,   61,   65,   63,   25,   12,   69,   70,
 /*   190 */    15,   72,   73,   71,   75,   10,   79,   60,   23,   82,
 /*   200 */    81,   84,   65,   18,    1,   86,   69,   70,   19,   72,
 /*   210 */    73,    3,   75,    6,   53,    8,   69,   10,   81,   72,
 /*   220 */    73,   14,   75,   86,   35,   36,   69,    1,   81,   61,
 /*   230 */    22,   69,   25,   76,   72,   73,    1,   75,   81,   71,
 /*   240 */     5,   38,   60,   81,   37,   38,   39,   65,   91,    3,
 /*   250 */    43,   69,   70,   85,   72,   73,   53,   75,    6,   63,
 /*   260 */     8,   69,   10,   81,    3,   73,   14,   75,   86,   23,
 /*   270 */    69,   60,   59,   81,   73,   12,   75,   25,   15,   53,
 /*   280 */    69,   70,   81,   72,   73,    1,   75,   69,   53,   37,
 /*   290 */    38,   39,   81,   35,   36,   43,   35,   36,   11,   81,
 /*   300 */    89,   90,    6,   60,    8,   92,   10,   20,   65,   91,
 /*   310 */    14,    3,   69,   70,    3,   72,   73,   10,   75,   16,
 /*   320 */    20,   25,   38,   20,   81,   18,   57,   58,   21,   86,
 /*   330 */    22,   20,   60,   37,   38,   39,    3,   53,   20,   43,
 /*   340 */    22,   69,   70,   67,   72,   73,    6,   75,   25,   84,
 /*   350 */    10,   60,   20,   81,   14,   22,   24,   77,   11,   83,
 /*   360 */    69,   70,   90,   72,   73,   25,   75,   13,   79,   78,
 /*   370 */    16,   82,   81,   84,   59,    3,   60,   37,   38,   39,
 /*   380 */    68,   80,   17,   43,   83,   69,   70,   22,   72,   73,
 /*   390 */    60,   75,   25,   67,   78,   83,   12,   81,   14,   69,
 /*   400 */    70,   63,   72,   73,   67,   75,   60,   92,   78,   83,
 /*   410 */    21,   81,   59,    3,   25,   69,   70,   62,   72,   73,
 /*   420 */    83,   75,   60,   59,   78,   87,   11,   81,   14,   40,
 /*   430 */     3,   69,   70,   44,   72,   73,   42,   75,   60,   79,
 /*   440 */     7,    8,   53,   81,   84,   92,   13,   69,   70,   79,
 /*   450 */    72,   73,   60,   75,   84,   25,   92,   69,   21,   81,
 /*   460 */    43,   69,   70,   25,   72,   73,   60,   75,   67,   81,
 /*   470 */    67,   25,   25,   81,   41,   69,   70,    3,   72,   73,
 /*   480 */    60,   75,   25,   25,   83,    3,   83,   81,   64,   69,
 /*   490 */    70,   14,   72,   73,   71,   75,   60,   92,   14,   88,
 /*   500 */    22,   81,   83,   74,   17,   69,   70,   10,   72,   73,
 /*   510 */    85,   75,   60,   82,   80,   93,   77,   81,   93,   93,
 /*   520 */    93,   69,   70,   93,   72,   73,   60,   75,   93,   93,
 /*   530 */    93,   93,   93,   81,   93,   69,   70,   93,   72,   73,
 /*   540 */    60,   75,   93,   93,   93,   93,   93,   81,   93,   69,
 /*   550 */    70,   93,   72,   73,   93,   75,   93,   93,   93,   93,
 /*   560 */    93,   81,
);
    const YY_SHIFT_USE_DFLT = -19;
    const YY_SHIFT_MAX = 128;
    static public $yy_shift_ofst = array(
 /*     0 */    61,   44,   -6,   -6,   -6,   -6,  135,   82,  138,   82,
 /*    10 */   135,   82,   82,   82,   82,   82,   82,   82,   82,   82,
 /*    20 */    82,   82,  296,  207,  252,  340,  340,  340,   47,    7,
 /*    30 */   114,  175,  175,  384,  318,  365,   61,  107,  -17,  389,
 /*    40 */    17,   78,   73,  284,  226,  -18,  -18,  226,  226,  -18,
 /*    50 */   -18,  -18,   34,  478,   34,  484,  433,   94,  203,  161,
 /*    60 */    60,  189,   70,  235,   60,   60,  261,   40,   -2,    2,
 /*    70 */   332,  208,  287,  258,  308,  333,  303,  258,   72,  311,
 /*    80 */   263,   34,  300,  300,  300,  300,  487,   34,  497,  477,
 /*    90 */   170,   34,   34,  300,  323,  300,  -19,  -19,   35,   62,
 /*   100 */   307,  185,  185,  185,  246,  152,  185,  354,  430,  367,
 /*   110 */   410,  170,  394,  414,  437,  427,  415,  417,  457,  482,
 /*   120 */   474,  447,  438,  372,  446,  458,  347,  144,  113,
);
    const YY_REDUCE_USE_DFLT = -36;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */   -35,  -26,  182,  119,  243,  137,  211,  316,    4,  346,
 /*    10 */   272,  330,  291,  378,  480,  420,  362,  436,  406,  392,
 /*    20 */   452,  466,  147,   89,  162,  201,  192,   84,  157,  122,
 /*    30 */   218,  117,  289,   85,   41,  168,  269,  338,  338,  301,
 /*    40 */   312,  301,  301,  315,  353,  276,  326,  364,  213,  337,
 /*    50 */   403,  401,  360,   71,  370,  388,  429,  411,  405,  419,
 /*    60 */   429,  411,  405,  405,  429,  429,  411,  405,  196,  423,
 /*    70 */   196,  423,  196,  411,  423,  423,  196,  411,  439,  196,
 /*    80 */   431,  265,  196,  196,  196,  196,  425,  265,  434,  355,
 /*    90 */   -29,  265,  265,  196,  280,  196,  424,   31,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 1 */ array(6, 8, 10, 12, 14, 25, 34, 37, 38, 39, 43, ),
        /* 2 */ array(6, 8, 10, 12, 14, 25, 34, 37, 38, 39, 43, ),
        /* 3 */ array(6, 8, 10, 12, 14, 25, 34, 37, 38, 39, 43, ),
        /* 4 */ array(6, 8, 10, 12, 14, 25, 34, 37, 38, 39, 43, ),
        /* 5 */ array(6, 8, 10, 12, 14, 25, 34, 37, 38, 39, 43, ),
        /* 6 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 7 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 8 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 9 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 10 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 11 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 12 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 13 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 14 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 15 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 16 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 17 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 18 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 19 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 20 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 21 */ array(6, 8, 10, 12, 14, 25, 37, 38, 39, 43, ),
        /* 22 */ array(6, 8, 10, 14, 25, 37, 38, 39, 43, ),
        /* 23 */ array(6, 8, 10, 14, 25, 37, 38, 39, 43, ),
        /* 24 */ array(6, 8, 10, 14, 25, 37, 38, 39, 43, ),
        /* 25 */ array(6, 10, 14, 25, 37, 38, 39, 43, ),
        /* 26 */ array(6, 10, 14, 25, 37, 38, 39, 43, ),
        /* 27 */ array(6, 10, 14, 25, 37, 38, 39, 43, ),
        /* 28 */ array(1, 14, 37, 42, 53, ),
        /* 29 */ array(10, 18, 20, 22, 23, ),
        /* 30 */ array(1, 14, 37, 42, 53, ),
        /* 31 */ array(12, 15, 23, ),
        /* 32 */ array(12, 15, 23, ),
        /* 33 */ array(12, 14, ),
        /* 34 */ array(20, 22, ),
        /* 35 */ array(17, 22, ),
        /* 36 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 37 */ array(11, 20, 26, 27, 28, 29, 30, 31, 32, 33, ),
        /* 38 */ array(20, 26, 27, 28, 29, 30, 31, 32, 33, ),
        /* 39 */ array(21, 25, 40, 44, 53, ),
        /* 40 */ array(9, 21, 25, 53, ),
        /* 41 */ array(21, 25, 44, 53, ),
        /* 42 */ array(25, 44, 53, ),
        /* 43 */ array(1, 38, 53, ),
        /* 44 */ array(1, 53, ),
        /* 45 */ array(25, 53, ),
        /* 46 */ array(25, 53, ),
        /* 47 */ array(1, 53, ),
        /* 48 */ array(1, 53, ),
        /* 49 */ array(25, 53, ),
        /* 50 */ array(25, 53, ),
        /* 51 */ array(25, 53, ),
        /* 52 */ array(23, ),
        /* 53 */ array(22, ),
        /* 54 */ array(23, ),
        /* 55 */ array(14, ),
        /* 56 */ array(7, 8, 13, 41, ),
        /* 57 */ array(11, 35, 36, ),
        /* 58 */ array(1, 38, 53, ),
        /* 59 */ array(21, 25, 53, ),
        /* 60 */ array(7, 8, 41, ),
        /* 61 */ array(19, 35, 36, ),
        /* 62 */ array(1, 46, 53, ),
        /* 63 */ array(1, 5, 53, ),
        /* 64 */ array(7, 8, 41, ),
        /* 65 */ array(7, 8, 41, ),
        /* 66 */ array(3, 35, 36, ),
        /* 67 */ array(1, 51, 53, ),
        /* 68 */ array(3, 20, ),
        /* 69 */ array(3, 22, ),
        /* 70 */ array(20, 24, ),
        /* 71 */ array(3, 22, ),
        /* 72 */ array(11, 20, ),
        /* 73 */ array(35, 36, ),
        /* 74 */ array(3, 22, ),
        /* 75 */ array(3, 22, ),
        /* 76 */ array(16, 20, ),
        /* 77 */ array(35, 36, ),
        /* 78 */ array(14, 25, ),
        /* 79 */ array(3, 20, ),
        /* 80 */ array(12, 15, ),
        /* 81 */ array(23, ),
        /* 82 */ array(20, ),
        /* 83 */ array(20, ),
        /* 84 */ array(20, ),
        /* 85 */ array(20, ),
        /* 86 */ array(17, ),
        /* 87 */ array(23, ),
        /* 88 */ array(10, ),
        /* 89 */ array(14, ),
        /* 90 */ array(10, ),
        /* 91 */ array(23, ),
        /* 92 */ array(23, ),
        /* 93 */ array(20, ),
        /* 94 */ array(25, ),
        /* 95 */ array(20, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(10, 18, 24, ),
        /* 99 */ array(10, 13, 18, ),
        /* 100 */ array(10, 18, 21, ),
        /* 101 */ array(10, 18, ),
        /* 102 */ array(10, 18, ),
        /* 103 */ array(10, 18, ),
        /* 104 */ array(3, 23, ),
        /* 105 */ array(16, 19, ),
        /* 106 */ array(10, 18, ),
        /* 107 */ array(13, 16, ),
        /* 108 */ array(25, ),
        /* 109 */ array(25, ),
        /* 110 */ array(3, ),
        /* 111 */ array(10, ),
        /* 112 */ array(42, ),
        /* 113 */ array(14, ),
        /* 114 */ array(21, ),
        /* 115 */ array(3, ),
        /* 116 */ array(11, ),
        /* 117 */ array(43, ),
        /* 118 */ array(25, ),
        /* 119 */ array(3, ),
        /* 120 */ array(3, ),
        /* 121 */ array(25, ),
        /* 122 */ array(25, ),
        /* 123 */ array(3, ),
        /* 124 */ array(25, ),
        /* 125 */ array(25, ),
        /* 126 */ array(11, ),
        /* 127 */ array(25, ),
        /* 128 */ array(11, ),
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
        /* 174 */ array(),
        /* 175 */ array(),
        /* 176 */ array(),
        /* 177 */ array(),
        /* 178 */ array(),
        /* 179 */ array(),
        /* 180 */ array(),
        /* 181 */ array(),
        /* 182 */ array(),
        /* 183 */ array(),
        /* 184 */ array(),
        /* 185 */ array(),
        /* 186 */ array(),
        /* 187 */ array(),
        /* 188 */ array(),
        /* 189 */ array(),
        /* 190 */ array(),
        /* 191 */ array(),
        /* 192 */ array(),
        /* 193 */ array(),
        /* 194 */ array(),
        /* 195 */ array(),
        /* 196 */ array(),
        /* 197 */ array(),
        /* 198 */ array(),
        /* 199 */ array(),
        /* 200 */ array(),
        /* 201 */ array(),
        /* 202 */ array(),
        /* 203 */ array(),
        /* 204 */ array(),
        /* 205 */ array(),
        /* 206 */ array(),
        /* 207 */ array(),
        /* 208 */ array(),
        /* 209 */ array(),
        /* 210 */ array(),
        /* 211 */ array(),
        /* 212 */ array(),
        /* 213 */ array(),
        /* 214 */ array(),
);
    static public $yy_default = array(
 /*     0 */   335,  335,  335,  335,  335,  335,  321,  296,  335,  296,
 /*    10 */   335,  296,  296,  335,  335,  335,  335,  335,  335,  335,
 /*    20 */   335,  335,  335,  335,  335,  335,  335,  335,  335,  242,
 /*    30 */   335,  274,  269,  335,  242,  242,  215,  305,  305,  278,
 /*    40 */   335,  278,  278,  335,  335,  335,  335,  335,  335,  335,
 /*    50 */   335,  335,  264,  242,  265,  335,  335,  335,  335,  335,
 /*    60 */   248,  335,  335,  335,  301,  280,  335,  335,  335,  335,
 /*    70 */   322,  335,  335,  303,  335,  335,  295,  307,  335,  335,
 /*    80 */   290,  270,  323,  246,  238,  306,  249,  287,  278,  335,
 /*    90 */   278,  267,  266,  243,  335,  324,  299,  299,  247,  335,
 /*   100 */   247,  335,  279,  300,  335,  335,  247,  335,  335,  335,
 /*   110 */   335,  268,  335,  335,  335,  335,  335,  335,  335,  335,
 /*   120 */   335,  335,  335,  335,  335,  335,  335,  335,  335,  244,
 /*   130 */   237,  302,  236,  231,  216,  233,  315,  232,  316,  234,
 /*   140 */   304,  317,  308,  309,  310,  312,  313,  314,  245,  239,
 /*   150 */   235,  311,  291,  258,  259,  257,  252,  298,  251,  260,
 /*   160 */   261,  254,  271,  289,  253,  262,  277,  240,  227,  333,
 /*   170 */   334,  331,  219,  217,  218,  332,  220,  225,  226,  224,
 /*   180 */   223,  221,  222,  272,  255,  325,  327,  283,  286,  284,
 /*   190 */   285,  328,  329,  228,  229,  241,  326,  330,  263,  275,
 /*   200 */   288,  297,  318,  294,  276,  256,  250,  320,  319,  281,
 /*   210 */   293,  282,  273,  292,  230,
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
    const YYNOCODE = 94;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 215;
    const YYNRULE = 120;
    const YYERRORSYMBOL = 54;
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
    1,  /*  LDELSLASH => OTHER */
    1,  /*       RDEL => OTHER */
    1,  /* COMMENTSTART => OTHER */
    1,  /* COMMENTEND => OTHER */
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
    1,  /* DOUBLECOLON => OTHER */
    1,  /*  SEMICOLON => OTHER */
    1,  /*       VERT => OTHER */
    1,  /*      EQUAL => OTHER */
    1,  /*      SPACE => OTHER */
    1,  /*        PTR => OTHER */
    1,  /*       APTR => OTHER */
    1,  /*         ID => OTHER */
    1,  /*     EQUALS => OTHER */
    1,  /*  NOTEQUALS => OTHER */
    1,  /* GREATERTHAN => OTHER */
    1,  /*   LESSTHAN => OTHER */
    1,  /* GREATEREQUAL => OTHER */
    1,  /*  LESSEQUAL => OTHER */
    1,  /*   IDENTITY => OTHER */
    1,  /* NONEIDENTITY => OTHER */
    1,  /*        NOT => OTHER */
    1,  /*       LAND => OTHER */
    1,  /*        LOR => OTHER */
    1,  /*      QUOTE => OTHER */
    1,  /* SINGLEQUOTE => OTHER */
    1,  /*    BOOLEAN => OTHER */
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*   BACKTICK => OTHER */
    1,  /*      HATCH => OTHER */
    1,  /*         AT => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*        PHP => nothing */
    0,  /*   PHPSTART => nothing */
    0,  /*     PHPEND => nothing */
    0,  /*        XML => nothing */
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
  '$',             'OTHER',         'LDELSLASH',     'RDEL',        
  'COMMENTSTART',  'COMMENTEND',    'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'DOUBLECOLON',   'SEMICOLON',   
  'VERT',          'EQUAL',         'SPACE',         'PTR',         
  'APTR',          'ID',            'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NONEIDENTITY',  'NOT',           'LAND',        
  'LOR',           'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',     
  'IN',            'ANDSYM',        'BACKTICK',      'HATCH',       
  'AT',            'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHP',           'PHPSTART',      'PHPEND',      
  'XML',           'LDEL',          'error',         'start',       
  'template',      'template_element',  'smartytag',     'text',        
  'expr',          'attributes',    'statement',     'modifier',    
  'modparameters',  'ifexprs',       'statements',    'varvar',      
  'foraction',     'variable',      'array',         'attribute',   
  'exprs',         'value',         'math',          'function',    
  'doublequoted',  'method',        'params',        'objectchain', 
  'vararraydefs',  'object',        'vararraydef',   'varvarele',   
  'objectelement',  'modparameter',  'ifexpr',        'ifcond',      
  'lop',           'arrayelements',  'arrayelement',  'doublequotedcontent',
  'textelement', 
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
 /*   5 */ "template_element ::= LITERALSTART text LITERALEND",
 /*   6 */ "template_element ::= LDELIMTAG",
 /*   7 */ "template_element ::= RDELIMTAG",
 /*   8 */ "template_element ::= PHP",
 /*   9 */ "template_element ::= PHPSTART text PHPEND",
 /*  10 */ "template_element ::= XML",
 /*  11 */ "template_element ::= OTHER",
 /*  12 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  13 */ "smartytag ::= LDEL statement RDEL",
 /*  14 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  15 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  16 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  17 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  18 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  19 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  20 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  21 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  22 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  23 */ "foraction ::= EQUAL expr",
 /*  24 */ "foraction ::= INCDEC",
 /*  25 */ "attributes ::= attributes attribute",
 /*  26 */ "attributes ::= attribute",
 /*  27 */ "attributes ::=",
 /*  28 */ "attribute ::= SPACE ID EQUAL expr",
 /*  29 */ "statements ::= statement",
 /*  30 */ "statements ::= statements COMMA statement",
 /*  31 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  32 */ "expr ::= ID",
 /*  33 */ "expr ::= exprs",
 /*  34 */ "expr ::= expr modifier modparameters",
 /*  35 */ "expr ::= array",
 /*  36 */ "exprs ::= value",
 /*  37 */ "exprs ::= UNIMATH value",
 /*  38 */ "exprs ::= exprs math value",
 /*  39 */ "exprs ::= exprs ANDSYM value",
 /*  40 */ "math ::= UNIMATH",
 /*  41 */ "math ::= MATH",
 /*  42 */ "value ::= variable",
 /*  43 */ "value ::= NUMBER",
 /*  44 */ "value ::= function",
 /*  45 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  46 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  47 */ "value ::= QUOTE doublequoted QUOTE",
 /*  48 */ "value ::= QUOTE QUOTE",
 /*  49 */ "value ::= ID DOUBLECOLON method",
 /*  50 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP",
 /*  51 */ "value ::= ID DOUBLECOLON method objectchain",
 /*  52 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  53 */ "value ::= ID DOUBLECOLON ID",
 /*  54 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs",
 /*  55 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs objectchain",
 /*  56 */ "value ::= HATCH ID HATCH",
 /*  57 */ "value ::= BOOLEAN",
 /*  58 */ "value ::= OPENP expr CLOSEP",
 /*  59 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  60 */ "variable ::= DOLLAR varvar AT ID",
 /*  61 */ "variable ::= object",
 /*  62 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  63 */ "vararraydefs ::=",
 /*  64 */ "vararraydef ::= DOT ID",
 /*  65 */ "vararraydef ::= DOT exprs",
 /*  66 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  67 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  68 */ "varvar ::= varvarele",
 /*  69 */ "varvar ::= varvar varvarele",
 /*  70 */ "varvarele ::= ID",
 /*  71 */ "varvarele ::= LDEL expr RDEL",
 /*  72 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  73 */ "objectchain ::= objectelement",
 /*  74 */ "objectchain ::= objectchain objectelement",
 /*  75 */ "objectelement ::= PTR ID vararraydefs",
 /*  76 */ "objectelement ::= PTR method",
 /*  77 */ "function ::= ID OPENP params CLOSEP",
 /*  78 */ "method ::= ID OPENP params CLOSEP",
 /*  79 */ "params ::= expr COMMA params",
 /*  80 */ "params ::= expr",
 /*  81 */ "params ::=",
 /*  82 */ "modifier ::= VERT ID",
 /*  83 */ "modparameters ::= modparameters modparameter",
 /*  84 */ "modparameters ::=",
 /*  85 */ "modparameter ::= COLON ID",
 /*  86 */ "modparameter ::= COLON exprs",
 /*  87 */ "ifexprs ::= ifexpr",
 /*  88 */ "ifexprs ::= NOT ifexprs",
 /*  89 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  90 */ "ifexpr ::= expr",
 /*  91 */ "ifexpr ::= expr ifcond expr",
 /*  92 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  93 */ "ifcond ::= EQUALS",
 /*  94 */ "ifcond ::= NOTEQUALS",
 /*  95 */ "ifcond ::= GREATERTHAN",
 /*  96 */ "ifcond ::= LESSTHAN",
 /*  97 */ "ifcond ::= GREATEREQUAL",
 /*  98 */ "ifcond ::= LESSEQUAL",
 /*  99 */ "ifcond ::= IDENTITY",
 /* 100 */ "ifcond ::= NONEIDENTITY",
 /* 101 */ "lop ::= LAND",
 /* 102 */ "lop ::= LOR",
 /* 103 */ "array ::= OPENB arrayelements CLOSEB",
 /* 104 */ "arrayelements ::= arrayelement",
 /* 105 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 106 */ "arrayelements ::=",
 /* 107 */ "arrayelement ::= expr",
 /* 108 */ "arrayelement ::= expr APTR expr",
 /* 109 */ "arrayelement ::= ID APTR expr",
 /* 110 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 111 */ "doublequoted ::= doublequotedcontent",
 /* 112 */ "doublequotedcontent ::= variable",
 /* 113 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 114 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 115 */ "doublequotedcontent ::= OTHER",
 /* 116 */ "text ::= text textelement",
 /* 117 */ "text ::= textelement",
 /* 118 */ "textelement ::= OTHER",
 /* 119 */ "textelement ::= LDEL",
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
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 6 ),
  array( 'lhs' => 58, 'rhs' => 6 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 11 ),
  array( 'lhs' => 58, 'rhs' => 8 ),
  array( 'lhs' => 58, 'rhs' => 8 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 0 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 7 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 8 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 5 ),
  array( 'lhs' => 73, 'rhs' => 6 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 0 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 0 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 0 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        32 => 0,
        36 => 0,
        42 => 0,
        43 => 0,
        44 => 0,
        57 => 0,
        61 => 0,
        104 => 0,
        1 => 1,
        33 => 1,
        35 => 1,
        40 => 1,
        41 => 1,
        68 => 1,
        87 => 1,
        111 => 1,
        117 => 1,
        118 => 1,
        119 => 1,
        2 => 2,
        62 => 2,
        110 => 2,
        116 => 2,
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
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 21,
        23 => 23,
        24 => 24,
        26 => 24,
        80 => 24,
        107 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        34 => 34,
        37 => 37,
        38 => 38,
        39 => 39,
        45 => 45,
        47 => 45,
        46 => 46,
        48 => 46,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        58 => 58,
        59 => 59,
        60 => 60,
        63 => 63,
        84 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        69 => 69,
        70 => 70,
        71 => 71,
        89 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        81 => 81,
        82 => 82,
        83 => 83,
        85 => 85,
        86 => 86,
        88 => 88,
        90 => 90,
        91 => 91,
        92 => 91,
        93 => 93,
        94 => 94,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        99 => 99,
        100 => 100,
        101 => 101,
        102 => 102,
        103 => 103,
        105 => 105,
        106 => 106,
        108 => 108,
        109 => 109,
        112 => 112,
        113 => 113,
        114 => 114,
        115 => 115,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1581 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1584 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1587 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1593 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1596 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1599 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1602 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1605 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1611 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1617 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1620 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1623 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1626 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1629 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1632 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1635 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -3]->minor,'modifier')) {
                                                                      $this->_retvalue .= "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -3]->minor . "(array(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor ."),'modifier');?>";
                                                                 } else {
                                                                   if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					                              $this->_retvalue .= $this->yystack[$this->yyidx + -3]->minor . "(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor .");?>";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1650 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1653 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1656 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1659 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1662 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1665 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1668 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1671 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1674 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1677 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1680 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1683 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1686 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1689 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r34(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
                                                                      $this->_retvalue = "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -1]->minor . "(array(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor ."),'modifier')";
                                                                 } else {
                                                                   if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                               $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -1]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1703 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1706 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1709 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1712 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1715 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "''";     }
#line 1718 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1721 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r50(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1724 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1727 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1730 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1733 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1736 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1739 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1742 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1745 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r59(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1749 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1752 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r63(){return;    }
#line 1755 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1758 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1761 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1764 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1767 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1770 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1773 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1776 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1779 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1782 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1785 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1788 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1791 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r77(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1800 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1803 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1806 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r81(){ return;    }
#line 1809 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1812 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1815 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1818 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1821 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1824 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1827 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1830 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '==';    }
#line 1833 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '!=';    }
#line 1836 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '>';    }
#line 1839 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '<';    }
#line 1842 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>=';    }
#line 1845 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<=';    }
#line 1848 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '===';    }
#line 1851 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '!==';    }
#line 1854 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '&&';    }
#line 1857 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '||';    }
#line 1860 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r103(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1863 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1866 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r106(){ return;     }
#line 1869 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r108(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1872 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r109(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1875 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1878 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1881 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1884 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1887 "internal.templateparser.php"

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
#line 55 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 2004 "internal.templateparser.php"
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
#line 47 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 2029 "internal.templateparser.php"
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
