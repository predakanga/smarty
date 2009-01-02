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
    const TP_LDELS                          =  2;
    const TP_LDELSLASH                      =  3;
    const TP_RDELS                          =  4;
    const TP_RDEL                           =  5;
    const TP_COMMENTSTART                   =  6;
    const TP_COMMENTEND                     =  7;
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
    const TP_SEMICOLON                      = 20;
    const TP_VERT                           = 21;
    const TP_EQUAL                          = 22;
    const TP_SPACE                          = 23;
    const TP_PTR                            = 24;
    const TP_APTR                           = 25;
    const TP_ID                             = 26;
    const TP_SI_QSTR                        = 27;
    const TP_EQUALS                         = 28;
    const TP_NOTEQUALS                      = 29;
    const TP_GREATERTHAN                    = 30;
    const TP_LESSTHAN                       = 31;
    const TP_GREATEREQUAL                   = 32;
    const TP_LESSEQUAL                      = 33;
    const TP_IDENTITY                       = 34;
    const TP_NOT                            = 35;
    const TP_LAND                           = 36;
    const TP_LOR                            = 37;
    const TP_QUOTE                          = 38;
    const TP_BOOLEAN                        = 39;
    const TP_IN                             = 40;
    const TP_ANDSYM                         = 41;
    const TP_UNDERL                         = 42;
    const TP_BACKTICK                       = 43;
    const TP_PHP                            = 44;
    const TP_LDEL                           = 45;
    const YY_NO_ACTION = 282;
    const YY_ACCEPT_ACTION = 281;
    const YY_ERROR_ACTION = 280;

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
 /*     0 */   133,  134,   35,  178,  141,  161,   13,   66,   11,  269,
 /*    10 */   156,  150,   84,  144,  145,   83,   12,  153,  152,  113,
 /*    20 */   112,  110,  109,  111,  129,  125,   34,  168,  126,   16,
 /*    30 */    15,   55,   28,   14,  171,  150,    9,  144,  137,   83,
 /*    40 */   164,  153,  152,   72,  139,  155,   76,  175,   35,   77,
 /*    50 */   124,    5,  126,   64,   88,   87,  176,  150,   20,  144,
 /*    60 */   145,   83,  103,  153,  152,  133,  134,   35,  281,   36,
 /*    70 */   128,  115,   59,  154,  126,   26,  150,    2,  144,  145,
 /*    80 */    83,   42,  153,  152,  113,  112,  110,  109,  111,  129,
 /*    90 */   125,  100,  151,  126,  133,  134,  114,   28,  141,   68,
 /*   100 */     4,   47,   96,   32,  132,  133,  134,   78,  162,   48,
 /*   110 */   150,  138,  144,  137,   83,   97,  153,  152,  150,    7,
 /*   120 */   144,  145,   83,  146,  153,  152,   28,   35,  167,   22,
 /*   130 */    87,  176,   70,  169,  123,  117,  150,   28,  144,  145,
 /*   140 */    83,  166,  153,  152,  170,  104,  103,  154,  180,   26,
 /*   150 */   161,    2,  154,  126,   26,   40,    6,  202,   37,  103,
 /*   160 */    42,  123,  117,  173,    7,   89,  151,  133,  134,   27,
 /*   170 */   100,  151,  158,   96,    4,    1,   92,   32,  132,  123,
 /*   180 */   117,   15,   32,  132,   14,  157,   99,  133,  134,   13,
 /*   190 */   154,   73,   26,  156,    6,   67,  133,  134,   41,   28,
 /*   200 */    39,  146,   48,   12,  162,    8,  121,   80,   29,  151,
 /*   210 */    39,  150,   16,  144,  145,   83,   91,  153,  152,   28,
 /*   220 */    32,  132,  143,   50,  133,  134,   98,   74,   28,   18,
 /*   230 */   156,   48,  150,   44,  144,  137,   83,   33,  153,  152,
 /*   240 */   150,  127,  144,  145,   83,   38,  153,  152,  149,   16,
 /*   250 */    30,  156,   69,  177,   15,  107,   28,   14,  179,   99,
 /*   260 */    31,  162,   94,  179,   73,   27,  156,  108,   56,  150,
 /*   270 */    16,  144,  145,   83,  102,  153,  152,  150,  154,  144,
 /*   280 */   145,   83,   19,  153,  152,   16,   42,   15,   90,   62,
 /*   290 */    14,  157,   99,   72,  139,  155,  100,  151,  150,  156,
 /*   300 */   144,  145,   83,   46,  153,  152,   39,  118,   32,  132,
 /*   310 */   135,   15,   95,   54,   14,   75,   99,  163,   16,   13,
 /*   320 */   165,   73,  150,  156,  144,  145,   83,  106,  153,  152,
 /*   330 */   123,  117,   63,   74,  130,   18,  133,  134,  103,  155,
 /*   340 */   141,  150,   16,  144,  145,   83,  122,  153,  152,   43,
 /*   350 */    58,   20,   12,  131,   10,   71,  160,   85,   49,  150,
 /*   360 */   101,  144,  145,   83,  162,  153,  152,  150,   28,  144,
 /*   370 */   145,   83,   61,  153,  152,   65,  139,   27,  133,  134,
 /*   380 */    22,  150,   51,  144,  145,   83,  140,  153,  152,    7,
 /*   390 */    17,  150,  103,  144,  145,   83,   53,  153,  152,    3,
 /*   400 */   120,  115,  103,  155,  116,  150,  119,  144,  145,   83,
 /*   410 */    28,  153,  152,  142,   57,  159,  105,   21,  148,  133,
 /*   420 */   134,  156,   52,  150,  174,  144,  145,   83,   93,  153,
 /*   430 */   152,  150,  136,  144,  145,   83,  103,  153,  152,  172,
 /*   440 */    16,  150,   23,   24,   45,   82,  150,  153,  152,  156,
 /*   450 */    79,   28,  153,  152,  150,  179,  179,   25,   81,   73,
 /*   460 */   153,  152,   86,  147,  185,  108,   60,  185,   16,  185,
 /*   470 */   179,  185,  185,  185,  185,  185,  185,  185,  185,  179,
    );
    static public $yy_lookahead = array(
 /*     0 */     9,   10,   52,   13,   13,   61,   22,   57,   18,   18,
 /*    10 */    26,   61,   55,   63,   64,   65,   25,   67,   68,   28,
 /*    20 */    29,   30,   31,   32,   33,   34,   52,   83,   78,   45,
 /*    30 */    14,   57,   41,   17,    1,   61,   12,   63,   64,   65,
 /*    40 */     7,   67,   68,   70,   71,   72,   73,   74,   52,   18,
 /*    50 */    54,   20,   78,   57,   58,   81,   82,   61,   19,   63,
 /*    60 */    64,   65,   23,   67,   68,    9,   10,   52,   47,   48,
 /*    70 */    49,   50,   57,    8,   78,   10,   61,   12,   63,   64,
 /*    80 */    65,   16,   67,   68,   28,   29,   30,   31,   32,   33,
 /*    90 */    34,   26,   27,   78,    9,   10,    5,   41,   13,   53,
 /*   100 */    35,   52,   21,   38,   39,    9,   10,   56,   62,   52,
 /*   110 */    61,   15,   63,   64,   65,   24,   67,   68,   61,   12,
 /*   120 */    63,   64,   65,   77,   67,   68,   41,   52,    5,   22,
 /*   130 */    81,   82,   57,   76,   36,   37,   61,   41,   63,   64,
 /*   140 */    65,    5,   67,   68,   13,   20,   23,    8,    5,   10,
 /*   150 */    61,   12,    8,   78,   10,   16,   12,    5,   69,   23,
 /*   160 */    16,   36,   37,    5,   12,   26,   27,    9,   10,   66,
 /*   170 */    26,   27,   83,   21,   35,   23,   24,   38,   39,   36,
 /*   180 */    37,   14,   38,   39,   17,    1,   19,    9,   10,   22,
 /*   190 */     8,   24,   10,   26,   12,   53,    9,   10,   16,   41,
 /*   200 */    16,   77,   52,   25,   62,   18,    5,   40,   26,   27,
 /*   210 */    16,   61,   45,   63,   64,   65,   61,   67,   68,   41,
 /*   220 */    38,   39,   38,   52,    9,   10,   76,   43,   41,   45,
 /*   230 */    26,   52,   61,   16,   63,   64,   65,   56,   67,   68,
 /*   240 */    61,    5,   63,   64,   65,   59,   67,   68,   26,   45,
 /*   250 */    59,   26,   53,   82,   14,   76,   41,   17,   72,   19,
 /*   260 */    52,   62,   54,   72,   24,   66,   26,   42,   52,   61,
 /*   270 */    45,   63,   64,   65,   26,   67,   68,   61,    8,   63,
 /*   280 */    64,   65,   12,   67,   68,   45,   16,   14,   26,   52,
 /*   290 */    17,    1,   19,   70,   71,   72,   26,   27,   61,   26,
 /*   300 */    63,   64,   65,   26,   67,   68,   16,   13,   38,   39,
 /*   310 */     1,   14,    3,   52,   17,    6,   19,    5,   45,   22,
 /*   320 */     5,   24,   61,   26,   63,   64,   65,   60,   67,   68,
 /*   330 */    36,   37,   52,   43,   13,   45,    9,   10,   23,   72,
 /*   340 */    13,   61,   45,   63,   64,   65,    5,   67,   68,   26,
 /*   350 */    52,   19,   25,   44,   45,   53,   43,   55,   52,   61,
 /*   360 */    26,   63,   64,   65,   62,   67,   68,   61,   41,   63,
 /*   370 */    64,   65,   52,   67,   68,   70,   71,   66,    9,   10,
 /*   380 */    22,   61,   52,   63,   64,   65,   26,   67,   68,   12,
 /*   390 */    79,   61,   23,   63,   64,   65,   52,   67,   68,   80,
 /*   400 */    49,   50,   23,   72,   54,   61,   11,   63,   64,   65,
 /*   410 */    41,   67,   68,   71,   52,    5,   26,   22,    5,    9,
 /*   420 */    10,   26,   52,   61,   75,   63,   64,   65,   61,   67,
 /*   430 */    68,   61,    1,   63,   64,   65,   23,   67,   68,   74,
 /*   440 */    45,   61,   59,   59,   16,   65,   61,   67,   68,   26,
 /*   450 */    65,   41,   67,   68,   61,   72,   72,   59,   65,   24,
 /*   460 */    67,   68,   51,   62,   84,   42,   59,   84,   45,   84,
 /*   470 */    72,   84,   84,   84,   84,   84,   84,   84,   84,   72,
);
    const YY_SHIFT_USE_DFLT = -17;
    const YY_SHIFT_MAX = 108;
    static public $yy_shift_ofst = array(
 /*     0 */   309,  139,   65,   65,   65,   65,  144,  144,  144,  144,
 /*    10 */   182,  144,  144,  144,  144,  144,  144,  144,  144,  144,
 /*    20 */   144,  144,  144,  167,  297,  240,  270,  270,  270,  152,
 /*    30 */   273,  369,  290,   39,   -9,   56,  309,  184,  395,  225,
 /*    40 */   225,  225,  423,   16,  204,  204,  379,  327,  187,   96,
 /*    50 */   178,   85,  158,  410,  215,  294,  215,  215,  215,  125,
 /*    60 */   -16,  215,  215,  215,  143,   16,   98,  136,  315,  413,
 /*    70 */    98,  123,   16,  390,  194,  431,  435,  428,  332,   81,
 /*    80 */   194,   81,   81,   81,  -17,  -17,   33,  -10,   31,  107,
 /*    90 */    91,  236,  277,  313,  312,  262,  222,  248,  321,  360,
 /*   100 */   377,  358,  341,  334,  217,   24,  201,  131,  323,
);
    const YY_REDUCE_USE_DFLT = -57;
    const YY_REDUCE_MAX = 85;
    static public $yy_reduce_ofst = array(
 /*     0 */    21,   -4,  -26,   75,  -50,   15,   49,  150,   57,  179,
 /*    10 */   208,  171,  261,  280,  320,  306,  370,  362,  344,  330,
 /*    20 */   298,  216,  237,  -27,  -27,  -27,  380,  385,  393,  302,
 /*    30 */   223,  199,   89,   46,  311,  311,  351,  -56,  267,  191,
 /*    40 */   383,  384,  398,  305,  186,  407,  142,  103,  103,  103,
 /*    50 */   103,  103,  103,  103,  103,  319,  103,  103,  103,  319,
 /*    60 */   331,  103,  103,  103,  319,  342,  319,  401,  401,  401,
 /*    70 */   319,  401,  342,  349,  367,  411,  365,  350,  124,  -43,
 /*    80 */   155,  -43,  -43,  -43,   51,  181,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 6, 44, 45, ),
        /* 1 */ array(8, 10, 12, 16, 26, 27, 35, 38, 39, ),
        /* 2 */ array(8, 10, 12, 16, 26, 27, 35, 38, 39, ),
        /* 3 */ array(8, 10, 12, 16, 26, 27, 35, 38, 39, ),
        /* 4 */ array(8, 10, 12, 16, 26, 27, 35, 38, 39, ),
        /* 5 */ array(8, 10, 12, 16, 26, 27, 35, 38, 39, ),
        /* 6 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 7 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 8 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 9 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 10 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 11 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 12 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 13 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 14 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 15 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 16 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 17 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 18 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 19 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 20 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 21 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 22 */ array(8, 10, 12, 16, 26, 27, 38, 39, ),
        /* 23 */ array(14, 17, 19, 22, 24, 26, 40, 45, ),
        /* 24 */ array(14, 17, 19, 22, 24, 26, 45, ),
        /* 25 */ array(14, 17, 19, 24, 26, 45, ),
        /* 26 */ array(8, 12, 16, 26, 27, 38, 39, ),
        /* 27 */ array(8, 12, 16, 26, 27, 38, 39, ),
        /* 28 */ array(8, 12, 16, 26, 27, 38, 39, ),
        /* 29 */ array(5, 12, 21, 23, 24, ),
        /* 30 */ array(14, 17, 19, 26, 45, ),
        /* 31 */ array(9, 10, 23, 41, ),
        /* 32 */ array(1, 16, 43, 45, ),
        /* 33 */ array(19, 23, ),
        /* 34 */ array(9, 10, 13, 18, 25, 28, 29, 30, 31, 32, 33, 34, 41, ),
        /* 35 */ array(9, 10, 28, 29, 30, 31, 32, 33, 34, 41, ),
        /* 36 */ array(1, 3, 6, 44, 45, ),
        /* 37 */ array(1, 16, 38, 43, 45, ),
        /* 38 */ array(11, 22, 26, 45, ),
        /* 39 */ array(26, 42, 45, ),
        /* 40 */ array(26, 42, 45, ),
        /* 41 */ array(26, 42, 45, ),
        /* 42 */ array(26, 42, 45, ),
        /* 43 */ array(14, 17, ),
        /* 44 */ array(26, 45, ),
        /* 45 */ array(26, 45, ),
        /* 46 */ array(23, ),
        /* 47 */ array(9, 10, 13, 25, 41, ),
        /* 48 */ array(9, 10, 18, 41, ),
        /* 49 */ array(9, 10, 15, 41, ),
        /* 50 */ array(9, 10, 25, 41, ),
        /* 51 */ array(9, 10, 13, 41, ),
        /* 52 */ array(5, 9, 10, 41, ),
        /* 53 */ array(5, 9, 10, 41, ),
        /* 54 */ array(9, 10, 41, ),
        /* 55 */ array(13, 36, 37, ),
        /* 56 */ array(9, 10, 41, ),
        /* 57 */ array(9, 10, 41, ),
        /* 58 */ array(9, 10, 41, ),
        /* 59 */ array(20, 36, 37, ),
        /* 60 */ array(22, 26, 45, ),
        /* 61 */ array(9, 10, 41, ),
        /* 62 */ array(9, 10, 41, ),
        /* 63 */ array(9, 10, 41, ),
        /* 64 */ array(5, 36, 37, ),
        /* 65 */ array(14, 17, ),
        /* 66 */ array(36, 37, ),
        /* 67 */ array(5, 23, ),
        /* 68 */ array(5, 23, ),
        /* 69 */ array(5, 23, ),
        /* 70 */ array(36, 37, ),
        /* 71 */ array(5, 23, ),
        /* 72 */ array(14, 17, ),
        /* 73 */ array(26, ),
        /* 74 */ array(16, ),
        /* 75 */ array(1, ),
        /* 76 */ array(24, ),
        /* 77 */ array(16, ),
        /* 78 */ array(19, ),
        /* 79 */ array(21, ),
        /* 80 */ array(16, ),
        /* 81 */ array(21, ),
        /* 82 */ array(21, ),
        /* 83 */ array(21, ),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(1, 7, ),
        /* 87 */ array(13, 18, ),
        /* 88 */ array(18, 20, ),
        /* 89 */ array(12, 22, ),
        /* 90 */ array(5, 24, ),
        /* 91 */ array(5, ),
        /* 92 */ array(26, ),
        /* 93 */ array(43, ),
        /* 94 */ array(5, ),
        /* 95 */ array(26, ),
        /* 96 */ array(26, ),
        /* 97 */ array(26, ),
        /* 98 */ array(13, ),
        /* 99 */ array(26, ),
        /* 100 */ array(12, ),
        /* 101 */ array(22, ),
        /* 102 */ array(5, ),
        /* 103 */ array(26, ),
        /* 104 */ array(16, ),
        /* 105 */ array(12, ),
        /* 106 */ array(5, ),
        /* 107 */ array(13, ),
        /* 108 */ array(26, ),
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
);
    static public $yy_default = array(
 /*     0 */   280,  280,  280,  280,  280,  280,  280,  246,  246,  246,
 /*    10 */   280,  280,  280,  280,  280,  280,  280,  280,  280,  280,
 /*    20 */   280,  280,  280,  230,  230,  230,  280,  280,  280,  222,
 /*    30 */   230,  202,  280,  202,  254,  254,  181,  280,  280,  280,
 /*    40 */   280,  280,  280,  230,  280,  280,  202,  269,  245,  280,
 /*    50 */   269,  280,  280,  280,  270,  280,  198,  255,  250,  280,
 /*    60 */   280,  231,  203,  206,  280,  227,  252,  280,  280,  280,
 /*    70 */   256,  280,  225,  280,  280,  280,  237,  280,  215,  211,
 /*    80 */   280,  212,  210,  209,  249,  249,  280,  280,  280,  222,
 /*    90 */   280,  280,  280,  280,  280,  280,  280,  280,  280,  280,
 /*   100 */   222,  280,  280,  280,  280,  240,  280,  280,  280,  260,
 /*   110 */   259,  261,  258,  257,  193,  184,  205,  265,  253,  199,
 /*   120 */   183,  196,  194,  264,  204,  263,  251,  197,  182,  262,
 /*   130 */   242,  186,  223,  214,  213,  187,  279,  208,  232,  228,
 /*   140 */   226,  224,  229,  221,  207,  208,  248,  200,  188,  247,
 /*   150 */   216,  220,  219,  218,  217,  234,  235,  277,  273,  276,
 /*   160 */   275,  274,  201,  189,  185,  192,  191,  190,  272,  244,
 /*   170 */   243,  278,  239,  236,  241,  238,  267,  268,  266,  233,
 /*   180 */   195,
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
    const YYNOCODE = 85;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 181;
    const YYNRULE = 99;
    const YYERRORSYMBOL = 46;
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
  'RDELS',         'RDEL',          'COMMENTSTART',  'COMMENTEND',  
  'NUMBER',        'MATH',          'UNIMATH',       'INCDEC',      
  'OPENP',         'CLOSEP',        'OPENB',         'CLOSEB',      
  'DOLLAR',        'DOT',           'COMMA',         'COLON',       
  'SEMICOLON',     'VERT',          'EQUAL',         'SPACE',       
  'PTR',           'APTR',          'ID',            'SI_QSTR',     
  'EQUALS',        'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',    
  'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',      'NOT',         
  'LAND',          'LOR',           'QUOTE',         'BOOLEAN',     
  'IN',            'ANDSYM',        'UNDERL',        'BACKTICK',    
  'PHP',           'LDEL',          'error',         'start',       
  'template',      'template_element',  'smartytag',     'text',        
  'expr',          'attributes',    'statement',     'modifier',    
  'modparameters',  'ifexprs',       'statements',    'varvar',      
  'foraction',     'variable',      'attribute',     'exprs',       
  'array',         'value',         'math',          'object',      
  'function',      'doublequoted',  'vararraydefs',  'vararraydef', 
  'varvarele',     'objectchain',   'objectelement',  'method',      
  'params',        'modparameter',  'ifexpr',        'ifcond',      
  'lop',           'arrayelements',  'arrayelement',  'doublequotedcontent',
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
 /*  10 */ "smartytag ::= LDEL ID PTR ID attributes RDEL",
 /*  11 */ "smartytag ::= LDEL ID modifier modparameters attributes RDEL",
 /*  12 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  13 */ "smartytag ::= LDELSLASH ID PTR ID RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  16 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  17 */ "foraction ::= EQUAL expr",
 /*  18 */ "foraction ::= INCDEC",
 /*  19 */ "attributes ::= attributes attribute",
 /*  20 */ "attributes ::= attribute",
 /*  21 */ "attributes ::=",
 /*  22 */ "attribute ::= SPACE ID EQUAL expr",
 /*  23 */ "statements ::= statement",
 /*  24 */ "statements ::= statements COMMA statement",
 /*  25 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  26 */ "expr ::= exprs",
 /*  27 */ "expr ::= array",
 /*  28 */ "exprs ::= value",
 /*  29 */ "exprs ::= UNIMATH value",
 /*  30 */ "exprs ::= expr math value",
 /*  31 */ "exprs ::= expr ANDSYM value",
 /*  32 */ "math ::= UNIMATH",
 /*  33 */ "math ::= MATH",
 /*  34 */ "value ::= value modifier modparameters",
 /*  35 */ "value ::= variable",
 /*  36 */ "value ::= NUMBER",
 /*  37 */ "value ::= object",
 /*  38 */ "value ::= function",
 /*  39 */ "value ::= SI_QSTR",
 /*  40 */ "value ::= QUOTE doublequoted QUOTE",
 /*  41 */ "value ::= ID",
 /*  42 */ "value ::= BOOLEAN",
 /*  43 */ "value ::= OPENP expr CLOSEP",
 /*  44 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  45 */ "variable ::= DOLLAR varvar COLON ID",
 /*  46 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  47 */ "vararraydefs ::= vararraydef",
 /*  48 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  49 */ "vararraydefs ::=",
 /*  50 */ "vararraydef ::= DOT expr",
 /*  51 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  52 */ "varvar ::= varvarele",
 /*  53 */ "varvar ::= varvar varvarele",
 /*  54 */ "varvarele ::= ID",
 /*  55 */ "varvarele ::= LDEL expr RDEL",
 /*  56 */ "object ::= DOLLAR varvar objectchain",
 /*  57 */ "objectchain ::= objectelement",
 /*  58 */ "objectchain ::= objectchain objectelement",
 /*  59 */ "objectelement ::= PTR ID",
 /*  60 */ "objectelement ::= PTR method",
 /*  61 */ "function ::= ID OPENP params CLOSEP",
 /*  62 */ "method ::= ID OPENP params CLOSEP",
 /*  63 */ "params ::= expr COMMA params",
 /*  64 */ "params ::= expr",
 /*  65 */ "params ::=",
 /*  66 */ "modifier ::= VERT ID",
 /*  67 */ "modparameters ::= modparameters modparameter",
 /*  68 */ "modparameters ::=",
 /*  69 */ "modparameter ::= COLON expr",
 /*  70 */ "ifexprs ::= ifexpr",
 /*  71 */ "ifexprs ::= NOT ifexprs",
 /*  72 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  73 */ "ifexpr ::= expr",
 /*  74 */ "ifexpr ::= expr ifcond expr",
 /*  75 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  76 */ "ifcond ::= EQUALS",
 /*  77 */ "ifcond ::= NOTEQUALS",
 /*  78 */ "ifcond ::= GREATERTHAN",
 /*  79 */ "ifcond ::= LESSTHAN",
 /*  80 */ "ifcond ::= GREATEREQUAL",
 /*  81 */ "ifcond ::= LESSEQUAL",
 /*  82 */ "ifcond ::= IDENTITY",
 /*  83 */ "lop ::= LAND",
 /*  84 */ "lop ::= LOR",
 /*  85 */ "array ::= OPENP arrayelements CLOSEP",
 /*  86 */ "arrayelements ::= arrayelement",
 /*  87 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  88 */ "arrayelement ::= expr",
 /*  89 */ "arrayelement ::= expr APTR expr",
 /*  90 */ "arrayelement ::= array",
 /*  91 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  92 */ "doublequoted ::= doublequotedcontent",
 /*  93 */ "doublequotedcontent ::= variable",
 /*  94 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /*  95 */ "doublequotedcontent ::= LDEL expr RDEL",
 /*  96 */ "doublequotedcontent ::= OTHER",
 /*  97 */ "text ::= text OTHER",
 /*  98 */ "text ::= OTHER",
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
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 6 ),
  array( 'lhs' => 50, 'rhs' => 6 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 5 ),
  array( 'lhs' => 50, 'rhs' => 5 ),
  array( 'lhs' => 50, 'rhs' => 11 ),
  array( 'lhs' => 50, 'rhs' => 8 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 0 ),
  array( 'lhs' => 62, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 0 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 0 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 0 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        28 => 0,
        35 => 0,
        36 => 0,
        37 => 0,
        38 => 0,
        39 => 0,
        42 => 0,
        86 => 0,
        1 => 1,
        26 => 1,
        27 => 1,
        32 => 1,
        33 => 1,
        47 => 1,
        52 => 1,
        70 => 1,
        92 => 1,
        96 => 1,
        98 => 1,
        2 => 2,
        48 => 2,
        91 => 2,
        97 => 2,
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
        20 => 18,
        64 => 18,
        88 => 18,
        90 => 18,
        19 => 19,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        29 => 29,
        30 => 30,
        31 => 31,
        34 => 34,
        40 => 40,
        41 => 41,
        43 => 43,
        44 => 44,
        56 => 44,
        45 => 45,
        46 => 46,
        49 => 49,
        68 => 49,
        50 => 50,
        51 => 51,
        53 => 53,
        54 => 54,
        55 => 55,
        72 => 55,
        57 => 57,
        58 => 58,
        59 => 59,
        60 => 59,
        61 => 61,
        62 => 62,
        63 => 63,
        65 => 65,
        66 => 66,
        67 => 67,
        69 => 69,
        71 => 71,
        73 => 73,
        74 => 74,
        75 => 74,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        87 => 87,
        89 => 89,
        93 => 93,
        94 => 94,
        95 => 95,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1439 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1442 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1445 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1450 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1453 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1459 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1462 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1465 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1468 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1471 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1474 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
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
#line 1489 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1492 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1495 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1498 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1501 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1504 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1507 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1510 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1513 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array();    }
#line 1516 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1519 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1522 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r24(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1525 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1528 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1531 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1534 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1537 "internal.templateparser.php"
#line 187 "internal.templateparser.y"
    function yy_r34(){if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
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
#line 1551 "internal.templateparser.php"
#line 211 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1554 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1557 "internal.templateparser.php"
#line 217 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1560 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1563 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1566 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1569 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r49(){return;    }
#line 1572 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1575 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1578 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r53(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1581 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1584 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r55(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1587 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1590 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r58(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1593 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1596 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r61(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1605 "internal.templateparser.php"
#line 278 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1608 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1611 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r65(){ return;    }
#line 1614 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1617 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1620 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1623 "internal.templateparser.php"
#line 308 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1626 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1629 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1632 "internal.templateparser.php"
#line 317 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '==';    }
#line 1635 "internal.templateparser.php"
#line 318 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '!=';    }
#line 1638 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '>';    }
#line 1641 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '<';    }
#line 1644 "internal.templateparser.php"
#line 321 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '>=';    }
#line 1647 "internal.templateparser.php"
#line 322 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '<=';    }
#line 1650 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '===';    }
#line 1653 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '&&';    }
#line 1656 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '||';    }
#line 1659 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1662 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r87(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1665 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r89(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1668 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1671 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1674 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1677 "internal.templateparser.php"

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
#line 1794 "internal.templateparser.php"
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
#line 1819 "internal.templateparser.php"
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
