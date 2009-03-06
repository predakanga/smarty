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
    const TP_NULL                           = 42;
    const TP_IN                             = 43;
    const TP_ANDSYM                         = 44;
    const TP_BACKTICK                       = 45;
    const TP_HATCH                          = 46;
    const TP_AT                             = 47;
    const TP_LITERALSTART                   = 48;
    const TP_LITERALEND                     = 49;
    const TP_LDELIMTAG                      = 50;
    const TP_RDELIMTAG                      = 51;
    const TP_PHP                            = 52;
    const TP_PHPSTART                       = 53;
    const TP_PHPEND                         = 54;
    const TP_LDEL                           = 55;
    const YY_NO_ACTION = 341;
    const YY_ACCEPT_ACTION = 340;
    const YY_ERROR_ACTION = 339;

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
 /*     0 */   154,  117,   25,  213,    2,   13,    6,   72,   47,   97,
 /*    10 */   169,  116,  117,   50,   71,  178,  179,  196,   21,  106,
 /*    20 */   170,   12,  191,  153,  207,  117,   58,  157,    4,  155,
 /*    30 */    74,   28,   43,  163,  184,  205,  187,  154,  118,   25,
 /*    40 */   196,    2,  113,    6,  209,   45,  216,  154,  205,   25,
 /*    50 */    17,   14,  170,    6,  159,   47,   98,   46,  186,  182,
 /*    60 */   183,  181,   49,   35,   11,    4,  106,  125,   28,   43,
 /*    70 */   163,  184,   97,  180,  171,  118,  127,  193,   28,   43,
 /*    80 */   163,  184,  187,  141,  154,  118,   25,  111,   14,   29,
 /*    90 */     6,  161,   51,  154,  205,   25,  117,   14,   34,    6,
 /*   100 */   112,   47,  109,   30,  197,  115,  171,  153,  207,   94,
 /*   110 */    58,  157,   99,  155,  205,   28,   43,  163,  184,  205,
 /*   120 */   206,  185,  118,  136,   28,   43,  163,  184,   38,  208,
 /*   130 */   130,  118,    8,   65,  102,   15,   92,  153,  207,  191,
 /*   140 */    58,  157,  213,  155,  340,   36,  132,  175,  195,  205,
 /*   150 */    89,  117,  191,    5,  140,   26,  134,  146,  148,  150,
 /*   160 */   145,  144,  143,  142,  139,   39,  154,   17,   25,  125,
 /*   170 */    14,  117,  113,   87,   47,  154,  167,   25,  201,   14,
 /*   180 */    17,  188,  170,   47,  154,  101,   25,  198,   14,  153,
 /*   190 */   149,  147,   47,  162,  100,  155,  152,   28,   43,  163,
 /*   200 */   184,  205,   47,  105,  118,  215,   28,   43,  163,  184,
 /*   210 */    79,   59,   96,  118,   38,   28,   43,  163,  184,   60,
 /*   220 */   196,  160,  118,  153,  207,  166,   58,  157,    6,  155,
 /*   230 */    47,   44,  118,   38,   24,  205,  171,   22,   68,  211,
 /*   240 */   140,   18,  153,  207,  176,   58,  157,   24,  155,  153,
 /*   250 */    22,   47,   56,  157,  205,  155,  117,  159,   94,  140,
 /*   260 */   118,  205,  146,  148,  150,  145,  144,  143,  142,  139,
 /*   270 */    37,   42,  153,   21,  164,   64,  165,  191,  155,  153,
 /*   280 */   207,  118,   58,  157,  205,  155,  170,  188,  191,   38,
 /*   290 */   173,  205,    7,   31,   73,    7,  140,  113,  153,  207,
 /*   300 */    77,   58,  157,   77,  155,   17,  206,  185,   71,   19,
 /*   310 */   205,  138,  212,  126,  128,  140,   17,  153,  207,   33,
 /*   320 */    58,  157,  190,  155,  154,  205,  131,  153,   14,  205,
 /*   330 */    63,  157,   47,  155,    7,  149,  147,  103,  214,  205,
 /*   340 */   171,   26,   77,  107,  117,   82,    1,  110,  167,  170,
 /*   350 */   201,  149,  147,   88,   69,   28,   43,  163,  184,  189,
 /*   360 */   135,   69,  118,  153,  207,  168,   58,  157,  121,  155,
 /*   370 */   153,  207,  108,   58,  157,  205,  155,  170,  117,  202,
 /*   380 */    69,  125,  205,    7,  149,  147,  125,   69,   23,  153,
 /*   390 */   207,   77,   58,  157,   16,  155,  153,  207,  124,   58,
 /*   400 */   157,  205,  155,  171,   61,  123,   76,  198,  205,    7,
 /*   410 */    41,  192,  210,   70,   97,  153,  207,   77,   58,  157,
 /*   420 */    95,  155,   47,  196,   21,  177,  188,  205,  191,  153,
 /*   430 */   207,  171,   58,  157,   75,  155,  153,  176,   20,   67,
 /*   440 */   157,  205,  155,  153,  207,  199,   58,  157,  205,  155,
 /*   450 */    85,   44,  118,   57,  120,  205,   17,  174,  175,  153,
 /*   460 */   207,   18,   58,  157,   91,  155,   81,   23,  117,  190,
 /*   470 */   125,  205,  125,  153,  207,    9,   58,  157,   93,  155,
 /*   480 */    66,   54,  122,  119,   62,  205,  176,  153,  207,   90,
 /*   490 */    58,  157,   86,  155,  201,   40,  188,  204,   84,  205,
 /*   500 */   194,  153,  207,  201,   58,  157,   83,  155,  153,  104,
 /*   510 */   203,  188,  156,  205,  155,  153,  207,  176,   58,  157,
 /*   520 */   205,  155,   78,  137,   48,  200,  151,  205,  133,  114,
 /*   530 */   129,  153,  207,   16,   58,  157,  172,  155,   55,  158,
 /*   540 */     3,   27,  190,  205,   52,  125,   53,   10,  219,  219,
 /*   550 */    32,   80,  219,  219,  167,
    );
    static public $yy_lookahead = array(
 /*     0 */     8,   22,   10,   13,   12,   26,   14,   63,   16,   65,
 /*    10 */     1,    2,   22,    4,   62,    6,    7,   73,   23,   27,
 /*    20 */     1,   18,   27,   71,   72,   22,   74,   75,   36,   77,
 /*    30 */    63,   39,   40,   41,   42,   83,   71,    8,   46,   10,
 /*    40 */    73,   12,   47,   14,   92,   16,    3,    8,   83,   10,
 /*    50 */    55,   12,    1,   14,   87,   16,   27,   48,   93,   50,
 /*    60 */    51,   52,   53,   66,   55,   36,   27,   24,   39,   40,
 /*    70 */    41,   42,   65,   54,   55,   46,   16,    3,   39,   40,
 /*    80 */    41,   42,   71,    3,    8,   46,   10,   27,   12,   78,
 /*    90 */    14,   40,   16,    8,   83,   10,   22,   12,   62,   14,
 /*   100 */    64,   16,   71,   27,   93,   25,   55,   71,   72,   25,
 /*   110 */    74,   75,   27,   77,   83,   39,   40,   41,   42,   83,
 /*   120 */     9,   10,   46,   11,   39,   40,   41,   42,   62,   15,
 /*   130 */    64,   46,   18,   67,   68,   23,   66,   71,   72,   27,
 /*   140 */    74,   75,   13,   77,   57,   58,   59,   60,    3,   83,
 /*   150 */    18,   22,   27,   21,   88,   44,    3,   28,   29,   30,
 /*   160 */    31,   32,   33,   34,   35,   69,    8,   55,   10,   24,
 /*   170 */    12,   22,   47,   81,   16,    8,   84,   10,   86,   12,
 /*   180 */    55,   85,    1,   16,    8,   27,   10,    1,   12,   71,
 /*   190 */    37,   38,   16,   75,   27,   77,   13,   39,   40,   41,
 /*   200 */    42,   83,   16,   27,   46,   13,   39,   40,   41,   42,
 /*   210 */    63,   61,   65,   46,   62,   39,   40,   41,   42,   67,
 /*   220 */    73,   40,   46,   71,   72,   39,   74,   75,   14,   77,
 /*   230 */    16,   45,   46,   62,   14,   83,   55,   17,   67,   79,
 /*   240 */    88,   55,   71,   72,   94,   74,   75,   14,   77,   71,
 /*   250 */    17,   16,   74,   75,   83,   77,   22,   87,   25,   88,
 /*   260 */    46,   83,   28,   29,   30,   31,   32,   33,   34,   35,
 /*   270 */    62,   69,   71,   23,   86,   67,   75,   27,   77,   71,
 /*   280 */    72,   46,   74,   75,   83,   77,    1,   85,   27,   62,
 /*   290 */     5,   83,   12,   43,   67,   12,   88,   47,   71,   72,
 /*   300 */    20,   74,   75,   20,   77,   55,    9,   10,   62,   26,
 /*   310 */    83,   13,   15,   71,   72,   88,   55,   71,   72,   82,
 /*   320 */    74,   75,   85,   77,    8,   83,    3,   71,   12,   83,
 /*   330 */    74,   75,   16,   77,   12,   37,   38,   91,   92,   83,
 /*   340 */    55,   44,   20,   27,   22,   81,   24,   25,   84,    1,
 /*   350 */    86,   37,   38,   27,   62,   39,   40,   41,   42,    3,
 /*   360 */     3,   62,   46,   71,   72,    3,   74,   75,   21,   77,
 /*   370 */    71,   72,   80,   74,   75,   83,   77,    1,   22,   80,
 /*   380 */    62,   24,   83,   12,   37,   38,   24,   62,   19,   71,
 /*   390 */    72,   20,   74,   75,   23,   77,   71,   72,   80,   74,
 /*   400 */    75,   83,   77,   55,   61,   80,   62,    1,   83,   12,
 /*   410 */    69,   45,   15,   63,   65,   71,   72,   20,   74,   75,
 /*   420 */    62,   77,   16,   73,   23,   49,   85,   83,   27,   71,
 /*   430 */    72,   55,   74,   75,   62,   77,   71,   94,   89,   74,
 /*   440 */    75,   83,   77,   71,   72,   39,   74,   75,   83,   77,
 /*   450 */    62,   45,   46,   61,   70,   83,   55,   59,   60,   71,
 /*   460 */    72,   55,   74,   75,   62,   77,   27,   19,   22,   85,
 /*   470 */    24,   83,   24,   71,   72,   12,   74,   75,   62,   77,
 /*   480 */    69,   27,   27,   27,   61,   83,   94,   71,   72,   81,
 /*   490 */    74,   75,   62,   77,   86,   69,   85,   46,   81,   83,
 /*   500 */     3,   71,   72,   86,   74,   75,   62,   77,   71,   27,
 /*   510 */    27,   85,   75,   83,   77,   71,   72,   94,   74,   75,
 /*   520 */    83,   77,   62,    3,   16,   27,    3,   83,   64,   27,
 /*   530 */     3,   71,   72,   23,   74,   75,   94,   77,   13,   73,
 /*   540 */    90,   76,   85,   83,   16,   24,   79,   12,   95,   95,
 /*   550 */    82,   82,   95,   95,   84,
);
    const YY_SHIFT_USE_DFLT = -22;
    const YY_SHIFT_MAX = 128;
    static public $yy_shift_ofst = array(
 /*     0 */     9,   29,   -8,   -8,   -8,   -8,   85,   39,   85,   39,
 /*    10 */    39,   76,   39,   39,   39,   39,   39,   39,   39,   39,
 /*    20 */    39,   39,  158,  176,  167,  316,  316,  316,  406,  186,
 /*    30 */   322,  214,  233,  233,  446,  448,    9,  129,  234,  250,
 /*    40 */    -5,  112,  125,   51,  235,  261,  348,  261,  261,  348,
 /*    50 */   348,  261,  261,   84,  521,   84,  297,  181,  111,  376,
 /*    60 */   347,   19,  285,  111,  298,  153,  401,  111,  314,    3,
 /*    70 */    43,  -21,  362,  314,  357,  -10,  356,   60,   74,  145,
 /*    80 */   220,  535,   84,  149,   84,  149,  149,   84,  463,  528,
 /*    90 */    84,  149,  369,  149,  326,  149,  -22,  -22,  371,  283,
 /*   100 */   397,  280,  132,  114,   80,  280,  280,  280,  525,  366,
 /*   110 */   454,  463,  497,  498,  451,  456,  482,  483,  502,  523,
 /*   120 */   520,  508,  510,  192,  183,  455,  323,  439,  527,
);
    const YY_REDUCE_USE_DFLT = -57;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */    87,   66,  208,  171,  227,  152,  246,  325,  -48,  318,
 /*    10 */   292,   36,  299,  388,  372,  358,  416,  344,  460,  444,
 /*    20 */   430,  402,  256,  365,  178,  437,  118,  201,   11,  -35,
 /*    30 */   147,  242,   92,  264,  -56,  -33,  398,  349,  349,  237,
 /*    40 */   237,  384,  237,  392,   31,   96,  150,  202,  341,  343,
 /*    50 */   423,  426,  411,  408,  350,  417,  465,  442,  465,  442,
 /*    60 */   450,  442,  442,  465,  450,  450,  457,  465,  450,    7,
 /*    70 */   466,    7,  466,  450,  466,    7,    7,  467,    7,  466,
 /*    80 */   470,  468,  188,    7,  188,    7,    7,  188,  469,  464,
 /*    90 */   188,    7,  170,    7,  160,    7,   -3,   70,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 6, 7, 48, 50, 51, 52, 53, 55, ),
        /* 1 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 42, 46, ),
        /* 2 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 42, 46, ),
        /* 3 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 42, 46, ),
        /* 4 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 42, 46, ),
        /* 5 */ array(8, 10, 12, 14, 16, 27, 36, 39, 40, 41, 42, 46, ),
        /* 6 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 7 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 8 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 9 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 10 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 11 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 12 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 13 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 14 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 15 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 16 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 17 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 18 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 19 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 20 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 21 */ array(8, 10, 12, 14, 16, 27, 39, 40, 41, 42, 46, ),
        /* 22 */ array(8, 10, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 23 */ array(8, 10, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 24 */ array(8, 10, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 25 */ array(8, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 26 */ array(8, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 27 */ array(8, 12, 16, 27, 39, 40, 41, 42, 46, ),
        /* 28 */ array(1, 16, 39, 45, 46, 55, ),
        /* 29 */ array(1, 16, 39, 45, 46, 55, ),
        /* 30 */ array(12, 20, 22, 24, 25, ),
        /* 31 */ array(14, 16, 46, ),
        /* 32 */ array(14, 17, 25, ),
        /* 33 */ array(14, 17, 25, ),
        /* 34 */ array(22, 24, ),
        /* 35 */ array(19, 24, ),
        /* 36 */ array(1, 2, 4, 6, 7, 48, 50, 51, 52, 53, 55, ),
        /* 37 */ array(13, 22, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 38 */ array(22, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 39 */ array(23, 27, 43, 47, 55, ),
        /* 40 */ array(23, 27, 47, 55, ),
        /* 41 */ array(11, 23, 27, 55, ),
        /* 42 */ array(27, 47, 55, ),
        /* 43 */ array(1, 40, 55, ),
        /* 44 */ array(16, 46, ),
        /* 45 */ array(27, 55, ),
        /* 46 */ array(1, 55, ),
        /* 47 */ array(27, 55, ),
        /* 48 */ array(27, 55, ),
        /* 49 */ array(1, 55, ),
        /* 50 */ array(1, 55, ),
        /* 51 */ array(27, 55, ),
        /* 52 */ array(27, 55, ),
        /* 53 */ array(25, ),
        /* 54 */ array(24, ),
        /* 55 */ array(25, ),
        /* 56 */ array(9, 10, 15, 44, ),
        /* 57 */ array(1, 40, 55, ),
        /* 58 */ array(9, 10, 44, ),
        /* 59 */ array(1, 49, 55, ),
        /* 60 */ array(21, 37, 38, ),
        /* 61 */ array(1, 54, 55, ),
        /* 62 */ array(1, 5, 55, ),
        /* 63 */ array(9, 10, 44, ),
        /* 64 */ array(13, 37, 38, ),
        /* 65 */ array(3, 37, 38, ),
        /* 66 */ array(23, 27, 55, ),
        /* 67 */ array(9, 10, 44, ),
        /* 68 */ array(37, 38, ),
        /* 69 */ array(18, 22, ),
        /* 70 */ array(3, 24, ),
        /* 71 */ array(22, 26, ),
        /* 72 */ array(3, 24, ),
        /* 73 */ array(37, 38, ),
        /* 74 */ array(3, 24, ),
        /* 75 */ array(13, 22, ),
        /* 76 */ array(3, 22, ),
        /* 77 */ array(16, 27, ),
        /* 78 */ array(3, 22, ),
        /* 79 */ array(3, 24, ),
        /* 80 */ array(14, 17, ),
        /* 81 */ array(12, ),
        /* 82 */ array(25, ),
        /* 83 */ array(22, ),
        /* 84 */ array(25, ),
        /* 85 */ array(22, ),
        /* 86 */ array(22, ),
        /* 87 */ array(25, ),
        /* 88 */ array(12, ),
        /* 89 */ array(16, ),
        /* 90 */ array(25, ),
        /* 91 */ array(22, ),
        /* 92 */ array(19, ),
        /* 93 */ array(22, ),
        /* 94 */ array(27, ),
        /* 95 */ array(22, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(12, 20, 23, ),
        /* 99 */ array(12, 20, 26, ),
        /* 100 */ array(12, 15, 20, ),
        /* 101 */ array(12, 20, ),
        /* 102 */ array(18, 21, ),
        /* 103 */ array(15, 18, ),
        /* 104 */ array(3, 25, ),
        /* 105 */ array(12, 20, ),
        /* 106 */ array(12, 20, ),
        /* 107 */ array(12, 20, ),
        /* 108 */ array(13, ),
        /* 109 */ array(45, ),
        /* 110 */ array(27, ),
        /* 111 */ array(12, ),
        /* 112 */ array(3, ),
        /* 113 */ array(27, ),
        /* 114 */ array(46, ),
        /* 115 */ array(27, ),
        /* 116 */ array(27, ),
        /* 117 */ array(27, ),
        /* 118 */ array(27, ),
        /* 119 */ array(3, ),
        /* 120 */ array(3, ),
        /* 121 */ array(16, ),
        /* 122 */ array(23, ),
        /* 123 */ array(13, ),
        /* 124 */ array(13, ),
        /* 125 */ array(27, ),
        /* 126 */ array(3, ),
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
        /* 216 */ array(),
);
    static public $yy_default = array(
 /*     0 */   339,  339,  339,  339,  339,  339,  325,  300,  339,  300,
 /*    10 */   300,  339,  300,  339,  339,  339,  339,  339,  339,  339,
 /*    20 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  339,
 /*    30 */   245,  339,  272,  277,  245,  245,  217,  309,  309,  282,
 /*    40 */   282,  339,  282,  339,  339,  339,  339,  339,  339,  339,
 /*    50 */   339,  339,  339,  267,  245,  268,  339,  339,  251,  339,
 /*    60 */   339,  339,  339,  284,  339,  339,  339,  305,  311,  299,
 /*    70 */   339,  326,  339,  307,  339,  339,  339,  339,  339,  339,
 /*    80 */   294,  282,  291,  328,  270,  327,  310,  273,  282,  339,
 /*    90 */   269,  249,  252,  246,  339,  241,  303,  303,  250,  250,
 /*   100 */   339,  283,  339,  339,  339,  304,  250,  339,  339,  339,
 /*   110 */   339,  271,  339,  339,  339,  339,  339,  339,  339,  339,
 /*   120 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  240,
 /*   130 */   247,  239,  218,  248,  237,  234,  242,  238,  308,  319,
 /*   140 */   306,  235,  318,  317,  316,  315,  312,  321,  313,  320,
 /*   150 */   314,  236,  297,  260,  261,  262,  255,  254,  243,  302,
 /*   160 */   263,  264,  257,  274,  293,  256,  265,  281,  230,  229,
 /*   170 */   337,  338,  335,  221,  219,  220,  336,  222,  227,  228,
 /*   180 */   226,  225,  223,  224,  275,  258,  329,  331,  287,  290,
 /*   190 */   288,  289,  332,  333,  231,  232,  244,  330,  334,  266,
 /*   200 */   278,  292,  298,  301,  280,  279,  259,  253,  322,  324,
 /*   210 */   285,  295,  286,  276,  323,  296,  233,
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
    const YYNOCODE = 96;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 217;
    const YYNRULE = 122;
    const YYERRORSYMBOL = 56;
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
  'SINGLEQUOTE',   'BOOLEAN',       'NULL',          'IN',          
  'ANDSYM',        'BACKTICK',      'HATCH',         'AT',          
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'text',          'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'variable',    
  'array',         'attribute',     'exprs',         'value',       
  'math',          'function',      'doublequoted',  'method',      
  'params',        'objectchain',   'vararraydefs',  'object',      
  'vararraydef',   'varvarele',     'objectelement',  'modparameter',
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
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 6 ),
  array( 'lhs' => 60, 'rhs' => 6 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 5 ),
  array( 'lhs' => 60, 'rhs' => 5 ),
  array( 'lhs' => 60, 'rhs' => 11 ),
  array( 'lhs' => 60, 'rhs' => 8 ),
  array( 'lhs' => 60, 'rhs' => 8 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 0 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 4 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 7 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 8 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 5 ),
  array( 'lhs' => 75, 'rhs' => 6 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 0 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 4 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 4 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 0 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 0 ),
  array( 'lhs' => 87, 'rhs' => 2 ),
  array( 'lhs' => 87, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 0 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
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
#line 1591 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1594 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1597 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1603 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1606 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1609 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1612 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1615 "internal.templateparser.php"
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
#line 1626 "internal.templateparser.php"
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
#line 1637 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1640 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1643 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1646 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1649 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1652 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1655 "internal.templateparser.php"
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
#line 1670 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1673 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1676 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1679 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1682 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1685 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1688 "internal.templateparser.php"
#line 163 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1691 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1694 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1697 "internal.templateparser.php"
#line 177 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1700 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1703 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1706 "internal.templateparser.php"
#line 185 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1709 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1712 "internal.templateparser.php"
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
#line 1726 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1729 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1732 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1735 "internal.templateparser.php"
#line 249 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1738 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "''";     }
#line 1741 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1744 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r51(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1747 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1750 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1753 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1756 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1759 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1762 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1765 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r60(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1769 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1772 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1775 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1778 "internal.templateparser.php"
#line 297 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1781 "internal.templateparser.php"
#line 298 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1784 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1787 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1790 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1793 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1796 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1799 "internal.templateparser.php"
#line 316 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1802 "internal.templateparser.php"
#line 318 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1805 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1808 "internal.templateparser.php"
#line 322 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1811 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1814 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1823 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1826 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1829 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1832 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1835 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1838 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1841 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1844 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1847 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1850 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1853 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '==';    }
#line 1856 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!=';    }
#line 1859 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>';    }
#line 1862 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<';    }
#line 1865 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '>=';    }
#line 1868 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '<=';    }
#line 1871 "internal.templateparser.php"
#line 387 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '===';    }
#line 1874 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '!==';    }
#line 1877 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '&&';    }
#line 1880 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = '||';    }
#line 1883 "internal.templateparser.php"
#line 393 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1886 "internal.templateparser.php"
#line 395 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1889 "internal.templateparser.php"
#line 396 "internal.templateparser.y"
    function yy_r108(){ return;     }
#line 1892 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1895 "internal.templateparser.php"
#line 400 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1898 "internal.templateparser.php"
#line 404 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1901 "internal.templateparser.php"
#line 405 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1904 "internal.templateparser.php"
#line 406 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1907 "internal.templateparser.php"
#line 407 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1910 "internal.templateparser.php"

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
#line 2027 "internal.templateparser.php"
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
#line 2052 "internal.templateparser.php"
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
