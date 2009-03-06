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
    const TP_LDEL                           =  3;
    const TP_RDEL                           =  4;
    const TP_XMLSTART                       =  5;
    const TP_XMLEND                         =  6;
    const TP_NUMBER                         =  7;
    const TP_MATH                           =  8;
    const TP_UNIMATH                        =  9;
    const TP_INCDEC                         = 10;
    const TP_OPENP                          = 11;
    const TP_CLOSEP                         = 12;
    const TP_OPENB                          = 13;
    const TP_CLOSEB                         = 14;
    const TP_DOLLAR                         = 15;
    const TP_DOT                            = 16;
    const TP_COMMA                          = 17;
    const TP_COLON                          = 18;
    const TP_DOUBLECOLON                    = 19;
    const TP_SEMICOLON                      = 20;
    const TP_VERT                           = 21;
    const TP_EQUAL                          = 22;
    const TP_SPACE                          = 23;
    const TP_PTR                            = 24;
    const TP_APTR                           = 25;
    const TP_ID                             = 26;
    const TP_EQUALS                         = 27;
    const TP_NOTEQUALS                      = 28;
    const TP_GREATERTHAN                    = 29;
    const TP_LESSTHAN                       = 30;
    const TP_GREATEREQUAL                   = 31;
    const TP_LESSEQUAL                      = 32;
    const TP_IDENTITY                       = 33;
    const TP_NONEIDENTITY                   = 34;
    const TP_NOT                            = 35;
    const TP_LAND                           = 36;
    const TP_LOR                            = 37;
    const TP_QUOTE                          = 38;
    const TP_SINGLEQUOTE                    = 39;
    const TP_BOOLEAN                        = 40;
    const TP_NULL                           = 41;
    const TP_IN                             = 42;
    const TP_ANDSYM                         = 43;
    const TP_BACKTICK                       = 44;
    const TP_HATCH                          = 45;
    const TP_AT                             = 46;
    const TP_COMMENT                        = 47;
    const TP_LITERALSTART                   = 48;
    const TP_LITERALEND                     = 49;
    const TP_LDELIMTAG                      = 50;
    const TP_RDELIMTAG                      = 51;
    const TP_PHP                            = 52;
    const TP_PHPSTART                       = 53;
    const TP_PHPEND                         = 54;
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
    const YY_SZ_ACTTAB = 547;
