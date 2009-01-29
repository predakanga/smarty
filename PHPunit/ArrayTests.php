<?php
/**
* Smarty PHPunit tests array definitions and access
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for array tests
*/
class ArrayTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->force_compile = true;
        $this->smarty->enableSecurity();
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
    * test simple array definition
    */
    public function testSimpleArrayDefinition()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=[1,2,3,4,5]}{for $bar in $foo}{$bar}{/for}', $this->smarty);
        $this->assertEquals('12345', $this->smarty->fetch($tpl));
    } 
    /**
    * test smarty2 array access
    */
    public function testSmarty2ArrayAccess()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=[1,2,3,4,5]}{$foo.0}{$foo.1}{$foo.2}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
    /**
    * test smarty3 array access
    */
    public function testSmarty3ArrayAccess()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=[1,2,3,4,5]}{$foo[0]}{$foo[1]}{$foo[2]}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
    /**
    * test indexed array definition
    */
    public function testIndexedArrayDefinition()
    {
        $tpl = $this->smarty->createTemplate('string:{$x=\'d\'}{$foo=[a=>1,\'b\'=>2,"c"=>3,$x=>4]}{$foo[\'a\']}{$foo[\'b\']}{$foo[\'c\']}{$foo[\'d\']}', $this->smarty);
        $this->assertEquals('1234', $this->smarty->fetch($tpl));
    } 
    /**
    * test nested array
    */
    public function testNestedArray()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=[1,2,[a,b,c],4,5]}{$foo[2][1]}', $this->smarty);
        $this->assertEquals('b', $this->smarty->fetch($tpl));
    } 
} 

?>
