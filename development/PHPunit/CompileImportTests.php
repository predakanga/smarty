<?php
/**
* Smarty PHPunit tests compilation of the {import} tag
*
* @package PHPunit
* @author Uwe Tews
*/


/**
* class for {import} tests
*/
class CompileIMportTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
    }

    public static function isRunnable()
    {
        return true;
    }

    /**
    * test standard output
    */
    public function testImportStandard()
    {
        $tpl = $this->smarty->createTemplate('test_import_main.tpl');
        $content = $this->smarty->fetch($tpl);
        $this->assertContains("hello world", $content);
        $this->assertContains("foo = assigned", $content);
    }
}
?>
