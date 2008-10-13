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
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Variable");
        $this->smarty->compile_variable = new Smarty_Internal_Compile_Smarty_Variable;
				$this->caching = true;
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
    const TP_NOCACHE                        = 37;
    const TP_PHP                            = 38;
    const TP_LDEL                           = 39;
    const YY_NO_ACTION = 247;
    const YY_ACCEPT_ACTION = 246;
    const YY_ERROR_ACTION = 245;

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
    const YY_SZ_ACTTAB = 405;
static public $yy_action = array(
 /*     0 */   117,  118,  134,   32,  108,   56,  119,    9,  145,  139,
 /*    10 */   129,  154,   82,   62,  112,  111,  220,  220,    2,  100,
 /*    20 */    89,  105,   91,   99,  106,  104,  135,  117,  118,   70,
 /*    30 */   155,   28,  119,   58,  119,  143,  128,  107,  129,   82,
 /*    40 */   112,  111,  112,  111,  117,  118,  100,   89,  105,   91,
 /*    50 */    99,  106,  104,  127,   74,   26,   82,    4,  127,   61,
 /*    60 */    26,   49,    4,   98,  143,    9,   49,   28,   10,   55,
 /*    70 */    77,   73,  110,   63,  129,   11,   79,  110,  112,  111,
 /*    80 */     3,  141,   94,   51,  138,    3,    8,  127,   51,   26,
 /*    90 */   102,   22,  127,   20,   26,   49,   22,  150,   72,  109,
 /*   100 */    49,   48,   63,   65,  119,   41,  110,  142,  129,  149,
 /*   110 */    79,  110,  112,  111,   18,  117,  118,   51,   75,  103,
 /*   120 */   101,  131,   51,   54,  127,  148,   26,   82,   22,  151,
 /*   130 */    13,  127,   49,   26,   27,    7,   58,  119,  136,   49,
 /*   140 */    21,  129,   79,  110,  127,  112,  111,   83,   22,   79,
 /*   150 */   110,   76,   49,   97,   51,  218,  218,   86,    3,  126,
 /*   160 */   138,   51,   79,  110,   80,  127,  152,   26,  127,   22,
 /*   170 */    26,   18,    1,   49,   51,  121,   49,   68,   28,   29,
 /*   180 */    53,  119,  145,   79,  110,  129,   79,  110,   16,  112,
 /*   190 */   111,   14,   93,  117,  118,   51,   34,  108,   51,  119,
 /*   200 */   148,  102,  127,  129,   26,   82,    1,  112,  111,   17,
 /*   210 */    49,   33,  122,   12,  119,   21,   16,  139,  129,   14,
 /*   220 */    67,  110,  112,  111,   27,  116,   58,  119,  117,  118,
 /*   230 */   133,  129,   51,  103,  101,  112,  111,   96,  140,    5,
 /*   240 */    82,   52,  125,   25,  117,  118,   69,   87,   28,    9,
 /*   250 */    59,  119,  117,  118,    9,  129,   82,   15,   78,  112,
 /*   260 */   111,  117,  118,   40,   82,  108,  119,   19,   17,   40,
 /*   270 */   129,  102,  119,   82,  112,  111,  129,   88,   92,   16,
 /*   280 */   112,  111,   14,  144,   71,  147,   90,  117,  118,   30,
 /*   290 */    66,  148,   21,   44,  119,   52,  119,   25,  120,   82,
 /*   300 */   129,   84,  112,  111,  112,  111,   21,  103,  101,   60,
 /*   310 */   115,  132,   64,  153,   11,   37,   24,   31,  119,  145,
 /*   320 */   119,  137,  129,  114,  129,   23,  112,  111,  112,  111,
 /*   330 */    47,  124,  123,  119,   50,    6,   35,  129,  132,  119,
 /*   340 */    85,  112,  111,  129,   81,   57,   38,  112,  111,  119,
 /*   350 */   113,  130,   46,  129,   30,  119,  165,  112,  111,  129,
 /*   360 */    60,  115,  132,  112,  111,  165,   39,  146,   43,  119,
 /*   370 */   165,  119,  165,  129,  165,  129,  165,  112,  111,  112,
 /*   380 */   111,  165,   42,  165,  165,  119,  165,  165,   45,  129,
 /*   390 */   165,  119,  165,  112,  111,  129,  165,  165,  165,  112,
 /*   400 */   111,  246,   36,   95,  123,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,    5,   45,   11,   59,   48,   10,   62,   51,
 /*    10 */    52,   65,   19,   54,   56,   57,   34,   35,   21,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   67,    7,    8,   71,
 /*    30 */    72,   45,   48,   47,   48,    1,   52,   11,   52,   19,
 /*    40 */    56,   57,   56,   57,    7,    8,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   68,    8,   19,   10,    6,   46,
 /*    60 */     8,   14,   10,   50,    1,   10,   14,   45,   10,   47,
 /*    70 */    48,   24,   25,   39,   52,   20,   24,   25,   56,   57,
 /*    80 */    33,   11,    9,   36,   37,   33,   16,    6,   36,    8,
 /*    90 */    68,   10,    6,   20,    8,   14,   10,   11,   11,   36,
 /*   100 */    14,   45,   39,   18,   48,   24,   25,   51,   52,   11,
 /*   110 */    24,   25,   56,   57,   16,    7,    8,   36,   37,   34,
 /*   120 */    35,   13,   36,   58,    6,   24,    8,   19,   10,   11,
 /*   130 */    20,    6,   14,    8,   45,   10,   47,   48,   73,   14,
 /*   140 */    39,   52,   24,   25,    6,   56,   57,   24,   10,   24,
 /*   150 */    25,   24,   14,    5,   36,   34,   35,   68,   33,    5,
 /*   160 */    37,   36,   24,   25,   37,    6,   11,    8,    6,   10,
 /*   170 */     8,   16,   10,   14,   36,    1,   14,    3,   45,   59,
 /*   180 */    47,   48,   62,   24,   25,   52,   24,   25,   12,   56,
 /*   190 */    57,   15,    5,    7,    8,   36,   45,   11,   36,   48,
 /*   200 */    24,   68,    6,   52,    8,   19,   10,   56,   57,   23,
 /*   210 */    14,   45,   38,   39,   48,   39,   12,   51,   52,   15,
 /*   220 */    24,   25,   56,   57,   45,   24,   47,   48,    7,    8,
 /*   230 */     5,   52,   36,   34,   35,   56,   57,    5,   72,   18,
 /*   240 */    19,   53,    5,   55,    7,    8,   21,   68,   45,   10,
 /*   250 */    47,   48,    7,    8,   10,   52,   19,   69,   21,   56,
 /*   260 */    57,    7,    8,   45,   19,   11,   48,   23,   23,   45,
 /*   270 */    52,   68,   48,   19,   56,   57,   52,   24,    5,   12,
 /*   280 */    56,   57,   15,    5,   66,    5,    5,    7,    8,   22,
 /*   290 */    66,   24,   39,   45,   48,   53,   48,   55,   52,   19,
 /*   300 */    52,   37,   56,   57,   56,   57,   39,   34,   35,   60,
 /*   310 */    61,   62,   63,   64,   20,   45,   59,   45,   48,   62,
 /*   320 */    48,   50,   52,   61,   52,   17,   56,   57,   56,   57,
 /*   330 */    45,   43,   44,   48,   14,   70,   45,   52,   62,   48,
 /*   340 */    48,   56,   57,   52,   49,   48,   45,   56,   57,   48,
 /*   350 */    73,   67,   45,   52,   22,   48,   74,   56,   57,   52,
 /*   360 */    60,   61,   62,   56,   57,   74,   45,   64,   45,   48,
 /*   370 */    74,   48,   74,   52,   74,   52,   74,   56,   57,   56,
 /*   380 */    57,   74,   45,   74,   74,   48,   74,   74,   45,   52,
 /*   390 */    74,   48,   74,   56,   57,   52,   74,   74,   74,   56,
 /*   400 */    57,   41,   42,   43,   44,
);
    const YY_SHIFT_USE_DFLT = -19;
    const YY_SHIFT_MAX = 88;
    static public $yy_shift_ofst = array(
 /*     0 */   174,  196,   47,  125,   52,   52,   52,   52,  196,  118,
 /*    10 */    86,  162,   81,  159,  159,  159,  159,  159,  159,  159,
 /*    20 */   159,  159,  159,  159,  267,  138,  138,   -7,   20,  176,
 /*    30 */   253,  237,  186,  245,  254,  221,  174,  280,  108,   37,
 /*    40 */    37,   -3,   37,   37,   37,   37,   37,   37,   37,  101,
 /*    50 */   101,   34,  308,   85,   63,  273,  101,   73,  199,  199,
 /*    60 */   204,  225,  308,  320,  332,  320,  155,  244,  127,  123,
 /*    70 */    70,   98,  -18,   55,  121,  148,  281,  110,  264,  239,
 /*    80 */   232,  187,  201,  294,  154,  278,   26,   87,   58,
);
    const YY_REDUCE_USE_DFLT = -55;
    const YY_REDUCE_MAX = 65;
    static public $yy_reduce_ofst = array(
 /*     0 */   360,  -42,   22,  -14,   89,  133,  203,  179,  166,  224,
 /*    10 */   218,   56,  272,  291,  285,  307,  301,  343,  337,  321,
 /*    20 */   323,  270,  151,  248,  249,  246,  -16,  188,  188,  300,
 /*    30 */   -54,  242,  242,  242,  242,  242,  288,  242,  242,  242,
 /*    40 */   242,   13,  242,  242,  242,  242,  242,  242,  242,  257,
 /*    50 */   120,   65,  -41,  265,  277,  265,  276,  295,  265,  265,
 /*    60 */   262,  271,  284,  292,  303,  297,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 38, 39, ),
        /* 1 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 2 */ array(6, 8, 10, 14, 24, 25, 33, 36, 37, ),
        /* 3 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 4 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 5 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 6 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 7 */ array(6, 8, 10, 14, 24, 25, 33, 36, ),
        /* 8 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 9 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 10 */ array(6, 8, 10, 11, 14, 24, 25, 36, ),
        /* 11 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 12 */ array(6, 8, 10, 14, 24, 25, 36, 37, ),
        /* 13 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 14 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 15 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 16 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 17 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 18 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 19 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 20 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 21 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 22 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 23 */ array(6, 8, 10, 14, 24, 25, 36, ),
        /* 24 */ array(12, 15, 22, 24, 39, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, ),
        /* 26 */ array(6, 10, 14, 24, 25, 36, ),
        /* 27 */ array(7, 8, 11, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 28 */ array(7, 8, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 29 */ array(12, 15, 24, 39, ),
        /* 30 */ array(24, 39, ),
        /* 31 */ array(5, 7, 8, 19, 21, ),
        /* 32 */ array(7, 8, 11, 19, 23, ),
        /* 33 */ array(7, 8, 19, 23, ),
        /* 34 */ array(7, 8, 11, 19, ),
        /* 35 */ array(7, 8, 18, 19, ),
        /* 36 */ array(1, 3, 38, 39, ),
        /* 37 */ array(5, 7, 8, 19, ),
        /* 38 */ array(7, 8, 13, 19, ),
        /* 39 */ array(7, 8, 19, ),
        /* 40 */ array(7, 8, 19, ),
        /* 41 */ array(5, 10, 21, ),
        /* 42 */ array(7, 8, 19, ),
        /* 43 */ array(7, 8, 19, ),
        /* 44 */ array(7, 8, 19, ),
        /* 45 */ array(7, 8, 19, ),
        /* 46 */ array(7, 8, 19, ),
        /* 47 */ array(7, 8, 19, ),
        /* 48 */ array(7, 8, 19, ),
        /* 49 */ array(24, 39, ),
        /* 50 */ array(24, 39, ),
        /* 51 */ array(1, 39, ),
        /* 52 */ array(17, ),
        /* 53 */ array(18, 34, 35, ),
        /* 54 */ array(1, 36, 39, ),
        /* 55 */ array(5, 34, 35, ),
        /* 56 */ array(24, 39, ),
        /* 57 */ array(9, 20, ),
        /* 58 */ array(34, 35, ),
        /* 59 */ array(34, 35, ),
        /* 60 */ array(12, 15, ),
        /* 61 */ array(5, 21, ),
        /* 62 */ array(17, ),
        /* 63 */ array(14, ),
        /* 64 */ array(22, ),
        /* 65 */ array(14, ),
        /* 66 */ array(11, 16, ),
        /* 67 */ array(10, 23, ),
        /* 68 */ array(24, 37, ),
        /* 69 */ array(24, 37, ),
        /* 70 */ array(11, 16, ),
        /* 71 */ array(11, 16, ),
        /* 72 */ array(34, 35, ),
        /* 73 */ array(10, 20, ),
        /* 74 */ array(34, 35, ),
        /* 75 */ array(5, ),
        /* 76 */ array(5, ),
        /* 77 */ array(20, ),
        /* 78 */ array(37, ),
        /* 79 */ array(10, ),
        /* 80 */ array(5, ),
        /* 81 */ array(5, ),
        /* 82 */ array(24, ),
        /* 83 */ array(20, ),
        /* 84 */ array(5, ),
        /* 85 */ array(5, ),
        /* 86 */ array(11, ),
        /* 87 */ array(11, ),
        /* 88 */ array(10, ),
        /* 89 */ array(),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(),
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
);
    static public $yy_default = array(
 /*     0 */   245,  245,  245,  245,  245,  245,  245,  245,  245,  245,
 /*    10 */   245,  245,  245,  245,  245,  245,  245,  245,  245,  245,
 /*    20 */   245,  245,  245,  245,  193,  245,  245,  222,  222,  193,
 /*    30 */   245,  245,  237,  237,  245,  245,  156,  245,  245,  239,
 /*    40 */   212,  192,  213,  171,  217,  238,  223,  197,  176,  245,
 /*    50 */   245,  245,  180,  245,  245,  245,  206,  245,  245,  224,
 /*    60 */   194,  245,  181,  245,  203,  245,  245,  192,  245,  245,
 /*    70 */   245,  245,  221,  192,  219,  245,  245,  187,  245,  192,
 /*    80 */   245,  245,  245,  245,  245,  245,  218,  218,  201,  226,
 /*    90 */   167,  228,  169,  170,  172,  157,  168,  165,  173,  229,
 /*   100 */   225,  233,  218,  232,  231,  227,  230,  220,  186,  191,
 /*   110 */   190,  189,  188,  241,  196,  195,  214,  184,  183,  187,
 /*   120 */   182,  161,  160,  159,  158,  162,  163,  185,  179,  178,
 /*   130 */   216,  198,  200,  166,  164,  215,  242,  174,  175,  240,
 /*   140 */   236,  234,  177,  244,  243,  199,  205,  202,  201,  210,
 /*   150 */   211,  209,  208,  204,  207,  235,
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
    const YYNOCODE = 75;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 156;
    const YYNRULE = 89;
    const YYERRORSYMBOL = 40;
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
    1,  /*    NOCACHE => OTHER */
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
  'QUOTE',         'NOCACHE',       'PHP',           'LDEL',        
  'error',         'start',         'template',      'template_element',
  'smartytag',     'expr',          'attributes',    'ifexprs',     
  'variable',      'foraction',     'attribute',     'array',       
  'value',         'modifier',      'modparameters',  'math',        
  'object',        'function',      'doublequoted',  'varvar',      
  'vararraydefs',  'vararraydef',   'varvarele',     'objectchain', 
  'objectelement',  'method',        'params',        'modparameter',
  'ifexpr',        'ifcond',        'lop',           'arrayelements',
  'arrayelement',  'other',       
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
 /*   4 */ "template_element ::= PHP",
 /*   5 */ "template_element ::= OTHER",
 /*   6 */ "smartytag ::= LDEL expr RDEL",
 /*   7 */ "smartytag ::= LDEL expr SPACE NOCACHE RDEL",
 /*   8 */ "smartytag ::= LDEL ID RDEL",
 /*   9 */ "smartytag ::= LDEL NOCACHE RDEL",
 /*  10 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  11 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  12 */ "smartytag ::= LDELSLASH NOCACHE RDEL",
 /*  13 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
 /*  15 */ "foraction ::= EQUAL expr",
 /*  16 */ "foraction ::= INCDEC",
 /*  17 */ "attributes ::= attribute",
 /*  18 */ "attributes ::= attributes attribute",
 /*  19 */ "attribute ::= SPACE NOCACHE",
 /*  20 */ "attribute ::= SPACE ID EQUAL expr",
 /*  21 */ "attribute ::= SPACE ID EQUAL array",
 /*  22 */ "expr ::= value",
 /*  23 */ "expr ::= UNIMATH value",
 /*  24 */ "expr ::= expr modifier",
 /*  25 */ "expr ::= expr modifier modparameters",
 /*  26 */ "expr ::= expr math value",
 /*  27 */ "math ::= UNIMATH",
 /*  28 */ "math ::= MATH",
 /*  29 */ "value ::= NUMBER",
 /*  30 */ "value ::= OPENP expr CLOSEP",
 /*  31 */ "value ::= variable",
 /*  32 */ "value ::= object",
 /*  33 */ "value ::= function",
 /*  34 */ "value ::= SI_QSTR",
 /*  35 */ "value ::= QUOTE doublequoted QUOTE",
 /*  36 */ "value ::= ID",
 /*  37 */ "variable ::= DOLLAR varvar",
 /*  38 */ "variable ::= DOLLAR varvar vararraydefs",
 /*  39 */ "vararraydefs ::= vararraydef",
 /*  40 */ "vararraydefs ::= vararraydefs vararraydef",
 /*  41 */ "vararraydef ::= DOT expr",
 /*  42 */ "vararraydef ::= OPENB expr CLOSEB",
 /*  43 */ "varvar ::= varvarele",
 /*  44 */ "varvar ::= varvar varvarele",
 /*  45 */ "varvarele ::= ID",
 /*  46 */ "varvarele ::= LDEL expr RDEL",
 /*  47 */ "object ::= DOLLAR varvar objectchain",
 /*  48 */ "objectchain ::= objectelement",
 /*  49 */ "objectchain ::= objectchain objectelement",
 /*  50 */ "objectelement ::= PTR varvar",
 /*  51 */ "objectelement ::= PTR method",
 /*  52 */ "function ::= ID OPENP params CLOSEP",
 /*  53 */ "function ::= ID OPENP CLOSEP",
 /*  54 */ "method ::= ID OPENP params CLOSEP",
 /*  55 */ "method ::= ID OPENP CLOSEP",
 /*  56 */ "params ::= expr",
 /*  57 */ "params ::= params COMMA expr",
 /*  58 */ "modifier ::= VERT ID",
 /*  59 */ "modparameters ::= modparameter",
 /*  60 */ "modparameters ::= modparameters modparameter",
 /*  61 */ "modparameter ::= COLON expr",
 /*  62 */ "ifexprs ::= ifexpr",
 /*  63 */ "ifexprs ::= NOT ifexpr",
 /*  64 */ "ifexprs ::= OPENP ifexpr CLOSEP",
 /*  65 */ "ifexprs ::= NOT OPENP ifexpr CLOSEP",
 /*  66 */ "ifexpr ::= expr",
 /*  67 */ "ifexpr ::= expr ifcond expr",
 /*  68 */ "ifexpr ::= ifexprs lop ifexprs",
 /*  69 */ "ifcond ::= EQUALS",
 /*  70 */ "ifcond ::= NOTEQUALS",
 /*  71 */ "ifcond ::= GREATERTHAN",
 /*  72 */ "ifcond ::= LESSTHAN",
 /*  73 */ "ifcond ::= GREATEREQUAL",
 /*  74 */ "ifcond ::= LESSEQUAL",
 /*  75 */ "ifcond ::= IDENTITY",
 /*  76 */ "lop ::= LAND",
 /*  77 */ "lop ::= LOR",
 /*  78 */ "array ::= OPENP arrayelements CLOSEP",
 /*  79 */ "arrayelements ::= arrayelement",
 /*  80 */ "arrayelements ::= arrayelements COMMA arrayelement",
 /*  81 */ "arrayelement ::= expr",
 /*  82 */ "arrayelement ::= expr APTR expr",
 /*  83 */ "arrayelement ::= ID APTR expr",
 /*  84 */ "arrayelement ::= array",
 /*  85 */ "doublequoted ::= doublequoted other",
 /*  86 */ "doublequoted ::= other",
 /*  87 */ "other ::= LDEL variable RDEL",
 /*  88 */ "other ::= OTHER",
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
  array( 'lhs' => 41, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 5 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 4 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 3 ),
  array( 'lhs' => 44, 'rhs' => 5 ),
  array( 'lhs' => 44, 'rhs' => 12 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 3 ),
  array( 'lhs' => 45, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 3 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 65, 'rhs' => 4 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 3 ),
  array( 'lhs' => 47, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        22 => 0,
        29 => 0,
        31 => 0,
        32 => 0,
        33 => 0,
        34 => 0,
        36 => 0,
        79 => 0,
        1 => 1,
        3 => 1,
        4 => 1,
        5 => 1,
        27 => 1,
        28 => 1,
        39 => 1,
        43 => 1,
        45 => 1,
        59 => 1,
        61 => 1,
        62 => 1,
        86 => 1,
        88 => 1,
        2 => 2,
        40 => 2,
        85 => 2,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 8,
        10 => 10,
        11 => 11,
        12 => 11,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 16,
        56 => 16,
        81 => 16,
        84 => 16,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 20,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        30 => 30,
        35 => 35,
        37 => 37,
        38 => 38,
        47 => 38,
        41 => 41,
        42 => 42,
        44 => 44,
        46 => 46,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 50,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        57 => 57,
        58 => 58,
        60 => 60,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 67,
        68 => 67,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 74,
        75 => 75,
        76 => 76,
        77 => 77,
        78 => 78,
        80 => 80,
        82 => 82,
        83 => 82,
        87 => 87,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1344 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1347 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1350 "internal.templateparser.php"
#line 88 "internal.templateparser.y"
    function yy_r6(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -1]->minor,'caching'=>$this->caching));$this->caching=true;    }
