<?php
/**
* Smarty PHPunit tests clearing all assigned variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for clearing all assigned variables tests
*/
class ClearAllAssignTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = Smarty::instance();
        SmartyTests::init();
        $this->smarty->assign('foo','foo');
        $this->smarty->data = new Smarty_Data($this->smarty);
        $this->smarty->data->assign('bar','bar');
        $this->smarty->tpl = $this->smarty->createTemplate('string:{$foo}{$bar}{$blar}',$this->smarty->data);
        $this->smarty->tpl->assign('blar','blar');
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test all variables accessable
    */
    public function testAllVariablesAccessable()
    {
		$this->assertEquals('foobarblar', $this->smarty->fetch($this->smarty->tpl));
    } 

    /**
    * test clear all assign in template
    */
    public function testClearAllAssignInTemplate()
    {
            $this->smarty->tpl->clear_all_assign();
		$this->assertEquals('foobar', $this->smarty->fetch($this->smarty->tpl));
    } 
    /**
    * test clear all assign in data
    */
    public function testClearAllAssignInData()
    {
            $this->smarty->data->clear_all_assign();
		$this->assertEquals('fooblar', $this->smarty->fetch($this->smarty->tpl));
    } 
    /**
    * test clear all assign in Smarty object
    */
    public function testClearAllAssignInSmarty()
    {
            $this->smarty->clear_all_assign();
		$this->assertEquals('barblar', $this->smarty->fetch($this->smarty->tpl));
    } 
} 

?>
