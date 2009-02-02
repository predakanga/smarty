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
    const YY_NO_ACTION = 308;
    const YY_ACCEPT_ACTION = 307;
    const YY_ERROR_ACTION = 306;

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
    const YY_SZ_ACTTAB = 502;
static public $yy_action = array(
 /*     0 */   143,  139,  143,  139,  125,  162,  125,   71,   19,   83,
 /*    10 */    19,  160,  159,   80,  159,    4,  187,  165,  159,  186,
 /*    20 */   185,  179,  178,  177,  180,  181,  184,  143,  139,   32,
 /*    30 */   116,  192,  116,   23,  154,   23,  116,  143,  139,   12,
 /*    40 */   154,   12,   90,  142,  152,   12,  186,  185,  179,  178,
 /*    50 */   177,  180,  181,  184,  126,  104,   34,   46,  140,   84,
 /*    60 */    23,   65,   94,  122,   21,  162,  194,   17,  193,   87,
 /*    70 */    23,  160,  169,  173,   18,  173,  187,  148,  159,   28,
 /*    80 */   147,  129,  156,  187,  151,  154,   41,  189,   41,  163,
 /*    90 */   151,   24,  113,    3,  172,    6,   44,   41,  149,  123,
 /*   100 */   124,   43,  114,  119,    7,   12,  131,   96,  157,  166,
 /*   110 */   163,   91,   24,   49,    3,   49,    6,    2,   45,   57,
 /*   120 */    26,  120,  191,   13,   29,   13,   98,   25,   93,  157,
 /*   130 */   147,  129,  183,  162,  194,  151,  193,   87,    2,  160,
 /*   140 */    15,   26,  120,  223,  187,  163,  154,   24,   76,   22,
 /*   150 */    10,    6,  150,   41,  147,  129,  192,  115,   55,  110,
 /*   160 */    14,    1,   99,   96,  157,  143,  139,  162,  194,  133,
 /*   170 */   193,   87,   10,  160,  143,  139,   26,  120,  187,  115,
 /*   180 */    34,   20,   16,    9,  175,   74,  155,   97,  135,  162,
 /*   190 */   194,  110,  193,   87,  118,  160,  151,  162,   23,   40,
 /*   200 */   187,   88,  103,  160,   33,  100,  156,   23,  187,   67,
 /*   210 */    72,  159,   92,  162,  194,  137,  193,   87,   11,  160,
 /*   220 */   165,  163,   56,   24,  187,   22,   10,    6,   63,   47,
 /*   230 */   156,  162,  194,  115,  193,   87,   34,  160,   12,   27,
 /*   240 */   157,   73,  187,  143,  139,  162,  194,   16,  193,   87,
 /*   250 */    69,  160,   26,  120,   34,   21,  187,  103,   17,   70,
 /*   260 */   109,  150,  156,  162,  194,   84,  193,   87,  169,  160,
 /*   270 */    89,   39,   54,  171,  187,  195,   23,  147,  129,  187,
 /*   280 */   156,  162,  194,  150,  193,   87,  168,  160,   54,  167,
 /*   290 */   182,  163,  187,  143,  139,   22,  111,  162,  194,   41,
 /*   300 */   193,   87,  174,  160,   54,   60,  143,  139,  187,   96,
 /*   310 */   157,  190,  117,  162,  194,   30,  193,   87,  164,  160,
 /*   320 */   168,   55,   26,  120,  187,   19,   23,  132,  112,  159,
 /*   330 */   162,  194,  108,  193,   87,   95,  160,  143,  139,   23,
 /*   340 */    52,  187,  307,   35,  128,  141,    6,  188,   41,  162,
 /*   350 */   194,  146,  193,   87,   59,  160,   12,  143,  139,  105,
 /*   360 */   187,  134,   79,  162,  194,  103,  193,   87,   66,  160,
 /*   370 */    23,  161,  165,  164,  187,   25,  102,  162,  194,  103,
 /*   380 */   193,   87,   58,  160,  158,  106,  107,  187,  187,  103,
 /*   390 */    23,  162,  194,   82,  193,   87,  187,  160,  195,   53,
 /*   400 */    38,   14,  187,   36,   48,  103,  144,  141,  162,  194,
 /*   410 */   101,  193,   87,   68,  160,  168,  170,   37,  168,  187,
 /*   420 */    75,  176,  162,  194,  121,  193,   87,   51,  160,  130,
 /*   430 */   165,  138,  168,  187,   31,    8,  162,  194,  145,  193,
 /*   440 */    87,   61,  160,   81,   78,    5,   25,  187,   41,   85,
 /*   450 */   162,  194,  153,  193,   87,  171,  160,  164,   64,  103,
 /*   460 */   127,  187,   77,   50,   42,  136,  203,  162,  194,  203,
 /*   470 */   193,   87,   62,  160,  203,  203,  203,  203,  187,  203,
 /*   480 */   203,  162,  194,  203,  193,   87,  203,  160,  203,  203,
 /*   490 */   162,  203,  187,  203,   86,  203,  160,  203,  203,  203,
 /*   500 */   203,  187,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,    7,    8,   11,   67,   11,   59,   20,   71,
 /*    10 */    20,   73,   24,   16,   24,   18,   78,   69,   24,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   33,    7,    8,   39,
 /*    30 */    42,   83,   42,   40,    1,   40,   42,    7,    8,   51,
 /*    40 */     1,   51,   24,   13,    5,   51,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,   33,    1,    2,   58,    4,   60,   22,
 /*    60 */    40,   63,   64,    9,   12,   67,   68,   15,   70,   71,
 /*    70 */    40,   73,   67,    1,   20,    1,   78,   44,   24,   74,
 /*    80 */    35,   36,   84,   78,   51,    1,   14,   24,   14,    6,
 /*    90 */    51,    8,   14,   10,   89,   12,   43,   14,   45,   46,
 /*   100 */    47,   48,   24,   50,   51,   51,   11,   24,   25,   37,
 /*   110 */     6,   61,    8,   41,   10,   41,   12,   34,   14,   57,
 /*   120 */    37,   38,    3,   51,   58,   51,   60,   72,   24,   25,
 /*   130 */    35,   36,   81,   67,   68,   51,   70,   71,   34,   73,
 /*   140 */    85,   37,   38,    3,   78,    6,    1,    8,   77,   10,
 /*   150 */    10,   12,   90,   14,   35,   36,   83,   17,   58,   19,
 /*   160 */    17,   21,   22,   24,   25,    7,    8,   67,   68,   75,
 /*   170 */    70,   71,   10,   73,    7,    8,   37,   38,   78,   17,
 /*   180 */    58,   23,   20,   16,    3,   63,    3,   87,   88,   67,
 /*   190 */    68,   19,   70,   71,   49,   73,   51,   67,   40,   14,
 /*   200 */    78,   71,   21,   73,   58,   22,   84,   40,   78,   63,
 /*   210 */    59,   24,   61,   67,   68,   13,   70,   71,   16,   73,
 /*   220 */    69,    6,   58,    8,   78,   10,   10,   12,   57,   14,
 /*   230 */    84,   67,   68,   17,   70,   71,   58,   73,   51,   24,
 /*   240 */    25,   63,   78,    7,    8,   67,   68,   20,   70,   71,
 /*   250 */    57,   73,   37,   38,   58,   12,   78,   21,   15,   63,
 /*   260 */    18,   90,   84,   67,   68,   22,   70,   71,   67,   73,
 /*   270 */    76,   65,   58,   79,   78,   81,   40,   35,   36,   78,
 /*   280 */    84,   67,   68,   90,   70,   71,   80,   73,   58,    3,
 /*   290 */    89,    6,   78,    7,    8,   10,   82,   67,   68,   14,
 /*   300 */    70,   71,    3,   73,   58,   65,    7,    8,   78,   24,
 /*   310 */    25,   11,   82,   67,   68,   77,   70,   71,   80,   73,
 /*   320 */    80,   58,   37,   38,   78,   20,   40,   11,   82,   24,
 /*   330 */    67,   68,   24,   70,   71,   24,   73,    7,    8,   40,
 /*   340 */    58,   78,   53,   54,   55,   56,   12,    3,   14,   67,
 /*   350 */    68,   88,   70,   71,   58,   73,   51,    7,    8,   66,
 /*   360 */    78,    3,   59,   67,   68,   21,   70,   71,   58,   73,
 /*   370 */    40,    3,   69,   80,   78,   72,   67,   67,   68,   21,
 /*   380 */    70,   71,   58,   73,    3,   67,   68,   78,   78,   21,
 /*   390 */    40,   67,   68,   76,   70,   71,   78,   73,   81,   58,
 /*   400 */    65,   17,   78,   65,   24,   21,   55,   56,   67,   68,
 /*   410 */    24,   70,   71,   58,   73,   80,   41,   65,   80,   78,
 /*   420 */    59,   24,   67,   68,    3,   70,   71,   58,   73,    3,
 /*   430 */    69,    3,   80,   78,   62,   10,   67,   68,    3,   70,
 /*   440 */    71,   58,   73,   24,   17,   86,   72,   78,   14,   62,
 /*   450 */    67,   68,   90,   70,   71,   79,   73,   80,   58,   21,
 /*   460 */    69,   78,   77,   75,   14,   60,   91,   67,   68,   91,
 /*   470 */    70,   71,   58,   73,   91,   91,   91,   91,   78,   91,
 /*   480 */    91,   67,   68,   91,   70,   71,   91,   73,   91,   91,
 /*   490 */    67,   91,   78,   91,   71,   91,   73,   91,   91,   91,
 /*   500 */    91,   78,
);
    const YY_SHIFT_USE_DFLT = -13;
    const YY_SHIFT_MAX = 116;
    static public $yy_shift_ofst = array(
 /*     0 */    53,  104,   83,   83,   83,   83,  139,  215,  139,  139,
 /*    10 */   139,  139,  139,  139,  139,  139,  139,  139,  139,  139,
 /*    20 */   139,  139,  139,  285,  285,  285,   74,  140,   72,  236,
 /*    30 */   243,  384,  334,   -7,   20,   53,  -10,  -12,   54,   -6,
 /*    40 */   187,  187,  187,   84,   84,  187,   84,  187,  438,  434,
 /*    50 */    37,  286,   -5,  299,  167,  158,   30,   33,  330,  330,
 /*    60 */   305,  330,  330,  145,  350,  119,  350,   95,  350,   39,
 /*    70 */   242,  344,  368,   45,   45,  181,   52,   52,   78,  358,
 /*    80 */   450,  425,   37,  172,  419,  143,  172,  172,  172,   37,
 /*    90 */   -13,  -13,  -13,  162,   -3,  183,  216,  202,  381,  380,
 /*   100 */   386,  426,  375,  308,  311,  421,  435,  428,  227,  185,
 /*   110 */    63,  300,  316,   18,  425,  427,  397,
);
    const YY_REDUCE_USE_DFLT = -63;
    const YY_REDUCE_MAX = 92;
    static public $yy_reduce_ofst = array(
 /*     0 */   289,   -2,  178,  146,  196,  122,  100,   66,  246,  230,
 /*    10 */   214,  263,  369,  341,  310,  355,  400,  414,  383,  324,
 /*    20 */   296,  164,  282,  423,  130,  -62,    5,  151,  201,  303,
 /*    30 */   194,  -52,  318,   55,   55,  351,  238,  238,  293,  238,
 /*    40 */   335,  206,  240,  171,   62,  338,  193,  352,  361,  309,
 /*    50 */   317,  374,  374,  374,  374,  374,  374,  362,  374,  374,
 /*    60 */   377,  374,  374,  362,  374,  359,  374,  359,  374,  362,
 /*    70 */   359,  391,  391,  359,  359,  391,  376,  376,  388,  391,
 /*    80 */   405,  385,   51,   50,   94,   73,   50,   50,   50,   51,
 /*    90 */    71,  387,  372,
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
        /* 27 */ array(3, 10, 17, 19, 21, 22, ),
        /* 28 */ array(1, 14, 37, 41, 51, ),
        /* 29 */ array(7, 8, 21, 40, ),
        /* 30 */ array(12, 15, 22, ),
        /* 31 */ array(17, 21, ),
        /* 32 */ array(12, 14, ),
        /* 33 */ array(7, 8, 11, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 34 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 33, 40, ),
        /* 35 */ array(1, 2, 4, 43, 45, 46, 47, 48, 50, 51, ),
        /* 36 */ array(20, 24, 39, 42, 51, ),
        /* 37 */ array(20, 24, 42, 51, ),
        /* 38 */ array(9, 20, 24, 51, ),
        /* 39 */ array(24, 42, 51, ),
        /* 40 */ array(24, 51, ),
        /* 41 */ array(24, 51, ),
        /* 42 */ array(24, 51, ),
        /* 43 */ array(1, 51, ),
        /* 44 */ array(1, 51, ),
        /* 45 */ array(24, 51, ),
        /* 46 */ array(1, 51, ),
        /* 47 */ array(24, 51, ),
        /* 48 */ array(21, ),
        /* 49 */ array(14, ),
        /* 50 */ array(22, ),
        /* 51 */ array(3, 7, 8, 40, ),
        /* 52 */ array(7, 8, 11, 40, ),
        /* 53 */ array(3, 7, 8, 40, ),
        /* 54 */ array(7, 8, 16, 40, ),
        /* 55 */ array(7, 8, 23, 40, ),
        /* 56 */ array(7, 8, 13, 40, ),
        /* 57 */ array(1, 44, 51, ),
        /* 58 */ array(7, 8, 40, ),
        /* 59 */ array(7, 8, 40, ),
        /* 60 */ array(20, 24, 51, ),
        /* 61 */ array(7, 8, 40, ),
        /* 62 */ array(7, 8, 40, ),
        /* 63 */ array(1, 49, 51, ),
        /* 64 */ array(7, 8, 40, ),
        /* 65 */ array(3, 35, 36, ),
        /* 66 */ array(7, 8, 40, ),
        /* 67 */ array(11, 35, 36, ),
        /* 68 */ array(7, 8, 40, ),
        /* 69 */ array(1, 5, 51, ),
        /* 70 */ array(18, 35, 36, ),
        /* 71 */ array(3, 21, ),
        /* 72 */ array(3, 21, ),
        /* 73 */ array(35, 36, ),
        /* 74 */ array(35, 36, ),
        /* 75 */ array(3, 21, ),
        /* 76 */ array(12, 15, ),
        /* 77 */ array(12, 15, ),
        /* 78 */ array(14, 24, ),
        /* 79 */ array(3, 21, ),
        /* 80 */ array(14, ),
        /* 81 */ array(10, ),
        /* 82 */ array(22, ),
        /* 83 */ array(19, ),
        /* 84 */ array(24, ),
        /* 85 */ array(17, ),
        /* 86 */ array(19, ),
        /* 87 */ array(19, ),
        /* 88 */ array(19, ),
        /* 89 */ array(22, ),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(10, 17, 20, ),
        /* 94 */ array(16, 18, ),
        /* 95 */ array(3, 22, ),
        /* 96 */ array(10, 17, ),
        /* 97 */ array(13, 16, ),
        /* 98 */ array(3, ),
        /* 99 */ array(24, ),
        /* 100 */ array(24, ),
        /* 101 */ array(3, ),
        /* 102 */ array(41, ),
        /* 103 */ array(24, ),
        /* 104 */ array(24, ),
        /* 105 */ array(3, ),
        /* 106 */ array(3, ),
        /* 107 */ array(3, ),
        /* 108 */ array(20, ),
        /* 109 */ array(14, ),
        /* 110 */ array(24, ),
        /* 111 */ array(11, ),
        /* 112 */ array(11, ),
        /* 113 */ array(24, ),
        /* 114 */ array(10, ),
        /* 115 */ array(17, ),
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
);
    static public $yy_default = array(
 /*     0 */   306,  306,  306,  306,  306,  306,  293,  306,  269,  269,
 /*    10 */   269,  306,  306,  306,  306,  306,  306,  306,  306,  306,
 /*    20 */   306,  306,  306,  306,  306,  306,  306,  246,  306,  223,
 /*    30 */   249,  223,  306,  277,  277,  196,  253,  253,  306,  253,
 /*    40 */   306,  306,  306,  306,  306,  306,  306,  306,  223,  306,
 /*    50 */   242,  306,  306,  306,  268,  294,  306,  306,  227,  295,
 /*    60 */   306,  219,  254,  306,  224,  306,  273,  306,  278,  306,
 /*    70 */   306,  306,  306,  275,  279,  306,  245,  263,  306,  306,
 /*    80 */   306,  253,  243,  232,  306,  236,  233,  230,  231,  260,
 /*    90 */   253,  272,  272,  246,  306,  306,  246,  306,  306,  306,
 /*   100 */   306,  306,  306,  306,  306,  306,  306,  306,  306,  306,
 /*   110 */   306,  306,  306,  306,  244,  306,  306,  267,  205,  206,
 /*   120 */   247,  216,  220,  203,  204,  248,  207,  221,  197,  289,
 /*   130 */   214,  276,  266,  264,  208,  291,  226,  290,  218,  234,
 /*   140 */   225,  199,  255,  235,  198,  217,  292,  288,  201,  202,
 /*   150 */   303,  305,  200,  302,  304,  213,  274,  240,  209,  258,
 /*   160 */   239,  210,  237,  238,  257,  222,  241,  259,  256,  298,
 /*   170 */   299,  252,  297,  301,  300,  211,  250,  284,  283,  282,
 /*   180 */   285,  286,  296,  262,  287,  281,  280,  251,  212,  270,
 /*   190 */   265,  215,  271,  228,  229,  261,
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
    const YYNSTATE = 196;
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
 /*  56 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  57 */ "vararraydefs ::=",
 /*  58 */ "vararraydef ::= DOT expr",
 /*  59 */ "vararraydef ::= OPENB expr CLOSEB",
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
        34 => 0,
        41 => 0,
        42 => 0,
        43 => 0,
        44 => 0,
        51 => 0,
        55 => 0,
        95 => 0,
        1 => 1,
        32 => 1,
        33 => 1,
        38 => 1,
        39 => 1,
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
#line 1510 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1513 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1516 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1521 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1524 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1527 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1530 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1533 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1539 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1545 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1548 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1551 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1554 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1557 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1560 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1563 "internal.templateparser.php"
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
#line 1578 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1581 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1584 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1587 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1590 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1593 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1596 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1599 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1602 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1605 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1608 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1611 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1614 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1617 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r35(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1620 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1623 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1626 "internal.templateparser.php"
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
#line 1640 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1643 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1646 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1649 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1652 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1655 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1658 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1661 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r53(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1665 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1668 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r57(){return;    }
#line 1671 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1674 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1677 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r61(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1680 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1683 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1686 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r64(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1689 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1692 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1695 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1698 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1701 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r69(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1710 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1713 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1716 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r73(){ return;    }
#line 1719 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1722 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1725 "internal.templateparser.php"
#line 319 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1728 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1731 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1734 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1737 "internal.templateparser.php"
#line 335 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '==';    }
#line 1740 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '!=';    }
#line 1743 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = '>';    }
#line 1746 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = '<';    }
#line 1749 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '>=';    }
#line 1752 "internal.templateparser.php"
#line 340 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '<=';    }
#line 1755 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '===';    }
#line 1758 "internal.templateparser.php"
#line 342 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '!==';    }
#line 1761 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '&&';    }
#line 1764 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '||';    }
#line 1767 "internal.templateparser.php"
#line 347 "internal.templateparser.y"
    function yy_r94(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1770 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r96(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1773 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r97(){ return;     }
#line 1776 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r99(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1779 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1782 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1785 "internal.templateparser.php"
#line 358 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1788 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1791 "internal.templateparser.php"

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
#line 1908 "internal.templateparser.php"
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
#line 1933 "internal.templateparser.php"
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
