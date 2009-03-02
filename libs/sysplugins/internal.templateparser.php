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
    const TP_SEMICOLON                      = 18;
    const TP_VERT                           = 19;
    const TP_EQUAL                          = 20;
    const TP_SPACE                          = 21;
    const TP_PTR                            = 22;
    const TP_APTR                           = 23;
    const TP_ID                             = 24;
    const TP_EQUALS                         = 25;
    const TP_NOTEQUALS                      = 26;
    const TP_GREATERTHAN                    = 27;
    const TP_LESSTHAN                       = 28;
    const TP_GREATEREQUAL                   = 29;
    const TP_LESSEQUAL                      = 30;
    const TP_IDENTITY                       = 31;
    const TP_NONEIDENTITY                   = 32;
    const TP_NOT                            = 33;
    const TP_LAND                           = 34;
    const TP_LOR                            = 35;
    const TP_QUOTE                          = 36;
    const TP_SINGLEQUOTE                    = 37;
    const TP_BOOLEAN                        = 38;
    const TP_IN                             = 39;
    const TP_ANDSYM                         = 40;
    const TP_BACKTICK                       = 41;
    const TP_HATCH                          = 42;
    const TP_AT                             = 43;
    const TP_LITERALSTART                   = 44;
    const TP_LITERALEND                     = 45;
    const TP_LDELIMTAG                      = 46;
    const TP_RDELIMTAG                      = 47;
    const TP_PHP                            = 48;
    const TP_PHPSTART                       = 49;
    const TP_PHPEND                         = 50;
    const TP_XML                            = 51;
    const TP_LDEL                           = 52;
    const YY_NO_ACTION = 338;
    const YY_ACCEPT_ACTION = 337;
    const YY_ERROR_ACTION = 336;

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
    const YY_SZ_ACTTAB = 538;
