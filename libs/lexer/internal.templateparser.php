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
    const TP_ISODD                          = 47;
    const TP_ISNOTODD                       = 48;
    const TP_ISEVEN                         = 49;
    const TP_ISNOTEVEN                      = 50;
    const TP_ISODDBY                        = 51;
    const TP_ISNOTODDBY                     = 52;
    const TP_ISEVENBY                       = 53;
    const TP_ISNOTEVENBY                    = 54;
    const TP_ISDIVBY                        = 55;
    const TP_ISNOTDIVBY                     = 56;
    const TP_COMMENTSTART                   = 57;
    const TP_COMMENTEND                     = 58;
    const TP_LITERALSTART                   = 59;
    const TP_LITERALEND                     = 60;
    const TP_LDELIMTAG                      = 61;
    const TP_RDELIMTAG                      = 62;
    const TP_PHP                            = 63;
    const TP_PHPSTART                       = 64;
    const TP_PHPEND                         = 65;
    const YY_NO_ACTION = 367;
    const YY_ACCEPT_ACTION = 366;
    const YY_ERROR_ACTION = 365;

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
    const YY_SZ_ACTTAB = 729;
static public $yy_action = array(
 /*     0 */   230,  147,  146,  366,   42,  161,  160,  165,  165,  166,
 /*    10 */   166,   16,  149,  148,  143,  152,   10,    8,   11,    9,
 /*    20 */     2,    7,  165,  215,  166,   34,   17,   27,  165,   12,
 /*    30 */   166,   57,  147,  146,   82,  165,  133,  166,    1,  128,
 /*    40 */    25,  194,  116,  149,  148,  143,  152,   10,    8,   11,
 /*    50 */     9,    2,    7,  126,   35,   48,  221,  223,   90,   23,
 /*    60 */   216,  127,  190,  192,  157,  133,  145,  174,  205,  147,
 /*    70 */   146,  220,  219,  178,  210,  209,  207,  208,  212,  133,
 /*    80 */   149,  148,  143,  152,   10,    8,   11,    9,    2,    7,
 /*    90 */   147,  146,  215,   25,   34,  133,    6,  122,   12,  167,
 /*   100 */    57,  149,  148,  143,  152,   10,    8,   11,    9,    2,
 /*   110 */     7,  116,   23,  215,   17,   34,  192,    6,   86,   12,
 /*   120 */     4,   51,   82,   35,   48,  221,  223,   25,   24,  215,
 /*   130 */   127,   34,  110,   27,   18,   12,  138,   57,  133,  163,
 /*   140 */   107,    4,  187,  225,   35,   48,  221,  223,  111,   25,
 /*   150 */   192,  127,  222,  177,  129,   14,  172,  169,  170,  137,
 /*   160 */    35,   48,  221,  223,   44,  131,  159,  127,   21,   63,
 /*   170 */   118,  122,  192,  144,  185,  102,   80,   33,  142,  214,
 /*   180 */   189,  203,   22,  197,   15,  184,   84,  215,  108,   34,
 /*   190 */   217,   27,  133,   12,   57,   52,  205,  134,  220,  219,
 /*   200 */   178,  210,  209,  207,  208,  212,   36,   98,  135,   53,
 /*   210 */   228,   54,  198,  175,  154,  171,   56,  213,   35,   48,
 /*   220 */   221,  223,  215,   60,   34,  127,   27,   89,   95,  109,
 /*   230 */    57,  228,  215,  198,   34,   17,   27,  205,  195,   58,
 /*   240 */    57,  112,  215,   82,   25,  181,   27,   37,   12,   17,
 /*   250 */    57,  113,  184,   35,   48,  221,  223,   82,  231,   25,
 /*   260 */   127,  117,  186,   35,   48,  221,  223,  192,  106,  224,
 /*   270 */   127,  187,  225,   35,   48,  221,  223,  196,   23,  181,
 /*   280 */   127,  215,  192,   34,   85,   27,  184,  138,  122,   57,
 /*   290 */    31,  215,  150,   30,  205,   27,  182,  151,   28,   57,
 /*   300 */   115,  106,  138,  165,   96,  166,   33,    5,  151,  227,
 /*   310 */   117,  122,   35,   48,  221,  223,  133,  125,  197,  127,
 /*   320 */    26,   44,   35,   48,  221,  223,   62,  133,  122,  127,
 /*   330 */   144,  185,  201,   80,   44,  142,  214,   31,  133,   72,
 /*   340 */    30,  218,  184,  144,  185,  226,   80,  217,  142,  214,
 /*   350 */   142,  214,   41,   44,  191,  184,   74,  184,   70,   78,
 /*   360 */   217,   29,  144,  185,  141,   80,  122,  142,  214,  142,
 /*   370 */   214,  133,   44,  108,  184,  183,  184,   65,  108,  217,
 /*   380 */    17,  144,  185,   29,   80,   44,  142,  214,   82,  173,
 /*   390 */    67,   19,  156,  184,  144,  185,  121,   80,  217,  142,
 /*   400 */   214,   39,   20,   44,  201,   79,  184,   75,   66,  164,
 /*   410 */   160,  217,  144,  185,   50,   80,   43,  142,  214,   77,
 /*   420 */    47,   64,  168,  211,  184,  144,  185,  229,   80,  217,
 /*   430 */   142,  214,  142,  214,  123,   83,  183,  184,  173,  184,
 /*   440 */   173,  124,  217,   45,  144,  185,  180,   80,  184,  142,
 /*   450 */   214,   59,  173,   19,  114,  232,  184,   44,   46,  183,
 /*   460 */    49,  202,   68,  105,  119,  199,  144,  185,  198,   80,
 /*   470 */    44,  142,  214,   94,  183,   71,  183,  193,  184,  144,
 /*   480 */   185,  100,   80,  217,  142,  214,  198,  162,   44,  206,
 /*   490 */   155,  184,    3,   69,  200,  158,  217,  144,  185,   32,
 /*   500 */    80,   91,  142,  214,  122,   57,   61,  201,   13,  184,
 /*   510 */   144,  185,   38,   80,  217,  142,  214,  153,   40,  136,
 /*   520 */   130,   55,  184,  176,  228,  239,  239,  144,  185,  239,
 /*   530 */    80,  144,  142,  214,   73,   91,  142,  214,  239,  184,
 /*   540 */   239,  239,  239,  184,  144,  185,  239,   80,   83,  142,
 /*   550 */   214,  239,  239,  132,  239,  239,  184,  144,  185,  239,
 /*   560 */    80,   91,  142,  214,  239,  239,  239,  239,  239,  184,
 /*   570 */   144,  185,  239,   80,   91,  142,  214,  239,  204,  179,
 /*   580 */   239,  239,  184,  144,  185,  239,   80,   97,  142,  214,
 /*   590 */   239,  189,  120,   22,  239,  184,  144,  185,  239,   80,
 /*   600 */    93,  142,  214,  239,  239,   57,  239,  239,  184,  144,
 /*   610 */   185,  239,   80,  144,  142,  214,   81,  104,  142,  214,
 /*   620 */   239,  184,  239,  239,  239,  184,  144,  185,  188,   80,
 /*   630 */    88,  142,  214,  239,   60,  239,  239,  239,  184,  144,
 /*   640 */   185,  239,   80,  103,  142,  214,  239,  239,  239,  239,
 /*   650 */   239,  184,  144,  185,  239,   80,  101,  142,  214,  239,
 /*   660 */   239,  239,  239,  239,  184,  144,  185,  239,   80,   92,
 /*   670 */   142,  214,  239,  239,  239,  239,  239,  184,  144,  185,
 /*   680 */   239,   80,   99,  142,  214,  239,  239,  239,  239,  239,
 /*   690 */   184,  144,  185,  239,   80,  239,  142,  214,  239,   87,
 /*   700 */   139,  140,  239,  184,  239,  142,  214,  239,  144,  185,
 /*   710 */   239,   80,  184,  142,  214,  239,  144,  239,  239,   76,
 /*   720 */   184,  142,  214,  239,  239,  239,  239,  239,  184,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,   36,   37,   67,   68,   69,   70,    1,    1,    3,
 /*    10 */     3,   11,   47,   48,   49,   50,   51,   52,   53,   54,
 /*    20 */    55,   56,    1,    7,    3,    9,   11,   11,    1,   13,
 /*    30 */     3,   15,   36,   37,   19,    1,   21,    3,   23,   24,
 /*    40 */     3,   89,   26,   47,   48,   49,   50,   51,   52,   53,
 /*    50 */    54,   55,   56,   20,   38,   39,   40,   41,   73,   22,
 /*    60 */    39,   45,    4,   26,   58,   21,   12,   60,   83,   36,
 /*    70 */    37,   27,   28,   29,   30,   31,   32,   33,   34,   21,
 /*    80 */    47,   48,   49,   50,   51,   52,   53,   54,   55,   56,
 /*    90 */    36,   37,    7,    3,    9,   21,   11,   23,   13,   65,
 /*   100 */    15,   47,   48,   49,   50,   51,   52,   53,   54,   55,
 /*   110 */    56,   26,   22,    7,   11,    9,   26,   11,   92,   13,
 /*   120 */    35,   15,   19,   38,   39,   40,   41,    3,   25,    7,
 /*   130 */    45,    9,   26,   11,   17,   13,   46,   15,   21,    4,
 /*   140 */    26,   35,    8,    9,   38,   39,   40,   41,   26,    3,
 /*   150 */    26,   45,    4,    1,    2,    3,   10,    5,    6,   24,
 /*   160 */    38,   39,   40,   41,   72,   26,   74,   45,   22,   77,
 /*   170 */    78,   23,   26,   81,   82,   76,   84,   43,   86,   87,
 /*   180 */     1,   14,    3,   12,   17,   93,   73,    7,   75,    9,
 /*   190 */    98,   11,   21,   13,   15,   15,   83,   15,   27,   28,
 /*   200 */    29,   30,   31,   32,   33,   34,   26,   91,   26,   57,
 /*   210 */    94,   59,   96,   61,   62,   63,   64,   38,   38,   39,
 /*   220 */    40,   41,    7,   44,    9,   45,   11,   73,   91,   75,
 /*   230 */    15,   94,    7,   96,    9,   11,   11,   83,   14,   12,
 /*   240 */    15,   26,    7,   19,    3,   86,   11,   88,   13,   11,
 /*   250 */    15,   26,   93,   38,   39,   40,   41,   19,   96,    3,
 /*   260 */    45,   26,  103,   38,   39,   40,   41,   26,   24,    4,
 /*   270 */    45,    8,    9,   38,   39,   40,   41,   14,   22,   86,
 /*   280 */    45,    7,   26,    9,   73,   11,   93,   46,   23,   15,
 /*   290 */    13,    7,    4,   16,   83,   11,  103,   97,   42,   15,
 /*   300 */    26,   24,   46,    1,   17,    3,   43,   20,   97,    4,
 /*   310 */    26,   23,   38,   39,   40,   41,   21,   80,   12,   45,
 /*   320 */    25,   72,   38,   39,   40,   41,   77,   21,   23,   45,
 /*   330 */    81,   82,   95,   84,   72,   86,   87,   13,   21,   77,
 /*   340 */    16,   39,   93,   81,   82,   81,   84,   98,   86,   87,
 /*   350 */    86,   87,   76,   72,    4,   93,   71,   93,   77,   79,
 /*   360 */    98,   18,   81,   82,   81,   84,   23,   86,   87,   86,
 /*   370 */    87,   21,   72,   75,   93,   95,   93,   77,   75,   98,
 /*   380 */    11,   81,   82,   18,   84,   72,   86,   87,   19,  104,
 /*   390 */    77,   22,    4,   93,   81,   82,   26,   84,   98,   86,
 /*   400 */    87,   92,   99,   72,   95,   71,   93,   71,   77,   69,
 /*   410 */    70,   98,   81,   82,   15,   84,   72,   86,   87,   71,
 /*   420 */    79,   77,    4,   12,   93,   81,   82,   81,   84,   98,
 /*   430 */    86,   87,   86,   87,   26,   72,   95,   93,  104,   93,
 /*   440 */   104,   86,   98,   79,   81,   82,   44,   84,   93,   86,
 /*   450 */    87,   26,  104,   22,   26,   45,   93,   72,   79,   95,
 /*   460 */    79,   26,   77,   91,  101,  102,   81,   82,   96,   84,
 /*   470 */    72,   86,   87,   26,   95,   77,   95,   12,   93,   81,
 /*   480 */    82,   91,   84,   98,   86,   87,   96,    4,   72,    4,
 /*   490 */     4,   93,  100,   77,   26,  104,   98,   81,   82,   85,
 /*   500 */    84,   72,   86,   87,   23,   15,   89,   95,   11,   93,
 /*   510 */    81,   82,   92,   84,   98,   86,   87,   83,   72,   90,
 /*   520 */    74,   15,   93,   74,   94,  105,  105,   81,   82,  105,
 /*   530 */    84,   81,   86,   87,   84,   72,   86,   87,  105,   93,
 /*   540 */   105,  105,  105,   93,   81,   82,  105,   84,   72,   86,
 /*   550 */    87,  105,  105,   90,  105,  105,   93,   81,   82,  105,
 /*   560 */    84,   72,   86,   87,  105,  105,  105,  105,  105,   93,
 /*   570 */    81,   82,  105,   84,   72,   86,   87,  105,  102,   90,
 /*   580 */   105,  105,   93,   81,   82,  105,   84,   72,   86,   87,
 /*   590 */   105,    1,   90,    3,  105,   93,   81,   82,  105,   84,
 /*   600 */    72,   86,   87,  105,  105,   15,  105,  105,   93,   81,
 /*   610 */    82,  105,   84,   81,   86,   87,   84,   72,   86,   87,
 /*   620 */   105,   93,  105,  105,  105,   93,   81,   82,   38,   84,
 /*   630 */    72,   86,   87,  105,   44,  105,  105,  105,   93,   81,
 /*   640 */    82,  105,   84,   72,   86,   87,  105,  105,  105,  105,
 /*   650 */   105,   93,   81,   82,  105,   84,   72,   86,   87,  105,
 /*   660 */   105,  105,  105,  105,   93,   81,   82,  105,   84,   72,
 /*   670 */    86,   87,  105,  105,  105,  105,  105,   93,   81,   82,
 /*   680 */   105,   84,   72,   86,   87,  105,  105,  105,  105,  105,
 /*   690 */    93,   81,   82,  105,   84,  105,   86,   87,  105,   72,
 /*   700 */    81,   82,  105,   93,  105,   86,   87,  105,   81,   82,
 /*   710 */   105,   84,   93,   86,   87,  105,   81,  105,  105,   84,
 /*   720 */    93,   86,   87,  105,  105,  105,  105,  105,   93,
);
    const YY_SHIFT_USE_DFLT = -36;
    const YY_SHIFT_MAX = 140;
    static public $yy_shift_ofst = array(
 /*     0 */   152,  106,   85,   85,   85,   85,   85,   85,   85,   85,
 /*    10 */    85,   85,  122,   16,  180,  122,   16,   16,   16,   16,
 /*    20 */    16,   16,   16,   16,   16,   16,   16,   16,  235,  274,
 /*    30 */   225,  215,  284,  284,  284,  590,   15,  179,  277,  277,
 /*    40 */    74,  343,  152,  171,   44,  256,   90,  146,  302,  241,
 /*    50 */   124,  124,  124,   27,   27,  124,   27,  124,  244,  481,
 /*    60 */   490,  244,   33,   -4,   54,  -35,  -35,  -35,  -35,  -35,
 /*    70 */   -35,  -35,  -35,  263,    7,    6,  134,   21,   37,   34,
 /*    80 */   134,  134,  182,  295,  288,  305,  324,  306,  350,  265,
 /*    90 */   148,  117,   58,  317,  497,  244,  506,  317,  244,  317,
 /*   100 */   244,  317,  365,  317,  317,  244,  114,    0,  -36,  -36,
 /*   110 */   369,  103,  224,  238,  135,  238,  238,  238,  287,  167,
 /*   120 */   411,  410,  408,  431,  402,  418,  399,  370,  425,  428,
 /*   130 */   485,  483,  465,  435,  447,    0,  227,  139,  468,  388,
 /*   140 */   486,
);
    const YY_REDUCE_USE_DFLT = -65;
    const YY_REDUCE_MAX = 109;
    static public $yy_reduce_ofst = array(
 /*     0 */   -64,   92,  262,  331,  300,  249,  344,  416,  385,  281,
 /*    10 */   398,  313,  363,  429,  446,  476,  463,  502,  489,  584,
 /*    20 */   528,  610,  597,  515,  571,  558,  545,  627,  619,  635,
 /*    30 */   532,  450,  346,  264,  283,  159,  154,  193,  116,  137,
 /*    40 */   113,  211,  340,  303,  303,  309,  309,  237,  348,  309,
 /*    50 */   341,  364,  379,  336,  285,  280,  334,  381,  390,  -15,
 /*    60 */   355,  372,  392,  392,  392,  392,  392,  392,  392,  392,
 /*    70 */   392,  392,  392,  414,  391,  391,  414,  391,  412,  391,
 /*    80 */   414,  414,  417,  298,  434,  434,  430,  298,  298,  434,
 /*    90 */   434,  298,  298,  298,  420,  162,  449,  298,  162,  298,
 /*   100 */   162,  298,  200,  298,  298,  162,  -48,   26,   99,  276,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 57, 59, 61, 62, 63, 64, ),
        /* 1 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 2 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 3 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 4 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 5 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 6 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 7 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 8 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 9 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 10 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
        /* 11 */ array(7, 9, 11, 13, 15, 26, 35, 38, 39, 40, 41, 45, ),
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
        /* 22 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 23 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 24 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 25 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 26 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 27 */ array(7, 9, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 28 */ array(7, 11, 13, 15, 26, 38, 39, 40, 41, 45, ),
        /* 29 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 30 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 31 */ array(7, 9, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 32 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 33 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 34 */ array(7, 11, 15, 26, 38, 39, 40, 41, 45, ),
        /* 35 */ array(1, 3, 15, 38, 44, ),
        /* 36 */ array(11, 19, 21, 23, 24, ),
        /* 37 */ array(1, 3, 15, 38, 44, ),
        /* 38 */ array(13, 16, 24, ),
        /* 39 */ array(13, 16, 24, ),
        /* 40 */ array(21, 23, ),
        /* 41 */ array(18, 23, ),
        /* 42 */ array(1, 2, 3, 5, 6, 57, 59, 61, 62, 63, 64, ),
        /* 43 */ array(12, 21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 44 */ array(21, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 45 */ array(3, 22, 26, 42, 46, ),
        /* 46 */ array(3, 22, 26, 46, ),
        /* 47 */ array(3, 10, 22, 26, ),
        /* 48 */ array(1, 3, 39, ),
        /* 49 */ array(3, 26, 46, ),
        /* 50 */ array(3, 26, ),
        /* 51 */ array(3, 26, ),
        /* 52 */ array(3, 26, ),
        /* 53 */ array(1, 3, ),
        /* 54 */ array(1, 3, ),
        /* 55 */ array(3, 26, ),
        /* 56 */ array(1, 3, ),
        /* 57 */ array(3, 26, ),
        /* 58 */ array(24, ),
        /* 59 */ array(23, ),
        /* 60 */ array(15, ),
        /* 61 */ array(24, ),
        /* 62 */ array(20, 36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 63 */ array(4, 36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 64 */ array(12, 36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 65 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 66 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 67 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 68 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 69 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 70 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 71 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 72 */ array(36, 37, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 73 */ array(8, 9, 14, 43, ),
        /* 74 */ array(1, 3, 60, ),
        /* 75 */ array(1, 3, 58, ),
        /* 76 */ array(8, 9, 43, ),
        /* 77 */ array(1, 3, 39, ),
        /* 78 */ array(3, 22, 26, ),
        /* 79 */ array(1, 3, 65, ),
        /* 80 */ array(8, 9, 43, ),
        /* 81 */ array(8, 9, 43, ),
        /* 82 */ array(15, 26, ),
        /* 83 */ array(21, 25, ),
        /* 84 */ array(4, 23, ),
        /* 85 */ array(4, 23, ),
        /* 86 */ array(13, 16, ),
        /* 87 */ array(12, 21, ),
        /* 88 */ array(4, 21, ),
        /* 89 */ array(4, 23, ),
        /* 90 */ array(4, 23, ),
        /* 91 */ array(17, 21, ),
        /* 92 */ array(4, 21, ),
        /* 93 */ array(21, ),
        /* 94 */ array(11, ),
        /* 95 */ array(24, ),
        /* 96 */ array(15, ),
        /* 97 */ array(21, ),
        /* 98 */ array(24, ),
        /* 99 */ array(21, ),
        /* 100 */ array(24, ),
        /* 101 */ array(21, ),
        /* 102 */ array(18, ),
        /* 103 */ array(21, ),
        /* 104 */ array(21, ),
        /* 105 */ array(24, ),
        /* 106 */ array(26, ),
        /* 107 */ array(11, ),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(11, 19, 22, ),
        /* 111 */ array(11, 19, 25, ),
        /* 112 */ array(11, 14, 19, ),
        /* 113 */ array(11, 19, ),
        /* 114 */ array(4, 24, ),
        /* 115 */ array(11, 19, ),
        /* 116 */ array(11, 19, ),
        /* 117 */ array(11, 19, ),
        /* 118 */ array(17, 20, ),
        /* 119 */ array(14, 17, ),
        /* 120 */ array(12, ),
        /* 121 */ array(45, ),
        /* 122 */ array(26, ),
        /* 123 */ array(22, ),
        /* 124 */ array(44, ),
        /* 125 */ array(4, ),
        /* 126 */ array(15, ),
        /* 127 */ array(26, ),
        /* 128 */ array(26, ),
        /* 129 */ array(26, ),
        /* 130 */ array(4, ),
        /* 131 */ array(4, ),
        /* 132 */ array(12, ),
        /* 133 */ array(26, ),
        /* 134 */ array(26, ),
        /* 135 */ array(11, ),
        /* 136 */ array(12, ),
        /* 137 */ array(26, ),
        /* 138 */ array(26, ),
        /* 139 */ array(4, ),
        /* 140 */ array(4, ),
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
);
    static public $yy_default = array(
 /*     0 */   365,  365,  365,  365,  365,  365,  365,  365,  365,  365,
 /*    10 */   365,  365,  351,  316,  365,  365,  316,  316,  316,  365,
 /*    20 */   365,  365,  365,  365,  365,  365,  365,  365,  365,  365,
 /*    30 */   365,  365,  365,  365,  365,  365,  261,  365,  289,  294,
 /*    40 */   261,  261,  233,  325,  325,  298,  298,  365,  365,  298,
 /*    50 */   365,  365,  365,  365,  365,  365,  365,  365,  285,  261,
 /*    60 */   365,  284,  365,  365,  365,  323,  327,  332,  337,  329,
 /*    70 */   333,  336,  328,  365,  365,  365,  321,  365,  365,  365,
 /*    80 */   267,  300,  365,  352,  365,  365,  310,  365,  365,  365,
 /*    90 */   365,  315,  365,  326,  298,  307,  365,  265,  290,  257,
 /*   100 */   287,  262,  268,  354,  353,  286,  365,  298,  319,  319,
 /*   110 */   266,  266,  365,  299,  365,  320,  266,  365,  365,  365,
 /*   120 */   365,  365,  365,  365,  365,  365,  365,  365,  365,  365,
 /*   130 */   365,  365,  365,  365,  365,  288,  365,  365,  365,  365,
 /*   140 */   365,  271,  276,  330,  270,  324,  347,  346,  335,  334,
 /*   150 */   246,  318,  331,  259,  240,  256,  255,  237,  361,  263,
 /*   160 */   236,  234,  252,  251,  235,  363,  364,  242,  254,  243,
 /*   170 */   244,  241,  258,  362,  238,  239,  264,  245,  340,  314,
 /*   180 */   358,  357,  355,  303,  296,  269,  356,  275,  283,  360,
 /*   190 */   359,  306,  305,  313,  311,  301,  302,  293,  308,  349,
 /*   200 */   295,  304,  317,  348,  350,  260,  247,  343,  344,  342,
 /*   210 */   341,  312,  345,  282,  279,  278,  280,  322,  281,  339,
 /*   220 */   338,  291,  249,  292,  248,  274,  273,  250,  297,  272,
 /*   230 */   253,  309,  277,
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
    const YYNOCODE = 106;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 233;
    const YYNRULE = 132;
    const YYERRORSYMBOL = 66;
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
  'BACKTICK',      'HATCH',         'AT',            'ISODD',       
  'ISNOTODD',      'ISEVEN',        'ISNOTEVEN',     'ISODDBY',     
  'ISNOTODDBY',    'ISEVENBY',      'ISNOTEVENBY',   'ISDIVBY',     
  'ISNOTDIVBY',    'COMMENTSTART',  'COMMENTEND',    'LITERALSTART',
  'LITERALEND',    'LDELIMTAG',     'RDELIMTAG',     'PHP',         
  'PHPSTART',      'PHPEND',        'error',         'start',       
  'template',      'template_element',  'smartytag',     'text',        
  'expr',          'attributes',    'statement',     'modifier',    
  'modparameters',  'ifexprs',       'statements',    'varvar',      
  'foraction',     'value',         'array',         'attribute',   
  'exprs',         'math',          'variable',      'function',    
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
 /*  95 */ "ifexpr ::= ifexprs ISDIVBY ifexprs",
 /*  96 */ "ifexpr ::= ifexprs ISNOTDIVBY ifexprs",
 /*  97 */ "ifexpr ::= ifexprs ISEVEN",
 /*  98 */ "ifexpr ::= ifexprs ISNOTEVEN",
 /*  99 */ "ifexpr ::= ifexprs ISEVENBY ifexprs",
 /* 100 */ "ifexpr ::= ifexprs ISNOTEVENBY ifexprs",
 /* 101 */ "ifexpr ::= ifexprs ISODD",
 /* 102 */ "ifexpr ::= ifexprs ISNOTODD",
 /* 103 */ "ifexpr ::= ifexprs ISODDBY ifexprs",
 /* 104 */ "ifexpr ::= ifexprs ISNOTODDBY ifexprs",
 /* 105 */ "ifcond ::= EQUALS",
 /* 106 */ "ifcond ::= NOTEQUALS",
 /* 107 */ "ifcond ::= GREATERTHAN",
 /* 108 */ "ifcond ::= LESSTHAN",
 /* 109 */ "ifcond ::= GREATEREQUAL",
 /* 110 */ "ifcond ::= LESSEQUAL",
 /* 111 */ "ifcond ::= IDENTITY",
 /* 112 */ "ifcond ::= NONEIDENTITY",
 /* 113 */ "lop ::= LAND",
 /* 114 */ "lop ::= LOR",
 /* 115 */ "array ::= OPENB arrayelements CLOSEB",
 /* 116 */ "arrayelements ::= arrayelement",
 /* 117 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /* 118 */ "arrayelements ::=",
 /* 119 */ "arrayelement ::= expr",
 /* 120 */ "arrayelement ::= expr APTR expr",
 /* 121 */ "arrayelement ::= ID APTR expr",
 /* 122 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 123 */ "doublequoted ::= doublequotedcontent",
 /* 124 */ "doublequotedcontent ::= variable",
 /* 125 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 126 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 127 */ "doublequotedcontent ::= OTHER",
 /* 128 */ "text ::= text textelement",
 /* 129 */ "text ::= textelement",
 /* 130 */ "textelement ::= OTHER",
 /* 131 */ "textelement ::= LDEL",
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
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 6 ),
  array( 'lhs' => 70, 'rhs' => 6 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 5 ),
  array( 'lhs' => 70, 'rhs' => 5 ),
  array( 'lhs' => 70, 'rhs' => 11 ),
  array( 'lhs' => 70, 'rhs' => 8 ),
  array( 'lhs' => 70, 'rhs' => 8 ),
  array( 'lhs' => 80, 'rhs' => 2 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 83, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 4 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 2 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 7 ),
  array( 'lhs' => 81, 'rhs' => 4 ),
  array( 'lhs' => 81, 'rhs' => 8 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 5 ),
  array( 'lhs' => 81, 'rhs' => 6 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 4 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 2 ),
  array( 'lhs' => 92, 'rhs' => 0 ),
  array( 'lhs' => 94, 'rhs' => 2 ),
  array( 'lhs' => 94, 'rhs' => 2 ),
  array( 'lhs' => 94, 'rhs' => 3 ),
  array( 'lhs' => 94, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 4 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 2 ),
  array( 'lhs' => 96, 'rhs' => 3 ),
  array( 'lhs' => 96, 'rhs' => 2 ),
  array( 'lhs' => 87, 'rhs' => 4 ),
  array( 'lhs' => 89, 'rhs' => 4 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 0 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 2 ),
  array( 'lhs' => 76, 'rhs' => 0 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 0 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 2 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        89 => 1,
        123 => 1,
        129 => 1,
        130 => 1,
        131 => 1,
        2 => 2,
        64 => 2,
        122 => 2,
        128 => 2,
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
        119 => 25,
        26 => 26,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        33 => 33,
        35 => 35,
        37 => 37,
        43 => 37,
        45 => 37,
        46 => 37,
        58 => 37,
        59 => 37,
        63 => 37,
        116 => 37,
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
        102 => 97,
        98 => 98,
        101 => 98,
        99 => 99,
        104 => 99,
        100 => 100,
        103 => 100,
        105 => 105,
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
        117 => 117,
        118 => 118,
        120 => 120,
        121 => 121,
        124 => 124,
        125 => 125,
        126 => 126,
        127 => 127,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 72 "internal.templateparser.y"
    function yy_r0(){ $_tmp = '';
                                   if ($this->smarty->direct_access_security){
                                     $_tmp .= "<?php if(!defined('SMARTY_DIR')) exit('no direct access allowed'); ?>";
                                    if ($this->smarty->caching) {
                                     $_tmp.= $this->cacher->processNocacheCode("<?php if(!defined('SMARTY_DIR')) exit('no direct access allowed'); ?>", $this->compiler, true, true);
                                    } }
                                   $this->_retvalue = $_tmp.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1705 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1708 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1711 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1717 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r4(){if ($this->smarty->comment_mode ==0) {
                                                            $this->_retvalue = '';
                                                           }elseif ($this->smarty->comment_mode ==1){
                                                            $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);
                                                           }else{
                                                            $this->_retvalue = $this->cacher->processNocacheCode('<?php /* '.str_replace('*/', '', $this->yystack[$this->yyidx + -1]->minor).'*/?>', $this->compiler,false,false);
                                                           }    }
#line 1726 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1729 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1732 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1735 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1746 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1757 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1760 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1763 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1766 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1769 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1772 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1775 "internal.templateparser.php"
#line 152 "internal.templateparser.y"
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
#line 1790 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1793 "internal.templateparser.php"
#line 168 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1796 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1799 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1802 "internal.templateparser.php"
#line 175 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1805 "internal.templateparser.php"
#line 177 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1808 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1811 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1814 "internal.templateparser.php"
#line 188 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1817 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1820 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1823 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1826 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1829 "internal.templateparser.php"
#line 207 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1832 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
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
#line 1846 "internal.templateparser.php"
#line 225 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1849 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1852 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1855 "internal.templateparser.php"
#line 231 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1858 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1861 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1864 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "''";     }
#line 1867 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1870 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1873 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1876 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r54(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1879 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1882 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1885 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1888 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1891 "internal.templateparser.php"
#line 299 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1895 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1898 "internal.templateparser.php"
#line 312 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1901 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1904 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1907 "internal.templateparser.php"
#line 317 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1910 "internal.templateparser.php"
#line 320 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1913 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1916 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1919 "internal.templateparser.php"
#line 332 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1922 "internal.templateparser.php"
#line 337 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1925 "internal.templateparser.php"
#line 339 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1928 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1931 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1934 "internal.templateparser.php"
#line 346 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1937 "internal.templateparser.php"
#line 351 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1946 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1949 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1952 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1955 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1958 "internal.templateparser.php"
#line 381 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1961 "internal.templateparser.php"
#line 385 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1964 "internal.templateparser.php"
#line 386 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1967 "internal.templateparser.php"
#line 393 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1970 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1973 "internal.templateparser.php"
#line 399 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1976 "internal.templateparser.php"
#line 401 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1979 "internal.templateparser.php"
#line 402 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1982 "internal.templateparser.php"
#line 403 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1985 "internal.templateparser.php"
#line 404 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1988 "internal.templateparser.php"
#line 405 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1991 "internal.templateparser.php"
#line 406 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1994 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = '==';    }
#line 1997 "internal.templateparser.php"
#line 413 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = '!=';    }
#line 2000 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = '>';    }
#line 2003 "internal.templateparser.php"
#line 415 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = '<';    }
#line 2006 "internal.templateparser.php"
#line 416 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = '>=';    }
#line 2009 "internal.templateparser.php"
#line 417 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = '<=';    }
#line 2012 "internal.templateparser.php"
#line 418 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = '===';    }
#line 2015 "internal.templateparser.php"
#line 419 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = '!==';    }
#line 2018 "internal.templateparser.php"
#line 421 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = '&&';    }
#line 2021 "internal.templateparser.php"
#line 422 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = '||';    }
#line 2024 "internal.templateparser.php"
#line 424 "internal.templateparser.y"
    function yy_r115(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2027 "internal.templateparser.php"
#line 426 "internal.templateparser.y"
    function yy_r117(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 2030 "internal.templateparser.php"
#line 427 "internal.templateparser.y"
    function yy_r118(){ return;     }
#line 2033 "internal.templateparser.php"
#line 429 "internal.templateparser.y"
    function yy_r120(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2036 "internal.templateparser.php"
#line 431 "internal.templateparser.y"
    function yy_r121(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2039 "internal.templateparser.php"
#line 435 "internal.templateparser.y"
    function yy_r124(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 2042 "internal.templateparser.php"
#line 436 "internal.templateparser.y"
    function yy_r125(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 2045 "internal.templateparser.php"
#line 437 "internal.templateparser.y"
    function yy_r126(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 2048 "internal.templateparser.php"
#line 438 "internal.templateparser.y"
    function yy_r127(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 2051 "internal.templateparser.php"

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
#line 2168 "internal.templateparser.php"
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
#line 2193 "internal.templateparser.php"
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
