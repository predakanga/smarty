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
    const TP_COMMENTSTART                   = 38;
    const TP_COMMENTEND                     = 39;
    const TP_PHP                            = 40;
    const TP_LDEL                           = 41;
    const YY_NO_ACTION = 257;
    const YY_ACCEPT_ACTION = 256;
    const YY_ERROR_ACTION = 255;

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
    const YY_SZ_ACTTAB = 428;
static public $yy_action = array(
 /*     0 */   135,  118,   23,   32,  139,  155,  113,  125,   26,  161,
 /*    10 */   123,   16,   84,   63,  111,  112,   64,  154,  153,  105,
 /*    20 */    95,  106,  107,  108,  103,  102,  137,  135,  118,   75,
 /*    30 */    99,   27,  113,   60,  113,   26,  114,   11,  123,   84,
 /*    40 */   111,  112,  111,  112,   13,  126,  105,   95,  106,  107,
 /*    50 */   108,  103,  102,  115,   87,   25,  152,    3,  115,   92,
 /*    60 */    25,   50,    3,   51,  152,   24,   50,  256,   33,   94,
 /*    70 */   127,   68,  116,   22,  135,  118,   80,  116,  139,   20,
 /*    80 */     6,   22,   26,   49,  147,    6,   84,  115,   49,   25,
 /*    90 */    19,   15,  149,   73,  115,   50,   25,   58,   15,  160,
 /*   100 */   155,    9,   50,  159,   37,   80,  116,  113,  132,  142,
 /*   110 */   161,  123,   80,  116,   18,  111,  112,   49,   51,  115,
 /*   120 */    24,   25,   10,   15,   49,   69,  115,   50,   25,   13,
 /*   130 */     4,  144,  227,  227,   50,  136,   56,   47,  116,   30,
 /*   140 */    28,  152,   55,  113,   80,  116,  134,  123,   70,   49,
 /*   150 */    83,  111,  112,    6,   82,  109,   49,  115,   22,   25,
 /*   160 */   115,    1,   25,  104,   15,   50,  129,   77,   50,  110,
 /*   170 */    27,   90,   60,  113,   98,   80,  116,  123,   80,  116,
 /*   180 */   115,  111,  112,   52,   15,  130,   12,   49,   50,   28,
 /*   190 */    49,   54,   78,   81,  225,  225,  123,  157,   80,  116,
 /*   200 */   111,  112,   17,  115,    9,   25,   28,    1,   60,  113,
 /*   210 */    49,   50,  104,  123,  138,  101,   96,  111,  112,    9,
 /*   220 */    97,   67,  116,   91,   28,   86,   61,  113,   57,   71,
 /*   230 */     2,  123,  100,   49,   21,  111,  112,   46,  147,   41,
 /*   240 */   113,   53,  113,  146,  123,   76,  123,  104,  111,  112,
 /*   250 */   111,  112,   41,  113,   48,  113,  140,  119,   85,  123,
 /*   260 */    74,  111,  112,  111,  112,  133,    7,  135,  118,   65,
 /*   270 */   153,   38,   89,   72,  113,   26,  135,  118,  123,   84,
 /*   280 */    14,   79,  111,  112,   26,  101,   96,  143,   84,   22,
 /*   290 */   135,  118,   19,   64,  154,  153,   66,  158,   26,  141,
 /*   300 */   135,  118,   84,  128,  127,  124,   93,  125,   26,  135,
 /*   310 */   118,    5,   84,  121,    9,  148,  150,   26,  135,  118,
 /*   320 */    43,   84,   42,  113,   11,  113,   26,  123,   88,  123,
 /*   330 */    84,  111,  112,  111,  112,  101,   96,   34,   29,   62,
 /*   340 */   113,  155,  156,   44,  123,  131,  113,   17,  111,  112,
 /*   350 */   123,   59,  135,  118,  111,  112,  139,   30,   40,  151,
 /*   360 */    26,  113,  145,  120,   84,  123,   13,    8,   35,  111,
 /*   370 */   112,  113,  173,  173,   36,  123,  173,  113,  173,  111,
 /*   380 */   112,  123,  173,  173,  173,  111,  112,  141,  173,   45,
 /*   390 */   173,  173,  113,  173,  173,   31,  123,  173,  113,  173,
 /*   400 */   111,  112,  123,  173,  173,   39,  111,  112,  113,  173,
 /*   410 */   113,  173,  123,  173,  122,  173,  111,  112,  111,  112,
 /*   420 */   173,  173,  117,  173,  173,  173,  173,   62,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    8,   62,   48,   11,   65,   51,    1,   15,   54,
 /*    10 */    55,   20,   19,   57,   59,   60,   63,   64,   65,   26,
 /*    20 */    27,   28,   29,   30,   31,   32,   70,    7,    8,   74,
 /*    30 */    75,   48,   51,   50,   51,   15,   55,   20,   55,   19,
 /*    40 */    59,   60,   59,   60,   12,   39,   26,   27,   28,   29,
 /*    50 */    30,   31,   32,    6,   71,    8,   24,   10,    6,    5,
 /*    60 */     8,   14,   10,   56,   24,   58,   14,   43,   44,   45,
 /*    70 */    46,   24,   25,   41,    7,    8,   24,   25,   11,   72,
 /*    80 */    33,   41,   15,   36,   37,   33,   19,    6,   36,    8,
 /*    90 */    23,   10,   11,   11,    6,   14,    8,   62,   10,   11,
 /*   100 */    65,   10,   14,   68,   48,   24,   25,   51,    5,    5,
 /*   110 */    54,   55,   24,   25,   23,   59,   60,   36,   56,    6,
 /*   120 */    58,    8,   10,   10,   36,   21,    6,   14,    8,   12,
 /*   130 */    10,   75,   34,   35,   14,   24,   47,   24,   25,   22,
 /*   140 */    48,   24,   50,   51,   24,   25,    1,   55,    3,   36,
 /*   150 */    37,   59,   60,   33,   24,    5,   36,    6,   41,    8,
 /*   160 */     6,   10,    8,   71,   10,   14,   77,   37,   14,    5,
 /*   170 */    48,    5,   50,   51,   11,   24,   25,   55,   24,   25,
 /*   180 */     6,   59,   60,   38,   10,   40,   41,   36,   14,   48,
 /*   190 */    36,   50,   51,   71,   34,   35,   55,   11,   24,   25,
 /*   200 */    59,   60,   16,    6,   10,    8,   48,   10,   50,   51,
 /*   210 */    36,   14,   71,   55,    5,   34,   35,   59,   60,   10,
 /*   220 */     5,   24,   25,    9,   48,   24,   50,   51,   49,   71,
 /*   230 */    21,   55,   53,   36,   20,   59,   60,   48,   37,   48,
 /*   240 */    51,   61,   51,   54,   55,   37,   55,   71,   59,   60,
 /*   250 */    59,   60,   48,   51,   14,   51,   76,   55,   52,   55,
 /*   260 */    69,   59,   60,   59,   60,    5,   73,    7,    8,   18,
 /*   270 */    65,   48,   24,   69,   51,   15,    7,    8,   55,   19,
 /*   280 */    17,   21,   59,   60,   15,   34,   35,   53,   19,   41,
 /*   290 */     7,    8,   23,   63,   64,   65,   66,   67,   15,    1,
 /*   300 */     7,    8,   19,   45,   46,   77,    5,    1,   15,    7,
 /*   310 */     8,   18,   19,   76,   10,   13,    5,   15,    7,    8,
 /*   320 */    48,   19,   48,   51,   20,   51,   15,   55,   51,   55,
 /*   330 */    19,   59,   60,   59,   60,   34,   35,   48,   62,   41,
 /*   340 */    51,   65,   11,   48,   55,   70,   51,   16,   59,   60,
 /*   350 */    55,   51,    7,    8,   59,   60,   11,   22,   48,   67,
 /*   360 */    15,   51,   11,   64,   19,   55,   12,   16,   48,   59,
 /*   370 */    60,   51,   78,   78,   48,   55,   78,   51,   78,   59,
 /*   380 */    60,   55,   78,   78,   78,   59,   60,    1,   78,   48,
 /*   390 */    78,   78,   51,   78,   78,   48,   55,   78,   51,   78,
 /*   400 */    59,   60,   55,   78,   78,   48,   59,   60,   51,   78,
 /*   410 */    51,   78,   55,   78,   55,   78,   59,   60,   59,   60,
 /*   420 */    78,   78,   36,   78,   78,   78,   78,   41,
);
    const YY_SHIFT_USE_DFLT = -10;
    const YY_SHIFT_MAX = 89;
    static public $yy_shift_ofst = array(
 /*     0 */   145,  197,   47,   52,   52,   52,  120,   52,  197,   81,
 /*    10 */    88,  151,  113,  154,  154,  154,  154,  154,  154,  154,
 /*    20 */   154,  154,  154,  117,  174,  174,  174,   -7,   20,   32,
 /*    30 */   248,  260,   67,  145,  345,  302,  311,  269,  293,  283,
 /*    40 */   283,  283,  283,  283,  283,  283,  283,  209,   40,  298,
 /*    50 */    40,  263,  306,  386,  301,  251,    6,  104,   40,  214,
 /*    60 */   181,  181,  240,  263,  354,  240,  335,   91,  304,  201,
 /*    70 */   130,  160,  186,   98,  331,  351,  103,   54,   -9,  208,
 /*    80 */   194,  163,  150,  215,  111,  166,   17,   82,  164,  112,
);
    const YY_REDUCE_USE_DFLT = -61;
    const YY_REDUCE_MAX = 66;
    static public $yy_reduce_ofst = array(
 /*     0 */    24,  -45,  141,  122,  -17,   92,  158,  176,   56,  204,
 /*    10 */   191,  189,  347,  320,  274,  289,  223,  341,  295,  272,
 /*    20 */   310,  357,  326,  230,  -19,  359,  202,    7,    7,  -47,
 /*    30 */    35,   62,   62,  258,   62,   62,   62,   62,   62,   62,
 /*    40 */    62,   62,   62,   62,   62,   62,   62,  179,  276,  180,
 /*    50 */   -60,  -44,   89,  237,  193,  193,  228,  234,  205,  206,
 /*    60 */   193,  193,  277,  275,  299,  300,  292,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 3, 38, 40, 41, ),
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
        /* 23 */ array(12, 22, 24, 41, ),
        /* 24 */ array(6, 10, 14, 24, 25, 36, ),
        /* 25 */ array(6, 10, 14, 24, 25, 36, ),
        /* 26 */ array(6, 10, 14, 24, 25, 36, ),
        /* 27 */ array(7, 8, 11, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 28 */ array(7, 8, 15, 19, 26, 27, 28, 29, 30, 31, 32, ),
        /* 29 */ array(12, 24, 41, ),
        /* 30 */ array(24, 41, ),
        /* 31 */ array(5, 7, 8, 15, 19, 21, ),
        /* 32 */ array(7, 8, 11, 15, 19, 23, ),
        /* 33 */ array(1, 3, 38, 40, 41, ),
        /* 34 */ array(7, 8, 11, 15, 19, ),
        /* 35 */ array(7, 8, 13, 15, 19, ),
        /* 36 */ array(5, 7, 8, 15, 19, ),
        /* 37 */ array(7, 8, 15, 19, 23, ),
        /* 38 */ array(7, 8, 15, 18, 19, ),
        /* 39 */ array(7, 8, 15, 19, ),
        /* 40 */ array(7, 8, 15, 19, ),
        /* 41 */ array(7, 8, 15, 19, ),
        /* 42 */ array(7, 8, 15, 19, ),
        /* 43 */ array(7, 8, 15, 19, ),
        /* 44 */ array(7, 8, 15, 19, ),
        /* 45 */ array(7, 8, 15, 19, ),
        /* 46 */ array(7, 8, 15, 19, ),
        /* 47 */ array(5, 10, 21, ),
        /* 48 */ array(24, 41, ),
        /* 49 */ array(1, 41, ),
        /* 50 */ array(24, 41, ),
        /* 51 */ array(17, ),
        /* 52 */ array(1, ),
        /* 53 */ array(1, 36, 41, ),
        /* 54 */ array(5, 34, 35, ),
        /* 55 */ array(18, 34, 35, ),
        /* 56 */ array(1, 39, ),
        /* 57 */ array(5, 21, ),
        /* 58 */ array(24, 41, ),
        /* 59 */ array(9, 20, ),
        /* 60 */ array(34, 35, ),
        /* 61 */ array(34, 35, ),
        /* 62 */ array(14, ),
        /* 63 */ array(17, ),
        /* 64 */ array(12, ),
        /* 65 */ array(14, ),
        /* 66 */ array(22, ),
        /* 67 */ array(10, 23, ),
        /* 68 */ array(10, 20, ),
        /* 69 */ array(24, 37, ),
        /* 70 */ array(24, 37, ),
        /* 71 */ array(34, 35, ),
        /* 72 */ array(11, 16, ),
        /* 73 */ array(34, 35, ),
        /* 74 */ array(11, 16, ),
        /* 75 */ array(11, 16, ),
        /* 76 */ array(5, ),
        /* 77 */ array(5, ),
        /* 78 */ array(20, ),
        /* 79 */ array(37, ),
        /* 80 */ array(10, ),
        /* 81 */ array(11, ),
        /* 82 */ array(5, ),
        /* 83 */ array(5, ),
        /* 84 */ array(24, ),
        /* 85 */ array(5, ),
        /* 86 */ array(20, ),
        /* 87 */ array(11, ),
        /* 88 */ array(5, ),
        /* 89 */ array(10, ),
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
        /* 156 */ array(),
        /* 157 */ array(),
        /* 158 */ array(),
        /* 159 */ array(),
        /* 160 */ array(),
        /* 161 */ array(),
);
    static public $yy_default = array(
 /*     0 */   255,  255,  255,  255,  255,  255,  255,  255,  255,  255,
 /*    10 */   255,  255,  255,  255,  255,  255,  255,  255,  255,  255,
 /*    20 */   255,  255,  255,  201,  255,  255,  255,  229,  229,  201,
 /*    30 */   255,  255,  244,  162,  255,  255,  255,  244,  255,  178,
 /*    40 */   230,  219,  224,  245,  246,  220,  183,  200,  255,  255,
 /*    50 */   255,  187,  255,  255,  255,  255,  255,  255,  213,  255,
 /*    60 */   255,  231,  255,  188,  202,  255,  210,  200,  200,  255,
 /*    70 */   255,  226,  255,  228,  255,  255,  255,  255,  195,  255,
 /*    80 */   200,  225,  255,  255,  255,  255,  255,  225,  255,  208,
 /*    90 */   177,  179,  175,  176,  163,  233,  240,  172,  227,  242,
 /*   100 */   180,  239,  238,  237,  225,  232,  234,  235,  236,  174,
 /*   110 */   250,  196,  197,  195,  189,  193,  198,  199,  191,  190,
 /*   120 */   204,  248,  186,  185,  252,  254,  166,  165,  164,  253,
 /*   130 */   167,  223,  170,  169,  168,  192,  221,  222,  171,  194,
 /*   140 */   249,  251,  173,  181,  243,  241,  184,  182,  205,  216,
 /*   150 */   209,  212,  208,  207,  203,  206,  217,  215,  211,  214,
 /*   160 */   218,  247,
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
    const YYNOCODE = 79;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 162;
    const YYNRULE = 93;
    const YYERRORSYMBOL = 42;
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
  'QUOTE',         'NOCACHE',       'COMMENTSTART',  'COMMENTEND',  
  'PHP',           'LDEL',          'error',         'start',       
  'template',      'template_element',  'smartytag',     'something',   
  'expr',          'attributes',    'ifexprs',       'variable',    
  'foraction',     'attribute',     'array',         'value',       
  'modifier',      'modparameters',  'math',          'object',      
  'function',      'doublequoted',  'varvar',        'vararraydefs',
  'vararraydef',   'varvarele',     'objectchain',   'objectelement',
  'method',        'params',        'modparameter',  'ifexpr',      
  'ifcond',        'lop',           'arrayelements',  'arrayelement',
  'other',         'otherth',     
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
 /*   4 */ "template_element ::= COMMENTSTART something COMMENTEND",
 /*   5 */ "template_element ::= PHP",
 /*   6 */ "template_element ::= OTHER",
 /*   7 */ "smartytag ::= LDEL expr RDEL",
 /*   8 */ "smartytag ::= LDEL expr SPACE NOCACHE RDEL",
 /*   9 */ "smartytag ::= LDEL ID RDEL",
 /*  10 */ "smartytag ::= LDEL NOCACHE RDEL",
 /*  11 */ "smartytag ::= LDEL ID attributes RDEL",
 /*  12 */ "smartytag ::= LDELSLASH ID RDEL",
 /*  13 */ "smartytag ::= LDELSLASH NOCACHE RDEL",
 /*  14 */ "smartytag ::= LDEL ID SPACE ifexprs RDEL",
 /*  15 */ "smartytag ::= LDEL ID SPACE variable EQUAL expr SEMICOLON ifexprs SEMICOLON variable foraction RDEL",
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
 /*  51 */ "objectelement ::= PTR varvar",
 /*  52 */ "objectelement ::= PTR method",
 /*  53 */ "function ::= ID OPENP params CLOSEP",
 /*  54 */ "function ::= ID OPENP CLOSEP",
 /*  55 */ "method ::= ID OPENP params CLOSEP",
 /*  56 */ "method ::= ID OPENP CLOSEP",
 /*  57 */ "params ::= expr",
 /*  58 */ "params ::= params COMMA expr",
 /*  59 */ "modifier ::= VERT ID",
 /*  60 */ "modparameters ::= modparameter",
 /*  61 */ "modparameters ::= modparameters modparameter",
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
 /*  88 */ "other ::= LDEL variable RDEL",
 /*  89 */ "other ::= OTHER",
 /*  90 */ "something ::= something otherth",
 /*  91 */ "something ::= otherth",
 /*  92 */ "otherth ::= OTHER",
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
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 3 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 5 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 4 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 5 ),
  array( 'lhs' => 46, 'rhs' => 12 ),
  array( 'lhs' => 52, 'rhs' => 2 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 2 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 53, 'rhs' => 4 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 2 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 67, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 4 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 2 ),
  array( 'lhs' => 70, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 2 ),
  array( 'lhs' => 50, 'rhs' => 3 ),
  array( 'lhs' => 50, 'rhs' => 4 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 2 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
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
        33 => 0,
        34 => 0,
        35 => 0,
        36 => 0,
        80 => 0,
        1 => 1,
        3 => 1,
        5 => 1,
        6 => 1,
        29 => 1,
        30 => 1,
        41 => 1,
        44 => 1,
        60 => 1,
        62 => 1,
        63 => 1,
        87 => 1,
        89 => 1,
        91 => 1,
        92 => 1,
        2 => 2,
        42 => 2,
        86 => 2,
        90 => 2,
        4 => 4,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 9,
        11 => 11,
        12 => 12,
        13 => 12,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 17,
        57 => 17,
        82 => 17,
        85 => 17,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 21,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        32 => 32,
        37 => 37,
        38 => 38,
        39 => 39,
        40 => 40,
        48 => 40,
        43 => 43,
        45 => 45,
        46 => 46,
        47 => 47,
        65 => 47,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 51,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        58 => 58,
        59 => 59,
        61 => 61,
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
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 63 "internal.templateparser.y"
    function yy_r0(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1372 "internal.templateparser.php"
#line 69 "internal.templateparser.y"
    function yy_r1(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1375 "internal.templateparser.php"
#line 71 "internal.templateparser.y"
    function yy_r2(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1378 "internal.templateparser.php"
#line 81 "internal.templateparser.y"
    function yy_r4(){ $this->_retvalue = '<?php /* comment placeholder */?> ';     }
#line 1381 "internal.templateparser.php"
#line 92 "internal.templateparser.y"
    function yy_r7(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -1]->minor,'caching'=>$this->caching));$this->caching=true;    }
#line 1384 "internal.templateparser.php"
#line 93 "internal.templateparser.y"
    function yy_r8(){ $this->_retvalue = $this->smarty->compile_variable->execute(array('var'=>$this->yystack[$this->yyidx + -3]->minor,'caching'=>false));    }
#line 1387 "internal.templateparser.php"
#line 95 "internal.templateparser.y"
    function yy_r9(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -1]->minor),array(0)));    }