static public $yy_action = array(
 /*     0 */   154,  140,   27,  115,    4,   73,    6,   38,   45,  147,
 /*    10 */   198,   22,   59,  105,   23,  196,  156,  207,  103,   61,
 /*    20 */   159,   83,  155,   45,  143,  142,  213,    5,  205,  158,
 /*    30 */    28,   43,  184,  141,  115,  154,  111,   27,  154,    4,
 /*    40 */    27,    6,   17,   48,    6,  199,   45,    8,    8,  127,
 /*    50 */    52,  187,  136,   98,  119,  119,  103,  115,   30,    1,
 /*    60 */   129,   14,    5,  205,  190,   28,   43,  184,   28,   43,
 /*    70 */   184,  111,   83,  197,  111,  156,  156,  213,   62,  159,
 /*    80 */   157,  155,  155,  143,  142,  115,  189,  205,  205,  170,
 /*    90 */   170,  146,  148,  149,  151,  150,  145,  137,  139,  154,
 /*   100 */   115,   27,  115,   17,   18,    6,   36,   45,  164,  179,
 /*   110 */   120,   58,   47,   77,   96,  156,  207,   99,   61,  159,
 /*   120 */   170,  155,  156,  207,  173,   61,  159,  205,  155,   28,
 /*   130 */    43,  184,  141,  177,  205,  111,  115,  193,  198,  180,
 /*   140 */   171,  171,  146,  148,  149,  151,  150,  145,  137,  139,
 /*   150 */   187,   45,   49,  115,  182,  183,  181,   44,   39,  178,
 /*   160 */     7,  154,  205,   27,  156,   17,  152,    6,  165,   51,
 /*   170 */   155,  171,  186,  166,  188,   38,  205,   19,   52,   29,
 /*   180 */    70,  191,   15,   93,  156,  207,  191,   61,  159,   14,
 /*   190 */   155,   28,   43,  184,   33,   38,  205,  111,  206,  185,
 /*   200 */    57,  141,  143,  142,  156,  207,  215,   61,  159,   20,
 /*   210 */   155,  154,  158,   27,   20,   17,  205,  170,  154,   45,
 /*   220 */    27,  141,   17,   24,  117,   38,   45,  206,  185,  104,
 /*   230 */    69,   25,    9,  212,  156,  207,  101,   61,  159,  170,
 /*   240 */   155,   28,   43,  184,  122,  125,  205,  111,   28,   43,
 /*   250 */   184,  141,  107,  160,  111,  154,  205,   27,  156,   17,
 /*   260 */    25,   56,  159,   45,  155,  191,   53,   68,  171,  203,
 /*   270 */   205,    8,   12,  100,  210,  161,  156,  207,  119,   61,
 /*   280 */   159,   80,  155,  116,  114,   28,   43,  184,  205,   10,
 /*   290 */   171,  111,  115,   20,  154,   76,  102,  209,   17,  143,
 /*   300 */   142,   82,   45,    2,  156,  207,   13,   61,  159,  169,
 /*   310 */   155,  156,  106,  121,   60,  159,  205,  155,   15,   76,
 /*   320 */   195,  134,  191,  205,   28,   43,  184,  117,  156,  207,
 /*   330 */   111,   61,  159,   76,  155,  174,  175,  128,  117,  117,
 /*   340 */   205,  114,  156,  207,   90,   61,  159,  167,  155,  201,
 /*   350 */    20,  204,  214,    8,  205,   34,  170,  113,   81,   74,
 /*   360 */   119,   97,   68,  130,  156,  207,   16,   61,  159,  196,
 /*   370 */   155,  156,  207,  131,   61,  159,  205,  155,  337,   37,
 /*   380 */   133,  175,  110,  205,   76,  126,   63,   22,  192,   96,
 /*   390 */    23,   71,  208,  156,  207,  109,   61,  159,  211,  155,
 /*   400 */   156,  207,  123,   61,  159,  205,  155,  171,   89,  138,
 /*   410 */    42,   85,  205,   21,  167,   95,  201,  156,  207,  176,
 /*   420 */    61,  159,   75,  155,  156,  207,  188,   61,  159,  205,
 /*   430 */   155,  156,  207,  163,   61,  159,  205,  155,    8,   78,
 /*   440 */    64,   96,  191,  205,   92,  119,  156,   54,   13,  196,
 /*   450 */   162,   84,  155,  156,  207,  118,   61,  159,  205,  155,
 /*   460 */   156,  207,   65,   61,  159,  205,  155,    6,   86,   45,
 /*   470 */    20,   66,  205,  176,  108,   94,   31,  156,  207,  190,
 /*   480 */    61,  159,   15,  155,  156,  207,  191,   61,  159,  205,
 /*   490 */   155,   41,  115,   67,  117,  176,  205,   91,  124,   40,
 /*   500 */   112,   35,  201,   72,  176,  114,  144,  188,   24,  188,
 /*   510 */   205,  135,  117,  196,   20,  188,   88,  194,   46,  202,
 /*   520 */   200,  201,    3,   50,  190,  172,  168,   26,  117,   45,
 /*   530 */    87,   79,  132,  153,  167,   55,   11,   32,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   11,    8,   19,   10,   60,   12,   59,   14,   61,
 /*    10 */     1,   12,   64,   65,   15,   70,   68,   69,   24,   71,
 /*    20 */    72,   22,   74,   14,   34,   35,   11,   33,   80,   84,
 /*    30 */    36,   37,   38,   85,   19,    6,   42,    8,    6,   10,
 /*    40 */     8,   12,   10,   14,   12,   36,   14,   10,   10,   67,
 /*    50 */    41,   68,    3,   24,   17,   17,   24,   19,   75,   21,
 /*    60 */    22,   52,   33,   80,   82,   36,   37,   38,   36,   37,
 /*    70 */    38,   42,   22,   90,   42,   68,   68,   11,   71,   72,
 /*    80 */    72,   74,   74,   34,   35,   19,    3,   80,   80,    1,
 /*    90 */     1,   25,   26,   27,   28,   29,   30,   31,   32,    6,
 /*   100 */    19,    8,   19,   10,   23,   12,   59,   14,   83,    1,
 /*   110 */     2,   64,    4,   59,   62,   68,   69,   24,   71,   72,
 /*   120 */     1,   74,   68,   69,    5,   71,   72,   80,   74,   36,
 /*   130 */    37,   38,   85,   45,   80,   42,   19,    3,    1,   50,
 /*   140 */    52,   52,   25,   26,   27,   28,   29,   30,   31,   32,
 /*   150 */    68,   14,   44,   19,   46,   47,   48,   49,   66,   51,
 /*   160 */    52,    6,   80,    8,   68,   10,    9,   12,   72,   14,
 /*   170 */    74,   52,   90,   36,   82,   59,   80,   20,   41,   24,
 /*   180 */    64,   24,   20,   63,   68,   69,   24,   71,   72,   52,
 /*   190 */    74,   36,   37,   38,   63,   59,   80,   42,    7,    8,
 /*   200 */    64,   85,   34,   35,   68,   69,    3,   71,   72,   52,
 /*   210 */    74,    6,   84,    8,   52,   10,   80,    1,    6,   14,
 /*   220 */     8,   85,   10,   17,   21,   59,   14,    7,    8,   24,
 /*   230 */    64,   40,   10,   13,   68,   69,   24,   71,   72,    1,
 /*   240 */    74,   36,   37,   38,   68,   69,   80,   42,   36,   37,
 /*   250 */    38,   85,   24,   37,   42,    6,   80,    8,   68,   10,
 /*   260 */    40,   71,   72,   14,   74,   24,   11,   59,   52,   13,
 /*   270 */    80,   10,   16,   24,   13,   37,   68,   69,   17,   71,
 /*   280 */    72,   17,   74,   18,   43,   36,   37,   38,   80,   16,
 /*   290 */    52,   42,   19,   52,    6,   59,   88,   89,   10,   34,
 /*   300 */    35,   16,   14,   18,   68,   69,   20,   71,   72,    3,
 /*   310 */    74,   68,   24,   77,   71,   72,   80,   74,   20,   59,
 /*   320 */     3,    3,   24,   80,   36,   37,   38,   21,   68,   69,
 /*   330 */    42,   71,   72,   59,   74,   56,   57,   77,   21,   21,
 /*   340 */    80,   43,   68,   69,   78,   71,   72,   81,   74,   83,
 /*   350 */    52,   77,   11,   10,   80,   59,    1,   61,   24,   60,
 /*   360 */    17,   62,   59,    3,   68,   69,   23,   71,   72,   70,
 /*   370 */    74,   68,   69,    3,   71,   72,   80,   74,   54,   55,
 /*   380 */    56,   57,   22,   80,   59,   14,   58,   12,   41,   62,
 /*   390 */    15,   59,   89,   68,   69,   24,   71,   72,   11,   74,
 /*   400 */    68,   69,   77,   71,   72,   80,   74,   52,   59,    3,
 /*   410 */    66,   78,   80,   86,   81,   59,   83,   68,   69,   91,
 /*   420 */    71,   72,   59,   74,   68,   69,   82,   71,   72,   80,
 /*   430 */    74,   68,   69,   42,   71,   72,   80,   74,   10,   60,
 /*   440 */    58,   62,   24,   80,   59,   17,   68,   24,   20,   70,
 /*   450 */    72,   59,   74,   68,   69,   24,   71,   72,   80,   74,
 /*   460 */    68,   69,   58,   71,   72,   80,   74,   12,   59,   14,
 /*   470 */    52,   58,   80,   91,   24,   59,   79,   68,   69,   82,
 /*   480 */    71,   72,   20,   74,   68,   69,   24,   71,   72,   80,
 /*   490 */    74,   66,   19,   66,   21,   91,   80,   78,   68,   66,
 /*   500 */    24,   39,   83,   60,   91,   43,    3,   82,   17,   82,
 /*   510 */    80,    3,   21,   70,   52,   82,   78,    3,   14,   24,
 /*   520 */    24,   83,   87,   14,   82,   91,   70,   73,   21,   14,
 /*   530 */    24,   79,   61,   76,   81,   76,   10,   79,
);
    const YY_SHIFT_USE_DFLT = -17;
    const YY_SHIFT_MAX = 129;
    static public $yy_shift_ofst = array(
 /*     0 */   108,   29,   -6,   -6,   -6,   -6,   93,  155,   32,   32,
 /*    10 */    32,   32,   93,   32,   32,   32,   32,   32,   32,   32,
 /*    20 */    32,   32,  249,  205,  212,  288,  288,  288,    9,   38,
 /*    30 */   137,   -1,   -1,  491,  473,  455,   66,  108,  117,  462,
 /*    40 */   298,  157,  241,  238,  355,  418,  418,  355,  418,  355,
 /*    50 */   418,  418,  515,   50,  507,   50,  220,  265,  -10,   49,
 /*    60 */   191,  191,  191,   89,   88,  119,  216,  162,   81,  168,
 /*    70 */   168,   83,  203,  318,  317,  134,  273,   15,  306,  375,
 /*    80 */   371,  526,  509,  506,  -16,   50,  -16,  222,   50,  -16,
 /*    90 */    50,   50,  -16,  206,  -16,  -16,  -17,  -17,  428,  343,
 /*   100 */   261,   37,  256,   37,   37,  285,   37,  360,  391,  222,
 /*   110 */   476,  450,  508,  514,  496,  495,  504,  431,  286,  264,
 /*   120 */   228,  341,  406,  387,  347,  503,  334,  370,  255,  423,
);
    const YY_REDUCE_USE_DFLT = -56;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */   324,  -52,  136,  166,   47,  116,  208,  296,  236,  325,
 /*    10 */   274,  260,  303,  392,  363,  356,  416,   54,  349,  385,
 /*    20 */   332,  409,  190,    7,  243,  378,   96,    8,  -17,  299,
 /*    30 */    82,  266,  333,  -55,  379,  176,  327,  279,  327,  397,
 /*    40 */   397,  -18,  397,  413,  328,  344,  425,  404,   92,  382,
 /*    50 */   427,  433,  430,  419,  443,  438,  454,  435,  435,  435,
 /*    60 */   454,  454,  454,  434,  434,  434,  434,  442,   52,  435,
 /*    70 */   435,   52,  456,  456,  456,   52,   52,   52,  456,  453,
 /*    80 */   459,  458,  471,  457,   52,   25,   52,  452,   25,   52,
 /*    90 */    25,   25,   52,  128,   52,   52,  120,  131,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 21 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 22 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 23 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 24 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 26 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 27 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 28 */ array(1, 14, 36, 41, 52, ),
        /* 29 */ array(10, 17, 19, 21, 22, ),
        /* 30 */ array(1, 14, 36, 41, 52, ),
        /* 31 */ array(12, 15, 22, ),
        /* 32 */ array(12, 15, 22, ),
        /* 33 */ array(17, 21, ),
        /* 34 */ array(19, 21, ),
        /* 35 */ array(12, 14, ),
        /* 36 */ array(11, 19, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 37 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 38 */ array(19, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 39 */ array(20, 24, 39, 43, 52, ),
        /* 40 */ array(20, 24, 43, 52, ),
        /* 41 */ array(9, 20, 24, 52, ),
        /* 42 */ array(24, 43, 52, ),
        /* 43 */ array(1, 37, 52, ),
        /* 44 */ array(1, 52, ),
        /* 45 */ array(24, 52, ),
        /* 46 */ array(24, 52, ),
        /* 47 */ array(1, 52, ),
        /* 48 */ array(24, 52, ),
        /* 49 */ array(1, 52, ),
        /* 50 */ array(24, 52, ),
        /* 51 */ array(24, 52, ),
        /* 52 */ array(14, ),
        /* 53 */ array(22, ),
        /* 54 */ array(21, ),
        /* 55 */ array(22, ),
        /* 56 */ array(7, 8, 13, 40, ),
        /* 57 */ array(18, 34, 35, ),
        /* 58 */ array(11, 34, 35, ),
        /* 59 */ array(3, 34, 35, ),
        /* 60 */ array(7, 8, 40, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(7, 8, 40, ),
        /* 63 */ array(1, 50, 52, ),
        /* 64 */ array(1, 45, 52, ),
        /* 65 */ array(1, 5, 52, ),
        /* 66 */ array(1, 37, 52, ),
        /* 67 */ array(20, 24, 52, ),
        /* 68 */ array(19, 23, ),
        /* 69 */ array(34, 35, ),
        /* 70 */ array(34, 35, ),
        /* 71 */ array(3, 19, ),
        /* 72 */ array(3, 21, ),
        /* 73 */ array(3, 21, ),
        /* 74 */ array(3, 21, ),
        /* 75 */ array(3, 19, ),
        /* 76 */ array(16, 19, ),
        /* 77 */ array(11, 19, ),
        /* 78 */ array(3, 21, ),
        /* 79 */ array(12, 15, ),
        /* 80 */ array(14, 24, ),
        /* 81 */ array(10, ),
        /* 82 */ array(14, ),
        /* 83 */ array(24, ),
        /* 84 */ array(19, ),
        /* 85 */ array(22, ),
        /* 86 */ array(19, ),
        /* 87 */ array(10, ),
        /* 88 */ array(22, ),
        /* 89 */ array(19, ),
        /* 90 */ array(22, ),
        /* 91 */ array(22, ),
        /* 92 */ array(19, ),
        /* 93 */ array(17, ),
        /* 94 */ array(19, ),
        /* 95 */ array(19, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(10, 17, 20, ),
        /* 99 */ array(10, 17, 23, ),
        /* 100 */ array(10, 13, 17, ),
        /* 101 */ array(10, 17, ),
        /* 102 */ array(13, 16, ),
        /* 103 */ array(10, 17, ),
        /* 104 */ array(10, 17, ),
        /* 105 */ array(16, 18, ),
        /* 106 */ array(10, 17, ),
        /* 107 */ array(3, 22, ),
        /* 108 */ array(42, ),
        /* 109 */ array(10, ),
        /* 110 */ array(24, ),
        /* 111 */ array(24, ),
        /* 112 */ array(3, ),
        /* 113 */ array(3, ),
        /* 114 */ array(24, ),
        /* 115 */ array(24, ),
        /* 116 */ array(14, ),
        /* 117 */ array(24, ),
        /* 118 */ array(20, ),
        /* 119 */ array(17, ),
        /* 120 */ array(24, ),
        /* 121 */ array(11, ),
        /* 122 */ array(3, ),
        /* 123 */ array(11, ),
        /* 124 */ array(41, ),
        /* 125 */ array(3, ),
        /* 126 */ array(24, ),
        /* 127 */ array(3, ),
        /* 128 */ array(11, ),
        /* 129 */ array(24, ),
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
        /* 215 */ array(),
);
    static public $yy_default = array(
 /*     0 */   336,  336,  336,  336,  336,  336,  322,  336,  297,  297,
 /*    10 */   297,  297,  336,  336,  336,  336,  336,  336,  336,  336,
 /*    20 */   336,  336,  336,  336,  336,  336,  336,  336,  336,  243,
 /*    30 */   336,  275,  270,  243,  243,  336,  306,  216,  306,  279,
 /*    40 */   279,  336,  279,  336,  336,  336,  336,  336,  336,  336,
 /*    50 */   336,  336,  336,  266,  243,  265,  336,  336,  336,  336,
 /*    60 */   301,  249,  281,  336,  336,  336,  336,  336,  323,  308,
 /*    70 */   304,  336,  336,  336,  336,  336,  296,  336,  336,  291,
 /*    80 */   336,  279,  336,  336,  244,  271,  307,  279,  267,  324,
 /*    90 */   288,  268,  239,  250,  325,  247,  300,  300,  248,  248,
 /*   100 */   336,  302,  336,  248,  280,  336,  336,  336,  336,  269,
 /*   110 */   336,  336,  336,  336,  336,  336,  336,  336,  336,  336,
 /*   120 */   336,  336,  336,  336,  336,  336,  336,  336,  336,  336,
 /*   130 */   233,  236,  246,  217,  232,  234,  235,  315,  237,  316,
 /*   140 */   305,  303,  318,  317,  238,  314,  309,  245,  310,  311,
 /*   150 */   313,  312,  240,  292,  259,  260,  258,  253,  299,  252,
 /*   160 */   261,  262,  255,  272,  290,  254,  263,  278,  241,  228,
 /*   170 */   334,  335,  332,  220,  218,  219,  333,  221,  226,  227,
 /*   180 */   225,  224,  222,  223,  273,  256,  326,  328,  284,  287,
 /*   190 */   285,  286,  329,  330,  229,  230,  242,  327,  331,  264,
 /*   200 */   276,  289,  298,  319,  295,  277,  257,  251,  321,  320,
 /*   210 */   282,  294,  283,  274,  293,  231,
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
    const YYNOCODE = 93;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 216;
    const YYNRULE = 120;
    const YYERRORSYMBOL = 53;
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
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'EQUALS',        'NOTEQUALS',     'GREATERTHAN', 
  'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',    
  'NONEIDENTITY',  'NOT',           'LAND',          'LOR',         
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'IN',          
  'ANDSYM',        'BACKTICK',      'HATCH',         'AT',          
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'XML',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'modifier',      'modparameters',
  'ifexprs',       'statements',    'varvar',        'foraction',   
  'variable',      'array',         'attribute',     'exprs',       
  'value',         'math',          'function',      'doublequoted',
  'method',        'params',        'objectchain',   'vararraydefs',
  'object',        'vararraydef',   'varvarele',     'objectelement',
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'doublequotedcontent',  'textelement', 
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
 /*  49 */ "value ::= ID COLON COLON method",
 /*  50 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP",
 /*  51 */ "value ::= ID COLON COLON method objectchain",
 /*  52 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  53 */ "value ::= ID COLON COLON ID",
 /*  54 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  55 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
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
 /*  85 */ "modparameter ::= COLON exprs",
 /*  86 */ "modparameter ::= COLON ID",
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
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 11 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 0 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 8 ),
  array( 'lhs' => 72, 'rhs' => 5 ),
  array( 'lhs' => 72, 'rhs' => 9 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 6 ),
  array( 'lhs' => 72, 'rhs' => 7 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 0 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 0 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 0 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
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
#line 1573 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1576 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1579 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1585 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1588 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1591 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1594 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1597 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1603 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1609 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1612 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1615 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1618 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1621 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1624 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1627 "internal.templateparser.php"
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
#line 1642 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1645 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1648 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1651 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1654 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1657 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1660 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1663 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1666 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1669 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1672 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1675 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1678 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1681 "internal.templateparser.php"
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
#line 1695 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1698 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1701 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1704 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1707 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "''";     }
#line 1710 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1713 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r50(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1716 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1719 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1722 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1725 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1728 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1731 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1734 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1737 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r59(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1741 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1744 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r63(){return;    }
#line 1747 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1750 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1753 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1756 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1759 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1762 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1765 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1768 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1771 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1774 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1777 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1780 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1783 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r77(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1792 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1795 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1798 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r81(){ return;    }
#line 1801 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1804 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1807 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1810 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1813 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1816 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1819 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1822 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '==';    }
#line 1825 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '!=';    }
#line 1828 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '>';    }
#line 1831 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '<';    }
#line 1834 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>=';    }
#line 1837 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<=';    }
#line 1840 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '===';    }
#line 1843 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '!==';    }
#line 1846 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '&&';    }
#line 1849 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '||';    }
#line 1852 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r103(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1855 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1858 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r106(){ return;     }
#line 1861 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r108(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1864 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r109(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1867 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1870 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1873 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1876 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1879 "internal.templateparser.php"

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
#line 1996 "internal.templateparser.php"
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
#line 2021 "internal.templateparser.php"
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
