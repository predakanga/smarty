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
    const TP_COMMENTSTART                   = 47;
    const TP_COMMENTEND                     = 48;
    const TP_LITERALSTART                   = 49;
    const TP_LITERALEND                     = 50;
    const TP_LDELIMTAG                      = 51;
    const TP_RDELIMTAG                      = 52;
    const TP_PHP                            = 53;
    const TP_PHPSTART                       = 54;
    const TP_PHPEND                         = 55;
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
    const YY_SZ_ACTTAB = 588;
static public $yy_action = array(
 /*     0 */   155,   12,   28,  170,    4,  171,    6,   82,   46,   78,
 /*    10 */   165,  125,  201,    1,  113,  127,  155,   16,   28,  102,
 /*    20 */     4,  162,    6,   16,   49,  170,  115,  171,    3,  207,
 /*    30 */   206,   29,   42,  184,  185,   98,   18,   12,  118,   16,
 /*    40 */   191,  170,   18,  171,    3,   78,  191,   29,   42,  184,
 /*    50 */   185,  187,  177,   31,  118,  155,   24,   28,  204,   20,
 /*    60 */   108,    6,  191,   45,   27,  155,  108,   28,  197,   20,
 /*    70 */   131,    6,  173,   46,   30,  155,  125,   28,   89,   20,
 /*    80 */    14,    6,  108,   46,  102,   88,   29,   42,  184,  185,
 /*    90 */   124,  174,  175,  118,   99,  180,   29,   42,  184,  185,
 /*   100 */   169,  126,    9,  118,  178,  179,   29,   42,  184,  185,
 /*   110 */   207,  206,   38,  118,  129,  128,  212,   62,  101,  193,
 /*   120 */    92,  157,  205,  165,   65,  201,  153,  160,  198,  151,
 /*   130 */    15,  137,  135,  204,  215,  155,  125,   28,  138,   20,
 /*   140 */   195,   12,   46,   46,  210,   27,   48,   35,   44,   78,
 /*   150 */   182,  183,  181,   47,  100,  170,  155,  171,   28,  110,
 /*   160 */    20,  137,  135,  213,   46,  167,   29,   42,  184,  185,
 /*   170 */     7,   55,  125,  118,  125,  106,  137,  135,  150,  145,
 /*   180 */   139,  141,  142,  140,  144,  148,  189,   29,   42,  184,
 /*   190 */   185,  155,  136,  166,  118,   20,  170,    6,  171,   46,
 /*   200 */   111,  112,   96,  125,  213,  153,  160,  155,   76,   28,
 /*   210 */   107,   20,  204,  125,   22,   46,  137,  135,  196,  110,
 /*   220 */   187,   86,   29,   42,  184,  185,  105,  204,  149,  118,
 /*   230 */   157,  205,  159,   65,  161,  153,  160,  186,   29,   42,
 /*   240 */   184,  185,  204,   43,  125,  118,  340,   36,  130,  175,
 /*   250 */   150,  145,  139,  141,  142,  140,  144,  148,  155,  188,
 /*   260 */    38,   16,   20,  125,   91,   77,   46,    2,  143,  157,
 /*   270 */   205,   96,   65,   38,  153,  160,  146,  107,   74,  216,
 /*   280 */    21,  204,  157,  205,  191,   65,  138,  153,  160,   29,
 /*   290 */    42,  184,  185,   51,  204,   17,  118,  157,  110,  138,
 /*   300 */    56,   38,  153,  160,   12,   25,   63,   79,   23,  204,
 /*   310 */   157,  205,   78,   65,   71,  153,  160,  198,   19,   15,
 /*   320 */    11,  203,  204,  157,  205,  152,   65,  138,  153,  160,
 /*   330 */    70,   46,   97,   16,  208,  204,   37,    8,  121,   40,
 /*   340 */   196,   59,   95,  104,  214,  157,  205,  201,   65,   69,
 /*   350 */   153,  160,   18,  168,  199,  188,  191,  204,  157,  205,
 /*   360 */    55,   65,  138,  153,  160,   16,   68,  119,  157,   71,
 /*   370 */   204,   67,  110,  153,  160,  132,  196,   69,  157,  205,
 /*   380 */   204,   65,   25,  153,  160,   23,  157,  205,  191,   65,
 /*   390 */   204,  153,  160,   89,  110,  202,  156,  154,  204,  209,
 /*   400 */   157,  153,  160,   57,   69,  153,  160,   80,  204,   96,
 /*   410 */   194,  133,  204,  157,  205,   83,   65,  196,  153,  160,
 /*   420 */   201,   34,  120,  114,  170,  204,  171,   61,  122,   39,
 /*   430 */   157,  205,  164,   65,   50,  153,  160,  153,  160,  125,
 /*   440 */    69,  110,  204,  190,  204,  188,   66,  117,   12,  157,
 /*   450 */   205,   54,   65,   87,  153,  160,   78,  116,  123,   13,
 /*   460 */   176,  204,  157,  205,  204,   65,   41,  153,  160,   60,
 /*   470 */    33,   58,  192,  190,  204,  163,   72,   53,  103,  176,
 /*   480 */   153,  160,  188,  134,   90,  157,  205,  204,   65,  109,
 /*   490 */   153,  160,  211,  157,  205,   64,   65,  204,  153,  160,
 /*   500 */    13,   84,  176,   94,  176,  204,  147,  200,   26,  172,
 /*   510 */     5,  188,  157,  205,   93,   65,   81,  153,  160,  158,
 /*   520 */   110,   52,   32,  190,  204,  157,  205,   10,   65,   22,
 /*   530 */   153,  160,  159,  165,   46,  219,  219,  204,  219,   85,
 /*   540 */   219,  219,  219,  219,  219,  219,  219,   75,  157,  205,
 /*   550 */   219,   65,  219,  153,  160,  219,  157,  205,  219,   65,
 /*   560 */   204,  153,  160,  219,  219,  219,   73,  219,  204,  219,
 /*   570 */   219,  219,  219,  219,  219,  157,  205,  219,   65,  219,
 /*   580 */   153,  160,  219,  219,  219,  219,  219,  204,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,   11,    9,    1,   11,    3,   13,   81,   15,   19,
 /*    10 */    84,   21,   86,   23,   24,   15,    7,    3,    9,   26,
 /*    20 */    11,   86,   13,    3,   15,    1,   26,    3,   35,    8,
 /*    30 */     9,   38,   39,   40,   41,   26,   22,   11,   45,    3,
 /*    40 */    26,    1,   22,    3,   35,   19,   26,   38,   39,   40,
 /*    50 */    41,   76,   50,   78,   45,    7,   42,    9,   83,   11,
 /*    60 */    46,   13,   26,   15,   43,    7,   46,    9,   93,   11,
 /*    70 */     4,   13,   48,   15,   26,    7,   21,    9,   24,   11,
 /*    80 */    25,   13,   46,   15,   26,   66,   38,   39,   40,   41,
 /*    90 */    24,   59,   60,   45,   26,   55,   38,   39,   40,   41,
 /*   100 */     1,    2,    3,   45,    5,    6,   38,   39,   40,   41,
 /*   110 */     8,    9,   62,   45,   64,   20,   14,   67,   68,    4,
 /*   120 */    81,   71,   72,   84,   74,   86,   76,   77,    1,    4,
 /*   130 */     3,   36,   37,   83,   12,    7,   21,    9,   88,   11,
 /*   140 */     4,   11,   15,   15,   14,   43,   47,   66,   49,   19,
 /*   150 */    51,   52,   53,   54,   26,    1,    7,    3,    9,   23,
 /*   160 */    11,   36,   37,   12,   15,   38,   38,   39,   40,   41,
 /*   170 */    17,   44,   21,   45,   21,   26,   36,   37,   27,   28,
 /*   180 */    29,   30,   31,   32,   33,   34,    4,   38,   39,   40,
 /*   190 */    41,    7,   12,   39,   45,   11,    1,   13,    3,   15,
 /*   200 */    71,   72,   65,   21,   12,   76,   77,    7,   63,    9,
 /*   210 */    26,   11,   83,   21,   18,   15,   36,   37,   73,   23,
 /*   220 */    76,   62,   38,   39,   40,   41,   26,   83,    4,   45,
 /*   230 */    71,   72,   87,   74,   39,   76,   77,   93,   38,   39,
 /*   240 */    40,   41,   83,   69,   21,   45,   57,   58,   59,   60,
 /*   250 */    27,   28,   29,   30,   31,   32,   33,   34,    7,   85,
 /*   260 */    62,    3,   11,   21,   17,   67,   15,   20,   10,   71,
 /*   270 */    72,   65,   74,   62,   76,   77,   64,   26,   67,    4,
 /*   280 */    22,   83,   71,   72,   26,   74,   88,   76,   77,   38,
 /*   290 */    39,   40,   41,   15,   83,   89,   45,   71,   23,   88,
 /*   300 */    74,   62,   76,   77,   11,   13,   67,   82,   16,   83,
 /*   310 */    71,   72,   19,   74,   62,   76,   77,    1,   25,    3,
 /*   320 */    11,   26,   83,   71,   72,   12,   74,   88,   76,   77,
 /*   330 */    63,   15,   65,    3,   14,   83,   62,   17,   26,   69,
 /*   340 */    73,   67,   81,   91,   92,   71,   72,   86,   74,   62,
 /*   350 */    76,   77,   22,    4,   38,   85,   26,   83,   71,   72,
 /*   360 */    44,   74,   88,   76,   77,    3,   63,   80,   71,   62,
 /*   370 */    83,   74,   23,   76,   77,    4,   73,   62,   71,   72,
 /*   380 */    83,   74,   13,   76,   77,   16,   71,   72,   26,   74,
 /*   390 */    83,   76,   77,   24,   23,   80,   71,   45,   83,   92,
 /*   400 */    71,   76,   77,   74,   62,   76,   77,   63,   83,   65,
 /*   410 */     4,    4,   83,   71,   72,   81,   74,   73,   76,   77,
 /*   420 */    86,   62,   80,   64,    1,   83,    3,   61,   70,   69,
 /*   430 */    71,   72,   71,   74,   15,   76,   77,   76,   77,   21,
 /*   440 */    62,   23,   83,   85,   83,   85,   61,   26,   11,   71,
 /*   450 */    72,   26,   74,   62,   76,   77,   19,   76,   80,   22,
 /*   460 */    94,   83,   71,   72,   83,   74,   69,   76,   77,   61,
 /*   470 */    82,   61,   44,   85,   83,   71,   62,   12,   26,   94,
 /*   480 */    76,   77,   85,    4,   62,   71,   72,   83,   74,   26,
 /*   490 */    76,   77,   79,   71,   72,   69,   74,   83,   76,   77,
 /*   500 */    22,   26,   94,   62,   94,   83,    4,   26,   75,   94,
 /*   510 */    90,   85,   71,   72,   26,   74,   62,   76,   77,   73,
 /*   520 */    23,   79,   82,   85,   83,   71,   72,   11,   74,   18,
 /*   530 */    76,   77,   87,   84,   15,   95,   95,   83,   95,   62,
 /*   540 */    95,   95,   95,   95,   95,   95,   95,   62,   71,   72,
 /*   550 */    95,   74,   95,   76,   77,   95,   71,   72,   95,   74,
 /*   560 */    83,   76,   77,   95,   95,   95,   62,   95,   83,   95,
 /*   570 */    95,   95,   95,   95,   95,   71,   72,   95,   74,   95,
 /*   580 */    76,   77,   95,   95,   95,   95,   95,   83,
);
    const YY_SHIFT_USE_DFLT = -11;
    const YY_SHIFT_MAX = 128;
    static public $yy_shift_ofst = array(
 /*     0 */    99,    9,   -7,   -7,   -7,   -7,   68,   58,   68,   48,
 /*    10 */    58,   58,   58,   58,   58,   58,   58,   58,   58,   58,
 /*    20 */    58,   58,  149,  200,  184,  128,  251,  251,  251,  316,
 /*    30 */   -10,  127,  369,  369,  418,  196,   99,  151,  223,   14,
 /*    40 */    20,  258,  154,   36,  423,  362,  362,  423,  423,  362,
 /*    50 */   362,  362,   54,   54,  497,  519,  102,   21,  195,  180,
 /*    60 */     2,   40,  125,   95,  330,   21,   24,   21,  275,  153,
 /*    70 */   136,   55,  182,  192,  140,  115,  371,  140,    0,  292,
 /*    80 */   349,  242,   54,   54,  516,  242,  242,  242,  511,  488,
 /*    90 */   242,  278,   54,  309,  242,   54,  -11,  -11,  437,  293,
 /*   100 */   130,  247,   26,   66,  320,   26,   26,   26,  481,  352,
 /*   110 */   421,  407,  479,  425,  406,  309,  428,  478,  463,  465,
 /*   120 */   313,  502,  224,  122,  312,  295,  452,  475,  419,
);
    const YY_REDUCE_USE_DFLT = -75;
    const YY_REDUCE_MAX = 97;
    static public $yy_reduce_ofst = array(
 /*     0 */   189,   50,  239,  211,  274,  198,  252,  315,  307,  359,
 /*    10 */   287,  342,  378,  454,  422,  485,  414,  159,  391,  441,
 /*    20 */   504,  477,  329,  297,  129,  226,  361,  404,  325,  -25,
 /*    30 */   267,  144,   39,  -74,  344,  145,   32,  206,  206,  388,
 /*    40 */   388,  358,  410,  388,  408,  270,  174,  366,  385,  360,
 /*    50 */   397,  426,  334,  261,  303,  381,  433,  433,  415,  420,
 /*    60 */   415,  415,  420,  420,  438,  433,  415,  433,  446,  137,
 /*    70 */   446,  137,  137,  137,  420,  137,  446,  420,  442,  449,
 /*    80 */   446,  137,  -65,  -65,  440,  137,  137,  137,  445,  413,
 /*    90 */   137,  212,  -65,  225,  137,  -65,   19,   81,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 47, 49, 51, 52, 53, 54, ),
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
        /* 24 */ array(7, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 25 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 26 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 27 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 28 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 29 */ array(1, 3, 15, 38, 44, ),
        /* 30 */ array(11, 19, 21, 23, 24, ),
        /* 31 */ array(1, 3, 15, 38, 44, ),
        /* 32 */ array(13, 16, 24, ),
        /* 33 */ array(13, 16, 24, ),
        /* 34 */ array(21, 23, ),
        /* 35 */ array(18, 23, ),
        /* 36 */ array(1, 2, 3, 5, 6, 47, 49, 51, 52, 53, 54, ),
        /* 37 */ array(12, 21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 38 */ array(21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 39 */ array(3, 22, 26, 42, 46, ),
        /* 40 */ array(3, 22, 26, 46, ),
        /* 41 */ array(3, 10, 22, 26, ),
        /* 42 */ array(1, 3, 39, ),
        /* 43 */ array(3, 26, 46, ),
        /* 44 */ array(1, 3, ),
        /* 45 */ array(3, 26, ),
        /* 46 */ array(3, 26, ),
        /* 47 */ array(1, 3, ),
        /* 48 */ array(1, 3, ),
        /* 49 */ array(3, 26, ),
        /* 50 */ array(3, 26, ),
        /* 51 */ array(3, 26, ),
        /* 52 */ array(24, ),
        /* 53 */ array(24, ),
        /* 54 */ array(23, ),
        /* 55 */ array(15, ),
        /* 56 */ array(8, 9, 14, 43, ),
        /* 57 */ array(8, 9, 43, ),
        /* 58 */ array(1, 3, 39, ),
        /* 59 */ array(12, 36, 37, ),
        /* 60 */ array(1, 3, 50, ),
        /* 61 */ array(1, 3, 55, ),
        /* 62 */ array(4, 36, 37, ),
        /* 63 */ array(20, 36, 37, ),
        /* 64 */ array(3, 22, 26, ),
        /* 65 */ array(8, 9, 43, ),
        /* 66 */ array(1, 3, 48, ),
        /* 67 */ array(8, 9, 43, ),
        /* 68 */ array(4, 23, ),
        /* 69 */ array(17, 21, ),
        /* 70 */ array(4, 23, ),
        /* 71 */ array(21, 25, ),
        /* 72 */ array(4, 21, ),
        /* 73 */ array(12, 21, ),
        /* 74 */ array(36, 37, ),
        /* 75 */ array(4, 21, ),
        /* 76 */ array(4, 23, ),
        /* 77 */ array(36, 37, ),
        /* 78 */ array(15, 26, ),
        /* 79 */ array(13, 16, ),
        /* 80 */ array(4, 23, ),
        /* 81 */ array(21, ),
        /* 82 */ array(24, ),
        /* 83 */ array(24, ),
        /* 84 */ array(11, ),
        /* 85 */ array(21, ),
        /* 86 */ array(21, ),
        /* 87 */ array(21, ),
        /* 88 */ array(18, ),
        /* 89 */ array(26, ),
        /* 90 */ array(21, ),
        /* 91 */ array(15, ),
        /* 92 */ array(24, ),
        /* 93 */ array(11, ),
        /* 94 */ array(21, ),
        /* 95 */ array(24, ),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(11, 19, 22, ),
        /* 99 */ array(11, 19, 25, ),
        /* 100 */ array(11, 14, 19, ),
        /* 101 */ array(17, 20, ),
        /* 102 */ array(11, 19, ),
        /* 103 */ array(4, 24, ),
        /* 104 */ array(14, 17, ),
        /* 105 */ array(11, 19, ),
        /* 106 */ array(11, 19, ),
        /* 107 */ array(11, 19, ),
        /* 108 */ array(26, ),
        /* 109 */ array(45, ),
        /* 110 */ array(26, ),
        /* 111 */ array(4, ),
        /* 112 */ array(4, ),
        /* 113 */ array(26, ),
        /* 114 */ array(4, ),
        /* 115 */ array(11, ),
        /* 116 */ array(44, ),
        /* 117 */ array(22, ),
        /* 118 */ array(26, ),
        /* 119 */ array(12, ),
        /* 120 */ array(12, ),
        /* 121 */ array(4, ),
        /* 122 */ array(4, ),
        /* 123 */ array(12, ),
        /* 124 */ array(26, ),
        /* 125 */ array(26, ),
        /* 126 */ array(26, ),
        /* 127 */ array(26, ),
        /* 128 */ array(15, ),
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
 /*     0 */   339,  339,  339,  339,  339,  339,  325,  300,  339,  339,
 /*    10 */   300,  300,  300,  339,  339,  339,  339,  339,  339,  339,
 /*    20 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  339,
 /*    30 */   245,  339,  273,  278,  245,  245,  217,  309,  309,  282,
 /*    40 */   282,  339,  339,  282,  339,  339,  339,  339,  339,  339,
 /*    50 */   339,  339,  268,  269,  245,  339,  339,  305,  339,  339,
 /*    60 */   339,  339,  339,  339,  339,  251,  339,  284,  339,  299,
 /*    70 */   339,  326,  339,  339,  307,  339,  339,  311,  339,  294,
 /*    80 */   339,  246,  291,  270,  282,  241,  310,  249,  252,  339,
 /*    90 */   327,  339,  274,  282,  328,  271,  303,  303,  250,  250,
 /*   100 */   339,  339,  250,  339,  339,  283,  304,  339,  339,  339,
 /*   110 */   339,  339,  339,  339,  339,  272,  339,  339,  339,  339,
 /*   120 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  247,
 /*   130 */   218,  235,  234,  239,  240,  321,  308,  320,  306,  314,
 /*   140 */   317,  315,  316,  242,  318,  313,  248,  236,  319,  238,
 /*   150 */   312,  237,  297,  260,  261,  262,  255,  254,  243,  302,
 /*   160 */   263,  264,  293,  257,  256,  281,  265,  266,  230,  229,
 /*   170 */   337,  338,  335,  221,  219,  220,  336,  222,  227,  228,
 /*   180 */   226,  225,  223,  224,  275,  276,  329,  331,  287,  290,
 /*   190 */   288,  289,  332,  333,  231,  232,  244,  330,  334,  267,
 /*   200 */   279,  292,  298,  301,  280,  253,  258,  259,  322,  324,
 /*   210 */   285,  295,  286,  277,  323,  296,  233,
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
    0,  /* COMMENTSTART => nothing */
    0,  /* COMMENTEND => nothing */
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
  'BACKTICK',      'HATCH',         'AT',            'COMMENTSTART',
  'COMMENTEND',    'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHP',           'PHPSTART',      'PHPEND',      
  'error',         'start',         'template',      'template_element',
  'smartytag',     'text',          'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'value',       
  'array',         'attribute',     'exprs',         'math',        
  'variable',      'function',      'doublequoted',  'method',      
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
 /*  22 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN value RDEL",
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
 /*  44 */ "value ::= HATCH ID HATCH",
 /*  45 */ "value ::= NUMBER",
 /*  46 */ "value ::= function",
 /*  47 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  48 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  49 */ "value ::= QUOTE doublequoted QUOTE",
 /*  50 */ "value ::= QUOTE QUOTE",
 /*  51 */ "value ::= ID DOUBLECOLON method",
 /*  52 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP",
 /*  53 */ "value ::= ID DOUBLECOLON method objectchain",
 /*  54 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  55 */ "value ::= ID DOUBLECOLON ID",
 /*  56 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs",
 /*  57 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs objectchain",
 /*  58 */ "value ::= BOOLEAN",
 /*  59 */ "value ::= NULL",
 /*  60 */ "value ::= OPENP expr CLOSEP",
 /*  61 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  62 */ "variable ::= DOLLAR varvar AT ID",
 /*  63 */ "variable ::= object",
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
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 7 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 8 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
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
        45 => 0,
        46 => 0,
        58 => 0,
        59 => 0,
        63 => 0,
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
        44 => 44,
        47 => 47,
        49 => 47,
        48 => 48,
        50 => 48,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        60 => 60,
        61 => 61,
        62 => 62,
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
#line 1597 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1600 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1603 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1609 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1612 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1615 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1618 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1621 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1632 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1643 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1646 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1649 "internal.templateparser.php"
#line 131 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1652 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1655 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1658 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1661 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
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
#line 1676 "internal.templateparser.php"
#line 153 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1679 "internal.templateparser.php"
#line 155 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1682 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1685 "internal.templateparser.php"
#line 159 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1688 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1691 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1694 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1697 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1700 "internal.templateparser.php"
#line 175 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1703 "internal.templateparser.php"
#line 179 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1706 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1709 "internal.templateparser.php"
#line 185 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1712 "internal.templateparser.php"
#line 187 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1715 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1718 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
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
#line 1732 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1735 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1738 "internal.templateparser.php"
#line 218 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1741 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1744 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1747 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "''";     }
#line 1750 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1753 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1756 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1759 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r54(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1762 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1765 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1768 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1771 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1774 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1778 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1781 "internal.templateparser.php"
#line 299 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1784 "internal.templateparser.php"
#line 301 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1787 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1790 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1793 "internal.templateparser.php"
#line 307 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1796 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1799 "internal.templateparser.php"
#line 317 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1802 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1805 "internal.templateparser.php"
#line 324 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1808 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1811 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1814 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1817 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1820 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1829 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1832 "internal.templateparser.php"
#line 353 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1835 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1838 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1841 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1844 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1847 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1850 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1853 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1856 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1859 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '==';    }
#line 1862 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!=';    }
#line 1865 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>';    }
#line 1868 "internal.templateparser.php"
#line 392 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<';    }
#line 1871 "internal.templateparser.php"
#line 393 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '>=';    }
#line 1874 "internal.templateparser.php"
#line 394 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '<=';    }
#line 1877 "internal.templateparser.php"
#line 395 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '===';    }
#line 1880 "internal.templateparser.php"
#line 396 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '!==';    }
#line 1883 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '&&';    }
#line 1886 "internal.templateparser.php"
#line 399 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = '||';    }
#line 1889 "internal.templateparser.php"
#line 401 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1892 "internal.templateparser.php"
#line 403 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1895 "internal.templateparser.php"
#line 404 "internal.templateparser.y"
    function yy_r108(){ return;     }
#line 1898 "internal.templateparser.php"
#line 406 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1901 "internal.templateparser.php"
#line 408 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1904 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1907 "internal.templateparser.php"
#line 413 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1910 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1913 "internal.templateparser.php"
#line 415 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1916 "internal.templateparser.php"

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
#line 2033 "internal.templateparser.php"
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
#line 2058 "internal.templateparser.php"
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
