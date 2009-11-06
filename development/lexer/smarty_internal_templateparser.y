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
        $this->compiler = $compiler;
        $this->smarty = $this->compiler->smarty;
        $this->template = $this->compiler->template;
        if ($this->template->security && isset($this->smarty->security_handler)) {
              $this->sec_obj = $this->smarty->security_policy;
        } else {
              $this->sec_obj = $this->smarty;
        }
        $this->cacher = $this->template->cacher_object; 
        $this->compiler->has_variable_string = false;
				$this->compiler->prefix_code = array();
				$this->prefix_number = 0;
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
    $this->yymajor = $yymajor;
    $this->compiler->trigger_template_error();
}

//
// fallback definition to catch all non Smarty template text
//
%fallback     OTHER XML PHP SHORTTAGSTART SHORTTAGEND PHPSTART PHPEND COMMENT SINGLEQUOTE LITERALSTART LITERALEND
              LDELIMTAG RDELIMTAG LDELSLASH LDEL RDEL ISIN AS BOOLEAN  NULL  IDENTITY NONEIDENTITY EQUALS NOTEQUALS GREATEREQUAL 
              LESSEQUAL GREATERTHAN LESSTHAN NOT LAND LOR LXOR ISODDBY ISNOTODDBY ISODD ISNOTODD ISEVENBY ISNOTEVENBY ISEVEN 
              ISNOTEVEN  ISDIVBY ISNOTDIVBY OPENP CLOSEP OPENB CLOSEB PTR APTR EQUAL INTEGER INCDEC UNIMATH MATH DOLLAR COLON 
              DOUBLECOLON SEMICOLON  AT HATCH QUOTE BACKTICK VERT DOT COMMA ANDSYM ID SPACE INSTANCEOF QMARK. 
              

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
template_element(res)::= LDEL smartytag(st) RDEL. {
                                          if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->compiler->prefix_code as $code) {$tmp.=$code;} $this->compiler->prefix_code=array();
                                            res = $this->cacher->processNocacheCode($tmp.st, $this->compiler,true);
                                         } else { res = st;}  $this->compiler->has_variable_string = false;}	
											// Smarty closing tag
template_element(res)::= LDELSLASH smartyclosetag(st) RDEL. { 
                                          if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->compiler->prefix_code as $code) {$tmp.=$code;} $this->compiler->prefix_code=array();
                                            res = $this->cacher->processNocacheCode($tmp.st, $this->compiler,true);
                                         } else { res = st;} $this->compiler->has_variable_string = false;}	
											// Output tag
template_element(res)::= LDEL outputtag(st) RDEL. {
                                          if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->compiler->prefix_code as $code) {$tmp.=$code;} $this->compiler->prefix_code=array();
                                            res = $this->cacher->processNocacheCode($tmp.st, $this->compiler,true);
                                         } else { res = st;} $this->compiler->has_variable_string = false;}	
											// comments
template_element(res)::= COMMENT. { res = '';}
											// Literal
template_element(res)::= LITERALSTART text(t) LITERALEND. { res = $this->cacher->processNocacheCode(t, $this->compiler,false);}	
											// {ldelim}
template_element(res)::= LDELIMTAG. {res = $this->cacher->processNocacheCode($this->smarty->left_delimiter, $this->compiler,false);}	
											// {rdelim}
template_element(res)::= RDELIMTAG. { res = $this->cacher->processNocacheCode($this->smarty->right_delimiter, $this->compiler,false);}	
											// <?php> tag
template_element(res)::= PHP OTHER(t) SHORTTAGEND.  {if ($this->sec_obj->php_handling == SMARTY_PHP_PASSTHRU) {
                                       res = $this->cacher->processNocacheCode("<?php echo htmlspecialchars('<?php".str_replace("'","\'",t)."?>', ENT_QUOTES);?>\n", $this->compiler, false);
                                      } elseif ($this->sec_obj->php_handling == SMARTY_PHP_QUOTE) {
                                       res = $this->cacher->processNocacheCode(htmlspecialchars('<?php'.t.'?>', ENT_QUOTES), $this->compiler, false);
                                      }elseif ($this->sec_obj->php_handling == SMARTY_PHP_ALLOW) {
                                       res = $this->cacher->processNocacheCode('<?php'.t.'?>', $this->compiler, true);
                                      }elseif ($this->sec_obj->php_handling == SMARTY_PHP_REMOVE) {
                                       res = '';
                                      }
                                     } 

