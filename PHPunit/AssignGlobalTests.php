<?php
/**
* Smarty PHPunit tests assign_global methode  and {assign_global} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for assign_global methode  and {assign_global} tag tests
*/
class AssignGlobalTests extends PHPUnit_Framework_TestCase {

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
    * test  assign_global and get_global
    */
    public function testAssignGlobalGetGlobal()
    {
            $this->smarty->assign_global('foo','bar');
		$this->assertEquals('bar', $this->smarty->get_global('foo'));
    } 
    /**
    * test  assign_global and get_global on arrays
    */
    public function testAssignGlobalGetGlobalArray()
    {
            $this->smarty->assign_global('foo',array('foo'=>'bar','foo2'=>'bar2'));
            $a1 = array('foo'=>array('foo'=>'bar','foo2'=>'bar2'));
            $a2 = $this->smarty->get_global();
            $diff = array_diff($a1,$a2);
            $cmp = empty($diff);
		$this->assertTrue($cmp);
    } 
    /**
    * test assign_global tag
    */
    public function testAssignGlobalTag()
    {
            $this->smarty->assign_global('foo','bar');
		$this->assertEquals('bar', $this->smarty->fetch('string:{$smarty.global.foo}'));
		$this->assertEquals('blar', $this->smarty->fetch('string:{assign_global var=foo value=blar}{$smarty.global.foo}'));
		$this->assertEquals('blar', $this->smarty->fetch('string:{$smarty.global.foo}'));
		$this->assertEquals('blar', $this->smarty->get_global('foo'));
    } 
    /**
    * test global var array element tag
    */
    public function testGlobalVarArrayTag()
    {
            $this->smarty->assign_global('foo',array('foo'=>'bar','foo2'=>'bar2'));
		$this->assertEquals('bar', $this->smarty->fetch('string:{$smarty.global.foo.foo2}'));
		$this->assertEquals('bar2', $this->smarty->fetch('string:{$smarty.global.foo.foo}'));
    } 
} 
?>
