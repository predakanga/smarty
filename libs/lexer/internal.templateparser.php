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
    const TP_HATCH                          = 42;
    const TP_AT                             = 43;
    const TP_LITERALSTART                   = 44;
    const TP_LITERALEND                     = 45;
    const TP_LDELIMTAG                      = 46;
    const TP_RDELIMTAG                      = 47;
    const TP_PHP                            = 48;
    const TP_PHPSTART                       = 49;
    const TP_PHPEND                         = 50;
    const TP_XML                            = 51;
    const TP_LDEL                           = 52;
    const YY_NO_ACTION = 316;
    const YY_ACCEPT_ACTION = 315;
    const YY_ERROR_ACTION = 314;

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
    const YY_SZ_ACTTAB = 531;
static public $yy_action = array(
 /*     0 */   165,  141,   23,   73,    3,    5,    6,  150,   40,  122,
 /*    10 */   121,  115,  113,  118,  142,  136,  135,  160,   83,   21,
 /*    20 */    61,  161,   22,  166,  137,  138,   96,    4,  151,   76,
 /*    30 */    26,   46,  144,   53,   10,  128,   94,  151,   58,   85,
 /*    40 */    11,  110,  160,  162,   74,   37,  161,  168,  166,  195,
 /*    50 */   165,   14,   23,  151,    3,  197,    6,   75,   43,  134,
 /*    60 */   168,  165,  195,   23,  105,   12,  197,    6,   82,   40,
 /*    70 */   148,   98,  120,   41,   90,  107,  114,    4,  196,   83,
 /*    80 */    26,   46,  144,   13,  160,  162,   94,   37,  161,   49,
 /*    90 */   166,   26,   46,  144,   13,  151,  133,   94,  127,  100,
 /*   100 */   315,   33,  129,  140,  165,   14,   23,   15,   12,  197,
 /*   110 */     6,  197,   47,   39,  176,  189,  192,  154,   42,   31,
 /*   120 */   149,    8,   27,  201,   32,  173,  172,   40,   90,  173,
 /*   130 */   172,  193,   70,   53,   26,   46,  144,   13,   63,   13,
 /*   140 */    94,   93,  160,  162,   72,   37,  161,  120,  166,  169,
 /*   150 */   197,   53,   21,  151,   51,   22,   64,  103,   24,  134,
 /*   160 */   160,  162,   24,   37,  161,   17,  166,   30,   18,   90,
 /*   170 */    52,  151,  102,  137,  138,   62,  186,  134,   13,  160,
 /*   180 */   162,  229,   37,  161,   71,  166,  119,  150,   10,  180,
 /*   190 */   151,  200,  137,  138,  181,  110,  134,   93,  201,    1,
 /*   200 */    91,  122,  121,  115,  113,  118,  142,  136,  135,   66,
 /*   210 */   156,   53,  188,    9,  167,   65,   67,  137,  138,  181,
 /*   220 */   160,  162,  120,   37,  161,  181,  166,  143,  126,  140,
 /*   230 */   108,  151,  102,  163,   28,  182,  120,  134,  151,  160,
 /*   240 */   162,  176,   37,  161,  165,  166,   23,  165,   12,  174,
 /*   250 */   151,   12,   40,  102,   40,   40,  123,  160,  171,   86,
 /*   260 */   153,  159,   83,  166,   29,   83,   14,  196,  151,  198,
 /*   270 */   197,  112,   87,  201,   26,   46,  144,   26,   46,  144,
 /*   280 */    94,   51,  151,   94,   50,  145,   89,  201,  173,  172,
 /*   290 */   131,  111,   17,  160,  162,  120,   37,  161,   13,  166,
 /*   300 */   183,  109,  107,   10,  151,  143,  184,   55,  130,  106,
 /*   310 */   110,  160,  162,   19,   37,  161,  151,  166,  102,  107,
 /*   320 */    84,   24,  151,   68,  102,   80,  190,  178,  160,  162,
 /*   330 */    79,   37,  161,  181,  166,  108,    7,   36,   34,  151,
 /*   340 */   185,   25,   56,  101,  160,  162,  201,   37,  161,  104,
 /*   350 */   166,   20,  187,  187,  125,  151,   60,   38,   35,    6,
 /*   360 */    57,   40,  124,  160,  162,  155,   37,  161,   69,  166,
 /*   370 */    59,   95,  187,  187,  151,  185,   44,   77,   99,  146,
 /*   380 */   160,  162,  195,   37,  161,  187,  166,  179,  139,  185,
 /*   390 */   177,  151,   81,  185,   19,  191,  199,  160,  162,   97,
 /*   400 */    37,  161,   76,  166,  164,    2,  116,  196,  151,  170,
 /*   410 */    48,  102,  157,  160,  162,   40,   37,  161,   16,  166,
 /*   420 */   168,   25,   45,  175,  151,  194,   78,  163,  132,   18,
 /*   430 */   206,  206,  160,  162,  206,   37,  161,  206,  166,  206,
 /*   440 */   152,  206,  206,  151,  206,  206,  206,  206,  206,  160,
 /*   450 */   162,  206,   37,  161,  206,  166,  206,   88,  206,  206,
 /*   460 */   151,  206,  206,  206,  206,  206,  160,  162,  206,   37,
 /*   470 */   161,  206,  166,  117,  206,  206,  206,  151,  206,  206,
 /*   480 */   206,  206,  160,  162,  206,   37,  161,  206,  166,  206,
 /*   490 */   206,  206,   92,  151,  206,  206,  206,  206,  206,  206,
 /*   500 */   206,  160,  162,  206,   37,  161,  206,  166,  206,  160,
 /*   510 */   206,  206,  151,  158,  206,  166,  206,  206,  160,  160,
 /*   520 */   151,   54,  161,  147,  166,  166,  206,  206,  206,  151,
 /*   530 */   151,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   11,    8,   16,   10,   18,   12,   11,   14,   25,
 /*    10 */    26,   27,   28,   29,   30,   31,   32,   68,   24,   12,
 /*    20 */    71,   72,   15,   74,   34,   35,   68,   33,   79,   22,
 /*    30 */    36,   37,   38,   59,   10,   61,   42,   79,   64,   65,
 /*    40 */    10,   17,   68,   69,   77,   71,   72,   80,   74,   82,
 /*    50 */     6,   20,    8,   79,   10,   24,   12,   77,   14,   85,
 /*    60 */    80,    6,   82,    8,   67,   10,   24,   12,   24,   14,
 /*    70 */     1,    2,    1,    4,   43,   59,    5,   33,   81,   24,
 /*    80 */    36,   37,   38,   52,   68,   69,   42,   71,   72,   24,
 /*    90 */    74,   36,   37,   38,   52,   79,    9,   42,    3,   83,
 /*   100 */    54,   55,   56,   57,    6,   20,    8,   20,   10,   24,
 /*   110 */    12,   24,   14,   44,    1,   46,   47,   48,   49,   63,
 /*   120 */    51,   52,   24,   52,   39,    7,    8,   14,   43,    7,
 /*   130 */     8,   13,   78,   59,   36,   37,   38,   52,   64,   52,
 /*   140 */    42,   19,   68,   69,   63,   71,   72,    1,   74,   36,
 /*   150 */    24,   59,   12,   79,   41,   15,   64,   18,   40,   85,
 /*   160 */    68,   69,   40,   71,   72,   52,   74,   78,   17,   43,
 /*   170 */    59,   79,   21,   34,   35,   64,    3,   85,   52,   68,
 /*   180 */    69,    3,   71,   72,   60,   74,    3,   11,   10,   24,
 /*   190 */    79,   45,   34,   35,   70,   17,   85,   19,   52,   21,
 /*   200 */    22,   25,   26,   27,   28,   29,   30,   31,   32,   60,
 /*   210 */    13,   59,    3,   16,    3,   60,   64,   34,   35,   70,
 /*   220 */    68,   69,    1,   71,   72,   70,   74,   68,   56,   57,
 /*   230 */    59,   79,   21,   84,   75,    3,    1,   85,   79,   68,
 /*   240 */    69,    1,   71,   72,    6,   74,    8,    6,   10,   90,
 /*   250 */    79,   10,   14,   21,   14,   14,    3,   68,   37,   88,
 /*   260 */    89,   72,   24,   74,   78,   24,   20,   81,   79,   24,
 /*   270 */    24,   68,   69,   52,   36,   37,   38,   36,   37,   38,
 /*   280 */    42,   41,   79,   42,   59,   50,   61,   52,    7,    8,
 /*   290 */     3,   14,   52,   68,   69,    1,   71,   72,   52,   74,
 /*   300 */     3,   24,   59,   10,   79,   68,    3,   58,    3,   22,
 /*   310 */    17,   68,   69,   20,   71,   72,   79,   74,   21,   59,
 /*   320 */    24,   40,   79,   60,   21,   62,   83,   90,   68,   69,
 /*   330 */    62,   71,   72,   70,   74,   59,   16,   66,   66,   79,
 /*   340 */    91,   73,   58,   83,   68,   69,   52,   71,   72,   24,
 /*   350 */    74,   23,   81,   81,   59,   79,   58,   66,   66,   12,
 /*   360 */    58,   14,    3,   68,   69,   89,   71,   72,   17,   74,
 /*   370 */    66,   59,   81,   81,   79,   91,   14,   77,   24,   42,
 /*   380 */    68,   69,   82,   71,   72,   81,   74,   41,   59,   91,
 /*   390 */     3,   79,   24,   91,   20,   11,   11,   68,   69,   24,
 /*   400 */    71,   72,   22,   74,   59,   87,   91,   81,   79,   70,
 /*   410 */    76,   21,   82,   68,   69,   14,   71,   72,   86,   74,
 /*   420 */    80,   73,   14,   59,   79,   76,   24,   84,   61,   17,
 /*   430 */    92,   92,   68,   69,   92,   71,   72,   92,   74,   92,
 /*   440 */    59,   92,   92,   79,   92,   92,   92,   92,   92,   68,
 /*   450 */    69,   92,   71,   72,   92,   74,   92,   59,   92,   92,
 /*   460 */    79,   92,   92,   92,   92,   92,   68,   69,   92,   71,
 /*   470 */    72,   92,   74,   59,   92,   92,   92,   79,   92,   92,
 /*   480 */    92,   92,   68,   69,   92,   71,   72,   92,   74,   92,
 /*   490 */    92,   92,   59,   79,   92,   92,   92,   92,   92,   92,
 /*   500 */    92,   68,   69,   92,   71,   72,   92,   74,   92,   68,
 /*   510 */    92,   92,   79,   72,   92,   74,   92,   92,   68,   68,
 /*   520 */    79,   71,   72,   72,   74,   74,   92,   92,   92,   79,
 /*   530 */    79,
);
    const YY_SHIFT_USE_DFLT = -17;
    const YY_SHIFT_MAX = 112;
    static public $yy_shift_ofst = array(
 /*     0 */    69,   44,   -6,   -6,   -6,   -6,   55,   55,   98,   55,
 /*    10 */    55,   55,   55,   55,   55,   55,   55,   55,   55,   55,
 /*    20 */    55,  238,  238,  241,  241,  241,  240,  178,  113,    7,
 /*    30 */     7,  151,  347,   69,   85,   31,   87,  122,  126,  294,
 /*    40 */    42,  294,  294,   42,   42,   42,  294,   42,  380,  390,
 /*    50 */   390,  401,  176,  -16,  118,   71,  235,  221,  183,  246,
 /*    60 */   146,  281,  -10,  139,  158,  232,  297,  158,  303,  277,
 /*    70 */   140,  211,  412,  408,  380,  380,  402,  380,   30,  -17,
 /*    80 */   -17,  -17,  293,   24,  287,  -13,  197,   95,  209,  173,
 /*    90 */   245,   65,   -4,  165,  375,  387,  346,  337,  296,  374,
 /*   100 */   385,  384,  354,  362,  305,  253,  325,  320,  328,   30,
 /*   110 */   351,  368,  359,
);
    const YY_REDUCE_USE_DFLT = -52;
    const YY_REDUCE_MAX = 81;
    static public $yy_reduce_ofst = array(
 /*     0 */    46,  -26,  152,  111,   92,   74,  171,  243,  225,  276,
 /*    10 */    16,  260,  433,  398,  295,  329,  414,  312,  364,  345,
 /*    20 */   381,  450,  -51,  189,  451,  441,  159,  263,  237,  -20,
 /*    30 */   -33,  149,  203,  172,  186,  186,   -3,  268,  186,  298,
 /*    40 */   291,  249,  284,  272,  271,  304,  302,  292,  300,  155,
 /*    50 */   124,  -42,  332,  332,  348,  315,  315,  315,  318,  326,
 /*    60 */   315,  348,  318,  318,  318,  339,  339,  318,  339,  334,
 /*    70 */   340,  339,  343,  367,  330,  330,  349,  330,   54,   81,
 /*    80 */    56,   89,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 42, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 42, ),
        /* 21 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 22 */ array(6, 8, 10, 14, 24, 36, 37, 38, 42, ),
        /* 23 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 24 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 42, ),
        /* 26 */ array(1, 14, 41, 52, ),
        /* 27 */ array(3, 10, 17, 19, 21, 22, ),
        /* 28 */ array(1, 14, 36, 41, 52, ),
        /* 29 */ array(12, 15, 22, ),
        /* 30 */ array(12, 15, 22, ),
        /* 31 */ array(17, 21, ),
        /* 32 */ array(12, 14, ),
        /* 33 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 34 */ array(20, 24, 39, 43, 52, ),
        /* 35 */ array(20, 24, 43, 52, ),
        /* 36 */ array(9, 20, 24, 52, ),
        /* 37 */ array(7, 8, 19, 40, ),
        /* 38 */ array(24, 43, 52, ),
        /* 39 */ array(1, 52, ),
        /* 40 */ array(24, 52, ),
        /* 41 */ array(1, 52, ),
        /* 42 */ array(1, 52, ),
        /* 43 */ array(24, 52, ),
        /* 44 */ array(24, 52, ),
        /* 45 */ array(24, 52, ),
        /* 46 */ array(1, 52, ),
        /* 47 */ array(24, 52, ),
        /* 48 */ array(22, ),
        /* 49 */ array(21, ),
        /* 50 */ array(21, ),
        /* 51 */ array(14, ),
        /* 52 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 53 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 54 */ array(7, 8, 13, 40, ),
        /* 55 */ array(1, 5, 52, ),
        /* 56 */ array(1, 50, 52, ),
        /* 57 */ array(1, 37, 52, ),
        /* 58 */ array(3, 34, 35, ),
        /* 59 */ array(20, 24, 52, ),
        /* 60 */ array(1, 45, 52, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(11, 34, 35, ),
        /* 63 */ array(18, 34, 35, ),
        /* 64 */ array(34, 35, ),
        /* 65 */ array(3, 21, ),
        /* 66 */ array(3, 21, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(3, 21, ),
        /* 69 */ array(14, 24, ),
        /* 70 */ array(12, 15, ),
        /* 71 */ array(3, 21, ),
        /* 72 */ array(17, ),
        /* 73 */ array(14, ),
        /* 74 */ array(22, ),
        /* 75 */ array(22, ),
        /* 76 */ array(24, ),
        /* 77 */ array(22, ),
        /* 78 */ array(10, ),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(10, 17, 20, ),
        /* 83 */ array(10, 17, ),
        /* 84 */ array(3, 22, ),
        /* 85 */ array(16, 18, ),
        /* 86 */ array(13, 16, ),
        /* 87 */ array(3, ),
        /* 88 */ array(3, ),
        /* 89 */ array(3, ),
        /* 90 */ array(24, ),
        /* 91 */ array(24, ),
        /* 92 */ array(11, ),
        /* 93 */ array(24, ),
        /* 94 */ array(24, ),
        /* 95 */ array(3, ),
        /* 96 */ array(41, ),
        /* 97 */ array(42, ),
        /* 98 */ array(24, ),
        /* 99 */ array(20, ),
        /* 100 */ array(11, ),
        /* 101 */ array(11, ),
        /* 102 */ array(24, ),
        /* 103 */ array(14, ),
        /* 104 */ array(3, ),
        /* 105 */ array(3, ),
        /* 106 */ array(24, ),
        /* 107 */ array(16, ),
        /* 108 */ array(23, ),
        /* 109 */ array(10, ),
        /* 110 */ array(17, ),
        /* 111 */ array(24, ),
        /* 112 */ array(3, ),
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
        /* 198 */ array(),
        /* 199 */ array(),
        /* 200 */ array(),
        /* 201 */ array(),
);
    static public $yy_default = array(
 /*     0 */   314,  314,  314,  314,  314,  314,  301,  277,  314,  314,
 /*    10 */   277,  277,  314,  314,  314,  314,  314,  314,  314,  314,
 /*    20 */   314,  314,  314,  314,  314,  314,  314,  253,  314,  257,
 /*    30 */   251,  229,  314,  202,  261,  261,  314,  234,  261,  314,
 /*    40 */   314,  314,  314,  314,  314,  314,  314,  314,  248,  229,
 /*    50 */   229,  314,  285,  285,  314,  314,  314,  314,  314,  314,
 /*    60 */   314,  262,  314,  314,  283,  314,  314,  287,  314,  314,
 /*    70 */   271,  314,  235,  314,  252,  268,  314,  249,  261,  280,
 /*    80 */   280,  261,  253,  253,  314,  314,  314,  314,  314,  314,
 /*    90 */   314,  314,  314,  314,  314,  314,  314,  314,  314,  314,
 /*   100 */   314,  314,  314,  314,  314,  314,  314,  276,  302,  250,
 /*   110 */   314,  314,  314,  291,  206,  290,  310,  286,  292,  221,
 /*   120 */   312,  289,  288,  222,  223,  233,  204,  224,  231,  203,
 /*   130 */   220,  219,  232,  226,  282,  295,  294,  296,  297,  225,
 /*   140 */   205,  284,  293,  306,  255,  211,  254,  240,  213,  212,
 /*   150 */   256,  259,  303,  299,  210,  300,  298,  270,  239,  238,
 /*   160 */   243,  237,  236,  279,  230,  244,  245,  214,  260,  247,
 /*   170 */   227,  246,  241,  242,  305,  281,  309,  308,  304,  307,
 /*   180 */   278,  228,  217,  218,  216,  311,  215,  264,  267,  208,
 /*   190 */   275,  274,  209,  263,  272,  269,  265,  266,  258,  273,
 /*   200 */   207,  313,
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
    const YYNOCODE = 93;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 202;
    const YYNRULE = 112;
    const YYERRORSYMBOL = 53;
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
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'IN',          
  'ANDSYM',        'BACKTICK',      'HATCH',         'AT',          
  'LITERALSTART',  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',   
  'PHP',           'PHPSTART',      'PHPEND',        'XML',         
  'LDEL',          'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'expr',        
  'attributes',    'statement',     'modifier',      'modparameters',
  'ifexprs',       'statements',    'varvar',        'foraction',   
  'variable',      'array',         'attribute',     'exprs',       
  'value',         'math',          'function',      'doublequoted',
  'method',        'objectchain',   'vararraydefs',  'object',      
  'vararraydef',   'varvarele',     'objectelement',  'params',      
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'doublequotedcontent',  'textelement', 
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
 /*  50 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
 /*  51 */ "value ::= ID",
 /*  52 */ "value ::= HATCH ID HATCH",
 /*  53 */ "value ::= BOOLEAN",
 /*  54 */ "value ::= OPENP expr CLOSEP",
 /*  55 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  56 */ "variable ::= DOLLAR varvar AT ID",
 /*  57 */ "variable ::= object",
 /*  58 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  59 */ "vararraydefs ::=",
 /*  60 */ "vararraydef ::= DOT exprs",
 /*  61 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  62 */ "varvar ::= varvarele",
 /*  63 */ "varvar ::= varvar varvarele",
 /*  64 */ "varvarele ::= ID",
 /*  65 */ "varvarele ::= LDEL expr RDEL",
 /*  66 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  67 */ "objectchain ::= objectelement",
 /*  68 */ "objectchain ::= objectchain objectelement",
 /*  69 */ "objectelement ::= PTR ID vararraydefs",
 /*  70 */ "objectelement ::= PTR method",
 /*  71 */ "function ::= ID OPENP params CLOSEP",
 /*  72 */ "method ::= ID OPENP params CLOSEP",
 /*  73 */ "params ::= expr COMMA params",
 /*  74 */ "params ::= expr",
 /*  75 */ "params ::=",
 /*  76 */ "modifier ::= VERT ID",
 /*  77 */ "modparameters ::= modparameters modparameter",
 /*  78 */ "modparameters ::=",
 /*  79 */ "modparameter ::= COLON expr",
 /*  80 */ "ifexprs ::= ifexpr",
 /*  81 */ "ifexprs ::= NOT ifexprs",
 /*  82 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  83 */ "ifexpr ::= expr",
 /*  84 */ "ifexpr ::= expr ifcond expr",
 /*  85 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  86 */ "ifcond ::= EQUALS",
 /*  87 */ "ifcond ::= NOTEQUALS",
 /*  88 */ "ifcond ::= GREATERTHAN",
 /*  89 */ "ifcond ::= LESSTHAN",
 /*  90 */ "ifcond ::= GREATEREQUAL",
 /*  91 */ "ifcond ::= LESSEQUAL",
 /*  92 */ "ifcond ::= IDENTITY",
 /*  93 */ "ifcond ::= NONEIDENTITY",
 /*  94 */ "lop ::= LAND",
 /*  95 */ "lop ::= LOR",
 /*  96 */ "array ::= OPENB arrayelements CLOSEB",
 /*  97 */ "arrayelements ::= arrayelement",
 /*  98 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  99 */ "arrayelements ::=",
 /* 100 */ "arrayelement ::= expr",
 /* 101 */ "arrayelement ::= expr APTR expr",
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
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 11 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 57, 'rhs' => 8 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 0 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 5 ),
  array( 'lhs' => 72, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 6 ),
  array( 'lhs' => 72, 'rhs' => 7 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 0 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 0 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 0 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
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
        53 => 0,
        57 => 0,
        97 => 0,
        1 => 1,
        32 => 1,
        34 => 1,
        39 => 1,
        40 => 1,
        62 => 1,
        80 => 1,
        103 => 1,
        109 => 1,
        110 => 1,
        111 => 1,
        2 => 2,
        58 => 2,
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
        74 => 24,
        100 => 24,
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
        51 => 51,
        52 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        59 => 59,
        78 => 59,
        60 => 60,
        61 => 61,
        63 => 63,
        64 => 64,
        65 => 65,
        82 => 65,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        75 => 75,
        76 => 76,
        77 => 77,
        79 => 79,
        81 => 81,
        83 => 83,
        84 => 84,
        85 => 84,
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
        96 => 96,
        98 => 98,
        99 => 99,
        101 => 101,
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
#line 1530 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1533 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1536 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1541 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1544 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1547 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1550 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1553 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1559 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1565 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1568 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1571 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1574 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1577 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1580 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1583 "internal.templateparser.php"
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
#line 1598 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1601 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1604 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1607 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1610 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1613 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1616 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1619 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1622 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1625 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1628 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1631 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1634 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1637 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r33(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
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
#line 1651 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1654 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1657 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1660 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1663 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1666 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1669 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1672 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1675 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1678 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1681 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r52(){ $this->compiler->trigger_template_error ("config variables not yet supported \"" . $this->yystack[$this->yyidx + -1]->minor . "\""); $this->_retvalue = '\''.$this->yystack[$this->yyidx + -1]->minor.'\'';     }
#line 1684 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1687 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r55(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1691 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1694 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r59(){return;    }
#line 1697 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1700 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1703 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1706 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1709 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1712 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1715 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1718 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1721 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1724 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1727 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r71(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1736 "internal.templateparser.php"
#line 311 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1739 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r73(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1742 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r75(){ return;    }
#line 1745 "internal.templateparser.php"
#line 324 "internal.templateparser.y"
    function yy_r76(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1748 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1751 "internal.templateparser.php"
#line 334 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1754 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1757 "internal.templateparser.php"
#line 346 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1760 "internal.templateparser.php"
#line 347 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1763 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '==';    }
#line 1766 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '!=';    }
#line 1769 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '>';    }
#line 1772 "internal.templateparser.php"
#line 353 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '<';    }
#line 1775 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '>=';    }
#line 1778 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '<=';    }
#line 1781 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '===';    }
#line 1784 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '!==';    }
#line 1787 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '&&';    }
#line 1790 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '||';    }
#line 1793 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r96(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1796 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r98(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1799 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r99(){ return;     }
#line 1802 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r101(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1805 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1808 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1811 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1814 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1817 "internal.templateparser.php"

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
#line 1934 "internal.templateparser.php"
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
#line 1959 "internal.templateparser.php"
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