template_element(res)::= SHORTTAGSTART OTHER(o) SHORTTAGEND. { 
                                      if ($this->sec_obj->php_handling == SMARTY_PHP_PASSTHRU || $this->sec_obj->php_handling == SMARTY_PHP_ALLOW) {
                                       res = $this->cacher->processNocacheCode("<?php echo '<?=".o."?>'?>\n", $this->compiler, false);
                                      } elseif ($this->sec_obj->php_handling == SMARTY_PHP_QUOTE) {
                                       res = $this->cacher->processNocacheCode(htmlspecialchars('<?='.o.'?>', ENT_QUOTES), $this->compiler, false);
                                      }elseif ($this->sec_obj == SMARTY_PHP_REMOVE) {
                                       res = '';
                                      }
                                     }

											// XML tag
template_element(res)::= XML(x). { $this->compiler->tag_nocache = true; res = $this->cacher->processNocacheCode("<?php echo '<?xml';?>", $this->compiler, true);}	
template_element(res)::= SHORTTAGEND. {$this->compiler->tag_nocache = true; res = $this->cacher->processNocacheCode("<?php echo '?>';?>\n", $this->compiler, true);}	
											// Other template text
//template_element(res)::= OTHER(o). { res = $this->cacher->processNocacheCode(o.$this->lex->lexText(''), $this->compiler,false);}	
template_element(res)::= OTHER(o). {res = $this->cacher->processNocacheCode(o, $this->compiler,false);}	


//
// output tags start here
//
									// output with optional attributes
outputtag(res)   ::= variable(e) attributes(a). { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>e),a));}
outputtag(res)   ::= expr(e) attributes(a). { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>e),a));}
outputtag(res)   ::= ternary(t) attributes(a). { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>t),a));}
//outputtag(res)   ::= expr(e) filter(f) modparameters(p) attributes(a). { res = $this->compiler->compileTag('print_expression',array_merge(array('value'=>e),a));}

//
// Smarty tags start here
//

									// assign new style
smartytag(res)   ::= varindexed(vi) EQUAL expr(e) attributes(a). { res = $this->compiler->compileTag('assign',array_merge(array('value'=>e),vi,a));}									
smartytag(res)   ::= varindexed(vi) EQUAL ternary(t) attributes(a). { res = $this->compiler->compileTag('assign',array_merge(array('value'=>t),vi,a));}									
									// tag with optional Smarty2 style attributes
smartytag(res)   ::= ID(i) attributes(a). { res = $this->compiler->compileTag(i,a);}
									// registered object tag
smartytag(res)   ::= ID(i) PTR ID(m) attributes(a). { res = $this->compiler->compileTag(i,array_merge(array('object_methode'=>m),a));}
									// tag with modifier and optional Smarty2 style attributes
smartytag(res)   ::= ID(i) modifier(m) modparameters(p) attributes(a). {  res = '<?php ob_start();?>'.$this->compiler->compileTag(i,a).'<?php echo ';
															                                   if ($this->smarty->plugin_handler->loadSmartyPlugin(m[0],'modifier')) {
                                                                      res .= "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(ob_get_clean()" . p. "),".m[1].");?>";
                                                                 } else {
                                                                   if (is_callable(m[0])) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier(m[0], $this->compiler)) {
                                                                         res .= "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(ob_get_clean()" . p. "),".m[1].");?>";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . m[0] . "\"");
                                                                 }
                                                              }
                                                                    }
									// {if}, {elseif} and {while} tag
smartytag(res)   ::= ID(i) SPACE ifexprs(ie). {if (!in_array(i,array('if','elseif','while'))) {
                                                            $this->compiler->trigger_template_error ("wrong syntax for tag \"" . i . "\""); 
                                                            }
                                                            res = $this->compiler->compileTag(i,array('if condition'=>ie));}
smartytag(res)   ::= ID(i) SPACE statement(ie). { if (!in_array(i,array('if','elseif','while'))) {
                                                            $this->compiler->trigger_template_error ("wrong syntax for tag \"" . i . "\""); 
                                                            }
                                                            res = $this->compiler->compileTag(i,array('if condition'=>ie));}
									// {for} tag