#line 1390 "internal.templateparser.php"
#line 99 "internal.templateparser.y"
    function yy_r11(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>$this->yystack[$this->yyidx + -2]->minor),array('_smarty_caching'=>$this->caching),$this->yystack[$this->yyidx + -1]->minor));$this->caching=true;    }
#line 1393 "internal.templateparser.php"
#line 101 "internal.templateparser.y"
    function yy_r12(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1396 "internal.templateparser.php"
#line 105 "internal.templateparser.y"
    function yy_r14(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -3]->minor,'ifexp'=>$this->yystack[$this->yyidx + -1]->minor));    }
#line 1399 "internal.templateparser.php"
#line 107 "internal.templateparser.y"
    function yy_r15(){ $this->_retvalue =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>$this->yystack[$this->yyidx + -10]->minor,'start'=>$this->yystack[$this->yyidx + -8]->minor.'='.$this->yystack[$this->yyidx + -6]->minor,'ifexp'=>$this->yystack[$this->yyidx + -4]->minor,'loop'=>$this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor));    }
#line 1402 "internal.templateparser.php"
#line 108 "internal.templateparser.y"
    function yy_r16(){ $this->_retvalue = '='.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1405 "internal.templateparser.php"
#line 109 "internal.templateparser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1408 "internal.templateparser.php"
#line 117 "internal.templateparser.y"
    function yy_r19(){ $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor,$this->yystack[$this->yyidx + 0]->minor);    }
