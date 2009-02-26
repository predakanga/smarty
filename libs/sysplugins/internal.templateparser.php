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
    const YY_NO_ACTION = 343;
    const YY_ACCEPT_ACTION = 342;
    const YY_ERROR_ACTION = 341;

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
 /*     0 */   152,   14,   25,   59,    2,  191,    6,   10,   45,   32,
 /*    10 */   212,  152,  201,   25,  117,    2,  169,    6,   93,   48,
 /*    20 */   144,   33,   14,  215,  210,  103,  191,    5,  186,   86,
 /*    30 */    28,   40,  161,  162,   18,  120,  171,  115,    5,   99,
 /*    40 */   185,   28,   40,  161,  162,  152,  103,   25,  115,   22,
 /*    50 */   189,    6,  186,   45,  152,   18,   25,   27,   22,   30,
 /*    60 */     6,  176,   45,   87,  185,  152,   14,   25,  170,   22,
 /*    70 */   191,    6,   93,   42,  199,   28,   40,  161,  162,  174,
 /*    80 */   175,  191,  115,   29,   28,   40,  161,  162,  155,  132,
 /*    90 */   131,  115,  209,  130,  153,   28,   40,  161,  162,   18,
 /*   100 */   185,  103,  115,  152,   16,   25,   85,   22,  191,    6,
 /*   110 */    18,   45,  152,  169,   25,   34,   22,   26,    6,  155,
 /*   120 */    45,   94,   63,  158,   21,  153,  215,  210,  110,  200,
 /*   130 */    92,  185,  211,   28,   40,  161,  162,   18,  178,  122,
 /*   140 */   115,   47,   28,   40,  161,  162,   56,   24,  137,  115,
 /*   150 */    23,   62,   95,  188,  136,  155,  164,   82,   38,  158,
 /*   160 */    27,  153,  152,  181,   25,  170,   22,  185,    6,   10,
 /*   170 */    45,   10,  139,  152,  135,   25,  117,   22,  117,   15,
 /*   180 */    89,   45,   49,   60,  177,  182,  183,   43,   54,  180,
 /*   190 */     9,   90,   28,   40,  161,  162,  155,  132,  131,  115,
 /*   200 */   156,  113,  153,   28,   40,  161,  162,    6,  185,   45,
 /*   210 */   115,  152,  126,   25,   10,   22,  171,  132,  131,   45,
 /*   220 */   152,  117,   77,  120,   22,    1,  112,  193,   45,   88,
 /*   230 */   110,  342,   35,  124,  175,  109,  123,  100,   93,  104,
 /*   240 */    45,   28,   40,  161,  162,  169,   53,  185,  115,  119,
 /*   250 */    28,   40,  161,  162,   56,  217,   75,  115,    3,   71,
 /*   260 */   215,  210,  160,  155,  164,  193,   38,  158,   51,  153,
 /*   270 */   155,  184,   78,  110,  165,  185,  153,  202,   45,   13,
 /*   280 */   139,  154,  185,    7,  150,  142,  141,  129,  145,  149,
 /*   290 */   148,  147,  140,  208,   27,   69,   11,  170,  155,  108,
 /*   300 */   194,   57,  158,  169,  153,  195,   51,  173,  155,  164,
 /*   310 */   185,   38,  158,   15,  153,  132,  131,   13,  169,  166,
 /*   320 */   185,   83,   81,  169,  157,   56,  202,  202,   96,  203,
 /*   330 */    68,   70,  191,   84,  155,  164,   10,   38,  158,   24,
 /*   340 */   153,  195,   23,  117,  128,   79,  185,   56,  157,   19,
 /*   350 */   202,  139,   64,  179,  159,  170,  155,  164,  125,   38,
 /*   360 */   158,   18,  153,   74,  216,   97,  167,   39,  185,   55,
 /*   370 */   170,  110,   41,  139,   65,  170,  110,   37,  155,  164,
 /*   380 */   201,   38,  158,  190,  153,  105,  184,   58,  190,  107,
 /*   390 */   185,  214,  138,  190,   91,  139,  107,  185,  155,  164,
 /*   400 */    36,   38,  158,  190,  153,  155,  164,  121,   38,  158,
 /*   410 */   185,  153,   66,  192,  101,   61,  190,  185,  114,  163,
 /*   420 */   107,  142,  141,  129,  145,  149,  148,  147,  140,  155,
 /*   430 */   164,   12,   38,  158,  107,  153,   80,  187,  213,   20,
 /*   440 */    67,  185,  127,  155,  164,  171,   38,  158,  171,  153,
 /*   450 */   195,   50,   98,  111,   44,  185,  196,  118,   73,  151,
 /*   460 */   155,  164,    8,   38,  158,  108,  153,   52,  195,  168,
 /*   470 */     4,   46,  185,  172,  155,  164,   26,   38,  158,  110,
 /*   480 */   153,  116,  157,   45,   82,  133,  185,  201,  207,   76,
 /*   490 */   155,  164,  166,   38,  158,  206,  153,  155,  164,   21,
 /*   500 */    38,  158,  185,  153,  134,   17,  198,   31,  218,  185,
 /*   510 */    72,  218,  218,  155,  164,  218,   38,  158,  218,  153,
 /*   520 */   218,  204,  218,  218,  218,  185,  218,  218,  218,  218,
 /*   530 */   155,  164,  218,   38,  158,  197,  153,  218,  218,  218,
 /*   540 */   218,  218,  185,  218,  155,  164,  218,   38,  158,  102,
 /*   550 */   153,  218,  218,  218,  218,  218,  185,  218,  155,  164,
 /*   560 */   218,   38,  158,  143,  153,  218,  218,  218,  218,  218,
 /*   570 */   185,  218,  155,  164,  218,   38,  158,  205,  153,  218,
 /*   580 */   218,  218,  218,  218,  185,  218,  155,  164,  218,   38,
 /*   590 */   158,  106,  153,  218,  218,  218,  218,  218,  185,  218,
 /*   600 */   155,  164,  218,   38,  158,  146,  153,  218,  218,  218,
 /*   610 */   218,  218,  185,  218,  155,  164,  218,   38,  158,  218,
 /*   620 */   153,  218,  218,  218,  218,  218,  185,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   20,    8,   59,   10,   24,   12,   10,   14,   80,
 /*    10 */    13,    6,   83,    8,   17,   10,    1,   12,   24,   14,
 /*    20 */     3,   40,   20,    7,    8,   44,   24,   33,   69,   24,
 /*    30 */    36,   37,   38,   39,   53,   19,   92,   43,   33,   22,
 /*    40 */    81,   36,   37,   38,   39,    6,   44,    8,   43,   10,
 /*    50 */    91,   12,   69,   14,    6,   53,    8,   41,   10,   76,
 /*    60 */    12,   46,   14,   24,   81,    6,   20,    8,   53,   10,
 /*    70 */    24,   12,   24,   14,   91,   36,   37,   38,   39,   57,
 /*    80 */    58,   24,   43,   24,   36,   37,   38,   39,   69,   34,
 /*    90 */    35,   43,   73,    9,   75,   36,   37,   38,   39,   53,
 /*   100 */    81,   44,   43,    6,   20,    8,   63,   10,   24,   12,
 /*   110 */    53,   14,    6,    1,    8,   64,   10,   74,   12,   69,
 /*   120 */    14,   24,   72,   73,   17,   75,    7,    8,   21,   11,
 /*   130 */    24,   81,   13,   36,   37,   38,   39,   53,    1,    2,
 /*   140 */    43,    4,   36,   37,   38,   39,   60,   12,   62,   43,
 /*   150 */    15,   65,   66,    3,    3,   69,   70,   22,   72,   73,
 /*   160 */    41,   75,    6,   51,    8,   53,   10,   81,   12,   10,
 /*   170 */    14,   10,   86,    6,   11,    8,   17,   10,   17,   20,
 /*   180 */    24,   14,   45,   59,   47,   48,   49,   50,   11,   52,
 /*   190 */    53,   24,   36,   37,   38,   39,   69,   34,   35,   43,
 /*   200 */    73,   18,   75,   36,   37,   38,   39,   12,   81,   14,
 /*   210 */    43,    6,    3,    8,   10,   10,   92,   34,   35,   14,
 /*   220 */     6,   17,   64,   19,   10,   21,   22,    1,   14,   24,
 /*   230 */    21,   55,   56,   57,   58,   69,   70,   24,   24,   14,
 /*   240 */    14,   36,   37,   38,   39,    1,   24,   81,   43,   24,
 /*   250 */    36,   37,   38,   39,   60,    3,   16,   43,   18,   65,
 /*   260 */     7,    8,   36,   69,   70,    1,   72,   73,   42,   75,
 /*   270 */    69,   11,   79,   21,   73,   81,   75,   84,   14,   53,
 /*   280 */    86,   37,   81,   10,    3,   25,   26,   27,   28,   29,
 /*   290 */    30,   31,   32,   13,   41,   61,   16,   53,   69,   60,
 /*   300 */    36,   72,   73,    1,   75,   71,   42,    5,   69,   70,
 /*   310 */    81,   72,   73,   20,   75,   34,   35,   53,    1,   85,
 /*   320 */    81,   79,   79,    1,   82,   60,   84,   84,   89,   90,
 /*   330 */    65,   61,   24,   63,   69,   70,   10,   72,   73,   12,
 /*   340 */    75,   71,   15,   17,    3,   79,   81,   60,   82,   23,
 /*   350 */    84,   86,   65,    3,   37,   53,   69,   70,    3,   72,
 /*   360 */    73,   53,   75,   17,   24,   68,   84,   67,   81,   60,
 /*   370 */    53,   21,   67,   86,   65,   53,   21,   67,   69,   70,
 /*   380 */    83,   72,   73,   83,   75,   69,   11,   67,   83,   60,
 /*   390 */    81,   11,    3,   83,   24,   86,   60,   81,   69,   70,
 /*   400 */    67,   72,   73,   83,   75,   69,   70,   78,   72,   73,
 /*   410 */    81,   75,   59,    3,   78,   59,   83,   81,   24,   43,
 /*   420 */    60,   25,   26,   27,   28,   29,   30,   31,   32,   69,
 /*   430 */    70,   16,   72,   73,   60,   75,   24,   42,   78,   23,
 /*   440 */    61,   81,    3,   69,   70,   92,   72,   73,   92,   75,
 /*   450 */    71,   60,   78,   62,   14,   81,    3,   24,   61,   24,
 /*   460 */    69,   70,   10,   72,   73,   60,   75,   77,   71,   71,
 /*   470 */    88,   14,   81,   92,   69,   70,   74,   72,   73,   21,
 /*   480 */    75,   60,   82,   14,   22,   62,   81,   83,   60,   24,
 /*   490 */    69,   70,   85,   72,   73,   90,   75,   69,   70,   17,
 /*   500 */    72,   73,   81,   75,   60,   87,   77,   80,   93,   81,
 /*   510 */    80,   93,   93,   69,   70,   93,   72,   73,   93,   75,
 /*   520 */    93,   60,   93,   93,   93,   81,   93,   93,   93,   93,
 /*   530 */    69,   70,   93,   72,   73,   60,   75,   93,   93,   93,
 /*   540 */    93,   93,   81,   93,   69,   70,   93,   72,   73,   60,
 /*   550 */    75,   93,   93,   93,   93,   93,   81,   93,   69,   70,
 /*   560 */    93,   72,   73,   60,   75,   93,   93,   93,   93,   93,
 /*   570 */    81,   93,   69,   70,   93,   72,   73,   60,   75,   93,
 /*   580 */    93,   93,   93,   93,   81,   93,   69,   70,   93,   72,
 /*   590 */    73,   60,   75,   93,   93,   93,   93,   93,   81,   93,
 /*   600 */    69,   70,   93,   72,   73,   60,   75,   93,   93,   93,
 /*   610 */    93,   93,   81,   93,   69,   70,   93,   72,   73,   93,
 /*   620 */    75,   93,   93,   93,   93,   93,   81,
);
    const YY_SHIFT_USE_DFLT = -20;
    const YY_SHIFT_MAX = 123;
    static public $yy_shift_ofst = array(
 /*     0 */   137,    5,   -6,   -6,   -6,   -6,   39,   48,   48,   59,
 /*    10 */    48,   39,   48,   48,  106,   97,   48,   48,   48,   48,
 /*    20 */    48,  156,   48,  167,  205,  214,  214,  214,  264,  204,
 /*    30 */   226,  135,  135,  195,  107,  137,  -19,    2,   16,   84,
 /*    40 */   317,   57,  308,  322,  308,  308,  308,  322,  308,  322,
 /*    50 */   458,  469,  462,  458,  462,  260,  396,  119,   46,  244,
 /*    60 */   112,  302,  281,  253,  183,  163,   15,  355,   55,  209,
 /*    70 */   252,   55,  327,  350,  225,  457,  273,  482,  462,  462,
 /*    80 */   452,  462,  465,  462,  -20,  -20,  159,  326,   -3,  161,
 /*    90 */   161,   17,  161,  161,  161,  240,  280,  151,  177,  213,
 /*   100 */   341,  118,  150,  435,  412,  395,  410,  415,  416,  439,
 /*   110 */   433,  453,  222,  440,  376,  394,  375,  346,  293,  273,
 /*   120 */   340,  380,  370,  389,
);
    const YY_REDUCE_USE_DFLT = -72;
    const YY_REDUCE_MAX = 85;
    static public $yy_reduce_ofst = array(
 /*     0 */   176,   86,  309,  287,  194,  265,  239,  336,  374,  391,
 /*    10 */   329,  405,  360,  489,  545,  475,  444,  503,  531,  428,
 /*    20 */   517,  461,  421,   50,  229,  127,  201,   19,  -17,  270,
 /*    30 */   -41,  266,  242,  166,  234,   22,  -71,  -71,   43,  297,
 /*    40 */   -56,  -71,  310,  124,  300,  305,  320,  356,  333,  353,
 /*    50 */   397,  316,  193,  379,  243,  418,  418,  402,  404,  381,
 /*    60 */   381,  381,  382,  402,  382,  382,  381,  398,  382,  398,
 /*    70 */   398,  382,  400,  398,  390,  423,  430,  407,  282,  282,
 /*    80 */   427,  282,  429,  282,   51,  158,
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
        /* 33 */ array(12, 14, ),
        /* 34 */ array(17, 21, ),
        /* 35 */ array(1, 2, 4, 45, 47, 48, 49, 50, 52, 53, ),
        /* 36 */ array(20, 24, 40, 44, 53, ),
        /* 37 */ array(20, 24, 44, 53, ),
        /* 38 */ array(7, 8, 19, 41, ),
        /* 39 */ array(9, 20, 24, 53, ),
        /* 40 */ array(1, 37, 53, ),
        /* 41 */ array(24, 44, 53, ),
        /* 42 */ array(24, 53, ),
        /* 43 */ array(1, 53, ),
        /* 44 */ array(24, 53, ),
        /* 45 */ array(24, 53, ),
        /* 46 */ array(24, 53, ),
        /* 47 */ array(1, 53, ),
        /* 48 */ array(24, 53, ),
        /* 49 */ array(1, 53, ),
        /* 50 */ array(21, ),
        /* 51 */ array(14, ),
        /* 52 */ array(22, ),
        /* 53 */ array(21, ),
        /* 54 */ array(22, ),
        /* 55 */ array(11, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 56 */ array(25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 57 */ array(7, 8, 13, 41, ),
        /* 58 */ array(20, 24, 53, ),
        /* 59 */ array(1, 37, 53, ),
        /* 60 */ array(1, 51, 53, ),
        /* 61 */ array(1, 5, 53, ),
        /* 62 */ array(3, 34, 35, ),
        /* 63 */ array(7, 8, 41, ),
        /* 64 */ array(18, 34, 35, ),
        /* 65 */ array(11, 34, 35, ),
        /* 66 */ array(1, 46, 53, ),
        /* 67 */ array(3, 21, ),
        /* 68 */ array(34, 35, ),
        /* 69 */ array(3, 21, ),
        /* 70 */ array(3, 21, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(12, 15, ),
        /* 73 */ array(3, 21, ),
        /* 74 */ array(14, 24, ),
        /* 75 */ array(14, ),
        /* 76 */ array(10, ),
        /* 77 */ array(17, ),
        /* 78 */ array(22, ),
        /* 79 */ array(22, ),
        /* 80 */ array(10, ),
        /* 81 */ array(22, ),
        /* 82 */ array(24, ),
        /* 83 */ array(22, ),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(10, 17, 20, ),
        /* 87 */ array(10, 17, 23, ),
        /* 88 */ array(10, 13, 17, ),
        /* 89 */ array(10, 17, ),
        /* 90 */ array(10, 17, ),
        /* 91 */ array(3, 22, ),
        /* 92 */ array(10, 17, ),
        /* 93 */ array(10, 17, ),
        /* 94 */ array(10, 17, ),
        /* 95 */ array(16, 18, ),
        /* 96 */ array(13, 16, ),
        /* 97 */ array(3, ),
        /* 98 */ array(11, ),
        /* 99 */ array(24, ),
        /* 100 */ array(3, ),
        /* 101 */ array(11, ),
        /* 102 */ array(3, ),
        /* 103 */ array(24, ),
        /* 104 */ array(24, ),
        /* 105 */ array(42, ),
        /* 106 */ array(3, ),
        /* 107 */ array(16, ),
        /* 108 */ array(23, ),
        /* 109 */ array(3, ),
        /* 110 */ array(24, ),
        /* 111 */ array(3, ),
        /* 112 */ array(24, ),
        /* 113 */ array(14, ),
        /* 114 */ array(43, ),
        /* 115 */ array(24, ),
        /* 116 */ array(11, ),
        /* 117 */ array(17, ),
        /* 118 */ array(20, ),
        /* 119 */ array(10, ),
        /* 120 */ array(24, ),
        /* 121 */ array(11, ),
        /* 122 */ array(24, ),
        /* 123 */ array(3, ),
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
        /* 217 */ array(),
);
    static public $yy_default = array(
 /*     0 */   341,  341,  341,  341,  341,  341,  326,  301,  301,  341,
 /*    10 */   301,  341,  301,  341,  341,  341,  341,  341,  341,  341,
 /*    20 */   341,  341,  341,  341,  341,  341,  341,  341,  341,  245,
 /*    30 */   341,  273,  279,  341,  245,  218,  283,  283,  252,  341,
 /*    40 */   341,  283,  341,  341,  341,  341,  341,  341,  341,  341,
 /*    50 */   245,  341,  268,  245,  269,  310,  310,  341,  341,  341,
 /*    60 */   341,  341,  341,  285,  341,  341,  341,  341,  308,  341,
 /*    70 */   341,  312,  295,  341,  341,  341,  283,  253,  270,  274,
 /*    80 */   283,  271,  341,  292,  304,  304,  341,  329,  341,  306,
 /*    90 */   284,  341,  251,  341,  246,  341,  341,  341,  341,  341,
 /*   100 */   341,  341,  341,  341,  341,  341,  341,  300,  327,  341,
 /*   110 */   341,  341,  341,  341,  341,  341,  341,  341,  341,  272,
 /*   120 */   341,  341,  341,  341,  219,  233,  234,  239,  236,  315,
 /*   130 */   242,  322,  321,  249,  241,  309,  238,  248,  240,  307,
 /*   140 */   320,  314,  313,  311,  235,  316,  250,  319,  318,  317,
 /*   150 */   237,  280,  262,  263,  264,  261,  256,  282,  255,  265,
 /*   160 */   266,  276,  277,  275,  254,  257,  303,  294,  243,  339,
 /*   170 */   340,  338,  337,  222,  220,  221,  223,  224,  229,  230,
 /*   180 */   228,  227,  225,  226,  278,  281,  333,  334,  335,  331,
 /*   190 */   288,  290,  291,  336,  267,  244,  231,  247,  296,  332,
 /*   200 */   298,  289,  293,  324,  305,  328,  325,  330,  323,  258,
 /*   210 */   259,  287,  286,  299,  297,  260,  302,  232,
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
    const YYNSTATE = 218;
    const YYNRULE = 123;
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
 /*  88 */ "modparameter ::= COLON ID",
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
 /* 111 */ "arrayelement ::= ID",
 /* 112 */ "arrayelement ::= ID APTR expr",
 /* 113 */ "doublequoted ::= doublequoted doublequotedcontent",
 /* 114 */ "doublequoted ::= doublequotedcontent",
 /* 115 */ "doublequotedcontent ::= variable",
 /* 116 */ "doublequotedcontent ::= BACKTICK variable BACKTICK",
 /* 117 */ "doublequotedcontent ::= LDEL expr RDEL",
 /* 118 */ "doublequotedcontent ::= OTHER",
 /* 119 */ "text ::= text textelement",
 /* 120 */ "text ::= textelement",
 /* 121 */ "textelement ::= OTHER",
 /* 122 */ "textelement ::= LDEL",
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
        106 => 0,
        1 => 1,
        34 => 1,
        36 => 1,
        41 => 1,
        42 => 1,
        70 => 1,
        89 => 1,
        114 => 1,
        120 => 1,
        121 => 1,
        122 => 1,
        2 => 2,
        64 => 2,
        113 => 2,
        119 => 2,
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
        109 => 24,
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
        112 => 112,
        115 => 115,
        116 => 116,
        117 => 117,
        118 => 118,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 71 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1604 "internal.templateparser.php"
#line 77 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1607 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1610 "internal.templateparser.php"
#line 85 "internal.templateparser.y"
    function yy_r3(){if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->prefix_code as $code) {$tmp.=$code;} $this->prefix_code=array();
                                            $this->_retvalue = $this->cacher->processNocacheCode($tmp.$this->yystack[$this->yyidx + 0]->minor, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;    }
#line 1616 "internal.templateparser.php"
#line 90 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);    }
#line 1619 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r5(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + -1]->minor, $this->compiler,false,false);    }
#line 1622 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r6(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);    }
#line 1625 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r7(){$this->_retvalue = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);    }
#line 1628 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r8(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars($this->yystack[$this->yyidx + 0]->minor, ENT_QUOTES), $this->compiler, false, false);}    }
#line 1634 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r9(){if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      $this->_retvalue = $this->cacher->processNocacheCode('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      $this->_retvalue = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.$this->yystack[$this->yyidx + -1]->minor.' ?>', ENT_QUOTES), $this->compiler, false, false);}    }
#line 1640 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r10(){$this->_retvalue = $this->cacher->processNocacheCode("<?php echo '".$this->yystack[$this->yyidx + 0]->minor."';?>", $this->compiler, false, false);    }
#line 1643 "internal.templateparser.php"
#line 110 "internal.templateparser.y"
    function yy_r11(){$this->_retvalue = $this->cacher->processNocacheCode($this->yystack[$this->yyidx + 0]->minor, $this->compiler,false,false);    }
#line 1646 "internal.templateparser.php"
#line 118 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1649 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue = $this->compiler->compileTag('assign',$this->yystack[$this->yyidx + -1]->minor);    }
#line 1652 "internal.templateparser.php"
#line 122 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor);    }
#line 1655 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -4]->minor,array_merge(array('object_methode'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor));    }
#line 1658 "internal.templateparser.php"
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
#line 1673 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -1]->minor.'close',array());    }
#line 1676 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor.'close',array('object_methode'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1679 "internal.templateparser.php"
#line 144 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1682 "internal.templateparser.php"
#line 146 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'varloop'=>$this->yystack[$this->yyidx + -2]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1685 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1688 "internal.templateparser.php"
#line 150 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1691 "internal.templateparser.php"
#line 151 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1694 "internal.templateparser.php"
#line 157 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1697 "internal.templateparser.php"
#line 161 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = array();    }
#line 1700 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>'\''.$this->yystack[$this->yyidx + 0]->minor.'\'');    }
#line 1703 "internal.templateparser.php"
#line 165 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1706 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);    }
#line 1709 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r31(){ $this->yystack[$this->yyidx + -2]->minor[]=$this->yystack[$this->yyidx + 0]->minor; $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;    }
#line 1712 "internal.templateparser.php"
#line 173 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1715 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = array('var' => $this->yystack[$this->yyidx + -2]->minor, 'value'=>'\''.$this->yystack[$this->yyidx + 0]->minor.'\'');    }
#line 1718 "internal.templateparser.php"
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
#line 1732 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1735 "internal.templateparser.php"
#line 200 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1738 "internal.templateparser.php"
#line 202 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1741 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r46(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1744 "internal.templateparser.php"
#line 236 "internal.templateparser.y"
    function yy_r47(){ $this->_retvalue = "''";     }
#line 1747 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1750 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r51(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -3]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -7]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -1]->minor .')';     }
#line 1753 "internal.templateparser.php"
#line 245 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor.'::'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1756 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r53(){ $this->prefix_number++; $this->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. $this->yystack[$this->yyidx + -4]->minor .'\')->value;?>'; $this->_retvalue = $this->yystack[$this->yyidx + -8]->minor.'::$_tmp'.$this->prefix_number.'('. $this->yystack[$this->yyidx + -2]->minor .')'.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1759 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor.'::'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1762 "internal.templateparser.php"
#line 250 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -5]->minor.'::$'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1765 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -6]->minor.'::$'.$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1768 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue = '$_smarty_tpl->getConfigVariable(\''. $this->yystack[$this->yyidx + -1]->minor .'\')';    }
#line 1771 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1774 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r61(){ if ($this->yystack[$this->yyidx + -1]->minor == '\'smarty\'') { $this->_retvalue =  $this->compiler->compileTag(trim($this->yystack[$this->yyidx + -1]->minor,"'"),$this->yystack[$this->yyidx + 0]->minor);} else {
                                                         $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -1]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}    }
#line 1778 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->'.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1781 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r65(){return;    }
#line 1784 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + 0]->minor ."']";    }
#line 1787 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r67(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1790 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r68(){ $this->_retvalue = "['". $this->yystack[$this->yyidx + -1]->minor ."']";    }
#line 1793 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r69(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1796 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1799 "internal.templateparser.php"
#line 293 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1802 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1805 "internal.templateparser.php"
#line 300 "internal.templateparser.y"
    function yy_r74(){ $this->_retvalue = '$_smarty_tpl->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->value'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor; $_var = $this->template->getVariable(trim($this->yystack[$this->yyidx + -2]->minor,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;    }
#line 1808 "internal.templateparser.php"
#line 302 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1811 "internal.templateparser.php"
#line 304 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1814 "internal.templateparser.php"
#line 306 "internal.templateparser.y"
    function yy_r77(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1817 "internal.templateparser.php"
#line 309 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1820 "internal.templateparser.php"
#line 314 "internal.templateparser.y"
    function yy_r79(){if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction($this->yystack[$this->yyidx + -3]->minor, $this->compiler)) {
																					            if ($this->yystack[$this->yyidx + -3]->minor == 'isset' || $this->yystack[$this->yyidx + -3]->minor == 'empty' || is_callable($this->yystack[$this->yyidx + -3]->minor)) {
																					                $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . $this->yystack[$this->yyidx + -3]->minor . "\"");
                                                      }
                                                    }    }
#line 1829 "internal.templateparser.php"
#line 325 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1832 "internal.templateparser.php"
#line 329 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1835 "internal.templateparser.php"
#line 333 "internal.templateparser.y"
    function yy_r83(){ return;    }
#line 1838 "internal.templateparser.php"
#line 338 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1841 "internal.templateparser.php"
#line 344 "internal.templateparser.y"
    function yy_r85(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1844 "internal.templateparser.php"
#line 348 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1847 "internal.templateparser.php"
#line 349 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = ',\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1850 "internal.templateparser.php"
#line 356 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1853 "internal.templateparser.php"
#line 361 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1856 "internal.templateparser.php"
#line 362 "internal.templateparser.y"
    function yy_r93(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1859 "internal.templateparser.php"
#line 365 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = '==';    }
#line 1862 "internal.templateparser.php"
#line 366 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = '!=';    }
#line 1865 "internal.templateparser.php"
#line 367 "internal.templateparser.y"
    function yy_r97(){$this->_retvalue = '>';    }
#line 1868 "internal.templateparser.php"
#line 368 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = '<';    }
#line 1871 "internal.templateparser.php"
#line 369 "internal.templateparser.y"
    function yy_r99(){$this->_retvalue = '>=';    }
#line 1874 "internal.templateparser.php"
#line 370 "internal.templateparser.y"
    function yy_r100(){$this->_retvalue = '<=';    }
#line 1877 "internal.templateparser.php"
#line 371 "internal.templateparser.y"
    function yy_r101(){$this->_retvalue = '===';    }
#line 1880 "internal.templateparser.php"
#line 372 "internal.templateparser.y"
    function yy_r102(){$this->_retvalue = '!==';    }
#line 1883 "internal.templateparser.php"
#line 374 "internal.templateparser.y"
    function yy_r103(){$this->_retvalue = '&&';    }
#line 1886 "internal.templateparser.php"
#line 375 "internal.templateparser.y"
    function yy_r104(){$this->_retvalue = '||';    }
#line 1889 "internal.templateparser.php"
#line 377 "internal.templateparser.y"
    function yy_r105(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1892 "internal.templateparser.php"
#line 379 "internal.templateparser.y"
    function yy_r107(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1895 "internal.templateparser.php"
#line 380 "internal.templateparser.y"
    function yy_r108(){ return;     }
#line 1898 "internal.templateparser.php"
#line 382 "internal.templateparser.y"
    function yy_r110(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1901 "internal.templateparser.php"
#line 383 "internal.templateparser.y"
    function yy_r111(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1904 "internal.templateparser.php"
#line 384 "internal.templateparser.y"
    function yy_r112(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + -2]->minor.'\'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1907 "internal.templateparser.php"
#line 388 "internal.templateparser.y"
    function yy_r115(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1910 "internal.templateparser.php"
#line 389 "internal.templateparser.y"
    function yy_r116(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1913 "internal.templateparser.php"
#line 390 "internal.templateparser.y"
    function yy_r117(){$this->_retvalue = "'.(".$this->yystack[$this->yyidx + -1]->minor.").'";    }
#line 1916 "internal.templateparser.php"
#line 391 "internal.templateparser.y"
    function yy_r118(){$this->_retvalue = addslashes($this->yystack[$this->yyidx + 0]->minor);    }
#line 1919 "internal.templateparser.php"

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
#line 2036 "internal.templateparser.php"
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
#line 2061 "internal.templateparser.php"
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