smartytag(res)   ::= ID(i) SPACE statements(st) SEMICOLON ifexprs(ie) SEMICOLON DOLLAR varvar(v2) foraction(e2). {
                                                            if (i != 'for') {
                                                               $this->compiler->trigger_template_error ("wrong syntax for tag \"" . i . "\""); 
                                                            }
                                                             res = $this->compiler->compileTag(i,array('start'=>st,'ifexp'=>ie,'varloop'=>v2,'loop'=>e2));}
  foraction(res)	 ::= EQUAL expr(e). { res = '='.e;}
  foraction(res)	 ::= INCDEC(e). { res = e;}
									// {foreach $array as $var} tag
smartytag(res)   ::= ID(i) SPACE value(v1) AS DOLLAR varvar(v0). {
                                                            if (i != 'foreach') {
                                                               $this->compiler->trigger_template_error ("wrong syntax for tag \"" . i . "\""); 
                                                            }
                                                            res = $this->compiler->compileTag(i,array('from'=>v1,'item'=>v0));}
smartytag(res)   ::= ID(i) SPACE array(a) AS DOLLAR varvar(v0). { 
                                                            if (i != 'foreach') {
                                                               $this->compiler->trigger_template_error ("wrong syntax for tag \"" . i . "\""); 
                                                            }
                                                            res = $this->compiler->compileTag(i,array('from'=>a,'item'=>v0));}

									// end of block tag  {/....}									
smartyclosetag(res)   ::= ID(i) attributes(a). { res = $this->compiler->compileTag(i.'close',a);}
smartyclosetag(res)   ::= ID(i) modifier(m) modparameters(p) attributes(a). {  res = '<?php ob_start();?>'.$this->compiler->compileTag(i.'close',a).'<?php echo ';
															                                   if ($this->smarty->plugin_handler->loadSmartyPlugin(m[0],'modifier')) {
                                                                      res .= "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(ob_get_clean()" . p. "),".m[1].");?>";
                                                                 } else {
                                                                   if (is_callable(m[0])) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier(m[0], $this->compiler)) {
                                                                         res .= "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(ob_get_clean()" . p. "),".m[1].");?>";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . m[0] . "\"");
                                                                 }
                                                              }
                                                                    }
									// end of block object tag  {/....}									
smartyclosetag(res)   ::= ID(i) PTR ID(m). {  res = $this->compiler->compileTag(i.'close',array('object_methode'=>m));}


//
//Attributes of Smarty tags 
//
									// list of attributes
attributes(res)  ::= attributes(a1) attribute(a2). { res = array_merge(a1,a2);}
									// single attribute
attributes(res)  ::= attribute(a). { res = a;}
									// no attributes
attributes(res)  ::= . { res = array();}
									
									// attribute
