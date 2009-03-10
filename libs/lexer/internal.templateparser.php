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
    const TP_NUMBER                         =  9;
    const TP_MATH                           = 10;
    const TP_UNIMATH                        = 11;
    const TP_INCDEC                         = 12;
    const TP_OPENP                          = 13;
    const TP_CLOSEP                         = 14;
    const TP_OPENB                          = 15;
    const TP_CLOSEB                         = 16;
    const TP_DOLLAR                         = 17;
    const TP_DOT                            = 18;
    const TP_COMMA                          = 19;
    const TP_COLON                          = 20;
    const TP_DOUBLECOLON                    = 21;
    const TP_SEMICOLON                      = 22;
    const TP_VERT                           = 23;
    const TP_EQUAL                          = 24;
    const TP_SPACE                          = 25;
    const TP_PTR                            = 26;
    const TP_APTR                           = 27;
    const TP_ID                             = 28;
    const TP_EQUALS                         = 29;
    const TP_NOTEQUALS                      = 30;
    const TP_GREATERTHAN                    = 31;
    const TP_LESSTHAN                       = 32;
    const TP_GREATEREQUAL                   = 33;
    const TP_LESSEQUAL                      = 34;
    const TP_IDENTITY                       = 35;
    const TP_NONEIDENTITY                   = 36;
    const TP_NOT                            = 37;
    const TP_LAND                           = 38;
    const TP_LOR                            = 39;
    const TP_QUOTE                          = 40;
    const TP_SINGLEQUOTE                    = 41;
    const TP_BOOLEAN                        = 42;
    const TP_NULL                           = 43;
    const TP_IN                             = 44;
    const TP_ANDSYM                         = 45;
    const TP_BACKTICK                       = 46;
    const TP_HATCH                          = 47;
    const TP_AT                             = 48;
    const TP_ISODD                          = 49;
    const TP_ISNOTODD                       = 50;
    const TP_ISEVEN                         = 51;
    const TP_ISNOTEVEN                      = 52;
    const TP_ISODDBY                        = 53;
    const TP_ISNOTODDBY                     = 54;
    const TP_ISEVENBY                       = 55;
    const TP_ISNOTEVENBY                    = 56;
    const TP_ISDIVBY                        = 57;
    const TP_ISNOTDIVBY                     = 58;
    const TP_COMMENTSTART                   = 59;
    const TP_COMMENTEND                     = 60;
    const TP_LITERALSTART                   = 61;
    const TP_LITERALEND                     = 62;
    const TP_LDELIMTAG                      = 63;
    const TP_RDELIMTAG                      = 64;
    const TP_PHPSTART                       = 65;
    const TP_PHPEND                         = 66;
    const YY_NO_ACTION = 369;
    const YY_ACCEPT_ACTION = 368;
    const YY_ERROR_ACTION = 367;

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
    const YY_SZ_ACTTAB = 757;
