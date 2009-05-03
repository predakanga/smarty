<?php
/**
* Smarty PHPunit tests compilation of {function} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for {function} tag tests
*/
class CompileFunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = Smarty::instance();
        SmartyTests::init();
        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test simple function call tag
    */
    public function testSimpleFunction()
    {
       $tpl = $this->smarty->createTemplate('string:{function name=functest default=\'default\'}{$default} {$param}{/function}{functest param=\'param\'}');
       $this->assertEquals("default param", $this->smarty->fetch($tpl));
    } 
    /**
    * test simple function call tag 2
    */
    public function testSimpleFunction2()
    {
        $tpl = $this->smarty->createTemplate('string:{function name=functest default=\'default\'}{$default} {$param}{/function}{functest param=\'param\'} {functest param=\'param2\'}');
        $this->assertEquals("default param default param2", $this->smarty->fetch($tpl));
    } 
    /**
    * test overwrite default function call tag 
    */
    public function testOverwriteDefaultFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{function name=functest default=\'default\'}{$default} {$param}{/function}{functest param=\'param\' default=\'overwrite\'} {functest param=\'param2\'}');
        $this->assertEquals("overwrite param default param2", $this->smarty->fetch($tpl));
    } 
    /**
    * test recursive function call tag 
    */
    public function testRecursiveFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{function name=functest loop=0}{$loop}{if $loop < 5}{functest loop=$loop+1}{/if}{/function}{functest}');
        $this->assertEquals("012345", $this->smarty->fetch($tpl));
    } 
    /**
    * test inherited function call tag 
    */
    public function testInheritedFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{function name=functest loop=0}{$loop}{if $loop < 5}{functest loop=$loop+1}{/if}{/function}{include file=\'test_inherit_function_tag.tpl\'}');
        $this->assertEquals("012345", $this->smarty->fetch($tpl));
    } 
    /**
    * test fuction definition in include 
    */
    public function testDefineFunctionInclude()
    {
        $tpl = $this->smarty->createTemplate('string:{include file=\'test_define_function_tag.tpl\'}{include file=\'test_inherit_function_tag.tpl\'}');
        $this->assertEquals("012345", $this->smarty->fetch($tpl));
    } 
} 

?>