attribute(res)   ::= SPACE ID(v) EQUAL expr(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL ternary(t). { res = array(v=>t);}
attribute(res)   ::= SPACE ID(v). { res = array(v=>'true');}

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
expr(res)				 ::= ID(i). { res = '\''.i.'\''; }
expr(res)				 ::= exprs(e).	{res = e;}
                 // resources/streams
expr(res)	       ::= DOLLAR ID(i) COLON ID(i2). {res = '$_smarty_tpl->getStreamVariable(\''. i .'://'. i2 . '\')';}
expr(res)        ::= expr(e) modifier(m) modparameters(p). {            
                                                            if ($this->smarty->plugin_handler->loadSmartyPlugin(m[0],'modifier')) {
                                                                      res = "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(". e . p. "),".m[1].")";
                                                                 } else {
                                                                   if (is_callable(m[0])) {
																					                            if (!$this->template->security || $this->smarty->security_handler->isTrustedModifier(m[0], $this->compiler)) {
                                                                         res = "\$_smarty_tpl->smarty->plugin_handler->executeModifier('".m[0] . "',array(". e . p. "),".m[1].")";
																					                            }
																					                         } else {
                                                                      $this->compiler->trigger_template_error ("unknown modifier \"" . m[0] . "\"");
                                                                 }
                                                              }
                                                            }

									// single value
exprs(res)        ::= value(v). { res = v; }
									// +/- value
exprs(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// arithmetic expression
exprs(res)        ::= exprs(e) math(m) value(v). { res = e . m . v; } 
                  // array
exprs(res)				::= array(a).	{res = a;}

//
// ternary
//
ternary(res)				::= OPENP ifexprs(if) CLOSEP  QMARK  expr(e1)  COLON  expr(e2). { res = if.' ? '.e1.' : '.e2;}
ternary(res)				::= OPENP expr(v) CLOSEP  QMARK  expr(e1) COLON  expr(e2). { res = v.' ? '.e1.' : '.e2;}


//
// mathematical operators
//
									// +,-
math(res)        ::= UNIMATH(m). {res = m;}
									// *,/,%
math(res)        ::= MATH(m). {res = m;}

                  // bit operators
//bitop(res)        ::= ANDSYM. {res = ' & ';}
math(res)        ::= ANDSYM. {res = ' & ';}

								 // value
value(res)		   ::= variable(v). { res = v; }
value(res)		   ::= variable(v) INCDEC(o). { res = v.o; }
                 // numeric
value(res)       ::= INTEGER(n). { res = n; }
value(res)       ::= INTEGER(n1) DOT INTEGER(n2). { res = n1.'.'.n2; }
									// boolean
value(res)       ::= BOOLEAN(b). { res = b; }
									// null
value(res)       ::= NULL(n). { res = n; }
									// function call
value(res)	     ::= function(f). { res = f; }
									// expression
value(res)       ::= OPENP expr(e) CLOSEP. { res = "(". e .")"; }
									// singele quoted string
value(res)	     ::= SINGLEQUOTE text(t) SINGLEQUOTE. { res = "'".t."'"; }
value(res)	     ::= SINGLEQUOTE SINGLEQUOTE. { res = "''"; }
									// double quoted string
value(res)	     ::= QUOTE doublequoted(s) QUOTE. { res = '"'.s.'"'; }
value(res)	     ::= QUOTE QUOTE. { res = "''"; }
									// static class methode call
value(res)	     ::= ID(c) DOUBLECOLON method(m). { res = c.'::'.m; }
value(res)	     ::= ID(c) DOUBLECOLON DOLLAR ID(f) OPENP params(p) CLOSEP. { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. f .'\')->value;?>'; res = c.'::$_tmp'.$this->prefix_number.'('. p .')'; }
									// static class methode call with object chainig
value(res)	     ::= ID(c) DOUBLECOLON method(m) objectchain(oc). { res = c.'::'.m.oc; }
value(res)	     ::= ID(c) DOUBLECOLON DOLLAR ID(f) OPENP params(p) CLOSEP objectchain(oc). { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'=$_smarty_tpl->getVariable(\''. f .'\')->value;?>'; res = c.'::$_tmp'.$this->prefix_number.'('. p .')'.oc; }
									// static class constant
value(res)       ::= ID(c) DOUBLECOLON ID(v). { res = c.'::'.v;}
									// static class variables
value(res)       ::= ID(c) DOUBLECOLON DOLLAR ID(v) arrayindex(a). { res = c.'::$'.v.a;}
									// static class variables with object chain
value(res)       ::= ID(c) DOUBLECOLON DOLLAR ID(v) arrayindex(a) objectchain(oc). { res = c.'::$'.v.a.oc;}
								  // Smarty tag
value(res)	     ::= LDEL smartytag(st) RDEL. { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php ob_start();?>'.st.'<?php $_tmp'.$this->prefix_number.'=ob_get_clean();?>'; res = '$_tmp'.$this->prefix_number; }


//
// variables 
//
									// simplest Smarty variable
//variable(res)    ::= DOLLAR ID(v). { res = '$_smarty_tpl->getVariable(\''. v .'\')->value'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable('v')->nocache;}
									// Smarty variable (optional array)
variable(res)    ::= varindexed(vi). {if (vi['var'] == '\'smarty\'') { res =  $this->compiler->compileTag('special_smarty_variable',vi['index']);} else {
                                                         res = '$_smarty_tpl->getVariable('. vi['var'] .')->value'.vi['index']; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(vi['var'],"'"))->nocache;}}
									// variable with property
variable(res)    ::= DOLLAR varvar(v) AT ID(p). { res = '$_smarty_tpl->getVariable('. v .')->'.p; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(v,"'"))->nocache;}
									// object
variable(res)    ::= object(o). { res = o; }
                  // config variable
variable(res)	   ::= HATCH ID(i) HATCH. {res = '$_smarty_tpl->getConfigVariable(\''. i .'\')';}
variable(res)	   ::= HATCH variable(v) HATCH. {res = '$_smarty_tpl->getConfigVariable('. v .')';}
                  // stream access

varindexed(res)  ::= DOLLAR varvar(v) arrayindex(a). {res = array('var'=>v, 'index'=>a);}

//
// array index
//
										// multiple array index
arrayindex(res)  ::= arrayindex(a1) indexdef(a2). {res = a1.a2;}
										// no array index
arrayindex        ::= . {return;}

// single index definition
										// Smarty2 style index 
indexdef(res)   ::= DOT ID(i). { res = "['". i ."']";}
indexdef(res)   ::= DOT INTEGER(n). { res = "[". n ."]";}
indexdef(res)   ::= DOT variable(v). { res = "[".v."]";}
indexdef(res)   ::= DOT LDEL exprs(e) RDEL. { res = "[". e ."]";}
										// section tag index
indexdef(res)   ::= OPENB ID(i)CLOSEB. { res = '['.$this->compiler->compileTag('special_smarty_variable','[\'section\'][\''.i.'\'][\'index\']').']';}
indexdef(res)   ::= OPENB ID(i) DOT ID(i2) CLOSEB. { res = '['.$this->compiler->compileTag('special_smarty_variable','[\'section\'][\''.i.'\'][\''.i2.'\']').']';}
										// PHP style index
indexdef(res)   ::= OPENB exprs(e) CLOSEB. { res = "[". e ."]";}
										// für assign append array
indexdef(res)  ::= OPENB CLOSEB. {res = '';}

//
// variable variable names
//
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
object(res)    ::= varindexed(vi) objectchain(oc). { if (vi['var'] == '\'smarty\'') { res =  $this->compiler->compileTag('internal_smarty_var',vi['index']).oc;} else {
                                                         res = '$_smarty_tpl->getVariable('. vi['var'] .')->value'.vi['index'].oc; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(vi['var'],"'"))->nocache;}}
										// single element