static public $yy_action = array(
 /*     0 */   211,  157,  161,  202,  126,   15,  133,  203,  181,   62,
 /*    10 */    24,  109,  154,  153,  187,  219,    7,    6,    5,    8,
 /*    20 */     4,    2,  212,  368,   42,  170,  173,  216,  217,  133,
 /*    30 */   165,  133,  166,  194,  157,  161,  169,  224,  228,  229,
 /*    40 */   233,  232,  231,  230,  223,  154,  153,  187,  219,    7,
 /*    50 */     6,    5,    8,    4,    2,  212,   61,  129,  125,  216,
 /*    60 */   217,   55,   33,   51,  133,  184,  185,   52,   29,  133,
 /*    70 */   145,   31,  159,  157,  161,  224,  228,  229,  233,  232,
 /*    80 */   231,  230,  223,   40,  154,  153,  187,  219,    7,    6,
 /*    90 */     5,    8,    4,    2,   33,  197,  157,  161,  148,  165,
 /*   100 */    34,  166,    9,  165,   12,  166,   56,  154,  153,  187,
 /*   110 */   219,    7,    6,    5,    8,    4,    2,  118,   83,  148,
 /*   120 */   110,   34,  136,    9,  156,   12,   10,   54,  199,   35,
 /*   130 */    49,  234,  218,  132,   16,  148,  142,   34,  111,   25,
 /*   140 */    36,   12,   91,   56,  190,  150,  222,   10,   88,   13,
 /*   150 */    35,   49,  234,  218,  118,  207,  103,  142,  199,  165,
 /*   160 */   182,  166,  168,  133,  143,   90,   35,   49,  234,  218,
 /*   170 */   201,  221,   28,  142,  163,  215,  148,   79,   34,  146,
 /*   180 */    25,   21,   12,  193,   56,  150,  172,  173,   18,  133,
 /*   190 */   143,   44,  133,  167,  225,  113,   63,  117,  221,  205,
 /*   200 */   163,  215,   26,   79,  123,  146,  200,   35,   49,  234,
 /*   210 */   218,  150,  109,  148,  142,   34,  214,   25,   21,   12,
 /*   220 */   123,   53,   30,  148,  180,   34,  134,   25,  133,  195,
 /*   230 */   123,   56,   37,  148,  152,   27,   75,   25,  143,   12,
 /*   240 */    94,   56,  116,  200,   35,   49,  234,  218,  127,  131,
 /*   250 */   123,  142,  119,  146,   35,   49,  234,  218,   21,  150,
 /*   260 */    16,  142,   21,  134,   35,   49,  234,  218,   91,  174,
 /*   270 */   133,  142,    1,  124,  148,  198,   34,   20,   25,   26,
 /*   280 */   143,   45,   56,  200,  148,   96,   34,  200,   25,  143,
 /*   290 */   178,   56,   56,  120,  148,  146,  220,  210,   25,  186,
 /*   300 */   189,  150,   56,  112,  146,   35,   49,  234,  218,   87,
 /*   310 */   150,  109,  142,  119,  206,   35,   49,  234,  218,  199,
 /*   320 */    60,  123,  142,   48,   21,   35,   49,  234,  218,  143,
 /*   330 */    44,  101,  142,  143,  177,   72,  188,   86,  220,  163,
 /*   340 */   215,  192,   79,  163,  146,   26,   82,  199,  146,  200,
 /*   350 */   150,  143,  143,   44,  150,  214,  104,   38,   68,   21,
 /*   360 */   204,  163,  163,  215,   80,   79,  146,  146,  158,  134,
 /*   370 */   114,   99,  150,  150,    3,  143,  143,   43,  214,  198,
 /*   380 */    22,   20,   65,   28,  200,  155,  163,  215,  123,   79,
 /*   390 */   146,  146,  156,   47,   57,   56,  150,  150,   97,  143,
 /*   400 */    44,  177,  214,  188,  165,   66,  166,  121,  220,  163,
 /*   410 */   215,  183,   79,  150,  146,   77,  143,   44,  162,  165,
 /*   420 */   150,  166,   73,  160,   60,  214,  163,  215,   98,   79,
 /*   430 */   220,  146,   29,  188,  149,   31,  122,  150,  143,  143,
 /*   440 */    44,  128,  214,  104,  144,   64,  171,   81,  163,  163,
 /*   450 */   215,   74,   79,  146,  146,  204,  143,   44,  191,  150,
 /*   460 */   150,   58,   71,  143,   90,  214,  163,  215,  213,   79,
 /*   470 */    46,  146,  208,  163,  215,  138,   79,  150,  146,   16,
 /*   480 */   174,   19,  214,   16,  150,  220,  196,   91,  143,   44,
 /*   490 */    19,   91,  115,  226,   70,   76,  150,   78,  163,  215,
 /*   500 */   147,   79,   95,  146,  135,  143,   44,  188,   17,  150,
 /*   510 */   141,   69,  175,   16,  214,  163,  215,   11,   79,  176,
 /*   520 */   146,   91,   56,  143,   44,  150,  150,   23,  174,   67,
 /*   530 */   174,  214,  151,  163,  215,   32,   79,  209,  146,  164,
 /*   540 */   143,   85,  204,  123,  150,   14,   50,  179,   39,  214,
 /*   550 */   163,  215,   92,   79,   59,  146,  143,   85,  137,  240,
 /*   560 */   177,  150,  240,  240,  240,  240,  163,  215,  240,   79,
 /*   570 */   240,  146,  143,   41,  140,  130,  240,  150,  240,  143,
 /*   580 */    85,  240,  163,  215,  240,   79,  240,  146,  240,  163,
 /*   590 */   215,  240,   79,  150,  146,  240,  240,  227,  143,   85,
 /*   600 */   150,  240,  240,  240,  240,  240,  240,  240,  163,  215,
 /*   610 */   240,   79,  240,  146,  143,  107,  139,  240,  240,  150,
 /*   620 */   240,  240,  240,  240,  163,  215,  240,   79,  240,  146,
 /*   630 */   143,   84,  240,  240,  240,  150,  240,  240,  240,  240,
 /*   640 */   163,  215,  240,   79,  240,  146,  143,  105,  240,  240,
 /*   650 */   240,  150,  240,  143,   93,  240,  163,  215,  240,   79,
 /*   660 */   240,  146,  240,  163,  215,  240,   79,  150,  146,  240,
 /*   670 */   240,  143,  108,  240,  150,  240,  240,  240,  240,  240,
 /*   680 */   240,  163,  215,  240,   79,  240,  146,  143,  102,  240,
 /*   690 */   240,  240,  150,  240,  240,  240,  240,  163,  215,  240,
 /*   700 */    79,  240,  146,  143,  100,  240,  240,  240,  150,  240,
 /*   710 */   240,  240,  240,  163,  215,  240,   79,  240,  146,  143,
 /*   720 */    89,  240,  240,  240,  150,  240,  240,  240,  240,  163,
 /*   730 */   215,  240,   79,  240,  146,  143,  106,  240,  240,  240,
 /*   740 */   150,  240,  240,  240,  240,  163,  215,  240,   79,  240,
 /*   750 */   146,  240,  240,  240,  240,  240,  150,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,   38,   39,    1,    2,    3,   23,    5,    6,    7,
 /*    10 */    27,   77,   49,   50,   51,   52,   53,   54,   55,   56,
 /*    20 */    57,   58,   14,   68,   69,   70,   71,   10,   11,   23,
 /*    30 */     1,   23,    3,   16,   38,   39,    4,   29,   30,   31,
 /*    40 */    32,   33,   34,   35,   36,   49,   50,   51,   52,   53,
 /*    50 */    54,   55,   56,   57,   58,   14,   14,   22,   26,   10,
 /*    60 */    11,   59,   45,   61,   23,   63,   64,   65,   15,   23,
 /*    70 */    41,   18,   14,   38,   39,   29,   30,   31,   32,   33,
 /*    80 */    34,   35,   36,   78,   49,   50,   51,   52,   53,   54,
 /*    90 */    55,   56,   57,   58,   45,   14,   38,   39,    9,    1,
 /*   100 */    11,    3,   13,    1,   15,    3,   17,   49,   50,   51,
 /*   110 */    52,   53,   54,   55,   56,   57,   58,   28,   75,    9,
 /*   120 */    77,   11,   17,   13,   73,   15,   37,   17,   85,   40,
 /*   130 */    41,   42,   43,   28,   13,    9,   47,   11,   28,   13,
 /*   140 */    89,   15,   21,   17,    4,   94,   16,   37,   75,   19,
 /*   150 */    40,   41,   42,   43,   28,  104,   78,   47,   85,    1,
 /*   160 */    62,    3,   60,   23,   73,   74,   40,   41,   42,   43,
 /*   170 */     4,   98,   20,   47,   83,   84,    9,   86,   11,   88,
 /*   180 */    13,    3,   15,    4,   17,   94,   70,   71,   19,   23,
 /*   190 */    73,   74,   23,   76,  103,   28,   79,   80,   98,    4,
 /*   200 */    83,   84,   24,   86,   25,   88,   28,   40,   41,   42,
 /*   210 */    43,   94,   77,    9,   47,   11,   99,   13,    3,   15,
 /*   220 */    25,   17,   44,    9,   66,   11,   48,   13,   23,    4,
 /*   230 */    25,   17,   28,    9,   46,  100,   72,   13,   73,   15,
 /*   240 */    28,   17,   28,   28,   40,   41,   42,   43,   83,   84,
 /*   250 */    25,   47,   28,   88,   40,   41,   42,   43,    3,   94,
 /*   260 */    13,   47,    3,   48,   40,   41,   42,   43,   21,  105,
 /*   270 */    23,   47,   25,   26,    9,    1,   11,    3,   13,   24,
 /*   280 */    73,   81,   17,   28,    9,   28,   11,   28,   13,   73,
 /*   290 */    83,   17,   17,   28,    9,   88,   96,   14,   13,   83,
 /*   300 */     4,   94,   17,   28,   88,   40,   41,   42,   43,   75,
 /*   310 */    94,   77,   47,   28,   40,   40,   41,   42,   43,   85,
 /*   320 */    46,   25,   47,   81,    3,   40,   41,   42,   43,   73,
 /*   330 */    74,   92,   47,   73,   95,   79,   97,   75,   96,   83,
 /*   340 */    84,   90,   86,   83,   88,   24,   86,   85,   88,   28,
 /*   350 */    94,   73,   73,   74,   94,   99,   26,   93,   79,    3,
 /*   360 */    96,   83,   83,   84,   86,   86,   88,   88,   12,   48,
 /*   370 */    28,   19,   94,   94,   22,   73,   73,   74,   99,    1,
 /*   380 */    24,    3,   79,   20,   28,   83,   83,   84,   25,   86,
 /*   390 */    88,   88,   73,   81,   17,   17,   94,   94,   92,   73,
 /*   400 */    74,   95,   99,   97,    1,   79,    3,   28,   96,   83,
 /*   410 */    84,    4,   86,   94,   88,   81,   73,   74,   40,    1,
 /*   420 */    94,    3,   79,  104,   46,   99,   83,   84,   92,   86,
 /*   430 */    96,   88,   15,   97,    4,   18,   28,   94,   73,   73,
 /*   440 */    74,   82,   99,   26,   41,   79,    4,   72,   83,   83,
 /*   450 */    84,   86,   86,   88,   88,   96,   73,   74,    4,   94,
 /*   460 */    94,   28,   79,   73,   74,   99,   83,   84,   28,   86,
 /*   470 */    81,   88,   28,   83,   84,   73,   86,   94,   88,   13,
 /*   480 */   105,   24,   99,   13,   94,   96,   16,   21,   73,   74,
 /*   490 */    24,   21,  102,  103,   79,   72,   94,   72,   83,   84,
 /*   500 */    47,   86,   92,   88,   73,   73,   74,   97,   13,   94,
 /*   510 */    28,   79,    4,   13,   99,   83,   84,  101,   86,    8,
 /*   520 */    88,   21,   17,   73,   74,   94,   94,   27,  105,   79,
 /*   530 */   105,   99,   97,   83,   84,   87,   86,   85,   88,  105,
 /*   540 */    73,   74,   96,   25,   94,   13,   17,   76,   93,   99,
 /*   550 */    83,   84,   93,   86,   90,   88,   73,   74,   91,  106,
 /*   560 */    95,   94,  106,  106,  106,  106,   83,   84,  106,   86,
 /*   570 */   106,   88,   73,   74,   91,   76,  106,   94,  106,   73,
 /*   580 */    74,  106,   83,   84,  106,   86,  106,   88,  106,   83,
 /*   590 */    84,  106,   86,   94,   88,  106,  106,   91,   73,   74,
 /*   600 */    94,  106,  106,  106,  106,  106,  106,  106,   83,   84,
 /*   610 */   106,   86,  106,   88,   73,   74,   91,  106,  106,   94,
 /*   620 */   106,  106,  106,  106,   83,   84,  106,   86,  106,   88,
 /*   630 */    73,   74,  106,  106,  106,   94,  106,  106,  106,  106,
 /*   640 */    83,   84,  106,   86,  106,   88,   73,   74,  106,  106,
 /*   650 */   106,   94,  106,   73,   74,  106,   83,   84,  106,   86,
 /*   660 */   106,   88,  106,   83,   84,  106,   86,   94,   88,  106,
 /*   670 */   106,   73,   74,  106,   94,  106,  106,  106,  106,  106,
 /*   680 */   106,   83,   84,  106,   86,  106,   88,   73,   74,  106,
 /*   690 */   106,  106,   94,  106,  106,  106,  106,   83,   84,  106,
 /*   700 */    86,  106,   88,   73,   74,  106,  106,  106,   94,  106,
 /*   710 */   106,  106,  106,   83,   84,  106,   86,  106,   88,   73,
 /*   720 */    74,  106,  106,  106,   94,  106,  106,  106,  106,   83,
 /*   730 */    84,  106,   86,  106,   88,   73,   74,  106,  106,  106,
 /*   740 */    94,  106,  106,  106,  106,   83,   84,  106,   86,  106,
 /*   750 */    88,  106,  106,  106,  106,  106,   94,
);
    const YY_SHIFT_USE_DFLT = -38;
    const YY_SHIFT_MAX = 142;
    static public $yy_shift_ofst = array(
 /*     0 */     2,  110,   89,   89,   89,   89,   89,   89,   89,   89,
 /*    10 */    89,   89,  167,  167,  126,  204,  126,  126,  126,  126,
 /*    20 */   126,  126,  126,  126,  126,  126,  126,  126,  265,  275,
 /*    30 */   224,  214,  285,  285,  285,  274,  378,  247,  417,  417,
 /*    40 */   363,  205,    2,    8,   46,  178,  356,  321,  215,  403,
 /*    50 */   259,  418,  418,  259,  259,  418,  259,  259,  518,  330,
 /*    60 */   505,  330,  505,   -4,   35,   58,  -37,  -37,  -37,  -37,
 /*    70 */   -37,  -37,  -37,  -37,   17,  102,   98,  255,  158,   49,
 /*    80 */    49,   29,   49,  296,  140,  169,  179,  195,  225,  166,
 /*    90 */   -17,  105,   53,   41,  495,  330,  532,  330,  330,  529,
 /*   100 */     6,  330,    6,  152,  212,    6,    6,    6,    6,  -38,
 /*   110 */   -38,  466,  470,  500,   32,  130,  121,  352,  121,  121,
 /*   120 */   121,  442,  457,  408,  433,  379,  342,  407,  430,  377,
 /*   130 */   454,  508,  495,  440,  444,  511,  257,   42,  188,   81,
 /*   140 */   283,  453,  482,
);
    const YY_REDUCE_USE_DFLT = -67;
    const YY_REDUCE_MAX = 110;
    static public $yy_reduce_ofst = array(
 /*     0 */   -45,  117,  279,  366,  326,  256,  383,  450,  415,  303,
 /*    10 */   432,  343,  390,   91,  467,  499,  483,  525,  506,  614,
 /*    20 */   557,  646,  630,  541,  598,  580,  573,  662,  260,  365,
 /*    30 */   165,  278,  302,  207,  216,   51,  319,   43,  239,  306,
 /*    40 */    73,  234,  116,  135,  135,  264,  359,  264,  264,  375,
 /*    50 */   334,  423,  425,  312,  200,  164,  242,  389,  262,  336,
 /*    60 */   402,  410,  431,  416,  416,  416,  416,  416,  416,  416,
 /*    70 */   416,  416,  416,  416,  448,  434,  434,  446,  434,  448,
 /*    80 */   448,  434,  448,  452,  -66,  -66,  452,  452,  452,  -66,
 /*    90 */   -66,  464,  465,  -66,  459,  435,  455,  435,  435,  471,
 /*   100 */   -66,  435,  -66,  100,  251,  -66,  -66,  -66,  -66,   78,
 /*   110 */     5,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 3, 5, 6, 7, 59, 61, 63, 64, 65, ),
        /* 1 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 2 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 3 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 4 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 5 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 6 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 7 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 8 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 9 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 10 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 11 */ array(9, 11, 13, 15, 17, 28, 37, 40, 41, 42, 43, 47, ),
        /* 12 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 13 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 14 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 15 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 16 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 17 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 18 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 19 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 20 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 21 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 22 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 23 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 24 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 25 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 26 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 27 */ array(9, 11, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 28 */ array(9, 11, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 29 */ array(9, 11, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 30 */ array(9, 13, 15, 17, 28, 40, 41, 42, 43, 47, ),
        /* 31 */ array(9, 11, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 32 */ array(9, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 33 */ array(9, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 34 */ array(9, 13, 17, 28, 40, 41, 42, 43, 47, ),
        /* 35 */ array(1, 3, 17, 40, 46, ),
        /* 36 */ array(1, 3, 17, 40, 46, ),
        /* 37 */ array(13, 21, 23, 25, 26, ),
        /* 38 */ array(15, 18, 26, ),
        /* 39 */ array(15, 18, 26, ),
        /* 40 */ array(20, 25, ),
        /* 41 */ array(23, 25, ),
        /* 42 */ array(1, 2, 3, 5, 6, 7, 59, 61, 63, 64, 65, ),
        /* 43 */ array(14, 23, 29, 30, 31, 32, 33, 34, 35, 36, ),
        /* 44 */ array(23, 29, 30, 31, 32, 33, 34, 35, 36, ),
        /* 45 */ array(3, 24, 28, 44, 48, ),
        /* 46 */ array(3, 12, 24, 28, ),
        /* 47 */ array(3, 24, 28, 48, ),
        /* 48 */ array(3, 28, 48, ),
        /* 49 */ array(1, 3, 41, ),
        /* 50 */ array(3, 28, ),
        /* 51 */ array(1, 3, ),
        /* 52 */ array(1, 3, ),
        /* 53 */ array(3, 28, ),
        /* 54 */ array(3, 28, ),
        /* 55 */ array(1, 3, ),
        /* 56 */ array(3, 28, ),
        /* 57 */ array(3, 28, ),
        /* 58 */ array(25, ),
        /* 59 */ array(26, ),
        /* 60 */ array(17, ),
        /* 61 */ array(26, ),
        /* 62 */ array(17, ),
        /* 63 */ array(4, 38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 64 */ array(22, 38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 65 */ array(14, 38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 66 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 67 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 68 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 69 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 70 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 71 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 72 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 73 */ array(38, 39, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, ),
        /* 74 */ array(10, 11, 16, 45, ),
        /* 75 */ array(1, 3, 60, ),
        /* 76 */ array(1, 3, 62, ),
        /* 77 */ array(3, 24, 28, ),
        /* 78 */ array(1, 3, 66, ),
        /* 79 */ array(10, 11, 45, ),
        /* 80 */ array(10, 11, 45, ),
        /* 81 */ array(1, 3, 41, ),
        /* 82 */ array(10, 11, 45, ),
        /* 83 */ array(4, 25, ),
        /* 84 */ array(4, 23, ),
        /* 85 */ array(19, 23, ),
        /* 86 */ array(4, 25, ),
        /* 87 */ array(4, 25, ),
        /* 88 */ array(4, 25, ),
        /* 89 */ array(4, 23, ),
        /* 90 */ array(23, 27, ),
        /* 91 */ array(17, 28, ),
        /* 92 */ array(15, 18, ),
        /* 93 */ array(14, 23, ),
        /* 94 */ array(13, ),
        /* 95 */ array(26, ),
        /* 96 */ array(13, ),
        /* 97 */ array(26, ),
        /* 98 */ array(26, ),
        /* 99 */ array(17, ),
        /* 100 */ array(23, ),
        /* 101 */ array(26, ),
        /* 102 */ array(23, ),
        /* 103 */ array(20, ),
        /* 104 */ array(28, ),
        /* 105 */ array(23, ),
        /* 106 */ array(23, ),
        /* 107 */ array(23, ),
        /* 108 */ array(23, ),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(13, 21, 24, ),
        /* 112 */ array(13, 16, 21, ),
        /* 113 */ array(13, 21, 27, ),
        /* 114 */ array(4, 26, ),
        /* 115 */ array(16, 19, ),
        /* 116 */ array(13, 21, ),
        /* 117 */ array(19, 22, ),
        /* 118 */ array(13, 21, ),
        /* 119 */ array(13, 21, ),
        /* 120 */ array(13, 21, ),
        /* 121 */ array(4, ),
        /* 122 */ array(24, ),
        /* 123 */ array(28, ),
        /* 124 */ array(28, ),
        /* 125 */ array(28, ),
        /* 126 */ array(28, ),
        /* 127 */ array(4, ),
        /* 128 */ array(4, ),
        /* 129 */ array(17, ),
        /* 130 */ array(4, ),
        /* 131 */ array(4, ),
        /* 132 */ array(13, ),
        /* 133 */ array(28, ),
        /* 134 */ array(28, ),
        /* 135 */ array(8, ),
        /* 136 */ array(28, ),
        /* 137 */ array(14, ),
        /* 138 */ array(46, ),
        /* 139 */ array(14, ),
        /* 140 */ array(14, ),
        /* 141 */ array(47, ),
        /* 142 */ array(28, ),
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
        /* 233 */ array(),
        /* 234 */ array(),
);
    static public $yy_default = array(
 /*     0 */   367,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*    10 */   367,  367,  353,  367,  318,  367,  318,  318,  318,  367,
 /*    20 */   367,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*    30 */   367,  367,  367,  367,  367,  367,  367,  263,  296,  291,
 /*    40 */   263,  263,  235,  327,  327,  300,  367,  300,  300,  367,
 /*    50 */   367,  367,  367,  367,  367,  367,  367,  367,  263,  286,
 /*    60 */   367,  287,  367,  367,  367,  367,  330,  338,  331,  325,
 /*    70 */   335,  339,  334,  329,  367,  367,  367,  367,  367,  269,
 /*    80 */   302,  367,  323,  367,  367,  317,  367,  367,  367,  367,
 /*    90 */   354,  367,  312,  367,  300,  289,  300,  292,  288,  367,
 /*   100 */   259,  309,  264,  270,  367,  267,  328,  356,  355,  321,
 /*   110 */   321,  268,  367,  268,  367,  367,  301,  367,  268,  367,
 /*   120 */   322,  367,  367,  367,  367,  367,  367,  367,  367,  367,
 /*   130 */   367,  367,  290,  367,  367,  367,  367,  367,  367,  367,
 /*   140 */   367,  367,  367,  278,  283,  282,  281,  279,  280,  256,
 /*   150 */   298,  311,  360,  337,  336,  274,  359,  348,  260,  326,
 /*   160 */   357,  349,  284,  272,  363,  365,  366,  265,  239,  253,
 /*   170 */   236,  254,  237,  238,  364,  258,  245,  299,  275,  266,
 /*   180 */   244,  243,  240,  257,  241,  242,  273,  332,  310,  250,
 /*   190 */   361,  249,  313,  251,  304,  252,  303,  315,  362,  262,
 /*   200 */   307,  308,  247,  246,  306,  248,  285,  358,  297,  261,
 /*   210 */   314,  255,  295,  319,  324,  271,  277,  276,  294,  333,
 /*   220 */   305,  320,  350,  347,  340,  352,  351,  316,  341,  342,
 /*   230 */   346,  345,  344,  343,  293,
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
    const YYNOCODE = 107;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 235;
    const YYNRULE = 132;
    const YYERRORSYMBOL = 67;
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
  'RDEL',          'XML',           'PHP',           'SHORTTAGSTART',
  'SHORTTAGEND',   'NUMBER',        'MATH',          'UNIMATH',     
  'INCDEC',        'OPENP',         'CLOSEP',        'OPENB',       
  'CLOSEB',        'DOLLAR',        'DOT',           'COMMA',       
  'COLON',         'DOUBLECOLON',   'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'EQUALS',        'NOTEQUALS',     'GREATERTHAN', 
  'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',     'IDENTITY',    
  'NONEIDENTITY',  'NOT',           'LAND',          'LOR',         
  'QUOTE',         'SINGLEQUOTE',   'BOOLEAN',       'NULL',        
  'IN',            'ANDSYM',        'BACKTICK',      'HATCH',       
  'AT',            'ISODD',         'ISNOTODD',      'ISEVEN',      
  'ISNOTEVEN',     'ISODDBY',       'ISNOTODDBY',    'ISEVENBY',    
  'ISNOTEVENBY',   'ISDIVBY',       'ISNOTDIVBY',    'COMMENTSTART',
  'COMMENTEND',    'LITERALSTART',  'LITERALEND',    'LDELIMTAG',   
  'RDELIMTAG',     'PHPSTART',      'PHPEND',        'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'text',          'variable',      'expr',          'attributes',  
  'statement',     'modifier',      'modparameters',  'ifexprs',     
  'statements',    'varvar',        'foraction',     'value',       
  'array',         'attribute',     'exprs',         'math',        
  'function',      'doublequoted',  'method',        'params',      
  'objectchain',   'vararraydefs',  'object',        'vararraydef', 
  'varvarele',     'objectelement',  'modparameter',  'ifexpr',      
  'ifcond',        'lop',           'arrayelements',  'arrayelement',
  'doublequotedcontent',  'textelement', 
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
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 6 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 5 ),
  array( 'lhs' => 71, 'rhs' => 11 ),
  array( 'lhs' => 71, 'rhs' => 8 ),
  array( 'lhs' => 71, 'rhs' => 8 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 2 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 0 ),
  array( 'lhs' => 85, 'rhs' => 4 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 4 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 2 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 7 ),
  array( 'lhs' => 83, 'rhs' => 4 ),
  array( 'lhs' => 83, 'rhs' => 8 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 5 ),
  array( 'lhs' => 83, 'rhs' => 6 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 2 ),
  array( 'lhs' => 93, 'rhs' => 0 ),
  array( 'lhs' => 95, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 2 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 2 ),
  array( 'lhs' => 96, 'rhs' => 1 ),
  array( 'lhs' => 96, 'rhs' => 3 ),
  array( 'lhs' => 94, 'rhs' => 4 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 2 ),
  array( 'lhs' => 97, 'rhs' => 3 ),
  array( 'lhs' => 97, 'rhs' => 2 ),
  array( 'lhs' => 88, 'rhs' => 4 ),
  array( 'lhs' => 90, 'rhs' => 4 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 0 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 98, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 2 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 3 ),
  array( 'lhs' => 102, 'rhs' => 0 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 2 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
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
#line 1716 "internal.templateparser.php"
#line 84 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1719 "internal.templateparser.php"
#line 86 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1722 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1728 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '';    }
#line 1731 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1734 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1737 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1740 "internal.templateparser.php"
#line 114 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security) { 
                                       $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                       $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                       $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                       $this->_retvalue = '';
                                      }	    }
#line 1751 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".$this->yystack[$this->yyidx + -1]->minor." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1762 "internal.templateparser.php"
#line 133 "internal.templateparser.y"
    function yy_r10(){if (!$this->template->security) { 
                                        $this->_retvalue = $this->cacher->processNocacheCode($this->compiler->compileTag('print_expression',array('value'=>$this->yystack[$this->yyidx + -1]->minor)), $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                        $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.t.' ?>', ENT_QUOTES), $this->compiler, false, false);	
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_PASSTHRU || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) {
                                        $this->_retvalue = $this->cacher->processNocacheCode("<?php echo '<?php ".t." ?>';?>\n", $this->compiler, false, false);
                                      }elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_REMOVE) {
                                        $this->_retvalue = '';
                                      }	    }
#line 1773 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>\n", $this->compiler, true, true);    }
#line 1776 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r12(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1779 "internal.templateparser.php"
#line 156 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1782 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1785 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1788 "internal.templateparser.php"
#line 162 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1791 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
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
#line 1806 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1809 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1812 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('if condition'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1815 "internal.templateparser.php"
#line 184 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1818 "internal.templateparser.php"
#line 187 "internal.templateparser.y"
    function yy_r22(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1821 "internal.templateparser.php"
#line 189 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1824 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1827 "internal.templateparser.php"
#line 196 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1830 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array();    }
#line 1833 "internal.templateparser.php"
#line 204 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1836 "internal.templateparser.php"
#line 209 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1839 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1842 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1845 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1848 "internal.templateparser.php"
#line 223 "internal.templateparser.y"
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
#line 1862 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1865 "internal.templateparser.php"
#line 240 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1868 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1871 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '('. $this->yystack[$this->yyidx + -2]->minor . ').(' . $this->yystack[$this->yyidx + 0]->minor. ')';     }
#line 1874 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1877 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1880 "internal.templateparser.php"
#line 280 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = "''";     }
#line 1883 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1886 "internal.templateparser.php"
#line 287 "internal.templateparser.y"
    function yy_r52(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1889 "internal.templateparser.php"
#line 289 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1892 "internal.templateparser.php"
#line 290 "internal.templateparser.y"
    function yy_r54(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1895 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1898 "internal.templateparser.php"
#line 294 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1901 "internal.templateparser.php"
#line 296 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1904 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1907 "internal.templateparser.php"
#line 312 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1911 "internal.templateparser.php"
#line 315 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1914 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1917 "internal.templateparser.php"
#line 327 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1920 "internal.templateparser.php"
#line 328 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1923 "internal.templateparser.php"
#line 330 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = '['.$this->compiler->compileTag('smarty','[\'section\'][\''.$this->yystack[$this->yyidx + -1]->minor.'\'][\'index\']').']';    }
#line 1926 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1929 "internal.templateparser.php"
#line 341 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1932 "internal.templateparser.php"
#line 343 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1935 "internal.templateparser.php"
#line 345 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1938 "internal.templateparser.php"
#line 350 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1941 "internal.templateparser.php"
#line 352 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1944 "internal.templateparser.php"
#line 354 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1947 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1950 "internal.templateparser.php"
#line 359 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1953 "internal.templateparser.php"
#line 364 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1962 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1965 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1968 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1971 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1974 "internal.templateparser.php"
#line 394 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1977 "internal.templateparser.php"
#line 398 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor.'';    }
#line 1980 "internal.templateparser.php"
#line 399 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1983 "internal.templateparser.php"
#line 406 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1986 "internal.templateparser.php"
#line 411 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1989 "internal.templateparser.php"
#line 412 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1992 "internal.templateparser.php"
#line 414 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1995 "internal.templateparser.php"
#line 415 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -2]->minor.' % '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 1998 "internal.templateparser.php"
#line 416 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2001 "internal.templateparser.php"
#line 417 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2004 "internal.templateparser.php"
#line 418 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '!(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2007 "internal.templateparser.php"
#line 419 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '(1 & '.$this->yystack[$this->yyidx + -2]->minor.' / '.$this->yystack[$this->yyidx + 0]->minor.')';    }
#line 2010 "internal.templateparser.php"
#line 425 "internal.templateparser.y"
    function yy_r105(){$this->_retvalue = '==';    }
#line 2013 "internal.templateparser.php"
#line 426 "internal.templateparser.y"
    function yy_r106(){$this->_retvalue = '!=';    }
#line 2016 "internal.templateparser.php"
#line 427 "internal.templateparser.y"
    function yy_r107(){$this->_retvalue = '>';    }
#line 2019 "internal.templateparser.php"
#line 428 "internal.templateparser.y"
    function yy_r108(){$this->_retvalue = '<';    }
#line 2022 "internal.templateparser.php"
#line 429 "internal.templateparser.y"
    function yy_r109(){$this->_retvalue = '>=';    }
#line 2025 "internal.templateparser.php"
#line 430 "internal.templateparser.y"
    function yy_r110(){$this->_retvalue = '<=';    }
#line 2028 "internal.templateparser.php"
#line 431 "internal.templateparser.y"
    function yy_r111(){$this->_retvalue = '===';    }
#line 2031 "internal.templateparser.php"
#line 432 "internal.templateparser.y"
    function yy_r112(){$this->_retvalue = '!==';    }
#line 2034 "internal.templateparser.php"
#line 434 "internal.templateparser.y"
    function yy_r113(){$this->_retvalue = '&&';    }
#line 2037 "internal.templateparser.php"
#line 435 "internal.templateparser.y"
    function yy_r114(){$this->_retvalue = '||';    }
#line 2040 "internal.templateparser.php"
#line 437 "internal.templateparser.y"
    function yy_r115(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 2043 "internal.templateparser.php"
#line 439 "internal.templateparser.y"
    function yy_r117(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 2046 "internal.templateparser.php"
#line 440 "internal.templateparser.y"
    function yy_r118(){ return;     }
#line 2049 "internal.templateparser.php"
#line 442 "internal.templateparser.y"
    function yy_r120(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2052 "internal.templateparser.php"
#line 444 "internal.templateparser.y"
    function yy_r121(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 2055 "internal.templateparser.php"
#line 448 "internal.templateparser.y"
    function yy_r124(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 2058 "internal.templateparser.php"
#line 449 "internal.templateparser.y"
    function yy_r125(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 2061 "internal.templateparser.php"
#line 450 "internal.templateparser.y"
    function yy_r126(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 2064 "internal.templateparser.php"
#line 451 "internal.templateparser.y"
    function yy_r127(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 2067 "internal.templateparser.php"

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
#line 2184 "internal.templateparser.php"
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
#line 2209 "internal.templateparser.php"
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
