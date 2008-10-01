/* This is an example for a Parser in PHP */
%name TP_
%declare_class {class Smarty_Internal_Templateparser}
%include_class
{
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
    }
    public static function &instance($new_instance = null)
    {
        static $instance = null;
        if (isset($new_instance) && is_object($new_instance))
            $instance = $new_instance;
        return $instance;
    }
    
} 


%token_prefix TP_

%parse_accept
{
    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
    //    echo $this->retvalue."\n\n";
}

%syntax_error
{
    $this->internalError = true;
    $this->smarty->trigger_template_error();
}

%fallback     OTHER LDELS RDELS RDEL NUMBER MINUS PLUS STAR SLASH PERCENT OPENP CLOSEP OPENB CLOSEB DOLLAR DOT COMMA COLON SEMICOLON
              VERT EQUAL SPACE PTR APTR ID SI_QSTR EQUALS NOTEQUALS GREATERTHAN LESSTHAN GREATEREQUAL LESSEQUAL IDENTITY
              NOT LAND LOR QUOTE.

start(res)       ::= input(t). { res = t; }

input(res)       ::= single(e). {res = e;}
input(res)       ::= input(i) single(e). {res = i.e;}

single(res)      ::= smartytag(st). {res = st;}
single(res)      ::= PHP(php). {res = php;}
single(res)      ::= OTHER(o). {res = o;}

smartytag(res)   ::= LDEL expr(e) RDEL. { res = "<?php echo str_replace('\"','&quot;',". e .");?>\n";}
smartytag(res)   ::= LDEL ID(e) attributes(a) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>e),a)) ."\n ";}
smartytag(res)   ::= LDEL ID(e) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>e),array(0))) ."\n ";}
smartytag(res)   ::= LDEL SLASH ID(e) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array('_smarty_tag'=>'end_'.e)) ."\n ";}
smartytag(res)   ::= IFTAG ifexprs(ie) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'if'),array('ifexp'=>ie))) ."\n ";}
smartytag(res)   ::= ELSEIFTAG ifexprs(ie) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'elseif'),array('ifexp'=>ie))) ."\n ";}
smartytag(res)   ::= FORTAG variable(v1) EQUAL expr(e1)SEMICOLON ifexprs(ie) SEMICOLON variable(v2) EQUAL expr(e2) RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>v1.'='.e1),array('ifexp'=>ie),array('loop'=>v2.'='.e2))) ."\n ";}
smartytag(res)   ::= FORTAG variable(v1) EQUAL expr(e1)SEMICOLON ifexprs(ie) SEMICOLON variable(v2) PLUS PLUS RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>v1.'='.e1),array('ifexp'=>ie),array('loop'=>v2.'++'))) ."\n ";}
smartytag(res)   ::= FORTAG variable(v1) EQUAL expr(e1)SEMICOLON ifexprs(ie) SEMICOLON variable(v2) MINUS MINUS RDEL. {$this->smarty = Smarty::instance(); res =  $this->smarty->compile_smarty_tag(array_merge(array('_smarty_tag'=>'for'),array('start'=>v1.'='.e1),array('ifexp'=>ie),array('loop'=>v2.'--'))) ."\n ";}

/* Attributes Smarty tags */
attributes(res)  ::= attribute(a). { res = a;}
attributes(res)  ::= attributes(a1) attribute(a2). { res = array_merge(a1,a2);}

