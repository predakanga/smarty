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
    const TP_NONEIDENTITY                   = 33;
    const TP_NOT                            = 34;
    const TP_LAND                           = 35;
    const TP_LOR                            = 36;
    const TP_QUOTE                          = 37;
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
    const YY_NO_ACTION = 312;
    const YY_ACCEPT_ACTION = 311;
    const YY_ERROR_ACTION = 310;

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
    const YY_SZ_ACTTAB = 555;
static public $yy_action = array(
 /*     0 */   159,  167,  145,  139,  149,  116,   22,  159,  167,   21,
 /*    10 */   311,   38,  132,  130,   19,  112,   11,  126,  186,  180,
 /*    20 */   197,  144,  117,  123,  121,  119,  118,   22,   20,   37,
 /*    30 */    21,  127,  186,   25,   62,   94,  107,   85,  160,  170,
 /*    40 */    25,  168,   86,  105,  164,   15,  159,  167,   90,  147,
 /*    50 */   159,  167,  142,  171,  149,  125,  163,  188,   24,   15,
 /*    60 */     2,   83,    6,    5,   42,  180,  197,  144,  117,  123,
 /*    70 */   121,  119,  118,   22,   95,  184,   21,  175,  109,   25,
 /*    80 */    47,   37,    8,   25,    3,  186,   67,   26,  151,   99,
 /*    90 */   160,  170,  225,  168,   86,  120,  164,  134,   80,    8,
 /*   100 */   169,  147,  141,  107,  100,   55,   99,  125,  100,   18,
 /*   110 */     1,   98,   15,  115,  160,  170,  103,  168,   86,   49,
 /*   120 */   164,  124,  122,  181,   48,  147,  178,    7,  163,  113,
 /*   130 */    24,  163,    2,   24,    6,   16,   43,    6,   75,   42,
 /*   140 */   191,  159,  167,  187,  159,  167,   93,  184,  177,   95,
 /*   150 */   184,   33,  154,  169,  188,   18,    3,   14,  195,   26,
 /*   160 */   151,  115,   26,  151,  152,   31,   37,   10,   19,  147,
 /*   170 */    22,   77,  186,   21,   25,  160,  170,   25,  168,   86,
 /*   180 */   146,  164,  195,  163,  150,   37,  147,   16,   30,  173,
 /*   190 */    74,   42,  125,  147,  160,  170,  185,  168,   86,   15,
 /*   200 */   164,   95,  184,  192,  196,  147,  142,  115,   36,   42,
 /*   210 */   135,  125,    8,   65,   26,  151,   92,  160,  170,   99,
 /*   220 */   168,   86,   13,  164,   89,  159,  167,  160,  147,   39,
 /*   230 */    57,   87,  162,  164,  125,   60,   51,  186,  147,  160,
 /*   240 */   148,   23,  168,   86,  192,  164,   12,  163,   85,   24,
 /*   250 */   147,   16,   34,    6,   17,   41,  141,  193,   25,   97,
 /*   260 */   157,  159,  167,  160,   15,   28,  184,   88,  137,  164,
 /*   270 */   159,  167,   57,  182,  147,   79,  158,  169,   26,  151,
 /*   280 */   185,  160,  148,   82,  168,   86,  165,  164,  171,  111,
 /*   290 */   108,   73,  147,   42,   25,  159,  167,  129,   55,  179,
 /*   300 */   147,  177,  153,   25,   27,  145,  139,  160,  170,  115,
 /*   310 */   168,   86,  142,  164,   55,  150,   96,  115,  147,  192,
 /*   320 */    51,   76,  156,  160,  170,  142,  168,   86,   25,  164,
 /*   330 */    12,   32,  106,  102,  147,  131,  130,    9,  114,   22,
 /*   340 */   160,  170,   21,  168,   86,   81,  164,    9,  160,  145,
 /*   350 */   139,  147,   84,   22,  164,  177,   21,   54,   23,  147,
 /*   360 */   138,   19,  141,   13,  174,  186,  160,  170,  104,  168,
 /*   370 */    86,   70,  164,  183,    6,  141,   42,  147,   59,  147,
 /*   380 */    35,   71,  115,  107,  145,  139,  192,   72,  128,  155,
 /*   390 */   160,  170,   15,  168,   86,  190,  164,   53,   46,   29,
 /*   400 */   110,  147,   45,   78,  189,   91,  160,  170,  133,  168,
 /*   410 */    86,  137,  164,  177,  192,   58,  176,  147,  161,   40,
 /*   420 */   137,  140,   23,  194,  160,  170,  101,  168,   86,   61,
 /*   430 */   164,   50,  166,    4,  188,  147,  115,   42,  160,  170,
 /*   440 */    52,  168,   86,  136,  164,  165,  172,  143,   44,  147,
 /*   450 */    69,  205,  205,  205,  205,  205,  205,  205,  205,  160,
 /*   460 */   170,  205,  168,   86,  205,  164,   66,  205,  205,  205,
 /*   470 */   147,  205,  205,  205,  205,  160,  170,  205,  168,   86,
 /*   480 */   205,  164,  205,   56,  205,  205,  147,  205,  205,  205,
 /*   490 */   205,  205,  160,  170,  205,  168,   86,   64,  164,  205,
 /*   500 */   205,  205,  205,  147,  205,  205,  160,  170,  205,  168,
 /*   510 */    86,  205,  164,  205,  205,  205,  205,  147,   63,  205,
 /*   520 */   205,  205,  205,  205,  205,  205,  205,  160,  170,  205,
 /*   530 */   168,   86,  205,  164,   68,  205,  205,  205,  147,  205,
 /*   540 */   205,  205,  205,  160,  170,  205,  168,   86,  205,  164,
 /*   550 */   205,  205,  205,  205,  147,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   35,   36,   11,   14,   12,    7,    8,   15,
 /*    10 */    53,   54,   55,   56,   20,   24,   16,    9,   24,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   33,   12,   20,   58,
 /*    30 */    15,   60,   24,   40,   63,   64,   42,   22,   67,   68,
 /*    40 */    40,   70,   71,   66,   73,   51,    7,    8,   76,   78,
 /*    50 */     7,    8,    1,   81,   11,   84,    6,   80,    8,   51,
 /*    60 */    10,   16,   12,   18,   14,   26,   27,   28,   29,   30,
 /*    70 */    31,   32,   33,   12,   24,   25,   15,    1,    2,   40,
 /*    80 */     4,   58,   10,   40,   34,   24,   63,   37,   38,   17,
 /*    90 */    67,   68,    3,   70,   71,   44,   73,    3,   77,   10,
 /*   100 */    79,   78,   51,   42,   19,   58,   17,   84,   19,   17,
 /*   110 */    21,   22,   51,   21,   67,   68,   22,   70,   71,   43,
 /*   120 */    73,   45,   46,   47,   48,   78,   50,   51,    6,   82,
 /*   130 */     8,    6,   10,    8,   12,   10,   14,   12,   59,   14,
 /*   140 */     3,    7,    8,    3,    7,    8,   24,   25,   69,   24,
 /*   150 */    25,   77,   75,   79,   80,   17,   34,   23,   67,   37,
 /*   160 */    38,   21,   37,   38,   13,   74,   58,   16,   20,   78,
 /*   170 */    12,   63,   24,   15,   40,   67,   68,   40,   70,   71,
 /*   180 */    89,   73,   67,    6,   83,   58,   78,   10,   65,    3,
 /*   190 */    63,   14,   84,   78,   67,   68,    1,   70,   71,   51,
 /*   200 */    73,   24,   25,   80,   89,   78,    1,   21,   58,   14,
 /*   210 */     5,   84,   10,   63,   37,   38,   61,   67,   68,   17,
 /*   220 */    70,   71,   20,   73,   62,    7,    8,   67,   78,   65,
 /*   230 */    58,   71,   37,   73,   84,   57,   41,   24,   78,   67,
 /*   240 */    68,   72,   70,   71,   80,   73,   51,    6,   22,    8,
 /*   250 */    78,   10,   62,   12,   85,   14,   51,    3,   40,   87,
 /*   260 */    88,    7,    8,   67,   51,   24,   25,   71,   90,   73,
 /*   270 */     7,    8,   58,    3,   78,   77,   13,   79,   37,   38,
 /*   280 */     1,   67,   68,   76,   70,   71,   79,   73,   81,   67,
 /*   290 */    68,   59,   78,   14,   40,    7,    8,    3,   58,    3,
 /*   300 */    78,   69,   88,   40,   65,   35,   36,   67,   68,   21,
 /*   310 */    70,   71,    1,   73,   58,   83,   24,   21,   78,   80,
 /*   320 */    41,   17,   82,   67,   68,    1,   70,   71,   40,   73,
 /*   330 */    51,   58,   18,   60,   78,   55,   56,   10,   82,   12,
 /*   340 */    67,   68,   15,   70,   71,   59,   73,   10,   67,   35,
 /*   350 */    36,   78,   71,   12,   73,   69,   15,   58,   72,   78,
 /*   360 */    11,   20,   51,   20,    3,   24,   67,   68,   67,   70,
 /*   370 */    71,   65,   73,   49,   12,   51,   14,   78,   57,   78,
 /*   380 */    39,   58,   21,   42,   35,   36,   80,   57,    3,   11,
 /*   390 */    67,   68,   51,   70,   71,   11,   73,   58,   24,   65,
 /*   400 */    24,   78,   14,   59,   24,   61,   67,   68,    3,   70,
 /*   410 */    71,   90,   73,   69,   80,   58,    3,   78,   24,   24,
 /*   420 */    90,    3,   72,   41,   67,   68,   24,   70,   71,   58,
 /*   430 */    73,   24,   81,   86,   80,   78,   21,   14,   67,   68,
 /*   440 */    75,   70,   71,   90,   73,   79,   69,   60,   14,   78,
 /*   450 */    58,   91,   91,   91,   91,   91,   91,   91,   91,   67,
 /*   460 */    68,   91,   70,   71,   91,   73,   58,   91,   91,   91,
 /*   470 */    78,   91,   91,   91,   91,   67,   68,   91,   70,   71,
 /*   480 */    91,   73,   91,   58,   91,   91,   78,   91,   91,   91,
 /*   490 */    91,   91,   67,   68,   91,   70,   71,   58,   73,   91,
 /*   500 */    91,   91,   91,   78,   91,   91,   67,   68,   91,   70,
 /*   510 */    71,   91,   73,   91,   91,   91,   91,   78,   58,   91,
 /*   520 */    91,   91,   91,   91,   91,   91,   91,   67,   68,   91,
 /*   530 */    70,   71,   91,   73,   58,   91,   91,   91,   78,   91,
 /*   540 */    91,   91,   91,   67,   68,   91,   70,   71,   91,   73,
 /*   550 */    91,   91,   91,   91,   78,
);
    const YY_SHIFT_USE_DFLT = -34;
    const YY_SHIFT_MAX = 116;
    static public $yy_shift_ofst = array(
 /*     0 */    76,  122,   50,   50,   50,   50,  125,  241,  125,  125,
 /*    10 */   125,  125,  125,  125,  125,  125,  125,  125,  125,  125,
 /*    20 */   125,  125,  125,  177,  177,  177,  279,  341,   89,   -6,
 /*    30 */    61,  195,  288,   15,   92,  362,   -7,   39,   76,    8,
 /*    40 */   327,  213,  213,  213,  213,  213,  158,  311,  311,  311,
 /*    50 */   415,  423,  226,  137,  263,    0,   43,  134,  254,  324,
 /*    60 */   205,  218,  270,  218,  218,  349,  218,  314,  218,  218,
 /*    70 */   148,  218,   51,  296,  -33,  361,   -9,  -33,  186,  158,
 /*    80 */   158,  140,  226,  434,   85,  395,   85,   85,   85,  138,
 /*    90 */   226,  -34,  -34,  202,   45,   72,   94,  151,  407,  304,
 /*   100 */   394,  405,  413,  402,  382,  418,  388,  380,  385,  292,
 /*   110 */   343,  294,  337,  378,  384,  376,  374,
);
    const YY_REDUCE_USE_DFLT = -44;
    const YY_REDUCE_MAX = 92;
    static public $yy_reduce_ofst = array(
 /*     0 */   -43,  -29,  150,  127,  108,   23,  172,  273,  256,   47,
 /*    10 */   214,  240,  357,  408,  392,  339,  425,  460,  439,  476,
 /*    20 */   371,  323,  299,  281,  160,  196,   91,   74,  344,   74,
 /*    30 */    74,  115,  286,  207,  232,  222,  169,  169,  280,  -23,
 /*    40 */   198,  334,  123,  239,  306,  164,   21,  178,  321,  330,
 /*    50 */    79,  301,  -28,  350,  350,  350,  350,  350,  350,  353,
 /*    60 */   353,  350,  347,  350,  350,  347,  350,  347,  350,  350,
 /*    70 */   354,  350,  353,  377,  347,  377,  365,  347,  377,  366,
 /*    80 */   366,  377,  351,  387,  155,   77,  155,  155,  155,  101,
 /*    90 */   351,  190,  162,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 43, 45, 46, 47, 48, 50, 51, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 25, 34, 37, 38, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 25, 34, 37, 38, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 25, 34, 37, 38, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 25, 34, 37, 38, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 25, 34, 37, 38, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 21 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 22 */ array(6, 8, 10, 12, 14, 24, 25, 37, 38, ),
        /* 23 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 24 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 25 */ array(6, 10, 14, 24, 25, 37, 38, ),
        /* 26 */ array(1, 14, 41, 51, ),
        /* 27 */ array(12, 15, 20, 24, 39, 42, 51, ),
        /* 28 */ array(3, 10, 17, 19, 21, 22, ),
        /* 29 */ array(12, 15, 20, 24, 42, 51, ),
        /* 30 */ array(12, 15, 24, 42, 51, ),
        /* 31 */ array(1, 14, 37, 41, 51, ),
        /* 32 */ array(7, 8, 21, 40, ),
        /* 33 */ array(12, 15, 22, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(12, 14, ),
        /* 36 */ array(7, 8, 11, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 37 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 38 */ array(1, 2, 4, 43, 45, 46, 47, 48, 50, 51, ),
        /* 39 */ array(9, 20, 24, 51, ),
        /* 40 */ array(10, 12, 15, ),
        /* 41 */ array(24, 51, ),
        /* 42 */ array(24, 51, ),
        /* 43 */ array(24, 51, ),
        /* 44 */ array(24, 51, ),
        /* 45 */ array(24, 51, ),
        /* 46 */ array(12, 15, ),
        /* 47 */ array(1, 51, ),
        /* 48 */ array(1, 51, ),
        /* 49 */ array(1, 51, ),
        /* 50 */ array(21, ),
        /* 51 */ array(14, ),
        /* 52 */ array(22, ),
        /* 53 */ array(3, 7, 8, 40, ),
        /* 54 */ array(7, 8, 13, 40, ),
        /* 55 */ array(7, 8, 16, 40, ),
        /* 56 */ array(7, 8, 11, 40, ),
        /* 57 */ array(7, 8, 23, 40, ),
        /* 58 */ array(3, 7, 8, 40, ),
        /* 59 */ array(1, 49, 51, ),
        /* 60 */ array(1, 5, 51, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(3, 35, 36, ),
        /* 63 */ array(7, 8, 40, ),
        /* 64 */ array(7, 8, 40, ),
        /* 65 */ array(11, 35, 36, ),
        /* 66 */ array(7, 8, 40, ),
        /* 67 */ array(18, 35, 36, ),
        /* 68 */ array(7, 8, 40, ),
        /* 69 */ array(7, 8, 40, ),
        /* 70 */ array(20, 24, 51, ),
        /* 71 */ array(7, 8, 40, ),
        /* 72 */ array(1, 44, 51, ),
        /* 73 */ array(3, 21, ),
        /* 74 */ array(35, 36, ),
        /* 75 */ array(3, 21, ),
        /* 76 */ array(14, 24, ),
        /* 77 */ array(35, 36, ),
        /* 78 */ array(3, 21, ),
        /* 79 */ array(12, 15, ),
        /* 80 */ array(12, 15, ),
        /* 81 */ array(3, 21, ),
        /* 82 */ array(22, ),
        /* 83 */ array(14, ),
        /* 84 */ array(19, ),
        /* 85 */ array(24, ),
        /* 86 */ array(19, ),
        /* 87 */ array(19, ),
        /* 88 */ array(19, ),
        /* 89 */ array(17, ),
        /* 90 */ array(22, ),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(10, 17, 20, ),
        /* 94 */ array(16, 18, ),
        /* 95 */ array(10, 17, ),
        /* 96 */ array(3, 22, ),
        /* 97 */ array(13, 16, ),
        /* 98 */ array(24, ),
        /* 99 */ array(17, ),
        /* 100 */ array(24, ),
        /* 101 */ array(3, ),
        /* 102 */ array(3, ),
        /* 103 */ array(24, ),
        /* 104 */ array(41, ),
        /* 105 */ array(3, ),
        /* 106 */ array(14, ),
        /* 107 */ array(24, ),
        /* 108 */ array(3, ),
        /* 109 */ array(24, ),
        /* 110 */ array(20, ),
        /* 111 */ array(3, ),
        /* 112 */ array(10, ),
        /* 113 */ array(11, ),
        /* 114 */ array(11, ),
        /* 115 */ array(24, ),
        /* 116 */ array(24, ),
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
 /*     0 */   310,  310,  310,  310,  310,  310,  296,  310,  272,  272,
 /*    10 */   310,  272,  310,  310,  310,  310,  310,  310,  310,  310,
 /*    20 */   310,  310,  310,  310,  310,  310,  310,  256,  248,  256,
 /*    30 */   256,  310,  225,  251,  225,  310,  280,  280,  198,  310,
 /*    40 */   256,  310,  310,  310,  310,  310,  256,  310,  310,  310,
 /*    50 */   225,  310,  244,  310,  310,  271,  310,  297,  310,  310,
 /*    60 */   310,  221,  310,  281,  276,  310,  226,  310,  229,  298,
 /*    70 */   310,  257,  310,  310,  278,  310,  310,  282,  310,  266,
 /*    80 */   247,  310,  263,  310,  234,  310,  232,  233,  235,  238,
 /*    90 */   245,  275,  275,  248,  310,  248,  310,  310,  310,  310,
 /*   100 */   310,  310,  310,  310,  310,  310,  310,  310,  310,  310,
 /*   110 */   310,  310,  246,  310,  310,  310,  310,  286,  290,  289,
 /*   120 */   203,  288,  205,  287,  204,  277,  222,  227,  220,  219,
 /*   130 */   201,  200,  199,  216,  215,  202,  306,  307,  279,  292,
 /*   140 */   218,  309,  308,  228,  285,  291,  301,  253,  231,  250,
 /*   150 */   274,  249,  293,  295,  267,  269,  270,  294,  258,  237,
 /*   160 */   239,  273,  243,  240,  241,  255,  265,  236,  230,  254,
 /*   170 */   231,  264,  223,  212,  213,  209,  211,  224,  208,  214,
 /*   180 */   283,  206,  217,  207,  242,  305,  261,  210,  260,  252,
 /*   190 */   268,  262,  259,  304,  303,  302,  300,  284,
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
    const YYNRULE = 112;
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
    1,  /*    SI_QSTR => OTHER */
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
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NONEIDENTITY',  'NOT',           'LAND',        
  'LOR',           'QUOTE',         'BOOLEAN',       'IN',          
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
 /*  33 */ "expr ::= array",
 /*  34 */ "exprs ::= value",
 /*  35 */ "exprs ::= UNIMATH value",
 /*  36 */ "exprs ::= expr math value",
 /*  37 */ "exprs ::= expr ANDSYM value",
 /*  38 */ "math ::= UNIMATH",
 /*  39 */ "math ::= MATH",
 /*  40 */ "value ::= value modifier modparameters",
 /*  41 */ "value ::= variable",
 /*  42 */ "value ::= NUMBER",
 /*  43 */ "value ::= function",
 /*  44 */ "value ::= SI_QSTR",
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
 /*  56 */ "vararraydefs ::= vararraydef",
 /*  57 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  58 */ "vararraydefs ::=",
 /*  59 */ "vararraydef ::= DOT expr",
 /*  60 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  61 */ "varvar ::= varvarele",
 /*  62 */ "varvar ::= varvar varvarele",
 /*  63 */ "varvarele ::= ID",
 /*  64 */ "varvarele ::= LDEL expr RDEL",
 /*  65 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  66 */ "objectchain ::= objectelement",
 /*  67 */ "objectchain ::= objectchain objectelement",
 /*  68 */ "objectelement ::= PTR ID vararraydefs",
 /*  69 */ "objectelement ::= PTR method",
 /*  70 */ "function ::= ID OPENP params CLOSEP",
 /*  71 */ "method ::= ID OPENP params CLOSEP",
 /*  72 */ "params ::= expr COMMA params",
 /*  73 */ "params ::= expr",
 /*  74 */ "params ::=",
 /*  75 */ "modifier ::= VERT ID",
 /*  76 */ "modparameters ::= modparameters modparameter",
 /*  77 */ "modparameters ::=",
 /*  78 */ "modparameter ::= COLON expr",
 /*  79 */ "ifexprs ::= ifexpr",
 /*  80 */ "ifexprs ::= NOT ifexprs",
 /*  81 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  82 */ "ifexpr ::= expr",
 /*  83 */ "ifexpr ::= expr ifcond expr",
 /*  84 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  85 */ "ifcond ::= EQUALS",
 /*  86 */ "ifcond ::= NOTEQUALS",
 /*  87 */ "ifcond ::= GREATERTHAN",
 /*  88 */ "ifcond ::= LESSTHAN",
 /*  89 */ "ifcond ::= GREATEREQUAL",
 /*  90 */ "ifcond ::= LESSEQUAL",
 /*  91 */ "ifcond ::= IDENTITY",
 /*  92 */ "ifcond ::= NONEIDENTITY",
 /*  93 */ "lop ::= LAND",
 /*  94 */ "lop ::= LOR",
 /*  95 */ "array ::= OPENB arrayelements CLOSEB",
 /*  96 */ "arrayelements ::= arrayelement",
 /*  97 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  98 */ "arrayelements ::=",
 /*  99 */ "arrayelement ::= expr",
 /* 100 */ "arrayelement ::= expr APTR expr",
 /* 101 */ "arrayelement ::= array",
 /* 102 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 103 */ "doublequoted ::= doublequotedcontent",
 /* 104 */ "doublequotedcontent ::= variable",
 /* 105 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 106 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 107 */ "doublequotedcontent ::= OTHER",
 /* 108 */ "text ::= text textelement",
 /* 109 */ "text ::= textelement",
 /* 110 */ "textelement ::= OTHER",
 /* 111 */ "textelement ::= LDEL",
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
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
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
  array( 'lhs' => 77, 'rhs' => 1 ),
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
  array( 'lhs' => 88, 'rhs' => 1 ),
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
        34 => 0,
        41 => 0,
        42 => 0,
        43 => 0,
        44 => 0,
        51 => 0,
        55 => 0,
        96 => 0,
        1 => 1,
        32 => 1,
        33 => 1,
        38 => 1,
        39 => 1,
        56 => 1,
        61 => 1,
        79 => 1,
        103 => 1,
        109 => 1,
        110 => 1,
        111 => 1,
        2 => 2,
        57 => 2,
        102 => 2,
        108 => 2,
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
        73 => 24,
        99 => 24,
        101 => 24,
        25 => 25,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        35 => 35,
        36 => 36,
        37 => 37,
        40 => 40,
        45 => 45,
        46 => 46,
        47 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        58 => 58,
        77 => 58,
        59 => 59,
        60 => 60,
        62 => 62,
        63 => 63,
        64 => 64,
        81 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        74 => 74,
        75 => 75,
        76 => 76,
        78 => 78,
        80 => 80,
        82 => 82,
        83 => 83,
        84 => 83,
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
        95 => 95,
        97 => 97,
        98 => 98,
        100 => 100,
        104 => 104,
        105 => 105,
        106 => 106,
        107 => 107,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1528 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1531 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1534 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1539 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1542 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1545 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1548 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1551 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1557 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1563 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1566 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1569 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1572 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1575 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1578 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1581 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  '<?php ob_start();?>'.$this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,$this->yystack[$this->yyidx + -1]->minor).'<?php echo ';
                                                                if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					                           $this->_retvalue .= $this->yystack[$this->yyidx + -3]->minor . "(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor .");?>";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -3]->minor,'modifier')) {
                                                                      $this->_retvalue .= "\$_smarty_tpl->smarty->plugin_handler->".$this->yystack[$this->yyidx + -3]->minor . "(array(ob_get_clean()". $this->yystack[$this->yyidx + -2]->minor ."),'modifier');?>";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                                 }
                                                              }
                                                                }
