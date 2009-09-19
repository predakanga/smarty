<?php
/**
* Smarty PHPunit tests append_by_ref methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for append_by_ref tests
*/
class AppendByRefTests extends PHPUnit_Framework_TestCase {
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
