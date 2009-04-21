<?php
/**
* Smarty PHPunit tests array definitions and access
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for array tests
*/
class ArrayTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->force_compile = true;
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
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
        $tpl = $this->smarty->createTemplate('string:{$foo=[1,2,3,4,5]}{foreach $foo as $bar}{$bar}{/foreach}', $this->smarty);
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
