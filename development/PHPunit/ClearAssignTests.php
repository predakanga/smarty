<?php
/**
* Smarty PHPunit tests clearing assigned variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for clearing assigned variables tests
*/
class ClearAssignTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->assign('foo','foo');
        $this->smarty->assign('bar','bar');
        $this->smarty->assign('blar','blar');
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
		$this->assertEquals('foobarblar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 

    /**
    * test simple clear assign
    */
    public function testClearAssign()
    {
            $this->smarty->clear_assign('blar');
		$this->assertEquals('foobar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 
    /**
    * test clear assign array of variables
    */
    public function testArrayClearAssign()
    {
            $this->smarty->clear_assign(array('blar','foo'));
		$this->assertEquals('bar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 
} 
?>