#line 1411 "internal.templateparser.php"
#line 119 "internal.templateparser.y"
    function yy_r20(){ $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor=>true);    }
#line 1414 "internal.templateparser.php"
#line 120 "internal.templateparser.y"
    function yy_r21(){ $this->_retvalue = array($this->yystack[$this->yyidx + -2]->minor=>$this->yystack[$this->yyidx + 0]->minor);    }
#line 1417 "internal.templateparser.php"
#line 130 "internal.templateparser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1420 "internal.templateparser.php"
#line 132 "internal.templateparser.y"
    function yy_r25(){ $this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + 0]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1423 "internal.templateparser.php"
#line 134 "internal.templateparser.y"
    function yy_r26(){$this->_retvalue = "\$this->smarty->modifier->".$this->yystack[$this->yyidx + -1]->minor . "(". $this->yystack[$this->yyidx + -2]->minor .",". $this->yystack[$this->yyidx + 0]->minor .")";     }
#line 1426 "internal.templateparser.php"
#line 136 "internal.templateparser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1429 "internal.templateparser.php"
#line 138 "internal.templateparser.y"
    function yy_r28(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor . '.' . $this->yystack[$this->yyidx + 0]->minor;     }
#line 1432 "internal.templateparser.php"
#line 154 "internal.templateparser.y"
    function yy_r32(){ $this->_retvalue = "(". $this->yystack[$this->yyidx + -1]->minor .")";     }