objectchain(res) ::= objectelement(oe). {res  = oe; }
										// chain of elements 
objectchain(res) ::= objectchain(oc) objectelement(oe). {res  = oc.oe; }
										// variable
objectelement(res)::= PTR ID(i) arrayindex(a).	    { res = '->'.i.a;}
objectelement(res)::= PTR variable(v) arrayindex(a).	    { res = '->{'.v.a.'}';}
objectelement(res)::= PTR LDEL expr(e) RDEL arrayindex(a).	    { res = '->{'.e.a.'}';}
objectelement(res)::= PTR ID(ii) LDEL expr(e) RDEL arrayindex(a).	    { res = '->{\''.ii.'\'.'.e.a.'}';}
										// method
objectelement(res)::= PTR method(f).	{ res = '->'.f;}


//
// function
//
function(res)     ::= ID(f) OPENP params(p) CLOSEP.	{if (!$this->template->security || $this->smarty->security_handler->isTrustedPhpFunction(f, $this->compiler)) {
																					            if (f == 'isset' || f == 'empty' || f == 'array' || is_callable(f)) {
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
modifier(res)    ::= VERT AT ID(m). { res =  array(m,'false');}
modifier(res)    ::= VERT ID(m). { res =  array(m,'true');}


//
// filter
//  
//filter(res)    ::= HATCH VERT ID(m). { res = m;}

//
// modifier parameter
//
										// multiple parameter
modparameters(res) ::= modparameters(mps) modparameter(mp). { res = mps.mp;}
										// no parameter
modparameters      ::= . {return;}
										// parameter expression
modparameter(res) ::= COLON exprs(mp). {res = ','.mp;}
modparameter(res) ::= COLON ID(mp). {res = ',\''.mp.'\'';}

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
//ifexpr(res)        ::= value(v1) bitop(o) value(v2). {res =v1.o.v2;}
ifexpr(res)        ::= expr(e1) ifcond(c) expr(e2). {res = e1.c.e2;}
ifexpr(res)			   ::= expr(e1) ISIN array(a).	{res = 'in_array('.e1.','.a.')';}
ifexpr(res)			   ::= expr(e1) ISIN value(v).	{res = 'in_array('.e1.',(array)'.v.')';}
ifexpr(res)			   ::= ifexprs(e1) lop(o) ifexprs(e2).	{res = e1.o.e2;}
ifexpr(res)			   ::= ifexprs(e1) ISDIVBY ifexprs(e2).	{res = '!('.e1.' % '.e2.')';}
ifexpr(res)			   ::= ifexprs(e1) ISNOTDIVBY ifexprs(e2).	{res = '('.e1.' % '.e2.')';}
ifexpr(res)			   ::= ifexprs(e1) ISEVEN.	{res = '!(1 & '.e1.')';}
ifexpr(res)			   ::= ifexprs(e1) ISNOTEVEN.	{res = '(1 & '.e1.')';}
ifexpr(res)			   ::= ifexprs(e1) ISEVENBY ifexprs(e2).	{res = '!(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= ifexprs(e1) ISNOTEVENBY ifexprs(e2).	{res = '(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= ifexprs(e1) ISODD.	{res = '(1 & '.e1.')';}
ifexpr(res)			   ::= ifexprs(e1) ISNOTODD.	{res = '!(1 & '.e1.')';}
ifexpr(res)			   ::= ifexprs(e1) ISODDBY ifexprs(e2).	{res = '(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= ifexprs(e1) ISNOTODDBY ifexprs(e2).	{res = '!(1 & '.e1.' / '.e2.')';}
ifexpr(res)        ::= value(v1) INSTANCEOF(i) ID(id). {res = v1.i.id;}
ifexpr(res)        ::= value(v1) INSTANCEOF(i) value(v2). {$this->prefix_number++; $this->compiler->prefix_code[] = '<?php $_tmp'.$this->prefix_number.'='.v2.';?>'; res = v1.i.'$_tmp'.$this->prefix_number;}

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
lop(res)        ::= LXOR. {res = ' XOR ';}

//
// ARRAY element assignment
//
array(res)		       ::=  OPENB arrayelements(a) CLOSEB.  { res = 'array('.a.')';}
arrayelements(res)   ::=  arrayelement(a).  { res = a; }
arrayelements(res)   ::=  arrayelements(a1) COMMA arrayelement(a).  { res = a1.','.a; }
arrayelements        ::=  .  { return; }
arrayelement(res)		 ::=  expr(e1) APTR expr(e2). { res = e1.'=>'.e2;}
arrayelement(res)		 ::=  ID(i) APTR expr(e2). { res = '\''.i.'\'=>'.e2;}
arrayelement(res)		 ::=  expr(e). { res = e;}


//
// double qouted strings
//
doublequoted(res)          ::= doublequoted(o1) doublequotedcontent(o2). {res = o1.o2;}
doublequoted(res)          ::= doublequotedcontent(o). {res = o;}
doublequotedcontent(res)           ::=  BACKTICK ID(i) BACKTICK. {res = "`".i."`";}
doublequotedcontent(res)           ::=  BACKTICK variable(v) BACKTICK. {res = '".'.v.'."'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  DOLLAR ID(i). {res = '".'.'$_smarty_tpl->getVariable(\''. i .'\')->value'.'."'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(i,"'"))->nocache; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  LDEL expr(e) RDEL. { res = '".('.e.')."'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res) 	         ::=  LDEL smartytag(st) RDEL. { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php ob_start();?>'.st.'<?php $_tmp'.$this->prefix_number.'=ob_get_clean();?>'; res = '".$_tmp'.$this->prefix_number.'."'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  DOLLAR OTHER(o). {res = '$'.o;}
doublequotedcontent(res)           ::=  LDEL(d) OTHER(o). {res = d.o;}
doublequotedcontent(res)           ::=  BACKTICK OTHER(o). {res = '`'.o;}
doublequotedcontent(res)           ::= OTHER(o). {res = o;}

//
// text string
//
text(res)          ::= text(t) textelement(e). {res = t.e;}
text(res)          ::= textelement(e). {res = e;}
textelement(res)          ::= OTHER(o). {res = o;}
textelement(res)          ::= LDEL(o). {res = o;}
