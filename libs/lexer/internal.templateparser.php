<?php
/* Driver template for the PHP_TP_rGenerator parser generator. (PHP port of LEMON)
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
#line 4 "internal.templateparser.y"
class Smarty_Internal_Templateparser#line 102 "internal.templateparser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 6 "internal.templateparser.y"

    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex,$tpl_vars) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->tpl_vars = $tpl_vars; 
        $this->smarty = Smarty::instance(); 
        $this->compiler = Smarty_Internal_Compiler::instance(); 
				$this->nocache = false;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 132 "internal.templateparser.php"

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
    const TP_LDELS                          =  2;
    const TP_LDELSLASH                      =  3;
    const TP_RDELS                          =  4;
    const TP_RDEL                           =  5;
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
    const TP_NOT                            = 33;
    const TP_LAND                           = 34;
    const TP_LOR                            = 35;
    const TP_QUOTE                          = 36;
    const TP_BOOLEAN                        = 37;
    const TP_IN                             = 38;
    const TP_ANDSYM                         = 39;
    const TP_IF                             = 40;
    const TP_FOR                            = 41;
    const TP_FOREACH                        = 42;
    const TP_UNDERL                         = 43;
    const TP_COMMENTSTART                   = 44;
    const TP_COMMENTEND                     = 45;
    const TP_PHP                            = 46;
    const TP_LDEL                           = 47;
    const YY_NO_ACTION = 285;
    const YY_ACCEPT_ACTION = 284;
    const YY_ERROR_ACTION = 283;

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
    const YY_SZ_ACTTAB = 515;
static public $yy_action = array(
 /*     0 */   150,  178,   29,  175,   25,  206,  132,   83,   42,   54,
 /*    10 */    11,  133,  163,  131,  129,  132,  154,   74,   39,  130,
 /*    20 */   141,   99,  131,  129,  179,  150,   24,   29,  160,    4,
 /*    30 */    33,  151,   99,   41,   89,  103,   47,  162,  136,  138,
 /*    40 */    52,  178,  159,   96,  130,  110,  132,  172,   74,  127,
 /*    50 */    37,  141,    5,  131,  129,   33,  151,  122,  113,  124,
 /*    60 */   125,  126,  117,  119,  161,  136,  138,   70,  137,  156,
 /*    70 */    30,   23,   93,  150,   17,   29,  102,    3,  115,  121,
 /*    80 */    40,   41,   11,  175,  122,  113,  124,  125,  126,  117,
 /*    90 */   119,   96,  130,  158,  149,   20,   80,   30,    8,   48,
 /*   100 */     5,  175,  163,   33,  151,  132,   24,   74,  115,  121,
 /*   110 */   141,   16,  131,  129,  150,  175,   29,  150,   25,   29,
 /*   120 */    92,    1,   41,  104,   24,   41,   82,  168,  174,   79,
 /*   130 */   136,  138,   96,  130,   12,   96,  130,   77,   24,  148,
 /*   140 */    14,   72,  137,   67,   33,  151,   35,   33,  151,  167,
 /*   150 */    63,   98,  132,  107,   74,  136,  138,  141,  169,  131,
 /*   160 */   129,  150,   30,   29,    9,    1,  156,  132,  135,   41,
 /*   170 */   123,   40,  139,   23,  131,  129,   17,   44,  102,   84,
 /*   180 */   130,   13,   35,   78,  169,  175,   68,   30,  132,  112,
 /*   190 */    74,   33,  151,  141,  111,  131,  129,   40,  143,   35,
 /*   200 */   101,  180,   28,   71,   18,  132,  123,   74,   24,   85,
 /*   210 */   141,  118,  131,  129,   35,   19,  115,  121,   59,  134,
 /*   220 */   132,   23,   74,   81,   17,  141,  102,  131,  129,  109,
 /*   230 */    18,   78,   75,  175,  165,   34,  182,  144,  123,   71,
 /*   240 */    15,  132,  145,   74,  175,  128,  141,   34,  131,  129,
 /*   250 */    99,   71,   99,  132,   23,   74,   24,   17,  141,  100,
 /*   260 */   131,  129,   53,   97,   45,   88,   73,   24,  132,  120,
 /*   270 */    74,  106,  167,  141,   55,  131,  129,  150,   57,  255,
 /*   280 */   255,   25,   69,    2,  132,   41,   74,  114,  167,  141,
 /*   290 */   177,  131,  129,   28,   10,   96,  130,   38,   64,   26,
 /*   300 */   105,  157,   43,   31,  132,   11,   74,   33,  151,  141,
 /*   310 */    57,  131,  129,  177,   57,  177,  132,   94,   74,  177,
 /*   320 */   132,  141,   74,  131,  129,  141,  164,  131,  129,  147,
 /*   330 */   146,   58,   95,   91,   86,   90,  181,  132,    6,   74,
 /*   340 */    56,   27,  141,  171,  131,  129,  132,  116,   74,   28,
 /*   350 */    61,  141,  156,  131,  129,  173,  132,  177,   74,   62,
 /*   360 */    99,  141,   22,  131,  129,  132,   78,   74,  140,   51,
 /*   370 */   141,  153,  131,  129,   46,  132,  142,   74,   50,   87,
 /*   380 */   141,  186,  131,  129,  132,  186,   74,   66,  186,  141,
 /*   390 */   186,  131,  129,  132,  186,   74,  186,   60,  141,  186,
 /*   400 */   131,  129,  186,  132,  186,   74,   32,  186,  141,  186,
 /*   410 */   131,  129,  132,  186,   74,  186,   49,  141,  186,  131,
 /*   420 */   129,  186,  132,  186,   74,   65,  186,  141,  186,  131,
 /*   430 */   129,  132,  186,   74,  186,  186,  141,  186,  131,  129,
 /*   440 */   186,  136,  138,  253,  253,  159,   70,  137,  156,   76,
 /*   450 */   176,  186,  186,  136,  138,  136,  138,   21,  186,  155,
 /*   460 */   186,  136,  138,  136,  138,  159,  186,  166,  186,  136,
 /*   470 */   138,   21,  186,   30,    7,  170,  186,  136,  138,  136,
 /*   480 */   138,  186,  136,  138,  186,   30,  186,   30,  284,   36,
 /*   490 */   108,  146,  132,   30,  186,   30,   99,  152,  186,  131,
 /*   500 */   129,   30,  186,  186,  186,  186,  186,  186,  186,   30,
 /*   510 */   186,   30,  186,  186,   30,
    );
    static public $yy_lookahead = array(
 /*     0 */     6,   60,    8,   24,   10,    5,   60,   53,   14,   54,
 /*    10 */    10,   65,   57,   67,   68,   60,    5,   62,   24,   25,
 /*    20 */    65,   21,   67,   68,   83,    6,   47,    8,   24,   10,
 /*    30 */    36,   37,   21,   14,   40,   41,   42,   82,    7,    8,
 /*    40 */    54,   60,   11,   24,   25,    5,   60,   75,   62,    5,
 /*    50 */    69,   65,   33,   67,   68,   36,   37,   26,   27,   28,
 /*    60 */    29,   30,   31,   32,   83,    7,    8,   70,   71,   72,
 /*    70 */    39,   12,   24,    6,   15,    8,   17,   10,   34,   35,
 /*    80 */    14,   14,   10,   24,   26,   27,   28,   29,   30,   31,
 /*    90 */    32,   24,   25,   11,    1,   23,    3,   39,   16,   54,
 /*   100 */    33,   24,   57,   36,   37,   60,   47,   62,   34,   35,
 /*   110 */    65,   20,   67,   68,    6,   24,    8,    6,   10,    8,
 /*   120 */    43,   10,   14,   60,   47,   14,   81,   82,    5,   38,
 /*   130 */     7,    8,   24,   25,   20,   24,   25,   44,   47,   46,
 /*   140 */    47,   70,   71,   55,   36,   37,   54,   36,   37,   61,
 /*   150 */    58,   21,   60,   59,   62,    7,    8,   65,    1,   67,
 /*   160 */    68,    6,   39,    8,   16,   10,   72,   60,   24,   14,
 /*   170 */    78,   14,   65,   12,   67,   68,   15,   14,   17,   24,
 /*   180 */    25,   20,   54,   22,    1,   24,   58,   39,   60,    5,
 /*   190 */    62,   36,   37,   65,    5,   67,   68,   14,    1,   54,
 /*   200 */    18,   11,   66,   58,   47,   60,   78,   62,   47,   11,
 /*   210 */    65,   11,   67,   68,   54,   79,   34,   35,   58,   36,
 /*   220 */    60,   12,   62,   78,   15,   65,   17,   67,   68,    9,
 /*   230 */    47,   22,   64,   24,    5,   54,    5,    1,   78,   58,
 /*   240 */    20,   60,   45,   62,   24,   77,   65,   54,   67,   68,
 /*   250 */    21,   58,   21,   60,   12,   62,   47,   15,   65,   78,
 /*   260 */    67,   68,   54,   24,   24,   57,   55,   47,   60,    5,
 /*   270 */    62,   78,   61,   65,   56,   67,   68,    6,   54,   34,
 /*   280 */    35,   10,   55,   21,   60,   14,   62,    5,   61,   65,
 /*   290 */    72,   67,   68,   66,   10,   24,   25,   56,   54,   56,
 /*   300 */    76,   57,   14,   56,   60,   10,   62,   36,   37,   65,
 /*   310 */    54,   67,   68,   72,   54,   72,   60,   24,   62,   72,
 /*   320 */    60,   65,   62,   67,   68,   65,    5,   67,   68,   51,
 /*   330 */    52,   54,   76,   40,   41,   42,   76,   60,   80,   62,
 /*   340 */    54,   56,   65,   11,   67,   68,   60,    5,   62,   66,
 /*   350 */    54,   65,   72,   67,   68,   74,   60,   72,   62,   54,
 /*   360 */    21,   65,   17,   67,   68,   60,   22,   62,   71,   54,
 /*   370 */    65,   61,   67,   68,   63,   60,   77,   62,   54,   19,
 /*   380 */    65,   84,   67,   68,   60,   84,   62,   54,   84,   65,
 /*   390 */    84,   67,   68,   60,   84,   62,   84,   54,   65,   84,
 /*   400 */    67,   68,   84,   60,   84,   62,   54,   84,   65,   84,
 /*   410 */    67,   68,   60,   84,   62,   84,   54,   65,   84,   67,
 /*   420 */    68,   84,   60,   84,   62,   54,   84,   65,   84,   67,
 /*   430 */    68,   60,   84,   62,   84,   84,   65,   84,   67,   68,
 /*   440 */    84,    7,    8,   34,   35,   11,   70,   71,   72,   73,
 /*   450 */    74,   84,   84,    7,    8,    7,    8,   23,   84,   13,
 /*   460 */    84,    7,    8,    7,    8,   11,   84,    5,   84,    7,
 /*   470 */     8,   23,   84,   39,   18,    5,   84,    7,    8,    7,
 /*   480 */     8,   84,    7,    8,   84,   39,   84,   39,   49,   50,
 /*   490 */    51,   52,   60,   39,   84,   39,   21,   65,   84,   67,
 /*   500 */    68,   39,   84,   84,   84,   84,   84,   84,   84,   39,
 /*   510 */    84,   39,   84,   84,   39,
);
    const YY_SHIFT_USE_DFLT = -22;
    const YY_SHIFT_MAX = 107;
    static public $yy_shift_ofst = array(
 /*     0 */    93,  155,   19,   19,   19,   67,   19,   19,  155,  108,
 /*    10 */   108,  108,  111,  111,   -6,  108,  108,  108,  108,  108,
 /*    20 */   108,  108,  108,  108,  108,  108,  161,  209,  271,  271,
 /*    30 */   271,   59,  475,  157,   31,   58,   93,  183,  220,    0,
 /*    40 */    77,   77,   77,  -21,  -21,  242,  345,  339,  434,  446,
 /*    50 */   123,  454,  470,  462,  448,   91,  456,  148,  472,   44,
 /*    60 */   472,  472,  472,  182,  472,  472,  472,  229,   74,   11,
 /*    70 */   242,   74,  242,  231,  360,  345,  344,  236,   48,   66,
 /*    80 */   293,  409,   82,  197,   72,  245,   40,    4,  321,  262,
 /*    90 */   264,  342,  240,  284,  282,  332,  295,  114,  288,  239,
 /*   100 */   200,  163,  144,  130,  184,  190,  198,  189,
);
    const YY_REDUCE_USE_DFLT = -60;
    const YY_REDUCE_MAX = 79;
    static public $yy_reduce_ofst = array(
 /*     0 */   439,   45,  160,  193,  181,  145,  128,   92,  -45,  260,
 /*    10 */   256,  224,  244,  208,  352,  296,  286,  305,  -14,  371,
 /*    20 */   343,  277,  333,  362,  324,  315,  376,  376,  432,  -54,
 /*    30 */   107,   -3,  227,  -19,  136,  136,  278,  -59,   94,   88,
 /*    40 */   247,  285,  243,  218,  241,   71,  168,  211,  283,  283,
 /*    50 */   283,  283,  283,  283,  283,  280,  283,  283,  283,  258,
 /*    60 */   283,  283,  283,  258,  283,  283,  283,  310,  258,  310,
 /*    70 */   297,  258,  297,  310,  311,  299,  281,  -46,  -28,   63,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 44, 46, 47, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 9 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 10 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 13 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 14 */ array(6, 8, 10, 14, 24, 25, 36, 37, 40, 41, 42, ),
        /* 15 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 16 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 17 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 18 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 19 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 20 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 21 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 22 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 24 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 25 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 26 */ array(12, 15, 17, 20, 22, 24, 47, ),
        /* 27 */ array(12, 15, 17, 22, 24, 47, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 31 */ array(12, 15, 17, 24, 47, ),
        /* 32 */ array(7, 8, 21, 39, ),
        /* 33 */ array(1, 14, 47, ),
        /* 34 */ array(7, 8, 11, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 35 */ array(7, 8, 26, 27, 28, 29, 30, 31, 32, 39, ),
        /* 36 */ array(1, 3, 44, 46, 47, ),
        /* 37 */ array(1, 14, 36, 47, ),
        /* 38 */ array(9, 20, 24, 47, ),
        /* 39 */ array(5, 10, 21, ),
        /* 40 */ array(24, 43, 47, ),
        /* 41 */ array(24, 43, 47, ),
        /* 42 */ array(24, 43, 47, ),
        /* 43 */ array(24, 47, ),
        /* 44 */ array(24, 47, ),
        /* 45 */ array(12, 15, ),
        /* 46 */ array(17, ),
        /* 47 */ array(21, ),
        /* 48 */ array(7, 8, 11, 23, 39, ),
        /* 49 */ array(7, 8, 13, 39, ),
        /* 50 */ array(5, 7, 8, 39, ),
        /* 51 */ array(7, 8, 11, 39, ),
        /* 52 */ array(5, 7, 8, 39, ),
        /* 53 */ array(5, 7, 8, 39, ),
        /* 54 */ array(7, 8, 23, 39, ),
        /* 55 */ array(20, 24, 38, 47, ),
        /* 56 */ array(7, 8, 18, 39, ),
        /* 57 */ array(7, 8, 16, 39, ),
        /* 58 */ array(7, 8, 39, ),
        /* 59 */ array(5, 34, 35, ),
        /* 60 */ array(7, 8, 39, ),
        /* 61 */ array(7, 8, 39, ),
        /* 62 */ array(7, 8, 39, ),
        /* 63 */ array(18, 34, 35, ),
        /* 64 */ array(7, 8, 39, ),
        /* 65 */ array(7, 8, 39, ),
        /* 66 */ array(7, 8, 39, ),
        /* 67 */ array(5, 21, ),
        /* 68 */ array(34, 35, ),
        /* 69 */ array(5, 21, ),
        /* 70 */ array(12, 15, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(12, 15, ),
        /* 73 */ array(5, 21, ),
        /* 74 */ array(19, ),
        /* 75 */ array(17, ),
        /* 76 */ array(22, ),
        /* 77 */ array(1, ),
        /* 78 */ array(24, ),
        /* 79 */ array(14, ),
        /* 80 */ array(24, 40, 41, 42, ),
        /* 81 */ array(34, 35, ),
        /* 82 */ array(11, 16, ),
        /* 83 */ array(1, 45, ),
        /* 84 */ array(10, 23, ),
        /* 85 */ array(34, 35, ),
        /* 86 */ array(5, ),
        /* 87 */ array(24, ),
        /* 88 */ array(5, ),
        /* 89 */ array(21, ),
        /* 90 */ array(5, ),
        /* 91 */ array(5, ),
        /* 92 */ array(24, ),
        /* 93 */ array(10, ),
        /* 94 */ array(5, ),
        /* 95 */ array(11, ),
        /* 96 */ array(10, ),
        /* 97 */ array(20, ),
        /* 98 */ array(14, ),
        /* 99 */ array(24, ),
        /* 100 */ array(11, ),
        /* 101 */ array(14, ),
        /* 102 */ array(24, ),
        /* 103 */ array(21, ),
        /* 104 */ array(5, ),
        /* 105 */ array(11, ),
        /* 106 */ array(11, ),
        /* 107 */ array(5, ),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(),
        /* 112 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   283,  283,  283,  283,  283,  283,  283,  283,  283,  247,
 /*    10 */   247,  247,  283,  283,  283,  283,  283,  283,  283,  283,
 /*    20 */   283,  283,  283,  283,  283,  283,  231,  231,  283,  283,
 /*    30 */   283,  231,  206,  283,  257,  257,  183,  283,  283,  225,
 /*    40 */   283,  283,  283,  283,  283,  231,  251,  206,  272,  283,
 /*    50 */   283,  283,  283,  283,  272,  283,  283,  246,  273,  283,
 /*    60 */   274,  202,  232,  283,  207,  258,  252,  283,  259,  283,
 /*    70 */   227,  283,  228,  283,  209,  210,  238,  283,  283,  283,
 /*    80 */   283,  254,  283,  283,  225,  256,  283,  283,  283,  283,
 /*    90 */   283,  283,  283,  241,  283,  283,  225,  283,  283,  283,
 /*   100 */   253,  283,  283,  283,  283,  283,  253,  283,  184,  203,
 /*   110 */   198,  200,  201,  261,  195,  267,  196,  265,  255,  266,
 /*   120 */   197,  268,  260,  253,  262,  263,  264,  199,  250,  222,
 /*   130 */   223,  221,  220,  212,  224,  226,  216,  229,  215,  214,
 /*   140 */   230,  211,  249,  281,  282,  187,  186,  185,  188,  189,
 /*   150 */   217,  218,  213,  204,  190,  233,  235,  208,  269,  219,
 /*   160 */   248,  277,  271,  275,  192,  193,  191,  205,  270,  280,
 /*   170 */   279,  244,  242,  240,  237,  236,  239,  234,  278,  276,
 /*   180 */   243,  245,  194,
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
    const YYNOCODE = 85;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 183;
    const YYNRULE = 100;
    const YYERRORSYMBOL = 48;
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
    1,  /*      LDELS => OTHER */
    1,  /*  LDELSLASH => OTHER */
    1,  /*      RDELS => OTHER */
    1,  /*       RDEL => OTHER */
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
    1,  /*        NOT => OTHER */
    1,  /*       LAND => OTHER */
    1,  /*        LOR => OTHER */
    1,  /*      QUOTE => OTHER */
    1,  /*    BOOLEAN => OTHER */
    1,  /*         IN => OTHER */
    1,  /*     ANDSYM => OTHER */
    1,  /*         IF => OTHER */
    1,  /*        FOR => OTHER */
    1,  /*    FOREACH => OTHER */
    1,  /*     UNDERL => OTHER */
    0,  /* COMMENTSTART => nothing */
    0,  /* COMMENTEND => nothing */
    0,  /*        PHP => nothing */
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
  '$',             'OTHER',         'LDELS',         'LDELSLASH',   
  'RDELS',         'RDEL',          'NUMBER',        'MATH',        
  'UNIMATH',       'INCDEC',        'OPENP',         'CLOSEP',      
  'OPENB',         'CLOSEB',        'DOLLAR',        'DOT',         
  'COMMA',         'COLON',         'SEMICOLON',     'VERT',        
  'EQUAL',         'SPACE',         'PTR',           'APTR',        
  'ID',            'SI_QSTR',       'EQUALS',        'NOTEQUALS',   
  'GREATERTHAN',   'LESSTHAN',      'GREATEREQUAL',  'LESSEQUAL',   
  'IDENTITY',      'NOT',           'LAND',          'LOR',         
  'QUOTE',         'BOOLEAN',       'IN',            'ANDSYM',      
  'IF',            'FOR',           'FOREACH',       'UNDERL',      
  'COMMENTSTART',  'COMMENTEND',    'PHP',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'commenttext',   'expr',          'attributes',  
  'varvar',        'array',         'ifexprs',       'foraction',   
  'variable',      'attribute',     'exprs',         'modifier',    
  'modparameters',  'value',         'math',          'object',      
  'function',      'doublequoted',  'vararraydefs',  'vararraydef', 
  'varvarele',     'objectchain',   'objectelement',  'method',      
  'params',        'modparameter',  'ifexpr',        'ifcond',      
  'lop',           'arrayelements',  'arrayelement',  'other',       
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
 /*   4 */ "template_element ::= COMMENTSTART commenttext COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr attributes RDEL",
 /*   8 */ "smartytag ::= LDEL DOLLAR varvar EQUAL expr RDEL",
 /*   9 */ "smartytag ::= LDEL DOLLAR varvar EQUAL array RDEL",
 /*  10 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  11 */ "smartytag ::= LDEL FOREACH attributes RDEL",
 /*  12 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  13 */ "smartytag ::= LDELSLASH IF RDEL",
 /*  14 */ "smartytag ::= LDELSLASH FOREACH RDEL",
 /*  15 */ "smartytag ::= LDELSLASH FOR RDEL",
 /*  16 */ "smartytag ::= LDEL IF SPACE ifexprs RDEL",
 /*  17 */ "smartytag ::= LDEL FOR SPACE DOLLAR varvar EQUAL expr SEMICOLON ifexprs SEMICOLON DOLLAR varvar foraction RDEL",
 /*  18 */ "smartytag ::= LDEL FOR SPACE DOLLAR varvar IN variable RDEL",
 /*  19 */ "foraction ::= EQUAL expr",
 /*  20 */ "foraction ::= INCDEC",
 /*  21 */ "attributes ::= attributes attribute",
 /*  22 */ "attributes ::= attribute",
 /*  23 */ "attributes ::=",
 /*  24 */ "attribute ::= SPACE ID EQUAL expr",
 /*  25 */ "attribute ::= SPACE ID EQUAL array",
 /*  26 */ "expr ::= exprs",
 /*  27 */ "expr ::= exprs modifier modparameters",
 /*  28 */ "exprs ::= value",
 /*  29 */ "exprs ::= UNIMATH value",
 /*  30 */ "exprs ::= expr math value",
 /*  31 */ "exprs ::= expr ANDSYM value",
 /*  32 */ "math ::= UNIMATH",
 /*  33 */ "math ::= MATH",
 /*  34 */ "value ::= NUMBER",
 /*  35 */ "value ::= BOOLEAN",
 /*  36 */ "value ::= OPENP expr CLOSEP",
 /*  37 */ "value ::= variable",
 /*  38 */ "value ::= object",
 /*  39 */ "value ::= function",
 /*  40 */ "value ::= SI_QSTR",
 /*  41 */ "value ::= QUOTE doublequoted QUOTE",
 /*  42 */ "value ::= ID",
 /*  43 */ "variable ::= DOLLAR varvar COLON ID",
 /*  44 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  45 */ "variable ::= DOLLAR UNDERL ID vararraydefs",
 /*  46 */ "vararraydefs ::= vararraydef",
 /*  47 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  48 */ "vararraydefs ::=",
 /*  49 */ "vararraydef ::= DOT expr",
 /*  50 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  51 */ "varvar ::= varvarele",
 /*  52 */ "varvar ::= varvar varvarele",
 /*  53 */ "varvarele ::= ID",
 /*  54 */ "varvarele ::= LDEL expr RDEL",
 /*  55 */ "object ::= DOLLAR varvar objectchain",
 /*  56 */ "objectchain ::= objectelement",
 /*  57 */ "objectchain ::= objectchain objectelement",
 /*  58 */ "objectelement ::= PTR ID",
 /*  59 */ "objectelement ::= PTR method",
 /*  60 */ "function ::= ID OPENP params CLOSEP",
 /*  61 */ "method ::= ID OPENP params CLOSEP",
 /*  62 */ "params ::= expr COMMA params",
 /*  63 */ "params ::= expr",
 /*  64 */ "params ::=",
 /*  65 */ "modifier ::= VERT ID",
 /*  66 */ "modparameters ::= modparameters modparameter",
 /*  67 */ "modparameters ::= modparameter",
 /*  68 */ "modparameters ::=",
 /*  69 */ "modparameter ::= COLON expr",
 /*  70 */ "ifexprs ::= ifexpr",
 /*  71 */ "ifexprs ::= NOT ifexpr",
 /*  72 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  73 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  74 */ "ifexpr ::= expr",
 /*  75 */ "ifexpr ::= expr ifcond expr",
 /*  76 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  77 */ "ifcond ::= EQUALS",
 /*  78 */ "ifcond ::= NOTEQUALS",
 /*  79 */ "ifcond ::= GREATERTHAN",
 /*  80 */ "ifcond ::= LESSTHAN",
 /*  81 */ "ifcond ::= GREATEREQUAL",
 /*  82 */ "ifcond ::= LESSEQUAL",
 /*  83 */ "ifcond ::= IDENTITY",
 /*  84 */ "lop ::= LAND",
 /*  85 */ "lop ::= LOR",
 /*  86 */ "array ::= OPENP arrayelements CLOSEP",
 /*  87 */ "arrayelements ::= arrayelement",
 /*  88 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  89 */ "arrayelement ::= expr",
 /*  90 */ "arrayelement ::= expr APTR expr",
 /*  91 */ "arrayelement ::= ID APTR expr",
 /*  92 */ "arrayelement ::= array",
 /*  93 */ "doublequoted ::= doublequoted other",
 /*  94 */ "doublequoted ::= other",
 /*  95 */ "other ::= variable",
 /*  96 */ "other ::= LDEL expr RDEL",
 /*  97 */ "other ::= OTHER",
 /*  98 */ "commenttext ::= commenttext OTHER",
 /*  99 */ "commenttext ::= OTHER",
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
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
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
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
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
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
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
                    self::$yyTokenName[$this->yystack[$i]->major]);
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
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 5 ),
  array( 'lhs' => 52, 'rhs' => 14 ),
  array( 'lhs' => 52, 'rhs' => 8 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 0 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 0 ),
  array( 'lhs' => 71, 'rhs' => 2 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 74, 'rhs' => 2 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 0 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 0 ),
  array( 'lhs' => 77, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        28 => 0,
        34 => 0,
        35 => 0,
        37 => 0,
        38 => 0,
        39 => 0,
        40 => 0,
        87 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        26 => 1,
        32 => 1,
        33 => 1,
        46 => 1,
        51 => 1,
        67 => 1,
        70 => 1,
        94 => 1,
        97 => 1,
        99 => 1,
        2 => 2,
        47 => 2,
        93 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 8,
        10 => 10,
        11 => 10,
        12 => 12,
        13 => 12,
        14 => 12,
        15 => 12,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        22 => 20,
        63 => 20,
        89 => 20,
        92 => 20,
        21 => 21,
        23 => 23,
        24 => 24,
        25 => 24,
        27 => 27,
        29 => 29,
        30 => 30,
        31 => 31,
        36 => 36,
        41 => 41,
        42 => 42,
        43 => 43,
        44 => 44,
        45 => 45,
        48 => 48,
        68 => 48,
        49 => 49,
        50 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        72 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        58 => 58,
        59 => 58,
        60 => 60,
        61 => 61,
        62 => 62,
        64 => 64,
        65 => 65,
        66 => 66,
        69 => 69,
        71 => 71,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 75,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        81 => 81,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        86 => 86,
        88 => 88,
        90 => 90,
        91 => 90,
        95 => 95,
        96 => 96,
        98 => 98,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 60 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1447 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1450 "internal.templateparser.php"
#line 68 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1453 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1456 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1459 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',array('var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1462 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor,$this->nocache);$this->nocache=false;    }
#line 1465 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag('end_'.$this->yystack[$this->yyidx + -1]->minor,array());    }
#line 1468 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1471 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -12]->minor,array('var'=>$this->yystack[$this->yyidx + -9]->minor,'start'=>$this->yystack[$this->yyidx + -7]->minor,'ifexp'=>$this->yystack[$this->yyidx + -5]->minor,'loop'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1474 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -6]->minor,array('from'=>$this->yystack[$this->yyidx + -1]->minor,'item'=>$this->yystack[$this->yyidx + -3]->minor));    }
#line 1477 "internal.templateparser.php"
#line 106 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1480 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1483 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1486 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = array();    }
#line 1489 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1492 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r27(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1495 "internal.templateparser.php"
#line 135 "internal.templateparser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1498 "internal.templateparser.php"
#line 139 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1501 "internal.templateparser.php"
#line 141 "internal.templateparser.y"
    function yy_r31(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1504 "internal.templateparser.php"
#line 159 "internal.templateparser.y"
    function yy_r36(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1507 "internal.templateparser.php"
#line 169 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1510 "internal.templateparser.php"
#line 171 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1513 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -2]->minor .')->prop[\''.$this->yystack[$this->yyidx + 0]->minor.'\']'; $_v = trim($this->yystack[$this->yyidx + -2]->minor,"'"); if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1516 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor;$_v = trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1519 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r45(){ $this->_retvalue = '$_'. strtoupper($this->yystack[$this->yyidx + -1]->minor).$this->yystack[$this->yyidx + 0]->minor;    }
#line 1522 "internal.templateparser.php"
#line 187 "internal.templateparser.y"
    function yy_r48(){return;    }
#line 1525 "internal.templateparser.php"
#line 189 "internal.templateparser.y"
    function yy_r49(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + 0]->minor ."]";    }
#line 1528 "internal.templateparser.php"
#line 191 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1531 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r52(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1534 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r53(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1537 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r54(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1540 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_v=trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1543 "internal.templateparser.php"
#line 208 "internal.templateparser.y"
    function yy_r56(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1546 "internal.templateparser.php"
#line 210 "internal.templateparser.y"
    function yy_r57(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1549 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1552 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1555 "internal.templateparser.php"
#line 229 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1558 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1561 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r64(){ return;    }
#line 1564 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r65(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1567 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r66(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1570 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1573 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1576 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1579 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1582 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1585 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '==';    }
#line 1588 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '!=';    }
#line 1591 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '>';    }
#line 1594 "internal.templateparser.php"
#line 273 "internal.templateparser.y"
    function yy_r80(){$this->_retvalue = '<';    }
#line 1597 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r81(){$this->_retvalue = '>=';    }
#line 1600 "internal.templateparser.php"
#line 275 "internal.templateparser.y"
    function yy_r82(){$this->_retvalue = '<=';    }
#line 1603 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r83(){$this->_retvalue = '===';    }
#line 1606 "internal.templateparser.php"
#line 278 "internal.templateparser.y"
    function yy_r84(){$this->_retvalue = '&&';    }
#line 1609 "internal.templateparser.php"
#line 279 "internal.templateparser.y"
    function yy_r85(){$this->_retvalue = '||';    }
#line 1612 "internal.templateparser.php"
#line 281 "internal.templateparser.y"
    function yy_r86(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1615 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r88(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1618 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r90(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1621 "internal.templateparser.php"
#line 291 "internal.templateparser.y"
    function yy_r95(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1624 "internal.templateparser.php"
#line 292 "internal.templateparser.y"
    function yy_r96(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1627 "internal.templateparser.php"
#line 295 "internal.templateparser.y"
    function yy_r98(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1630 "internal.templateparser.php"

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
#line 44 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1747 "internal.templateparser.php"
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
#line 36 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1772 "internal.templateparser.php"
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
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
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
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
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