#line 1353 "internal.templateparser.php"
#line 89 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -3]->minor,'caching'=>false));    }
#line 1356 "internal.templateparser.php"
#line 91 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1359 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r10(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_caching'=>$this->caching),$this->yystack[$this->yyidx + -1]->minor));$this->caching=true;    }
#line 1362 "internal.templateparser.php"
#line 97 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1365 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r13(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1368 "internal.templateparser.php"
#line 103 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1371 "internal.templateparser.php"
#line 104 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1374 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1377 "internal.templateparser.php"
#line 113 "internal.templateparser.y"
    function yy_r18(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1380 "internal.templateparser.php"
#line 115 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1383 "internal.templateparser.php"
#line 116 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1386 "internal.templateparser.php"
#line 126 "internal.templateparser.y"
    function yy_r23(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1389 "internal.templateparser.php"
#line 128 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1392 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r25(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1395 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r26(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1398 "internal.templateparser.php"
#line 148 "internal.templateparser.y"
    function yy_r30(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1401 "internal.templateparser.php"
#line 158 "internal.templateparser.y"
    function yy_r35(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1404 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->caching) $this->caching=false;    }
#line 1407 "internal.templateparser.php"
#line 168 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->caching) $this->caching=false;    }
#line 1410 "internal.templateparser.php"
#line 175 "internal.templateparser.y"
    function yy_r41(){ $this->_retvalue = ".". $this->yystack[$this->yyidx + 0]->minor;    }
#line 1413 "internal.templateparser.php"
#line 177 "internal.templateparser.y"
    function yy_r42(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1416 "internal.templateparser.php"
#line 183 "internal.templateparser.y"
    function yy_r44(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.".".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1419 "internal.templateparser.php"
#line 187 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = "(".$this->yystack[$this->yyidx + -1]->minor.")";    }
#line 1422 "internal.templateparser.php"
#line 194 "internal.templateparser.y"
    function yy_r48(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1425 "internal.templateparser.php"
#line 196 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1428 "internal.templateparser.php"
#line 198 "internal.templateparser.y"
    function yy_r50(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1431 "internal.templateparser.php"
#line 206 "internal.templateparser.y"
    function yy_r52(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1434 "internal.templateparser.php"
#line 208 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1437 "internal.templateparser.php"
#line 214 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1440 "internal.templateparser.php"
#line 216 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1443 "internal.templateparser.php"
#line 222 "internal.templateparser.y"
    function yy_r57(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1446 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1449 "internal.templateparser.php"
#line 232 "internal.templateparser.y"
    function yy_r60(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1452 "internal.templateparser.php"
#line 241 "internal.templateparser.y"
    function yy_r63(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1455 "internal.templateparser.php"
#line 242 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1458 "internal.templateparser.php"
#line 243 "internal.templateparser.y"
    function yy_r65(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1461 "internal.templateparser.php"
#line 247 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1464 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1467 "internal.templateparser.php"
#line 251 "internal.templateparser.y"
    function yy_r69(){$this->_retvalue = '==';    }
#line 1470 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '!=';    }
#line 1473 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '>';    }
#line 1476 "internal.templateparser.php"
#line 254 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '<';    }
#line 1479 "internal.templateparser.php"
#line 255 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '>=';    }
#line 1482 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '<=';    }
#line 1485 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '===';    }
#line 1488 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '&&';    }
#line 1491 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '||';    }
#line 1494 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r78(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1497 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r80(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1500 "internal.templateparser.php"
#line 266 "internal.templateparser.y"
    function yy_r82(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1503 "internal.templateparser.php"
#line 272 "internal.templateparser.y"
    function yy_r87(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1506 "internal.templateparser.php"

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
#line 1623 "internal.templateparser.php"
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
#line 1648 "internal.templateparser.php"
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
