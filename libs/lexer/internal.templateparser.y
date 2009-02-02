/**
* Smarty Internal Plugin Templateparser
*
* This is the template parser
* 
* 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
%name TP_
%declare_class {class Smarty_Internal_Templateparser}
%include_class
{
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
%fallback     OTHER LDELSLASH RDEL COMMENTSTART COMMENTEND NUMBER MATH UNIMATH INCDEC OPENP CLOSEP OPENB CLOSEB DOLLAR DOT COMMA COLON SEMICOLON
              VERT EQUAL SPACE PTR APTR ID SI_QSTR EQUALS NOTEQUALS GREATERTHAN LESSTHAN GREATEREQUAL LESSEQUAL IDENTITY NONEIDENTITY
              NOT LAND LOR QUOTE BOOLEAN IN ANDSYM BACKTICK AT.
              

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
template_element(res)::= smartytag(st). {if ($this->compiler->has_code) {
                                            res = $this->cacher->processNocacheCode(st, $this->compiler,$this->nocache,true);
                                         } $this->nocache=false;}	
											// comments
template_element(res)::= COMMENTSTART text(t) COMMENTEND. { res = $this->cacher->processNocacheCode('<?php /* comment placeholder */?>', $this->compiler,false,false);}	
											// Literal
template_element(res)::= LITERALSTART text(t) LITERALEND. {res = $this->cacher->processNocacheCode(t, $this->compiler,false,false);}	
											// {ldelim}
template_element(res)::= LDELIMTAG. {res = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false,false);}	
											// {rdelim}
template_element(res)::= RDELIMTAG. {res = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false,false);}	
											// <?php> tag
template_element(res)::= PHP(php). {if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      res = $this->cacher->processNocacheCode(php, $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      res = $this->cacher->processNocacheCode(htmlspecialchars(php, ENT_QUOTES), $this->compiler, false, false);}}	
											// {PHP} tag
template_element(res)::= PHPSTART text(t) PHPEND. {if (!$this->template->security || $this->smarty->security_policy->php_handling == SMARTY_PHP_ALLOW) { 
                                      res = $this->cacher->processNocacheCode('<?php '.t.' ?>', $this->compiler, false,true);
                                      } elseif ($this->smarty->security_policy->php_handling == SMARTY_PHP_QUOTE) {
                                      res = $this->cacher->processNocacheCode(htmlspecialchars('<?php '.t.' ?>', ENT_QUOTES), $this->compiler, false, false);}}	
											// XML tag
template_element(res)::= XML(xml). {res = $this->cacher->processNocacheCode("<?php echo '".xml."';?>", $this->compiler, false, false);}	
											// Other template text
template_element(res)::= OTHER(o). {res = $this->cacher->processNocacheCode(o, $this->compiler,false,false);}	
//template_element(res)::= text(t). {res = $this->cacher->processNocacheCode(t, $this->compiler,false,false);}	


//
// all Smarty tags start here
//
									// output with optional attributes
smartytag(res)   ::= LDEL expr(e) attributes(a) RDEL. { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>e),a));}
									// assign new style
smartytag(res)   ::= LDEL statement(s) RDEL. { res = $this->compiler->compileTag('assign',s);}									
									// tag with optional Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) attributes(a) RDEL. { res =  $this->compiler->compileTag(i,a);}
									// registered object tag
smartytag(res)   ::= LDEL ID(i) PTR ID(m) attributes(a) RDEL. { res =  $this->compiler->compileTag(i,array_merge(array('object_methode'=>m),a));}
									// tag with modifier and optional Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) modifier(m) modparameters(p) attributes(a) RDEL. { res =  '<?php ob_start();?>'.$this->compiler->compileTag(i,a).'<?php echo ';
                                                                if (m == 'isset' || m == 'empty' || is_callable(m)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier(m, $this->compiler)) {
																					                           res .= m . "(ob_get_clean()". p .");?>";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin(m,'modifier')) {
                                                                      res .= "\$_smarty_tpl->smarty->plugin_handler->".m . "(array(ob_get_clean()". p ."),'modifier');?>";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . m . "\"");
                                                                 }
                                                              }
                                                            }
									// end of block tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) RDEL. { res =  $this->compiler->compileTag(i.'close',array());}
									// end of block object tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) PTR ID(m) RDEL. { res =  $this->compiler->compileTag(i.'close',array('object_methode'=>m));}
									// {if} and {elseif} tag
