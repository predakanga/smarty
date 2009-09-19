<?php
/**
* Smarty PHPunit tests compilation of the {insert} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for {insert} tests
*/
class CompileInsertTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
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
    * test inserted function with assign no output
    */
    public function testInsertFunctionAssignNoOutput()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='test' foo='bar' assign=blar} end");
        $this->assertEquals("start  end", $this->smarty->fetch($tpl));
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
