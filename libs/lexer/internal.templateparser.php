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
    const TP_XML                            =  5;
    const TP_PHP                            =  6;
    const TP_SHORTTAGSTART                  =  7;
    const TP_SHORTTAGEND                    =  8;
    const TP_COMMENTEND                     =  9;
    const TP_COMMENTSTART                   = 10;
    const TP_NUMBER                         = 11;
    const TP_MATH                           = 12;
    const TP_UNIMATH                        = 13;
    const TP_INCDEC                         = 14;
    const TP_OPENP                          = 15;
    const TP_CLOSEP                         = 16;
    const TP_OPENB                          = 17;
    const TP_CLOSEB                         = 18;
    const TP_DOLLAR                         = 19;
    const TP_DOT                            = 20;
    const TP_COMMA                          = 21;
    const TP_COLON                          = 22;
    const TP_DOUBLECOLON                    = 23;
    const TP_SEMICOLON                      = 24;
    const TP_VERT                           = 25;
    const TP_EQUAL                          = 26;
    const TP_SPACE                          = 27;
    const TP_PTR                            = 28;
    const TP_APTR                           = 29;
    const TP_ID                             = 30;
    const TP_EQUALS                         = 31;
    const TP_NOTEQUALS                      = 32;
    const TP_GREATERTHAN                    = 33;
    const TP_LESSTHAN                       = 34;
    const TP_GREATEREQUAL                   = 35;
    const TP_LESSEQUAL                      = 36;
    const TP_IDENTITY                       = 37;
    const TP_NONEIDENTITY                   = 38;
    const TP_NOT                            = 39;
    const TP_LAND                           = 40;
    const TP_LOR                            = 41;
    const TP_QUOTE                          = 42;
    const TP_SINGLEQUOTE                    = 43;
    const TP_BOOLEAN                        = 44;
    const TP_NULL                           = 45;
    const TP_IN                             = 46;
    const TP_ANDSYM                         = 47;
    const TP_BACKTICK                       = 48;
    const TP_HATCH                          = 49;
    const TP_AT                             = 50;
    const TP_ISODD                          = 51;
    const TP_ISNOTODD                       = 52;
    const TP_ISEVEN                         = 53;
    const TP_ISNOTEVEN                      = 54;
    const TP_ISODDBY                        = 55;
    const TP_ISNOTODDBY                     = 56;
    const TP_ISEVENBY                       = 57;
    const TP_ISNOTEVENBY                    = 58;
    const TP_ISDIVBY                        = 59;
    const TP_ISNOTDIVBY                     = 60;
    const TP_LITERALSTART                   = 61;
    const TP_LITERALEND                     = 62;
    const TP_LDELIMTAG                      = 63;
    const TP_RDELIMTAG                      = 64;
    const TP_PHPSTART                       = 65;
    const TP_PHPEND                         = 66;
    const TP_BLOCKSTART                     = 67;
    const TP_BLOCKEND                       = 68;
    const YY_NO_ACTION = 377;
    const YY_ACCEPT_ACTION = 376;
    const YY_ERROR_ACTION = 375;

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
    const YY_SZ_ACTTAB = 809;
