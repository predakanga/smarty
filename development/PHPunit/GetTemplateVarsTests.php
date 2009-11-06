<?php
/**
* Smarty PHPunit tests get_template_vars method
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for get_template_vars method test
*/
class GetTemplateVarsTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test root get_template_vars single value
    */
    public function testGetSingleTemplateVarScopeRoot()
    {
        $this->smarty->assign('foo', 'bar');
        $this->smarty->assign('blar', 'buh');
        $this->assertEquals("bar", $this->smarty->get_template_vars('foo'));
    } 
    /**
    * test root get_template_vars all values
    */
    public function testGetAllTemplateVarsScopeRoot()
    {
        $this->smarty->assign('foo', 'bar');
        $this->smarty->assign('blar', 'buh');
        $vars = $this->smarty->get_template_vars();
        $this->assertTrue(is_array($vars));
        $this->assertEquals("bar", $vars['foo']);
        $this->assertEquals("buh", $vars['blar']);
    } 

    /**
    * test single variable with data object chain
    */
    public function testGetSingleTemplateVarScopeAll()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data2 = new Smarty_Data($data1);
        $this->smarty->assign('foo', 'bar');
        $this->smarty->assign('blar', 'buh');
        $this->assertEquals("bar", $this->smarty->get_template_vars('foo', $data2));
    } 
    /**
    * test get all variables with data object chain
    */
    public function testGetAllTemplateVarsScopeAll()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data2 = new Smarty_Data($data1);
        $this->smarty->assign('foo', 'bar');
        $data1->assign('blar', 'buh');
        $data2->assign('foo2', 'bar2');
        $vars = $this->smarty->get_template_vars(null, $data2);
        $this->assertTrue(is_array($vars));
        $this->assertEquals("bar", $vars['foo']);
        $this->assertEquals("bar2", $vars['foo2']);
        $this->assertEquals("buh", $vars['blar']);
    } 
    /**
    * test get all variables with data object chain search parents disabled
    */
    public function testGetAllTemplateVarsScopeAllNoParents()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data2 = new Smarty_Data($data1);
        $this->smarty->assign('foo', 'bar');
        $data1->assign('blar', 'buh');
        $data2->assign('foo2', 'bar2');
        $vars = $this->smarty->get_template_vars(null, $data2, false);
        $this->assertTrue(is_array($vars));
        $this->assertFalse(isset($vars['foo']));
        $this->assertEquals("bar2", $vars['foo2']);
        $this->assertFalse(isset($vars['blar']));
    } 
    /**
    * test get single variables with data object chain search parents disabled
    */
    public function testGetSingleTemplateVarsScopeAllNoParents()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data2 = new Smarty_Data($data1);
        $this->smarty->assign('foo', 'bar');
        $data1->assign('blar', 'buh');
        $data2->assign('foo2', 'bar2');
        $this->assertEquals("", $this->smarty->get_template_vars('foo', $data2, false));
        $this->assertEquals("bar2", $this->smarty->get_template_vars('foo2', $data2, false));
        $this->assertEquals("", $this->smarty->get_template_vars('blar', $data2, false));
    } 
} 

?>
