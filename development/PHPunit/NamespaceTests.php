<?php
/**
* Smarty PHPunit tests static class access to constants, variables and methodes
*
* @package PHPunit
* @author Uwe Tews
*/

/**
* class for static class access to constants, variables and methodes tests
*/
class NamespaceTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        require_once dirname(__FILE__) .'/helpers/namespaced_class.php';

        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->disableSecurity();
    }

    public static function isRunnable()
    {
        return version_compare(PHP_VERSION, '5.3.0') >= 0;
    }

    public function testConstant()
    {
        $tpl = $this->smarty->createTemplate('eval:{\foo\bar\FOO}');
        $this->assertEquals("CONSTANT", $tpl->fetch());
    }

    public function testFunction()
    {
        $tpl = $this->smarty->createTemplate('eval:{$t = \foo\bar\foo()}{$t}');
        $this->assertEquals("FUNCTION", $tpl->fetch());
    }

    public function testClassConstant()
    {
        $tpl = $this->smarty->createTemplate('eval:{\foo\bar\Baz::FOO}');
        $this->assertEquals("CONSTANT", $tpl->fetch());
    }

    public function testClassStatic()
    {
        $tpl = $this->smarty->createTemplate('eval:{\foo\bar\Baz::$FOO}');
        $this->assertEquals("STATIC", $tpl->fetch());
    }

    public function testClassFunction()
    {
        $tpl = $this->smarty->createTemplate('eval:{\foo\bar\Baz::foo()}');
        $this->assertEquals("FUNCTION", $tpl->fetch());
    }

    public function testInstanceof()
    {
        $tpl = $this->smarty->createTemplate('eval:{if $object instanceof \foo\bar\Baz}yeah!{/if}');
        $class = '\foo\bar\Baz';
        $tpl->assign('object', new $class());
        $this->assertEquals("yeah!", $tpl->fetch());

        $tpl = $this->smarty->createTemplate('eval:{if $object instanceof \'\foo\bar\Baz\'}yeah!{/if}');
        $class = '\foo\bar\Baz';
        $tpl->assign('object', new $class());
        $this->assertEquals("yeah!", $tpl->fetch());
    }
}

?>