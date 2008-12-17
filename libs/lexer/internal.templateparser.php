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

    function __construct($lex) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->smarty = Smarty::instance(); 
        $this->compiler = Smarty_Internal_Compiler::instance();
        $this->template = $this->compiler->template; 
				$this->nocache = false;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 139 "internal.templateparser.php"

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
    const TP_IN                             = 38;
    const TP_ANDSYM                         = 39;
    const TP_UNDERL                         = 40;
    const TP_COMMENTSTART                   = 41;
    const TP_COMMENTEND                     = 42;
    const TP_PHP                            = 43;
    const TP_LDEL                           = 44;
    const YY_NO_ACTION = 273;
    const YY_ACCEPT_ACTION = 272;
    const YY_ERROR_ACTION = 271;

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
    const YY_SZ_ACTTAB = 568;
static public $yy_action = array(
 /*     0 */   127,  128,   18,   96,  125,   19,  160,  100,   90,  260,
 /*    10 */    15,   76,   71,    6,  133,   18,   17,  134,   19,  173,
 /*    20 */   116,  104,  106,  103,  110,  109,   34,   15,   77,   70,
 /*    30 */   113,  133,   30,  147,   21,   75,  127,  128,  126,  145,
 /*    40 */    66,  169,  141,  112,  133,   11,   72,  151,  158,   12,
 /*    50 */    34,   21,   92,   70,   13,   80,  171,  147,  133,   75,
 /*    60 */    67,  156,  126,  145,   21,  169,  141,  151,   30,   43,
 /*    70 */   127,  128,   27,   28,  125,   89,   86,   93,   21,   80,
 /*    80 */   171,   35,  152,  120,   56,   79,   17,  163,  147,   94,
 /*    90 */    75,  127,  128,  146,  145,   43,  169,  141,  159,  127,
 /*   100 */   128,  108,   30,  125,   26,  111,  122,  118,  127,  128,
 /*   110 */   173,  116,  104,  106,  103,  110,  109,   35,  133,  163,
 /*   120 */    70,   74,  152,   30,  147,   22,   75,  111,  122,  146,
 /*   130 */   145,   30,  169,  141,   87,   43,   18,   47,   21,   19,
 /*   140 */    30,  100,  119,   85,  147,   46,   75,  168,  133,  126,
 /*   150 */   145,   78,  169,  141,  124,   61,  142,  139,   29,  153,
 /*   160 */     3,  127,  128,   93,   39,   22,   80,  171,   21,   35,
 /*   170 */   163,  147,   58,   24,  101,  140,  147,  144,   75,  169,
 /*   180 */   141,  146,  145,    5,  169,  141,   33,  129,  142,  167,
 /*   190 */    29,  105,    1,   30,    8,  118,   39,   69,  131,  134,
 /*   200 */    18,  243,  243,   19,  192,  100,  101,  140,   15,    9,
 /*   210 */    71,    9,  133,   38,   28,    5,  117,  114,   33,  129,
 /*   220 */     2,   24,  142,    9,   29,  155,    1,   20,  163,  142,
 /*   230 */    40,   29,   21,    1,  127,  128,   23,   39,  111,  122,
 /*   240 */    81,  140,   69,  131,  134,   73,  164,   84,  140,    5,
 /*   250 */    17,  154,   33,  129,  154,  130,    5,    9,   35,   33,
 /*   260 */   129,   65,  241,  241,   37,  147,   30,   75,  142,   88,
 /*   270 */   146,  145,   14,  169,  141,  161,   39,  142,  150,   29,
 /*   280 */   132,    7,  127,  128,  118,   39,  101,  140,  272,   36,
 /*   290 */   107,  114,  166,  115,  147,  101,  140,   25,   33,  129,
 /*   300 */   137,   45,  169,  141,   52,  121,  162,   33,  129,   10,
 /*   310 */    31,  147,  163,   75,   30,   44,  146,  145,   99,  169,
 /*   320 */   141,   18,   98,   52,   19,  163,  100,  123,  102,   82,
 /*   330 */   147,   71,   75,  133,    4,  146,  145,  134,  169,  141,
 /*   340 */   143,   28,  142,  147,   29,  157,    7,   95,  138,  136,
 /*   350 */    39,  169,  141,   21,   91,   68,  131,  165,   83,   16,
 /*   360 */    84,  140,   71,  149,  182,  148,  182,  182,   51,  182,
 /*   370 */   182,  182,   33,  129,  182,  147,  182,   75,  182,  182,
 /*   380 */   126,  145,  182,  169,  141,  127,  128,  182,   32,  182,
 /*   390 */    97,  135,  182,  182,  182,  147,  182,   75,  172,   63,
 /*   400 */   146,  145,  182,  169,  141,  142,  147,   29,   75,    7,
 /*   410 */   182,  146,  145,   41,  169,  141,  182,   30,  182,  127,
 /*   420 */   128,  182,  182,   42,  140,   52,  182,  182,  182,  182,
 /*   430 */   182,  182,  147,   93,   75,   33,  129,  146,  145,  182,
 /*   440 */   169,  141,  182,  182,  182,  182,  182,   59,  182,  170,
 /*   450 */   182,   30,  182,  182,  147,  182,   75,   60,  182,  146,
 /*   460 */   145,  182,  169,  141,  147,  182,   75,   57,  182,  146,
 /*   470 */   145,  182,  169,  141,  147,  182,   75,   55,  182,  146,
 /*   480 */   145,  182,  169,  141,  147,  182,   75,   50,  182,  146,
 /*   490 */   145,  182,  169,  141,  147,  182,   75,  182,  182,  146,
 /*   500 */   145,   49,  169,  141,  182,  182,  182,  182,  147,  182,
 /*   510 */    75,   64,  182,  146,  145,  182,  169,  141,  147,  182,
 /*   520 */    75,   54,  182,  146,  145,  182,  169,  141,  147,  182,
 /*   530 */    75,   48,  182,  146,  145,  182,  169,  141,  147,  182,
 /*   540 */    75,   62,  182,  146,  145,  182,  169,  141,  147,  182,
 /*   550 */    75,   53,  182,  146,  145,  182,  169,  141,  147,  182,
 /*   560 */    75,  182,  182,  146,  145,  182,  169,  141,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   12,   57,   11,   15,    1,   17,    3,   16,
 /*    10 */    20,   16,   22,   18,   24,   12,   23,   71,   15,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   51,   20,   38,   54,
 /*    30 */    53,   24,   39,   58,   44,   60,    7,    8,   63,   64,
 /*    40 */    52,   66,   67,    9,   24,   16,   41,   59,   43,   44,
 /*    50 */    51,   44,   77,   54,   20,   80,   81,   58,   24,   60,
 /*    60 */    52,    5,   63,   64,   44,   66,   67,   59,   39,   14,
 /*    70 */     7,    8,   56,   65,   11,   58,   77,   21,   44,   80,
 /*    80 */    81,   51,    1,   53,   54,   55,   23,   71,   58,   18,
 /*    90 */    60,    7,    8,   63,   64,   14,   66,   67,    5,    7,
 /*   100 */     8,    1,   39,   11,   56,   34,   35,   77,    7,    8,
 /*   110 */    26,   27,   28,   29,   30,   31,   32,   51,   24,   71,
 /*   120 */    54,   62,    1,   39,   58,   44,   60,   34,   35,   63,
 /*   130 */    64,   39,   66,   67,   40,   14,   12,   51,   44,   15,
 /*   140 */    39,   17,   42,   77,   58,   14,   60,    5,   24,   63,
 /*   150 */    64,   61,   66,   67,   11,   56,    6,   36,    8,    5,
 /*   160 */    10,    7,    8,   21,   14,   44,   80,   81,   44,   51,
 /*   170 */    71,   58,   54,   20,   24,   25,   58,   64,   60,   66,
 /*   180 */    67,   63,   64,   33,   66,   67,   36,   37,    6,   11,
 /*   190 */     8,    5,   10,   39,   16,   77,   14,   69,   70,   71,
 /*   200 */    12,   34,   35,   15,    5,   17,   24,   25,   20,   10,
 /*   210 */    22,   10,   24,   56,   65,   33,   48,   49,   36,   37,
 /*   220 */    21,   20,    6,   10,    8,    5,   10,   78,   71,    6,
 /*   230 */    14,    8,   44,   10,    7,    8,   23,   14,   34,   35,
 /*   240 */    24,   25,   69,   70,   71,   72,   73,   24,   25,   33,
 /*   250 */    23,   58,   36,   37,   58,   24,   33,   10,   51,   36,
 /*   260 */    37,   54,   34,   35,   68,   58,   39,   60,    6,   19,
 /*   270 */    63,   64,   10,   66,   67,   82,   14,    6,   82,    8,
 /*   280 */     5,   10,    7,    8,   77,   14,   24,   25,   46,   47,
 /*   290 */    48,   49,   11,    5,   58,   24,   25,   56,   36,   37,
 /*   300 */    64,   14,   66,   67,   51,    5,   24,   36,   37,   10,
 /*   310 */    56,   58,   71,   60,   39,   24,   63,   64,   24,   66,
 /*   320 */    67,   12,   24,   51,   15,   71,   17,   11,   75,   11,
 /*   330 */    58,   22,   60,   24,   79,   63,   64,   71,   66,   67,
 /*   340 */    76,   65,    6,   58,    8,    1,   10,   75,   70,   64,
 /*   350 */    14,   66,   67,   44,   24,   69,   70,   74,   50,   17,
 /*   360 */    24,   25,   22,   59,   83,   73,   83,   83,   51,   83,
 /*   370 */    83,   83,   36,   37,   83,   58,   83,   60,   83,   83,
 /*   380 */    63,   64,   83,   66,   67,    7,    8,   83,   51,   83,
 /*   390 */    53,   13,   83,   83,   83,   58,   83,   60,   81,   51,
 /*   400 */    63,   64,   83,   66,   67,    6,   58,    8,   60,   10,
 /*   410 */    83,   63,   64,   14,   66,   67,   83,   39,   83,    7,
 /*   420 */     8,   83,   83,   24,   25,   51,   83,   83,   83,   83,
 /*   430 */    83,   83,   58,   21,   60,   36,   37,   63,   64,   83,
 /*   440 */    66,   67,   83,   83,   83,   83,   83,   51,   83,   75,
 /*   450 */    83,   39,   83,   83,   58,   83,   60,   51,   83,   63,
 /*   460 */    64,   83,   66,   67,   58,   83,   60,   51,   83,   63,
 /*   470 */    64,   83,   66,   67,   58,   83,   60,   51,   83,   63,
 /*   480 */    64,   83,   66,   67,   58,   83,   60,   51,   83,   63,
 /*   490 */    64,   83,   66,   67,   58,   83,   60,   83,   83,   63,
 /*   500 */    64,   51,   66,   67,   83,   83,   83,   83,   58,   83,
 /*   510 */    60,   51,   83,   63,   64,   83,   66,   67,   58,   83,
 /*   520 */    60,   51,   83,   63,   64,   83,   66,   67,   58,   83,
 /*   530 */    60,   51,   83,   63,   64,   83,   66,   67,   58,   83,
 /*   540 */    60,   51,   83,   63,   64,   83,   66,   67,   58,   83,
 /*   550 */    60,   51,   83,   63,   64,   83,   66,   67,   58,   83,
 /*   560 */    60,   83,   83,   63,   64,   83,   66,   67,
);
    const YY_SHIFT_USE_DFLT = -11;
    const YY_SHIFT_MAX = 102;
    static public $yy_shift_ofst = array(
 /*     0 */     5,  223,  216,  223,  182,  150,  182,  336,  336,  271,
 /*    10 */   271,  271,  399,  271,  271,  271,  271,  271,  271,  271,
 /*    20 */   271,  271,  271,  271,  271,  -10,  188,  309,  262,  262,
 /*    30 */   262,  124,  412,   81,   -7,   84,    5,  121,   34,   94,
 /*    40 */    94,   94,  199,   94,    3,   20,   20,   63,  275,  378,
 /*    50 */    92,  227,   29,  154,  101,  101,   93,  101,   71,  101,
 /*    60 */   101,    7,  101,  101,  101,  204,   56,  142,    3,    3,
 /*    70 */   204,  330,  344,  340,  342,  250,  131,   55,  -11,   -5,
 /*    80 */   178,  201,  167,  100,  213,  228,  318,  291,  282,  300,
 /*    90 */   294,  299,  316,  298,  287,  143,  288,  220,  153,  186,
 /*   100 */   231,  247,  281,
);
    const YY_REDUCE_USE_DFLT = -55;
    const YY_REDUCE_MAX = 78;
    static public $yy_reduce_ofst = array(
 /*     0 */   242,  -25,   30,   -1,  207,   66,  118,   86,  317,  272,
 /*    10 */   253,  374,  337,  396,  436,  490,  470,  406,  450,  426,
 /*    20 */   460,  480,  500,  416,  348,  173,  173,  173,  236,  113,
 /*    30 */   285,  128,    8,  196,  149,  149,  168,  193,  -54,   16,
 /*    40 */   241,   48,  -12,  254,  286,  157,   99,  276,  276,  276,
 /*    50 */   276,  276,  276,  276,  276,  276,  255,  276,  255,  276,
 /*    60 */   276,  266,  276,  276,  276,  255,  304,  304,  278,  278,
 /*    70 */   255,  283,  308,  292,  264,   90,  -23,   17,   59,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 41, 43, 44, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
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
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(12, 15, 17, 20, 22, 24, 38, 44, ),
        /* 26 */ array(12, 15, 17, 20, 22, 24, 44, ),
        /* 27 */ array(12, 15, 17, 22, 24, 44, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 31 */ array(12, 15, 17, 24, 44, ),
        /* 32 */ array(7, 8, 21, 39, ),
        /* 33 */ array(1, 14, 44, ),
        /* 34 */ array(7, 8, 11, 16, 23, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 35 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 36 */ array(1, 3, 41, 43, 44, ),
        /* 37 */ array(1, 14, 36, 44, ),
        /* 38 */ array(9, 20, 24, 44, ),
        /* 39 */ array(24, 40, 44, ),
        /* 40 */ array(24, 40, 44, ),
        /* 41 */ array(24, 40, 44, ),
        /* 42 */ array(5, 10, 21, ),
        /* 43 */ array(24, 40, 44, ),
        /* 44 */ array(12, 15, ),
        /* 45 */ array(24, 44, ),
        /* 46 */ array(24, 44, ),
        /* 47 */ array(7, 8, 11, 23, 39, ),
        /* 48 */ array(5, 7, 8, 39, ),
        /* 49 */ array(7, 8, 13, 39, ),
        /* 50 */ array(7, 8, 11, 39, ),
        /* 51 */ array(7, 8, 23, 39, ),
        /* 52 */ array(7, 8, 16, 39, ),
        /* 53 */ array(5, 7, 8, 39, ),
        /* 54 */ array(7, 8, 39, ),
        /* 55 */ array(7, 8, 39, ),
        /* 56 */ array(5, 34, 35, ),
        /* 57 */ array(7, 8, 39, ),
        /* 58 */ array(18, 34, 35, ),
        /* 59 */ array(7, 8, 39, ),
        /* 60 */ array(7, 8, 39, ),
        /* 61 */ array(20, 24, 44, ),
        /* 62 */ array(7, 8, 39, ),
        /* 63 */ array(7, 8, 39, ),
        /* 64 */ array(7, 8, 39, ),
        /* 65 */ array(34, 35, ),
        /* 66 */ array(5, 21, ),
        /* 67 */ array(5, 21, ),
        /* 68 */ array(12, 15, ),
        /* 69 */ array(12, 15, ),
        /* 70 */ array(34, 35, ),
        /* 71 */ array(24, ),
        /* 72 */ array(1, ),
        /* 73 */ array(22, ),
        /* 74 */ array(17, ),
        /* 75 */ array(19, ),
        /* 76 */ array(14, ),
        /* 77 */ array(14, ),
        /* 78 */ array(),
        /* 79 */ array(16, 18, ),
        /* 80 */ array(11, 16, ),
        /* 81 */ array(10, 20, ),
        /* 82 */ array(34, 35, ),
        /* 83 */ array(1, 42, ),
        /* 84 */ array(10, 23, ),
        /* 85 */ array(34, 35, ),
        /* 86 */ array(11, ),
        /* 87 */ array(24, ),
        /* 88 */ array(24, ),
        /* 89 */ array(5, ),
        /* 90 */ array(24, ),
        /* 91 */ array(10, ),
        /* 92 */ array(11, ),
        /* 93 */ array(24, ),
        /* 94 */ array(14, ),
        /* 95 */ array(11, ),
        /* 96 */ array(5, ),
        /* 97 */ array(5, ),
        /* 98 */ array(20, ),
        /* 99 */ array(5, ),
        /* 100 */ array(24, ),
        /* 101 */ array(10, ),
        /* 102 */ array(11, ),
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
        /* 171 */ array(),
        /* 172 */ array(),
        /* 173 */ array(),
);
    static public $yy_default = array(
 /*     0 */   271,  271,  271,  271,  271,  271,  271,  271,  271,  236,
 /*    10 */   236,  236,  271,  271,  271,  271,  271,  271,  271,  271,
 /*    20 */   271,  271,  271,  271,  271,  220,  220,  220,  271,  271,
 /*    30 */   271,  220,  192,  271,  245,  245,  174,  271,  271,  271,
 /*    40 */   271,  271,  212,  271,  220,  271,  271,  260,  271,  271,
 /*    50 */   271,  260,  235,  271,  240,  221,  271,  262,  271,  188,
 /*    60 */   261,  271,  196,  193,  246,  247,  271,  271,  217,  215,
 /*    70 */   271,  271,  271,  227,  198,  197,  271,  271,  239,  271,
 /*    80 */   271,  212,  244,  271,  212,  242,  241,  271,  271,  271,
 /*    90 */   271,  230,  241,  271,  271,  271,  271,  271,  271,  271,
 /*   100 */   271,  212,  271,  252,  250,  184,  251,  175,  269,  254,
 /*   110 */   253,  255,  189,  195,  177,  186,  249,  176,  241,  178,
 /*   120 */   194,  187,  256,  243,  232,  214,  199,  205,  204,  213,
 /*   130 */   216,  218,  226,  225,  224,  222,  203,  202,  219,  211,
 /*   140 */   210,  209,  207,  238,  201,  200,  199,  206,  229,  190,
 /*   150 */   265,  191,  268,  267,  266,  182,  183,  270,  179,  185,
 /*   160 */   180,  264,  237,  223,  228,  231,  233,  257,  181,  208,
 /*   170 */   234,  258,  259,  248,
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
    const YYNOCODE = 84;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 174;
    const YYNRULE = 97;
    const YYERRORSYMBOL = 45;
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
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*     UNDERL => OTHER */
    0,  /* COMMENTSTART => nothing */
    0,  /* COMMENTEND => nothing */
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
  'RDELS',         'RDEL',          'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NOT',           'LAND',          'LOR',         
  'QUOTE',         'BOOLEAN',       'IN',            'ANDSYM',      
  'UNDERL',        'COMMENTSTART',  'COMMENTEND',    'PHP',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'ifexprs',       'statements',  
  'varvar',        'foraction',     'variable',      'attribute',   
  'exprs',         'modifier',      'modparameters',  'array',       
  'value',         'math',          'object',        'function',    
  'doublequoted',  'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'method',        'params',      
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'doublequotedcontent',
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
 /*  10 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  11 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  12 */ "smartytag ::= LDEL ID SPACE statements SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN variable RDEL",
 /*  14 */ "foraction ::= EQUAL expr",
 /*  15 */ "foraction ::= INCDEC",
 /*  16 */ "attributes ::= attributes attribute",
 /*  17 */ "attributes ::= attribute",
 /*  18 */ "attributes ::=",
 /*  19 */ "attribute ::= SPACE ID EQUAL expr",
 /*  20 */ "statements ::= statement",
 /*  21 */ "statements ::= statements COMMA statement",
 /*  22 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  23 */ "expr ::= exprs",
 /*  24 */ "expr ::= exprs modifier modparameters",
 /*  25 */ "expr ::= array",
 /*  26 */ "exprs ::= value",
 /*  27 */ "exprs ::= UNIMATH value",
 /*  28 */ "exprs ::= expr math value",
 /*  29 */ "exprs ::= expr ANDSYM value",
 /*  30 */ "math ::= UNIMATH",
 /*  31 */ "math ::= MATH",
 /*  32 */ "value ::= variable",
 /*  33 */ "value ::= NUMBER",
 /*  34 */ "value ::= object",
 /*  35 */ "value ::= function",
 /*  36 */ "value ::= SI_QSTR",
 /*  37 */ "value ::= QUOTE doublequoted QUOTE",
 /*  38 */ "value ::= ID",
 /*  39 */ "value ::= BOOLEAN",
 /*  40 */ "value ::= OPENP expr CLOSEP",
 /*  41 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  42 */ "variable ::= DOLLAR varvar COLON ID",
 /*  43 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  44 */ "vararraydefs ::= vararraydef",
 /*  45 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  46 */ "vararraydefs ::=",
 /*  47 */ "vararraydef ::= DOT expr",
 /*  48 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  49 */ "varvar ::= varvarele",
 /*  50 */ "varvar ::= varvar varvarele",
 /*  51 */ "varvarele ::= ID",
 /*  52 */ "varvarele ::= LDEL expr RDEL",
 /*  53 */ "object ::= DOLLAR varvar objectchain",
 /*  54 */ "objectchain ::= objectelement",
 /*  55 */ "objectchain ::= objectchain objectelement",
 /*  56 */ "objectelement ::= PTR ID",
 /*  57 */ "objectelement ::= PTR method",
 /*  58 */ "function ::= ID OPENP params CLOSEP",
 /*  59 */ "method ::= ID OPENP params CLOSEP",
 /*  60 */ "params ::= expr COMMA params",
 /*  61 */ "params ::= expr",
 /*  62 */ "params ::=",
 /*  63 */ "modifier ::= VERT ID",
 /*  64 */ "modparameters ::= modparameters modparameter",
 /*  65 */ "modparameters ::=",
 /*  66 */ "modparameter ::= COLON expr",
 /*  67 */ "ifexprs ::= ifexpr",
 /*  68 */ "ifexprs ::= NOT ifexpr",
 /*  69 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  70 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  71 */ "ifexpr ::= expr",
 /*  72 */ "ifexpr ::= expr ifcond expr",
 /*  73 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  74 */ "ifcond ::= EQUALS",
 /*  75 */ "ifcond ::= NOTEQUALS",
 /*  76 */ "ifcond ::= GREATERTHAN",
 /*  77 */ "ifcond ::= LESSTHAN",
 /*  78 */ "ifcond ::= GREATEREQUAL",
 /*  79 */ "ifcond ::= LESSEQUAL",
 /*  80 */ "ifcond ::= IDENTITY",
 /*  81 */ "lop ::= LAND",
 /*  82 */ "lop ::= LOR",
 /*  83 */ "array ::= OPENP arrayelements CLOSEP",
 /*  84 */ "arrayelements ::= arrayelement",
 /*  85 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  86 */ "arrayelement ::= expr",
 /*  87 */ "arrayelement ::= expr APTR expr",
 /*  88 */ "arrayelement ::= ID APTR expr",
 /*  89 */ "arrayelement ::= array",
 /*  90 */ "doublequoted ::= doublequoted doublequotedcontent",
 /*  91 */ "doublequoted ::= doublequotedcontent",
 /*  92 */ "doublequotedcontent ::= variable",
 /*  93 */ "doublequotedcontent ::= LDEL expr RDEL",
 /*  94 */ "doublequotedcontent ::= OTHER",
 /*  95 */ "text ::= text OTHER",
 /*  96 */ "text ::= OTHER",
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
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 5 ),
  array( 'lhs' => 49, 'rhs' => 11 ),
  array( 'lhs' => 49, 'rhs' => 8 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 0 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 0 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 0 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        26 => 0,
        32 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        39 => 0,
        84 => 0,
        1 => 1,
        23 => 1,
        25 => 1,
        30 => 1,
        31 => 1,
        44 => 1,
        49 => 1,
        67 => 1,
        91 => 1,
        94 => 1,
        96 => 1,
        2 => 2,
        45 => 2,
        90 => 2,
        95 => 2,
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
        17 => 15,
        61 => 15,
        86 => 15,
        89 => 15,
        16 => 16,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        24 => 24,
        27 => 27,
        28 => 28,
        29 => 29,
        37 => 37,
        38 => 38,
        40 => 40,
        41 => 41,
        53 => 41,
        42 => 42,
        43 => 43,
        46 => 46,
        65 => 46,
        47 => 47,
        48 => 48,
        50 => 50,
        51 => 51,
        52 => 52,
        69 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 56,
        58 => 58,
        59 => 59,
        60 => 60,
        62 => 62,
        63 => 63,
        64 => 64,
        66 => 66,
        68 => 68,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 72,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        83 => 83,
        85 => 85,
        87 => 87,
        88 => 87,
        92 => 92,
        93 => 93,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 67 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1439 "internal.templateparser.php"
#line 73 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1442 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1445 "internal.templateparser.php"
#line 81 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1450 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->template->cacher_object->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1453 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r5(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->template->cacher_object->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1459 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->template->cacher_object->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1462 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1465 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1468 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1471 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1474 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1477 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1480 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1483 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1486 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1489 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1492 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = array();    }
#line 1495 "internal.templateparser.php"
#line 127 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1498 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1501 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r21(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1504 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1507 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r24(){if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -1]->minor, $this->compiler)) {
																					                      if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
																					                        $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
																					                      } else {
                                                                  $this->_retvalue = "\$_smarty_tpl->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";
                                                                }
                                                               }    }
