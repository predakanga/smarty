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

    public static function escape_start_tag($tag_text) {
       $tag = preg_replace('/\A<\?(.*)\z/', '<<?php ?>?\1', $tag_text, -1 , $count); //Escape tag
       assert($tag !== false && $count === 1);
       return $tag;
    }

    public static function escape_end_tag($tag_text) {
       assert($tag_text === '?>');
       return '?<?php ?>>';
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
// complete template
//
start(res)       ::= template(t). { res = t; }

//
// loop over template elements
//
											// single template element
template(res)       ::= template_element(e). {if ($this->template->extract_code == false) {
                                                  res = e;
                                               } else {
                                                 // store code in extract buffer
                                                  $this->template->extracted_compiled_code .= e;
                                               } 
                                             }
											// loop of elements
template(res)       ::= template(t) template_element(e). {if ($this->template->extract_code == false) {
                                                             res = t.e;
                                                           } else {
                                                             // store code in extract buffer
                                                             $this->template->extracted_compiled_code .= e;
                                                             res = t;
                                                           } 
                                                          }

//
// template elements
//
											// Smarty tag
template_element(res)::= smartytag(st). {
                                          if ($this->compiler->has_code) {
                                            $tmp =''; foreach ($this->compiler->prefix_code as $code) {$tmp.=$code;} $this->compiler->prefix_code=array();
                                            res = $this->compiler->processNocacheCode($tmp.st,true);
                                         } else { res = st;}  $this->compiler->has_variable_string = false;}	

											// comments
template_element(res)::= COMMENT. { res = '';}

											// Literal
template_element(res) ::= literal(l). { res = l; }

											// <?php> tag
template_element(res)::= PHPSTARTTAG(st) php_code(t) PHPENDTAG. {
                                      if ($this->sec_obj->php_handling == SMARTY_PHP_PASSTHRU) {
					                             res = self::escape_start_tag(st) . str_replace('<?','&lt;?',t) . '?<??>>';
                                      } elseif ($this->sec_obj->php_handling == SMARTY_PHP_QUOTE) {
                                       res = $this->compiler->processNocacheCode(htmlspecialchars(st.t.'?>', ENT_QUOTES), false);
                                      }elseif ($this->sec_obj->php_handling == SMARTY_PHP_ALLOW) {
                                       res = $this->compiler->processNocacheCode('<?php'.t.'?>', true);
                                      }elseif ($this->sec_obj->php_handling == SMARTY_PHP_REMOVE) {
                                       res = '';
                                      }
                                     }


											// Other template text
template_element(res)::= OTHER(o). {if ($this->lex->strip) {
                                       res = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', o);	
                                     } else {
                                       res = o;	
                                     }
                                    }
template_element(res)::= FAKEPHPSTARTTAG(t). {if ($this->lex->strip) {
                                       res = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', self::escape_start_tag(t));	
                                     } else {
                                       res = self::escape_start_tag(t);	
                                     }
                                    }



php_code(res) ::= php_code_element(c1) php_code(c2). { res = c1.c2; }
php_code(res) ::= . { res = ''; }

php_code_element(res) ::= PHP_CODE(c). { res = c; }
php_code_element(res) ::= PHP_CODE_START_DOUBLEQUOTE(c1) php_dq_contents(c2) PHP_CODE_DOUBLEQUOTE(c3). { res = c1.c2.c3; }
php_code_element(res) ::= PHP_HEREDOC_START(c1) php_dq_contents(c2) PHP_HEREDOC_END(c3). { res = c1.c2.c3; }
php_code_element(res) ::= PHP_NOWDOC_START(c1)  php_dq_contents(c2) PHP_NOWDOC_END(c3) . { res = c1.c2.c3; }

php_dq_contents(res) ::= php_dq_content(c1) php_dq_contents(c2). { res = c1.c2; }
php_dq_contents(res) ::= . { res = ''; }

php_dq_content(res) ::= PHP_DQ_CONTENT(c). { res = c; }
php_dq_content(res) ::= PHP_DQ_EMBED_START(c1) php_code(c2) PHP_DQ_EMBED_END(c3). { res = c1.c2.c3; }



literal(res) ::= LITERALSTART LITERALEND. { res = ''; }
literal(res) ::= LITERALSTART literal_elements(l) LITERALEND. { res = l; }
 
literal_elements(res) ::= literal_element(l1) literal_elements(l2). { res = l1.l2; }
literal_elements(res) ::= . { res = ''; }

literal_element(res) ::= literal(l). { res = l; }
literal_element(res) ::= LITERAL(l). { res = l; }
literal_element(res) ::= PHPSTARTTAG(st). { res = self::escape_start_tag(st); }
literal_element(res) ::= FAKEPHPSTARTTAG(st). { res = self::escape_start_tag(st); }
literal_element(res) ::= PHPENDTAG(et). { res = self::escape_end_tag(et); }


//
// output tags start here
//

									// output with optional attributes
smartytag(res)   ::= LDEL value(e) RDEL. { res = $this->compiler->compileTag('private_print_expression',array('value'=>e));}
smartytag(res)   ::= LDEL value(e) attributes(a) RDEL. { res = $this->compiler->compileTag('private_print_expression',array_merge(array('value'=>e),a));}
smartytag(res)   ::= LDEL variable(e) attributes(a) RDEL. { res = $this->compiler->compileTag('private_print_expression',array_merge(array('value'=>e),a));}
smartytag(res)   ::= LDEL expr(e) attributes(a) RDEL. { res = $this->compiler->compileTag('private_print_expression',array_merge(array('value'=>e),a));}
smartytag(res)   ::= LDEL ternary(t) attributes(a) RDEL. { res = $this->compiler->compileTag('private_print_expression',array_merge(array('value'=>t),a));}
//smartytag(res)   ::= LDEL expr(e) filter(f) modparameters(p) attributes(a) RDEL. { res = $this->compiler->compileTag('private_print_expression',array_merge(array('value'=>e),a));}

//
// Smarty tags start here
//

									// assign new style
smartytag(res)   ::= LDEL DOLLAR ID(i) EQUAL value(e) RDEL. { res = $this->compiler->compileTag('assign',array('value'=>e,'var'=>"'".i."'"));}									
smartytag(res)   ::= LDEL DOLLAR ID(i) EQUAL expr(e) RDEL. { res = $this->compiler->compileTag('assign',array('value'=>e,'var'=>"'".i."'"));}									
smartytag(res)   ::= LDEL DOLLAR ID(i) EQUAL expr(e) attributes(a) RDEL. { res = $this->compiler->compileTag('assign',array_merge(array('value'=>e,'var'=>"'".i."'"),a));}									
smartytag(res)   ::= LDEL DOLLAR ID(i) EQUAL ternary(t) attributes(a) RDEL. { res = $this->compiler->compileTag('assign',array_merge(array('value'=>t,'var'=>"'".i."'"),a));}									
smartytag(res)   ::= LDEL varindexed(vi) EQUAL expr(e) attributes(a) RDEL. { res = $this->compiler->compileTag('assign',array_merge(array('value'=>e),vi,a));}									
smartytag(res)   ::= LDEL varindexed(vi) EQUAL ternary(t) attributes(a) RDEL. { res = $this->compiler->compileTag('assign',array_merge(array('value'=>t),vi,a));}									
									// tag with optional Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) attributes(a) RDEL. { res = $this->compiler->compileTag(i,a);}
smartytag(res)   ::= LDEL FOREACH(i) attributes(a) RDEL. { res = $this->compiler->compileTag(i,a);}
smartytag(res)   ::= LDEL ID(i) RDEL. { res = $this->compiler->compileTag(i,array());}
									// registered object tag
smartytag(res)   ::= LDEL ID(i) PTR ID(m) attributes(a) RDEL. { res = $this->compiler->compileTag(i,array_merge(array('object_methode'=>m),a));}
									// tag with modifier and optional Smarty2 style attributes
smartytag(res)   ::= LDEL ID(i) modifier(m) modparameters(p) attributes(a) RDEL. {  res = '<?php ob_start();?>'.$this->compiler->compileTag(i,a).'<?php echo ';
                                                                                    res .= $this->compiler->compileTag('private_modifier',array('modifier'=>m,'params'=>'ob_get_clean()'.p)).'?>';
                                                                                 }
									// registered object tag with modifiers
smartytag(res)   ::= LDEL ID(i) PTR ID(me) modifier(m) modparameters(p) attributes(a) RDEL. {  res = '<?php ob_start();?>'.$this->compiler->compileTag(i,array_merge(array('object_methode'=>me),a)).'<?php echo ';
                                                                                               res .= $this->compiler->compileTag('private_modifier',array('modifier'=>m,'params'=>'ob_get_clean()'.p)).'?>';
                                                                                            }
									// {if}, {elseif} and {while} tag
smartytag(res)   ::= LDEL IF(i) SPACE ifexpr(ie) RDEL. { res = $this->compiler->compileTag((i == 'else if')? 'elseif' : i,array('if condition'=>ie));}
smartytag(res)   ::= LDEL IF(i) UNIMATH(m) ifexpr(ie) RDEL. { res = $this->compiler->compileTag((i == 'else if')? 'elseif' : i,array('if condition'=>trim(m).ie));}
smartytag(res)   ::= LDEL IF(i) SPACE statement(ie) RDEL. { res = $this->compiler->compileTag((i == 'else if')? 'elseif' : i,array('if condition'=>ie));}
									// {for} tag
smartytag(res)   ::= LDEL FOR(i) SPACE statements(st) SEMICOLON optspace ifexpr(ie) SEMICOLON optspace DOLLAR varvar(v2) foraction(e2) RDEL. {
                                                             res = $this->compiler->compileTag(i,array('start'=>st,'ifexp'=>ie,'varloop'=>v2,'loop'=>e2));}
  foraction(res)	 ::= EQUAL expr(e). { res = '='.e;}
  foraction(res)	 ::= INCDEC(e). { res = e;}
smartytag(res)   ::= LDEL FOR(i) SPACE statement(st) TO expr(v) attributes(a) RDEL. { res = $this->compiler->compileTag(i,array_merge(array('start'=>st,'to'=>v),a));}
//smartytag(res)   ::= LDEL FOR(i) SPACE statement(st) TO expr(v) STEP expr(v2) RDEL. { res = $this->compiler->compileTag(i,array('start'=>st,'to'=>v,'step'=>v2));}
									// {foreach $array as $var} tag
smartytag(res)   ::= LDEL FOREACH(i) SPACE value(v1) AS DOLLAR varvar(v0) RDEL. {
                                                            res = $this->compiler->compileTag(i,array('from'=>v1,'item'=>v0));}
smartytag(res)   ::= LDEL FOREACH(i) SPACE value(v1) AS DOLLAR varvar(v2) APTR DOLLAR varvar(v0) RDEL. {
                                                            res = $this->compiler->compileTag(i,array('from'=>v1,'item'=>v0,'key'=>v2));}
smartytag(res)   ::= LDEL FOREACH(i) SPACE array(a) AS DOLLAR varvar(v0) RDEL. { 
                                                            res = $this->compiler->compileTag(i,array('from'=>a,'item'=>v0));}
smartytag(res)   ::= LDEL FOREACH(i) SPACE array(a) AS DOLLAR varvar(v1) APTR DOLLAR varvar(v0) RDEL. { 
                                                            res = $this->compiler->compileTag(i,array('from'=>a,'item'=>v0,'key'=>v1));}

									// end of block tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) RDEL. { res = $this->compiler->compileTag(i.'close',array());}
smartytag(res)   ::= LDELSLASH specialclose(i) RDEL. { res = $this->compiler->compileTag(i.'close',array());}
specialclose(res)::= IF(i). { res = i; }
specialclose(res)::= FOR(i). { res = i; }
specialclose(res)::= FOREACH(i). { res = i; }
smartytag(res)   ::= LDELSLASH ID(i) attributes(a) RDEL. { res = $this->compiler->compileTag(i.'close',a);}
smartytag(res)   ::= LDELSLASH ID(i) modifier(m) modparameters(p) attributes(a) RDEL. {  res = '<?php ob_start();?>'.$this->compiler->compileTag(i.'close',a).'<?php echo ';
                                                                                         res .= $this->compiler->compileTag('private_modifier',array('modifier'=>m,'params'=>'ob_get_clean()'.p)).'?>';
                                                                                      }
									// end of block object tag  {/....}									
smartytag(res)   ::= LDELSLASH ID(i) PTR ID(m) RDEL. {  res = $this->compiler->compileTag(i.'close',array('object_methode'=>m));}


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
attribute(res)   ::= SPACE ID(v) EQUAL ID(i). { res = array(v=>"'".i."'");}
attribute(res)   ::= SPACE ID(v) EQUAL expr(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL value(e). { res = array(v=>e);}
attribute(res)   ::= SPACE ID(v) EQUAL ternary(t). { res = array(v=>t);}
attribute(res)   ::= SPACE ID(v). { res = array(v=>'true');}
attribute(res)   ::= SPACE INTEGER(i) EQUAL expr(e). { res = array(i=>e);}
									

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
expr(res)        ::= expr(e) modifier(m) modparameters(p). {  res = $this->compiler->compileTag('private_modifier',array('modifier'=>m,'params'=>e.p)); }

									// single value
exprs(res)        ::= value(v). { res = v; }
									// +/- value
//exprs(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// logical negation
//exprs(res)		   ::= NOT value(v). { res = '!'.v; }
									// arithmetic expression
exprs(res)        ::= exprs(e) MATH(m) value(v). { res = e . trim(m) . v; } 
exprs(res)        ::= exprs(e) UNIMATH(m) value(v). { res = e . trim(m) . v; } 
									// bit operation 
exprs(res)        ::= exprs(e) ANDSYM(m) value(v). { res = e . trim(m) . v; } 
                  // array
exprs(res)				::= array(a).	{res = a;}
exprs(res)        ::= exprs(e1) ifcond(c) value(e2). {res = e1.c.e2;}
exprs(res)			  ::= exprs(e1) lop(o) value(e2).	{res = e1.o.e2;}

//
// ternary
//
ternary(res)				::= OPENP ifexpr(if) CLOSEP  QMARK  expr(e1)  COLON  expr(e2). { res = if.' ? '.e1.' : '.e2;}
ternary(res)				::= OPENP expr(v) CLOSEP  QMARK  expr(e1) COLON  expr(e2). { res = v.' ? '.e1.' : '.e2;}


								 // value
value(res)		   ::= variable(v). { res = v; }
									// +/- value
value(res)        ::= UNIMATH(m) value(v). { res = m.v; }
									// logical negation
value(res)		   ::= NOT value(v). { res = '!'.v; }
value(res)		   ::= TYPECAST(t) value(v). { res = t.v; }
value(res)		   ::= variable(v) INCDEC(o). { res = v.o; }
                 // numeric
value(res)       ::= INTEGER(n). { res = n; }
value(res)       ::= INTEGER(n1) DOT INTEGER(n2). { res = n1.'.'.n2; }
									// constant
//value(res)       ::= CONSTANT(c). { res = c; }
									// boolean
value(res)       ::= BOOLEAN(b). { res = b; }
									// null
value(res)       ::= NULL(n). { res = n; }
									// function call
value(res)	     ::= function(f). { res = f; }
									// expression
value(res)       ::= OPENP expr(e) CLOSEP. { res = "(". e .")"; }
									// singele quoted string
value(res)	     ::= SINGLEQUOTESTRING(t). { res = t; }
									// double quoted string
value(res)	     ::= QUOTE doublequoted(s) QUOTE. { $_s = str_replace(array('."".','.""'),array('.',''),'"'.s.'"'); 
                                                    if (substr($_s,0,3) == '"".') {
                                                      res = substr($_s,3);
                                                    } else {
                                                      res = $_s;
                                                    }
                                                  }
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
value(res)	     ::= smartytag(st). { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php ob_start();?>'.st.'<?php $_tmp'.$this->prefix_number.'=ob_get_clean();?>'; res = '$_tmp'.$this->prefix_number; }


//
// variables 
//
									// simplest Smarty variable
//variable(res)    ::= DOLLAR varvar(v).  { res = '$_smarty_tpl->getVariable(\''. v .'\')->value'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable('v', null, true, false)->nocache;}
									// Smarty variable (optional array)
variable(res)    ::= varindexed(vi). {if (vi['var'] == '\'smarty\'') { res =  $this->compiler->compileTag('private_special_variable',vi['smarty_internal_index']);} else {
                                                         res = '$_smarty_tpl->getVariable('. vi['var'] .')->value'.vi['smarty_internal_index']; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(vi['var'],"'"), null, true, false)->nocache;}}
									// variable with property
variable(res)    ::= DOLLAR varvar(v) AT ID(p). { res = '$_smarty_tpl->getVariable('. v .')->'.p; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(v,"'"), null, true, false)->nocache;}
									// object
variable(res)    ::= object(o). { res = o; }
                  // config variable
variable(res)	   ::= HATCH ID(i) HATCH. {res = '$_smarty_tpl->getConfigVariable(\''. i .'\')';}
variable(res)	   ::= HATCH variable(v) HATCH. {res = '$_smarty_tpl->getConfigVariable('. v .')';}
                  // stream access

varindexed(res)  ::= DOLLAR varvar(v) arrayindex(a). {res = array('var'=>v, 'smarty_internal_index'=>a);}

//
// array index
//
										// multiple array index
arrayindex(res)  ::= arrayindex(a1) indexdef(a2). {res = a1.a2;}
										// no array index
arrayindex        ::= . {return;}

// single index definition
										// Smarty2 style index 
indexdef(res)    ::= DOT DOLLAR varvar(v).  { res = '[$_smarty_tpl->getVariable('. v .')->value]'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable('v', null, true, false)->nocache;}
indexdef(res)    ::= DOT DOLLAR varvar(v) AT ID(p). { res = '[$_smarty_tpl->getVariable('. v .')->'.p.']'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(v,"'"), null, true, false)->nocache;}
//indexdef(res)    ::= DOT object(o). { res = '['.o.']'; }
//indexdef(res)   ::= DOT variable(v). { res = "[".v."]";}
indexdef(res)   ::= DOT ID(i). { res = "['". i ."']";}
//indexdef(res)   ::= DOT CONSTANT(i). { res = "['". i ."']";}
indexdef(res)   ::= DOT BOOLEAN(i). { res = "['". i ."']";}
indexdef(res)   ::= DOT NULL(i). { res = "['". i ."']";}
indexdef(res)   ::= DOT INTEGER(n). { res = "[". n ."]";}
indexdef(res)   ::= DOT LDEL exprs(e) RDEL. { res = "[". e ."]";}
										// section tag index
