<?php
/**
* Smarty PHPunit tests for Block Extend
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for block extend compiler tests
*/
class CompileBlockExtendTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

        /**
    * test block default outout
    */
    public function testBlockDefault1()
    {
        $result = $this->smarty->fetch('string:{block name=test}-- block default --{/block name=test}');
        $this->assertEquals('-- block default --', $result);
    } 

     public function testBlockDefault2()
    {
        $result = $this->smarty->fetch('string:{block name=test}-- block default --{/block}');
        $this->assertEquals('-- block default --', $result);
    } 

     public function testBlockDefault3()
    {
        $this->smarty->assign ('foo', 'another');
        $result = $this->smarty->fetch('string:{block name=test}-- {$foo} block default --{/block}');
        $this->assertEquals('-- another block default --', $result);
    } 
    public function testUnmatchedNameError()
    {
        try {
            $this->smarty->fetch('string:{block name=test}-- block default --{/block name=none}');
        } 
        catch (Exception $e) {
            $this->assertContains('mismatching name attributes', $e->getMessage());
            return;
        } 
        $this->fail('Exception for not matching name attributes has not been raised.');
    } 
    /**
    * test just call of  base template, no blocks predefined
    */
    public function testCompileBlockBase()
    {
        $result = $this->smarty->fetch('test_block_base.tpl');
        $this->assertContains('-- default title --', $result);
        $this->assertContains('-- default headline --', $result);
        $this->assertContains('-- default description --', $result);
    } 
    public function testCompileBlockBase1()
    {
        $result = $this->smarty->fetch('test_block_section.tpl');
        $this->assertContains('-- default title --', $result);
        $this->assertContains('-- headline from test_block_section.tlp --', $result);
        $this->assertContains('-- default description --', $result);
    } 
    public function testCompileBlockBase2()
    {
        $this->smarty->assign ('foo', 'this is foo text');
        $result = $this->smarty->fetch('test_block.tpl');
        $this->assertContains('-- My titel --', $result);
        $this->assertContains('-- Yes we can --', $result);
        $this->assertContains(' assigned description this is foo text ', $result);
    } 
} 

?>
