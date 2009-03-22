<?php
/**
* Smarty PHPunit tests append_by_ref methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for append_by_ref tests
*/
class AppendByRefTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test append_by_ref
    */
    public function testAppendByRef()
    {
        $bar = 'bar';
        $bar2 = 'bar2';
        $this->smarty->append_by_ref('foo', $bar);
        $this->smarty->append_by_ref('foo', $bar2);
        $bar = 'newbar';
        $bar2 = 'newbar2';
        $this->assertEquals('newbar newbar2', $this->smarty->fetch('string:{$foo[0]} {$foo[1]}'));
    } 
    /**
    * test append_by_ref to unassigned variable
    */
    public function testAppendByRefUnassigned()
    {
        $bar2 = 'bar2';
        $this->smarty->append_by_ref('foo', $bar2);
        $bar2 = 'newbar2';
        $this->assertEquals('newbar2', $this->smarty->fetch('string:{$foo[0]}'));
    } 
    /**
    * test append_by_ref merge
    * 
    * @todo fix testAppendByRefMerge
    */
    public function testAppendByRefMerge()
    {
        /*
        $bar = array('b' => 'd');
        $this->smarty->assign('foo', array('a' => 'a', 'b' => 'b', 'c' => 'c'));
        $this->smarty->append_by_ref('foo', $bar, true);
        $this->assertEquals('a d c', $this->smarty->fetch('string:{$foo["a"]} {$foo["b"]} {$foo["c"]}'));
        $bar = array('b' => 'newd');
        $this->assertEquals('a newd c', $this->smarty->fetch('string:{$foo["a"]} {$foo["b"]} {$foo["c"]}'));
*/
    } 
} 

?>
