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

    function __construct($lex) {
        // set instance object
        self::instance($this); 
        $this->lex = $lex;
        $this->smarty = Smarty::instance(); 
        $this->compiler = Smarty_Internal_Compiler::instance(); 
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Tag");
        $this->smarty->compile_tag = new Smarty_Internal_Compile_Smarty_Tag;
 //       $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Variable");
 //       $this->smarty->compile_variable = new Smarty_Internal_Compile_Smarty_Variable;
				$this->nocache = false;
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
#line 135 "internal.templateparser.php"

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
    const TP_NOCACHE                        = 43;
    const YY_NO_ACTION = 265;
    const YY_ACCEPT_ACTION = 264;
    const YY_ERROR_ACTION = 263;

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
    const YY_SZ_ACTTAB = 464;
static public $yy_action = array(
 /*     0 */   136,  137,   68,   35,  160,   48,  116,  121,   28,  121,
 /*    10 */   148,  107,   91,  107,  158,  156,  157,  156,  157,  100,
 /*    20 */   104,   97,  103,   96,  102,  120,   80,  136,  137,   72,
 /*    30 */   147,   95,  136,  137,  152,   28,   66,  138,  139,   91,
 /*    40 */    28,  143,   90,    7,   91,  118,  100,  104,   97,  103,
 /*    50 */    96,  102,  120,  101,   57,   27,   29,    3,  101,   14,
 /*    60 */    27,   55,    3,  106,  114,    9,   55,  264,   39,   98,
 /*    70 */   109,   78,  165,   99,   52,   11,   89,  165,  121,  119,
 /*    80 */     5,  162,  107,   34,  108,    5,  156,  157,   34,  108,
 /*    90 */   142,  101,   57,   27,   29,    6,  101,  111,   27,   55,
 /*   100 */    12,  131,   33,  163,   55,  106,  114,   19,   18,   89,
 /*   110 */   165,   81,  136,  137,   89,  165,  160,  126,    5,   79,
 /*   120 */    28,   34,  108,   85,   91,    9,   34,  108,  101,  127,
 /*   130 */    27,   10,   12,  124,   15,  101,   55,   27,   21,   12,
 /*   140 */   136,  137,  142,   55,  160,    9,   89,  165,   28,  136,
 /*   150 */   137,  135,   91,   89,  165,  130,   22,   28,   34,  108,
 /*   160 */   101,   91,   27,  145,    1,   34,  108,  101,   55,   27,
 /*   170 */   151,   12,  136,  137,   83,   54,   56,  128,   89,  165,
 /*   180 */    28,  136,  137,  164,   91,   53,  165,   51,    8,   28,
 /*   190 */    34,  108,  101,   91,   27,  128,    1,   34,  108,  101,
 /*   200 */    55,  161,  117,   12,   23,  233,  233,   55,   82,  154,
 /*   210 */    73,  165,   66,  138,  139,   69,  122,   89,  165,  129,
 /*   220 */    40,  110,   34,  108,  121,   79,   13,  148,  107,   34,
 /*   230 */   108,   41,  156,  157,   16,  121,   70,   32,  143,  107,
 /*   240 */    58,   74,   11,  156,  157,  107,   77,  149,  105,  156,
 /*   250 */   157,   31,  235,  235,   60,  121,   14,   13,   32,  107,
 /*   260 */   113,   60,  121,  156,  157,   87,  107,   70,  133,  143,
 /*   270 */   156,  157,    4,   15,   84,   71,   26,   13,  136,  137,
 /*   280 */    31,   76,  145,   60,  121,   13,   28,   14,  107,  143,
 /*   290 */    91,  126,  156,  157,   22,   56,   32,   61,   32,   59,
 /*   300 */   121,   64,  121,   86,  107,   25,  107,   14,  156,  157,
 /*   310 */   156,  157,  166,   17,  136,  137,  153,  159,   94,  113,
 /*   320 */   126,  113,   28,   23,  125,   48,   91,   67,   79,  121,
 /*   330 */   132,   93,   92,  107,   20,  155,   56,  156,  157,  141,
 /*   340 */     9,  136,  137,  106,  114,  144,   75,  136,  137,   28,
 /*   350 */   123,    2,   88,   91,   65,   28,  168,   24,   70,   91,
 /*   360 */    44,  140,   42,  167,  121,  175,  121,  175,  107,  175,
 /*   370 */   107,  175,  156,  157,  156,  157,   38,  121,  175,   37,
 /*   380 */   121,  134,  175,  121,  107,  156,  157,  107,  156,  157,
 /*   390 */   175,  156,  157,   43,  112,  109,   46,  121,  175,   45,
 /*   400 */   121,  107,  175,  121,  107,  156,  157,  107,  156,  157,
 /*   410 */    47,  156,  157,   49,  121,  175,  175,  121,  107,  175,
 /*   420 */   175,  107,  156,  157,   36,  156,  157,   30,  121,  175,
 /*   430 */    50,  121,  107,  175,  121,  107,  156,  157,  107,  156,
 /*   440 */   157,   63,  156,  157,  175,  175,  146,  175,  121,   57,
 /*   450 */   175,   29,  150,  175,  121,   62,  156,  157,  115,  175,
 /*   460 */   146,  175,  156,  157,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   60,   50,   11,   50,    1,   54,   15,   54,
 /*    10 */    57,   58,   19,   58,   72,   62,   63,   62,   63,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   71,    7,    8,   76,
 /*    30 */    77,   24,    7,    8,    5,   15,   65,   66,   67,   19,
 /*    40 */    15,   24,   14,   18,   19,   40,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   59,    8,   61,   10,    6,   42,
 /*    60 */     8,   14,   10,   34,   35,   10,   14,   45,   46,   47,
 /*    70 */    48,   24,   25,    5,   50,   20,   24,   25,   54,    5,
 /*    80 */    33,   57,   58,   36,   37,   33,   62,   63,   36,   37,
 /*    90 */    43,    6,   59,    8,   61,   10,    6,    9,    8,   14,
 /*   100 */    10,   11,   52,    5,   14,   34,   35,   74,   20,   24,
 /*   110 */    25,   24,    7,    8,   24,   25,   11,   67,   33,   21,
 /*   120 */    15,   36,   37,   24,   19,   10,   36,   37,    6,   11,
 /*   130 */     8,   10,   10,   11,   16,    6,   14,    8,   23,   10,
 /*   140 */     7,    8,   43,   14,   11,   10,   24,   25,   15,    7,
 /*   150 */     8,   24,   19,   24,   25,   13,   23,   15,   36,   37,
 /*   160 */     6,   19,    8,    1,   10,   36,   37,    6,   14,    8,
 /*   170 */     5,   10,    7,    8,   23,   14,   14,   54,   24,   25,
 /*   180 */    15,    7,    8,   11,   19,   24,   25,   64,   16,   15,
 /*   190 */    36,   37,    6,   19,    8,   54,   10,   36,   37,    6,
 /*   200 */    14,   78,   11,   10,   42,   34,   35,   14,   14,    5,
 /*   210 */    24,   25,   65,   66,   67,   68,   69,   24,   25,   78,
 /*   220 */    50,    5,   36,   37,   54,   21,   12,   57,   58,   36,
 /*   230 */    37,   50,   62,   63,   20,   54,   22,   50,   24,   58,
 /*   240 */    53,   54,   20,   62,   63,   58,   11,   77,    1,   62,
 /*   250 */    63,   50,   34,   35,   53,   54,   42,   12,   50,   58,
 /*   260 */    73,   53,   54,   62,   63,   24,   58,   22,   11,   24,
 /*   270 */    62,   63,   75,   16,   73,   49,   52,   12,    7,    8,
 /*   280 */    50,   73,    1,   53,   54,   12,   15,   42,   58,   24,
 /*   290 */    19,   67,   62,   63,   23,   14,   50,   54,   50,   53,
 /*   300 */    54,   53,   54,   73,   58,   52,   58,   42,   62,   63,
 /*   310 */    62,   63,    5,   20,    7,    8,    1,   36,    3,   73,
 /*   320 */    67,   73,   15,   42,   66,   50,   19,   18,   21,   54,
 /*   330 */    56,   38,   55,   58,   17,    5,   14,   62,   63,    5,
 /*   340 */    10,    7,    8,   34,   35,    5,   71,    7,    8,   15,
 /*   350 */    70,   21,   24,   19,   39,   15,   41,   42,   22,   19,
 /*   360 */    50,   69,   50,   72,   54,   79,   54,   79,   58,   79,
 /*   370 */    58,   79,   62,   63,   62,   63,   50,   54,   79,   50,
 /*   380 */    54,   58,   79,   54,   58,   62,   63,   58,   62,   63,
 /*   390 */    79,   62,   63,   50,   47,   48,   50,   54,   79,   50,
 /*   400 */    54,   58,   79,   54,   58,   62,   63,   58,   62,   63,
 /*   410 */    50,   62,   63,   50,   54,   79,   79,   54,   58,   79,
 /*   420 */    79,   58,   62,   63,   50,   62,   63,   50,   54,   79,
 /*   430 */    50,   54,   58,   79,   54,   58,   62,   63,   58,   62,
 /*   440 */    63,   51,   62,   63,   79,   79,   56,   79,   54,   59,
 /*   450 */    79,   61,   58,   79,   54,   51,   62,   63,   58,   79,
 /*   460 */    56,   79,   62,   63,
);
    const YY_SHIFT_USE_DFLT = -8;
    const YY_SHIFT_MAX = 95;
    static public $yy_shift_ofst = array(
 /*     0 */   315,  186,   47,   52,   52,   85,   52,   52,  186,   90,
 /*    10 */   122,  154,  129,  129,  129,  129,  129,  129,  129,  129,
 /*    20 */   129,  129,  129,  129,  161,  214,  245,  193,  193,  193,
 /*    30 */   307,   -7,   20,  265,  162,  133,  340,  334,  142,  315,
 /*    40 */   271,  105,  165,   25,  174,  174,  174,  174,  174,  174,
 /*    50 */   174,  281,  174,  330,   17,   17,   17,  317,   29,  309,
 /*    60 */    71,   88,  204,   98,   71,  247,  273,  322,  317,  336,
 /*    70 */   328,    5,  172,  115,  293,  118,  171,  218,   55,   99,
 /*    80 */   257,   68,   87,  194,  235,  222,  191,  151,  121,  135,
 /*    90 */   241,  127,  216,   28,    7,   74,
);
    const YY_REDUCE_USE_DFLT = -59;
    const YY_REDUCE_MAX = 70;
    static public $yy_reduce_ofst = array(
 /*     0 */    22,  -47,  187,  230,  248,  208,  201,  246,  170,  275,
 /*    10 */   -45,   24,  181,  326,  329,  346,  312,  343,  349,  310,
 /*    20 */   363,  360,  380,  374,  377,  147,  147,  400,  323,  394,
 /*    30 */   390,   33,   33,  -29,  123,   -5,   -5,   -5,   -5,  347,
 /*    40 */    -5,   -5,   -5,   -5,   -5,   -5,   -5,   -5,   -5,   -5,
 /*    50 */    -5,  141,   -5,  404,  253,  224,   50,  -58,  197,  197,
 /*    60 */   197,  277,  274,  274,  197,  226,  258,  243,  291,  292,
 /*    70 */   280,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 39, 41, 42, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, 43, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 9 */ array(6, 8, 10, 11, 14, 24, 25, 36, 37, ),
        /* 10 */ array(6, 8, 10, 11, 14, 24, 25, 36, 37, ),
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
        /* 30 */ array(5, 7, 8, 15, 19, 21, ),
        /* 31 */ array(7, 8, 11, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 32 */ array(7, 8, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 33 */ array(12, 24, 42, ),
        /* 34 */ array(1, 14, 42, ),
        /* 35 */ array(7, 8, 11, 15, 19, 23, ),
        /* 36 */ array(5, 7, 8, 15, 19, ),
        /* 37 */ array(5, 7, 8, 15, 19, ),
        /* 38 */ array(7, 8, 13, 15, 19, ),
        /* 39 */ array(1, 3, 39, 41, 42, ),
        /* 40 */ array(7, 8, 15, 19, 23, ),
        /* 41 */ array(7, 8, 11, 15, 19, ),
        /* 42 */ array(5, 7, 8, 15, 19, ),
        /* 43 */ array(7, 8, 15, 18, 19, ),
        /* 44 */ array(7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(7, 8, 15, 19, ),
        /* 47 */ array(7, 8, 15, 19, ),
        /* 48 */ array(7, 8, 15, 19, ),
        /* 49 */ array(7, 8, 15, 19, ),
        /* 50 */ array(7, 8, 15, 19, ),
        /* 51 */ array(1, 14, 36, 42, ),
        /* 52 */ array(7, 8, 15, 19, ),
        /* 53 */ array(5, 10, 21, ),
        /* 54 */ array(24, 42, ),
        /* 55 */ array(24, 42, ),
        /* 56 */ array(24, 42, ),
        /* 57 */ array(17, ),
        /* 58 */ array(5, 34, 35, ),
        /* 59 */ array(18, 34, 35, ),
        /* 60 */ array(34, 35, ),
        /* 61 */ array(9, 20, ),
        /* 62 */ array(5, 21, ),
        /* 63 */ array(5, 21, ),
        /* 64 */ array(34, 35, ),
        /* 65 */ array(1, ),
        /* 66 */ array(12, ),
        /* 67 */ array(14, ),
        /* 68 */ array(17, ),
        /* 69 */ array(22, ),
        /* 70 */ array(24, ),
        /* 71 */ array(1, 40, ),
        /* 72 */ array(11, 16, ),
        /* 73 */ array(10, 23, ),
        /* 74 */ array(20, 38, ),
        /* 75 */ array(11, 16, ),
        /* 76 */ array(34, 35, ),
        /* 77 */ array(34, 35, ),
        /* 78 */ array(10, 20, ),
        /* 79 */ array(24, 43, ),
        /* 80 */ array(11, 16, ),
        /* 81 */ array(5, ),
        /* 82 */ array(24, ),
        /* 83 */ array(14, ),
        /* 84 */ array(11, ),
        /* 85 */ array(20, ),
        /* 86 */ array(11, ),
        /* 87 */ array(23, ),
        /* 88 */ array(10, ),
        /* 89 */ array(10, ),
        /* 90 */ array(24, ),
        /* 91 */ array(24, ),
        /* 92 */ array(5, ),
        /* 93 */ array(14, ),
        /* 94 */ array(24, ),
        /* 95 */ array(5, ),
        /* 96 */ array(),
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
        /* 167 */ array(),
        /* 168 */ array(),
);
    static public $yy_default = array(
 /*     0 */   263,  263,  263,  263,  263,  263,  263,  263,  263,  263,
 /*    10 */   263,  263,  263,  263,  263,  263,  263,  263,  263,  263,
 /*    20 */   263,  263,  263,  263,  263,  209,  209,  263,  263,  263,
 /*    30 */   263,  237,  237,  209,  263,  252,  263,  263,  263,  169,
 /*    40 */   252,  263,  263,  263,  238,  185,  228,  254,  227,  232,
 /*    50 */   253,  263,  190,  208,  263,  263,  263,  194,  263,  263,
 /*    60 */   263,  263,  263,  263,  239,  263,  210,  263,  195,  218,
 /*    70 */   263,  263,  263,  208,  203,  263,  234,  236,  208,  263,
 /*    80 */   263,  263,  263,  263,  233,  263,  233,  263,  221,  208,
 /*    90 */   263,  263,  263,  263,  263,  263,  244,  242,  170,  184,
 /*   100 */   240,  200,  245,  243,  241,  262,  247,  192,  201,  172,
 /*   110 */   183,  186,  171,  233,  248,  193,  261,  235,  173,  181,
 /*   120 */   246,  203,  219,  222,  226,  212,  214,  223,  258,  256,
 /*   130 */   213,  224,  188,  225,  197,  229,  199,  198,  211,  215,
 /*   140 */   220,  217,  189,  216,  259,  260,  187,  250,  255,  251,
 /*   150 */   196,  178,  182,  175,  180,  179,  204,  205,  230,  207,
 /*   160 */   202,  257,  191,  177,  249,  206,  176,  231,  174,
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
    const YYNSTATE = 169;
    const YYNRULE = 94;
    const YYERRORSYMBOL = 44;
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
    0,  /*    NOCACHE => nothing */
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
  'COMMENTEND',    'PHP',           'LDEL',          'NOCACHE',     
  'error',         'start',         'template',      'template_element',
  'smartytag',     'commenttext',   'expr',          'attributes',  
  'varvar',        'ifexprs',       'variable',      'foraction',   
  'attribute',     'array',         'value',         'modifier',    
  'modparameters',  'math',          'object',        'function',    
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
 /*   7 */ "smartytag ::= LDEL expr RDEL",
 /*   8 */ "smartytag ::= LDEL expr attributes RDEL",
 /*   9 */ "smartytag ::= LDEL DOLLAR varvar EQUAL expr RDEL",
 /*  10 */ "smartytag ::= LDEL ID RDEL",
 /*  11 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  12 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE variable AS DOLLAR ID APTR DOLLAR ID RDEL",
 /*  16 */ "foraction ::= EQUAL expr",
 /*  17 */ "foraction ::= INCDEC",
 /*  18 */ "attributes ::= attribute",
 /*  19 */ "attributes ::= attributes attribute",
 /*  20 */ "attribute ::= SPACE NOCACHE",
 /*  21 */ "attribute ::= SPACE ID EQUAL expr",
 /*  22 */ "attribute ::= SPACE ID EQUAL array",
 /*  23 */ "expr ::= value",
 /*  24 */ "expr ::= UNIMATH value",
 /*  25 */ "expr ::= expr modifier",
 /*  26 */ "expr ::= expr modifier modparameters",
 /*  27 */ "expr ::= expr math value",
 /*  28 */ "expr ::= expr DOT value",
 /*  29 */ "math ::= UNIMATH",
 /*  30 */ "math ::= MATH",
 /*  31 */ "value ::= NUMBER",
 /*  32 */ "value ::= BOOLEAN",
 /*  33 */ "value ::= OPENP expr CLOSEP",
 /*  34 */ "value ::= variable",
 /*  35 */ "value ::= object",
 /*  36 */ "value ::= function",
 /*  37 */ "value ::= SI_QSTR",
 /*  38 */ "value ::= QUOTE doublequoted QUOTE",
 /*  39 */ "value ::= ID",
 /*  40 */ "variable ::= DOLLAR varvar",
 /*  41 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  42 */ "vararraydefs ::= vararraydef",
 /*  43 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  44 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  45 */ "varvar ::= varvarele",
 /*  46 */ "varvar ::= varvar varvarele",
 /*  47 */ "varvarele ::= ID",
 /*  48 */ "varvarele ::= LDEL expr RDEL",
 /*  49 */ "object ::= DOLLAR varvar objectchain",
 /*  50 */ "objectchain ::= objectelement",
 /*  51 */ "objectchain ::= objectchain objectelement",
 /*  52 */ "objectelement ::= PTR ID",
 /*  53 */ "objectelement ::= PTR method",
 /*  54 */ "function ::= ID OPENP params CLOSEP",
 /*  55 */ "function ::= ID OPENP CLOSEP",
 /*  56 */ "method ::= ID OPENP params CLOSEP",
 /*  57 */ "method ::= ID OPENP CLOSEP",
 /*  58 */ "params ::= expr",
 /*  59 */ "params ::= params COMMA expr",
 /*  60 */ "modifier ::= VERT ID",
 /*  61 */ "modparameters ::= modparameter",
 /*  62 */ "modparameters ::= modparameters modparameter",
 /*  63 */ "modparameter ::= COLON expr",
 /*  64 */ "ifexprs ::= ifexpr",
 /*  65 */ "ifexprs ::= NOT ifexpr",
 /*  66 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  67 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  68 */ "ifexpr ::= expr",
 /*  69 */ "ifexpr ::= expr ifcond expr",
 /*  70 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  71 */ "ifcond ::= EQUALS",
 /*  72 */ "ifcond ::= NOTEQUALS",
 /*  73 */ "ifcond ::= GREATERTHAN",
 /*  74 */ "ifcond ::= LESSTHAN",
 /*  75 */ "ifcond ::= GREATEREQUAL",
 /*  76 */ "ifcond ::= LESSEQUAL",
 /*  77 */ "ifcond ::= IDENTITY",
 /*  78 */ "lop ::= LAND",
 /*  79 */ "lop ::= LOR",
 /*  80 */ "array ::= OPENP arrayelements CLOSEP",
 /*  81 */ "arrayelements ::= arrayelement",
 /*  82 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  83 */ "arrayelement ::= expr",
 /*  84 */ "arrayelement ::= expr APTR expr",
 /*  85 */ "arrayelement ::= ID APTR expr",
 /*  86 */ "arrayelement ::= array",
 /*  87 */ "doublequoted ::= doublequoted other",
 /*  88 */ "doublequoted ::= other",
 /*  89 */ "other ::= variable",
 /*  90 */ "other ::= LDEL expr RDEL",
 /*  91 */ "other ::= OTHER",
 /*  92 */ "commenttext ::= commenttext OTHER",
 /*  93 */ "commenttext ::= OTHER",
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
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 6 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 5 ),
  array( 'lhs' => 48, 'rhs' => 12 ),
  array( 'lhs' => 48, 'rhs' => 11 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 2 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 69, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 4 ),
  array( 'lhs' => 70, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
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
  array( 'lhs' => 57, 'rhs' => 3 ),
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
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        23 => 0,
        31 => 0,
        32 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        37 => 0,
        81 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        29 => 1,
        30 => 1,
        42 => 1,
        45 => 1,
        61 => 1,
        63 => 1,
        64 => 1,
        88 => 1,
        91 => 1,
        93 => 1,
        2 => 2,
        43 => 2,
        87 => 2,
        4 => 4,
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
        18 => 17,
        58 => 17,
        83 => 17,
        86 => 17,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 21,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        33 => 33,
        38 => 38,
        39 => 39,
        40 => 40,
        41 => 41,
        49 => 41,
        44 => 44,
        46 => 46,
        47 => 47,
        48 => 48,
        66 => 48,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 52,
        54 => 54,
        55 => 55,
        56 => 56,
        57 => 57,
        59 => 59,
        60 => 60,
        62 => 62,
        65 => 65,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 69,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        79 => 79,
        80 => 80,
        82 => 82,
        84 => 84,
        85 => 84,
        89 => 89,
        90 => 90,
        92 => 92,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1396 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1399 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1402 "internal.templateparser.php"
#line 79 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?>';     }
#line 1405 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -1]->minor),array('_smarty_nocache'=>$this->nocache)));$this->nocache=false;    }
#line 1408 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>'print_expression'),array('value'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1411 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue = $this->smarty->compile_tag->execute(array('_smarty_tag'=>'assign','var' => $this->yystack[$this->yyidx + -3]->minor, 'value'=>$this->yystack[$this->yyidx + -1]->minor,'_smarty_nocache'=>$this->nocache));$this->nocache=false;    }
#line 1414 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1417 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_nocache'=>$this->nocache),$this->yystack[$this->yyidx + -1]->minor));$this->nocache=false;    }
#line 1420 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1423 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1426 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1429 "internal.templateparser.php"
#line 111 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -9]->minor,'from'=>$this->yystack[$this->yyidx + -7]->minor,'key'=>$this->yystack[$this->yyidx + -4]->minor,'item'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1432 "internal.templateparser.php"
#line 112 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1435 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1438 "internal.templateparser.php"
#line 121 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1441 "internal.templateparser.php"
#line 123 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1444 "internal.templateparser.php"
#line 124 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1447 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1450 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1453 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r26(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1456 "internal.templateparser.php"
#line 140 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1459 "internal.templateparser.php"
#line 142 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1462 "internal.templateparser.php"
#line 160 "internal.templateparser.y"
    function yy_r33(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1465 "internal.templateparser.php"
#line 170 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1468 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1471 "internal.templateparser.php"
#line 178 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->nocache) $this->nocache=true;    }
#line 1474 "internal.templateparser.php"
#line 180 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = '$this->tpl_vars->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if($this->tpl_vars->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->nocache) $this->nocache=true;    }
#line 1477 "internal.templateparser.php"
#line 188 "internal.templateparser.y"
    function yy_r44(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1480 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1483 "internal.templateparser.php"
#line 196 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1486 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1489 "internal.templateparser.php"
#line 205 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1492 "internal.templateparser.php"
#line 207 "internal.templateparser.y"
    function yy_r51(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1495 "internal.templateparser.php"
#line 209 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1498 "internal.templateparser.php"
#line 218 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1501 "internal.templateparser.php"
#line 220 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1504 "internal.templateparser.php"
#line 226 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1507 "internal.templateparser.php"
#line 228 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1510 "internal.templateparser.php"
#line 234 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1513 "internal.templateparser.php"
#line 239 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1516 "internal.templateparser.php"
#line 244 "internal.templateparser.y"
    function yy_r62(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1519 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1522 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1525 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1528 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1531 "internal.templateparser.php"
#line 263 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '==';    }
#line 1534 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '!=';    }
#line 1537 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '>';    }
#line 1540 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '<';    }
#line 1543 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '>=';    }
#line 1546 "internal.templateparser.php"
#line 268 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '<=';    }
#line 1549 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '===';    }
#line 1552 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '&&';    }
#line 1555 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r79(){$this->_retvalue = '||';    }
#line 1558 "internal.templateparser.php"
#line 274 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1561 "internal.templateparser.php"
#line 276 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1564 "internal.templateparser.php"
#line 278 "internal.templateparser.y"
    function yy_r84(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1567 "internal.templateparser.php"
#line 284 "internal.templateparser.y"
    function yy_r89(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + 0]->minor.".'";    }
#line 1570 "internal.templateparser.php"
#line 285 "internal.templateparser.y"
    function yy_r90(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1573 "internal.templateparser.php"
#line 288 "internal.templateparser.y"
    function yy_r92(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.o;    }
#line 1576 "internal.templateparser.php"

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
#line 47 "internal.templateparser.y"

    $this->internalError = true;
    $this->compiler->trigger_template_error();
#line 1693 "internal.templateparser.php"
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
#line 39 "internal.templateparser.y"

    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //echo $this->retvalue."\n\n";
#line 1718 "internal.templateparser.php"
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