attribute(res)   ::= SPACE ID(v) EQUAL expr(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL ID(e). { res = array(v=>e);}

expr(res)        ::= value(f). { res = f; }
expr(res)        ::= MINUS value(f). { res = "-".f; }
expr(res)        ::= expr(f) modifier(m). { res = m . "(". f .")"; }
expr(res)        ::= expr(f) modifier(m) modparameters(p). { res = m . "(". f .",". p .")"; } 
expr(res)        ::= expr(t1) math(m) value(f2). { res = t1 . m . f2; } 
expr(res)        ::= array(a). { res = a; }

math(res)        ::= PLUS. { res = "+";}
math(res)        ::= MINUS. { res = "-";}
math(res)        ::= STAR. { res = "*";}
math(res)        ::= SLASH. { res = "/";}

value(res)       ::= NUMBER(n). { res = n; }
value(res)       ::= OPENP expr(e) CLOSEP. { res = "(". e .")"; }
value(res)		   ::= variable(f). { res = f; }
value(res)       ::= method(m). { res = m; }
value(res)	     ::= SI_QSTR(s). { res = s; }
value(res)	     ::= QUOTE doublequoted(s) QUOTE. { res = '"'.s.'"'; }
value(res)	     ::= function(f). { res = f; }

/* variables */
variable(res)    ::= DOLLAR varids(s). { res = '$this->smarty->tpl_vars['. s .']';}
variable(res)    ::= variable(v) DOT varids(s). { res = v ."[". s ."]";}
variable(res)    ::= variable(v) OPENB varids(s) CLOSEB. { res = v ."[". s ."]";}
varids(res)			 ::= varids(v1) varid(v2). {res = v1.".".v2;}
varids(res)			 ::= varid(v). {res = v;}
varid(res)       ::= ID(s). {res = s;}
/* varid(res)       ::= expr(s). {res = s;}*/
varid(res)       ::= OPENP expr(s) CLOSEP. {res = s;}

/* methode */
method(res)      ::= DOLLAR ID(s) methodchain(mc). { res = '$this->smarty->tpl_vars['. s .']'.mc;}

methodchain(res)      ::= methodelement(me). {res  = me; } 
methodchain(res)      ::= methodchain(mc) methodelement(me). {res  = mc.me; }
/* methodchain(res)      ::= .  */

methodelement(res)     ::= PTR ID(s).	{ res = '->'.s;}
methodelement(res)     ::= PTR function(f).	{ res = '->'.f;}

/* function */
function(res)     ::= ID(s) OPENP params(p) CLOSEP.	{ res = s."(".p.")";}
function(res)     ::= ID(s) OPENP CLOSEP.	{ res = s."()";}

/* parameter */
params(res)       ::= expr(e). { res = e;}
params(res)       ::= params(p) COMMA expr(e). { res = p.",".e;}

/* modifier */  
modifier(res)    ::= VERT ID(s). { res =  s;}

/* modifier parameter */
modparameters(res) ::= modparameter(mp). {res = mp;}
modparameters(res) ::= modparameters(mps) modparameter(mp). {res = mps .",". mp;}
modparameter(res) ::= COLON value(mp). {res = mp;}

ifexprs(res)			 ::= ifexpr(e).	{res = e;}
ifexprs(res)			 ::= ifexprs(e1) lop(o) ifexprs(e2).	{res = e1.o.e2;}
ifexprs(res)			 ::= OPENP ifexprs(e1) lop(o) ifexprs(e2) CLOSEP.	{res = '('.e1.o.e2.')';}

ifexpr(res)        ::= expr(e). {res =e;}
ifexpr(res)        ::= NOT expr(e). {res = '!'.e;}
ifexpr(res)        ::= expr(e1) ifcond(c) expr(e2). {res = e1.c.e2;}
ifexpr(res)        ::= OPENP expr(e1) ifcond(c) expr(e2) CLOSEP. {res = e1.c.e2;}

ifcond(res)        ::= EQUALS. {res = '==';}
ifcond(res)        ::= NOTEQUALS. {res = '!=';}
ifcond(res)        ::= GREATERTHAN. {res = '>';}
ifcond(res)        ::= LESSTHAN. {res = '<';}
ifcond(res)        ::= GREATEREQUAL. {res = '>=';}
ifcond(res)        ::= LESSEQUAL. {res = '<=';}
ifcond(res)        ::= IDENTITY. {res = '===';}

lop(res)        ::= LAND. {res = '&&';}
lop(res)        ::= LOR. {res = '||';}

array(res)		  ::=  OPENP arrayelements(a) CLOSEP.  { res = 'array('.a.')';}
arrayelements(res)   ::=  arrayelement(a).  { res = a; }
arrayelements(res)   ::=  arrayelements(a1) COMMA arrayelement(a).  { res = a1.','.a; }
arrayelement(res)		 ::=  expr(e). { res = e;}
arrayelement(res)		 ::=  expr(e1) APTR expr(e2). { res = e1.'=>'.e2;}

doublequoted(res)          ::= doublequoted(o1) other(o2). {res = o1.o2;}
doublequoted(res)          ::= other(o). {res = o;}
other(res)           ::= variable(v). {res = '".'.v.'."';}
other(res)           ::= OTHER(o). {res = o;}