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
    const YY_NO_ACTION = 320;
    const YY_ACCEPT_ACTION = 319;
    const YY_ERROR_ACTION = 318;

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
    const YY_SZ_ACTTAB = 513;
static public $yy_action = array(
 /*     0 */   166,   48,   23,   94,    3,   14,    6,  201,   42,  199,
 /*    10 */   161,  163,   21,   37,  162,   22,  167,  139,   86,  120,
 /*    20 */    77,  157,   75,  114,   32,  197,  156,    4,   91,    9,
 /*    30 */    26,   39,  146,   53,  177,  121,  101,   13,   58,   84,
 /*    40 */   127,  130,  161,  163,  144,   37,  162,   42,  167,  177,
 /*    50 */   166,   28,   23,  157,    3,  157,    6,  231,   45,  135,
 /*    60 */   150,  100,   42,   41,   10,   10,  176,   71,   82,  168,
 /*    70 */   131,  102,  102,   89,   50,    1,   96,    4,   53,   11,
 /*    80 */    26,   39,  146,   57,  175,   17,  101,  161,  163,   50,
 /*    90 */    37,  162,   76,  167,  319,   33,  124,  133,  157,   72,
 /*   100 */    17,    5,  120,   43,  135,  190,  193,  153,   44,   14,
 /*   110 */   148,    8,  166,  199,   23,  166,   12,   23,    6,   12,
 /*   120 */    42,    6,  180,   40,  126,  161,  127,  130,   62,  162,
 /*   130 */    86,  167,   91,   27,   31,   15,  157,  120,  171,  199,
 /*   140 */   108,   13,   26,   39,  146,   26,   39,  146,  101,   73,
 /*   150 */    53,  101,  159,  131,  197,   67,  127,  130,  120,  161,
 /*   160 */   163,   74,   37,  162,  159,  167,  197,   13,  194,  174,
 /*   170 */   157,  199,   52,  194,  174,   29,  135,   56,  198,  195,
 /*   180 */    89,  161,  163,  178,   37,  162,  151,  167,  131,  166,
 /*   190 */    91,   23,  157,   12,  104,  103,  152,   42,  135,   13,
 /*   200 */    21,   24,  202,   22,  152,  157,   24,   86,   66,  131,
 /*   210 */   115,  118,  143,  138,  137,  125,  142,  141,  187,   26,
 /*   220 */    39,  146,   53,   10,  119,  101,  123,   64,   65,   87,
 /*   230 */   102,  161,  163,   19,   37,  162,   79,  167,  187,   88,
 /*   240 */    14,  112,  157,  111,  199,  194,  174,   25,  135,   90,
 /*   250 */   161,  163,  164,   37,  162,  199,  167,  127,  130,   81,
 /*   260 */   157,  157,  161,  182,  144,   54,  162,  110,  167,  120,
 /*   270 */    85,  173,   13,  157,  183,  157,  161,  163,   24,   37,
 /*   280 */   162,   30,  167,   13,  203,  110,  179,  157,  184,  169,
 /*   290 */   200,  107,   98,  105,  161,  163,   19,   37,  162,  136,
 /*   300 */   167,  110,   98,  120,  166,  157,   98,   98,   12,   92,
 /*   310 */   161,  163,   42,   37,  162,    6,  167,   42,  134,  133,
 /*   320 */   131,  157,   86,   38,  192,  191,  115,  118,  143,  138,
 /*   330 */   137,  125,  142,  141,   26,   39,  146,  112,  188,  172,
 /*   340 */   101,   36,   46,   68,   97,   80,  161,  163,  109,   37,
 /*   350 */   162,  154,  167,  187,  131,   18,  188,  157,  198,   98,
 /*   360 */   161,  163,  122,   37,  162,  140,  167,  155,  116,   61,
 /*   370 */    55,  157,   20,   63,  161,  163,  132,   37,  162,   59,
 /*   380 */   167,  129,   49,   34,   70,  157,  189,   69,    7,  145,
 /*   390 */   161,  163,   99,   37,  162,  165,  167,  187,  188,   60,
 /*   400 */    83,  157,  185,  185,  161,  163,  185,   37,  162,  106,
 /*   410 */   167,   35,  185,  186,  188,  157,  149,    2,  161,  163,
 /*   420 */   117,   37,  162,  113,  167,   25,  188,  170,   98,  157,
 /*   430 */    16,   75,  161,  163,  198,   37,  162,   95,  167,   42,
 /*   440 */    18,   78,   51,  157,  164,  196,  161,  163,  128,   37,
 /*   450 */   162,  181,  167,   47,  159,  208,  208,  157,  208,  208,
 /*   460 */   161,  163,  208,   37,  162,   93,  167,  208,  208,  208,
 /*   470 */   208,  157,  208,  208,  161,  163,  208,   37,  162,  208,
 /*   480 */   167,  208,  208,  208,  161,  157,  208,  161,  147,  208,
 /*   490 */   167,  158,  208,  167,  208,  157,  208,  208,  157,  208,
 /*   500 */   208,  161,  208,  208,  208,  160,  208,  167,  208,  208,
 /*   510 */   208,  208,  157,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   59,    8,   61,   10,   20,   12,   11,   14,   24,
 /*    10 */    68,   69,   12,   71,   72,   15,   74,   11,   24,    1,
 /*    20 */    77,   79,   22,    5,   39,   82,   13,   33,   43,   16,
 /*    30 */    36,   37,   38,   59,    1,   61,   42,   52,   64,   65,
 /*    40 */    34,   35,   68,   69,   68,   71,   72,   14,   74,    1,
 /*    50 */     6,   75,    8,   79,   10,   79,   12,    3,   14,   85,
 /*    60 */     1,    2,   14,    4,   10,   10,   90,   78,   24,   36,
 /*    70 */    52,   17,   17,   19,   41,   21,   22,   33,   59,   10,
 /*    80 */    36,   37,   38,   64,   36,   52,   42,   68,   69,   41,
 /*    90 */    71,   72,   63,   74,   54,   55,   56,   57,   79,   16,
 /*   100 */    52,   18,    1,   44,   85,   46,   47,   48,   49,   20,
 /*   110 */    51,   52,    6,   24,    8,    6,   10,    8,   12,   10,
 /*   120 */    14,   12,   41,   14,    9,   68,   34,   35,   71,   72,
 /*   130 */    24,   74,   43,   24,   63,   20,   79,    1,   37,   24,
 /*   140 */    18,   52,   36,   37,   38,   36,   37,   38,   42,   77,
 /*   150 */    59,   42,   80,   52,   82,   64,   34,   35,    1,   68,
 /*   160 */    69,   77,   71,   72,   80,   74,   82,   52,    7,    8,
 /*   170 */    79,   24,   59,    7,    8,   78,   85,   64,   81,   13,
 /*   180 */    19,   68,   69,    3,   71,   72,   50,   74,   52,    6,
 /*   190 */    43,    8,   79,   10,   68,   69,   11,   14,   85,   52,
 /*   200 */    12,   40,   45,   15,   11,   79,   40,   24,   60,   52,
 /*   210 */    25,   26,   27,   28,   29,   30,   31,   32,   70,   36,
 /*   220 */    37,   38,   59,   10,    3,   42,    3,   64,   60,   14,
 /*   230 */    17,   68,   69,   20,   71,   72,   62,   74,   70,   24,
 /*   240 */    20,   59,   79,   22,   24,    7,    8,   73,   85,   68,
 /*   250 */    68,   69,   84,   71,   72,   24,   74,   34,   35,   24,
 /*   260 */    79,   79,   68,   24,   68,   71,   72,   59,   74,    1,
 /*   270 */    88,   89,   52,   79,    3,   79,   68,   69,   40,   71,
 /*   280 */    72,   78,   74,   52,    3,   59,   90,   79,    3,    3,
 /*   290 */    24,   83,   21,   24,   68,   69,   20,   71,   72,    3,
 /*   300 */    74,   59,   21,    1,    6,   79,   21,   21,   10,   83,
 /*   310 */    68,   69,   14,   71,   72,   12,   74,   14,   56,   57,
 /*   320 */    52,   79,   24,   66,   11,   83,   25,   26,   27,   28,
 /*   330 */    29,   30,   31,   32,   36,   37,   38,   59,   81,   37,
 /*   340 */    42,   66,   14,   60,   67,   62,   68,   69,   24,   71,
 /*   350 */    72,   59,   74,   70,   52,   17,   81,   79,   81,   21,
 /*   360 */    68,   69,    3,   71,   72,   59,   74,   89,    3,   58,
 /*   370 */    58,   79,   23,   58,   68,   69,    3,   71,   72,   58,
 /*   380 */    74,   59,   24,   66,   17,   79,    3,   60,   16,   42,
 /*   390 */    68,   69,   24,   71,   72,   59,   74,   70,   81,   66,
 /*   400 */    24,   79,   91,   91,   68,   69,   91,   71,   72,   59,
 /*   410 */    74,   66,   91,    3,   81,   79,   82,   87,   68,   69,
 /*   420 */    91,   71,   72,   59,   74,   73,   81,   70,   21,   79,
 /*   430 */    86,   22,   68,   69,   81,   71,   72,   59,   74,   14,
 /*   440 */    17,   24,   76,   79,   84,   76,   68,   69,   61,   71,
 /*   450 */    72,   59,   74,   14,   80,   92,   92,   79,   92,   92,
 /*   460 */    68,   69,   92,   71,   72,   59,   74,   92,   92,   92,
 /*   470 */    92,   79,   92,   92,   68,   69,   92,   71,   72,   92,
 /*   480 */    74,   92,   92,   92,   68,   79,   92,   68,   72,   92,
 /*   490 */    74,   72,   92,   74,   92,   79,   92,   92,   79,   92,
 /*   500 */    92,   68,   92,   92,   92,   72,   92,   74,   92,   92,
 /*   510 */    92,   92,   79,
);
    const YY_SHIFT_USE_DFLT = -16;
    const YY_SHIFT_MAX = 112;
    static public $yy_shift_ofst = array(
 /*     0 */    59,   44,   -6,   -6,   -6,   -6,  106,  106,  109,  106,
 /*    10 */   106,  106,  106,  106,  106,  106,  106,  106,  106,  106,
 /*    20 */   106,  183,  183,  298,  298,  298,   48,   54,   33,    0,
 /*    30 */     0,  338,  303,   59,  -15,  115,   89,  161,  147,  101,
 /*    40 */   231,  268,  231,  268,  268,  231,  231,  231,  407,  407,
 /*    50 */   425,  409,  185,  301,  166,   18,    6,  122,  223,  136,
 /*    60 */   220,  157,  238,  302,   92,  281,  285,   92,  271,  286,
 /*    70 */   215,  188,  439,  409,  409,  417,  423,  409,   69,  -16,
 /*    80 */   -16,  -16,  213,  221,   83,   13,   55,  235,   69,  239,
 /*    90 */    81,  266,   -4,  180,  410,  383,  358,  373,  269,  347,
 /*   100 */   376,  368,  367,  365,  296,  276,  193,  313,  328,  359,
 /*   110 */   372,  324,  349,
);
    const YY_REDUCE_USE_DFLT = -59;
    const YY_REDUCE_MAX = 81;
    static public $yy_reduce_ofst = array(
 /*     0 */    40,  -26,  163,  113,   91,   19,  182,  242,  -58,  278,
 /*    10 */   226,  208,  350,  378,  306,  322,  364,  406,  392,  336,
 /*    20 */   292,  194,   57,  433,  416,  419,  -24,  283,  196,   84,
 /*    30 */    72,  168,  126,  262,   97,  277,   97,  174,   97,  315,
 /*    40 */   275,  312,  257,  311,  321,  317,  345,  333,  327,  148,
 /*    50 */   181,  -57,  344,  344,  352,  329,  330,  330,  330,  329,
 /*    60 */   353,  329,  352,  329,  330,  357,  357,  330,  357,  357,
 /*    70 */   366,  374,  387,  334,  334,  369,  360,  334,  -11,   29,
 /*    80 */    71,  203,
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
        /* 26 */ array(1, 14, 36, 41, 52, ),
        /* 27 */ array(3, 10, 17, 19, 21, 22, ),
        /* 28 */ array(1, 14, 36, 41, 52, ),
        /* 29 */ array(12, 15, 22, ),
        /* 30 */ array(12, 15, 22, ),
        /* 31 */ array(17, 21, ),
        /* 32 */ array(12, 14, ),
        /* 33 */ array(1, 2, 4, 44, 46, 47, 48, 49, 51, 52, ),
        /* 34 */ array(20, 24, 39, 43, 52, ),
        /* 35 */ array(9, 20, 24, 52, ),
        /* 36 */ array(20, 24, 43, 52, ),
        /* 37 */ array(7, 8, 19, 40, ),
        /* 38 */ array(24, 43, 52, ),
        /* 39 */ array(1, 37, 52, ),
        /* 40 */ array(24, 52, ),
        /* 41 */ array(1, 52, ),
        /* 42 */ array(24, 52, ),
        /* 43 */ array(1, 52, ),
        /* 44 */ array(1, 52, ),
        /* 45 */ array(24, 52, ),
        /* 46 */ array(24, 52, ),
        /* 47 */ array(24, 52, ),
        /* 48 */ array(21, ),
        /* 49 */ array(21, ),
        /* 50 */ array(14, ),
        /* 51 */ array(22, ),
        /* 52 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 53 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 54 */ array(7, 8, 13, 40, ),
        /* 55 */ array(1, 5, 52, ),
        /* 56 */ array(11, 34, 35, ),
        /* 57 */ array(18, 34, 35, ),
        /* 58 */ array(3, 34, 35, ),
        /* 59 */ array(1, 50, 52, ),
        /* 60 */ array(20, 24, 52, ),
        /* 61 */ array(1, 45, 52, ),
        /* 62 */ array(7, 8, 40, ),
        /* 63 */ array(1, 37, 52, ),
        /* 64 */ array(34, 35, ),
        /* 65 */ array(3, 21, ),
        /* 66 */ array(3, 21, ),
        /* 67 */ array(34, 35, ),
        /* 68 */ array(3, 21, ),
        /* 69 */ array(3, 21, ),
        /* 70 */ array(14, 24, ),
        /* 71 */ array(12, 15, ),
        /* 72 */ array(14, ),
        /* 73 */ array(22, ),
        /* 74 */ array(22, ),
        /* 75 */ array(24, ),
        /* 76 */ array(17, ),
        /* 77 */ array(22, ),
        /* 78 */ array(10, ),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(10, 17, 20, ),
        /* 83 */ array(3, 22, ),
        /* 84 */ array(16, 18, ),
        /* 85 */ array(13, 16, ),
        /* 86 */ array(10, 17, ),
        /* 87 */ array(24, ),
        /* 88 */ array(10, ),
        /* 89 */ array(24, ),
        /* 90 */ array(41, ),
        /* 91 */ array(24, ),
        /* 92 */ array(11, ),
        /* 93 */ array(3, ),
        /* 94 */ array(3, ),
        /* 95 */ array(3, ),
        /* 96 */ array(24, ),
        /* 97 */ array(3, ),
        /* 98 */ array(24, ),
        /* 99 */ array(42, ),
        /* 100 */ array(24, ),
        /* 101 */ array(24, ),
        /* 102 */ array(17, ),
        /* 103 */ array(3, ),
        /* 104 */ array(3, ),
        /* 105 */ array(20, ),
        /* 106 */ array(11, ),
        /* 107 */ array(11, ),
        /* 108 */ array(14, ),
        /* 109 */ array(3, ),
        /* 110 */ array(16, ),
        /* 111 */ array(24, ),
        /* 112 */ array(23, ),
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
        /* 202 */ array(),
        /* 203 */ array(),
);
    static public $yy_default = array(
 /*     0 */   318,  318,  318,  318,  318,  318,  305,  281,  318,  318,
 /*    10 */   281,  281,  318,  318,  318,  318,  318,  318,  318,  318,
 /*    20 */   318,  318,  318,  318,  318,  318,  318,  257,  318,  261,
 /*    30 */   255,  231,  318,  204,  265,  318,  265,  236,  265,  318,
 /*    40 */   318,  318,  318,  318,  318,  318,  318,  318,  231,  231,
 /*    50 */   318,  252,  289,  289,  318,  318,  318,  318,  318,  318,
 /*    60 */   318,  318,  266,  318,  291,  318,  318,  287,  318,  318,
 /*    70 */   318,  275,  318,  256,  272,  318,  237,  253,  265,  284,
 /*    80 */   284,  265,  257,  318,  318,  318,  257,  318,  254,  318,
 /*    90 */   318,  318,  318,  318,  318,  318,  318,  318,  318,  318,
 /*   100 */   318,  318,  318,  318,  318,  318,  318,  318,  318,  318,
 /*   110 */   280,  318,  306,  290,  208,  292,  226,  314,  293,  221,
 /*   120 */   316,  233,  222,  223,  205,  297,  228,  300,  234,  227,
 /*   130 */   301,  317,  224,  207,  206,  286,  225,  296,  295,  288,
 /*   140 */   235,  299,  298,  294,  310,  258,  259,  242,  214,  274,
 /*   150 */   215,  213,  260,  212,  307,  304,  302,  263,  241,  264,
 /*   160 */   240,  245,  239,  238,  283,  232,  246,  247,  250,  216,
 /*   170 */   229,  249,  248,  303,  243,  251,  309,  313,  312,  308,
 /*   180 */   311,  285,  282,  218,  219,  315,  217,  230,  268,  271,
 /*   190 */   210,  279,  278,  211,  244,  267,  276,  273,  269,  270,
 /*   200 */   262,  277,  209,  220,
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
    const YYNSTATE = 204;
    const YYNRULE = 114;
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
 /*  45 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  46 */ "value ::= QUOTE doublequoted QUOTE",
 /*  47 */ "value ::= QUOTE QUOTE",
 /*  48 */ "value ::= ID COLON COLON method",
 /*  49 */ "value ::= ID COLON COLON method objectchain",
 /*  50 */ "value ::= ID COLON COLON ID",
 /*  51 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  52 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
 /*  53 */ "value ::= ID",
 /*  54 */ "value ::= HATCH ID HATCH",
 /*  55 */ "value ::= BOOLEAN",
 /*  56 */ "value ::= OPENP expr CLOSEP",
 /*  57 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  58 */ "variable ::= DOLLAR varvar AT ID",
 /*  59 */ "variable ::= object",
 /*  60 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  61 */ "vararraydefs ::=",
 /*  62 */ "vararraydef ::= DOT exprs",
 /*  63 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  64 */ "varvar ::= varvarele",
 /*  65 */ "varvar ::= varvar varvarele",
 /*  66 */ "varvarele ::= ID",
 /*  67 */ "varvarele ::= LDEL expr RDEL",
 /*  68 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  69 */ "objectchain ::= objectelement",
 /*  70 */ "objectchain ::= objectchain objectelement",
 /*  71 */ "objectelement ::= PTR ID vararraydefs",
 /*  72 */ "objectelement ::= PTR method",
 /*  73 */ "function ::= ID OPENP params CLOSEP",
 /*  74 */ "method ::= ID OPENP params CLOSEP",
 /*  75 */ "params ::= expr COMMA params",
 /*  76 */ "params ::= expr",
 /*  77 */ "params ::=",
 /*  78 */ "modifier ::= VERT ID",
 /*  79 */ "modparameters ::= modparameters modparameter",
 /*  80 */ "modparameters ::=",
 /*  81 */ "modparameter ::= COLON expr",
 /*  82 */ "ifexprs ::= ifexpr",
 /*  83 */ "ifexprs ::= NOT ifexprs",
 /*  84 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  85 */ "ifexpr ::= expr",
 /*  86 */ "ifexpr ::= expr ifcond expr",
 /*  87 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  88 */ "ifcond ::= EQUALS",
 /*  89 */ "ifcond ::= NOTEQUALS",
 /*  90 */ "ifcond ::= GREATERTHAN",
 /*  91 */ "ifcond ::= LESSTHAN",
 /*  92 */ "ifcond ::= GREATEREQUAL",
 /*  93 */ "ifcond ::= LESSEQUAL",
 /*  94 */ "ifcond ::= IDENTITY",
 /*  95 */ "ifcond ::= NONEIDENTITY",
 /*  96 */ "lop ::= LAND",
 /*  97 */ "lop ::= LOR",
 /*  98 */ "array ::= OPENB arrayelements CLOSEB",
 /*  99 */ "arrayelements ::= arrayelement",
 /* 100 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 101 */ "arrayelements ::=",
 /* 102 */ "arrayelement ::= expr",
 /* 103 */ "arrayelement ::= expr APTR expr",
 /* 104 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 105 */ "doublequoted ::= doublequotedcontent",
 /* 106 */ "doublequotedcontent ::= variable",
 /* 107 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 108 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 109 */ "doublequotedcontent ::= OTHER",
 /* 110 */ "text ::= text textelement",
 /* 111 */ "text ::= textelement",
 /* 112 */ "textelement ::= OTHER",
 /* 113 */ "textelement ::= LDEL",
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
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
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
        55 => 0,
        59 => 0,
        99 => 0,
        1 => 1,
        32 => 1,
        34 => 1,
        39 => 1,
        40 => 1,
        64 => 1,
        82 => 1,
        105 => 1,
        111 => 1,
        112 => 1,
        113 => 1,
        2 => 2,
        60 => 2,
        104 => 2,
        110 => 2,
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
        76 => 24,
        102 => 24,
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
        46 => 44,
        45 => 45,
        47 => 45,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        56 => 56,
        57 => 57,
        58 => 58,
        61 => 61,
        80 => 61,
        62 => 62,
        63 => 63,
        65 => 65,
        66 => 66,
        67 => 67,
        84 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        77 => 77,
        78 => 78,
        79 => 79,
        81 => 81,
        83 => 83,
        85 => 85,
        86 => 86,
        87 => 86,
        88 => 88,
        89 => 89,
        90 => 90,
        91 => 91,
        92 => 92,
        93 => 93,
        94 => 94,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        100 => 100,
        101 => 101,
        103 => 103,
        106 => 106,
        107 => 107,
        108 => 108,
        109 => 109,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 69 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1534 "internal.templateparser.php"
#line 75 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1537 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1540 "internal.templateparser.php"
#line 83 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1545 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1548 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1551 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1554 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1557 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1563 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1569 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1572 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1575 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1578 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1581 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1584 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1587 "internal.templateparser.php"
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
#line 1602 "internal.templateparser.php"
#line 137 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1605 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1608 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1611 "internal.templateparser.php"
#line 143 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1614 "internal.templateparser.php"
#line 145 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1617 "internal.templateparser.php"
#line 147 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1620 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1623 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1626 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1629 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1632 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1635 "internal.templateparser.php"
#line 167 "internal.templateparser.y"
    function yy_r30(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1638 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1641 "internal.templateparser.php"
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
#line 1655 "internal.templateparser.php"
#line 193 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1658 "internal.templateparser.php"
#line 195 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1661 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1664 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1667 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = "''";     }
#line 1670 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1673 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1676 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1679 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1682 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1685 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1688 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1691 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1694 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r57(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1698 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1701 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r61(){return;    }
#line 1704 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1707 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1710 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1713 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1716 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1719 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1722 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1725 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1728 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r71(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1731 "internal.templateparser.php"
#line 297 "internal.templateparser.y"
    function yy_r72(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1734 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r73(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1743 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1746 "internal.templateparser.php"
#line 317 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1749 "internal.templateparser.php"
#line 321 "internal.templateparser.y"
    function yy_r77(){ return;    }
#line 1752 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1755 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1758 "internal.templateparser.php"
#line 336 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1761 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1764 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1767 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r86(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1770 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = '==';    }
#line 1773 "internal.templateparser.php"
#line 353 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '!=';    }
#line 1776 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '>';    }
#line 1779 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '<';    }
#line 1782 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = '>=';    }
#line 1785 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = '<=';    }
#line 1788 "internal.templateparser.php"
#line 358 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '===';    }
#line 1791 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!==';    }
#line 1794 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '&&';    }
#line 1797 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '||';    }
#line 1800 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r98(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1803 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r100(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1806 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r101(){ return;     }
#line 1809 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r103(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1812 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1815 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1818 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1821 "internal.templateparser.php"
#line 376 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1824 "internal.templateparser.php"

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
#line 1941 "internal.templateparser.php"
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
#line 1966 "internal.templateparser.php"
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
