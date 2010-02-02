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
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test inserted function
    */
    public function testInsertFunctionSingle()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='test' foo='bar'} end");
        $this->assertEquals("start insert function parameter value bar end", $this->smarty->fetch($tpl));
    } 
    public function testInsertFunctionDouble()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name=\"test\" foo='bar'} end");
        $this->assertEquals("start insert function parameter value bar end", $this->smarty->fetch($tpl));
    } 
    public function testInsertFunctionVariableName()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name=\$variable foo='bar'} end");
        $tpl->assign('variable','test');
        $this->assertEquals("start insert function parameter value bar end", $this->smarty->fetch($tpl));
    } 
    public function testInsertPlugin()
    {
        $tpl = $this->smarty->createTemplate("string:start {insert name='plugintest' foo='bar'} end");
        $this->assertEquals("start from insert plugin bar end", $this->smarty->fetch($tpl));
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
            $this->assertContains("{insert} no function or plugin found for 'mustfail'", $e->getMessage());
            return;
        } 
        $this->fail('Exception for "function is not callable" has not been raised.');
    } 
    /**
    * test inserted function none existing script
    */
    public function testInsertFunctionNoneExistingScript()
    {
        $tpl = $this->smarty->createTemplate("string:{insert name='mustfail' foo='bar' script='nofile.php'}");
        try {
            $this->smarty->fetch($tpl);
        } 
        catch (Exception $e) {
            $this->assertContains('missing script file', $e->getMessage());
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
