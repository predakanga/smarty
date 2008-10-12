/* This is a Parser for Smarty3 */
/* Definitions written by Uwe Tews */
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
        $this->compiler = Smarty_Internal_Compiler::instance(); 
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Tag");
        $this->smarty->compile_tag = new Smarty_Internal_Compile_Smarty_Tag;
        $this->smarty->loadPlugin("Smarty_Internal_Compile_Smarty_Variable");
        $this->smarty->compile_variable = new Smarty_Internal_Compile_Smarty_Variable;
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
    //echo $this->retvalue."\n\n";
}

%syntax_error
{
    $this->internalError = true;
    $this->compiler->trigger_template_error();
}

//
// fallback definition to catch all non Smarty template text
//
%fallback     OTHER LDELS LDELSLASH RDELS RDEL NUMBER MATH UNIMATH INCDEC OPENP CLOSEP OPENB CLOSEB DOLLAR DOT COMMA COLON SEMICOLON
              VERT EQUAL SPACE PTR APTR ID SI_QSTR EQUALS NOTEQUALS GREATERTHAN LESSTHAN GREATEREQUAL LESSEQUAL IDENTITY
              NOT LAND LOR QUOTE NOCACHE.


//
// complete template
//
start(res)       ::= template(t). { res = t; }

//
// loop over template elements
//
											// single template element
template(res)       ::= template_element(e). {res = e;}
											// loop of elements
template(res)       ::= template(t) template_element(e). {res = t.e;}

//
// template elements
//
											// Smarty tag
template_element(res)::= smartytag(st). {res = st;}	
											// PHP tag
template_element(res)::= PHP(php). {res = php;}	
											// Other template text
template_element(res)::= OTHER(o). {res = o;}	


//
// all Smarty tags start here
//
									// variable
smartytag(res)   ::= LDEL expr(e) RDEL. { res = $this->smarty->compile_variable->execute(array('var'=>e));}
smartytag(res)   ::= LDEL expr(e) SPACE NOCACHE(v) RDEL. { res = $this->smarty->compile_variable->execute(array('var'=>e,v=>true));}
									// tag without attributes
smartytag(res)   ::= LDEL ID(i) RDEL. { res =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>i),array(0)));}
smartytag(res)   ::= LDEL NOCACHE(i) RDEL. { res =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>i),array(0)));}
									// tag with Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) attributes(a) RDEL. { res =  $this->smarty->compile_tag->execute(array_merge(array('_smarty_tag'=>i),a));}
									// end of block tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) RDEL. { res =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.i));}
smartytag(res)   ::= LDELSLASH NOCACHE(i) RDEL. { res =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>'end_'.i));}
									// {if} and {elseif} tag
smartytag(res)   ::= LDEL ID(i) SPACE ifexprs(ie) RDEL. { res =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>i,'ifexp'=>ie));}
									// {for} tag
smartytag(res)   ::= LDEL ID(i) SPACE variable(v1) EQUAL expr(e1)SEMICOLON ifexprs(ie) SEMICOLON variable(v2) foraction(e2) RDEL. { res =  $this->smarty->compile_tag->execute(array('_smarty_tag'=>i,'start'=>v1.'='.e1,'ifexp'=>ie,'loop'=>v2.e2));}
foraction(res)	 ::= EQUAL expr(e). { res = '='.e;}
foraction(res)	 ::= INCDEC(e). { res = e;}

//
//Attributes of Smarty tags 
//
									// single attribute
attributes(res)  ::= attribute(a). { res = a;}
									// list of attributes
attributes(res)  ::= attributes(a1) attribute(a2). { res = array_merge(a1,a2);}
									// different formats of attribute
