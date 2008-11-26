<?php
/**
* Smarty PHPunit tests compilation of assign tags
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for assign tags tests
*/
class CompileAssignTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test old style of assign tag
    */
    public function testAssignRequiredAttributeVar()
    {
        try {
            ob_start();
            $this->smarty->fetch('string:{assign value=1}');
        } 
        catch (Exception $e) {
            $this->assertContains('Error compiling template string', $e->getMessage());
            $this->assertContains('missing "var" attribute', ob_get_clean());
            return;
        } 
        $this->fail('Exception for required attribute "var" has not been raised.');
    } 


    public function testAssignOld1()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=1}{\$foo}");
        $this->assertEquals("1", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld2()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var='foo' value=1}{\$foo}");
        $this->assertEquals("1", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld3()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=\"foo\" value=1}{\$foo}");
        $this->assertEquals("1", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld4()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=bar}{\$foo}");
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld5()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=1+2}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld6()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=strlen('bar')}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld7()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value='bar'|strlen}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld8()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=(9,8,7,6)}{for \$x in \$foo}{\$x}{/for}");
        $this->assertEquals("9876", $this->smarty->fetch($tpl));
    } 
    public function testAssignOld9()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=('a'=>9,'b'=>8,'c'=>7,'d'=>6)}{for \$x in \$foo}{\$x:key}{\$x}{/for}");
        $this->assertEquals("a9b8c7d6", $this->smarty->fetch($tpl));
    } 
    /**
    * test new style of assign tag
    */
    public function testAssignNew1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=1}{\$foo}");
        $this->assertEquals("1", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=bar}{\$foo}");
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew3()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=1+2}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew4()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=strlen('bar')}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew5()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo='bar'|strlen}{\$foo}");
        $this->assertEquals("3", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew6()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=(9,8,7,6)}{for \$x in \$foo}{\$x}{/for}");
        $this->assertEquals("9876", $this->smarty->fetch($tpl));
    } 
    public function testAssignNew7()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=('a'=>9,'b'=>8,'c'=>7,'d'=>6)}{for \$x in \$foo}{\$x:key}{\$x}{/for}");
        $this->assertEquals("a9b8c7d6", $this->smarty->fetch($tpl));
    } 
} 

?>
