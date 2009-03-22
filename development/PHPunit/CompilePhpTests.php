<?php
/**
* Smarty PHPunit tests compilation of {php} and <?php...?> tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for {php} and <?php...?> tag tests
*/
class CompilePhpTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
//        $this->markTestSkipped('php tests are skiped as the tags are disabled.');
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test {php} tag
    */
    public function testPhpSmartyTag()
    {
        $tpl = $this->smarty->createTemplate("string:{php}echo 'hello world'; {/php}");
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 
    /**
    * test <?php...\> tag
    */
    public function testPhpTag()
    {
        $tpl = $this->smarty->createTemplate("string:<?php echo 'hello world'; ?>");
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 
    /**
    * test <?=...\> shorttag
    */
    public function testShortTag()
    {
        $this->smarty->assign('foo','bar');
        $this->assertEquals('bar', $this->smarty->fetch('string:<?=$foo?>'));
    } 
} 

?>