static public $yy_action = array(
 /*     0 */   152,    7,   25,   10,    2,  122,    6,  162,   48,  151,
 /*    10 */   123,   76,   55,  155,   23,  153,  152,   22,   25,  104,
 /*    20 */     2,  203,    6,   89,   50,   81,  129,  135,    3,  187,
 /*    30 */    72,   28,   43,  161,  182,   97,  204,  183,  124,  151,
 /*    40 */   205,   46,   58,  155,    3,  153,  122,   28,   43,  161,
 /*    50 */   182,  203,  151,   19,  124,  152,  163,   25,  153,   20,
 /*    60 */   207,    6,   41,   48,  203,  167,  115,   11,   59,  176,
 /*    70 */   177,   26,   16,  185,  104,  152,  189,   25,  186,   20,
 /*    80 */    29,    6,  211,   48,  186,  203,   28,   43,  161,  182,
 /*    90 */    48,  122,   32,  124,   98,  195,  110,  148,  143,  137,
 /*   100 */   139,  140,  141,  142,  147,  213,   28,   43,  161,  182,
 /*   110 */    10,  171,   44,  124,  180,  181,  179,   47,   76,  152,
 /*   120 */   124,   25,  122,   20,   15,    6,   81,   51,  148,  143,
 /*   130 */   137,  139,  140,  141,  142,  147,  151,   19,   30,   65,
 /*   140 */   155,   19,  153,  151,   19,  211,   61,  155,  203,  153,
 /*   150 */    28,   43,  161,  182,  122,  203,   38,  124,  127,   35,
 /*   160 */   189,   60,  105,   16,  189,  151,  205,  189,   58,  155,
 /*   170 */   152,  153,   25,   63,   20,  129,  135,  203,   48,  152,
 /*   180 */   110,   25,  145,   20,   34,   93,  109,   48,  165,  101,
 /*   190 */   199,    8,   10,  151,  205,  208,   58,  155,  103,  153,
 /*   200 */    76,   28,   43,  161,  182,  203,  175,  169,  124,  174,
 /*   210 */    28,   43,  161,  182,  132,   10,  152,  124,   25,  196,
 /*   220 */    20,   21,  157,   76,   48,  122,  151,    1,  108,   24,
 /*   230 */   160,   38,  153,   48,  106,   96,   67,  169,  203,  174,
 /*   240 */   151,  205,   94,   58,  155,  159,  153,   28,   43,  161,
 /*   250 */   182,  152,  203,  131,  124,   20,  197,  145,  193,   48,
 /*   260 */    24,  133,   45,  124,   23,  113,   38,   22,  204,  183,
 /*   270 */    99,   56,  113,   77,  210,  151,  205,  113,   58,  155,
 /*   280 */   122,  153,   28,   43,  161,  182,   37,  203,   42,  124,
 /*   290 */   178,   57,  145,  129,  135,  151,  205,  191,   58,  155,
 /*   300 */   169,  153,  174,   26,  186,  214,   38,  203,  112,  111,
 /*   310 */   166,   74,  145,   72,  122,  151,  205,  209,   58,  155,
 /*   320 */   203,  153,  151,  205,  113,   58,  155,  203,  153,  113,
 /*   330 */    80,   14,  145,  165,  203,  199,   52,   75,  338,   36,
 /*   340 */   128,  173,  102,  212,   75,  201,  151,  205,  170,   58,
 /*   350 */   155,   75,  153,  151,  205,  200,   58,  155,  203,  153,
 /*   360 */   151,  205,  117,   58,  155,  203,  153,  151,   75,  121,
 /*   370 */   185,  154,  203,  153,  114,   79,   19,  151,  205,  203,
 /*   380 */    58,  155,  203,  153,  151,  205,  118,   58,  155,  203,
 /*   390 */   153,   19,  184,  122,   85,   16,  203,   18,  146,  189,
 /*   400 */   169,   69,  174,  151,  205,  192,   58,  155,   82,  153,
 /*   410 */    17,  194,  172,  173,  189,  203,   10,  151,  205,  110,
 /*   420 */    58,  155,   86,  153,   76,  157,   78,   14,   94,  203,
 /*   430 */   125,  151,  205,  136,   58,  155,  194,  153,  158,   91,
 /*   440 */   169,  120,  174,  203,  206,  134,   73,   12,  151,  205,
 /*   450 */    33,   58,  155,  188,  153,  151,  205,   62,   58,  155,
 /*   460 */   203,  153,   66,  149,   95,   88,    6,  203,   48,  129,
 /*   470 */   135,   64,  194,   83,  151,  205,    4,   58,  155,   70,
 /*   480 */   153,  196,  122,   21,  113,   94,  203,  116,  151,  205,
 /*   490 */   175,   58,  155,   71,  153,   48,  119,   68,  124,  203,
 /*   500 */   203,  198,  151,  205,  175,   58,  155,  194,  153,   13,
 /*   510 */    40,  188,   39,  107,  203,  100,   84,   90,  164,   87,
 /*   520 */   144,  199,  199,   53,   45,  124,  186,  202,  186,  190,
 /*   530 */   126,  130,  156,   27,    5,   54,  168,  113,  188,    9,
 /*   540 */    49,   92,  150,   31,  138,  218,  165,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,   17,    9,   11,   11,   21,   13,   85,   15,   70,
 /*    10 */    20,   19,   73,   74,   13,   76,    7,   16,    9,   26,
 /*    20 */    11,   82,   13,   65,   15,   24,   36,   37,   35,    4,
 /*    30 */    61,   38,   39,   40,   41,   26,    8,    9,   45,   70,
 /*    40 */    71,   15,   73,   74,   35,   76,   21,   38,   39,   40,
 /*    50 */    41,   82,   70,    3,   45,    7,   74,    9,   76,   11,
 /*    60 */    91,   13,   68,   15,   82,    1,    2,    3,   68,    5,
 /*    70 */     6,   43,   22,   70,   26,    7,   26,    9,   84,   11,
 /*    80 */    77,   13,   12,   15,   84,   82,   38,   39,   40,   41,
 /*    90 */    15,   21,   42,   45,   26,   92,   46,   27,   28,   29,
 /*   100 */    30,   31,   32,   33,   34,   12,   38,   39,   40,   41,
 /*   110 */    11,   47,   48,   45,   50,   51,   52,   53,   19,    7,
 /*   120 */    45,    9,   21,   11,   25,   13,   24,   15,   27,   28,
 /*   130 */    29,   30,   31,   32,   33,   34,   70,    3,   26,   73,
 /*   140 */    74,    3,   76,   70,    3,   12,   73,   74,   82,   76,
 /*   150 */    38,   39,   40,   41,   21,   82,   61,   45,   63,   65,
 /*   160 */    26,   66,   67,   22,   26,   70,   71,   26,   73,   74,
 /*   170 */     7,   76,    9,   60,   11,   36,   37,   82,   15,    7,
 /*   180 */    46,    9,   87,   11,   61,   80,   63,   15,   83,   26,
 /*   190 */    85,   11,   11,   70,   71,   14,   73,   74,   26,   76,
 /*   200 */    19,   38,   39,   40,   41,   82,   93,    1,   45,    3,
 /*   210 */    38,   39,   40,   41,    4,   11,    7,   45,    9,    1,
 /*   220 */    11,    3,   86,   19,   15,   21,   70,   23,   24,   18,
 /*   230 */    74,   61,   76,   15,   24,   26,   66,    1,   82,    3,
 /*   240 */    70,   71,   64,   73,   74,   39,   76,   38,   39,   40,
 /*   250 */    41,    7,   82,    4,   45,   11,   38,   87,    4,   15,
 /*   260 */    18,    4,   44,   45,   13,   23,   61,   16,    8,    9,
 /*   270 */    26,   66,   23,   81,   14,   70,   71,   23,   73,   74,
 /*   280 */    21,   76,   38,   39,   40,   41,   61,   82,   68,   45,
 /*   290 */    54,   66,   87,   36,   37,   70,   71,    4,   73,   74,
 /*   300 */     1,   76,    3,   43,   84,    4,   61,   82,   70,   71,
 /*   310 */     4,   66,   87,   61,   21,   70,   71,   12,   73,   74,
 /*   320 */    82,   76,   70,   71,   23,   73,   74,   82,   76,   23,
 /*   330 */    80,   22,   87,   83,   82,   85,   26,   61,   56,   57,
 /*   340 */    58,   59,   90,   91,   61,   26,   70,   71,   49,   73,
 /*   350 */    74,   61,   76,   70,   71,   79,   73,   74,   82,   76,
 /*   360 */    70,   71,   79,   73,   74,   82,   76,   70,   61,   79,
 /*   370 */    70,   74,   82,   76,   26,   61,    3,   70,   71,   82,
 /*   380 */    73,   74,   82,   76,   70,   71,   79,   73,   74,   82,
 /*   390 */    76,    3,   92,   21,   61,   22,   82,   25,   10,   26,
 /*   400 */     1,   62,    3,   70,   71,    4,   73,   74,   61,   76,
 /*   410 */    22,   72,   58,   59,   26,   82,   11,   70,   71,   46,
 /*   420 */    73,   74,   61,   76,   19,   86,   62,   22,   64,   82,
 /*   430 */    15,   70,   71,    4,   73,   74,   72,   76,   39,   61,
 /*   440 */     1,   26,    3,   82,   14,   12,   61,   17,   70,   71,
 /*   450 */    81,   73,   74,   84,   76,   70,   71,   60,   73,   74,
 /*   460 */    82,   76,   62,    4,   64,   61,   13,   82,   15,   36,
 /*   470 */    37,   60,   72,   17,   70,   71,   20,   73,   74,   61,
 /*   480 */    76,    1,   21,    3,   23,   64,   82,   70,   70,   71,
 /*   490 */    93,   73,   74,   61,   76,   15,   69,   62,   45,   82,
 /*   500 */    82,   26,   70,   71,   93,   73,   74,   72,   76,   88,
 /*   510 */    68,   84,   68,   26,   82,   26,   80,   80,   38,   26,
 /*   520 */     4,   85,   85,   12,   44,   45,   84,   45,   84,   44,
 /*   530 */    26,    4,   72,   75,   89,   78,   93,   23,   84,   11,
 /*   540 */    15,   26,   78,   81,   63,   94,   83,
);
    const YY_SHIFT_USE_DFLT = -17;
    const YY_SHIFT_MAX = 126;
    static public $yy_shift_ofst = array(
 /*     0 */    64,    9,   -7,   -7,   -7,   -7,   68,   48,   48,   48,
 /*    10 */    48,  112,   68,   48,   48,   48,   48,   48,   48,   48,
 /*    20 */    48,   48,  172,  209,  163,  244,  244,  244,  218,  480,
 /*    30 */   204,    1,  453,    1,  461,  242,   64,   70,  101,   50,
 /*    40 */   373,  388,  134,  206,  439,   75,  138,  439,  138,  138,
 /*    50 */   138,  138,  514,  102,  102,  260,  -10,  433,   28,  141,
 /*    60 */   257,   28,  299,  399,  236,   28,  254,  139,  301,  249,
 /*    70 */   133,  293,  372,   25,  139,  -16,  415,  251,  306,  259,
 /*    80 */   102,  515,  259,  525,  102,  259,  259,  528,  259,  211,
 /*    90 */   102,  259,  180,  102,  -17,  -17,  181,  405,   99,   -8,
 /*   100 */   210,   -8,  430,   -8,   -8,  456,  487,  527,  310,  401,
 /*   110 */   475,  459,  429,  348,  309,  489,  485,  511,  305,  516,
 /*   120 */   180,   93,  319,   26,  504,  493,  482,
);
    const YY_REDUCE_USE_DFLT = -79;
    const YY_REDUCE_MAX = 95;
    static public $yy_reduce_ofst = array(
 /*     0 */   282,   95,  225,  170,  205,  245,  252,  276,  307,  283,
 /*    10 */   290,  123,  -31,  347,  314,  404,  378,  333,  361,  385,
 /*    20 */   418,  432,   66,  -61,   73,  297,  156,  -18,    3,  300,
 /*    30 */   400,  105,  238,  250,  364,  339,  354,  421,  421,  369,
 /*    40 */   369,  427,  369,  113,  397,  417,   -6,  411,  220,    0,
 /*    50 */   444,  442,  435,  437,  436,  458,  445,  445,  458,  454,
 /*    60 */   445,  458,  443,  443,  443,  458,  460,  445,  460,  460,
 /*    70 */   178,  178,  178,  178,  445,  178,  457,  463,  460,  178,
 /*    80 */   -78,  464,  178,  481,  -78,  178,  178,  462,  178,  136,
 /*    90 */   -78,  178,  192,  -78,  -42,   94,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 47, 48, 50, 51, 52, 53, ),
        /* 1 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 2 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 3 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 4 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 5 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 6 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 7 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 8 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 9 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 10 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 11 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 12 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 13 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 14 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 15 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 16 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 17 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 18 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 19 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 20 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 21 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 22 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 23 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 24 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 25 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 26 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 27 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 28 */ array(1, 3, 15, 38, 44, 45, ),
        /* 29 */ array(1, 3, 15, 38, 44, 45, ),
        /* 30 */ array(11, 19, 21, 23, 24, ),
        /* 31 */ array(13, 16, 24, ),
        /* 32 */ array(13, 15, 45, ),
        /* 33 */ array(13, 16, 24, ),
        /* 34 */ array(21, 23, ),
        /* 35 */ array(18, 23, ),
        /* 36 */ array(1, 2, 3, 5, 6, 47, 48, 50, 51, 52, 53, ),
        /* 37 */ array(12, 21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 38 */ array(21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 39 */ array(3, 22, 26, 42, 46, ),
        /* 40 */ array(3, 22, 26, 46, ),
        /* 41 */ array(3, 10, 22, 26, ),
        /* 42 */ array(3, 26, 46, ),
        /* 43 */ array(1, 3, 39, ),
        /* 44 */ array(1, 3, ),
        /* 45 */ array(15, 45, ),
        /* 46 */ array(3, 26, ),
        /* 47 */ array(1, 3, ),
        /* 48 */ array(3, 26, ),
        /* 49 */ array(3, 26, ),
        /* 50 */ array(3, 26, ),
        /* 51 */ array(3, 26, ),
        /* 52 */ array(23, ),
        /* 53 */ array(24, ),
        /* 54 */ array(24, ),
        /* 55 */ array(8, 9, 14, 43, ),
        /* 56 */ array(20, 36, 37, ),
        /* 57 */ array(12, 36, 37, ),
        /* 58 */ array(8, 9, 43, ),
        /* 59 */ array(3, 22, 26, ),
        /* 60 */ array(4, 36, 37, ),
        /* 61 */ array(8, 9, 43, ),
        /* 62 */ array(1, 3, 49, ),
        /* 63 */ array(1, 3, 39, ),
        /* 64 */ array(1, 3, 54, ),
        /* 65 */ array(8, 9, 43, ),
        /* 66 */ array(4, 23, ),
        /* 67 */ array(36, 37, ),
        /* 68 */ array(4, 23, ),
        /* 69 */ array(4, 23, ),
        /* 70 */ array(12, 21, ),
        /* 71 */ array(4, 21, ),
        /* 72 */ array(21, 25, ),
        /* 73 */ array(4, 21, ),
        /* 74 */ array(36, 37, ),
        /* 75 */ array(17, 21, ),
        /* 76 */ array(15, 26, ),
        /* 77 */ array(13, 16, ),
        /* 78 */ array(4, 23, ),
        /* 79 */ array(21, ),
        /* 80 */ array(24, ),
        /* 81 */ array(26, ),
        /* 82 */ array(21, ),
        /* 83 */ array(15, ),
        /* 84 */ array(24, ),
        /* 85 */ array(21, ),
        /* 86 */ array(21, ),
        /* 87 */ array(11, ),
        /* 88 */ array(21, ),
        /* 89 */ array(18, ),
        /* 90 */ array(24, ),
        /* 91 */ array(21, ),
        /* 92 */ array(11, ),
        /* 93 */ array(24, ),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(11, 14, 19, ),
        /* 97 */ array(11, 19, 22, ),
        /* 98 */ array(11, 19, 25, ),
        /* 99 */ array(11, 19, ),
        /* 100 */ array(4, 24, ),
        /* 101 */ array(11, 19, ),
        /* 102 */ array(14, 17, ),
        /* 103 */ array(11, 19, ),
        /* 104 */ array(11, 19, ),
        /* 105 */ array(17, 20, ),
        /* 106 */ array(26, ),
        /* 107 */ array(4, ),
        /* 108 */ array(26, ),
        /* 109 */ array(4, ),
        /* 110 */ array(26, ),
        /* 111 */ array(4, ),
        /* 112 */ array(4, ),
        /* 113 */ array(26, ),
        /* 114 */ array(22, ),
        /* 115 */ array(26, ),
        /* 116 */ array(44, ),
        /* 117 */ array(12, ),
        /* 118 */ array(12, ),
        /* 119 */ array(4, ),
        /* 120 */ array(11, ),
        /* 121 */ array(12, ),
        /* 122 */ array(26, ),
        /* 123 */ array(15, ),
        /* 124 */ array(26, ),
        /* 125 */ array(26, ),
        /* 126 */ array(45, ),
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
 /*     0 */   337,  337,  337,  337,  337,  337,  323,  298,  298,  298,
 /*    10 */   298,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*    20 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*    30 */   243,  270,  337,  275,  243,  243,  215,  307,  307,  280,
 /*    40 */   280,  337,  280,  337,  337,  337,  337,  337,  337,  337,
 /*    50 */   337,  337,  243,  266,  265,  337,  337,  337,  249,  337,
 /*    60 */   337,  303,  337,  337,  337,  282,  337,  305,  337,  337,
 /*    70 */   337,  337,  324,  337,  309,  297,  337,  292,  337,  244,
 /*    80 */   289,  337,  308,  337,  267,  239,  325,  280,  326,  250,
 /*    90 */   268,  247,  280,  271,  301,  301,  337,  248,  248,  337,
 /*   100 */   337,  302,  337,  281,  248,  337,  337,  337,  337,  337,
 /*   110 */   337,  337,  337,  337,  337,  337,  337,  337,  337,  337,
 /*   120 */   269,  337,  337,  337,  337,  337,  337,  245,  216,  318,
 /*   130 */   234,  232,  233,  235,  306,  319,  237,  312,  246,  313,
 /*   140 */   314,  315,  316,  311,  236,  304,  240,  317,  310,  238,
 /*   150 */   293,  258,  259,  260,  253,  252,  241,  300,  261,  262,
 /*   160 */   255,  272,  291,  254,  263,  279,  228,  227,  333,  335,
 /*   170 */   220,  219,  217,  218,  336,  334,  225,  226,  224,  223,
 /*   180 */   221,  222,  273,  256,  327,  329,  285,  288,  286,  287,
 /*   190 */   330,  331,  229,  230,  242,  328,  332,  264,  276,  290,
 /*   200 */   296,  299,  278,  277,  257,  251,  320,  322,  283,  295,
 /*   210 */   284,  274,  321,  294,  231,
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
    const YYNSTATE = 215;
    const YYNRULE = 122;
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
    1,  /*       LDEL => OTHER */
    1,  /*       RDEL => OTHER */
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
    1,  /*       NULL => OTHER */
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*   BACKTICK => OTHER */
    1,  /*      HATCH => OTHER */
    1,  /*         AT => OTHER */
    0,  /*    COMMENT => nothing */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*        PHP => nothing */
    0,  /*   PHPSTART => nothing */
    0,  /*     PHPEND => nothing */
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
  '$',             'OTHER',         'LDELSLASH',     'LDEL',        
  'RDEL',          'XMLSTART',      'XMLEND',        'NUMBER',      
  'MATH',          'UNIMATH',       'INCDEC',        'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'DOUBLECOLON', 
  'SEMICOLON',     'VERT',          'EQUAL',         'SPACE',       
  'PTR',           'APTR',          'ID',            'EQUALS',      
  'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',
  'LESSEQUAL',     'IDENTITY',      'NONEIDENTITY',  'NOT',         
  'LAND',          'LOR',           'QUOTE',         'SINGLEQUOTE', 
  'BOOLEAN',       'NULL',          'IN',            'ANDSYM',      
  'BACKTICK',      'HATCH',         'AT',            'COMMENT',     
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'error',       
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
 /*   4 */ "template_element ::= COMMENT",
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
 /*  57 */ "value ::= BOOLEAN",
 /*  58 */ "value ::= NULL",
 /*  59 */ "value ::= OPENP expr CLOSEP",
 /*  60 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  61 */ "variable ::= DOLLAR varvar AT ID",
 /*  62 */ "variable ::= object",
 /*  63 */ "variable ::= HATCH ID HATCH",
 /*  64 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  65 */ "vararraydefs ::=",
 /*  66 */ "vararraydef ::= DOT ID",
 /*  67 */ "vararraydef ::= DOT exprs",
 /*  68 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  69 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  70 */ "varvar ::= varvarele",
 /*  71 */ "varvar ::= varvar varvarele",
 /*  72 */ "varvarele ::= ID",
 /*  73 */ "varvarele ::= LDEL expr RDEL",
 /*  74 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  75 */ "objectchain ::= objectelement",
 /*  76 */ "objectchain ::= objectchain objectelement",
 /*  77 */ "objectelement ::= PTR ID vararraydefs",
 /*  78 */ "objectelement ::= PTR method",
 /*  79 */ "function ::= ID OPENP params CLOSEP",
 /*  80 */ "method ::= ID OPENP params CLOSEP",
 /*  81 */ "params ::= expr COMMA params",
 /*  82 */ "params ::= expr",
 /*  83 */ "params ::=",
 /*  84 */ "modifier ::= VERT ID",
 /*  85 */ "modparameters ::= modparameters modparameter",
 /*  86 */ "modparameters ::=",
 /*  87 */ "modparameter ::= COLON ID",
 /*  88 */ "modparameter ::= COLON exprs",
 /*  89 */ "ifexprs ::= ifexpr",
 /*  90 */ "ifexprs ::= NOT ifexprs",
 /*  91 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  92 */ "ifexpr ::= expr",
 /*  93 */ "ifexpr ::= expr ifcond expr",
 /*  94 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  95 */ "ifcond ::= EQUALS",
 /*  96 */ "ifcond ::= NOTEQUALS",
 /*  97 */ "ifcond ::= GREATERTHAN",
 /*  98 */ "ifcond ::= LESSTHAN",
 /*  99 */ "ifcond ::= GREATEREQUAL",
 /* 100 */ "ifcond ::= LESSEQUAL",
 /* 101 */ "ifcond ::= IDENTITY",
 /* 102 */ "ifcond ::= NONEIDENTITY",
 /* 103 */ "lop ::= LAND",
 /* 104 */ "lop ::= LOR",
 /* 105 */ "array ::= OPENB arrayelements CLOSEB",
 /* 106 */ "arrayelements ::= arrayelement",
 /* 107 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 108 */ "arrayelements ::=",
 /* 109 */ "arrayelement ::= expr",
 /* 110 */ "arrayelement ::= expr APTR expr",
 /* 111 */ "arrayelement ::= ID APTR expr",
 /* 112 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 113 */ "doublequoted ::= doublequotedcontent",
 /* 114 */ "doublequotedcontent ::= variable",
 /* 115 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 116 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 117 */ "doublequotedcontent ::= OTHER",
 /* 118 */ "text ::= text textelement",
 /* 119 */ "text ::= textelement",
 /* 120 */ "textelement ::= OTHER",
 /* 121 */ "textelement ::= LDEL",
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
  array( 'lhs' => 58, 'rhs' => 1 ),
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
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
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
        37 => 0,
        43 => 0,
        44 => 0,
        45 => 0,
        57 => 0,
        58 => 0,
        62 => 0,
        106 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        89 => 1,
        113 => 1,
        119 => 1,
        120 => 1,
        121 => 1,
        2 => 2,
        64 => 2,
        112 => 2,
        118 => 2,
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
        82 => 25,
        109 => 25,
        26 => 26,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        33 => 33,
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
        59 => 59,
        60 => 60,
        61 => 61,
        63 => 63,
        65 => 65,
        86 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        91 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        83 => 83,
        84 => 84,
        85 => 85,
        87 => 87,
        88 => 88,
        90 => 90,
        92 => 92,
        93 => 93,
        94 => 93,
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
        105 => 105,
        107 => 107,
        108 => 108,
        110 => 110,
        111 => 111,
        114 => 114,
        115 => 115,
        116 => 116,
        117 => 117,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1585 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1588 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1591 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1597 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1600 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1603 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1606 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1609 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode(php, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1620 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1631 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1634 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1637 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1640 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1643 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1646 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1649 "internal.templateparser.php"
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
#line 1664 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1667 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1670 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1673 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1676 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1679 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1682 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1685 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1688 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1691 "internal.templateparser.php"
#line 177 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1694 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1697 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1700 "internal.templateparser.php"
#line 185 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1703 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1706 "internal.templateparser.php"
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
#line 1720 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1723 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1726 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1729 "internal.templateparser.php"
#line 249 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1732 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "''";     }
#line 1735 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1738 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r51(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1741 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1744 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1747 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1750 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1753 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1756 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1759 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r60(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1763 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1766 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1769 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1772 "internal.templateparser.php"
#line 297 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1775 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1778 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1781 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1784 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1787 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1790 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1793 "internal.templateparser.php"
#line 316 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1796 "internal.templateparser.php"
#line 318 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1799 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1802 "internal.templateparser.php"
#line 322 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1805 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1808 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1817 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1820 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1823 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1826 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1829 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1832 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1835 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1838 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1841 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1844 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1847 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '==';    }
#line 1850 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!=';    }
#line 1853 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>';    }
#line 1856 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<';    }
#line 1859 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '>=';    }
#line 1862 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '<=';    }
#line 1865 "internal.templateparser.php"
#line 387 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '===';    }
#line 1868 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '!==';    }
#line 1871 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '&&';    }
#line 1874 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = '||';    }
#line 1877 "internal.templateparser.php"
#line 393 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1880 "internal.templateparser.php"
#line 395 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1883 "internal.templateparser.php"
#line 396 "internal.templateparser.y"
    function yy_r108(){ return;     }
#line 1886 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1889 "internal.templateparser.php"
#line 400 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1892 "internal.templateparser.php"
#line 404 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1895 "internal.templateparser.php"
#line 405 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1898 "internal.templateparser.php"
#line 406 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1901 "internal.templateparser.php"
#line 407 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1904 "internal.templateparser.php"

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
#line 2021 "internal.templateparser.php"
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
#line 2046 "internal.templateparser.php"
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
