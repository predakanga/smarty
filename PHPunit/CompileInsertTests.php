<?php
/**
* Smarty PHPunit tests compilation of the {insert} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {insert} tests
*/
class CompileInsertTests extends PHPUnit_Framework_TestCase {
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
    * test inserted function
    */
    public function testInsertFunction()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='test' foo='bar'} end");
        $this->assertEquals("start insert function parameter value bar end", $this->smarty->fetch($tpl));
    } 
    /**
    * test inserted function with assign
    */
    public function testInsertFunctionAssign()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='test' foo='bar' assign=blar} end {\$blar}");
        $this->assertEquals("start  end insert function parameter value bar", $this->smarty->fetch($tpl));
    } 

    /**
    * test inserted function none existing function
    */
    public function testInsertFunctionNoneExistingFunction()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='mustfail' foo='bar' assign=blar} end {\$blar}");
        try {
            $this->smarty->fetch($tpl);
        } 
        catch (Exception $e) {
            $this->assertContains('function "insert_mustfail" is not callable', $e->getMessage());
            return;
        } 
        $this->fail('Exception for "function is not callable" has not been raised.');
    } 
    /**
    * test inserted function none existing function
    */
    public function testInsertFunctionNoneExistingScript()
    {
        $tpl = $this->smarty->createTemplate("string:{insert name='mustfail' foo='bar' script='nofile.php'}");
        try {
            $this->smarty->fetch($tpl);
        } 
        catch (Exception $e) {
            $this->assertContains('missing file', $e->getMessage());
            return;
        } 
        $this->fail('Exception for "missing file" has not been raised.');
    } 
} 

/**
* test function
*/
function insert_test($params)
{
    return "insert function parameter value $params[foo]";
} 

?>
