<?php
/**
* Smarty PHPunit tests comments in templates
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for security test
*/
class CommentsTests extends PHPUnit_Framework_TestCase {

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
    * test simple comments
    */
    /**
*    public function testSimpleComment1()
*   {
*        $tpl = $this->smarty->createTemplate("string:{* this is a comment *}");
*        $this->assertEquals("", $this->smarty->fetch($tpl));
*        $this->assertContains('<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
*    } 
    public function testSimpleComment2()
    {
        $tpl = $this->smarty->createTemplate("string:{* another \$foo comment *}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
        $this->assertContains('<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
    } 
    public function testSimpleComment3()
    {
        $tpl = $this->smarty->createTemplate("string:{* another  comment *}some in between{* another  comment *}");
        $this->assertEquals("some in between", $this->smarty->fetch($tpl));
        $this->assertContains('<?php /* comment placeholder * /?>some in between<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
    } 
    public function testSimpleComment4()
    {
        $tpl = $this->smarty->createTemplate("string:{* multi line \n comment *}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
        $this->assertContains('<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
    } 
    public function testSimpleComment5()
    {
        $tpl = $this->smarty->createTemplate("string:{* /* foo * / *}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
        $this->assertContains('<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
    } 
    public function testSimpleComment6 ()
    {
        $this->smarty->comment_mode = 0;
        $tpl = $this->smarty->createTemplate("string:{* multi line \n comment *}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
        $this->assertNotContains('<?php /* comment placeholder * /?>', $tpl->getCompiledTemplate());
    } 
    public function testSimpleComment7 ()
    {
        $this->smarty->comment_mode = 2;
        $tpl = $this->smarty->createTemplate("string:{* multi line \n comment *}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
        $this->assertContains('multi line', $tpl->getCompiledTemplate());
    } 
 */
    /**
    * test comment text combinations
    */
    public function testTextComment1()
    {
        $tpl = $this->smarty->createTemplate("string:A{* comment *}B\nC");
        $this->assertEquals("AB\nC", $this->smarty->fetch($tpl));
    } 
    public function testTextComment2()
    {
        $tpl = $this->smarty->createTemplate("string:D{* comment *}\n{* comment *}E\nF");
        $this->assertEquals("D\nE\nF", $this->smarty->fetch($tpl));
    } 
    public function testTextComment3()
    {
        $tpl = $this->smarty->createTemplate("string:G{* multi \nline *}H");
        $this->assertEquals("GH", $this->smarty->fetch($tpl));
    } 
    public function testTextComment4()
    {
        $tpl = $this->smarty->createTemplate("string:I{* multi \nline *}\nJ");
        $this->assertEquals("I\nJ", $this->smarty->fetch($tpl));
    } 
} 

?>
