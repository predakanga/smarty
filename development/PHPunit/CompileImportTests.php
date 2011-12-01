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
    /**
    * test nocach section
    */
    public function testImportNocacheSection1()
    {
        $tpl = $this->smarty->createTemplate('test_import_nocache_section.tpl');
        $tpl->caching = 1;
        $tpl->assign('foo',1);
        $content = $this->smarty->fetch($tpl);
        $this->assertContains("foo = 1", $content);
    }
    public function testImportNocacheSection2()
    {
        $tpl = $this->smarty->createTemplate('test_import_nocache_section.tpl');
        $tpl->caching = 1;
        $tpl->assign('foo',2);
        $content = $this->smarty->fetch($tpl);
        $this->assertContains("foo = 2", $content);
    }
    /**
    * test nocache tag
    */
    public function testImportNocacheTag1()
    {
        $tpl = $this->smarty->createTemplate('test_import_nocache_tag.tpl');
        $tpl->caching = 1;
        $tpl->assign('foo',3);
        $content = $this->smarty->fetch($tpl);
        $this->assertContains("foo = 3", $content);
    }
    public function testImportNocacheTag2()
    {
        $tpl = $this->smarty->createTemplate('test_import_nocache_tag.tpl');
        $tpl->caching = 1;
        $tpl->assign('foo',4);
        $content = $this->smarty->fetch($tpl);
        $this->assertContains("foo = 4", $content);
    }
}
?>
