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
    public function testPhpSmartyTagAllowed()
    {
        $this->smarty->allow_php_tag = true;
        $this->smarty->security = false;
        $tpl = $this->smarty->createTemplate("string:{php}echo 'hello world'; {/php}");
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 
    public function testPhpSmartyTagNotAllowed()
    {
        try {
            $this->smarty->fetch("string:{php}echo 'hello world'; {/php}");
        } 
        catch (Exception $e) {
            $this->assertContains('{php} is deprecated', $e->getMessage());
            return;
        } 
        $this->fail('Warning {php} has not been raised.');
    } 
    /**
    * test <?php...\> tag
    * default is PASSTHRU
    */
    public function testPhpTag()
    {
        $tpl = $this->smarty->createTemplate("string:<?php echo 'hello world'; ?>");
        $content = $this->smarty->fetch($tpl);
        $this->assertEquals("&lt;?php echo &#039;hello world&#039;; ?&gt;", $content);
    } 
    // ALLOW
    public function testPhpTagAllow()
    {
        $this->smarty->php_handling = SMARTY_PHP_ALLOW;
        $this->smarty->security = false;
        $tpl = $this->smarty->createTemplate("string:<?php echo 'hello world'; ?>");
        $content = $this->smarty->fetch($tpl);
        $this->assertEquals('hello world', $content);
    } 
    /**
    * test <?=...\> shorttag
    * default is PASSTHRU
    */
    public function testShortTag()
    {
        $this->smarty->assign('foo', 'bar');
        $content = $this->smarty->fetch('string:<?=$foo?>');
        $this->assertEquals('<?=$foo?>', $content);
    } 
} 

?>