smartytag(res)   ::= LDEL ID(i)SPACE ifexprs(ie) RDEL. { res =  $this->compiler->compileTag(i,array('ifexp'=>ie));}
									// {for} tag
smartytag(res)   ::= LDEL ID(i) SPACE statements(s) SEMICOLON ifexprs(ie) SEMICOLON DOLLAR varvar(v2) foraction(e2) RDEL. { res =  $this->compiler->compileTag(i,array('start'=>s,'ifexp'=>ie,'varloop'=>v2,'loop'=>e2));}
									// {for $var in $array} tag
smartytag(res)   ::= LDEL ID(i) SPACE DOLLAR varvar(v0) IN variable(v1) RDEL. { res =  $this->compiler->compileTag(i,array('from'=>v1,'item'=>v0));}
smartytag(res)   ::= LDEL ID(i) SPACE DOLLAR varvar(v0) IN array(a) RDEL. { res =  $this->compiler->compileTag(i,array('from'=>a,'item'=>v0));}
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

//
// statement
//
statements(res)		::= statement(s). { res = array(s);}
statements(res)		::= statements(s1) COMMA statement(s). { s1[]=s; res = s1;}

statement(res)		::= DOLLAR varvar(v) EQUAL expr(e). { res = array('var' => v, 'value'=>e);}

//
// expressions
//
									// simple expression
expr(res)				 ::= exprs(e).	{res = e;}
									// array
expr(res)				 ::= array(a).	{res = a;}

									// single value
exprs(res)        ::= value(v). { res = v; }
									// +/- value
exprs(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// arithmetic expression
exprs(res)        ::= expr(e) math(m) value(v). { res = e . m . v; } 
									// catenate
exprs(res)        ::= expr(e) ANDSYM value(v). { res = e . '.' . v; } 

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
value(res)        ::= value(e) modifier(m) modparameters(p). {if (m == 'isset' || m == 'empty' || is_callable(m)) {
																					                       if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier(m, $this->compiler)) {
																					                           res = m . "(". e . p .")";
																					                        }
																					                    } else {
																					                       if ($this->smarty->plugin_handler->loadSmartyPlugin(m,'modifier')) {
                                                                      res = "\$_smarty_tpl->smarty->plugin_handler->".m . "(array(". e . p ."),'modifier')";
                                                                 } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier\"" . m . "\"");
                                                                 }
                                                              }
                                                            }

									// variable
value(res)		   ::= variable(v). { res = v; }
									// numeric constant
value(res)       ::= NUMBER(n). { res = n; }
									// function call
value(res)	     ::= function(f). { res = f; }
									// singele quoted string
value(res)	     ::= SI_QSTR(s). { res = s; }
									// double quoted string
value(res)	     ::= QUOTE doublequoted(s) QUOTE. { res = "'".s."'"; }
									// static class methode call
value(res)	     ::= ID(c) COLON COLON method(m). { res = c.'::'.m; }
									// static class methode call with object chainig
value(res)	     ::= ID(c) COLON COLON method(m) objectchain(oc). { res = c.'::'.m.oc; }
									// static class constant
value(res)       ::= ID(c) COLON COLON ID(v). { res = c.'::'.v;}
									// static class variables
value(res)       ::= ID(c) COLON COLON DOLLAR ID(v) vararraydefs(a). { res = c.'::$'.v.a;}
									// identifier
value(res)	     ::= ID(i). { res = '\''.i.'\''; }
									// boolean
value(res)       ::= BOOLEAN(b). { res = b; }
									// expression
value(res)       ::= OPENP expr(e) CLOSEP. { res = "(". e .")"; }

//
// variables 
//
									// simple Smarty variable (optional array)
