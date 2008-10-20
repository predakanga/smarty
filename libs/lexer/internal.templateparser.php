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
    const TP_AS                             = 38;
    const TP_COMMENTSTART                   = 39;
    const TP_COMMENTEND                     = 40;
    const TP_PHP                            = 41;
    const TP_LDEL                           = 42;
    const YY_NO_ACTION = 262;
    const YY_ACCEPT_ACTION = 261;
    const YY_ERROR_ACTION = 260;

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
    const YY_SZ_ACTTAB = 481;
static public $yy_action = array(
 /*     0 */   138,  122,  123,  123,  144,   10,   23,   91,   27,  138,
 /*    10 */   122,  108,   36,   88,    9,   11,   66,   27,  154,   99,
 /*    20 */   105,  103,  104,  107,  101,  100,  131,  124,   99,  105,
 /*    30 */   103,  104,  107,  101,  100,  121,   17,   28,   23,    2,
 /*    40 */   121,  146,   28,   39,    7,   70,  136,  137,   39,   64,
 /*    50 */   154,  118,   22,   96,  158,   24,  138,  122,   96,  158,
 /*    60 */   144,   41,    3,  153,   27,   32,  145,    3,   17,  162,
 /*    70 */    32,  145,  121,   93,   28,  164,    2,  161,  157,   85,
 /*    80 */    39,   42,  151,  143,  155,   72,  162,  154,  127,   69,
 /*    90 */    77,  158,  163,   10,  161,  157,  121,  127,   28,    3,
 /*   100 */    16,   38,   32,  145,   39,   17,   18,   63,   73,  148,
 /*   110 */    38,  110,  109,  147,   96,  158,  121,   34,   28,   68,
 /*   120 */     1,   52,  162,  159,   39,   69,   32,  145,  163,   20,
 /*   130 */   161,  157,  129,   65,   96,  158,  138,  122,   20,  116,
 /*   140 */    50,  113,  125,  155,   27,  162,   32,  145,   69,  110,
 /*   150 */   109,  163,   34,  161,  157,   85,   56,   76,   14,  149,
 /*   160 */    69,  138,  122,  163,   23,  161,  157,   34,  156,   27,
 /*   170 */    10,   59,  162,  139,  186,   69,  113,  162,  163,   10,
 /*   180 */   161,  157,  120,  130,   33,  161,  157,   11,   59,  162,
 /*   190 */     5,   75,   69,   15,   31,  163,  121,  161,  157,  121,
 /*   200 */    16,   28,  128,    1,   39,  138,  122,   39,   82,  144,
 /*   210 */   140,  138,  122,   27,   96,  158,   84,   74,  158,   27,
 /*   220 */    81,   19,    4,  138,  122,   23,   32,  145,   34,   32,
 /*   230 */   145,   27,   60,  162,   87,   66,   69,  154,  162,  163,
 /*   240 */   150,  161,  157,  165,  152,   33,  161,  157,    8,   59,
 /*   250 */   162,   89,  113,   69,   79,   17,  163,  112,  161,  157,
 /*   260 */   121,   92,   28,  114,   16,  232,  232,   29,   40,   78,
 /*   270 */    70,  136,  137,   67,  141,  132,  230,  230,   37,  158,
 /*   280 */    21,  261,   35,  106,   97,  126,   80,  138,  122,   46,
 /*   290 */    32,  145,  117,   61,  162,   27,  111,   69,   98,  147,
 /*   300 */   163,  102,  161,  157,   29,  138,  122,  110,  109,   58,
 /*   310 */    26,   95,  160,   27,  162,   12,   25,   69,   29,   85,
 /*   320 */   163,    6,  161,  157,  115,   97,  140,  119,   43,   66,
 /*   330 */   134,   86,  140,  162,   71,   83,   69,   94,  142,  163,
 /*   340 */    46,  161,  157,  166,   46,  162,   62,   38,   69,  162,
 /*   350 */   174,  163,   69,  161,  157,  163,  174,  161,  157,   30,
 /*   360 */   138,  122,   90,  174,  162,  174,  133,   69,   27,   13,
 /*   370 */   163,  174,  161,  157,  135,  174,  138,  122,   49,  174,
 /*   380 */   138,  122,  174,  162,   27,  174,   69,  174,   27,  163,
 /*   390 */    44,  161,  157,  174,   53,  162,   19,  174,   69,  162,
 /*   400 */   174,  163,   69,  161,  157,  163,  174,  161,  157,   48,
 /*   410 */   174,  174,  174,  174,  162,  174,   45,   69,  174,  174,
 /*   420 */   163,  162,  161,  157,   69,  174,  174,  163,   54,  161,
 /*   430 */   157,  174,  174,  162,  174,  174,   69,  174,  174,  163,
 /*   440 */    57,  161,  157,  174,   47,  162,  174,  174,   69,  162,
 /*   450 */   174,  163,   69,  161,  157,  163,  174,  161,  157,   55,
 /*   460 */   174,  174,  174,  174,  162,  174,   51,   69,  174,  174,
 /*   470 */   163,  162,  161,  157,   69,  174,  174,  163,  174,  161,
 /*   480 */   157,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   54,   54,   11,   10,   12,   19,   15,    7,
 /*    10 */     8,    1,   64,    3,   20,   20,   22,   15,   24,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   78,   78,   26,   27,
 /*    30 */    28,   29,   30,   31,   32,    6,   42,    8,   12,   10,
 /*    40 */     6,   72,    8,   14,   10,   65,   66,   67,   14,   39,
 /*    50 */    24,   41,   42,   24,   25,   20,    7,    8,   24,   25,
 /*    60 */    11,   58,   33,    5,   15,   36,   37,   33,   42,   54,
 /*    70 */    36,   37,    6,   38,    8,   60,   10,   62,   63,   21,
 /*    80 */    14,   49,    5,   66,   52,   11,   54,   24,    1,   57,
 /*    90 */    24,   25,   60,   10,   62,   63,    6,    1,    8,   33,
 /*   100 */    10,   14,   36,   37,   14,   42,   23,   50,   76,   77,
 /*   110 */    14,   34,   35,   56,   24,   25,    6,   49,    8,   59,
 /*   120 */    10,   53,   54,   36,   14,   57,   36,   37,   60,   42,
 /*   130 */    62,   63,   72,   18,   24,   25,    7,    8,   42,    5,
 /*   140 */    49,   73,   13,   52,   15,   54,   36,   37,   57,   34,
 /*   150 */    35,   60,   49,   62,   63,   21,   53,   54,   17,    5,
 /*   160 */    57,    7,    8,   60,   12,   62,   63,   49,   77,   15,
 /*   170 */    10,   53,   54,   11,    5,   57,   73,   54,   60,   10,
 /*   180 */    62,   63,    9,   60,   49,   62,   63,   20,   53,   54,
 /*   190 */    21,   73,   57,   20,   51,   60,    6,   62,   63,    6,
 /*   200 */    10,    8,   24,   10,   14,    7,    8,   14,   73,   11,
 /*   210 */    67,    7,    8,   15,   24,   25,   24,   24,   25,   15,
 /*   220 */    23,   23,   18,    7,    8,   12,   36,   37,   49,   36,
 /*   230 */    37,   15,   53,   54,   24,   22,   57,   24,   54,   60,
 /*   240 */     5,   62,   63,   11,   60,   49,   62,   63,   16,   53,
 /*   250 */    54,   24,   73,   57,   24,   42,   60,    1,   62,   63,
 /*   260 */     6,   14,    8,    5,   10,   34,   35,   61,   14,   73,
 /*   270 */    65,   66,   67,   68,   69,   11,   34,   35,   24,   25,
 /*   280 */    74,   44,   45,   46,   47,    5,   14,    7,    8,   49,
 /*   290 */    36,   37,   11,   50,   54,   15,   40,   57,    5,   56,
 /*   300 */    60,    5,   62,   63,   61,    7,    8,   34,   35,   49,
 /*   310 */    51,   71,   52,   15,   54,   10,   51,   57,   61,   21,
 /*   320 */    60,   75,   62,   63,   46,   47,   67,    1,   49,   22,
 /*   330 */    69,   52,   67,   54,   48,   24,   57,   55,   70,   60,
 /*   340 */    49,   62,   63,   56,   49,   54,   54,   14,   57,   54,
 /*   350 */    79,   60,   57,   62,   63,   60,   79,   62,   63,   49,
 /*   360 */     7,    8,   71,   79,   54,   79,   71,   57,   15,   16,
 /*   370 */    60,   79,   62,   63,    5,   79,    7,    8,   49,   79,
 /*   380 */     7,    8,   79,   54,   15,   79,   57,   79,   15,   60,
 /*   390 */    49,   62,   63,   79,   49,   54,   23,   79,   57,   54,
 /*   400 */    79,   60,   57,   62,   63,   60,   79,   62,   63,   49,
 /*   410 */    79,   79,   79,   79,   54,   79,   49,   57,   79,   79,
 /*   420 */    60,   54,   62,   63,   57,   79,   79,   60,   49,   62,
 /*   430 */    63,   79,   79,   54,   79,   79,   57,   79,   79,   60,
 /*   440 */    49,   62,   63,   79,   49,   54,   79,   79,   57,   54,
 /*   450 */    79,   60,   57,   62,   63,   60,   79,   62,   63,   49,
 /*   460 */    79,   79,   79,   79,   54,   79,   49,   57,   79,   79,
 /*   470 */    60,   54,   62,   63,   57,   79,   79,   60,   79,   62,
 /*   480 */    63,
);
    const YY_SHIFT_USE_DFLT = -13;
    const YY_SHIFT_MAX = 96;
    static public $yy_shift_ofst = array(
 /*     0 */    10,  193,   29,   34,   29,   66,   29,   29,  193,  110,
 /*    10 */    90,  110,   90,   90,   90,   90,   90,   90,   90,   90,
 /*    20 */    90,   90,  254,   90,   90,   -6,  213,  190,  190,  190,
 /*    30 */   298,   26,   96,   -7,    2,   10,   87,  169,   63,   63,
 /*    40 */    63,  141,  198,  154,  204,  369,  353,  280,   49,  129,
 /*    50 */   373,  216,  115,  216,  216,  216,   77,  216,  216,  273,
 /*    60 */   273,  134,  173,   58,  326,  333,  311,  307,  141,  -12,
 /*    70 */   152,  256,  231,  232,   83,  242,   35,   -5,   74,  258,
 /*    80 */   230,  272,  281,  305,  167,  192,  235,  296,  210,  197,
 /*    90 */   162,  178,  227,  247,  293,  264,  160,
);
    const YY_REDUCE_USE_DFLT = -53;
    const YY_REDUCE_MAX = 70;
    static public $yy_reduce_ofst = array(
 /*     0 */   237,   32,  135,  118,   68,  103,  179,  196,   91,  279,
 /*    10 */   240,  260,  291,  295,  391,  345,  360,  367,  410,  417,
 /*    20 */   395,  379,  310,  329,  341,  205,  205,  123,   15,  184,
 /*    30 */   243,  -20,  -52,  206,  206,  278,  -51,   57,  143,  259,
 /*    40 */   265,   60,  257,  257,  257,  257,  257,  257,  257,  257,
 /*    50 */   257,  257,  246,  257,  257,  257,  246,  257,  257,  246,
 /*    60 */   246,  287,  282,  287,  286,  292,  268,  261,  -31,    3,
 /*    70 */    17,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 39, 41, 42, ),
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
        /* 14 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
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
        /* 25 */ array(12, 20, 22, 24, 42, ),
        /* 26 */ array(12, 22, 24, 42, ),
        /* 27 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 28 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 29 */ array(6, 10, 14, 24, 25, 36, 37, ),
        /* 30 */ array(7, 8, 15, 21, ),
        /* 31 */ array(12, 24, 42, ),
        /* 32 */ array(1, 14, 42, ),
        /* 33 */ array(7, 8, 11, 15, 26, 27, 28, 29, 30, 31, 32, ),
        /* 34 */ array(7, 8, 15, 26, 27, 28, 29, 30, 31, 32, ),
        /* 35 */ array(1, 3, 39, 41, 42, ),
        /* 36 */ array(1, 14, 36, 42, ),
        /* 37 */ array(5, 10, 21, ),
        /* 38 */ array(24, 42, ),
        /* 39 */ array(24, 42, ),
        /* 40 */ array(24, 42, ),
        /* 41 */ array(17, ),
        /* 42 */ array(7, 8, 11, 15, 23, ),
        /* 43 */ array(5, 7, 8, 15, ),
        /* 44 */ array(7, 8, 15, 18, ),
        /* 45 */ array(5, 7, 8, 15, ),
        /* 46 */ array(7, 8, 15, 16, ),
        /* 47 */ array(5, 7, 8, 15, ),
        /* 48 */ array(7, 8, 11, 15, ),
        /* 49 */ array(7, 8, 13, 15, ),
        /* 50 */ array(7, 8, 15, 23, ),
        /* 51 */ array(7, 8, 15, ),
        /* 52 */ array(18, 34, 35, ),
        /* 53 */ array(7, 8, 15, ),
        /* 54 */ array(7, 8, 15, ),
        /* 55 */ array(7, 8, 15, ),
        /* 56 */ array(5, 34, 35, ),
        /* 57 */ array(7, 8, 15, ),
        /* 58 */ array(7, 8, 15, ),
        /* 59 */ array(34, 35, ),
        /* 60 */ array(34, 35, ),
        /* 61 */ array(5, 21, ),
        /* 62 */ array(9, 20, ),
        /* 63 */ array(5, 21, ),
        /* 64 */ array(1, ),
        /* 65 */ array(14, ),
        /* 66 */ array(24, ),
        /* 67 */ array(22, ),
        /* 68 */ array(17, ),
        /* 69 */ array(19, ),
        /* 70 */ array(12, ),
        /* 71 */ array(1, 40, ),
        /* 72 */ array(34, 35, ),
        /* 73 */ array(11, 16, ),
        /* 74 */ array(10, 23, ),
        /* 75 */ array(34, 35, ),
        /* 76 */ array(20, 38, ),
        /* 77 */ array(10, 20, ),
        /* 78 */ array(11, ),
        /* 79 */ array(5, ),
        /* 80 */ array(24, ),
        /* 81 */ array(14, ),
        /* 82 */ array(11, ),
        /* 83 */ array(10, ),
        /* 84 */ array(20, ),
        /* 85 */ array(24, ),
        /* 86 */ array(5, ),
        /* 87 */ array(5, ),
        /* 88 */ array(24, ),
        /* 89 */ array(23, ),
        /* 90 */ array(11, ),
        /* 91 */ array(24, ),
        /* 92 */ array(24, ),
        /* 93 */ array(14, ),
        /* 94 */ array(5, ),
        /* 95 */ array(11, ),
        /* 96 */ array(10, ),
        /* 97 */ array(),
        /* 98 */ array(),
        /* 99 */ array(),
        /* 100 */ array(),
        /* 101 */ array(),
        /* 102 */ array(),
        /* 103 */ array(),
        /* 104 */ array(),
        /* 105 */ array(),
        /* 106 */ array(),
        /* 107 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   260,  260,  260,  260,  260,  260,  260,  260,  260,  260,
 /*    10 */   224,  260,  224,  224,  260,  260,  260,  260,  260,  260,
 /*    20 */   260,  260,  260,  260,  260,  206,  206,  260,  260,  260,
 /*    30 */   186,  206,  260,  234,  234,  167,  260,  205,  260,  260,
 /*    40 */   260,  228,  249,  260,  260,  260,  223,  260,  260,  260,
 /*    50 */   249,  250,  260,  182,  235,  251,  260,  229,  187,  260,
 /*    60 */   236,  260,  260,  260,  260,  260,  260,  215,  190,  189,
 /*    70 */   207,  260,  233,  260,  205,  231,  200,  205,  230,  260,
 /*    80 */   260,  260,  230,  218,  260,  260,  260,  260,  260,  260,
 /*    90 */   260,  260,  260,  260,  260,  260,  205,  170,  180,  237,
 /*   100 */   243,  242,  178,  239,  240,  238,  168,  241,  173,  245,
 /*   110 */   244,  171,  258,  230,  181,  169,  174,  232,  172,  259,
 /*   120 */   183,  197,  195,  255,  253,  210,  256,  257,  225,  227,
 /*   130 */   194,  254,  220,  222,  217,  214,  208,  212,  196,  221,
 /*   140 */   211,  216,  219,  209,  199,  198,  226,  185,  247,  175,
 /*   150 */   176,  179,  193,  177,  213,  252,  248,  202,  203,  204,
 /*   160 */   188,  201,  200,  191,  192,  246,  184,
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
    const YYNOCODE = 80;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 167;
    const YYNRULE = 93;
    const YYERRORSYMBOL = 43;
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
    1,  /*         AS => OTHER */
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
  'QUOTE',         'BOOLEAN',       'AS',            'COMMENTSTART',
  'COMMENTEND',    'PHP',           'LDEL',          'error',       
  'start',         'template',      'template_element',  'smartytag',   
  'commenttext',   'expr',          'attributes',    'varvar',      
  'array',         'ifexprs',       'variable',      'foraction',   
  'attribute',     'exprs',         'modifier',      'modparameters',
  'value',         'math',          'object',        'function',    
  'doublequoted',  'vararraydefs',  'vararraydef',   'varvarele',   
  'objectchain',   'objectelement',  'method',        'params',      
  'modparameter',  'ifexpr',        'ifcond',        'lop',         
  'arrayelements',  'arrayelement',  'other',       
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
 /*  11 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  12 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE variable AS DOLLAR ID APTR DOLLAR ID RDEL",
 /*  15 */ "foraction ::= EQUAL expr",
 /*  16 */ "foraction ::= INCDEC",
 /*  17 */ "attributes ::= attributes attribute",
 /*  18 */ "attributes ::= attribute",
 /*  19 */ "attributes ::=",
 /*  20 */ "attribute ::= SPACE ID EQUAL expr",
 /*  21 */ "attribute ::= SPACE ID EQUAL array",
 /*  22 */ "expr ::= exprs",
 /*  23 */ "expr ::= exprs modifier modparameters",
 /*  24 */ "exprs ::= value",
 /*  25 */ "exprs ::= UNIMATH value",
 /*  26 */ "exprs ::= expr math value",
 /*  27 */ "exprs ::= expr DOT value",
 /*  28 */ "math ::= UNIMATH",
 /*  29 */ "math ::= MATH",
 /*  30 */ "value ::= NUMBER",
 /*  31 */ "value ::= BOOLEAN",
 /*  32 */ "value ::= OPENP expr CLOSEP",
 /*  33 */ "value ::= variable",
 /*  34 */ "value ::= object",
 /*  35 */ "value ::= function",
 /*  36 */ "value ::= SI_QSTR",
 /*  37 */ "value ::= QUOTE doublequoted QUOTE",
 /*  38 */ "value ::= ID",
 /*  39 */ "variable ::= DOLLAR varvar",
 /*  40 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  41 */ "vararraydefs ::= vararraydef",
 /*  42 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  43 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  44 */ "varvar ::= varvarele",
 /*  45 */ "varvar ::= varvar varvarele",
 /*  46 */ "varvarele ::= ID",
 /*  47 */ "varvarele ::= LDEL expr RDEL",
 /*  48 */ "object ::= DOLLAR varvar objectchain",
 /*  49 */ "objectchain ::= objectelement",
 /*  50 */ "objectchain ::= objectchain objectelement",
 /*  51 */ "objectelement ::= PTR ID",
 /*  52 */ "objectelement ::= PTR method",
 /*  53 */ "function ::= ID OPENP params CLOSEP",
 /*  54 */ "method ::= ID OPENP params CLOSEP",
 /*  55 */ "params ::= expr COMMA params",
 /*  56 */ "params ::= expr",
 /*  57 */ "params ::=",
 /*  58 */ "modifier ::= VERT ID",
 /*  59 */ "modparameters ::= modparameters modparameter",
 /*  60 */ "modparameters ::= modparameter",
 /*  61 */ "modparameters ::=",
 /*  62 */ "modparameter ::= COLON expr",
 /*  63 */ "ifexprs ::= ifexpr",
 /*  64 */ "ifexprs ::= NOT ifexpr",
 /*  65 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  66 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  67 */ "ifexpr ::= expr",
 /*  68 */ "ifexpr ::= expr ifcond expr",
 /*  69 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  70 */ "ifcond ::= EQUALS",
 /*  71 */ "ifcond ::= NOTEQUALS",
 /*  72 */ "ifcond ::= GREATERTHAN",
 /*  73 */ "ifcond ::= LESSTHAN",
 /*  74 */ "ifcond ::= GREATEREQUAL",
 /*  75 */ "ifcond ::= LESSEQUAL",
 /*  76 */ "ifcond ::= IDENTITY",
 /*  77 */ "lop ::= LAND",
 /*  78 */ "lop ::= LOR",
 /*  79 */ "array ::= OPENP arrayelements CLOSEP",
 /*  80 */ "arrayelements ::= arrayelement",
 /*  81 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  82 */ "arrayelement ::= expr",
 /*  83 */ "arrayelement ::= expr APTR expr",
 /*  84 */ "arrayelement ::= ID APTR expr",
 /*  85 */ "arrayelement ::= array",
 /*  86 */ "doublequoted ::= doublequoted other",
 /*  87 */ "doublequoted ::= other",
 /*  88 */ "other ::= variable",
 /*  89 */ "other ::= LDEL expr RDEL",
 /*  90 */ "other ::= OTHER",
 /*  91 */ "commenttext ::= commenttext OTHER",
 /*  92 */ "commenttext ::= OTHER",
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
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 6 ),
  array( 'lhs' => 47, 'rhs' => 6 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 5 ),
  array( 'lhs' => 47, 'rhs' => 12 ),
  array( 'lhs' => 47, 'rhs' => 11 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 0 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 0 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 0 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        24 => 0,
        30 => 0,
        31 => 0,
        33 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        80 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        22 => 1,
        28 => 1,
        29 => 1,
        41 => 1,
        44 => 1,
        60 => 1,
        63 => 1,
        87 => 1,
        90 => 1,
        92 => 1,
        2 => 2,
        42 => 2,
        86 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 8,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        18 => 16,
        56 => 16,
        82 => 16,
        85 => 16,
        17 => 17,
        19 => 19,
        20 => 20,
        21 => 20,
        23 => 23,
        25 => 25,
        26 => 26,
        27 => 27,
        32 => 32,
        37 => 37,
        38 => 38,
        39 => 39,
        40 => 40,
        43 => 43,
        45 => 45,
        46 => 46,
        47 => 47,
        65 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 51,
        53 => 53,
        54 => 54,
        55 => 55,
        57 => 57,
        58 => 58,
        59 => 59,
        61 => 61,
        62 => 62,
        64 => 64,
        66 => 66,
        67 => 67,
        68 => 68,
        69 => 68,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        81 => 81,
        83 => 83,
        84 => 83,
        88 => 88,
        89 => 89,
        91 => 91,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 60 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1390 "internal.templateparser.php"
#line 66 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1393 "internal.templateparser.php"
#line 68 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1396 "internal.templateparser.php"
#line 76 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1399 "internal.templateparser.php"
#line 87 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->compiler->compileTag('print_expression',array_merge(array('value'=>$this->yystack[$this->yyidx + -2]->minor),$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1402 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->compiler->compileTag('assign',array('var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor),$this->nocache);$this->nocache=false;    }
#line 1405 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -2]->minor,$this->yystack[$this->yyidx + -1]->minor,$this->nocache);$this->nocache=false;    }
#line 1408 "internal.templateparser.php"
#line 94 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->compiler->compileTag('end_'.$this->yystack[$this->yyidx + -1]->minor,array());    }
#line 1411 "internal.templateparser.php"
#line 96 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -3]->minor,array('ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1414 "internal.templateparser.php"
#line 98 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -10]->minor,array('start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1417 "internal.templateparser.php"
#line 100 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->compiler->compileTag($this->yystack[$this->yyidx + -9]->minor,array('from'=>$this->yystack[$this->yyidx + -7]->minor,'key'=>$this->yystack[$this->yyidx + -4]->minor,'item'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1420 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1423 "internal.templateparser.php"
#line 102 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1426 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1429 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array();    }
#line 1432 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1435 "internal.templateparser.php"
#line 125 "internal.templateparser.y"
    function yy_r23(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1438 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1441 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1444 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1447 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1450 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1453 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1456 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + 0]->minor .')->value'; $_v = trim($this->yystack[$this->yyidx + 0]->minor,"'"); if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1459 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor;$_v = trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1462 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1465 "internal.templateparser.php"
#line 188 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1468 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1471 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1474 "internal.templateparser.php"
#line 197 "internal.templateparser.y"
    function yy_r48(){ $this->_retvalue = '$this->tpl_vars->getVariable('. $this->yystack[$this->yyidx + -1]->minor .')->value'.$this->yystack[$this->yyidx + 0]->minor; $_v=trim($this->yystack[$this->yyidx + -1]->minor,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;    }
#line 1477 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1480 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1483 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1486 "internal.templateparser.php"
#line 212 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1489 "internal.templateparser.php"
#line 220 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1492 "internal.templateparser.php"
#line 226 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1495 "internal.templateparser.php"
#line 230 "internal.templateparser.y"
    function yy_r57(){ return;    }
#line 1498 "internal.templateparser.php"
#line 235 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1501 "internal.templateparser.php"
#line 238 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1504 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r61(){return;    }
#line 1507 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r62(){$this->_retvalue = ','.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1510 "internal.templateparser.php"
#line 251 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1513 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1516 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1519 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1522 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '==';    }
#line 1525 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '!=';    }
#line 1528 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '>';    }
#line 1531 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '<';    }
#line 1534 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '>=';    }
#line 1537 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '<=';    }
#line 1540 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '===';    }
#line 1543 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '&&';    }
#line 1546 "internal.templateparser.php"
#line 270 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '||';    }
#line 1549 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1552 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1555 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1558 "internal.templateparser.php"
#line 282 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1561 "internal.templateparser.php"
#line 283 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1564 "internal.templateparser.php"
#line 286 "internal.templateparser.y"
    function yy_r91(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1567 "internal.templateparser.php"

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
#line 1684 "internal.templateparser.php"
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
#line 1709 "internal.templateparser.php"
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
