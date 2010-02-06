<?php
/**
* Smarty PHPunit tests of filter
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for filter tests
*/
class FilterTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
//        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test autoload filter
    */
    public function testAutoloadOutputFilter()
    {
        $this->smarty->autoloadFilters['output'] = 'trimwhitespace';
        $tpl = $this->smarty->createTemplate('string:{"    <br>hello world"}');
        $this->assertEquals("<br>hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test loaded filter
    */
    public function testLoadedOutputFilter()
    {
        $this->smarty->loadFilter('output', 'trimwhitespace');
        $tpl = $this->smarty->createTemplate('string:{"    <br>hello world"}');
        $this->assertEquals("<br>hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered output filter
    */
    public function testRegisteredOutputFilter()
    {
        function myoutputfilter($input)
        {
            return str_replace('   ', ' ', $input);
        } 
        $this->smarty->register->outputFilter('myoutputfilter');
        $tpl = $this->smarty->createTemplate('string:{"hello   world"}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered pre filter
    */
    public function testRegisteredPreFilter()
    {
        function myprefilter($input)
        {
            return '{$foo}' . $input;
        } 
        $this->smarty->register->preFilter('myprefilter');
        $tpl = $this->smarty->createTemplate('string:{" hello world"}');
        $tpl->assign('foo', 'bar');
        $this->assertEquals("bar hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered pre filter class
    */
    public function testRegisteredPreFilterClass()
    {
        $this->smarty->register->preFilter(array('myprefilterclass', 'myprefilter'));
        $tpl = $this->smarty->createTemplate('string:{" hello world"}');
        $tpl->assign('foo', 'bar');
        $this->assertEquals("bar hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered post filter
    */
    public function testRegisteredPostFilter()
    {
        function mypostfilter($input)
        {
            return '{$foo}' . $input;
        } 
        $this->smarty->register->postFilter('mypostfilter');
        $tpl = $this->smarty->createTemplate('string:{" hello world"}');
        $tpl->assign('foo', 'bar');
        $this->assertEquals('{$foo} hello world', $this->smarty->fetch($tpl));
    } 
} 
class myprefilterclass {
    static function myprefilter($input)
    {
        return '{$foo}' . $input;
    } 
} 

?>