#line 1596 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1599 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1602 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1605 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1608 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1611 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1614 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1617 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1620 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1623 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1626 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1629 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1632 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1635 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r35(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1638 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1641 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1644 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r40(){if ($this->yystack[$this->yyidx + -1]->minor == 'isset' || $this->yystack[$this->yyidx + -1]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -1]->minor)) {
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
#line 1658 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1661 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1664 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1667 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1670 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1673 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1676 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1679 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r53(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1683 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1686 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r58(){return;    }
#line 1689 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1692 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1695 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1698 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1701 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1704 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1707 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1710 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1713 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1716 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1719 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r70(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1728 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1731 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1734 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r74(){ return;    }
#line 1737 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1740 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1743 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1746 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1749 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1752 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1755 "internal.templateparser.php"
#line 335 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '==';    }
#line 1758 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '!=';    }
#line 1761 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '>';    }
#line 1764 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '<';    }
#line 1767 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '>=';    }
#line 1770 "internal.templateparser.php"
#line 340 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '<=';    }
#line 1773 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '===';    }
#line 1776 "internal.templateparser.php"
#line 342 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '!==';    }
#line 1779 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '&&';    }
#line 1782 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '||';    }
#line 1785 "internal.templateparser.php"
#line 347 "internal.templateparser.y"
    function yy_r95(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1788 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r97(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1791 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r98(){ return;     }
#line 1794 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r100(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1797 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1800 "internal.templateparser.php"
#line 358 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1803 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1806 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1809 "internal.templateparser.php"

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
#line 1926 "internal.templateparser.php"
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
#line 1951 "internal.templateparser.php"
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
