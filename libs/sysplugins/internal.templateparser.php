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
    const TP_NULL                           = 39;
    const TP_IN                             = 40;
    const TP_ANDSYM                         = 41;
    const TP_BACKTICK                       = 42;
    const TP_HATCH                          = 43;
    const TP_AT                             = 44;
    const TP_LITERALSTART                   = 45;
    const TP_LITERALEND                     = 46;
    const TP_LDELIMTAG                      = 47;
    const TP_RDELIMTAG                      = 48;
    const TP_PHP                            = 49;
    const TP_PHPSTART                       = 50;
    const TP_PHPEND                         = 51;
    const TP_XML                            = 52;
    const TP_LDEL                           = 53;
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
    const YY_SZ_ACTTAB = 627;
static public $yy_action = array(
 /*     0 */   151,   16,   26,   14,    3,  190,    6,  112,   43,  207,
 /*    10 */   154,  151,    7,   26,  208,    3,  152,    6,   92,   45,
 /*    20 */   195,   34,  184,  214,  209,  102,   50,    4,  185,   86,
 /*    30 */    28,   40,  160,  161,   19,  121,   23,  119,    4,   24,
 /*    40 */   184,   28,   40,  160,  161,  151,   77,   26,  119,   18,
 /*    50 */   188,    6,  185,   43,  151,  147,   26,   27,   18,   30,
 /*    60 */     6,  168,   47,   92,  184,  151,   13,   26,   62,   18,
 /*    70 */   190,    6,   29,   43,  198,   28,   40,  160,  161,  192,
 /*    80 */    97,    8,  119,   89,   28,   40,  160,  161,  107,  168,
 /*    90 */    98,  119,   43,    8,   15,   28,   40,  160,  161,   19,
 /*   100 */   107,  170,  119,  151,   16,   26,  175,   18,  190,    6,
 /*   110 */   110,   43,  154,  169,  159,   57,  157,   56,  152,  124,
 /*   120 */    53,   91,   61,   93,  184,  200,  154,  163,  102,   37,
 /*   130 */   157,   20,  152,   28,   40,  160,  161,   19,  184,  180,
 /*   140 */   119,  169,  151,  137,   26,  154,   18,  111,    6,  164,
 /*   150 */    43,  152,  177,  116,  190,   42,   56,  184,  113,  117,
 /*   160 */    87,   71,   70,  144,  141,  154,  163,  192,   37,  157,
 /*   170 */   184,  152,   28,   40,  160,  161,   33,  184,    8,  119,
 /*   180 */    43,  211,  137,   19,   11,  107,  149,  133,  128,  134,
 /*   190 */   138,  132,  125,  127,  214,  209,   46,  216,  176,  181,
 /*   200 */   182,   48,  193,  179,    9,  151,   16,   26,   53,   18,
 /*   210 */   190,   79,  129,   43,  151,  112,   26,  140,   18,   20,
 /*   220 */     8,   76,   43,   88,  156,  151,  201,  107,   27,   18,
 /*   230 */    17,  115,   90,   43,  191,   28,   40,  160,  161,   19,
 /*   240 */   144,  141,  119,   92,   28,   40,  160,  161,  168,  183,
 /*   250 */    31,  119,  172,  200,  178,   28,   40,  160,  161,   81,
 /*   260 */   162,    5,  119,  149,  133,  128,  134,  138,  132,  125,
 /*   270 */   127,  168,  112,  154,  118,   78,   59,  157,  156,  152,
 /*   280 */   201,    8,   41,  154,  163,  184,   37,  157,  107,  152,
 /*   290 */   121,   23,    1,  100,   24,  184,   56,    6,  189,   43,
 /*   300 */   169,   58,   82,   94,  202,  154,  163,  153,   37,  157,
 /*   310 */   190,  152,  214,  209,   67,  131,   55,  184,  210,  173,
 /*   320 */   174,   60,  137,  169,  194,  154,  163,   63,   37,  157,
 /*   330 */   102,  152,  168,  112,  168,   56,   75,  184,  165,   19,
 /*   340 */    72,  201,  137,  105,  154,  163,   27,   37,  157,  139,
 /*   350 */   152,  340,   35,  130,  174,  184,  184,  122,   95,  154,
 /*   360 */   170,  137,  114,  155,  118,  152,  154,  163,  158,   37,
 /*   370 */   157,  184,  152,  154,  163,  101,   37,  157,  184,  152,
 /*   380 */   144,  141,  123,  122,  169,  184,  169,   69,  135,   85,
 /*   390 */   122,  148,  154,  163,  205,   37,  157,  194,  152,  154,
 /*   400 */   163,  212,   37,  157,  184,  152,  144,  141,  120,  112,
 /*   410 */    84,  184,   38,   68,   64,   73,   21,   51,   36,   99,
 /*   420 */    66,   25,  109,  194,   12,  194,  154,  163,  189,   37,
 /*   430 */   157,  122,  152,  215,  189,  213,  126,  108,  184,  199,
 /*   440 */   154,  163,   65,   37,  157,   80,  152,  170,  187,  104,
 /*   450 */   201,  145,  184,  170,   39,  150,  186,  183,  189,  142,
 /*   460 */   154,  163,   49,   37,  157,   96,  152,   74,   54,   17,
 /*   470 */   189,  167,  184,  171,  154,  163,   52,   37,  157,  200,
 /*   480 */   152,   43,    2,  156,   77,  112,  184,  136,   14,   22,
 /*   490 */    32,   25,   83,   10,  146,  166,  154,  163,  197,   37,
 /*   500 */   157,  204,  152,  165,   44,  217,  217,  217,  184,  217,
 /*   510 */   154,  163,  217,   37,  157,  217,  152,  217,  217,  217,
 /*   520 */   217,  206,  184,  217,  217,  217,  217,  217,  217,  217,
 /*   530 */   154,  163,  217,   37,  157,  203,  152,  217,  217,  217,
 /*   540 */   217,  217,  184,  217,  154,  163,  217,   37,  157,  217,
 /*   550 */   152,  217,  217,  217,  217,  217,  184,  143,  217,  217,
 /*   560 */   217,  217,  217,  217,  217,  217,  154,  163,  217,   37,
 /*   570 */   157,  196,  152,  217,  217,  217,  217,  217,  184,  217,
 /*   580 */   154,  163,  217,   37,  157,  217,  152,  217,  217,  217,
 /*   590 */   217,  103,  184,  217,  217,  217,  217,  217,  217,  217,
 /*   600 */   154,  163,  217,   37,  157,  106,  152,  217,  217,  217,
 /*   610 */   217,  217,  184,  217,  154,  163,  217,   37,  157,  217,
 /*   620 */   152,  217,  217,  217,  217,  217,  184,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   20,    8,   17,   10,   24,   12,   21,   14,   13,
 /*    10 */    69,    6,   16,    8,   73,   10,   75,   12,   24,   14,
 /*    20 */     3,   40,   81,    7,    8,   44,   24,   33,   69,   24,
 /*    30 */    36,   37,   38,   39,   53,   19,   12,   43,   33,   15,
 /*    40 */    81,   36,   37,   38,   39,    6,   22,    8,   43,   10,
 /*    50 */    91,   12,   69,   14,    6,    9,    8,   41,   10,   76,
 /*    60 */    12,    1,   14,   24,   81,    6,   20,    8,   59,   10,
 /*    70 */    24,   12,   24,   14,   91,   36,   37,   38,   39,    1,
 /*    80 */    14,   10,   43,   24,   36,   37,   38,   39,   17,    1,
 /*    90 */    24,   43,   14,   10,   23,   36,   37,   38,   39,   53,
 /*   100 */    17,   92,   43,    6,   20,    8,   46,   10,   24,   12,
 /*   110 */    68,   14,   69,   53,   36,   72,   73,   60,   75,   62,
 /*   120 */    42,   24,   65,   66,   81,   83,   69,   70,   44,   72,
 /*   130 */    73,   53,   75,   36,   37,   38,   39,   53,   81,   51,
 /*   140 */    43,   53,    6,   86,    8,   69,   10,   18,   12,   73,
 /*   150 */    14,   75,    1,    2,   24,    4,   60,   81,   69,   70,
 /*   160 */    24,   65,   80,   34,   35,   69,   70,    1,   72,   73,
 /*   170 */    81,   75,   36,   37,   38,   39,   64,   81,   10,   43,
 /*   180 */    14,   13,   86,   53,   10,   17,   25,   26,   27,   28,
 /*   190 */    29,   30,   31,   32,    7,    8,   45,    3,   47,   48,
 /*   200 */    49,   50,   36,   52,   53,    6,   20,    8,   42,   10,
 /*   210 */    24,   64,    3,   14,    6,   21,    8,   11,   10,   53,
 /*   220 */    10,   79,   14,   24,   82,    6,   84,   17,   41,   10,
 /*   230 */    20,   22,   24,   14,    3,   36,   37,   38,   39,   53,
 /*   240 */    34,   35,   43,   24,   36,   37,   38,   39,    1,   11,
 /*   250 */    80,   43,    5,   83,    3,   36,   37,   38,   39,   16,
 /*   260 */    43,   18,   43,   25,   26,   27,   28,   29,   30,   31,
 /*   270 */    32,    1,   21,   69,   60,   79,   72,   73,   82,   75,
 /*   280 */    84,   10,   67,   69,   70,   81,   72,   73,   17,   75,
 /*   290 */    19,   12,   21,   22,   15,   81,   60,   12,   83,   14,
 /*   300 */    53,   65,   24,   89,   90,   69,   70,   37,   72,   73,
 /*   310 */    24,   75,    7,    8,   61,    3,   60,   81,   13,   57,
 /*   320 */    58,   65,   86,   53,   71,   69,   70,   59,   72,   73,
 /*   330 */    44,   75,    1,   21,    1,   60,   79,   81,   85,   53,
 /*   340 */    65,   84,   86,   69,   69,   70,   41,   72,   73,    3,
 /*   350 */    75,   55,   56,   57,   58,   81,   81,   60,   24,   69,
 /*   360 */    92,   86,   24,   73,   60,   75,   69,   70,   37,   72,
 /*   370 */    73,   81,   75,   69,   70,   78,   72,   73,   81,   75,
 /*   380 */    34,   35,    3,   60,   53,   81,   53,   61,    3,   63,
 /*   390 */    60,    3,   69,   70,   90,   72,   73,   71,   75,   69,
 /*   400 */    70,   78,   72,   73,   81,   75,   34,   35,   78,   21,
 /*   410 */    63,   81,   67,   61,   59,   61,   23,   60,   67,   62,
 /*   420 */    59,   74,   24,   71,   16,   71,   69,   70,   83,   72,
 /*   430 */    73,   60,   75,   24,   83,   11,    3,   24,   81,   11,
 /*   440 */    69,   70,   67,   72,   73,   79,   75,   92,    3,   78,
 /*   450 */    84,   60,   81,   92,   67,   24,   42,   11,   83,    3,
 /*   460 */    69,   70,   14,   72,   73,   60,   75,   17,   11,   20,
 /*   470 */    83,   71,   81,   92,   69,   70,   77,   72,   73,   83,
 /*   480 */    75,   14,   88,   82,   22,   21,   81,   60,   17,   87,
 /*   490 */    80,   74,   24,   10,   62,   84,   69,   70,   77,   72,
 /*   500 */    73,   60,   75,   85,   14,   93,   93,   93,   81,   93,
 /*   510 */    69,   70,   93,   72,   73,   93,   75,   93,   93,   93,
 /*   520 */    93,   60,   81,   93,   93,   93,   93,   93,   93,   93,
 /*   530 */    69,   70,   93,   72,   73,   60,   75,   93,   93,   93,
 /*   540 */    93,   93,   81,   93,   69,   70,   93,   72,   73,   93,
 /*   550 */    75,   93,   93,   93,   93,   93,   81,   60,   93,   93,
 /*   560 */    93,   93,   93,   93,   93,   93,   69,   70,   93,   72,
 /*   570 */    73,   60,   75,   93,   93,   93,   93,   93,   81,   93,
 /*   580 */    69,   70,   93,   72,   73,   93,   75,   93,   93,   93,
 /*   590 */    93,   60,   81,   93,   93,   93,   93,   93,   93,   93,
 /*   600 */    69,   70,   93,   72,   73,   60,   75,   93,   93,   93,
 /*   610 */    93,   93,   81,   93,   69,   70,   93,   72,   73,   93,
 /*   620 */    75,   93,   93,   93,   93,   93,   81,
);
    const YY_SHIFT_USE_DFLT = -20;
    const YY_SHIFT_MAX = 122;
    static public $yy_shift_ofst = array(
 /*     0 */   151,    5,   -6,   -6,   -6,   -6,  136,  136,   39,   48,
 /*    10 */    39,   39,   39,   39,   39,   39,   97,   59,   39,   39,
 /*    20 */    39,   39,   39,  199,  208,  219,  219,  219,  166,  271,
 /*    30 */    78,   24,   24,  -14,  285,  151,  -19,   16,   46,   84,
 /*    40 */   331,  286,  333,  130,  130,  130,  333,  130,  333,  130,
 /*    50 */   464,  464,  462,  467,  462,  238,  161,  305,  129,  187,
 /*    60 */   206,  346,  270,  247,   60,  186,   88,  388,  312,  194,
 /*    70 */   279,  372,  372,  251,   66,  462,  462,  468,  462,  471,
 /*    80 */   462,  490,  483,  174,  -20,  -20,  210,   71,  168,   83,
 /*    90 */    83,   83,   83,  243,   -4,  209,  231,  278,  174,   17,
 /*   100 */     2,  457,  431,  445,  428,  414,  446,  450,  449,  217,
 /*   110 */   456,  448,  413,  433,  385,  338,  334,  379,  393,  398,
 /*   120 */   424,  409,  408,
);
    const YY_REDUCE_USE_DFLT = -60;
    const YY_REDUCE_MAX = 85;
    static public $yy_reduce_ofst = array(
 /*     0 */   296,   57,  275,  256,   96,  236,  214,  304,  330,  357,
 /*    10 */   297,  371,  323,  497,  475,  461,  427,  511,  545,  405,
 /*    20 */   531,  441,  391,   43,  204,   76,  290,  -59,  -17,  326,
 /*    30 */   -41,  196,  142,  253,   89,  262,  170,  347,   42,  170,
 /*    40 */     9,  170,  268,  215,  375,  351,  355,  387,  361,  345,
 /*    50 */   352,  354,  366,  274,  257,  402,  402,  417,  394,  417,
 /*    60 */   394,  394,  381,  381,  381,  396,  381,  400,  400,  400,
 /*    70 */   401,  394,  394,  400,  399,  411,  411,  421,  411,  418,
 /*    80 */   411,  432,  410,   82,  147,  112,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 1 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 2 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 3 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 4 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 5 */ array(6, 8, 10, 12, 14, 24, 33, 36, 37, 38, 39, 43, ),
        /* 6 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 7 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 8 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 9 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 10 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 11 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 12 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 13 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 14 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 15 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 16 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 17 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 18 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 19 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 20 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 21 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 22 */ array(6, 8, 10, 12, 14, 24, 36, 37, 38, 39, 43, ),
        /* 23 */ array(6, 8, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 24 */ array(6, 8, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 25 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 26 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 27 */ array(6, 10, 14, 24, 36, 37, 38, 39, 43, ),
        /* 28 */ array(1, 14, 36, 42, 53, ),
        /* 29 */ array(10, 17, 19, 21, 22, ),
        /* 30 */ array(1, 14, 36, 42, 53, ),
        /* 31 */ array(12, 15, 22, ),
        /* 32 */ array(12, 15, 22, ),
        /* 33 */ array(17, 21, ),
        /* 34 */ array(12, 14, ),
        /* 35 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 36 */ array(20, 24, 40, 44, 53, ),
        /* 37 */ array(7, 8, 19, 41, ),
        /* 38 */ array(9, 20, 24, 53, ),
        /* 39 */ array(20, 24, 44, 53, ),
        /* 40 */ array(1, 37, 53, ),
        /* 41 */ array(24, 44, 53, ),
        /* 42 */ array(1, 53, ),
        /* 43 */ array(24, 53, ),
        /* 44 */ array(24, 53, ),
        /* 45 */ array(24, 53, ),
        /* 46 */ array(1, 53, ),
        /* 47 */ array(24, 53, ),
        /* 48 */ array(1, 53, ),
        /* 49 */ array(24, 53, ),
        /* 50 */ array(21, ),
        /* 51 */ array(21, ),
        /* 52 */ array(22, ),
        /* 53 */ array(14, ),
        /* 54 */ array(22, ),
        /* 55 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 56 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 57 */ array(7, 8, 13, 41, ),
        /* 58 */ array(18, 34, 35, ),
        /* 59 */ array(7, 8, 41, ),
        /* 60 */ array(11, 34, 35, ),
        /* 61 */ array(3, 34, 35, ),
        /* 62 */ array(1, 37, 53, ),
        /* 63 */ array(1, 5, 53, ),
        /* 64 */ array(1, 46, 53, ),
        /* 65 */ array(20, 24, 53, ),
        /* 66 */ array(1, 51, 53, ),
        /* 67 */ array(3, 21, ),
        /* 68 */ array(3, 21, ),
        /* 69 */ array(3, 21, ),
        /* 70 */ array(12, 15, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(34, 35, ),
        /* 73 */ array(3, 21, ),
        /* 74 */ array(14, 24, ),
        /* 75 */ array(22, ),
        /* 76 */ array(22, ),
        /* 77 */ array(24, ),
        /* 78 */ array(22, ),
        /* 79 */ array(17, ),
        /* 80 */ array(22, ),
        /* 81 */ array(14, ),
        /* 82 */ array(10, ),
        /* 83 */ array(10, ),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(10, 17, 20, ),
        /* 87 */ array(10, 17, 23, ),
        /* 88 */ array(10, 13, 17, ),
        /* 89 */ array(10, 17, ),
        /* 90 */ array(10, 17, ),
        /* 91 */ array(10, 17, ),
        /* 92 */ array(10, 17, ),
        /* 93 */ array(16, 18, ),
        /* 94 */ array(13, 16, ),
        /* 95 */ array(3, 22, ),
        /* 96 */ array(3, ),
        /* 97 */ array(24, ),
        /* 98 */ array(10, ),
        /* 99 */ array(3, ),
        /* 100 */ array(24, ),
        /* 101 */ array(11, ),
        /* 102 */ array(24, ),
        /* 103 */ array(3, ),
        /* 104 */ array(11, ),
        /* 105 */ array(42, ),
        /* 106 */ array(11, ),
        /* 107 */ array(17, ),
        /* 108 */ array(20, ),
        /* 109 */ array(43, ),
        /* 110 */ array(3, ),
        /* 111 */ array(14, ),
        /* 112 */ array(24, ),
        /* 113 */ array(3, ),
        /* 114 */ array(3, ),
        /* 115 */ array(24, ),
        /* 116 */ array(24, ),
        /* 117 */ array(3, ),
        /* 118 */ array(23, ),
        /* 119 */ array(24, ),
        /* 120 */ array(11, ),
        /* 121 */ array(24, ),
        /* 122 */ array(16, ),
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
 /*     0 */   339,  339,  339,  339,  339,  339,  324,  339,  300,  339,
 /*    10 */   300,  300,  300,  339,  339,  339,  339,  339,  339,  339,
 /*    20 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  244,
 /*    30 */   339,  278,  272,  244,  339,  217,  282,  251,  339,  282,
 /*    40 */   339,  282,  339,  339,  339,  339,  339,  339,  339,  339,
 /*    50 */   244,  244,  267,  339,  268,  308,  308,  339,  339,  284,
 /*    60 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  339,
 /*    70 */   294,  306,  310,  339,  339,  270,  273,  339,  291,  252,
 /*    80 */   269,  339,  282,  282,  303,  303,  339,  327,  339,  245,
 /*    90 */   283,  250,  339,  339,  339,  339,  339,  339,  271,  339,
 /*   100 */   339,  339,  339,  339,  339,  339,  339,  339,  339,  339,
 /*   110 */   339,  339,  339,  339,  339,  339,  339,  339,  325,  339,
 /*   120 */   339,  339,  299,  239,  247,  317,  238,  318,  313,  234,
 /*   130 */   218,  232,  316,  312,  314,  235,  249,  305,  315,  236,
 /*   140 */   307,  320,  237,  240,  319,  309,  248,  241,  233,  311,
 /*   150 */   279,  261,  262,  263,  260,  255,  281,  254,  264,  265,
 /*   160 */   275,  276,  274,  253,  256,  302,  293,  242,  337,  338,
 /*   170 */   336,  335,  221,  219,  220,  222,  223,  228,  229,  227,
 /*   180 */   226,  224,  225,  277,  280,  331,  332,  333,  329,  287,
 /*   190 */   289,  290,  334,  266,  243,  230,  246,  295,  330,  297,
 /*   200 */   288,  292,  322,  304,  326,  323,  328,  321,  257,  258,
 /*   210 */   286,  285,  298,  296,  259,  301,  231,
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
    const YYNOCODE = 94;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 217;
    const YYNRULE = 122;
    const YYERRORSYMBOL = 54;
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
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'NULL',        
  'IN',            'ANDSYM',        'BACKTICK',      'HATCH',       
  'AT',            'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHP',           'PHPSTART',      'PHPEND',      
  'XML',           'LDEL',          'error',         'start',       
  'template',      'template_element',  'smartytag',     'text',        
  'expr',          'attributes',    'statement',     'modifier',    
  'modparameters',  'ifexprs',       'statements',    'varvar',      
  'foraction',     'variable',      'array',         'attribute',   
  'exprs',         'value',         'math',          'function',    
  'doublequoted',  'method',        'params',        'objectchain', 
  'vararraydefs',  'object',        'vararraydef',   'varvarele',   
  'objectelement',  'modparameter',  'ifexpr',        'ifcond',      
  'lop',           'arrayelements',  'arrayelement',  'doublequotedcontent',
  'textelement', 
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
 /*  28 */ "attribute ::= SPACE ID EQUAL ID",
 /*  29 */ "attribute ::= SPACE ID EQUAL expr",
 /*  30 */ "statements ::= statement",
 /*  31 */ "statements ::= statements COMMA statement",
 /*  32 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  33 */ "statement ::= DOLLAR varvar EQUAL ID",
 /*  34 */ "expr ::= exprs",
 /*  35 */ "expr ::= exprs modifier modparameters",
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
 /*  50 */ "value ::= ID COLON COLON method",
 /*  51 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP",
 /*  52 */ "value ::= ID COLON COLON method objectchain",
 /*  53 */ "value ::= ID COLON COLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  54 */ "value ::= ID COLON COLON ID",
 /*  55 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs",
 /*  56 */ "value ::= ID COLON COLON DOLLAR ID vararraydefs objectchain",
 /*  57 */ "value ::= HATCH ID HATCH",
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
 /*  87 */ "modparameter ::= COLON expr",
 /*  88 */ "ifexprs ::= ifexpr",
 /*  89 */ "ifexprs ::= NOT ifexprs",
 /*  90 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  91 */ "ifexpr ::= expr",
 /*  92 */ "ifexpr ::= expr ifcond expr",
 /*  93 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  94 */ "ifcond ::= EQUALS",
 /*  95 */ "ifcond ::= NOTEQUALS",
 /*  96 */ "ifcond ::= GREATERTHAN",
 /*  97 */ "ifcond ::= LESSTHAN",
 /*  98 */ "ifcond ::= GREATEREQUAL",
 /*  99 */ "ifcond ::= LESSEQUAL",
 /* 100 */ "ifcond ::= IDENTITY",
 /* 101 */ "ifcond ::= NONEIDENTITY",
 /* 102 */ "lop ::= LAND",
 /* 103 */ "lop ::= LOR",
 /* 104 */ "array ::= OPENB arrayelements CLOSEB",
 /* 105 */ "arrayelements ::= arrayelement",
 /* 106 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 107 */ "arrayelements ::=",
 /* 108 */ "arrayelement ::= expr",
 /* 109 */ "arrayelement ::= expr APTR expr",
 /* 110 */ "arrayelement ::= ID",
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
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 6 ),
  array( 'lhs' => 58, 'rhs' => 6 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 11 ),
  array( 'lhs' => 58, 'rhs' => 8 ),
  array( 'lhs' => 58, 'rhs' => 8 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 0 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 4 ),
  array( 'lhs' => 62, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 8 ),
  array( 'lhs' => 73, 'rhs' => 5 ),
  array( 'lhs' => 73, 'rhs' => 9 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 6 ),
  array( 'lhs' => 73, 'rhs' => 7 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 0 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 77, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 0 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 0 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
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
        58 => 0,
        59 => 0,
        63 => 0,
        105 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        88 => 1,
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
        82 => 24,
        108 => 24,
        25 => 25,
        27 => 27,
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
        90 => 73,
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
        89 => 89,
        91 => 91,
        92 => 92,
        93 => 92,
        94 => 94,
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
        106 => 106,
        107 => 107,
        109 => 109,
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
#line 1600 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1603 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1606 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1612 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1615 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1618 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1621 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1624 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1630 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1636 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1639 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1642 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1645 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1648 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1651 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1654 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
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
#line 1669 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1672 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1675 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1678 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1681 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1684 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1687 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1690 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1693 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1696 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>'\''.$this->yystack[$this->yyidx + 0]->minor.'\'');    }
#line 1699 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1702 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1705 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1708 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1711 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>'\''.$this->yystack[$this->yyidx + 0]->minor.'\'');    }
#line 1714 "internal.templateparser.php"
#line 181 "internal.templateparser.y"
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
#line 1728 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1731 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1734 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1737 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1740 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "''";     }
#line 1743 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1746 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r51(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1749 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1752 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1755 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1758 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1761 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1764 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1767 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1770 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1774 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1777 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1780 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1783 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1786 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1789 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1792 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1795 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1798 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1801 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1804 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1807 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1810 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1813 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1816 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1825 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1828 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1831 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1834 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1837 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1840 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1843 "internal.templateparser.php"
#line 355 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1846 "internal.templateparser.php"
#line 360 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1849 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1852 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = '==';    }
#line 1855 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!=';    }
#line 1858 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '>';    }
#line 1861 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '<';    }
#line 1864 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '>=';    }
#line 1867 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '<=';    }
#line 1870 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '===';    }
#line 1873 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '!==';    }
#line 1876 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '&&';    }
#line 1879 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '||';    }
#line 1882 "internal.templateparser.php"
#line 376 "internal.templateparser.y"
    function yy_r104(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1885 "internal.templateparser.php"
#line 378 "internal.templateparser.y"
    function yy_r106(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1888 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r107(){ return;     }
#line 1891 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r109(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1894 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1897 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1900 "internal.templateparser.php"
#line 387 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1903 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1906 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1909 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1912 "internal.templateparser.php"

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
#line 2029 "internal.templateparser.php"
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
#line 2054 "internal.templateparser.php"
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
