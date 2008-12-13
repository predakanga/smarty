<?php
/**
* Smarty PHPunit tests clearing assigned variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for clearing assigned variables tests
*/
class ClearAssignTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->assign('foo','foo');
        $this->smarty->data = new Smarty_Data($this->smarty);
        $this->smarty->data->assign('bar','bar');
        $this->smarty->tpl = $this->smarty->createTemplate('string:{$foo}{$bar}{$blar}',$this->smarty->data);
        $this->smarty->tpl->assign('blar','blar');
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test all variables accessable
    */
    public function testAllVariablesAccessable()
    {
		$this->assertEquals('foobarblar', $this->smarty->fetch($this->smarty->tpl));
    } 

    /**
    * test clear assign in template
    */
    public function testClearAssignInTemplate()
    {
            $this->smarty->clear_assign('blar',$this->smarty->tpl);
		$this->assertEquals('foobar', $this->smarty->fetch($this->smarty->tpl));
    } 
    /**
    * test clear assign in data
    */
    public function testClearAssignInData()
    {
            $this->smarty->clear_assign('bar',$this->smarty->tpl);
		$this->assertEquals('fooblar', $this->smarty->fetch($this->smarty->tpl));
    } 
    /**
    * test clear assign in Smarty object
    */
    public function testClearAssignInSmarty1()
    {
            $this->smarty->clear_assign('foo',$this->smarty->tpl);
		$this->assertEquals('barblar', $this->smarty->fetch($this->smarty->tpl));
    } 
    public function testClearAssignInSmarty2()
    {
            $this->smarty->clear_assign('foo');
		$this->assertEquals('barblar', $this->smarty->fetch($this->smarty->tpl));
    } 
    /**
    * test clear assign variable array
    */
    public function testClearAssignVariableArray()
    {
            $this->smarty->clear_assign(array('foo','bar','blar'),$this->smarty->tpl);
		$this->assertEquals('', $this->smarty->fetch($this->smarty->tpl));
    } 
} 

?>