attribute(res)   ::= SPACE NOCACHE(v). { res = array(v=>true);}
attribute(res)   ::= SPACE ID(v) EQUAL expr(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL ID(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL array(a). { res = array(v=>a);}

//
// expressions
//
									// single value
expr(res)        ::= value(v). { res = v; }
									// +/- value
expr(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// expression with simple modifier
expr(res)        ::= expr(e) modifier(m). { res = "\$this->smarty->modifier->".m . "(". e .")"; }
									// expression with modifier and additional modifier paramter
expr(res)        ::= expr(e) modifier(m) modparameters(p). {res = "\$this->smarty->modifier->".m . "(". e .",". p .")"; } 
									// arithmetic expression
expr(res)        ::= expr(e) math(m) value(v). { res = e . m . v; } 

//
// mathematical operators
//
									// +,-
math(res)        ::= UNIMATH(m). {res = m;}
									// *,/,%
math(res)        ::= MATH(m). {res = m;}

//
// value in expressions
//
									// numeric constant
value(res)       ::= NUMBER(n). { res = n; }
									// expression
value(res)       ::= OPENP expr(e) CLOSEP. { res = "(". e .")"; }
									// variable
value(res)		   ::= variable(v). { res = v; }
									// object
value(res)       ::= object(o). { res = o; }
									// function call
value(res)	     ::= function(f). { res = f; }
									// singele quoted string
value(res)	     ::= SI_QSTR(s). { res = s; }
									// double quoted string
value(res)	     ::= QUOTE doublequoted(s) QUOTE. { res = "'".s."'"; }

//
// variables 
//
									// simple Smarty variable
variable(res)    ::= DOLLAR varvar(v). { res = '$this->smarty->tpl_vars['. v .']';}
									// array variable
variable(res)    ::= DOLLAR varvar(v) vararraydefs(a). { res = '$this->smarty->tpl_vars['. v .']'.a;}
										// single array index
vararraydefs(res)  ::= vararraydef(a). {res = a;}
										// multiple array index
vararraydefs(res)  ::= vararraydefs(a1) vararraydef(a2). {res = a1.a2;}
										// Smarty2 style index
vararraydef(res)   ::= DOT expr(e). { res = "[". e ."]";}
										// PHP style index
vararraydef(res)   ::= OPENB expr(e) CLOSEB. { res = "[". e ."]";}

// variable identifer, supporting variable variables
										// singel identifier element
varvar(res)			 ::= varvarele(v). {res = v;}
										// sequence of identifier elements
varvar(res)			 ::= varvar(v1) varvarele(v2). {res = v1.".".v2;}
										// fix sections of element
varvarele(res)	 ::= ID(s). {res = s;}
										// variable sections of element
varvarele(res)	 ::= LDEL expr(e) RDEL. {res = "(".e.")";}

//
// objects
//
object(res)      ::= DOLLAR varvar(v) objectchain(oc). { res = '$this->smarty->tpl_vars['. v .']'.oc;}
										// single element
objectchain(res) ::= objectelement(oe). {res  = oe; }
										// cahin of elements 
objectchain(res) ::= objectchain(oc) objectelement(oe). {res  = oc.oe; }
										// variable
objectelement(res)::= PTR varvar(v).	{ res = '->'.v;}
										// method
objectelement(res)::= PTR method(f).	{ res = '->'.f;}

//
// function
//
										// function with parameter
function(res)     ::= ID(f) OPENP params(p) CLOSEP.	{ res = "\$this->smarty->function->".f . "(". p .")";}
										// function without parameter
function(res)     ::= ID(f) OPENP CLOSEP.	{ res = "\$this->smarty->function->".f."()";}

//
// method
//
										// function with parameter
method(res)     ::= ID(f) OPENP params(p) CLOSEP.	{ res = f . "(". p .")";}
										// function without parameter
method(res)     ::= ID(f) OPENP CLOSEP.	{ res = f."()";}

// function parameter
										// single parameter
params(res)       ::= expr(e). { res = e;}
										// multiple parameters
params(res)       ::= params(p) COMMA expr(e). { res = p.",".e;}

//
// modifier
//  
modifier(res)    ::= VERT ID(s). { res =  s;}
// modifier parameter
										// single parameter
modparameters(res) ::= modparameter(mp). {res = mp;}
										// multiple parameter
modparameters(res) ::= modparameters(mps) modparameter(mp). { res = mps.",".mp;}
										// parameter expression
modparameter(res) ::= COLON expr(mp). {res = mp;}

//
// if expressions
//
										// single if expression
ifexprs(res)			 ::= ifexpr(e).	{res = e;}
ifexprs(res)			 ::= NOT ifexpr(e).	{res = '!'.e;}
ifexprs(res)			 ::= OPENP ifexpr(e) CLOSEP.	{res = '('.e.')';}
ifexprs(res)			 ::= NOT OPENP ifexpr(e) CLOSEP.	{res = '!('.e.')';}

// if expression
										// simple expression
ifexpr(res)        ::= expr(e). {res =e;}
ifexpr(res)        ::= expr(e1) ifcond(c) expr(e2). {res = e1.c.e2;}
ifexpr(res)			   ::= ifexprs(e1) lop(o) ifexprs(e2).	{res = e1.o.e2;}

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
arrayelement(res)		 ::=  ID(e1) APTR expr(e2). { res = e1.'=>'.e2;}
arrayelement(res)		 ::=  array(a). { res = a;}

doublequoted(res)          ::= doublequoted(o1) other(o2). {res = o1.o2;}
doublequoted(res)          ::= other(o). {res = o;}
other(res)           ::= LDEL variable(v) RDEL. {res = "'.".v.".'";}
other(res)           ::= OTHER(o). {res = o;}