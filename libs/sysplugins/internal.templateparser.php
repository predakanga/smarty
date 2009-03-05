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
    const TP_XMLSTART                       =  6;
    const TP_XMLEND                         =  7;
    const TP_NUMBER                         =  8;
    const TP_MATH                           =  9;
    const TP_UNIMATH                        = 10;
    const TP_INCDEC                         = 11;
    const TP_OPENP                          = 12;
    const TP_CLOSEP                         = 13;
    const TP_OPENB                          = 14;
    const TP_CLOSEB                         = 15;
    const TP_DOLLAR                         = 16;
    const TP_DOT                            = 17;
    const TP_COMMA                          = 18;
    const TP_COLON                          = 19;
    const TP_DOUBLECOLON                    = 20;
    const TP_SEMICOLON                      = 21;
    const TP_VERT                           = 22;
    const TP_EQUAL                          = 23;
    const TP_SPACE                          = 24;
    const TP_PTR                            = 25;
    const TP_APTR                           = 26;
    const TP_ID                             = 27;
    const TP_EQUALS                         = 28;
    const TP_NOTEQUALS                      = 29;
    const TP_GREATERTHAN                    = 30;
    const TP_LESSTHAN                       = 31;
    const TP_GREATEREQUAL                   = 32;
    const TP_LESSEQUAL                      = 33;
    const TP_IDENTITY                       = 34;
    const TP_NONEIDENTITY                   = 35;
    const TP_NOT                            = 36;
    const TP_LAND                           = 37;
    const TP_LOR                            = 38;
    const TP_QUOTE                          = 39;
    const TP_SINGLEQUOTE                    = 40;
    const TP_BOOLEAN                        = 41;
    const TP_IN                             = 42;
    const TP_ANDSYM                         = 43;
    const TP_BACKTICK                       = 44;
    const TP_HATCH                          = 45;
    const TP_AT                             = 46;
    const TP_LITERALSTART                   = 47;
    const TP_LITERALEND                     = 48;
    const TP_LDELIMTAG                      = 49;
    const TP_RDELIMTAG                      = 50;
    const TP_PHP                            = 51;
    const TP_PHPSTART                       = 52;
    const TP_PHPEND                         = 53;
    const TP_LDEL                           = 54;
    const YY_NO_ACTION = 339;
    const YY_ACCEPT_ACTION = 338;
    const YY_ERROR_ACTION = 337;

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
    const YY_SZ_ACTTAB = 540;
