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
    const YY_NO_ACTION = 306;
    const YY_ACCEPT_ACTION = 305;
    const YY_ERROR_ACTION = 304;

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
 /*     0 */   195,  194,   36,   87,  189,    3,   19,   81,  154,  291,
 /*    10 */   173,  127,  141,  130,  129,   89,   13,  121,  147,  153,
 /*    20 */   180,  181,  184,  183,  182,  196,   35,  179,  120,   86,
 /*    30 */   156,   70,   25,   16,   75,  127,   93,  130,  187,   89,
 /*    40 */   157,  121,  147,  174,  195,  194,  195,  194,   36,   69,
 /*    50 */   133,   64,  120,   67,   97,   98,  166,  127,  142,  130,
 /*    60 */   129,   89,  163,  121,  147,  153,  180,  181,  184,  183,
 /*    70 */   182,  196,  162,  123,  120,   23,   25,    2,   25,  185,
 /*    80 */    44,   43,  149,  127,  149,   44,   52,   88,   49,  121,
 /*    90 */   147,   99,  177,  195,  194,  127,  192,  130,  187,   89,
 /*   100 */     4,  121,  147,   32,  191,  101,  173,  178,   36,   13,
 /*   110 */   126,  128,   91,   82,  107,   98,  166,  127,   22,  130,
 /*   120 */   129,   89,  118,  121,  147,   25,   36,  141,  123,   16,
 /*   130 */    23,   65,    2,  173,  120,  127,   40,  130,  129,   89,
 /*   140 */    17,  121,  147,   12,  195,  194,   95,  177,  189,  123,
 /*   150 */    92,   23,  120,    6,   42,    4,   16,   43,   32,  191,
 /*   160 */   144,  116,   24,   46,   33,  158,  172,   99,  177,  150,
 /*   170 */   140,  127,   31,  142,  108,   90,   25,  121,  147,   32,
 /*   180 */   191,  127,  105,  130,  129,   89,   11,  121,  147,  113,
 /*   190 */   127,  195,  194,  100,   83,  189,  121,  147,  126,  128,
 /*   200 */    80,   45,   57,  151,  148,  131,    8,   13,  123,  174,
 /*   210 */    23,  127,    6,  130,  187,   89,   41,  121,  147,   17,
 /*   220 */    76,  171,   12,   25,  111,   17,   27,  177,   12,  174,
 /*   230 */   111,  173,  167,   19,   54,  195,  194,  173,   32,  191,
 /*   240 */    96,  190,  145,  127,  122,  130,  129,   89,  103,  121,
 /*   250 */   147,   84,  141,   14,   16,  143,  132,  173,  165,   54,
 /*   260 */    16,  305,   37,  138,  136,  126,  128,   25,  127,  157,
 /*   270 */   130,  129,   89,   55,  121,  147,   48,   38,  195,  194,
 /*   280 */    16,   54,  127,  114,  130,  129,   89,    9,  121,  147,
 /*   290 */   127,  161,  130,  129,   89,   78,  121,  147,  142,  123,
 /*   300 */    66,  162,   20,   18,  174,  117,  107,   43,   24,  127,
 /*   310 */    25,  130,  129,   89,   44,  121,  147,   99,  177,  195,
 /*   320 */   194,   11,   94,   61,  146,   79,  158,  172,  100,   32,
 /*   330 */   191,   21,  127,  107,  130,  129,   89,   68,  121,  147,
 /*   340 */    17,   91,  107,   12,  188,   56,  127,   22,  130,  129,
 /*   350 */    89,   25,  121,  147,  127,   53,  130,  129,   89,  160,
 /*   360 */   121,  147,  107,   24,  127,  115,  130,  129,   89,  124,
 /*   370 */   121,  147,   60,  193,   17,  104,   15,   12,   47,  111,
 /*   380 */    85,  127,   19,  130,  129,   89,  173,  121,  147,   59,
 /*   390 */   109,  107,  126,  128,    7,  119,   17,   77,  127,   12,
 /*   400 */   130,  129,   89,   71,  121,  147,   73,  158,  176,   16,
 /*   410 */   155,   63,  127,   10,  130,  129,   89,   62,  121,  147,
 /*   420 */   127,   58,  130,  129,   89,  135,  121,  147,  221,   28,
 /*   430 */   127,  168,  130,  129,   89,   11,  121,  147,  137,  136,
 /*   440 */    50,  175,  100,  168,  103,  106,    1,  110,  159,   26,
 /*   450 */   170,   29,  195,  194,  195,  194,   30,   21,  172,   72,
 /*   460 */   158,   39,  169,  168,  125,  168,   74,  158,  112,  139,
 /*   470 */   168,   34,   51,  164,    7,  168,    5,  134,  107,  172,
 /*   480 */    92,  179,  186,  152,   25,   20,   25,  122,  196,  196,
 /*   490 */   102,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   54,   16,   11,   18,   20,   59,   72,   16,
 /*    10 */    24,   63,    1,   65,   66,   67,   23,   69,   70,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   54,   74,   80,   76,
 /*    30 */    77,   59,   39,   47,   55,   63,   57,   65,   66,   67,
 /*    40 */    63,   69,   70,   64,    7,    8,    7,    8,   54,   53,
 /*    50 */    56,   53,   80,   59,   60,   83,   84,   63,   47,   65,
 /*    60 */    66,   67,   85,   69,   70,   26,   27,   28,   29,   30,
 /*    70 */    31,   32,    1,    6,   80,    8,   39,   10,   39,    3,
 /*    80 */    14,   14,   86,   63,   86,   14,   54,   67,   24,   69,
 /*    90 */    70,   24,   25,    7,    8,   63,    3,   65,   66,   67,
 /*   100 */    33,   69,   70,   36,   37,   63,   24,   36,   54,   23,
 /*   110 */    34,   35,   41,   59,   21,   83,   84,   63,   47,   65,
 /*   120 */    66,   67,   40,   69,   70,   39,   54,    1,    6,   47,
 /*   130 */     8,   59,   10,   24,   80,   63,   14,   65,   66,   67,
 /*   140 */    12,   69,   70,   15,    7,    8,   24,   25,   11,    6,
 /*   150 */    22,    8,   80,   10,   24,   33,   47,   14,   36,   37,
 /*   160 */     1,    2,   68,    4,   73,   74,   75,   24,   25,   43,
 /*   170 */     3,   63,   54,   47,   56,   67,   39,   69,   70,   36,
 /*   180 */    37,   63,   18,   65,   66,   67,   10,   69,   70,   22,
 /*   190 */    63,    7,    8,   17,   67,   11,   69,   70,   34,   35,
 /*   200 */    55,   42,   54,   44,   45,   46,   47,   23,    6,   64,
 /*   210 */     8,   63,   10,   65,   66,   67,   14,   69,   70,   12,
 /*   220 */    55,   11,   15,   39,   17,   12,   24,   25,   15,   64,
 /*   230 */    17,   24,   84,   20,   54,    7,    8,   24,   36,   37,
 /*   240 */    24,   13,    9,   63,   79,   65,   66,   67,   19,   69,
 /*   250 */    70,   38,    1,   20,   47,   56,    5,   24,   78,   54,
 /*   260 */    47,   49,   50,   51,   52,   34,   35,   39,   63,   63,
 /*   270 */    65,   66,   67,   54,   69,   70,   14,   71,    7,    8,
 /*   280 */    47,   54,   63,   78,   65,   66,   67,   16,   69,   70,
 /*   290 */    63,   85,   65,   66,   67,   55,   69,   70,   47,    6,
 /*   300 */    54,    1,   17,   10,   64,   78,   21,   14,   68,   63,
 /*   310 */    39,   65,   66,   67,   14,   69,   70,   24,   25,    7,
 /*   320 */     8,   10,   57,   54,    3,   73,   74,   75,   17,   36,
 /*   330 */    37,   20,   63,   21,   65,   66,   67,   54,   69,   70,
 /*   340 */    12,   41,   21,   15,    3,   54,   63,   47,   65,   66,
 /*   350 */    67,   39,   69,   70,   63,   54,   65,   66,   67,   77,
 /*   360 */    69,   70,   21,   68,   63,   14,   65,   66,   67,   11,
 /*   370 */    69,   70,   54,    3,   12,   24,   81,   15,   24,   17,
 /*   380 */    58,   63,   20,   65,   66,   67,   24,   69,   70,   54,
 /*   390 */    24,   21,   34,   35,   10,    3,   12,   17,   63,   15,
 /*   400 */    65,   66,   67,   54,   69,   70,   73,   74,   11,   47,
 /*   410 */    41,   54,   63,   16,   65,   66,   67,   61,   69,   70,
 /*   420 */    63,   54,   65,   66,   67,    3,   69,   70,    3,   61,
 /*   430 */    63,   75,   65,   66,   67,   10,   69,   70,   51,   52,
 /*   440 */    14,    3,   17,   75,   19,   62,   21,   22,    3,   61,
 /*   450 */     3,   61,    7,    8,    7,    8,   61,   20,   75,   73,
 /*   460 */    74,   61,   11,   75,   24,   75,   73,   74,   24,    3,
 /*   470 */    75,   58,   24,   24,   10,   75,   82,   86,   21,   75,
 /*   480 */    22,   74,   72,   64,   39,   17,   39,   79,   87,   87,
 /*   490 */    63,
);
    const YY_SHIFT_USE_DFLT = -15;
    const YY_SHIFT_MAX = 118;
    static public $yy_shift_ofst = array(
 /*     0 */   159,  122,   67,   67,   67,   67,  143,  143,  202,  143,
 /*    10 */   143,  143,  143,  143,  143,  143,  143,  143,  143,  143,
 /*    20 */   143,  143,  143,  293,  293,  293,  213,  425,  362,  207,
 /*    30 */   207,  312,  300,  128,  285,   -7,   39,  159,   71,  233,
 /*    40 */    82,   82,  384,   82,   82,   11,   11,  328,  109,  328,
 /*    50 */   109,  457,  184,  228,  271,  445,  137,   86,  447,   37,
 /*    60 */    37,   37,  -14,   37,  126,  164,   37,   76,   37,  251,
 /*    70 */   358,   37,  328,  328,  328,  341,  370,  351,  321,  328,
 /*    80 */    93,  231,  231,  229,   66,  468,  458,  262,  229,  229,
 /*    90 */   229,   66,  130,  -15,  -15,  311,  167,  -13,  397,  176,
 /*   100 */   380,  369,  422,  440,  464,  426,  392,  366,  438,  437,
 /*   110 */   448,  449,  466,  444,  451,  354,  216,  210,   64,
);
    const YY_REDUCE_USE_DFLT = -65;
    const YY_REDUCE_MAX = 94;
    static public $yy_reduce_ofst = array(
 /*     0 */   212,   -6,  -28,   72,  -52,   54,   32,  205,  118,  180,
 /*    10 */   148,  227,  318,  246,  269,  357,  367,  301,  291,  335,
 /*    20 */   349,  283,  219,   20,  108,  127,   91,  -21,   91,   91,
 /*    30 */   252,  240,  206,  -47,  165,  295,  295,  387,  -23,  383,
 /*    40 */   388,  368,  386,  390,  395,   -2,   -4,  333,  356,  393,
 /*    50 */   400,  145,   94,   94,   94,   94,   94,   94,   94,   94,
 /*    60 */    94,   94,  404,   94,  391,  394,   94,  394,   94,  391,
 /*    70 */   394,   94,  407,  407,  407,  419,  419,  410,  419,  407,
 /*    80 */   419,  394,  394,  265,  427,  408,  282,  199,  265,  265,
 /*    90 */   265,   42,  -64,  413,  322,
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
        /* 27 */ array(3, 10, 17, 19, 21, 22, ),
        /* 28 */ array(12, 15, 17, 20, 24, 47, ),
        /* 29 */ array(12, 15, 17, 24, 47, ),
        /* 30 */ array(12, 15, 17, 24, 47, ),
        /* 31 */ array(7, 8, 21, 39, ),
        /* 32 */ array(1, 14, 41, 47, ),
        /* 33 */ array(12, 15, 22, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 36 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 37 */ array(1, 2, 4, 42, 44, 45, 46, 47, ),
        /* 38 */ array(1, 14, 36, 41, 47, ),
        /* 39 */ array(9, 20, 24, 47, ),
        /* 40 */ array(24, 40, 47, ),
        /* 41 */ array(24, 40, 47, ),
        /* 42 */ array(10, 12, 15, ),
        /* 43 */ array(24, 40, 47, ),
        /* 44 */ array(24, 40, 47, ),
        /* 45 */ array(1, 47, ),
        /* 46 */ array(1, 47, ),
        /* 47 */ array(12, 15, ),
        /* 48 */ array(24, 47, ),
        /* 49 */ array(12, 15, ),
        /* 50 */ array(24, 47, ),
        /* 51 */ array(21, ),
        /* 52 */ array(7, 8, 11, 23, 39, ),
        /* 53 */ array(7, 8, 13, 39, ),
        /* 54 */ array(7, 8, 16, 39, ),
        /* 55 */ array(3, 7, 8, 39, ),
        /* 56 */ array(7, 8, 11, 39, ),
        /* 57 */ array(7, 8, 23, 39, ),
        /* 58 */ array(3, 7, 8, 39, ),
        /* 59 */ array(7, 8, 39, ),
        /* 60 */ array(7, 8, 39, ),
        /* 61 */ array(7, 8, 39, ),
        /* 62 */ array(20, 24, 47, ),
        /* 63 */ array(7, 8, 39, ),
        /* 64 */ array(1, 43, 47, ),
        /* 65 */ array(18, 34, 35, ),
        /* 66 */ array(7, 8, 39, ),
        /* 67 */ array(3, 34, 35, ),
        /* 68 */ array(7, 8, 39, ),
        /* 69 */ array(1, 5, 47, ),
        /* 70 */ array(11, 34, 35, ),
        /* 71 */ array(7, 8, 39, ),
        /* 72 */ array(12, 15, ),
        /* 73 */ array(12, 15, ),
        /* 74 */ array(12, 15, ),
        /* 75 */ array(3, 21, ),
        /* 76 */ array(3, 21, ),
        /* 77 */ array(14, 24, ),
        /* 78 */ array(3, 21, ),
        /* 79 */ array(12, 15, ),
        /* 80 */ array(3, 21, ),
        /* 81 */ array(34, 35, ),
        /* 82 */ array(34, 35, ),
        /* 83 */ array(19, ),
        /* 84 */ array(14, ),
        /* 85 */ array(17, ),
        /* 86 */ array(22, ),
        /* 87 */ array(14, ),
        /* 88 */ array(19, ),
        /* 89 */ array(19, ),
        /* 90 */ array(19, ),
        /* 91 */ array(14, ),
        /* 92 */ array(24, ),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(10, 17, 20, ),
        /* 96 */ array(3, 22, ),
        /* 97 */ array(16, 18, ),
        /* 98 */ array(11, 16, ),
        /* 99 */ array(10, 17, ),
        /* 100 */ array(17, ),
        /* 101 */ array(41, ),
        /* 102 */ array(3, ),
        /* 103 */ array(24, ),
        /* 104 */ array(10, ),
        /* 105 */ array(14, ),
        /* 106 */ array(3, ),
        /* 107 */ array(24, ),
        /* 108 */ array(3, ),
        /* 109 */ array(20, ),
        /* 110 */ array(24, ),
        /* 111 */ array(24, ),
        /* 112 */ array(3, ),
        /* 113 */ array(24, ),
        /* 114 */ array(11, ),
        /* 115 */ array(24, ),
        /* 116 */ array(24, ),
        /* 117 */ array(11, ),
        /* 118 */ array(24, ),
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
);
    static public $yy_default = array(
 /*     0 */   304,  304,  304,  304,  304,  304,  304,  268,  304,  268,
 /*    10 */   304,  268,  304,  304,  304,  304,  304,  304,  304,  304,
 /*    20 */   304,  304,  304,  304,  304,  304,  252,  244,  252,  252,
 /*    30 */   252,  221,  304,  247,  221,  276,  276,  197,  304,  304,
 /*    40 */   304,  304,  252,  304,  304,  304,  304,  252,  304,  252,
 /*    50 */   304,  221,  291,  304,  267,  304,  304,  291,  304,  225,
 /*    60 */   253,  217,  304,  277,  304,  304,  292,  304,  222,  304,
 /*    70 */   304,  272,  262,  243,  249,  304,  304,  304,  304,  247,
 /*    80 */   304,  274,  278,  231,  304,  234,  259,  304,  229,  228,
 /*    90 */   230,  304,  304,  271,  271,  244,  304,  304,  304,  244,
 /*   100 */   304,  304,  304,  304,  242,  304,  304,  304,  304,  304,
 /*   110 */   304,  304,  304,  304,  304,  304,  304,  304,  304,  215,
 /*   120 */   273,  237,  270,  236,  275,  269,  286,  235,  287,  227,
 /*   130 */   226,  205,  201,  223,  300,  216,  200,  199,  198,  213,
 /*   140 */   212,  302,  303,  224,  206,  218,  207,  238,  204,  301,
 /*   150 */   202,  203,  219,  279,  263,  297,  260,  296,  250,  298,
 /*   160 */   261,  295,  299,  294,  248,  266,  289,  290,  255,  265,
 /*   170 */   258,  264,  256,  257,  220,  208,  288,  239,  240,  251,
 /*   180 */   280,  281,  284,  283,  282,  214,  241,  227,  209,  246,
 /*   190 */   254,  245,  210,  211,  232,  233,  285,
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
    const YYNSTATE = 197;
    const YYNRULE = 107;
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
  'method',        'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'params',        'modparameter',
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
 /*  44 */ "value ::= ID COLON COLON method",
 /*  45 */ "value ::= ID COLON COLON ID",
 /*  46 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  47 */ "value ::= ID",
 /*  48 */ "value ::= BOOLEAN",
 /*  49 */ "value ::= OPENP expr CLOSEP",
 /*  50 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  51 */ "variable ::= DOLLAR varvar COLON ID",
 /*  52 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  53 */ "vararraydefs ::= vararraydef",
 /*  54 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  55 */ "vararraydefs ::=",
 /*  56 */ "vararraydef ::= DOT expr",
 /*  57 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  58 */ "varvar ::= varvarele",
 /*  59 */ "varvar ::= varvar varvarele",
 /*  60 */ "varvarele ::= ID",
 /*  61 */ "varvarele ::= LDEL expr RDEL",
 /*  62 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  63 */ "objectchain ::= objectelement",
 /*  64 */ "objectchain ::= objectchain objectelement",
 /*  65 */ "objectelement ::= PTR ID vararraydefs",
 /*  66 */ "objectelement ::= PTR method",
 /*  67 */ "function ::= ID OPENP params CLOSEP",
 /*  68 */ "method ::= ID OPENP params CLOSEP",
 /*  69 */ "params ::= expr COMMA params",
 /*  70 */ "params ::= expr",
 /*  71 */ "params ::=",
 /*  72 */ "modifier ::= VERT ID",
 /*  73 */ "modparameters ::= modparameters modparameter",
 /*  74 */ "modparameters ::=",
 /*  75 */ "modparameter ::= COLON expr",
 /*  76 */ "ifexprs ::= ifexpr",
 /*  77 */ "ifexprs ::= NOT ifexprs",
 /*  78 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  79 */ "ifexpr ::= expr",
 /*  80 */ "ifexpr ::= expr ifcond expr",
 /*  81 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  82 */ "ifcond ::= EQUALS",
 /*  83 */ "ifcond ::= NOTEQUALS",
 /*  84 */ "ifcond ::= GREATERTHAN",
 /*  85 */ "ifcond ::= LESSTHAN",
 /*  86 */ "ifcond ::= GREATEREQUAL",
 /*  87 */ "ifcond ::= LESSEQUAL",
 /*  88 */ "ifcond ::= IDENTITY",
 /*  89 */ "lop ::= LAND",
 /*  90 */ "lop ::= LOR",
 /*  91 */ "array ::= OPENP arrayelements CLOSEP",
 /*  92 */ "arrayelements ::= arrayelement",
 /*  93 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  94 */ "arrayelement ::= expr",
 /*  95 */ "arrayelement ::= expr APTR expr",
 /*  96 */ "arrayelement ::= array",
 /*  97 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  98 */ "doublequoted ::= doublequotedcontent",
 /*  99 */ "doublequotedcontent ::= variable",
 /* 100 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 101 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 102 */ "doublequotedcontent ::= OTHER",
 /* 103 */ "text ::= text textelement",
 /* 104 */ "text ::= textelement",
 /* 105 */ "textelement ::= OTHER",
 /* 106 */ "textelement ::= LDEL",
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
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 6 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
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
        48 => 0,
        92 => 0,
        1 => 1,
        29 => 1,
        30 => 1,
        35 => 1,
        36 => 1,
        53 => 1,
        58 => 1,
        76 => 1,
        98 => 1,
        102 => 1,
        104 => 1,
        105 => 1,
        106 => 1,
        2 => 2,
        54 => 2,
        97 => 2,
        103 => 2,
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
        70 => 21,
        94 => 21,
        96 => 21,
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
        45 => 45,
        46 => 46,
        47 => 47,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        55 => 55,
        74 => 55,
        56 => 56,
        57 => 57,
        59 => 59,
        60 => 60,
        61 => 61,
        78 => 61,
        62 => 62,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        75 => 75,
        77 => 77,
        79 => 79,
        80 => 80,
        81 => 80,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        86 => 86,
        87 => 87,
        88 => 88,
        89 => 89,
        90 => 90,
        91 => 91,
        93 => 93,
        95 => 95,
        99 => 99,
        100 => 100,
        101 => 101,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1491 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1494 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1497 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1502 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1505 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1508 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1511 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1514 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1520 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1523 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1526 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1529 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1532 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1535 "internal.templateparser.php"
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
#line 1550 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1553 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1556 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1559 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1562 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1565 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1568 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1571 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1574 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = array();    }
#line 1577 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1580 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1583 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1586 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1589 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1592 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1595 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1598 "internal.templateparser.php"
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
#line 1612 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1615 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1618 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1621 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1624 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1627 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1630 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1633 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1636 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1639 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r55(){return;    }
#line 1642 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1645 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1648 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r59(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1651 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r60(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1654 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1657 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1660 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1663 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1666 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1669 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1672 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r67(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1681 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1684 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1687 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r71(){ return;    }
#line 1690 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1693 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1696 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1699 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1702 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1705 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1708 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '==';    }
#line 1711 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '!=';    }
#line 1714 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '>';    }
#line 1717 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '<';    }
#line 1720 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '>=';    }
#line 1723 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '<=';    }
#line 1726 "internal.templateparser.php"
#line 335 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '===';    }
#line 1729 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '&&';    }
#line 1732 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '||';    }
#line 1735 "internal.templateparser.php"
#line 340 "internal.templateparser.y"
    function yy_r91(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1738 "internal.templateparser.php"
#line 342 "internal.templateparser.y"
    function yy_r93(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1741 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r95(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1744 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1747 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1750 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1753 "internal.templateparser.php"

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
#line 1870 "internal.templateparser.php"
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
#line 1895 "internal.templateparser.php"
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