indexdef(res)   ::= OPENB ID(i)CLOSEB. { res = '['.$this->compiler->compileTag('private_special_variable','[\'section\'][\''.i.'\'][\'index\']').']';}
indexdef(res)   ::= OPENB ID(i) DOT ID(i2) CLOSEB. { res = '['.$this->compiler->compileTag('private_special_variable','[\'section\'][\''.i.'\'][\''.i2.'\']').']';}
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
//varvarele(res)	 ::= CONSTANT(s). {res = '\''.s.'\'';}
										// variable sections of element
varvarele(res)	 ::= LDEL expr(e) RDEL. {res = '('.e.')';}

//
// objects
//
object(res)    ::= varindexed(vi) objectchain(oc). { if (vi['var'] == '\'smarty\'') { res =  $this->compiler->compileTag('private_special_variable',vi['smarty_internal_index']).oc;} else {
                                                         res = '$_smarty_tpl->getVariable('. vi['var'] .')->value'.vi['smarty_internal_index'].oc; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(vi['var'],"'"), null, true, false)->nocache;}}
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
modifier(res)    ::= VERT AT ID(m). { res =  m;}
modifier(res)    ::= VERT ID(m). { res =  m;}


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

// if expressions
										// simple expression
ifexpr(res)        ::= expr(e). {res =e;}
ifexpr(res)			   ::= expr(e1) ISIN array(a).	{res = 'in_array('.e1.','.a.')';}
ifexpr(res)			   ::= expr(e1) ISIN value(v).	{res = 'in_array('.e1.',(array)'.v.')';}
ifexpr(res)			   ::= expr(e1) ISDIVBY expr(e2).	{res = '!('.e1.' % '.e2.')';}
ifexpr(res)			   ::= expr(e1) ISNOTDIVBY expr(e2).	{res = '('.e1.' % '.e2.')';}
ifexpr(res)			   ::= expr(e1) ISEVEN.	{res = '!(1 & '.e1.')';}
ifexpr(res)			   ::= expr(e1) ISNOTEVEN.	{res = '(1 & '.e1.')';}
ifexpr(res)			   ::= expr(e1) ISEVENBY expr(e2).	{res = '!(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= expr(e1) ISNOTEVENBY expr(e2).	{res = '(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= expr(e1) ISODD.	{res = '(1 & '.e1.')';}
ifexpr(res)			   ::= expr(e1) ISNOTODD.	{res = '!(1 & '.e1.')';}
ifexpr(res)			   ::= expr(e1) ISODDBY expr(e2).	{res = '(1 & '.e1.' / '.e2.')';}
ifexpr(res)			   ::= expr(e1) ISNOTODDBY expr(e2).	{res = '!(1 & '.e1.' / '.e2.')';}
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
ifcond(res)        ::= MOD. {res = '%';}

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
arrayelement(res)		 ::=  value(e1) APTR expr(e2). { res = e1.'=>'.e2;}
arrayelement(res)		 ::=  ID(i) APTR expr(e2). { res = '\''.i.'\'=>'.e2;}
arrayelement(res)		 ::=  expr(e). { res = e;}


//
// double qouted strings
//
doublequoted(res)          ::= doublequoted(o1) doublequotedcontent(o2). {res = o1.o2;}
doublequoted(res)          ::= doublequotedcontent(o). {res = o;}
doublequotedcontent(res)           ::=  BACKTICK variable(v) BACKTICK. {res = '{'.v.'}'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  BACKTICK expr(e) BACKTICK. {res = '{'.e.'}'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  DOLLARID(i). {res = '{$_smarty_tpl->getVariable(\''. substr(i,1) .'\')->value}'; $this->compiler->tag_nocache=$this->compiler->tag_nocache|$this->template->getVariable(trim(i,"'"), null, true, false)->nocache; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  LDEL variable(v) RDEL. {if (substr(v,0,1) == '\'') {
                                                                 res = '".'.v.'."'; $this->compiler->has_variable_string = true;
                                                                } else {
                                                                 res = '{'.v.'}'; $this->compiler->has_variable_string = true;
                                                                }
                                                               }
doublequotedcontent(res)           ::=  LDEL expr(e) RDEL. { res = '".('.e.')."'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res) 	         ::=  smartytag(st). { $this->prefix_number++; $this->compiler->prefix_code[] = '<?php ob_start();?>'.st.'<?php $_tmp'.$this->prefix_number.'=ob_get_clean();?>'; res = '{$_tmp'.$this->prefix_number.'}'; $this->compiler->has_variable_string = true;}
doublequotedcontent(res)           ::=  OTHER(o). {res = o;}


//
// optional space
//
optspace(res)			::= SPACE(s).  {res = s;}
optspace(res)			::= .          {res = '';}
