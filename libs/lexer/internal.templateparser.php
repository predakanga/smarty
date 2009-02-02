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
    const TP_AT                             = 42;
    const TP_LITERALSTART                   = 43;
    const TP_LITERALEND                     = 44;
    const TP_LDELIMTAG                      = 45;
    const TP_RDELIMTAG                      = 46;
    const TP_PHP                            = 47;
    const TP_PHPSTART                       = 48;
    const TP_PHPEND                         = 49;
    const TP_XML                            = 50;
    const TP_LDEL                           = 51;
    const YY_NO_ACTION = 310;
    const YY_ACCEPT_ACTION = 309;
    const YY_ERROR_ACTION = 308;

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
    const YY_SZ_ACTTAB = 486;
static public $yy_action = array(
 /*     0 */    52,  185,  125,  118,  185,   59,   82,  138,  119,  152,
 /*    10 */   162,   92,   34,  163,   17,  144,  101,  143,  183,   23,
 /*    20 */   149,    2,  149,    6,  124,   46,  132,  160,   86,  192,
 /*    30 */    42,   70,  136,  135,  143,   84,   23,  142,    2,  225,
 /*    40 */     6,  180,   40,   91,    4,   20,   11,   26,   38,  151,
 /*    50 */   185,  167,   81,  109,  167,   96,  152,    1,   97,   53,
 /*    60 */   163,    4,  144,   11,   26,   38,  151,  149,  172,   39,
 /*    70 */   109,  159,  157,  156,   43,   28,  155,    9,  143,  149,
 /*    80 */    23,  143,   18,   23,    6,   18,   46,    6,  170,   45,
 /*    90 */   175,  152,   80,  158,   60,  163,   84,  144,   85,   27,
 /*   100 */   167,   46,  149,   24,  183,   14,   99,   11,   26,   38,
 /*   110 */   151,   26,   38,  151,  109,  152,  162,   15,   34,  163,
 /*   120 */    52,  144,   90,  141,  112,   67,  149,   30,   48,  152,
 /*   130 */   162,   20,   34,  163,  164,  144,  168,  170,   12,   52,
 /*   140 */   149,  186,  187,   69,   58,   78,  132,  184,  152,  162,
 /*   150 */    46,   34,  163,  180,  144,  136,  135,   77,   52,  149,
 /*   160 */    16,  186,  187,   71,  183,  132,   63,  152,  162,  108,
 /*   170 */    34,  163,  134,  144,   25,   65,  180,   48,  149,   31,
 /*   180 */   136,  135,   90,  193,  132,   16,  178,   12,   51,  183,
 /*   190 */   164,   20,   66,   55,   25,  136,  135,  152,  162,   88,
 /*   200 */    34,  163,  180,  144,   87,  150,  152,   90,  149,   76,
 /*   210 */   146,    5,  144,    6,  132,   46,   20,  149,   99,  113,
 /*   220 */   114,  110,  137,  131,  130,  129,  128,  152,  162,   15,
 /*   230 */    34,  163,  143,  144,   23,  152,   18,   57,  149,  153,
 /*   240 */    46,  144,  309,   32,  122,  133,  149,   83,  189,  177,
 /*   250 */    84,  106,  148,  182,   72,    7,   49,  139,  103,  194,
 /*   260 */     8,  105,   26,   38,  151,  152,  162,   87,   34,  163,
 /*   270 */   147,  144,   21,  104,   54,   22,  149,   16,  185,  186,
 /*   280 */   187,  183,  152,  162,  179,   34,  163,  161,  144,  104,
 /*   290 */   120,   96,  143,  149,  185,   10,   18,  196,  152,  162,
 /*   300 */    46,   34,  163,  172,  144,   87,  104,  147,   20,  149,
 /*   310 */    84,   13,   25,   98,  149,  152,  162,   21,   34,  163,
 /*   320 */    22,  144,   26,   38,  151,  174,  149,   73,  167,   41,
 /*   330 */    89,  197,  113,  114,  110,  137,  131,  130,  129,  128,
 /*   340 */    29,   95,  154,  193,  167,  183,  126,  102,  100,   87,
 /*   350 */   152,  162,   61,   34,  163,  116,  144,   56,  149,   33,
 /*   360 */    79,  149,   14,   68,  152,  162,   87,   34,  163,   94,
 /*   370 */   144,   36,   20,   62,  173,  149,  121,  169,  152,  162,
 /*   380 */   123,   34,  163,  176,  144,  147,  173,   37,  173,  149,
 /*   390 */   147,  171,  152,  162,   75,   34,  163,  165,  144,  194,
 /*   400 */    35,  150,  173,  149,  127,  133,  152,  162,  181,   34,
 /*   410 */   163,  190,  144,  188,  145,  173,   50,  149,   93,  191,
 /*   420 */    24,  193,  152,  162,    3,   34,  163,  111,  144,  166,
 /*   430 */   139,   87,   46,  149,   73,  117,  152,  162,   19,   34,
 /*   440 */   163,  115,  144,   47,   74,  195,   44,  149,   64,  203,
 /*   450 */   152,  162,  203,   34,  163,  107,  144,  203,  203,  203,
 /*   460 */   203,  149,  203,  203,  152,  162,  203,   34,  163,  203,
 /*   470 */   144,  203,  203,  203,  152,  149,  203,  203,  140,  203,
 /*   480 */   144,  203,  203,  203,  203,  149,
    );
    static public $yy_lookahead = array(
 /*     0 */    58,    1,   60,    9,    1,   63,   64,   24,    5,   67,
 /*    10 */    68,   67,   70,   71,   20,   73,   18,    6,   24,    8,
 /*    20 */    78,   10,   78,   12,    3,   14,   84,    1,    2,   11,
 /*    30 */     4,   59,   34,   35,    6,   24,    8,   37,   10,    3,
 /*    40 */    12,   69,   14,   22,   33,   51,   10,   36,   37,   38,
 /*    50 */     1,   51,   24,   17,   51,   19,   67,   21,   22,   70,
 /*    60 */    71,   33,   73,   10,   36,   37,   38,   78,   67,   43,
 /*    70 */    17,   45,   46,   47,   48,   74,   50,   51,    6,   78,
 /*    80 */     8,    6,   10,    8,   12,   10,   14,   12,    1,   14,
 /*    90 */    89,   67,   61,   44,   70,   71,   24,   73,   24,   24,
 /*   100 */    51,   14,   78,   72,   24,   17,   58,   10,   36,   37,
 /*   110 */    38,   36,   37,   38,   17,   67,   68,   20,   70,   71,
 /*   120 */    58,   73,   42,   36,    3,   63,   78,   62,   41,   67,
 /*   130 */    68,   51,   70,   71,   83,   73,   88,    1,   51,   58,
 /*   140 */    78,    7,    8,   59,   63,   61,   84,   13,   67,   68,
 /*   150 */    14,   70,   71,   69,   73,   34,   35,   62,   58,   78,
 /*   160 */    20,    7,    8,   63,   24,   84,   59,   67,   68,   66,
 /*   170 */    70,   71,   11,   73,   40,   77,   69,   41,   78,   39,
 /*   180 */    34,   35,   42,   80,   84,   20,    3,   51,   58,   24,
 /*   190 */    83,   51,   59,   63,   40,   34,   35,   67,   68,   24,
 /*   200 */    70,   71,   69,   73,   21,   11,   67,   42,   78,   16,
 /*   210 */    71,   18,   73,   12,   84,   14,   51,   78,   58,   25,
 /*   220 */    26,   27,   28,   29,   30,   31,   32,   67,   68,   20,
 /*   230 */    70,   71,    6,   73,    8,   67,   10,   57,   78,   71,
 /*   240 */    14,   73,   53,   54,   55,   56,   78,   87,   88,    3,
 /*   250 */    24,   14,   13,    3,   76,   16,   58,   79,   60,   81,
 /*   260 */    16,   24,   36,   37,   38,   67,   68,   21,   70,   71,
 /*   270 */    90,   73,   12,   58,   57,   15,   78,   20,    1,    7,
 /*   280 */     8,   24,   67,   68,    3,   70,   71,    3,   73,   58,
 /*   290 */     3,   19,    6,   78,    1,   10,   10,   82,   67,   68,
 /*   300 */    14,   70,   71,   67,   73,   21,   58,   90,   51,   78,
 /*   310 */    24,   23,   40,   82,   78,   67,   68,   12,   70,   71,
 /*   320 */    15,   73,   36,   37,   38,   89,   78,   22,   51,   14,
 /*   330 */    82,    3,   25,   26,   27,   28,   29,   30,   31,   32,
 /*   340 */    77,   58,   49,   80,   51,   24,    3,   67,   68,   21,
 /*   350 */    67,   68,   57,   70,   71,   58,   73,   57,   78,   65,
 /*   360 */    24,   78,   17,   17,   67,   68,   21,   70,   71,   58,
 /*   370 */    73,   65,   51,   65,   80,   78,    3,    3,   67,   68,
 /*   380 */     3,   70,   71,   58,   73,   90,   80,   65,   80,   78,
 /*   390 */    90,   41,   67,   68,   76,   70,   71,   58,   73,   81,
 /*   400 */    65,   11,   80,   78,   55,   56,   67,   68,   24,   70,
 /*   410 */    71,   11,   73,   58,   81,   80,   24,   78,   24,   90,
 /*   420 */    72,   80,   67,   68,   86,   70,   71,   58,   73,   69,
 /*   430 */    79,   21,   14,   78,   22,   60,   67,   68,   85,   70,
 /*   440 */    71,   58,   73,   75,   24,   75,   14,   78,   77,   91,
 /*   450 */    67,   68,   91,   70,   71,   58,   73,   91,   91,   91,
 /*   460 */    91,   78,   91,   91,   67,   68,   91,   70,   71,   91,
 /*   470 */    73,   91,   91,   91,   67,   78,   91,   91,   71,   91,
 /*   480 */    73,   91,   91,   91,   91,   78,
);
    const YY_SHIFT_USE_DFLT = -18;
    const YY_SHIFT_MAX = 109;
    static public $yy_shift_ofst = array(
 /*     0 */    26,   28,   11,   11,   11,   11,   72,   72,   72,   75,
 /*    10 */    72,   72,   72,   72,   72,   72,   72,   72,   72,   72,
 /*    20 */    72,  226,  226,  286,  286,  286,  136,   36,   87,  305,
 /*    30 */   345,  201,   26,  140,  272,  165,   -6,   80,  277,  277,
 /*    40 */   321,  321,  277,  277,  321,  321,  321,  412,  418,  410,
 /*    50 */   410,  194,  307,  134,  293,  161,   49,    3,   -2,  121,
 /*    60 */   154,    0,  257,  328,  260,  260,  284,  146,  237,  246,
 /*    70 */   183,  146,  412,  420,  285,  412,  432,   88,  -18,  -18,
 /*    80 */   -18,   97,  193,  239,   53,   21,   74,  175,  209,   18,
 /*    90 */   -17,  394,  350,  377,  374,  250,  384,  392,  400,  288,
 /*   100 */   373,  315,  287,  281,  244,  285,  336,  390,  343,  346,
);
    const YY_REDUCE_USE_DFLT = -59;
    const YY_REDUCE_MAX = 80;
    static public $yy_reduce_ofst = array(
 /*     0 */   189,  -58,  130,  100,   62,   81,  160,   48,  215,  198,
 /*    10 */   231,  248,  311,  355,  325,  339,  297,  383,  397,  369,
 /*    20 */   283,  -11,   24,  168,  407,  139,    1,   84,  236,  178,
 /*    30 */   107,  280,  349,  263,   31,  263,  103,  263,  295,  300,
 /*    40 */   294,  306,  180,  217,  308,  335,  322,  318,  -56,  133,
 /*    50 */   -28,  353,  353,  348,  329,  338,  329,  329,  338,  338,
 /*    60 */   348,  329,  341,  360,  351,  351,  360,  338,  368,  360,
 /*    70 */   360,  338,  333,  370,  371,  333,  375,   51,   65,   98,
 /*    80 */    95,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 43, 45, 46, 47, 48, 50, 51, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, ),
        /* 21 */ array(6, 8, 10, 14, 24, 36, 37, 38, ),
        /* 22 */ array(6, 8, 10, 14, 24, 36, 37, 38, ),
        /* 23 */ array(6, 10, 14, 24, 36, 37, 38, ),
        /* 24 */ array(6, 10, 14, 24, 36, 37, 38, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, ),
        /* 26 */ array(1, 14, 41, 51, ),
        /* 27 */ array(3, 10, 17, 19, 21, 22, ),
        /* 28 */ array(1, 14, 36, 41, 51, ),
        /* 29 */ array(12, 15, 22, ),
        /* 30 */ array(17, 21, ),
        /* 31 */ array(12, 14, ),
        /* 32 */ array(1, 2, 4, 43, 45, 46, 47, 48, 50, 51, ),
        /* 33 */ array(20, 24, 39, 42, 51, ),
        /* 34 */ array(7, 8, 19, 40, ),
        /* 35 */ array(20, 24, 42, 51, ),
        /* 36 */ array(9, 20, 24, 51, ),
        /* 37 */ array(24, 42, 51, ),
        /* 38 */ array(1, 51, ),
        /* 39 */ array(1, 51, ),
        /* 40 */ array(24, 51, ),
        /* 41 */ array(24, 51, ),
        /* 42 */ array(1, 51, ),
        /* 43 */ array(1, 51, ),
        /* 44 */ array(24, 51, ),
        /* 45 */ array(24, 51, ),
        /* 46 */ array(24, 51, ),
        /* 47 */ array(22, ),
        /* 48 */ array(14, ),
        /* 49 */ array(21, ),
        /* 50 */ array(21, ),
        /* 51 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 52 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 53 */ array(7, 8, 13, 40, ),
        /* 54 */ array(1, 49, 51, ),
        /* 55 */ array(11, 34, 35, ),
        /* 56 */ array(1, 44, 51, ),
        /* 57 */ array(1, 5, 51, ),
        /* 58 */ array(18, 34, 35, ),
        /* 59 */ array(3, 34, 35, ),
        /* 60 */ array(7, 8, 40, ),
        /* 61 */ array(1, 37, 51, ),
        /* 62 */ array(20, 24, 51, ),
        /* 63 */ array(3, 21, ),
        /* 64 */ array(12, 15, ),
        /* 65 */ array(12, 15, ),
        /* 66 */ array(3, 21, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(14, 24, ),
        /* 69 */ array(3, 21, ),
        /* 70 */ array(3, 21, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(22, ),
        /* 73 */ array(24, ),
        /* 74 */ array(10, ),
        /* 75 */ array(22, ),
        /* 76 */ array(14, ),
        /* 77 */ array(17, ),
        /* 78 */ array(),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(10, 17, 20, ),
        /* 82 */ array(16, 18, ),
        /* 83 */ array(13, 16, ),
        /* 84 */ array(10, 17, ),
        /* 85 */ array(3, 22, ),
        /* 86 */ array(24, ),
        /* 87 */ array(24, ),
        /* 88 */ array(20, ),
        /* 89 */ array(11, ),
        /* 90 */ array(24, ),
        /* 91 */ array(24, ),
        /* 92 */ array(41, ),
        /* 93 */ array(3, ),
        /* 94 */ array(3, ),
        /* 95 */ array(3, ),
        /* 96 */ array(24, ),
        /* 97 */ array(24, ),
        /* 98 */ array(11, ),
        /* 99 */ array(23, ),
        /* 100 */ array(3, ),
        /* 101 */ array(14, ),
        /* 102 */ array(3, ),
        /* 103 */ array(3, ),
        /* 104 */ array(16, ),
        /* 105 */ array(10, ),
        /* 106 */ array(24, ),
        /* 107 */ array(11, ),
        /* 108 */ array(3, ),
        /* 109 */ array(17, ),
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
);
    static public $yy_default = array(
 /*     0 */   308,  308,  308,  308,  308,  308,  295,  308,  271,  308,
 /*    10 */   271,  271,  308,  308,  308,  308,  308,  308,  308,  308,
 /*    20 */   308,  308,  308,  308,  308,  308,  308,  248,  308,  251,
 /*    30 */   225,  308,  198,  255,  230,  255,  308,  255,  308,  308,
 /*    40 */   308,  308,  308,  308,  308,  308,  308,  244,  308,  225,
 /*    50 */   225,  279,  279,  308,  308,  308,  308,  308,  308,  308,
 /*    60 */   256,  308,  308,  308,  265,  247,  308,  277,  308,  308,
 /*    70 */   308,  281,  262,  308,  255,  245,  308,  231,  274,  255,
 /*    80 */   274,  248,  308,  308,  248,  308,  308,  308,  308,  308,
 /*    90 */   308,  308,  308,  308,  308,  308,  308,  308,  308,  296,
 /*   100 */   308,  308,  308,  308,  270,  246,  308,  308,  308,  308,
 /*   110 */   284,  280,  217,  282,  283,  221,  229,  228,  222,  202,
 /*   120 */   219,  220,  199,  216,  215,  227,  218,  200,  289,  288,
 /*   130 */   287,  286,  276,  201,  278,  291,  290,  285,  252,  254,
 /*   140 */   235,  243,  242,  240,  241,  264,  236,  305,  292,  253,
 /*   150 */   250,  249,  239,  234,  207,  208,  206,  205,  203,  204,
 /*   160 */   209,  210,  232,  233,  273,  226,  223,  307,  294,  302,
 /*   170 */   303,  301,  300,  258,  298,  299,  275,  212,  213,  211,
 /*   180 */   224,  272,  261,  260,  257,  306,  238,  237,  297,  293,
 /*   190 */   268,  304,  267,  259,  263,  266,  269,  214,
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
    const YYNOCODE = 92;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 198;
    const YYNRULE = 110;
    const YYERRORSYMBOL = 52;
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
  'ANDSYM',        'BACKTICK',      'AT',            'LITERALSTART',
  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',     'PHP',         
  'PHPSTART',      'PHPEND',        'XML',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'text',          'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'variable',    
  'array',         'attribute',     'exprs',         'value',       
  'math',          'function',      'doublequoted',  'method',      
  'objectchain',   'vararraydefs',  'object',        'vararraydef', 
  'varvarele',     'objectelement',  'params',        'modparameter',
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
 /*  32 */ "expr ::= exprs",
 /*  33 */ "expr ::= exprs modifier modparameters",
 /*  34 */ "expr ::= array",
 /*  35 */ "exprs ::= value",
 /*  36 */ "exprs ::= UNIMATH value",
 /*  37 */ "exprs ::= exprs math value",
 /*  38 */ "exprs ::= exprs ANDSYM value",
 /*  39 */ "math ::= UNIMATH",
 /*  40 */ "math ::= MATH",
 /*  41 */ "value ::= variable",
 /*  42 */ "value ::= NUMBER",
 /*  43 */ "value ::= function",
 /*  44 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  45 */ "value ::= QUOTE doublequoted QUOTE",
 /*  46 */ "value ::= ID COLON COLON method",
 /*  47 */ "value ::= ID COLON COLON method objectchain",
 /*  48 */ "value ::= ID COLON COLON ID",
 /*  49 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  50 */ "value ::= ID",
 /*  51 */ "value ::= BOOLEAN",
 /*  52 */ "value ::= OPENP expr CLOSEP",
 /*  53 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  54 */ "variable ::= DOLLAR varvar AT ID",
 /*  55 */ "variable ::= object",
 /*  56 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  57 */ "vararraydefs ::=",
 /*  58 */ "vararraydef ::= DOT exprs",
 /*  59 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  60 */ "varvar ::= varvarele",
 /*  61 */ "varvar ::= varvar varvarele",
 /*  62 */ "varvarele ::= ID",
 /*  63 */ "varvarele ::= LDEL expr RDEL",
 /*  64 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  65 */ "objectchain ::= objectelement",
 /*  66 */ "objectchain ::= objectchain objectelement",
 /*  67 */ "objectelement ::= PTR ID vararraydefs",
 /*  68 */ "objectelement ::= PTR method",
 /*  69 */ "function ::= ID OPENP params CLOSEP",
 /*  70 */ "method ::= ID OPENP params CLOSEP",
 /*  71 */ "params ::= expr COMMA params",
 /*  72 */ "params ::= expr",
 /*  73 */ "params ::=",
 /*  74 */ "modifier ::= VERT ID",
 /*  75 */ "modparameters ::= modparameters modparameter",
 /*  76 */ "modparameters ::=",
 /*  77 */ "modparameter ::= COLON expr",
 /*  78 */ "ifexprs ::= ifexpr",
 /*  79 */ "ifexprs ::= NOT ifexprs",
 /*  80 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  81 */ "ifexpr ::= expr",
 /*  82 */ "ifexpr ::= expr ifcond expr",
 /*  83 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  84 */ "ifcond ::= EQUALS",
 /*  85 */ "ifcond ::= NOTEQUALS",
 /*  86 */ "ifcond ::= GREATERTHAN",
 /*  87 */ "ifcond ::= LESSTHAN",
 /*  88 */ "ifcond ::= GREATEREQUAL",
 /*  89 */ "ifcond ::= LESSEQUAL",
 /*  90 */ "ifcond ::= IDENTITY",
 /*  91 */ "ifcond ::= NONEIDENTITY",
 /*  92 */ "lop ::= LAND",
 /*  93 */ "lop ::= LOR",
 /*  94 */ "array ::= OPENB arrayelements CLOSEB",
 /*  95 */ "arrayelements ::= arrayelement",
 /*  96 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  97 */ "arrayelements ::=",
 /*  98 */ "arrayelement ::= expr",
 /*  99 */ "arrayelement ::= expr APTR expr",
 /* 100 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 101 */ "doublequoted ::= doublequotedcontent",
 /* 102 */ "doublequotedcontent ::= variable",
 /* 103 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 104 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 105 */ "doublequotedcontent ::= OTHER",
 /* 106 */ "text ::= text textelement",
 /* 107 */ "text ::= textelement",
 /* 108 */ "textelement ::= OTHER",
 /* 109 */ "textelement ::= LDEL",
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
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 6 ),
  array( 'lhs' => 56, 'rhs' => 6 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 5 ),
  array( 'lhs' => 56, 'rhs' => 5 ),
  array( 'lhs' => 56, 'rhs' => 11 ),
  array( 'lhs' => 56, 'rhs' => 8 ),
  array( 'lhs' => 56, 'rhs' => 8 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 0 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 0 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 0 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 0 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        35 => 0,
        41 => 0,
        42 => 0,
        43 => 0,
        51 => 0,
        55 => 0,
        95 => 0,
        1 => 1,
        32 => 1,
        34 => 1,
        39 => 1,
        40 => 1,
        60 => 1,
        78 => 1,
        101 => 1,
        107 => 1,
        108 => 1,
        109 => 1,
        2 => 2,
        56 => 2,
        100 => 2,
        106 => 2,
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
        72 => 24,
        98 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        33 => 33,
        36 => 36,
        37 => 37,
        38 => 38,
        44 => 44,
        45 => 44,
        46 => 46,
        47 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        57 => 57,
        76 => 57,
        58 => 58,
        59 => 59,
        61 => 61,
        62 => 62,
        63 => 63,
        80 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        73 => 73,
        74 => 74,
        75 => 75,
        77 => 77,
        79 => 79,
        81 => 81,
        82 => 82,
        83 => 82,
        84 => 84,
        85 => 85,
        86 => 86,
        87 => 87,
        88 => 88,
        89 => 89,
        90 => 90,
        91 => 91,
        92 => 92,
        93 => 93,
        94 => 94,
        96 => 96,
        97 => 97,
        99 => 99,
        102 => 102,
        103 => 103,
        104 => 104,
        105 => 105,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1506 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1509 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1512 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1517 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1520 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1523 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1526 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1529 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1535 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1541 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1544 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1547 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1550 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1553 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1556 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1559 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
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
#line 1574 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1577 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1580 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1583 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1586 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1589 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1592 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1595 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1598 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1601 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1604 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1607 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1610 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1613 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r33(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
                                                                      $this->_retvalue = "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -1]->minor . "(array(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor ."),'modifier')";
                                                                 } else {
                                                                   if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                               $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . $this->yystack[$this->yyidx + -1]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1627 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1630 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1633 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1636 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1639 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1642 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1645 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1648 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1651 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1654 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1657 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r53(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1661 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1664 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r57(){return;    }
#line 1667 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1670 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1673 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1676 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1679 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1682 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1685 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1688 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1691 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1694 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1697 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r69(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1706 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1709 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1712 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r73(){ return;    }
#line 1715 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1718 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1721 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1724 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1727 "internal.templateparser.php"
#line 342 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1730 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1733 "internal.templateparser.php"
#line 346 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '==';    }
#line 1736 "internal.templateparser.php"
#line 347 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '!=';    }
#line 1739 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '>';    }
#line 1742 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '<';    }
#line 1745 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '>=';    }
#line 1748 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '<=';    }
#line 1751 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '===';    }
#line 1754 "internal.templateparser.php"
#line 353 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '!==';    }
#line 1757 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '&&';    }
#line 1760 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '||';    }
#line 1763 "internal.templateparser.php"
#line 358 "internal.templateparser.y"
    function yy_r94(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1766 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r96(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1769 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r97(){ return;     }
#line 1772 "internal.templateparser.php"
#line 363 "internal.templateparser.y"
    function yy_r99(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1775 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1778 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1781 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1784 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1787 "internal.templateparser.php"

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
#line 1904 "internal.templateparser.php"
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
#line 1929 "internal.templateparser.php"
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