static public $yy_action = array(
 /*     0 */   159,  149,  153,  189,   19,  190,   19,  376,   42,  157,
 /*    10 */   195,  193,  162,  161,  165,  169,    7,    5,    3,    8,
 /*    20 */     2,    6,  152,   19,  149,  153,  176,   26,   34,   26,
 /*    30 */    21,  223,   12,  223,   58,  162,  161,  165,  169,    7,
 /*    40 */     5,    3,    8,    2,    6,  118,   87,   31,  112,  147,
 /*    50 */   223,  142,  206,  142,  220,   14,  209,   35,   48,  183,
 /*    60 */   180,  146,  146,   85,  136,  149,  153,  219,  216,  217,
 /*    70 */   218,  241,  163,  155,  156,  185,  162,  161,  165,  169,
 /*    80 */     7,    5,    3,    8,    2,    6,   28,  205,  182,   30,
 /*    90 */   149,  153,  176,  232,   34,   13,   10,   99,   12,  146,
 /*   100 */    58,  162,  161,  165,  169,    7,    5,    3,    8,    2,
 /*   110 */     6,  118,  210,  130,   15,  146,  211,  204,   61,   23,
 /*   120 */     4,   50,   33,   35,   48,  183,  180,   97,   14,  176,
 /*   130 */   136,   34,  238,   10,  178,   12,   85,   56,  146,  112,
 /*   140 */     1,  139,   96,  188,   44,  198,   55,  238,  115,   71,
 /*   150 */    36,  205,  182,  199,  231,  172,   81,    4,  177,  215,
 /*   160 */    35,   48,  183,  180,  172,  235,   19,  136,  189,  151,
 /*   170 */   190,   99,   53,  145,  197,  203,   52,  150,  134,  176,
 /*   180 */    41,   34,  140,   21,  144,   12,   33,   57,  176,   24,
 /*   190 */    34,  181,   21,  223,   12,   14,   58,   14,   37,  110,
 /*   200 */   234,  105,   25,   85,  198,   85,  238,  113,   20,   22,
 /*   210 */    35,   48,  183,  180,  189,   19,  190,  136,   58,   35,
 /*   220 */    48,  183,  180,  206,  188,   44,  136,  160,  212,  196,
 /*   230 */    65,  122,  146,  188,  199,  231,  176,   81,   34,  177,
 /*   240 */    21,  170,  223,  199,   58,  172,   76,   59,  177,  146,
 /*   250 */   151,  188,  176,   29,  172,  114,   21,  234,   12,   25,
 /*   260 */    58,  199,  142,  189,   84,  190,  177,   35,   48,  183,
 /*   270 */   180,  121,  172,   19,  136,   58,  189,   28,  190,   14,
 /*   280 */    30,  132,  233,   35,   48,  183,  180,   85,  106,  178,
 /*   290 */   136,   11,  167,  148,   45,  176,   26,   34,  171,   21,
 /*   300 */   223,  166,  188,   58,   59,  175,   46,  224,   78,  213,
 /*   310 */   172,  176,  186,   34,  117,   21,  138,  177,  174,   58,
 /*   320 */   173,  213,   91,  172,  111,  222,   35,   48,  183,  180,
 /*   330 */   119,  176,  209,  136,  237,   21,   49,  229,   98,   58,
 /*   340 */    16,  191,   35,   48,  183,  180,  188,   44,  140,  136,
 /*   350 */   121,  213,   68,  207,  221,  146,  199,  231,  129,   81,
 /*   360 */   188,  177,   35,   48,  183,  180,  189,  172,  190,  136,
 /*   370 */   128,  123,  151,  188,   44,  177,  140,  140,  201,   67,
 /*   380 */    79,  172,   38,  199,  231,  224,   81,  188,  177,  112,
 /*   390 */   188,   44,   82,  189,  172,  190,   69,  184,  158,  151,
 /*   400 */   199,  231,  177,   81,   93,  177,   83,   80,  172,  188,
 /*   410 */    44,  172,   27,  191,  209,   70,  151,  131,   47,  199,
 /*   420 */   231,  213,   81,  137,  177,  191,  230,  181,  188,   44,
 /*   430 */   172,  202,  188,  213,   64,  151,  194,  195,  199,  231,
 /*   440 */   191,   81,  199,  177,  172,   75,  100,  177,   77,  172,
 /*   450 */   146,  238,  127,  172,  151,   17,  219,  216,  217,  218,
 /*   460 */   241,  163,  155,  156,  188,   90,   29,  146,   88,  140,
 /*   470 */   239,  140,  154,  172,  199,  231,  126,   81,  209,  177,
 /*   480 */   236,  191,   60,  135,  179,  172,  188,   44,   20,  133,
 /*   490 */    63,  214,   72,  120,  225,  227,  199,  231,  168,   81,
 /*   500 */    54,  177,   51,  116,  188,   44,  187,  172,  188,  164,
 /*   510 */    73,   62,  151,    9,  199,  231,   32,   81,  200,  177,
 /*   520 */   224,  208,  192,  177,  140,  172,  188,   43,   58,  172,
 /*   530 */   151,   18,   66,  188,   92,  198,  199,  231,   39,   81,
 /*   540 */   101,  177,   95,  199,  231,  243,   81,  172,  177,  243,
 /*   550 */   188,   44,  151,  240,  172,  243,   74,  243,  243,  243,
 /*   560 */   199,  231,  243,   81,  243,  177,  243,  243,  188,   90,
 /*   570 */   243,  172,  243,  243,  243,  243,  151,  243,  199,  231,
 /*   580 */   243,   81,  243,  177,  243,  243,  188,   86,  243,  172,
 /*   590 */   243,  243,  243,  243,  243,  243,  199,  231,  228,   81,
 /*   600 */   243,  177,  188,   86,  226,  243,  243,  172,  243,  243,
 /*   610 */   243,  243,  199,  231,  243,   81,  243,  177,  243,  243,
 /*   620 */   141,  243,  243,  172,  243,  243,  188,   40,  243,  124,
 /*   630 */   243,  243,  243,  243,  243,  243,  199,  231,  243,   81,
 /*   640 */   243,  177,  243,  243,  188,   86,  243,  172,  243,  243,
 /*   650 */   243,  243,  243,  243,  199,  231,  243,   81,  243,  177,
 /*   660 */   243,  243,  143,  188,   86,  172,  243,  243,  243,  243,
 /*   670 */   243,  243,  243,  199,  231,  243,   81,  243,  177,  188,
 /*   680 */   109,  125,  243,  243,  172,  243,  243,  243,  243,  199,
 /*   690 */   231,  243,   81,  243,  177,  243,  188,  102,  243,  243,
 /*   700 */   172,  243,  243,  188,  107,  243,  199,  231,  243,   81,
 /*   710 */   243,  177,  243,  199,  231,  243,   81,  172,  177,  243,
 /*   720 */   188,  108,  243,  243,  172,  243,  243,  243,  243,  243,
 /*   730 */   199,  231,  243,   81,  243,  177,  243,  243,  188,   94,
 /*   740 */   243,  172,  243,  243,  243,  243,  243,  243,  199,  231,
 /*   750 */   243,   81,  243,  177,  188,  103,  243,  243,  243,  172,
 /*   760 */   243,  243,  243,  243,  199,  231,  243,   81,  243,  177,
 /*   770 */   188,  104,  243,  243,  243,  172,  243,  243,  243,  243,
 /*   780 */   199,  231,  243,   81,  243,  177,  243,  188,   89,  243,
 /*   790 */   243,  172,  243,  243,  243,  243,  243,  199,  231,  243,
 /*   800 */    81,  243,  177,  243,  243,  243,  243,  243,  172,
    );
    static public $yy_lookahead = array(
 /*     0 */    16,   40,   41,    1,    3,    3,    3,   70,   71,   72,
 /*    10 */    73,    9,   51,   52,   53,   54,   55,   56,   57,   58,
 /*    20 */    59,   60,   78,    3,   40,   41,   11,   26,   13,   26,
 /*    30 */    15,   30,   17,   30,   19,   51,   52,   53,   54,   55,
 /*    40 */    56,   57,   58,   59,   60,   30,   77,   46,   79,   24,
 /*    50 */    30,   50,   16,   50,    4,   15,   87,   42,   43,   44,
 /*    60 */    45,   25,   25,   23,   49,   40,   41,   31,   32,   33,
 /*    70 */    34,   35,   36,   37,   38,   99,   51,   52,   53,   54,
 /*    80 */    55,   56,   57,   58,   59,   60,   17,   12,   13,   20,
 /*    90 */    40,   41,   11,   18,   13,   21,   15,   28,   17,   25,
 /*   100 */    19,   51,   52,   53,   54,   55,   56,   57,   58,   59,
 /*   110 */    60,   30,    1,    2,    3,   25,    5,    6,    7,   29,
 /*   120 */    39,   10,   47,   42,   43,   44,   45,   94,   15,   11,
 /*   130 */    49,   13,   99,   15,   75,   17,   23,   19,   25,   79,
 /*   140 */    27,   28,   94,   75,   76,   97,   19,   99,   30,   81,
 /*   150 */    91,   12,   13,   85,   86,   96,   88,   39,   90,    4,
 /*   160 */    42,   43,   44,   45,   96,  106,    3,   49,    1,  101,
 /*   170 */     3,   28,   61,   19,   63,   64,   65,   14,   67,   11,
 /*   180 */    80,   13,   27,   15,   30,   17,   47,   19,   11,   26,
 /*   190 */    13,  100,   15,   30,   17,   15,   19,   15,   30,   80,
 /*   200 */     1,   94,    3,   23,   97,   23,   99,   30,   26,   29,
 /*   210 */    42,   43,   44,   45,    1,    3,    3,   49,   19,   42,
 /*   220 */    43,   44,   45,   16,   75,   76,   49,   78,    4,   62,
 /*   230 */    81,   82,   25,   75,   85,   86,   11,   88,   13,   90,
 /*   240 */    15,   42,   30,   85,   19,   96,   88,   48,   90,   25,
 /*   250 */   101,   75,   11,   22,   96,   30,   15,    1,   17,    3,
 /*   260 */    19,   85,   50,    1,   88,    3,   90,   42,   43,   44,
 /*   270 */    45,   30,   96,    3,   49,   19,    1,   17,    3,   15,
 /*   280 */    20,   68,   18,   42,   43,   44,   45,   23,   21,   75,
 /*   290 */    49,   24,    4,   84,   83,   11,   26,   13,   42,   15,
 /*   300 */    30,    4,   75,   19,   48,   43,   83,   98,   74,   98,
 /*   310 */    96,   11,   85,   13,   30,   15,   28,   90,   43,   19,
 /*   320 */   106,   98,   77,   96,   79,    4,   42,   43,   44,   45,
 /*   330 */    30,   11,   87,   49,    4,   15,   83,   18,   30,   19,
 /*   340 */    21,  107,   42,   43,   44,   45,   75,   76,   27,   49,
 /*   350 */    30,   98,   81,    4,    4,   25,   85,   86,   30,   88,
 /*   360 */    75,   90,   42,   43,   44,   45,    1,   96,    3,   49,
 /*   370 */    85,   86,  101,   75,   76,   90,   27,   27,    8,   81,
 /*   380 */    74,   96,   95,   85,   86,   98,   88,   75,   90,   79,
 /*   390 */    75,   76,   74,    1,   96,    3,   81,   85,    4,  101,
 /*   400 */    85,   86,   90,   88,   77,   90,   83,   74,   96,   75,
 /*   410 */    76,   96,  102,  107,   87,   81,  101,   30,   83,   85,
 /*   420 */    86,   98,   88,   75,   90,  107,   30,  100,   75,   76,
 /*   430 */    96,   66,   75,   98,   81,  101,   72,   73,   85,   86,
 /*   440 */   107,   88,   85,   90,   96,   88,   94,   90,   74,   96,
 /*   450 */    25,   99,   75,   96,  101,   15,   31,   32,   33,   34,
 /*   460 */    35,   36,   37,   38,   75,   76,   22,   25,   77,   27,
 /*   470 */    16,   27,    4,   96,   85,   86,   30,   88,   87,   90,
 /*   480 */    30,  107,   30,   30,   48,   96,   75,   76,   26,   30,
 /*   490 */    16,    4,   81,  104,  105,   16,   85,   86,    4,   88,
 /*   500 */    19,   90,    4,   30,   75,   76,   49,   96,   75,    4,
 /*   510 */    81,   92,  101,  103,   85,   86,   89,   88,   85,   90,
 /*   520 */    98,   87,  107,   90,   27,   96,   75,   76,   19,   96,
 /*   530 */   101,   15,   81,   75,   76,   97,   85,   86,   95,   88,
 /*   540 */    30,   90,   95,   85,   86,  108,   88,   96,   90,  108,
 /*   550 */    75,   76,  101,   92,   96,  108,   81,  108,  108,  108,
 /*   560 */    85,   86,  108,   88,  108,   90,  108,  108,   75,   76,
 /*   570 */   108,   96,  108,  108,  108,  108,  101,  108,   85,   86,
 /*   580 */   108,   88,  108,   90,  108,  108,   75,   76,  108,   96,
 /*   590 */   108,  108,  108,  108,  108,  108,   85,   86,  105,   88,
 /*   600 */   108,   90,   75,   76,   93,  108,  108,   96,  108,  108,
 /*   610 */   108,  108,   85,   86,  108,   88,  108,   90,  108,  108,
 /*   620 */    93,  108,  108,   96,  108,  108,   75,   76,  108,   78,
 /*   630 */   108,  108,  108,  108,  108,  108,   85,   86,  108,   88,
 /*   640 */   108,   90,  108,  108,   75,   76,  108,   96,  108,  108,
 /*   650 */   108,  108,  108,  108,   85,   86,  108,   88,  108,   90,
 /*   660 */   108,  108,   93,   75,   76,   96,  108,  108,  108,  108,
 /*   670 */   108,  108,  108,   85,   86,  108,   88,  108,   90,   75,
 /*   680 */    76,   93,  108,  108,   96,  108,  108,  108,  108,   85,
 /*   690 */    86,  108,   88,  108,   90,  108,   75,   76,  108,  108,
 /*   700 */    96,  108,  108,   75,   76,  108,   85,   86,  108,   88,
 /*   710 */   108,   90,  108,   85,   86,  108,   88,   96,   90,  108,
 /*   720 */    75,   76,  108,  108,   96,  108,  108,  108,  108,  108,
 /*   730 */    85,   86,  108,   88,  108,   90,  108,  108,   75,   76,
 /*   740 */   108,   96,  108,  108,  108,  108,  108,  108,   85,   86,
 /*   750 */   108,   88,  108,   90,   75,   76,  108,  108,  108,   96,
 /*   760 */   108,  108,  108,  108,   85,   86,  108,   88,  108,   90,
 /*   770 */    75,   76,  108,  108,  108,   96,  108,  108,  108,  108,
 /*   780 */    85,   86,  108,   88,  108,   90,  108,   75,   76,  108,
 /*   790 */   108,   96,  108,  108,  108,  108,  108,   85,   86,  108,
 /*   800 */    88,  108,   90,  108,  108,  108,  108,  108,   96,
);
    const YY_SHIFT_USE_DFLT = -40;
    const YY_SHIFT_MAX = 148;
    static public $yy_shift_ofst = array(
 /*     0 */   111,  118,   81,   81,   81,   81,   81,   81,   81,   81,
 /*    10 */    81,   81,  177,   15,   15,  168,  177,   15,   15,   15,
 /*    20 */    15,   15,   15,   15,   15,   15,   15,   15,  225,  284,
 /*    30 */   300,  241,  320,  320,  320,  199,  256,  113,   69,   69,
 /*    40 */   442,  444,  111,   36,  425,    1,    3,  163,  275,  212,
 /*    50 */   392,  392,  392,  392,   20,   20,   20,   20,   20,  509,
 /*    60 */   497,  509,  143,  143,   25,   50,  -16,  -39,  -39,  -39,
 /*    70 */   -39,  -39,  -39,  -39,  -39,   75,  139,    2,  213,  262,
 /*    80 */   167,  139,  365,  270,  139,  154,   74,  349,  350,  330,
 /*    90 */    90,  155,  207,  321,  224,  260,  143,  143,  516,  510,
 /*   100 */   143,  440,   37,   37,   37,  143,  127,   37,   37,   37,
 /*   110 */   231,  -40,  -40,  180,  264,  182,  288,   40,   40,   40,
 /*   120 */   319,   40,  267,  297,  487,  474,  462,  436,  494,  457,
 /*   130 */   473,  498,  459,  468,  387,  394,  328,  370,  453,  452,
 /*   140 */   446,  479,  450,  454,  440,  308,  396,  481,  505,
);
    const YY_REDUCE_USE_DFLT = -64;
    const YY_REDUCE_MAX = 112;
    static public $yy_reduce_ofst = array(
 /*     0 */   -63,  149,  315,  298,  271,   68,  334,  475,  411,  429,
 /*    10 */   451,  353,  389,  511,  527,  551,  493,  569,  588,  663,
 /*    20 */   679,  458,  645,  628,  604,  712,  621,  695,  357,  176,
 /*    30 */   158,  285,  227,  312,  433,   59,  214,  245,  107,   48,
 /*    40 */   -31,  327,  364,  310,  310,  287,  287,  209,  306,  287,
 /*    50 */   374,  234,  318,  333,  335,  323,  211,  223,  253,  377,
 /*    60 */   391,  348,  352,   33,  410,  410,  410,  410,  410,  410,
 /*    70 */   410,  410,  410,  410,  410,  427,  427,  415,  415,  415,
 /*    80 */   415,  427,  415,  422,  427,  419,   60,  434,  434,   60,
 /*    90 */    60,  434,   60,  434,   60,  438,  -24,  -24,  443,  461,
 /*   100 */   -24,  447,   60,   60,   60,  -24,  -56,   60,   60,   60,
 /*   110 */    91,  100,  119,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 7, 10, 61, 63, 64, 65, 67, ),
        /* 1 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 2 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 3 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 4 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 5 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 6 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 7 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 8 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 9 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 10 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 11 */ array(11, 13, 15, 17, 19, 30, 39, 42, 43, 44, 45, 49, ),
        /* 12 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 13 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 14 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 15 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 16 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 17 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 18 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 19 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 20 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 21 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 22 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 23 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 24 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 25 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 26 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 27 */ array(11, 13, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 28 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 29 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 30 */ array(11, 13, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 31 */ array(11, 15, 17, 19, 30, 42, 43, 44, 45, 49, ),
        /* 32 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 33 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 34 */ array(11, 15, 19, 30, 42, 43, 44, 45, 49, ),
        /* 35 */ array(1, 3, 19, 42, 48, ),
        /* 36 */ array(1, 3, 19, 42, 48, ),
        /* 37 */ array(15, 23, 25, 27, 28, ),
        /* 38 */ array(17, 20, 28, ),
        /* 39 */ array(17, 20, 28, ),
        /* 40 */ array(25, 27, ),
        /* 41 */ array(22, 27, ),
        /* 42 */ array(1, 2, 3, 5, 6, 7, 10, 61, 63, 64, 65, 67, ),
        /* 43 */ array(16, 25, 31, 32, 33, 34, 35, 36, 37, 38, ),
        /* 44 */ array(25, 31, 32, 33, 34, 35, 36, 37, 38, ),
        /* 45 */ array(3, 26, 30, 46, 50, ),
        /* 46 */ array(3, 26, 30, 50, ),
        /* 47 */ array(3, 14, 26, 30, ),
        /* 48 */ array(1, 3, 43, ),
        /* 49 */ array(3, 30, 50, ),
        /* 50 */ array(1, 3, ),
        /* 51 */ array(1, 3, ),
        /* 52 */ array(1, 3, ),
        /* 53 */ array(1, 3, ),
        /* 54 */ array(3, 30, ),
        /* 55 */ array(3, 30, ),
        /* 56 */ array(3, 30, ),
        /* 57 */ array(3, 30, ),
        /* 58 */ array(3, 30, ),
        /* 59 */ array(19, ),
        /* 60 */ array(27, ),
        /* 61 */ array(19, ),
        /* 62 */ array(28, ),
        /* 63 */ array(28, ),
        /* 64 */ array(24, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 65 */ array(4, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 66 */ array(16, 40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 67 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 68 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 69 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 70 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 71 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 72 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 73 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 74 */ array(40, 41, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, ),
        /* 75 */ array(12, 13, 18, 47, ),
        /* 76 */ array(12, 13, 47, ),
        /* 77 */ array(1, 3, 9, ),
        /* 78 */ array(1, 3, 68, ),
        /* 79 */ array(1, 3, 43, ),
        /* 80 */ array(1, 3, 62, ),
        /* 81 */ array(12, 13, 47, ),
        /* 82 */ array(1, 3, 66, ),
        /* 83 */ array(3, 26, 30, ),
        /* 84 */ array(12, 13, 47, ),
        /* 85 */ array(19, 30, ),
        /* 86 */ array(21, 25, ),
        /* 87 */ array(4, 27, ),
        /* 88 */ array(4, 27, ),
        /* 89 */ array(4, 25, ),
        /* 90 */ array(25, 29, ),
        /* 91 */ array(4, 27, ),
        /* 92 */ array(16, 25, ),
        /* 93 */ array(4, 27, ),
        /* 94 */ array(4, 25, ),
        /* 95 */ array(17, 20, ),
        /* 96 */ array(28, ),
        /* 97 */ array(28, ),
        /* 98 */ array(15, ),
        /* 99 */ array(30, ),
        /* 100 */ array(28, ),
        /* 101 */ array(15, ),
        /* 102 */ array(25, ),
        /* 103 */ array(25, ),
        /* 104 */ array(25, ),
        /* 105 */ array(28, ),
        /* 106 */ array(19, ),
        /* 107 */ array(25, ),
        /* 108 */ array(25, ),
        /* 109 */ array(25, ),
        /* 110 */ array(22, ),
        /* 111 */ array(),
        /* 112 */ array(),
        /* 113 */ array(15, 23, 29, ),
        /* 114 */ array(15, 18, 23, ),
        /* 115 */ array(15, 23, 26, ),
        /* 116 */ array(4, 28, ),
        /* 117 */ array(15, 23, ),
        /* 118 */ array(15, 23, ),
        /* 119 */ array(15, 23, ),
        /* 120 */ array(18, 21, ),
        /* 121 */ array(15, 23, ),
        /* 122 */ array(21, 24, ),
        /* 123 */ array(4, ),
        /* 124 */ array(4, ),
        /* 125 */ array(16, ),
        /* 126 */ array(26, ),
        /* 127 */ array(48, ),
        /* 128 */ array(4, ),
        /* 129 */ array(49, ),
        /* 130 */ array(30, ),
        /* 131 */ array(4, ),
        /* 132 */ array(30, ),
        /* 133 */ array(4, ),
        /* 134 */ array(30, ),
        /* 135 */ array(4, ),
        /* 136 */ array(30, ),
        /* 137 */ array(8, ),
        /* 138 */ array(30, ),
        /* 139 */ array(30, ),
        /* 140 */ array(30, ),
        /* 141 */ array(16, ),
        /* 142 */ array(30, ),
        /* 143 */ array(16, ),
        /* 144 */ array(15, ),
        /* 145 */ array(30, ),
        /* 146 */ array(30, ),
        /* 147 */ array(19, ),
        /* 148 */ array(4, ),
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
        /* 217 */ array(),
        /* 218 */ array(),
        /* 219 */ array(),
        /* 220 */ array(),
        /* 221 */ array(),
        /* 222 */ array(),
        /* 223 */ array(),
        /* 224 */ array(),
        /* 225 */ array(),
        /* 226 */ array(),
        /* 227 */ array(),
        /* 228 */ array(),
        /* 229 */ array(),
        /* 230 */ array(),
        /* 231 */ array(),
        /* 232 */ array(),
        /* 233 */ array(),
        /* 234 */ array(),
        /* 235 */ array(),
        /* 236 */ array(),
        /* 237 */ array(),
        /* 238 */ array(),
        /* 239 */ array(),
        /* 240 */ array(),
        /* 241 */ array(),
);
    static public $yy_default = array(
 /*     0 */   375,  375,  375,  375,  375,  375,  375,  375,  375,  375,
 /*    10 */   375,  375,  361,  326,  326,  375,  375,  326,  326,  375,
 /*    20 */   375,  375,  375,  375,  375,  375,  375,  375,  375,  375,
 /*    30 */   375,  375,  375,  375,  375,  375,  375,  271,  304,  299,
 /*    40 */   271,  271,  242,  335,  335,  308,  308,  375,  375,  308,
 /*    50 */   375,  375,  375,  375,  375,  375,  375,  375,  375,  375,
 /*    60 */   271,  375,  294,  295,  375,  375,  375,  342,  333,  338,
 /*    70 */   339,  347,  343,  337,  346,  375,  310,  375,  375,  375,
 /*    80 */   375,  277,  375,  375,  331,  375,  325,  375,  375,  375,
 /*    90 */   362,  375,  375,  375,  375,  320,  300,  297,  308,  375,
 /*   100 */   296,  308,  275,  272,  336,  317,  375,  363,  364,  264,
 /*   110 */   278,  329,  329,  276,  375,  276,  375,  330,  276,  309,
 /*   120 */   375,  375,  375,  375,  375,  375,  375,  375,  375,  375,
 /*   130 */   375,  375,  375,  375,  375,  375,  375,  375,  375,  375,
 /*   140 */   375,  375,  375,  375,  298,  375,  375,  375,  375,  356,
 /*   150 */   265,  332,  274,  357,  268,  354,  355,  243,  261,  334,
 /*   160 */   273,  345,  344,  353,  263,  340,  267,  260,  266,  341,
 /*   170 */   293,  292,  306,  365,  291,  290,  288,  289,  367,  368,
 /*   180 */   302,  328,  284,  301,  283,  319,  282,  287,  286,  373,
 /*   190 */   374,  372,  371,  246,  244,  245,  247,  248,  307,  280,
 /*   200 */   281,  252,  251,  249,  250,  285,  303,  255,  269,  270,
 /*   210 */   254,  253,  316,  313,  256,  257,  349,  350,  351,  348,
 /*   220 */   262,  258,  259,  315,  314,  359,  324,  322,  360,  358,
 /*   230 */   327,  279,  312,  311,  370,  366,  305,  369,  318,  323,
 /*   240 */   321,  352,
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
    const YYNOCODE = 109;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 242;
    const YYNRULE = 133;
    const YYERRORSYMBOL = 69;
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
    1,  /*        XML => OTHER */
    1,  /*        PHP => OTHER */
    1,  /* SHORTTAGSTART => OTHER */
    1,  /* SHORTTAGEND => OTHER */
    1,  /* COMMENTEND => OTHER */
    1,  /* COMMENTSTART => OTHER */
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
    1,  /*      ISODD => OTHER */
    1,  /*   ISNOTODD => OTHER */
    1,  /*     ISEVEN => OTHER */
    1,  /*  ISNOTEVEN => OTHER */
    1,  /*    ISODDBY => OTHER */
    1,  /* ISNOTODDBY => OTHER */
    1,  /*   ISEVENBY => OTHER */
    1,  /* ISNOTEVENBY => OTHER */
    1,  /*    ISDIVBY => OTHER */
    1,  /* ISNOTDIVBY => OTHER */
    0,  /* LITERALSTART => nothing */
    0,  /* LITERALEND => nothing */
    0,  /*  LDELIMTAG => nothing */
    0,  /*  RDELIMTAG => nothing */
    0,  /*   PHPSTART => nothing */
    0,  /*     PHPEND => nothing */
    0,  /* BLOCKSTART => nothing */
    0,  /*   BLOCKEND => nothing */
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
  'RDEL',          'XML',           'PHP',           'SHORTTAGSTART',
  'SHORTTAGEND',   'COMMENTEND',    'COMMENTSTART',  'NUMBER',      
  'MATH',          'UNIMATH',       'INCDEC',        'OPENP',       
  'CLOSEP',        'OPENB',         'CLOSEB',        'DOLLAR',      
  'DOT',           'COMMA',         'COLON',         'DOUBLECOLON', 
  'SEMICOLON',     'VERT',          'EQUAL',         'SPACE',       
  'PTR',           'APTR',          'ID',            'EQUALS',      
  'NOTEQUALS',     'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',
  'LESSEQUAL',     'IDENTITY',      'NONEIDENTITY',  'NOT',         
  'LAND',          'LOR',           'QUOTE',         'SINGLEQUOTE', 
  'BOOLEAN',       'NULL',          'IN',            'ANDSYM',      
  'BACKTICK',      'HATCH',         'AT',            'ISODD',       
  'ISNOTODD',      'ISEVEN',        'ISNOTEVEN',     'ISODDBY',     
  'ISNOTODDBY',    'ISEVENBY',      'ISNOTEVENBY',   'ISDIVBY',     
  'ISNOTDIVBY',    'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHPSTART',      'PHPEND',        'BLOCKSTART',  
  'BLOCKEND',      'error',         'start',         'template',    
  'template_element',  'smartytag',     'text',          'variable',    
  'expr',          'attributes',    'statement',     'modifier',    
  'modparameters',  'ifexprs',       'statements',    'varvar',      
  'foraction',     'value',         'array',         'attribute',   
  'exprs',         'math',          'function',      'doublequoted',
  'method',        'params',        'objectchain',   'vararraydefs',
  'object',        'vararraydef',   'varvarele',     'objectelement',
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
 /*  10 */ "template_element ::= SHORTTAGSTART variable SHORTTAGEND",
 /*  11 */ "template_element ::= XML",
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
 /*  22 */ "foraction ::= EQUAL expr",
 /*  23 */ "foraction ::= INCDEC",
 /*  24 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN value RDEL",
 /*  25 */ "smartytag ::= LDEL ID SPACE DOLLAR varvar IN array RDEL",
 /*  26 */ "smartytag ::= BLOCKSTART ID RDEL text BLOCKEND ID RDEL",
 /*  27 */ "attributes ::= attributes attribute",
 /*  28 */ "attributes ::= attribute",
 /*  29 */ "attributes ::=",
 /*  30 */ "attribute ::= SPACE ID EQUAL expr",
 /*  31 */ "statements ::= statement",
 /*  32 */ "statements ::= statements COMMA statement",
 /*  33 */ "statement ::= DOLLAR varvar EQUAL expr",
 /*  34 */ "expr ::= ID",
 /*  35 */ "expr ::= exprs",
 /*  36 */ "expr ::= expr modifier modparameters",
 /*  37 */ "expr ::= array",
 /*  38 */ "exprs ::= value",
 /*  39 */ "exprs ::= UNIMATH value",
 /*  40 */ "exprs ::= exprs math value",
 /*  41 */ "exprs ::= exprs ANDSYM value",
 /*  42 */ "math ::= UNIMATH",
 /*  43 */ "math ::= MATH",
 /*  44 */ "value ::= variable",
 /*  45 */ "value ::= HATCH ID HATCH",
 /*  46 */ "value ::= NUMBER",
 /*  47 */ "value ::= function",
 /*  48 */ "value ::= SINGLEQUOTE text SINGLEQUOTE",
 /*  49 */ "value ::= SINGLEQUOTE SINGLEQUOTE",
 /*  50 */ "value ::= QUOTE doublequoted QUOTE",
 /*  51 */ "value ::= QUOTE QUOTE",
 /*  52 */ "value ::= ID DOUBLECOLON method",
 /*  53 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP",
 /*  54 */ "value ::= ID DOUBLECOLON method objectchain",
 /*  55 */ "value ::= ID DOUBLECOLON DOLLAR ID OPENP params CLOSEP objectchain",
 /*  56 */ "value ::= ID DOUBLECOLON ID",
 /*  57 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs",
 /*  58 */ "value ::= ID DOUBLECOLON DOLLAR ID vararraydefs objectchain",
 /*  59 */ "value ::= BOOLEAN",
 /*  60 */ "value ::= NULL",
 /*  61 */ "value ::= OPENP expr CLOSEP",
 /*  62 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  63 */ "variable ::= DOLLAR varvar AT ID",
 /*  64 */ "variable ::= object",
 /*  65 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  66 */ "vararraydefs ::=",
 /*  67 */ "vararraydef ::= DOT ID",
 /*  68 */ "vararraydef ::= DOT exprs",
 /*  69 */ "vararraydef ::= OPENB ID CLOSEB",
 /*  70 */ "vararraydef ::= OPENB exprs CLOSEB",
 /*  71 */ "varvar ::= varvarele",
 /*  72 */ "varvar ::= varvar varvarele",
 /*  73 */ "varvarele ::= ID",
 /*  74 */ "varvarele ::= LDEL expr RDEL",
 /*  75 */ "object ::= DOLLAR varvar vararraydefs objectchain",
 /*  76 */ "objectchain ::= objectelement",
 /*  77 */ "objectchain ::= objectchain objectelement",
 /*  78 */ "objectelement ::= PTR ID vararraydefs",
 /*  79 */ "objectelement ::= PTR method",
 /*  80 */ "function ::= ID OPENP params CLOSEP",
 /*  81 */ "method ::= ID OPENP params CLOSEP",
 /*  82 */ "params ::= expr COMMA params",
 /*  83 */ "params ::= expr",
 /*  84 */ "params ::=",
 /*  85 */ "modifier ::= VERT ID",
 /*  86 */ "modparameters ::= modparameters modparameter",
 /*  87 */ "modparameters ::=",
 /*  88 */ "modparameter ::= COLON ID",
 /*  89 */ "modparameter ::= COLON exprs",
 /*  90 */ "ifexprs ::= ifexpr",
 /*  91 */ "ifexprs ::= NOT ifexprs",
 /*  92 */ "ifexprs ::= OPENP ifexprs CLOSEP",
 /*  93 */ "ifexpr ::= expr",
 /*  94 */ "ifexpr ::= expr ifcond expr",
 /*  95 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  96 */ "ifexpr ::= ifexprs ISDIVBY ifexprs",
 /*  97 */ "ifexpr ::= ifexprs ISNOTDIVBY ifexprs",
 /*  98 */ "ifexpr ::= ifexprs ISEVEN",
 /*  99 */ "ifexpr ::= ifexprs ISNOTEVEN",
 /* 100 */ "ifexpr ::= ifexprs ISEVENBY ifexprs",
 /* 101 */ "ifexpr ::= ifexprs ISNOTEVENBY ifexprs",
 /* 102 */ "ifexpr ::= ifexprs ISODD",
 /* 103 */ "ifexpr ::= ifexprs ISNOTODD",
 /* 104 */ "ifexpr ::= ifexprs ISODDBY ifexprs",
 /* 105 */ "ifexpr ::= ifexprs ISNOTODDBY ifexprs",
 /* 106 */ "ifcond ::= EQUALS",
 /* 107 */ "ifcond ::= NOTEQUALS",
 /* 108 */ "ifcond ::= GREATERTHAN",
 /* 109 */ "ifcond ::= LESSTHAN",
 /* 110 */ "ifcond ::= GREATEREQUAL",
 /* 111 */ "ifcond ::= LESSEQUAL",
 /* 112 */ "ifcond ::= IDENTITY",
 /* 113 */ "ifcond ::= NONEIDENTITY",
 /* 114 */ "lop ::= LAND",
 /* 115 */ "lop ::= LOR",
 /* 116 */ "array ::= OPENB arrayelements CLOSEB",
 /* 117 */ "arrayelements ::= arrayelement",
 /* 118 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 119 */ "arrayelements ::=",
 /* 120 */ "arrayelement ::= expr",
 /* 121 */ "arrayelement ::= expr APTR expr",
 /* 122 */ "arrayelement ::= ID APTR expr",
 /* 123 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 124 */ "doublequoted ::= doublequotedcontent",
 /* 125 */ "doublequotedcontent ::= variable",
 /* 126 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 127 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 128 */ "doublequotedcontent ::= OTHER",
 /* 129 */ "text ::= text textelement",
 /* 130 */ "text ::= textelement",
 /* 131 */ "textelement ::= OTHER",
 /* 132 */ "textelement ::= LDEL",
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
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 6 ),
  array( 'lhs' => 73, 'rhs' => 6 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 5 ),
  array( 'lhs' => 73, 'rhs' => 5 ),
  array( 'lhs' => 73, 'rhs' => 11 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 8 ),
  array( 'lhs' => 73, 'rhs' => 8 ),
  array( 'lhs' => 73, 'rhs' => 7 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 0 ),
  array( 'lhs' => 87, 'rhs' => 4 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 2 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 2 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 7 ),
  array( 'lhs' => 85, 'rhs' => 4 ),
  array( 'lhs' => 85, 'rhs' => 8 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 5 ),
  array( 'lhs' => 85, 'rhs' => 6 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 0 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 97, 'rhs' => 3 ),
  array( 'lhs' => 97, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 96, 'rhs' => 4 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 90, 'rhs' => 4 ),
  array( 'lhs' => 92, 'rhs' => 4 ),
  array( 'lhs' => 93, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 0 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 0 ),
  array( 'lhs' => 100, 'rhs' => 2 ),
  array( 'lhs' => 100, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 2 ),
  array( 'lhs' => 101, 'rhs' => 2 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 2 ),
  array( 'lhs' => 101, 'rhs' => 2 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 0 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 3 ),
  array( 'lhs' => 105, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 2 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 106, 'rhs' => 1 ),
  array( 'lhs' => 106, 'rhs' => 3 ),
  array( 'lhs' => 106, 'rhs' => 3 ),
  array( 'lhs' => 106, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 107, 'rhs' => 1 ),
  array( 'lhs' => 107, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        38 => 0,
        44 => 0,
        46 => 0,
        47 => 0,
        59 => 0,
        60 => 0,
        64 => 0,
        117 => 0,
        1 => 1,
        35 => 1,
        37 => 1,
        42 => 1,
        43 => 1,
        71 => 1,
        90 => 1,
        124 => 1,
        130 => 1,
        131 => 1,
        132 => 1,
        2 => 2,
        65 => 2,
        123 => 2,
        129 => 2,
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
        22 => 22,
        23 => 23,
        28 => 23,
        83 => 23,
        120 => 23,
        24 => 24,
        25 => 24,
        26 => 26,
        27 => 27,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        33 => 33,
        34 => 34,
        36 => 36,
        39 => 39,
        40 => 40,
        41 => 41,
        45 => 45,
        48 => 48,
        49 => 49,
        51 => 49,
        50 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        58 => 58,
        61 => 61,
        62 => 62,
        63 => 63,
        66 => 66,
        87 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        72 => 72,
        73 => 73,
        74 => 74,
        92 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        84 => 84,
        85 => 85,
        86 => 86,
        88 => 88,
        89 => 89,
        91 => 91,
        93 => 93,
        94 => 94,
        95 => 94,
        96 => 96,
        97 => 97,
        98 => 98,
        103 => 98,
        99 => 99,
        102 => 99,
        100 => 100,
        105 => 100,
        101 => 101,
        104 => 101,
        106 => 106,
        107 => 107,
        108 => 108,
        109 => 109,
        110 => 110,
        111 => 111,
        112 => 112,
        113 => 113,
        114 => 114,
        115 => 115,
        116 => 116,
        118 => 118,
        119 => 119,
        121 => 121,
        122 => 122,
        125 => 125,
        126 => 126,
        127 => 127,
        128 => 128,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 72 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1735 "internal.templateparser.php"
#line 78 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1738 "internal.templateparser.php"
#line 80 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1741 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1747 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '';    }
#line 1750 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1753 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1756 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1759 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1770 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1781 "internal.templateparser.php"
#line 127 "internal.templateparser.y"
    function yy_r10(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode($this->compiler->compileTag('print_expression',array('value'=>$this->yystack[$this->yyidx + -1]->minor)), $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.t.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".t." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1792 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1795 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1798 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1801 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1804 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1807 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1810 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
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
#line 1825 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1828 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1831 "internal.templateparser.php"
#line 176 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1834 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1837 "internal.templateparser.php"
#line 179 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1840 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1843 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1846 "internal.templateparser.php"
#line 186 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue =  $this->compiler->compileTag('block',array('id'=>$this->yystack[$this->yyidx + -5]->minor,'content'=>$this->yystack[$this->yyidx + -3]->minor));  $this->compiler->compileTag('blockclose',array('id'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1849 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1852 "internal.templateparser.php"
#line 196 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array();    }
#line 1855 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1858 "internal.templateparser.php"
#line 205 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1861 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r32(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1864 "internal.templateparser.php"
#line 208 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1867 "internal.templateparser.php"
#line 215 "internal.templateparser.y"
    function yy_r34(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1870 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r36(){if ($this->smarty->plugin_handler->loadSmartyPlugin($this->yystack[$this->yyidx + -1]->minor,'modifier')) {
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
#line 1884 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1887 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1890 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1893 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1896 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1899 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = "''";     }
#line 1902 "internal.templateparser.php"
#line 278 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = "'".str_replace('\"','"',$this->yystack[$this->yyidx + -1]->minor)."'";     }
#line 1905 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1908 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1911 "internal.templateparser.php"
#line 287 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1914 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r55(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1917 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1920 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1923 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1926 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1929 "internal.templateparser.php"
#line 310 "internal.templateparser.y"
    function yy_r62(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1933 "internal.templateparser.php"
#line 313 "internal.templateparser.y"
    function yy_r63(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1936 "internal.templateparser.php"
#line 323 "internal.templateparser.y"
    function yy_r66(){return;    }
#line 1939 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1942 "internal.templateparser.php"
#line 326 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1945 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1948 "internal.templateparser.php"
#line 331 "internal.templateparser.y"
    function yy_r70(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1951 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1954 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1957 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1960 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r75(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1963 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1966 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1969 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1972 "internal.templateparser.php"
#line 357 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1975 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r80(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1984 "internal.templateparser.php"
#line 373 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1987 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1990 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r84(){ return;    }
#line 1993 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1996 "internal.templateparser.php"
#line 392 "internal.templateparser.y"
    function yy_r86(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1999 "internal.templateparser.php"
#line 396 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 2002 "internal.templateparser.php"
#line 397 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2005 "internal.templateparser.php"
#line 404 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2008 "internal.templateparser.php"
#line 409 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 2011 "internal.templateparser.php"
#line 410 "internal.templateparser.y"
    function yy_r94(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2014 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2017 "internal.templateparser.php"
#line 413 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2020 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2023 "internal.templateparser.php"
#line 415 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2026 "internal.templateparser.php"
#line 416 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2029 "internal.templateparser.php"
#line 417 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2032 "internal.templateparser.php"
#line 423 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = '==';    }
#line 2035 "internal.templateparser.php"
#line 424 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = '!=';    }
#line 2038 "internal.templateparser.php"
#line 425 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = '>';    }
#line 2041 "internal.templateparser.php"
#line 426 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = '<';    }
#line 2044 "internal.templateparser.php"
#line 427 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = '>=';    }
#line 2047 "internal.templateparser.php"
#line 428 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = '<=';    }
#line 2050 "internal.templateparser.php"
#line 429 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = '===';    }
#line 2053 "internal.templateparser.php"
#line 430 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = '!==';    }
#line 2056 "internal.templateparser.php"
#line 432 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = '&&';    }
#line 2059 "internal.templateparser.php"
#line 433 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = '||';    }
#line 2062 "internal.templateparser.php"
#line 435 "internal.templateparser.y"
    function yy_r116(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2065 "internal.templateparser.php"
#line 437 "internal.templateparser.y"
    function yy_r118(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 2068 "internal.templateparser.php"
#line 438 "internal.templateparser.y"
    function yy_r119(){ return;     }
#line 2071 "internal.templateparser.php"
#line 440 "internal.templateparser.y"
    function yy_r121(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2074 "internal.templateparser.php"
#line 442 "internal.templateparser.y"
    function yy_r122(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2077 "internal.templateparser.php"
#line 446 "internal.templateparser.y"
    function yy_r125(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 2080 "internal.templateparser.php"
#line 447 "internal.templateparser.y"
    function yy_r126(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 2083 "internal.templateparser.php"
#line 448 "internal.templateparser.y"
    function yy_r127(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 2086 "internal.templateparser.php"
#line 449 "internal.templateparser.y"
    function yy_r128(){$this->_retvalue = addcslashes($this->yystack[$this->yyidx + 0]->minor,"'");    }
#line 2089 "internal.templateparser.php"

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
#line 2206 "internal.templateparser.php"
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
#line 2231 "internal.templateparser.php"
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