#line 1516 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1519 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1522 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1525 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1528 "internal.templateparser.php"
#line 188 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1531 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1534 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1537 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1540 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1543 "internal.templateparser.php"
#line 209 "internal.templateparser.y"
    function yy_r46(){return;    }
#line 1546 "internal.templateparser.php"
#line 211 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1549 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1552 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1555 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1558 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1561 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1564 "internal.templateparser.php"
#line 232 "internal.templateparser.y"
    function yy_r55(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1567 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1570 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r58(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       //   $this->_retvalue = "\$_smarty_tpl->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
                                                       $this->compiler->trigger_template_error ("unknown fuction\"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1580 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1583 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1586 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r62(){ return;    }
#line 1589 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1592 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1595 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1598 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1601 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1604 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1607 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1610 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '==';    }
#line 1613 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '!=';    }
#line 1616 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '>';    }
#line 1619 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '<';    }
#line 1622 "internal.templateparser.php"
#line 297 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '>=';    }
#line 1625 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '<=';    }
#line 1628 "internal.templateparser.php"
#line 299 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '===';    }
#line 1631 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '&&';    }
#line 1634 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '||';    }
#line 1637 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1640 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1643 "internal.templateparser.php"
#line 308 "internal.templateparser.y"
    function yy_r87(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1646 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1649 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1652 "internal.templateparser.php"

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
#line 52 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1769 "internal.templateparser.php"
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
#line 44 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1794 "internal.templateparser.php"
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
