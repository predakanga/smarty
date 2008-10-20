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
              NOT LAND LOR QUOTE BOOLEAN AS.


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
											// comments
template_element(res)::= COMMENTSTART commenttext(t) COMMENTEND. { res = '<?php /* comment placeholder */?>'; }	
											// PHP tag
template_element(res)::= PHP(php). {res = php;}	
											// Other template text
template_element(res)::= OTHER(o). {res = o;}	


//
// all Smarty tags start here
//
									// output with optional attributes
smartytag(res)   ::= LDEL expr(e) attributes(a) RDEL. { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>e),a),$this->nocache);$this->nocache=false;}
									// assign new style
smartytag(res)   ::= LDEL DOLLAR varvar(v) EQUAL expr(e) RDEL. { res = $this->compiler->compileTag('assign',array('var' => v, 'value'=>e),$this->nocache);$this->nocache=false;}									
smartytag(res)   ::= LDEL DOLLAR varvar(v) EQUAL array(e) RDEL. { res = $this->compiler->compileTag('assign',array('var' => v, 'value'=>e),$this->nocache);$this->nocache=false;}									
									// tag with optional Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) attributes(a) RDEL. { res =  $this->compiler->compileTag(i,a,$this->nocache);$this->nocache=false;}
									// end of block tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) RDEL. { res =  $this->compiler->compileTag('end_'.i,array());}
									// {if} and {elseif} tag
smartytag(res)   ::= LDEL ID(i) SPACE ifexprs(ie) RDEL. { res =  $this->compiler->compileTag(i,array('ifexp'=>ie));}
									// {for} tag
smartytag(res)   ::= LDEL ID(i) SPACE variable(v1) EQUAL expr(e1)SEMICOLON ifexprs(ie) SEMICOLON variable(v2) foraction(e2) RDEL. { res =  $this->compiler->compileTag(i,array('start'=>v1.'='.e1,'ifexp'=>ie,'loop'=>v2.e2));}
									// {foreach} tag
smartytag(res)   ::= LDEL ID(i) SPACE variable(v0) AS DOLLAR ID(v1) APTR DOLLAR ID(v2) RDEL. { res =  $this->compiler->compileTag(i,array('from'=>v0,'key'=>v1,'item'=>v2));}
foraction(res)	 ::= EQUAL expr(e). { res = '='.e;}
foraction(res)	 ::= INCDEC(e). { res = e;}

//
//Attributes of Smarty tags 
//
									// list of attributes
attributes(res)  ::= attributes(a1) attribute(a2). { res = array_merge(a1,a2);}
									// single attribute
attributes(res)  ::= attribute(a). { res = a;}
									// no attributes
attributes(res)  ::= . { res = array();}
									
									// different formats of attribute
attribute(res)   ::= SPACE ID(v) EQUAL expr(e). { res = array(v=>e);}
//attribute(res)   ::= SPACE ID(v) EQUAL ID(e). { res = array(v=>'e');}
attribute(res)   ::= SPACE ID(v) EQUAL array(a). { res = array(v=>a);}

//
// expressions
//
									// simple expression
expr(res)				 ::= exprs(e).	{res = e;}
									// expression with modifier and optional additional modifier paramter
expr(res)        ::= exprs(e) modifier(m) modparameters(p). {res = "\$this->smarty->modifier->".m . "(". e . p .")"; } 

									// single value
exprs(res)        ::= value(v). { res = v; }
									// +/- value
exprs(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// expression with simple modifier
//expr(res)        ::= expr(e) modifier(m). { res = "\$this->smarty->modifier->".m . "(". e .")"; }
									// arithmetic expression
exprs(res)        ::= expr(e) math(m) value(v). { res = e . m . v; } 
									// catenate
exprs(res)        ::= expr(e) DOT value(v). { res = e . '.' . v; } 

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
									// boolean
value(res)       ::= BOOLEAN(b). { res = b; }
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
									// identifier
value(res)	     ::= ID(i). { res = '\''.i.'\''; }

//
// variables 
//
									// simple Smarty variable
variable(res)    ::= DOLLAR varvar(v). { res = '$this->tpl_vars->getVariable('. v .')->value'; $_v = trim(v,"'"); if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;}
									// array variable
variable(res)    ::= DOLLAR varvar(v) vararraydefs(a). { res = '$this->tpl_vars->getVariable('. v .')->value'.a;$_v = trim(v,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;}
										// single array index
vararraydefs(res)  ::= vararraydef(a). {res = a;}
										// multiple array index
vararraydefs(res)  ::= vararraydefs(a1) vararraydef(a2). {res = a1.a2;}
// Smarty2 style index  not supported any longer
//vararraydef(res)   ::= DOT expr(e). { res = "[". e ."]";}
										// PHP style index
vararraydef(res)   ::= OPENB expr(e) CLOSEB. { res = "[". e ."]";}

// variable identifer, supporting variable variables
										// singel identifier element
varvar(res)			 ::= varvarele(v). {res = v;}
										// sequence of identifier elements
varvar(res)			 ::= varvar(v1) varvarele(v2). {res = v1.'.'.v2;}
										// fix sections of element
varvarele(res)	 ::= ID(s). {res = '\''.s.'\'';}
										// variable sections of element
varvarele(res)	 ::= LDEL expr(e) RDEL. {res = '('.e.')';}

//
// objects
//
object(res)      ::= DOLLAR varvar(v) objectchain(oc). { res = '$this->tpl_vars->getVariable('. v .')->value'.oc; $_v=trim(v,"'");if($this->tpl_vars->getVariable($_v)->nocache) $this->nocache=true;}
										// single element
objectchain(res) ::= objectelement(oe). {res  = oe; }
										// cahin of elements 
objectchain(res) ::= objectchain(oc) objectelement(oe). {res  = oc.oe; }
										// variable
objectelement(res)::= PTR ID(i).	    { res = '->'.i;}
//objectelement(res)::= PTR varvar(v).	{ res = '->'.v;}
										// method
objectelement(res)::= PTR method(f).	{ res = '->'.f;}

//
// function
//
										// function with parameter
function(res)     ::= ID(f) OPENP params(p) CLOSEP.	{ res = "\$this->smarty->function->".f . "(". p .")";}
										// function without parameter
//function(res)     ::= ID(f) OPENP CLOSEP.	{ res = "\$this->smarty->function->".f."()";}

//
// method
//
										// method with parameter
method(res)     ::= ID(f) OPENP params(p) CLOSEP.	{ res = f . "(". p .")";}
										// function without parameter
//method(res)     ::= ID(f) OPENP CLOSEP.	{ res = f."()";}

// function parameter
										// multiple parameters
params(res)       ::= expr(e) COMMA params(p). { res = e.",".p;}
										// single parameter
params(res)       ::= expr(e). { res = e;}
										// kein parameter
params            ::= . { return;}

//
// modifier
//  
modifier(res)    ::= VERT ID(s). { res =  s;}
// modifier parameter
										// multiple parameter
modparameters(res) ::= modparameters(mps) modparameter(mp). { res = mps.mp;}
										// single parameter
modparameters(res) ::= modparameter(mp). {res = mp;}
										// no parameter
modparameters      ::= . {return;}
										// parameter expression
modparameter(res) ::= COLON expr(mp). {res = ','.mp;}

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
other(res)           ::=  variable(v). {res = "'.".v.".'";}
other(res)           ::=  LDEL expr(e) RDEL. {res = "'.".e.".'";}
other(res)           ::= OTHER(o). {res = o;}

commenttext(res)          ::= commenttext(t) OTHER(o2). {res = t.o;}
commenttext(res)          ::= OTHER(o). {res = o;}