static public $yy_action = array(
 /*     0 */   154,    9,   25,  126,    3,   62,    6,   16,   48,   68,
 /*    10 */   168,  114,   18,   49,   72,  177,  178,  169,   14,  102,
 /*    20 */    22,  172,  190,  153,  204,  121,   61,  156,    2,  159,
 /*    30 */   153,   28,   43,  184,  163,  203,  159,  123,  175,  137,
 /*    40 */    96,  109,  203,  107,  213,  169,  154,   79,   25,   20,
 /*    50 */     3,   17,    6,  212,   50,  190,   47,  195,  181,  182,
 /*    60 */   180,   45,  126,   11,   21,   98,  215,  147,  151,  153,
 /*    70 */   170,  158,   56,  156,    2,  159,   75,   28,   43,  184,
 /*    80 */   169,  203,   20,  123,  160,  153,  204,  121,   61,  156,
 /*    90 */    38,  159,  148,    9,  124,   59,  101,  203,  170,  153,
 /*   100 */   204,   68,   61,  156,  154,  159,   25,   15,   13,  126,
 /*   110 */     6,  203,   48,  154,  127,   25,  138,   13,   96,    6,
 /*   120 */   154,   44,   25,  102,   13,  108,    6,  176,   48,  190,
 /*   130 */     9,   93,   29,  170,    4,   28,   43,  184,   68,  100,
 /*   140 */   126,  123,    1,  111,   28,   43,  184,    9,  197,  212,
 /*   150 */   123,   28,   43,  184,  135,   68,   20,  123,  126,  338,
 /*   160 */    36,  133,  174,   48,  150,  145,  144,  139,  141,  142,
 /*   170 */   140,  149,   37,  206,  205,    9,   40,   58,  209,  211,
 /*   180 */   120,  153,  204,   68,   61,  156,  198,  159,  147,  151,
 /*   190 */    38,   54,  187,  203,   42,   69,  147,  151,  138,  153,
 /*   200 */   204,   19,   61,  156,  154,  159,   25,   26,   13,  153,
 /*   210 */   187,  203,   48,  162,  153,  159,  138,   60,  156,   38,
 /*   220 */   159,  203,   23,   99,   74,   24,  203,   85,  153,  204,
 /*   230 */   169,   61,  156,   90,  159,   28,   43,  184,  152,  190,
 /*   240 */   203,  123,  126,  206,  205,  138,  129,  131,  150,  145,
 /*   250 */   144,  139,  141,  142,  140,  149,   82,  154,  109,   25,
 /*   260 */   154,   13,   25,  136,   13,   48,   20,   35,   48,  153,
 /*   270 */   147,  151,   63,  156,  153,  159,  105,   26,  155,  106,
 /*   280 */   159,  203,  179,  170,  121,   78,  203,   97,   28,   43,
 /*   290 */   184,   28,   43,  184,  123,  195,   38,  123,  173,  174,
 /*   300 */    76,   57,   96,   75,   23,  153,  204,   24,   61,  156,
 /*   310 */   195,  159,  153,  204,  186,   61,  156,  203,  159,   75,
 /*   320 */   197,  119,  138,   12,  203,  130,  203,  126,  153,  204,
 /*   330 */   188,   61,  156,  169,  159,   48,  185,  201,  154,  194,
 /*   340 */   203,   75,   13,  117,  125,  167,   48,  110,  161,  126,
 /*   350 */   153,  204,  169,   61,  156,  203,  159,  103,  166,  116,
 /*   360 */   121,   51,  203,   54,   14,   90,  121,   72,  190,   28,
 /*   370 */    43,  184,  165,   19,    7,  123,  153,  204,  183,   61,
 /*   380 */   156,   33,  159,  112,   89,   46,  170,  164,  203,  200,
 /*   390 */   153,  204,  191,   61,  156,   20,  159,  208,   88,   81,
 /*   400 */   192,  164,  203,  200,  207,  170,  202,    8,  153,  204,
 /*   410 */   146,   61,  156,   70,  159,  128,    6,   14,   48,  126,
 /*   420 */   203,  190,  153,  204,   65,   61,  156,   67,  159,   83,
 /*   430 */   189,   41,   66,  214,  203,  126,   34,  121,  153,  204,
 /*   440 */   109,   61,  156,   94,  159,   84,   32,  187,   20,  189,
 /*   450 */   203,   39,  153,  204,  186,   61,  156,  175,  159,   91,
 /*   460 */   175,   30,  122,   64,  203,  175,  203,  187,  153,  204,
 /*   470 */    95,   61,  156,   73,  159,  200,  196,  118,  115,  187,
 /*   480 */   203,   77,  153,  204,   87,   61,  156,   80,  159,  200,
 /*   490 */   203,  195,   18,  143,  203,  132,  153,  204,   55,   61,
 /*   500 */   156,   92,  159,  113,  199,  193,   53,  134,  203,  210,
 /*   510 */   153,  204,  104,   61,  156,   86,  159,   27,    5,  157,
 /*   520 */   171,   52,  203,  189,  153,  204,   48,   61,  156,  164,
 /*   530 */   159,   10,   71,   31,   22,  121,  203,  217,  217,  158,
    );
    static public $yy_lookahead = array(
 /*     0 */     8,   12,   10,   22,   12,   60,   14,   26,   16,   20,
 /*    10 */     1,    2,   23,    4,   61,    6,    7,    1,   23,   27,
 /*    20 */    19,    5,   27,   70,   71,   24,   73,   74,   36,   76,
 /*    30 */    70,   39,   40,   41,   74,   82,   76,   45,   93,   11,
 /*    40 */    64,   46,   82,   90,   91,    1,    8,   62,   10,   54,
 /*    50 */    12,   23,   14,   13,   16,   27,   47,   72,   49,   50,
 /*    60 */    51,   52,   22,   54,   88,   27,    3,   37,   38,   70,
 /*    70 */    54,   86,   73,   74,   36,   76,   61,   39,   40,   41,
 /*    80 */     1,   82,   54,   45,   40,   70,   71,   24,   73,   74,
 /*    90 */    61,   76,   63,   12,   79,   66,   67,   82,   54,   70,
 /*   100 */    71,   20,   73,   74,    8,   76,   10,   26,   12,   22,
 /*   110 */    14,   82,   16,    8,   16,   10,   87,   12,   64,   14,
 /*   120 */     8,   16,   10,   27,   12,   27,   14,   48,   16,   27,
 /*   130 */    12,   18,   27,   54,   21,   39,   40,   41,   20,   27,
 /*   140 */    22,   45,   24,   25,   39,   40,   41,   12,    1,   13,
 /*   150 */    45,   39,   40,   41,    3,   20,   54,   45,   22,   56,
 /*   160 */    57,   58,   59,   16,   28,   29,   30,   31,   32,   33,
 /*   170 */    34,   35,   61,    9,   10,   12,   68,   66,   15,   15,
 /*   180 */    21,   70,   71,   20,   73,   74,   39,   76,   37,   38,
 /*   190 */    61,   44,   84,   82,   68,   66,   37,   38,   87,   70,
 /*   200 */    71,   54,   73,   74,    8,   76,   10,   43,   12,   70,
 /*   210 */    84,   82,   16,   74,   70,   76,   87,   73,   74,   61,
 /*   220 */    76,   82,   14,   27,   66,   17,   82,   27,   70,   71,
 /*   230 */     1,   73,   74,   25,   76,   39,   40,   41,   78,   27,
 /*   240 */    82,   45,   22,    9,   10,   87,   13,   63,   28,   29,
 /*   250 */    30,   31,   32,   33,   34,   35,   65,    8,   46,   10,
 /*   260 */     8,   12,   10,    3,   12,   16,   54,   65,   16,   70,
 /*   270 */    37,   38,   73,   74,   70,   76,   27,   43,   74,   27,
 /*   280 */    76,   82,   53,   54,   24,   62,   82,   64,   39,   40,
 /*   290 */    41,   39,   40,   41,   45,   72,   61,   45,   58,   59,
 /*   300 */    62,   66,   64,   61,   14,   70,   71,   17,   73,   74,
 /*   310 */    72,   76,   70,   71,   70,   73,   74,   82,   76,   61,
 /*   320 */     1,   79,   87,   18,   82,    3,   82,   22,   70,   71,
 /*   330 */     3,   73,   74,    1,   76,   16,   92,   79,    8,    3,
 /*   340 */    82,   61,   12,   70,   71,    3,   16,   25,   85,   22,
 /*   350 */    70,   71,    1,   73,   74,   82,   76,   27,   39,   79,
 /*   360 */    24,   16,   82,   44,   23,   25,   24,   61,   27,   39,
 /*   370 */    40,   41,   40,   54,   12,   45,   70,   71,   45,   73,
 /*   380 */    74,   61,   76,   63,   80,   16,   54,   83,   82,   85,
 /*   390 */    70,   71,   44,   73,   74,   54,   76,   91,   80,   61,
 /*   400 */     3,   83,   82,   85,   15,   54,   27,   18,   70,   71,
 /*   410 */     3,   73,   74,   61,   76,   69,   14,   23,   16,   22,
 /*   420 */    82,   27,   70,   71,   60,   73,   74,   60,   76,   61,
 /*   430 */    84,   68,   60,   13,   82,   22,   42,   24,   70,   71,
 /*   440 */    46,   73,   74,   61,   76,   27,   81,   84,   54,   84,
 /*   450 */    82,   68,   70,   71,   70,   73,   74,   93,   76,   61,
 /*   460 */    93,   77,   27,   68,   82,   93,   82,   84,   70,   71,
 /*   470 */    80,   73,   74,   61,   76,   85,   92,   27,   70,   84,
 /*   480 */    82,   62,   70,   71,   80,   73,   74,   61,   76,   85,
 /*   490 */    82,   72,   23,    3,   82,    3,   70,   71,   27,   73,
 /*   500 */    74,   61,   76,   27,   27,    3,   13,    3,   82,   13,
 /*   510 */    70,   71,   27,   73,   74,   61,   76,   75,   89,   72,
 /*   520 */    93,   78,   82,   84,   70,   71,   16,   73,   74,   83,
 /*   530 */    76,   12,   81,   81,   19,   24,   82,   94,   94,   86,
);
    const YY_SHIFT_USE_DFLT = -20;
    const YY_SHIFT_MAX = 128;
    static public $yy_shift_ofst = array(
 /*     0 */     9,   38,   -8,   -8,   -8,   -8,  112,   96,  112,   96,
 /*    10 */    96,  105,   96,   96,   96,   96,   96,   96,   96,   96,
 /*    20 */    96,   96,  252,  196,  249,  330,  330,  330,  147,  118,
 /*    30 */   319,  208,  208,  413,  402,    1,    9,  136,  220,  394,
 /*    40 */    -5,   28,  212,  332,  102,  351,  102,  351,  102,  351,
 /*    50 */   102,  102,  340,  340,  510,  511,  164,  159,  233,  151,
 /*    60 */   234,  234,   16,  234,  341,   79,  229,   44,   98,   30,
 /*    70 */    40,  290,  -19,  397,   30,  305,  342,   63,  336,  260,
 /*    80 */   327,   87,  515,   87,  519,  362,   87,  340,  340,  340,
 /*    90 */   200,   87,   87,  345,   87,  340,  -20,  -20,  -11,  163,
 /*   100 */    81,  113,  135,  135,  322,  135,  135,  389,  362,  477,
 /*   110 */   476,  471,  502,  504,  485,  348,  496,  490,  333,  420,
 /*   120 */   369,  435,  469,  450,  493,  407,  379,  418,  492,
);
    const YY_REDUCE_USE_DFLT = -56;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */   103,   29,  158,  111,  235,  129,  -47,  280,  306,  242,
 /*    10 */    15,  320,  258,  352,  454,  382,  338,  398,  368,  412,
 /*    20 */   426,  440,  144,   -1,  199,  204,  139,  -40,  384,  223,
 /*    30 */   244,  304,  318,  238,  273,  -15,  240,  -24,  -24,  365,
 /*    40 */   365,  346,  365,  367,  108,  372,  363,  364,  126,  -55,
 /*    50 */   383,  395,  390,  404,  408,  419,  442,  429,  429,  429,
 /*    60 */   442,  442,  427,  442,  439,  427,  427,  427,  443,  429,
 /*    70 */    54,  446,   54,   54,  429,   54,  447,  447,  447,  447,
 /*    80 */    54,   54,  453,   54,  452,  451,   54,  263,  263,  263,
 /*    90 */   160,   54,   54,  184,   54,  263,  191,  202,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 6, 7, 47, 49, 50, 51, 52, 54, ),
        /* 1 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 45, ),
        /* 2 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 45, ),
        /* 3 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 45, ),
        /* 4 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 45, ),
        /* 5 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 45, ),
        /* 6 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 7 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 8 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 9 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 10 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 11 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 12 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 13 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 14 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 15 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 16 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 17 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 18 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 19 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 20 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 21 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 45, ),
        /* 22 */ array(8, 10, 12, 16, 27, 39, 40, 41, 45, ),
        /* 23 */ array(8, 10, 12, 16, 27, 39, 40, 41, 45, ),
        /* 24 */ array(8, 10, 12, 16, 27, 39, 40, 41, 45, ),
        /* 25 */ array(8, 12, 16, 27, 39, 40, 41, 45, ),
        /* 26 */ array(8, 12, 16, 27, 39, 40, 41, 45, ),
        /* 27 */ array(8, 12, 16, 27, 39, 40, 41, 45, ),
        /* 28 */ array(1, 16, 39, 44, 54, ),
        /* 29 */ array(12, 20, 22, 24, 25, ),
        /* 30 */ array(1, 16, 39, 44, 54, ),
        /* 31 */ array(14, 17, 25, ),
        /* 32 */ array(14, 17, 25, ),
        /* 33 */ array(22, 24, ),
        /* 34 */ array(14, 16, ),
        /* 35 */ array(19, 24, ),
        /* 36 */ array(1, 2, 4, 6, 7, 47, 49, 50, 51, 52, 54, ),
        /* 37 */ array(13, 22, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 38 */ array(22, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 39 */ array(23, 27, 42, 46, 54, ),
        /* 40 */ array(23, 27, 46, 54, ),
        /* 41 */ array(11, 23, 27, 54, ),
        /* 42 */ array(27, 46, 54, ),
        /* 43 */ array(1, 40, 54, ),
        /* 44 */ array(27, 54, ),
        /* 45 */ array(1, 54, ),
        /* 46 */ array(27, 54, ),
        /* 47 */ array(1, 54, ),
        /* 48 */ array(27, 54, ),
        /* 49 */ array(1, 54, ),
        /* 50 */ array(27, 54, ),
        /* 51 */ array(27, 54, ),
        /* 52 */ array(25, ),
        /* 53 */ array(25, ),
        /* 54 */ array(16, ),
        /* 55 */ array(24, ),
        /* 56 */ array(9, 10, 15, 43, ),
        /* 57 */ array(21, 37, 38, ),
        /* 58 */ array(13, 37, 38, ),
        /* 59 */ array(3, 37, 38, ),
        /* 60 */ array(9, 10, 43, ),
        /* 61 */ array(9, 10, 43, ),
        /* 62 */ array(1, 5, 54, ),
        /* 63 */ array(9, 10, 43, ),
        /* 64 */ array(23, 27, 54, ),
        /* 65 */ array(1, 48, 54, ),
        /* 66 */ array(1, 53, 54, ),
        /* 67 */ array(1, 40, 54, ),
        /* 68 */ array(16, 27, ),
        /* 69 */ array(37, 38, ),
        /* 70 */ array(13, 22, ),
        /* 71 */ array(14, 17, ),
        /* 72 */ array(22, 26, ),
        /* 73 */ array(3, 22, ),
        /* 74 */ array(37, 38, ),
        /* 75 */ array(18, 22, ),
        /* 76 */ array(3, 24, ),
        /* 77 */ array(3, 24, ),
        /* 78 */ array(3, 24, ),
        /* 79 */ array(3, 24, ),
        /* 80 */ array(3, 22, ),
        /* 81 */ array(22, ),
        /* 82 */ array(19, ),
        /* 83 */ array(22, ),
        /* 84 */ array(12, ),
        /* 85 */ array(12, ),
        /* 86 */ array(22, ),
        /* 87 */ array(25, ),
        /* 88 */ array(25, ),
        /* 89 */ array(25, ),
        /* 90 */ array(27, ),
        /* 91 */ array(22, ),
        /* 92 */ array(22, ),
        /* 93 */ array(16, ),
        /* 94 */ array(22, ),
        /* 95 */ array(25, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(12, 20, 23, ),
        /* 99 */ array(12, 15, 20, ),
        /* 100 */ array(12, 20, 26, ),
        /* 101 */ array(18, 21, ),
        /* 102 */ array(12, 20, ),
        /* 103 */ array(12, 20, ),
        /* 104 */ array(3, 25, ),
        /* 105 */ array(12, 20, ),
        /* 106 */ array(12, 20, ),
        /* 107 */ array(15, 18, ),
        /* 108 */ array(12, ),
        /* 109 */ array(27, ),
        /* 110 */ array(27, ),
        /* 111 */ array(27, ),
        /* 112 */ array(3, ),
        /* 113 */ array(3, ),
        /* 114 */ array(27, ),
        /* 115 */ array(44, ),
        /* 116 */ array(13, ),
        /* 117 */ array(3, ),
        /* 118 */ array(45, ),
        /* 119 */ array(13, ),
        /* 120 */ array(16, ),
        /* 121 */ array(27, ),
        /* 122 */ array(23, ),
        /* 123 */ array(27, ),
        /* 124 */ array(13, ),
        /* 125 */ array(3, ),
        /* 126 */ array(27, ),
        /* 127 */ array(27, ),
        /* 128 */ array(3, ),
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
        /* 215 */ array(),
);
    static public $yy_default = array(
 /*     0 */   337,  337,  337,  337,  337,  337,  323,  298,  337,  298,
 /*    10 */   298,  337,  298,  337,  337,  337,  337,  337,  337,  337,
 /*    20 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  244,
 /*    30 */   337,  271,  276,  244,  337,  244,  216,  307,  307,  280,
 /*    40 */   280,  337,  280,  337,  337,  337,  337,  337,  337,  337,
 /*    50 */   337,  337,  266,  267,  337,  244,  337,  337,  337,  337,
 /*    60 */   303,  250,  337,  282,  337,  337,  337,  337,  337,  309,
 /*    70 */   337,  292,  324,  337,  305,  297,  337,  337,  337,  337,
 /*    80 */   337,  325,  251,  245,  280,  280,  248,  269,  289,  272,
 /*    90 */   337,  240,  308,  337,  326,  268,  301,  301,  249,  337,
 /*   100 */   249,  337,  249,  337,  337,  281,  302,  337,  270,  337,
 /*   110 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*   120 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  306,
 /*   130 */   234,  247,  237,  217,  235,  236,  233,  241,  304,  313,
 /*   140 */   316,  314,  315,  238,  312,  311,  239,  318,  246,  317,
 /*   150 */   310,  319,  293,  259,  260,  254,  253,  242,  300,  261,
 /*   160 */   262,  291,  256,  255,  279,  263,  264,  229,  228,  335,
 /*   170 */   336,  333,  220,  218,  219,  334,  221,  226,  227,  225,
 /*   180 */   224,  222,  223,  273,  274,  327,  329,  285,  288,  286,
 /*   190 */   287,  330,  331,  230,  231,  243,  328,  332,  265,  277,
 /*   200 */   290,  296,  299,  278,  252,  257,  258,  320,  322,  283,
 /*   210 */   295,  284,  275,  321,  294,  232,
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
    const YYNOCODE = 95;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 216;
    const YYNRULE = 121;
    const YYERRORSYMBOL = 55;
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
    1,  /*   XMLSTART => OTHER */
    1,  /*     XMLEND => OTHER */
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
  'COMMENTSTART',  'COMMENTEND',    'XMLSTART',      'XMLEND',      
  'NUMBER',        'MATH',          'UNIMATH',       'INCDEC',      
  'OPENP',         'CLOSEP',        'OPENB',         'CLOSEB',      
  'DOLLAR',        'DOT',           'COMMA',         'COLON',       
  'DOUBLECOLON',   'SEMICOLON',     'VERT',          'EQUAL',       
  'SPACE',         'PTR',           'APTR',          'ID',          
  'EQUALS',        'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',    
  'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',      'NONEIDENTITY',
  'NOT',           'LAND',          'LOR',           'QUOTE',       
  'SINGLEQUOTE',   'BOOLEAN',       'IN',            'ANDSYM',      
  'BACKTICK',      'HATCH',         'AT',            'LITERALSTART',
  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',     'PHP',         
  'PHPSTART',      'PHPEND',        'LDEL',          'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'text',          'expr',          'attributes',    'statement',   
  'modifier',      'modparameters',  'ifexprs',       'statements',  
  'varvar',        'foraction',     'variable',      'array',       
  'attribute',     'exprs',         'value',         'math',        
  'function',      'doublequoted',  'method',        'params',      
  'objectchain',   'vararraydefs',  'object',        'vararraydef', 
  'varvarele',     'objectelement',  'modparameter',  'ifexpr',      
  'ifcond',        'lop',           'arrayelements',  'arrayelement',
  'doublequotedcontent',  'textelement', 
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
 /*  10 */ "template_element ::= XMLSTART",
 /*  11 */ "template_element ::= XMLEND",
 /*  12 */ "template_element ::= OTHER",
 /*  13 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  14 */ "smartytag ::= LDEL statement RDEL",
 /*  15 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  16 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  17 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  18 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  19 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  20 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  21 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  22 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  23 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  24 */ "foraction ::= EQUAL expr",
 /*  25 */ "foraction ::= INCDEC",
 /*  26 */ "attributes ::= attributes attribute",
 /*  27 */ "attributes ::= attribute",
 /*  28 */ "attributes ::=",
 /*  29 */ "attribute ::= SPACE ID EQUAL expr",
 /*  30 */ "statements ::= statement",
 /*  31 */ "statements ::= statements COMMA statement",
 /*  32 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  33 */ "expr ::= ID",
 /*  34 */ "expr ::= exprs",
 /*  35 */ "expr ::= expr modifier modparameters",
 /*  36 */ "expr ::= array",
 /*  37 */ "exprs ::= value",
 /*  38 */ "exprs ::= UNIMATH value",
 /*  39 */ "exprs ::= exprs math value",
 /*  40 */ "exprs ::= exprs ANDSYM value",
 /*  41 */ "math ::= UNIMATH",
 /*  42 */ "math ::= MATH",
 /*  43 */ "value ::= variable",
 /*  44 */ "value ::= NUMBER",
 /*  45 */ "value ::= function",
 /*  46 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  47 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  48 */ "value ::= QUOTE doublequoted QUOTE",
 /*  49 */ "value ::= QUOTE QUOTE",
 /*  50 */ "value ::= ID DOUBLECOLON method",
 /*  51 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP",
 /*  52 */ "value ::= ID DOUBLECOLON method objectchain",
 /*  53 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  54 */ "value ::= ID DOUBLECOLON ID",
 /*  55 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs",
 /*  56 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs objectchain",
 /*  57 */ "value ::= HATCH ID HATCH",
 /*  58 */ "value ::= BOOLEAN",
 /*  59 */ "value ::= OPENP expr CLOSEP",
 /*  60 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  61 */ "variable ::= DOLLAR varvar AT ID",
 /*  62 */ "variable ::= object",
 /*  63 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  64 */ "vararraydefs ::=",
 /*  65 */ "vararraydef ::= DOT ID",
 /*  66 */ "vararraydef ::= DOT exprs",
 /*  67 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  68 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  69 */ "varvar ::= varvarele",
 /*  70 */ "varvar ::= varvar varvarele",
 /*  71 */ "varvarele ::= ID",
 /*  72 */ "varvarele ::= LDEL expr RDEL",
 /*  73 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  74 */ "objectchain ::= objectelement",
 /*  75 */ "objectchain ::= objectchain objectelement",
 /*  76 */ "objectelement ::= PTR ID vararraydefs",
 /*  77 */ "objectelement ::= PTR method",
 /*  78 */ "function ::= ID OPENP params CLOSEP",
 /*  79 */ "method ::= ID OPENP params CLOSEP",
 /*  80 */ "params ::= expr COMMA params",
 /*  81 */ "params ::= expr",
 /*  82 */ "params ::=",
 /*  83 */ "modifier ::= VERT ID",
 /*  84 */ "modparameters ::= modparameters modparameter",
 /*  85 */ "modparameters ::=",
 /*  86 */ "modparameter ::= COLON ID",
 /*  87 */ "modparameter ::= COLON exprs",
 /*  88 */ "ifexprs ::= ifexpr",
 /*  89 */ "ifexprs ::= NOT ifexprs",
 /*  90 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  91 */ "ifexpr ::= expr",
 /*  92 */ "ifexpr ::= expr ifcond expr",
 /*  93 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  94 */ "ifcond ::= EQUALS",
 /*  95 */ "ifcond ::= NOTEQUALS",
 /*  96 */ "ifcond ::= GREATERTHAN",
 /*  97 */ "ifcond ::= LESSTHAN",
 /*  98 */ "ifcond ::= GREATEREQUAL",
 /*  99 */ "ifcond ::= LESSEQUAL",
 /* 100 */ "ifcond ::= IDENTITY",
 /* 101 */ "ifcond ::= NONEIDENTITY",
 /* 102 */ "lop ::= LAND",
 /* 103 */ "lop ::= LOR",
 /* 104 */ "array ::= OPENB arrayelements CLOSEB",
 /* 105 */ "arrayelements ::= arrayelement",
 /* 106 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 107 */ "arrayelements ::=",
 /* 108 */ "arrayelement ::= expr",
 /* 109 */ "arrayelement ::= expr APTR expr",
 /* 110 */ "arrayelement ::= ID APTR expr",
 /* 111 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 112 */ "doublequoted ::= doublequotedcontent",
 /* 113 */ "doublequotedcontent ::= variable",
 /* 114 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 115 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 116 */ "doublequotedcontent ::= OTHER",
 /* 117 */ "text ::= text textelement",
 /* 118 */ "text ::= textelement",
 /* 119 */ "textelement ::= OTHER",
 /* 120 */ "textelement ::= LDEL",
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
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 5 ),
  array( 'lhs' => 59, 'rhs' => 5 ),
  array( 'lhs' => 59, 'rhs' => 11 ),
  array( 'lhs' => 59, 'rhs' => 8 ),
  array( 'lhs' => 59, 'rhs' => 8 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 7 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 8 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 5 ),
  array( 'lhs' => 74, 'rhs' => 6 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 0 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 4 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 0 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 0 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        33 => 0,
        37 => 0,
        43 => 0,
        44 => 0,
        45 => 0,
        58 => 0,
        62 => 0,
        105 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        69 => 1,
        88 => 1,
        112 => 1,
        118 => 1,
        119 => 1,
        120 => 1,
        2 => 2,
        63 => 2,
        111 => 2,
        117 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 10,
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
        22 => 22,
        23 => 22,
        24 => 24,
        25 => 25,
        27 => 25,
        81 => 25,
        108 => 25,
        26 => 26,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        35 => 35,
        38 => 38,
        39 => 39,
        40 => 40,
        46 => 46,
        48 => 46,
        47 => 47,
        49 => 47,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        59 => 59,
        60 => 60,
        61 => 61,
        64 => 64,
        85 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        70 => 70,
        71 => 71,
        72 => 72,
        90 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        82 => 82,
        83 => 83,
        84 => 84,
        86 => 86,
        87 => 87,
        89 => 89,
        91 => 91,
        92 => 92,
        93 => 92,
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
        104 => 104,
        106 => 106,
        107 => 107,
        109 => 109,
        110 => 110,
        113 => 113,
        114 => 114,
        115 => 115,
        116 => 116,
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
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode(php, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1616 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1627 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1630 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1633 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1636 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1639 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1642 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1645 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
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
#line 1660 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1663 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1666 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1669 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1672 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1675 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1678 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1681 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1684 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1687 "internal.templateparser.php"
#line 177 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1690 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1693 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1696 "internal.templateparser.php"
#line 185 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1699 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r35(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
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
#line 1713 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1716 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1719 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1722 "internal.templateparser.php"
#line 249 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1725 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "''";     }
#line 1728 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1731 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r51(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1734 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1737 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1740 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1743 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1746 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1749 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1752 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1755 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r60(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1759 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1762 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r64(){return;    }
#line 1765 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1768 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1771 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1774 "internal.templateparser.php"
#line 297 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1777 "internal.templateparser.php"
#line 303 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1780 "internal.templateparser.php"
#line 305 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1783 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1786 "internal.templateparser.php"
#line 312 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1789 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1792 "internal.templateparser.php"
#line 316 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1795 "internal.templateparser.php"
#line 318 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1798 "internal.templateparser.php"
#line 321 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1801 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r78(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1810 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1813 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1816 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r82(){ return;    }
#line 1819 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1822 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1825 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1828 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1831 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1834 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1837 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1840 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '==';    }
#line 1843 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!=';    }
#line 1846 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '>';    }
#line 1849 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '<';    }
#line 1852 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '>=';    }
#line 1855 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '<=';    }
#line 1858 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '===';    }
#line 1861 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '!==';    }
#line 1864 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '&&';    }
#line 1867 "internal.templateparser.php"
#line 387 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '||';    }
#line 1870 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r104(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1873 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r106(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1876 "internal.templateparser.php"
#line 392 "internal.templateparser.y"
    function yy_r107(){ return;     }
#line 1879 "internal.templateparser.php"
#line 394 "internal.templateparser.y"
    function yy_r109(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1882 "internal.templateparser.php"
#line 396 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1885 "internal.templateparser.php"
#line 400 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1888 "internal.templateparser.php"
#line 401 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1891 "internal.templateparser.php"
#line 402 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1894 "internal.templateparser.php"
#line 403 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1897 "internal.templateparser.php"

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
#line 2014 "internal.templateparser.php"
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
#line 2039 "internal.templateparser.php"
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