#line 1435 "internal.templateparser.php"
#line 164 "internal.templateparser.y"
    function yy_r37(){ $this->_retvalue = "'".$this->yystack[$this->yyidx + -1]->minor."'";     }
#line 1438 "internal.templateparser.php"
#line 166 "internal.templateparser.y"
    function yy_r38(){ $this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';     }
#line 1441 "internal.templateparser.php"
#line 172 "internal.templateparser.y"
    function yy_r39(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + 0]->minor .']->data'; if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + 0]->minor]->caching) $this->caching=false;    }
#line 1444 "internal.templateparser.php"
#line 174 "internal.templateparser.y"
    function yy_r40(){ $this->_retvalue = '$this->smarty->tpl_vars['. $this->yystack[$this->yyidx + -1]->minor .']->data'.$this->yystack[$this->yyidx + 0]->minor;if(!$this->smarty->tpl_vars[$this->yystack[$this->yyidx + -1]->minor]->caching) $this->caching=false;    }
#line 1447 "internal.templateparser.php"
#line 182 "internal.templateparser.y"
    function yy_r43(){ $this->_retvalue = "[". $this->yystack[$this->yyidx + -1]->minor ."]";    }
#line 1450 "internal.templateparser.php"
#line 188 "internal.templateparser.y"
    function yy_r45(){$this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.'.'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1453 "internal.templateparser.php"
#line 190 "internal.templateparser.y"
    function yy_r46(){$this->_retvalue = '\''.$this->yystack[$this->yyidx + 0]->minor.'\'';    }
#line 1456 "internal.templateparser.php"
#line 192 "internal.templateparser.y"
    function yy_r47(){$this->_retvalue = '('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1459 "internal.templateparser.php"
#line 199 "internal.templateparser.y"
    function yy_r49(){$this->_retvalue  = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1462 "internal.templateparser.php"
#line 201 "internal.templateparser.y"
    function yy_r50(){$this->_retvalue  = $this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1465 "internal.templateparser.php"
#line 203 "internal.templateparser.y"
    function yy_r51(){ $this->_retvalue = '->'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1468 "internal.templateparser.php"
#line 211 "internal.templateparser.y"
    function yy_r53(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1471 "internal.templateparser.php"
#line 213 "internal.templateparser.y"
    function yy_r54(){ $this->_retvalue = "\$this->smarty->function->".$this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1474 "internal.templateparser.php"
#line 219 "internal.templateparser.y"
    function yy_r55(){ $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor . "(". $this->yystack[$this->yyidx + -1]->minor .")";    }
#line 1477 "internal.templateparser.php"
#line 221 "internal.templateparser.y"
    function yy_r56(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor."()";    }
#line 1480 "internal.templateparser.php"
#line 227 "internal.templateparser.y"
    function yy_r58(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1483 "internal.templateparser.php"
#line 232 "internal.templateparser.y"
    function yy_r59(){ $this->_retvalue =  $this->yystack[$this->yyidx + 0]->minor;    }
#line 1486 "internal.templateparser.php"
#line 237 "internal.templateparser.y"
    function yy_r61(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor.",".$this->yystack[$this->yyidx + 0]->minor;    }
#line 1489 "internal.templateparser.php"
#line 246 "internal.templateparser.y"
    function yy_r64(){$this->_retvalue = '!'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1492 "internal.templateparser.php"
#line 248 "internal.templateparser.y"
    function yy_r66(){$this->_retvalue = '!('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1495 "internal.templateparser.php"
#line 252 "internal.templateparser.y"
    function yy_r67(){$this->_retvalue =$this->yystack[$this->yyidx + 0]->minor;    }
#line 1498 "internal.templateparser.php"
#line 253 "internal.templateparser.y"
    function yy_r68(){$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.$this->yystack[$this->yyidx + -1]->minor.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1501 "internal.templateparser.php"
#line 256 "internal.templateparser.y"
    function yy_r70(){$this->_retvalue = '==';    }
#line 1504 "internal.templateparser.php"
#line 257 "internal.templateparser.y"
    function yy_r71(){$this->_retvalue = '!=';    }
#line 1507 "internal.templateparser.php"
#line 258 "internal.templateparser.y"
    function yy_r72(){$this->_retvalue = '>';    }
#line 1510 "internal.templateparser.php"
#line 259 "internal.templateparser.y"
    function yy_r73(){$this->_retvalue = '<';    }
#line 1513 "internal.templateparser.php"
#line 260 "internal.templateparser.y"
    function yy_r74(){$this->_retvalue = '>=';    }
#line 1516 "internal.templateparser.php"
#line 261 "internal.templateparser.y"
    function yy_r75(){$this->_retvalue = '<=';    }
#line 1519 "internal.templateparser.php"
#line 262 "internal.templateparser.y"
    function yy_r76(){$this->_retvalue = '===';    }
#line 1522 "internal.templateparser.php"
#line 264 "internal.templateparser.y"
    function yy_r77(){$this->_retvalue = '&&';    }
#line 1525 "internal.templateparser.php"
#line 265 "internal.templateparser.y"
    function yy_r78(){$this->_retvalue = '||';    }
#line 1528 "internal.templateparser.php"
#line 267 "internal.templateparser.y"
    function yy_r79(){ $this->_retvalue = 'array('.$this->yystack[$this->yyidx + -1]->minor.')';    }
#line 1531 "internal.templateparser.php"
#line 269 "internal.templateparser.y"
    function yy_r81(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.','.$this->yystack[$this->yyidx + 0]->minor;     }
#line 1534 "internal.templateparser.php"
#line 271 "internal.templateparser.y"
    function yy_r83(){ $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor.'=>'.$this->yystack[$this->yyidx + 0]->minor;    }
#line 1537 "internal.templateparser.php"
#line 277 "internal.templateparser.y"
    function yy_r88(){$this->_retvalue = "'.".$this->yystack[$this->yyidx + -1]->minor.".'";    }
#line 1540 "internal.templateparser.php"

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
#line 1657 "internal.templateparser.php"
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
#line 1682 "internal.templateparser.php"
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
