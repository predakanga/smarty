<?php
/**
* Smarty PHPunit tests compilation of {php} and <?php...?> tag
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for {php} and <?php...?> tag tests
*/
class CompilePhpTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->security = false;
    } 

    public static function isRunnable()
    {
        return true;
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
