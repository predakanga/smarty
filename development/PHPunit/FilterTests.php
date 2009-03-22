<?php
/**
* Smarty PHPunit tests of filter
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for filter tests
*/
class FilterTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test autoload filter
    */
    public function testAutoloadOutputFilter()
    {
        $this->smarty->autoload_filters['output'] = 'trimwhitespace';
        $tpl = $this->smarty->createTemplate('string:{"    <br>hello world"}');
        $this->assertEquals("<br>hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test loaded filter
    */
    public function testLoadedOutputFilter()
    {
        $this->smarty->load_filter('output', 'trimwhitespace');
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
        $this->smarty->register_outputfilter('myoutputfilter');
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
            return '{$foo}'.$input;
        } 
        $this->smarty->register_prefilter('myprefilter');
        $tpl = $this->smarty->createTemplate('string:{" hello world"}');
        $tpl->assign('foo','bar');
        $this->assertEquals("bar hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered post filter
    */
    public function testRegisteredPostFilter()
    {
        function mypostfilter($input)
        {
            return '{$foo}'.$input;
        } 
        $this->smarty->register_postfilter('mypostfilter');
        $tpl = $this->smarty->createTemplate('string:{" hello world"}');
        $tpl->assign('foo','bar');
        $this->assertEquals('{$foo} hello world', $this->smarty->fetch($tpl));
    } 
} 

?>