variable(res)    ::= DOLLAR varvar(v) vararraydefs(a). { if (v == '\'smarty\'') { res =  $this->compiler->compileTag(trim(v,"'"),a);} else {
                                                         res = '$_smarty_tpl->getVariable('. v .')->value'.a; $_var = $this->template->getVariable(trim(v,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}}
									// variable with property
variable(res)    ::= DOLLAR varvar(v) AT ID(p). { res = '$_smarty_tpl->getVariable('. v .')->'.p; $_var = $this->template->getVariable(trim(v,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}
									// object
variable(res)    ::= object(o). { res = o; }
										// single array index
//vararraydefs(res)  ::= vararraydef(a). {res = a;}
										// multiple array index
vararraydefs(res)  ::= vararraydefs(a1) vararraydef(a2). {res = a1.a2;}
										// no array index
vararraydefs        ::= . {return;}
										// Smarty2 style index 
vararraydef(res)   ::= DOT expr(e). { res = "[". e ."]";}
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
object(res)      ::= DOLLAR varvar(v) vararraydefs(a) objectchain(oc). { res = '$_smarty_tpl->getVariable('. v .')->value'.a.oc; $_var = $this->template->getVariable(trim(v,"'")); if(!is_null($_var)) if ($_var->nocache) $this->nocache=true;}
										// single element
objectchain(res) ::= objectelement(oe). {res  = oe; }
										// chain of elements 
objectchain(res) ::= objectchain(oc) objectelement(oe). {res  = oc.oe; }
										// variable
objectelement(res)::= PTR ID(i) vararraydefs(a).	    { res = '->'.i.a;}
//objectelement(res)::= PTR varvar(v) vararraydefs(a).	{ res = '->'.v.a;}
										// method
objectelement(res)::= PTR method(f).	{ res = '->'.f;}

//
// function
//
function(res)     ::= ID(f) OPENP params(p) CLOSEP.	{if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction(f, $this->compiler)) {
																					            if (f == 'isset' || f == 'empty' || is_callable(f)) {
																					                res = f . "(". p .")";
																					            } else {
                                                       $this->compiler->trigger_template_error ("unknown function \"" . f . "\"");
                                                      }
                                                    }}

//
// method
//
method(res)     ::= ID(f) OPENP params(p) CLOSEP.	{ res = f . "(". p .")";}

// function/method parameter
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

//
// modifier parameter
//
										// multiple parameter
modparameters(res) ::= modparameters(mps) modparameter(mp). { res = mps.mp;}
										// no parameter
modparameters      ::= . {return;}
										// parameter expression
modparameter(res) ::= COLON expr(mp). {res = ','.mp;}

//
// if expressions
//
										// single if expression
ifexprs(res)			 ::= ifexpr(e).	{res = e;}
ifexprs(res)			 ::= NOT ifexprs(e).	{res = '!'.e;}
ifexprs(res)			 ::= OPENP ifexprs(e) CLOSEP.	{res = '('.e.')';}

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
ifcond(res)        ::= NONEIDENTITY. {res = '!==';}

lop(res)        ::= LAND. {res = '&&';}
lop(res)        ::= LOR. {res = '||';}

array(res)		       ::=  OPENB arrayelements(a) CLOSEB.  { res = 'array('.a.')';}
arrayelements(res)   ::=  arrayelement(a).  { res = a; }
arrayelements(res)   ::=  arrayelements(a1) COMMA arrayelement(a).  { res = a1.','.a; }
arrayelements        ::=  .  { return; }
arrayelement(res)		 ::=  expr(e). { res = e;}
arrayelement(res)		 ::=  expr(e1) APTR expr(e2). { res = e1.'=>'.e2;}

doublequoted(res)          ::= doublequoted(o1) doublequotedcontent(o2). {res = o1.o2;}
doublequoted(res)          ::= doublequotedcontent(o). {res = o;}
doublequotedcontent(res)           ::=  variable(v). {res = "'.".v.".'";}
doublequotedcontent(res)           ::=  BACKTICK variable(v) BACKTICK. {res = "'.".v.".'";}
doublequotedcontent(res)           ::=  LDEL expr(e) RDEL. {res = "'.(".e.").'";}
doublequotedcontent(res)           ::= OTHER(o). {res = addslashes(o);}
//doublequotedcontent(res)           ::= text(t). {res = addslashes(t);}

text(res)          ::= text(t) textelement(e). {res = t.e;}
text(res)          ::= textelement(e). {res = e;}
textelement(res)          ::= OTHER(o). {res = o;}
textelement(res)          ::= LDEL(o). {res = o;}
