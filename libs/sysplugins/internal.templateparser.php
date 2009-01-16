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
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 140 "internal.templateparser.php"

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
    const TP_BACKTICK                       = 41;
    const TP_LITERALSTART                   = 42;
    const TP_LITERALEND                     = 43;
    const TP_LDELIMTAG                      = 44;
    const TP_RDELIMTAG                      = 45;
    const TP_PHP                            = 46;
    const TP_LDEL                           = 47;
    const YY_NO_ACTION = 295;
    const YY_ACCEPT_ACTION = 294;
    const YY_ERROR_ACTION = 293;

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
    const YY_SZ_ACTTAB = 480;
static public $yy_action = array(
 /*     0 */   144,  143,   51,   85,  162,    4,  144,  143,  151,  280,
 /*    10 */   137,  169,  163,  176,  172,   82,   16,  178,  186,  174,
 /*    20 */   173,  188,  138,  113,  112,  115,  104,  123,   36,  109,
 /*    30 */   130,  126,   24,   57,   93,  123,   76,  169,   24,  176,
 /*    40 */   172,   82,  170,  178,  186,  183,   35,   61,  177,   23,
 /*    50 */    25,   58,    6,  123,  119,  169,   41,  176,  154,   82,
 /*    60 */    98,  178,  186,  144,  143,   87,  102,  187,  294,   37,
 /*    70 */   141,  129,  119,  124,  164,   91,  148,  131,   31,  153,
 /*    80 */   125,  124,  174,  173,  188,  138,  113,  112,  115,   36,
 /*    90 */   106,  114,  144,  143,   60,   24,  162,   18,  169,  124,
 /*   100 */   176,  172,   82,   17,  178,  186,   22,  169,  177,   98,
 /*   110 */    25,   83,    1,  178,  186,  119,   41,   17,  120,  117,
 /*   120 */    22,   54,   94,  168,   24,   19,  102,  187,   45,  164,
 /*   130 */   169,   50,  176,  154,   82,    5,  178,  186,   31,  153,
 /*   140 */   169,   98,  176,  154,   82,   19,  178,  186,  167,  164,
 /*   150 */   169,  147,   18,   36,   80,  164,  178,  186,   70,   12,
 /*   160 */    91,  148,  169,   98,  176,  172,   82,   92,  178,  186,
 /*   170 */    46,   36,   18,  177,   62,   25,   74,    1,   18,  119,
 /*   180 */   169,   40,  176,  172,   82,  181,  178,  186,   33,  159,
 /*   190 */   160,   90,  187,  135,  107,  169,   44,  119,   43,   78,
 /*   200 */     5,  178,  186,   31,  153,  111,   73,  125,   51,  132,
 /*   210 */   177,  139,   25,  149,    6,  183,  171,  169,   42,  176,
 /*   220 */   172,   82,   21,  178,  186,   79,  164,  164,   29,  187,
 /*   230 */   175,   14,  152,   47,   48,  150,  140,  136,  134,    8,
 /*   240 */    31,  153,   32,  106,   99,  144,  143,  120,  117,   18,
 /*   250 */    18,  169,   51,  176,  172,   82,    7,  178,  186,   98,
 /*   260 */   110,  169,   88,  176,  172,   82,   15,  178,  186,   64,
 /*   270 */   103,  177,   43,  144,  143,   20,   97,   24,  169,   41,
 /*   280 */   176,  172,   82,   65,  178,  186,  120,  117,  145,  102,
 /*   290 */   187,  118,  169,   11,  176,  172,   82,   63,  178,  186,
 /*   300 */   157,   31,  153,  149,  184,   24,  169,   55,  176,  172,
 /*   310 */    82,   38,  178,  186,  120,  117,  169,   26,  176,  172,
 /*   320 */    82,   81,  178,  186,   68,  180,   17,   75,  159,   22,
 /*   330 */   146,   94,  105,  169,   19,  176,  172,   82,  164,  178,
 /*   340 */   186,   72,  127,   89,   69,  166,   17,   86,  156,   22,
 /*   350 */   183,   94,   84,  169,   59,  176,  172,   82,  164,  178,
 /*   360 */   186,   18,   67,  169,  101,  176,  172,   82,    9,  178,
 /*   370 */   186,  169,  158,  176,  172,   82,  160,  178,  186,   53,
 /*   380 */   121,   18,   77,  159,  160,  185,  108,   71,  169,   52,
 /*   390 */   176,  172,   82,  181,  178,  186,  183,   49,  169,   56,
 /*   400 */   176,  172,   82,   98,  178,  186,   43,  165,  169,   39,
 /*   410 */   176,  172,   82,   66,  178,  186,  213,   30,  144,  143,
 /*   420 */   161,  142,  146,    7,  144,  143,  146,   10,  179,    7,
 /*   430 */   146,   27,  110,   79,    2,  100,  144,  143,   34,   14,
 /*   440 */   162,  144,  143,  182,  146,  133,   23,  144,  143,   96,
 /*   450 */    24,   28,   16,  128,  129,   17,   24,   16,   22,   13,
 /*   460 */   175,   15,  155,   23,  146,   87,    3,   98,   24,  122,
 /*   470 */   160,   12,  166,   24,  116,   95,  193,  193,  193,   24,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   54,   16,   11,   18,    7,    8,   11,   16,
 /*    10 */     3,   63,   13,   65,   66,   67,   23,   69,   70,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   78,    1,   54,   22,
 /*    30 */    56,    5,   39,   59,   60,    1,   55,   63,   39,   65,
 /*    40 */    66,   67,    3,   69,   70,   64,   54,   53,    6,   68,
 /*    50 */     8,   59,   10,    1,   80,   63,   14,   65,   66,   67,
 /*    60 */    21,   69,   70,    7,    8,   22,   24,   25,   49,   50,
 /*    70 */    51,   52,   80,   47,   24,   83,   84,   43,   36,   37,
 /*    80 */    86,   47,   26,   27,   28,   29,   30,   31,   32,   54,
 /*    90 */    40,    3,    7,    8,   59,   39,   11,   47,   63,   47,
 /*   100 */    65,   66,   67,   12,   69,   70,   15,   63,    6,   21,
 /*   110 */     8,   67,   10,   69,   70,   80,   14,   12,   34,   35,
 /*   120 */    15,   54,   17,    3,   39,   20,   24,   25,   24,   24,
 /*   130 */    63,   54,   65,   66,   67,   33,   69,   70,   36,   37,
 /*   140 */    63,   21,   65,   66,   67,   20,   69,   70,   76,   24,
 /*   150 */    63,   84,   47,   54,   67,   24,   69,   70,   59,   17,
 /*   160 */    83,   84,   63,   21,   65,   66,   67,   24,   69,   70,
 /*   170 */    14,   54,   47,    6,   53,    8,   59,   10,   47,   80,
 /*   180 */    63,   14,   65,   66,   67,    1,   69,   70,   72,   73,
 /*   190 */    74,   24,   25,    1,    2,   63,    4,   80,   14,   67,
 /*   200 */    33,   69,   70,   36,   37,   24,   55,   86,   54,   56,
 /*   210 */     6,    9,    8,   63,   10,   64,    3,   63,   14,   65,
 /*   220 */    66,   67,   20,   69,   70,   41,   24,   24,   24,   25,
 /*   230 */    79,   47,   78,   14,   42,   85,   44,   45,   46,   47,
 /*   240 */    36,   37,   54,   40,   56,    7,    8,   34,   35,   47,
 /*   250 */    47,   63,   54,   65,   66,   67,   10,   69,   70,   21,
 /*   260 */    19,   63,   57,   65,   66,   67,   20,   69,   70,   54,
 /*   270 */    18,    6,   14,    7,    8,   10,   78,   39,   63,   14,
 /*   280 */    65,   66,   67,   54,   69,   70,   34,   35,   11,   24,
 /*   290 */    25,   11,   63,   16,   65,   66,   67,   54,   69,   70,
 /*   300 */    77,   36,   37,   63,    3,   39,   63,   54,   65,   66,
 /*   310 */    67,   71,   69,   70,   34,   35,   63,   61,   65,   66,
 /*   320 */    67,   58,   69,   70,   54,   85,   12,   72,   73,   15,
 /*   330 */    74,   17,   63,   63,   20,   65,   66,   67,   24,   69,
 /*   340 */    70,   55,    3,   57,   54,   73,   12,   75,   76,   15,
 /*   350 */    64,   17,   38,   63,   54,   65,   66,   67,   24,   69,
 /*   360 */    70,   47,   54,   63,   62,   65,   66,   67,   10,   69,
 /*   370 */    70,   63,   11,   65,   66,   67,   74,   69,   70,   54,
 /*   380 */     3,   47,   72,   73,   74,    3,   24,   55,   63,   54,
 /*   390 */    65,   66,   67,    1,   69,   70,   64,   24,   63,   54,
 /*   400 */    65,   66,   67,   21,   69,   70,   14,   41,   63,   61,
 /*   410 */    65,   66,   67,   61,   69,   70,    3,   61,    7,    8,
 /*   420 */     3,   24,   74,   10,    7,    8,   74,   16,   36,   10,
 /*   430 */    74,   61,   19,   41,   21,   22,    7,    8,   58,   47,
 /*   440 */    11,    7,    8,    3,   74,    3,   68,    7,    8,   24,
 /*   450 */    39,   61,   23,   51,   52,   12,   39,   23,   15,   81,
 /*   460 */    79,   20,   24,   68,   74,   22,   82,   21,   39,   86,
 /*   470 */    74,   17,   73,   39,   64,   63,   87,   87,   87,   39,
);
    const YY_SHIFT_USE_DFLT = -14;
    const YY_SHIFT_MAX = 111;
    static public $yy_shift_ofst = array(
 /*     0 */   192,  102,  167,  102,  102,  102,   42,   42,  204,   42,
 /*    10 */    42,   42,   42,   42,   42,   42,   42,   42,   42,   42,
 /*    20 */    42,   42,   42,  265,  265,  265,  314,  105,  334,  413,
 /*    30 */   334,  184,  238,  443,  142,   -7,   56,  192,  392,  202,
 /*    40 */    50,  203,  203,  203,   52,   91,  131,  131,   52,  446,
 /*    50 */   429,  411,   -1,  417,  434,  440,   85,  213,  280,  266,
 /*    60 */   252,   26,   34,  266,  266,  266,  125,  266,  266,  266,
 /*    70 */    84,   39,  382,  120,   84,   91,   88,   91,  241,  258,
 /*    80 */   241,  454,  241,  241,  258,  219,   43,  181,  -14,  -14,
 /*    90 */   246,  277,    7,  -13,  438,  366,  377,  361,  362,  301,
 /*   100 */   373,  442,  419,  156,   -3,  339,  104,  143,  441,  425,
 /*   110 */   397,  358,
);
    const YY_REDUCE_USE_DFLT = -53;
    const YY_REDUCE_MAX = 89;
    static public $yy_reduce_ofst = array(
 /*     0 */    19,   -8,  -26,  117,   35,   99,   77,  -52,  188,  198,
 /*    10 */   154,   67,  243,  270,  253,  290,  300,  335,  325,  308,
 /*    20 */   345,  215,  229,   44,  132,   87,  116,  116,  116,  286,
 /*    30 */   310,  240,  -19,  272,  151,  378,  378,  402,  150,  302,
 /*    40 */   256,  390,  370,  356,   -6,  255,  348,  352,  121,  332,
 /*    50 */   395,  395,  395,  395,  395,  395,  395,  384,  384,  395,
 /*    60 */   384,  383,  383,  395,  395,  395,  396,  395,  395,  395,
 /*    70 */   384,  410,  410,  410,  384,  399,  410,  399,  205,  412,
 /*    80 */   205,  381,  205,  205,  269,  153,   72,  223,  263,  380,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 42, 44, 45, 46, 47, ),
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
        /* 23 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 26 */ array(12, 15, 17, 20, 24, 38, 47, ),
        /* 27 */ array(12, 15, 17, 20, 24, 47, ),
        /* 28 */ array(12, 15, 17, 24, 47, ),
        /* 29 */ array(3, 10, 19, 21, 22, ),
        /* 30 */ array(12, 15, 17, 24, 47, ),
        /* 31 */ array(1, 14, 41, 47, ),
        /* 32 */ array(7, 8, 21, 39, ),
        /* 33 */ array(12, 15, 22, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 36 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 37 */ array(1, 2, 4, 42, 44, 45, 46, 47, ),
        /* 38 */ array(1, 14, 36, 41, 47, ),
        /* 39 */ array(9, 20, 24, 47, ),
        /* 40 */ array(24, 40, 47, ),
        /* 41 */ array(24, 40, 47, ),
        /* 42 */ array(24, 40, 47, ),
        /* 43 */ array(24, 40, 47, ),
        /* 44 */ array(1, 47, ),
        /* 45 */ array(12, 15, ),
        /* 46 */ array(24, 47, ),
        /* 47 */ array(24, 47, ),
        /* 48 */ array(1, 47, ),
        /* 49 */ array(21, ),
        /* 50 */ array(7, 8, 11, 23, 39, ),
        /* 51 */ array(7, 8, 16, 39, ),
        /* 52 */ array(7, 8, 13, 39, ),
        /* 53 */ array(3, 7, 8, 39, ),
        /* 54 */ array(7, 8, 23, 39, ),
        /* 55 */ array(3, 7, 8, 39, ),
        /* 56 */ array(7, 8, 11, 39, ),
        /* 57 */ array(3, 34, 35, ),
        /* 58 */ array(11, 34, 35, ),
        /* 59 */ array(7, 8, 39, ),
        /* 60 */ array(18, 34, 35, ),
        /* 61 */ array(1, 5, 47, ),
        /* 62 */ array(1, 43, 47, ),
        /* 63 */ array(7, 8, 39, ),
        /* 64 */ array(7, 8, 39, ),
        /* 65 */ array(7, 8, 39, ),
        /* 66 */ array(20, 24, 47, ),
        /* 67 */ array(7, 8, 39, ),
        /* 68 */ array(7, 8, 39, ),
        /* 69 */ array(7, 8, 39, ),
        /* 70 */ array(34, 35, ),
        /* 71 */ array(3, 21, ),
        /* 72 */ array(3, 21, ),
        /* 73 */ array(3, 21, ),
        /* 74 */ array(34, 35, ),
        /* 75 */ array(12, 15, ),
        /* 76 */ array(3, 21, ),
        /* 77 */ array(12, 15, ),
        /* 78 */ array(19, ),
        /* 79 */ array(14, ),
        /* 80 */ array(19, ),
        /* 81 */ array(17, ),
        /* 82 */ array(19, ),
        /* 83 */ array(19, ),
        /* 84 */ array(14, ),
        /* 85 */ array(14, ),
        /* 86 */ array(22, ),
        /* 87 */ array(24, ),
        /* 88 */ array(),
        /* 89 */ array(),
        /* 90 */ array(10, 20, ),
        /* 91 */ array(11, 16, ),
        /* 92 */ array(3, 22, ),
        /* 93 */ array(16, 18, ),
        /* 94 */ array(24, ),
        /* 95 */ array(41, ),
        /* 96 */ array(3, ),
        /* 97 */ array(11, ),
        /* 98 */ array(24, ),
        /* 99 */ array(3, ),
        /* 100 */ array(24, ),
        /* 101 */ array(3, ),
        /* 102 */ array(10, ),
        /* 103 */ array(14, ),
        /* 104 */ array(11, ),
        /* 105 */ array(3, ),
        /* 106 */ array(24, ),
        /* 107 */ array(24, ),
        /* 108 */ array(20, ),
        /* 109 */ array(24, ),
        /* 110 */ array(24, ),
        /* 111 */ array(10, ),
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
);
    static public $yy_default = array(
 /*     0 */   293,  293,  293,  293,  293,  293,  293,  257,  293,  257,
 /*    10 */   257,  293,  293,  293,  293,  293,  293,  293,  293,  293,
 /*    20 */   293,  293,  293,  293,  293,  293,  241,  241,  241,  233,
 /*    30 */   241,  293,  213,  236,  213,  265,  265,  189,  293,  293,
 /*    40 */   293,  293,  293,  293,  293,  241,  293,  293,  293,  213,
 /*    50 */   280,  256,  293,  293,  280,  293,  293,  293,  293,  281,
 /*    60 */   293,  293,  293,  261,  209,  242,  293,  217,  266,  214,
 /*    70 */   263,  293,  293,  293,  267,  238,  293,  236,  223,  293,
 /*    80 */   221,  226,  220,  222,  293,  293,  248,  293,  260,  260,
 /*    90 */   233,  293,  293,  293,  293,  293,  293,  293,  293,  293,
 /*   100 */   293,  293,  233,  293,  293,  293,  293,  293,  293,  293,
 /*   110 */   293,  251,  273,  272,  199,  274,  211,  276,  264,  262,
 /*   120 */   275,  205,  289,  291,  292,  290,  193,  208,  191,  192,
 /*   130 */   215,  194,  216,  207,  197,  198,  196,  204,  271,  210,
 /*   140 */   195,  190,  258,  224,  225,  277,  244,  279,  278,  285,
 /*   150 */   283,  253,  255,  234,  219,  237,  249,  252,  254,  239,
 /*   160 */   245,  247,  235,  243,  246,  286,  240,  250,  203,  227,
 /*   170 */   202,  206,  219,  269,  268,  259,  218,  228,  229,  232,
 /*   180 */   284,  288,  287,  212,  200,  201,  230,  231,  270,
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
    const YYNOCODE = 88;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 189;
    const YYNRULE = 104;
    const YYERRORSYMBOL = 48;
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
    1,  /*   BACKTICK => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
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
  '$',             'OTHER',         'LDELSLASH',     'RDEL',        
  'COMMENTSTART',  'COMMENTEND',    'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NOT',           'LAND',          'LOR',         
  'QUOTE',         'BOOLEAN',       'IN',            'ANDSYM',      
  'UNDERL',        'BACKTICK',      'LITERALSTART',  'LITERALEND',  
  'LDELIMTAG',     'RDELIMTAG',     'PHP',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'text',          'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'variable',    
  'attribute',     'exprs',         'array',         'value',       
  'math',          'object',        'function',      'doublequoted',
  'vararraydefs',  'vararraydef',   'varvarele',     'objectchain', 
  'objectelement',  'method',        'params',        'modparameter',
  'ifexpr',        'ifcond',        'lop',           'arrayelements',
  'arrayelement',  'doublequotedcontent',  'textelement', 
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
 /*   9 */ "template_element ::= OTHER",
 /*  10 */ "smartytag ::= LDEL expr attributes RDEL",
 /*  11 */ "smartytag ::= LDEL statement RDEL",
 /*  12 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  13 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  14 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  15 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  16 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  17 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  18 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  19 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  20 */ "foraction ::= EQUAL expr",
 /*  21 */ "foraction ::= INCDEC",
 /*  22 */ "attributes ::= attributes attribute",
 /*  23 */ "attributes ::= attribute",
 /*  24 */ "attributes ::=",
 /*  25 */ "attribute ::= SPACE ID EQUAL expr",
 /*  26 */ "statements ::= statement",
 /*  27 */ "statements ::= statements COMMA statement",
 /*  28 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  29 */ "expr ::= exprs",
 /*  30 */ "expr ::= array",
 /*  31 */ "exprs ::= value",
 /*  32 */ "exprs ::= UNIMATH value",
 /*  33 */ "exprs ::= expr math value",
 /*  34 */ "exprs ::= expr ANDSYM value",
 /*  35 */ "math ::= UNIMATH",
 /*  36 */ "math ::= MATH",
 /*  37 */ "value ::= value modifier modparameters",
 /*  38 */ "value ::= variable",
 /*  39 */ "value ::= NUMBER",
 /*  40 */ "value ::= object",
 /*  41 */ "value ::= function",
 /*  42 */ "value ::= SI_QSTR",
 /*  43 */ "value ::= QUOTE doublequoted QUOTE",
 /*  44 */ "value ::= ID",
 /*  45 */ "value ::= BOOLEAN",
 /*  46 */ "value ::= OPENP expr CLOSEP",
 /*  47 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  48 */ "variable ::= DOLLAR varvar COLON ID",
 /*  49 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  50 */ "vararraydefs ::= vararraydef",
 /*  51 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  52 */ "vararraydefs ::=",
 /*  53 */ "vararraydef ::= DOT expr",
 /*  54 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  55 */ "varvar ::= varvarele",
 /*  56 */ "varvar ::= varvar varvarele",
 /*  57 */ "varvarele ::= ID",
 /*  58 */ "varvarele ::= LDEL expr RDEL",
 /*  59 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  60 */ "objectchain ::= objectelement",
 /*  61 */ "objectchain ::= objectchain objectelement",
 /*  62 */ "objectelement ::= PTR ID",
 /*  63 */ "objectelement ::= PTR method",
 /*  64 */ "function ::= ID OPENP params CLOSEP",
 /*  65 */ "method ::= ID OPENP params CLOSEP",
 /*  66 */ "params ::= expr COMMA params",
 /*  67 */ "params ::= expr",
 /*  68 */ "params ::=",
 /*  69 */ "modifier ::= VERT ID",
 /*  70 */ "modparameters ::= modparameters modparameter",
 /*  71 */ "modparameters ::=",
 /*  72 */ "modparameter ::= COLON expr",
 /*  73 */ "ifexprs ::= ifexpr",
 /*  74 */ "ifexprs ::= NOT ifexprs",
 /*  75 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  76 */ "ifexpr ::= expr",
 /*  77 */ "ifexpr ::= expr ifcond expr",
 /*  78 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  79 */ "ifcond ::= EQUALS",
 /*  80 */ "ifcond ::= NOTEQUALS",
 /*  81 */ "ifcond ::= GREATERTHAN",
 /*  82 */ "ifcond ::= LESSTHAN",
 /*  83 */ "ifcond ::= GREATEREQUAL",
 /*  84 */ "ifcond ::= LESSEQUAL",
 /*  85 */ "ifcond ::= IDENTITY",
 /*  86 */ "lop ::= LAND",
 /*  87 */ "lop ::= LOR",
 /*  88 */ "array ::= OPENP arrayelements CLOSEP",
 /*  89 */ "arrayelements ::= arrayelement",
 /*  90 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  91 */ "arrayelement ::= expr",
 /*  92 */ "arrayelement ::= expr APTR expr",
 /*  93 */ "arrayelement ::= array",
 /*  94 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  95 */ "doublequoted ::= doublequotedcontent",
 /*  96 */ "doublequotedcontent ::= variable",
 /*  97 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /*  98 */ "doublequotedcontent ::= LDEL expr RDEL",
 /*  99 */ "doublequotedcontent ::= OTHER",
 /* 100 */ "text ::= text textelement",
 /* 101 */ "text ::= textelement",
 /* 102 */ "textelement ::= OTHER",
 /* 103 */ "textelement ::= LDEL",
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
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 5 ),
  array( 'lhs' => 52, 'rhs' => 5 ),
  array( 'lhs' => 52, 'rhs' => 11 ),
  array( 'lhs' => 52, 'rhs' => 8 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 0 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 0 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        31 => 0,
        38 => 0,
        39 => 0,
        40 => 0,
        41 => 0,
        42 => 0,
        45 => 0,
        89 => 0,
        1 => 1,
        29 => 1,
        30 => 1,
        35 => 1,
        36 => 1,
        50 => 1,
        55 => 1,
        73 => 1,
        95 => 1,
        99 => 1,
        101 => 1,
        102 => 1,
        103 => 1,
        2 => 2,
        51 => 2,
        94 => 2,
        100 => 2,
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
        23 => 21,
        67 => 21,
        91 => 21,
        93 => 21,
        22 => 22,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        32 => 32,
        33 => 33,
        34 => 34,
        37 => 37,
        43 => 43,
        44 => 44,
        46 => 46,
        47 => 47,
        48 => 48,
        49 => 49,
        52 => 52,
        71 => 52,
        53 => 53,
        54 => 54,
        56 => 56,
        57 => 57,
        58 => 58,
        75 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        63 => 62,
        64 => 64,
        65 => 65,
        66 => 66,
        68 => 68,
        69 => 69,
        70 => 70,
        72 => 72,
        74 => 74,
        76 => 76,
        77 => 77,
        78 => 77,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        86 => 86,
        87 => 87,
        88 => 88,
        90 => 90,
        92 => 92,
        96 => 96,
        97 => 97,
        98 => 98,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1468 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1471 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1474 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1479 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1482 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1485 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1488 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1491 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1497 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1500 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1503 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1506 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1509 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1512 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
                                                                if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					                           $this->_retvalue .= $this->yystack[$this->yyidx + -3]->minor . "(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor .");?>";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -3]->minor,'modifier')) {
                                                                      $this->_retvalue .= "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -3]->minor . "(array(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor ."),'modifier');?>";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1527 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1530 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1533 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1536 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1539 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1542 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1545 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1548 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1551 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = array();    }
#line 1554 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1557 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1560 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1563 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1566 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1569 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1572 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1575 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r37(){if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
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
#line 1589 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1592 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1595 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1598 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1601 "internal.templateparser.php"
#line 233 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1604 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1607 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r52(){return;    }
#line 1610 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1613 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1616 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1619 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1622 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1625 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1628 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1631 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1634 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1637 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r64(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1646 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1649 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1652 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r68(){ return;    }
#line 1655 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1658 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1661 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1664 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1667 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1670 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1673 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '==';    }
#line 1676 "internal.templateparser.php"
#line 324 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '!=';    }
#line 1679 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '>';    }
#line 1682 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '<';    }
#line 1685 "internal.templateparser.php"
#line 327 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '>=';    }
#line 1688 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '<=';    }
#line 1691 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '===';    }
#line 1694 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '&&';    }
#line 1697 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '||';    }
#line 1700 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r88(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1703 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r90(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1706 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r92(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1709 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1712 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1715 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1718 "internal.templateparser.php"

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
#line 53 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1835 "internal.templateparser.php"
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
#line 45 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1860 "internal.templateparser.php"
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
