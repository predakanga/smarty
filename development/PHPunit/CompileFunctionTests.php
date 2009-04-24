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
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
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
} 

?>
