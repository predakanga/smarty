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
    const TP_NULL                           = 39;
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
    const YY_NO_ACTION = 333;
    const YY_ACCEPT_ACTION = 332;
    const YY_ERROR_ACTION = 331;

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
    const YY_SZ_ACTTAB = 536;
static public $yy_action = array(
 /*     0 */   128,   22,   27,   22,    4,  171,    6,  171,   44,   83,
 /*    10 */   144,  128,   80,   27,  150,    4,  174,    6,   87,   45,
 /*    20 */    26,   33,  129,  176,  177,  111,  120,    5,  182,   85,
 /*    30 */    28,   41,  124,  123,   15,   96,   15,  117,    5,    8,
 /*    40 */   129,   28,   41,  124,  123,  128,  106,   27,  117,   21,
 /*    50 */   187,    6,   10,   44,  128,  160,   27,   25,   21,    8,
 /*    60 */     6,  180,   43,   87,   76,  159,  106,  130,   96,  189,
 /*    70 */     1,   99,   29,   14,   44,   28,   41,  124,  123,   39,
 /*    80 */   201,  104,  117,   42,   28,   41,  124,  123,   56,   18,
 /*    90 */   149,  117,   20,   64,   89,  169,  179,  144,  143,   78,
 /*   100 */    37,  142,   52,  174,  128,  194,   27,  162,   21,  129,
 /*   110 */     6,  141,   44,   17,  135,   73,  332,   35,  139,  148,
 /*   120 */   189,  160,   84,   81,   48,    3,  119,  136,  199,   46,
 /*   130 */    92,  192,   12,   56,   28,   41,  124,  123,   61,   71,
 /*   140 */   182,  117,  144,  143,  160,   37,  142,   30,  174,  204,
 /*   150 */   160,   56,  129,    6,  129,   44,   68,  158,    8,  135,
 /*   160 */   144,  143,  178,   37,  142,  106,  174,  128,   14,   56,
 /*   170 */    97,   21,  129,  162,   67,   44,  207,  135,  144,  143,
 /*   180 */     8,   37,  142,  168,  174,   88,  125,  106,  160,  127,
 /*   190 */   129,  121,  145,   23,  108,  135,  162,   28,   41,  124,
 /*   200 */   123,   74,  162,  188,  117,  197,  196,  212,  154,  134,
 /*   210 */   133,  132,  131,   31,   77,   55,  175,  130,  155,  189,
 /*   220 */    60,  108,  112,  181,  144,  143,  180,   37,  142,   24,
 /*   230 */   174,  144,  143,  171,   37,  142,  129,  174,   19,   44,
 /*   240 */   162,  135,  108,  129,  144,   53,  208,  103,  186,    9,
 /*   250 */   174,   90,  202,  122,  144,  143,  129,   37,  142,  112,
 /*   260 */   174,  147,   15,  166,  190,   34,  129,   52,  144,  143,
 /*   270 */    62,   37,  142,  114,  174,  144,  138,  137,   17,  184,
 /*   280 */   129,  174,  144,  143,  126,   37,  142,  129,  174,  211,
 /*   290 */     7,   93,  114,   22,  129,  138,  137,  171,   40,  102,
 /*   300 */   105,  144,  143,  157,   37,  142,  114,  174,  171,  160,
 /*   310 */   191,  129,   65,  129,  169,  144,  143,  111,   37,  142,
 /*   320 */   114,  174,  204,  101,  115,  121,   15,  129,  111,  144,
 /*   330 */   143,   18,   37,  142,   20,  174,  161,   15,  107,  138,
 /*   340 */   137,  129,  197,  196,  212,  154,  134,  133,  132,  131,
 /*   350 */    95,  163,  148,  171,   69,  209,   82,  138,  137,  144,
 /*   360 */   143,  162,   37,  142,  204,  174,  206,   91,  193,   59,
 /*   370 */   100,  129,  118,  108,   13,  144,  143,   98,   37,  142,
 /*   380 */   156,  174,   15,   63,  129,  175,  108,  129,  195,  144,
 /*   390 */   143,   75,   37,  142,  200,  174,  189,   36,   58,   57,
 /*   400 */    72,  129,  157,  144,  143,  165,   37,  142,  164,  174,
 /*   410 */   204,   38,   49,  169,  169,  129,  157,  144,  143,  109,
 /*   420 */    37,  142,  205,  174,  153,  198,  146,  169,  173,  129,
 /*   430 */    47,  140,  157,  144,  143,  203,   37,  142,   86,  174,
 /*   440 */   110,   94,   50,  151,   51,  129,   66,  161,    2,  144,
 /*   450 */   143,   54,   37,  142,  167,  174,  172,   78,  152,   44,
 /*   460 */   130,  129,  108,  144,  143,  175,   37,  142,  183,  174,
 /*   470 */   210,   16,   19,   70,   11,  129,   32,  144,  143,  213,
 /*   480 */    37,  142,  116,  174,   79,  213,  213,  170,  213,  129,
 /*   490 */   213,  144,  143,  213,   37,  142,  213,  174,  185,  213,
 /*   500 */   213,  213,  213,  129,  213,  213,  213,  144,  143,  213,
 /*   510 */    37,  142,  213,  174,  113,  213,  213,  213,  213,  129,
 /*   520 */   213,  213,  213,  144,  143,  213,   37,  142,  213,  174,
 /*   530 */   213,  213,  213,  213,  213,  129,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   20,    8,   20,   10,   24,   12,   24,   14,   63,
 /*    10 */    69,    6,   64,    8,   73,   10,   75,   12,   24,   14,
 /*    20 */    74,   40,   81,    7,    8,   44,    3,   33,   69,   24,
 /*    30 */    36,   37,   38,   39,   53,   19,   53,   43,   33,   10,
 /*    40 */    81,   36,   37,   38,   39,    6,   17,    8,   43,   10,
 /*    50 */    91,   12,   10,   14,    6,    1,    8,   41,   10,   10,
 /*    60 */    12,    1,   14,   24,   79,   62,   17,   82,   19,   84,
 /*    70 */    21,   22,   24,   20,   14,   36,   37,   38,   39,   67,
 /*    80 */     1,    2,   43,    4,   36,   37,   38,   39,   60,   12,
 /*    90 */    62,   43,   15,   65,   66,   83,   36,   69,   70,   22,
 /*   100 */    72,   73,   42,   75,    6,   51,    8,   53,   10,   81,
 /*   110 */    12,    3,   14,   53,   86,   79,   55,   56,   57,   58,
 /*   120 */    84,    1,   24,   16,   45,   18,   47,   48,   49,   50,
 /*   130 */    22,   52,   53,   60,   36,   37,   38,   39,   65,   61,
 /*   140 */    69,   43,   69,   70,    1,   72,   73,   76,   75,   71,
 /*   150 */     1,   60,   81,   12,   81,   14,   65,   37,   10,   86,
 /*   160 */    69,   70,   91,   72,   73,   17,   75,    6,   20,   60,
 /*   170 */    24,   10,   81,   53,   65,   14,    3,   86,   69,   70,
 /*   180 */    10,   72,   73,   24,   75,   24,   37,   17,    1,   46,
 /*   190 */    81,   11,    5,   23,   21,   86,   53,   36,   37,   38,
 /*   200 */    39,   24,   53,    3,   43,   25,   26,   27,   28,   29,
 /*   210 */    30,   31,   32,   80,   79,   60,   83,   82,    9,   84,
 /*   220 */    65,   21,   60,    3,   69,   70,    1,   72,   73,   20,
 /*   230 */    75,   69,   70,   24,   72,   73,   81,   75,   17,   14,
 /*   240 */    53,   86,   21,   81,   69,   60,   13,   62,   73,   16,
 /*   250 */    75,   89,   90,   11,   69,   70,   81,   72,   73,   60,
 /*   260 */    75,   36,   53,   11,    3,   64,   81,   42,   69,   70,
 /*   270 */    59,   72,   73,   60,   75,   69,   34,   35,   53,   73,
 /*   280 */    81,   75,   69,   70,   43,   72,   73,   81,   75,   90,
 /*   290 */    16,   78,   60,   20,   81,   34,   35,   24,   67,   69,
 /*   300 */    70,   69,   70,   92,   72,   73,   60,   75,   24,    1,
 /*   310 */    78,   81,   61,   81,   83,   69,   70,   44,   72,   73,
 /*   320 */    60,   75,   71,   18,   78,   11,   53,   81,   44,   69,
 /*   330 */    70,   12,   72,   73,   15,   75,   85,   53,   78,   34,
 /*   340 */    35,   81,   25,   26,   27,   28,   29,   30,   31,   32,
 /*   350 */    60,   57,   58,   24,   61,    3,   63,   34,   35,   69,
 /*   360 */    70,   53,   72,   73,   71,   75,   60,   14,    3,   59,
 /*   370 */    68,   81,   69,   21,   23,   69,   70,   24,   72,   73,
 /*   380 */    60,   75,   53,   59,   81,   83,   21,   81,   11,   69,
 /*   390 */    70,   79,   72,   73,   60,   75,   84,   67,   67,   59,
 /*   400 */    61,   81,   92,   69,   70,    3,   72,   73,   60,   75,
 /*   410 */    71,   67,   14,   83,   83,   81,   92,   69,   70,   24,
 /*   420 */    72,   73,   13,   75,   60,   42,    3,   83,   24,   81,
 /*   430 */    14,    3,   92,   69,   70,    3,   72,   73,   24,   75,
 /*   440 */    60,   24,   11,    3,   24,   81,   17,   85,   88,   69,
 /*   450 */    70,   77,   72,   73,   60,   75,   71,   22,   92,   14,
 /*   460 */    82,   81,   21,   69,   70,   83,   72,   73,   60,   75,
 /*   470 */    84,   87,   17,   80,   10,   81,   80,   69,   70,   93,
 /*   480 */    72,   73,   60,   75,   24,   93,   93,   77,   93,   81,
 /*   490 */    93,   69,   70,   93,   72,   73,   93,   75,   60,   93,
 /*   500 */    93,   93,   93,   81,   93,   93,   93,   69,   70,   93,
 /*   510 */    72,   73,   93,   75,   60,   93,   93,   93,   93,   81,
 /*   520 */    93,   93,   93,   69,   70,   93,   72,   73,   93,   75,
 /*   530 */    93,   93,   93,   93,   93,   81,
);
    const YY_SHIFT_USE_DFLT = -20;
    const YY_SHIFT_MAX = 118;
    static public $yy_shift_ofst = array(
 /*     0 */    79,    5,   -6,   -6,   -6,   -6,   98,   39,   39,   98,
 /*    10 */    39,   39,   48,   39,   39,   39,   39,   39,   39,   39,
 /*    20 */    39,   39,   39,   39,   39,  161,  161,  161,   60,   49,
 /*    30 */   225,   77,   77,  141,  221,   79,  -19,   16,  209,  273,
 /*    40 */   284,  120,  308,  329,  329,  329,  308,  329,  308,  329,
 /*    50 */   435,  441,  445,  441,  435,  180,  317,   54,  -17,  143,
 /*    60 */   242,  305,  187,  149,  261,  365,  353,  323,  323,  173,
 /*    70 */   319,  200,  352,  435,  464,  435,  435,  435,  460,   42,
 /*    80 */   455,  398,  -20,  -20,  170,  148,  108,   29,   29,  107,
 /*    90 */   233,  177,  146,  252,   53,  220,  159,   23,   42,  420,
 /*   100 */   428,  416,  423,  432,  414,  440,  429,  431,  417,  241,
 /*   110 */   409,  404,  351,  314,  274,  377,  402,  395,  383,
);
    const YY_REDUCE_USE_DFLT = -60;
    const YY_REDUCE_MAX = 83;
    static public $yy_reduce_ofst = array(
 /*     0 */    61,   28,  109,   73,  155,   91,  162,  232,  246,  199,
 /*    10 */   213,  260,  185,  306,  438,  422,  348,  290,  380,  408,
 /*    20 */   394,  454,  364,  334,  320,  206,  175,  -59,   71,  293,
 /*    30 */   -41,  135,  -15,  230,  251,  294,  133,  -54,  302,  133,
 /*    40 */   133,  324,  211,   12,  231,  330,  340,  344,  310,  331,
 /*    50 */   312,  339,  303,   78,   36,  384,  384,  366,  382,  366,
 /*    60 */   360,  360,  366,  366,  360,  385,  374,  360,  360,  385,
 /*    70 */   378,  385,  385,  386,  396,  386,  386,  386,  410,  393,
 /*    80 */   362,    3,  201,  -52,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 21 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 22 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 23 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 24 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 26 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 27 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 28 */ array(1, 14, 36, 42, 53, ),
        /* 29 */ array(10, 17, 19, 21, 22, ),
        /* 30 */ array(1, 14, 36, 42, 53, ),
        /* 31 */ array(12, 15, 22, ),
        /* 32 */ array(12, 15, 22, ),
        /* 33 */ array(12, 14, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 36 */ array(20, 24, 40, 44, 53, ),
        /* 37 */ array(7, 8, 19, 41, ),
        /* 38 */ array(9, 20, 24, 53, ),
        /* 39 */ array(20, 24, 44, 53, ),
        /* 40 */ array(24, 44, 53, ),
        /* 41 */ array(1, 37, 53, ),
        /* 42 */ array(1, 53, ),
        /* 43 */ array(24, 53, ),
        /* 44 */ array(24, 53, ),
        /* 45 */ array(24, 53, ),
        /* 46 */ array(1, 53, ),
        /* 47 */ array(24, 53, ),
        /* 48 */ array(1, 53, ),
        /* 49 */ array(24, 53, ),
        /* 50 */ array(22, ),
        /* 51 */ array(21, ),
        /* 52 */ array(14, ),
        /* 53 */ array(21, ),
        /* 54 */ array(22, ),
        /* 55 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 56 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 57 */ array(1, 51, 53, ),
        /* 58 */ array(20, 24, 53, ),
        /* 59 */ array(1, 46, 53, ),
        /* 60 */ array(11, 34, 35, ),
        /* 61 */ array(18, 34, 35, ),
        /* 62 */ array(1, 5, 53, ),
        /* 63 */ array(1, 37, 53, ),
        /* 64 */ array(3, 34, 35, ),
        /* 65 */ array(3, 21, ),
        /* 66 */ array(14, 24, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(34, 35, ),
        /* 69 */ array(3, 21, ),
        /* 70 */ array(12, 15, ),
        /* 71 */ array(3, 21, ),
        /* 72 */ array(3, 21, ),
        /* 73 */ array(22, ),
        /* 74 */ array(10, ),
        /* 75 */ array(22, ),
        /* 76 */ array(22, ),
        /* 77 */ array(22, ),
        /* 78 */ array(24, ),
        /* 79 */ array(10, ),
        /* 80 */ array(17, ),
        /* 81 */ array(14, ),
        /* 82 */ array(),
        /* 83 */ array(),
        /* 84 */ array(10, 17, 23, ),
        /* 85 */ array(10, 17, 20, ),
        /* 86 */ array(3, 22, ),
        /* 87 */ array(10, 17, ),
        /* 88 */ array(10, 17, ),
        /* 89 */ array(16, 18, ),
        /* 90 */ array(13, 16, ),
        /* 91 */ array(24, ),
        /* 92 */ array(24, ),
        /* 93 */ array(11, ),
        /* 94 */ array(20, ),
        /* 95 */ array(3, ),
        /* 96 */ array(24, ),
        /* 97 */ array(3, ),
        /* 98 */ array(10, ),
        /* 99 */ array(24, ),
        /* 100 */ array(3, ),
        /* 101 */ array(14, ),
        /* 102 */ array(3, ),
        /* 103 */ array(3, ),
        /* 104 */ array(24, ),
        /* 105 */ array(3, ),
        /* 106 */ array(17, ),
        /* 107 */ array(11, ),
        /* 108 */ array(24, ),
        /* 109 */ array(43, ),
        /* 110 */ array(13, ),
        /* 111 */ array(24, ),
        /* 112 */ array(23, ),
        /* 113 */ array(11, ),
        /* 114 */ array(16, ),
        /* 115 */ array(11, ),
        /* 116 */ array(3, ),
        /* 117 */ array(24, ),
        /* 118 */ array(42, ),
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
);
    static public $yy_default = array(
 /*     0 */   331,  331,  331,  331,  331,  331,  317,  293,  293,  331,
 /*    10 */   293,  293,  331,  331,  331,  331,  331,  331,  331,  331,
 /*    20 */   331,  331,  331,  331,  331,  331,  331,  331,  331,  240,
 /*    30 */   331,  273,  267,  331,  240,  213,  277,  246,  331,  277,
 /*    40 */   277,  331,  331,  331,  331,  331,  331,  331,  331,  331,
 /*    50 */   263,  240,  331,  240,  262,  301,  301,  331,  331,  331,
 /*    60 */   331,  331,  331,  331,  331,  331,  331,  303,  299,  331,
 /*    70 */   287,  331,  331,  264,  277,  265,  268,  284,  331,  277,
 /*    80 */   247,  331,  296,  296,  245,  245,  331,  245,  331,  331,
 /*    90 */   331,  331,  331,  331,  331,  331,  331,  331,  266,  331,
 /*   100 */   331,  331,  331,  331,  331,  331,  331,  331,  331,  331,
 /*   110 */   331,  331,  318,  331,  292,  331,  331,  331,  331,  219,
 /*   120 */   231,  272,  300,  271,  270,  258,  269,  218,  256,  275,
 /*   130 */   276,  311,  310,  309,  308,  298,  220,  313,  312,  214,
 /*   140 */   233,  230,  249,  248,  255,  217,  234,  260,  216,  242,
 /*   150 */   250,  235,  327,  244,  307,  237,  236,  328,  259,  243,
 /*   160 */   329,  295,  330,  215,  302,  283,  290,  278,  294,  280,
 /*   170 */   288,  282,  238,  274,  257,  281,  254,  253,  322,  261,
 /*   180 */   326,  325,  323,  297,  252,  241,  251,  321,  225,  285,
 /*   190 */   232,  291,  223,  229,  222,  289,  305,  304,  324,  221,
 /*   200 */   320,  224,  315,  226,  239,  279,  319,  227,  314,  228,
 /*   210 */   286,  316,  306,
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
    const YYNSTATE = 213;
    const YYNRULE = 118;
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
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'NULL',        
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
 /*  34 */ "expr ::= exprs modifier modparameters",
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
 /*  58 */ "value ::= NULL",
 /*  59 */ "value ::= OPENP expr CLOSEP",
 /*  60 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  61 */ "variable ::= DOLLAR varvar AT ID",
 /*  62 */ "variable ::= object",
 /*  63 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  64 */ "vararraydefs ::=",
 /*  65 */ "vararraydef ::= DOT expr",
 /*  66 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  67 */ "varvar ::= varvarele",
 /*  68 */ "varvar ::= varvar varvarele",
 /*  69 */ "varvarele ::= ID",
 /*  70 */ "varvarele ::= LDEL expr RDEL",
 /*  71 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  72 */ "objectchain ::= objectelement",
 /*  73 */ "objectchain ::= objectchain objectelement",
 /*  74 */ "objectelement ::= PTR ID vararraydefs",
 /*  75 */ "objectelement ::= PTR method",
 /*  76 */ "function ::= ID OPENP params CLOSEP",
 /*  77 */ "method ::= ID OPENP params CLOSEP",
 /*  78 */ "params ::= expr COMMA params",
 /*  79 */ "params ::= expr",
 /*  80 */ "params ::=",
 /*  81 */ "modifier ::= VERT ID",
 /*  82 */ "modparameters ::= modparameters modparameter",
 /*  83 */ "modparameters ::=",
 /*  84 */ "modparameter ::= COLON expr",
 /*  85 */ "ifexprs ::= ifexpr",
 /*  86 */ "ifexprs ::= NOT ifexprs",
 /*  87 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  88 */ "ifexpr ::= expr",
 /*  89 */ "ifexpr ::= expr ifcond expr",
 /*  90 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  91 */ "ifcond ::= EQUALS",
 /*  92 */ "ifcond ::= NOTEQUALS",
 /*  93 */ "ifcond ::= GREATERTHAN",
 /*  94 */ "ifcond ::= LESSTHAN",
 /*  95 */ "ifcond ::= GREATEREQUAL",
 /*  96 */ "ifcond ::= LESSEQUAL",
 /*  97 */ "ifcond ::= IDENTITY",
 /*  98 */ "ifcond ::= NONEIDENTITY",
 /*  99 */ "lop ::= LAND",
 /* 100 */ "lop ::= LOR",
 /* 101 */ "array ::= OPENB arrayelements CLOSEB",
 /* 102 */ "arrayelements ::= arrayelement",
 /* 103 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 104 */ "arrayelements ::=",
 /* 105 */ "arrayelement ::= expr",
 /* 106 */ "arrayelement ::= expr APTR expr",
 /* 107 */ "arrayelement ::= ID APTR expr",
 /* 108 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 109 */ "doublequoted ::= doublequotedcontent",
 /* 110 */ "doublequotedcontent ::= variable",
 /* 111 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 112 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 113 */ "doublequotedcontent ::= OTHER",
 /* 114 */ "text ::= text textelement",
 /* 115 */ "text ::= textelement",
 /* 116 */ "textelement ::= OTHER",
 /* 117 */ "textelement ::= LDEL",
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
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 8 ),
  array( 'lhs' => 73, 'rhs' => 5 ),
  array( 'lhs' => 73, 'rhs' => 9 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 6 ),
  array( 'lhs' => 73, 'rhs' => 7 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 0 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
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
        36 => 0,
        42 => 0,
        43 => 0,
        44 => 0,
        57 => 0,
        58 => 0,
        62 => 0,
        102 => 0,
        1 => 1,
        33 => 1,
        35 => 1,
        40 => 1,
        41 => 1,
        67 => 1,
        85 => 1,
        109 => 1,
        115 => 1,
        116 => 1,
        117 => 1,
        2 => 2,
        63 => 2,
        108 => 2,
        114 => 2,
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
        79 => 24,
        105 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
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
        59 => 59,
        60 => 60,
        61 => 61,
        64 => 64,
        83 => 64,
        65 => 65,
        66 => 66,
        68 => 68,
        69 => 69,
        70 => 70,
        87 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        80 => 80,
        81 => 81,
        82 => 82,
        84 => 84,
        86 => 86,
        88 => 88,
        89 => 89,
        90 => 89,
        91 => 91,
        92 => 92,
        93 => 93,
        94 => 94,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        99 => 99,
        100 => 100,
        101 => 101,
        103 => 103,
        104 => 104,
        106 => 106,
        107 => 107,
        110 => 110,
        111 => 111,
        112 => 112,
        113 => 113,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1565 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1568 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1571 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1577 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1580 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1583 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1586 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1589 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1595 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1601 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1604 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1607 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1610 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1613 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1616 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1619 "internal.templateparser.php"
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
#line 1634 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1637 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1640 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1643 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1646 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1649 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1652 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1655 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1658 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1661 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1664 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1667 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1670 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1673 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1676 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
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
#line 1690 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1693 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1696 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1699 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1702 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "''";     }
#line 1705 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1708 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r50(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1711 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1714 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1717 "internal.templateparser.php"
#line 249 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1720 "internal.templateparser.php"
#line 251 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1723 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1726 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1729 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1732 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r60(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1736 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1739 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r64(){return;    }
#line 1742 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1745 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1748 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1751 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1754 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1757 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1760 "internal.templateparser.php"
#line 303 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1763 "internal.templateparser.php"
#line 305 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1766 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1769 "internal.templateparser.php"
#line 310 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1772 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r76(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1781 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1784 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1787 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r80(){ return;    }
#line 1790 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1793 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1796 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1799 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1802 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1805 "internal.templateparser.php"
#line 363 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1808 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '==';    }
#line 1811 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '!=';    }
#line 1814 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '>';    }
#line 1817 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '<';    }
#line 1820 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '>=';    }
#line 1823 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '<=';    }
#line 1826 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '===';    }
#line 1829 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '!==';    }
#line 1832 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '&&';    }
#line 1835 "internal.templateparser.php"
#line 376 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '||';    }
#line 1838 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r101(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1841 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r103(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1844 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r104(){ return;     }
#line 1847 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r106(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1850 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1853 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1856 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1859 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1862 "internal.templateparser.php"
#line 392 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1865 "internal.templateparser.php"

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
#line 1982 "internal.templateparser.php"
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
#line 2007 "internal.templateparser.php"
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
