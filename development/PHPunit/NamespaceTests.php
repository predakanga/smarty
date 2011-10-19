<?php
/**
* Smarty PHPunit tests static class access to constants, variables and methodes
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once dirname(__FILE__) .'/helpers/namespaced_class.php';

/**
* class for static class access to constants, variables and methodes tests
*/
class NamespaceTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->disableSecurity();
    } 

    public static function isRunnable()
    {
        return true;
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
        $tpl->assign('object', new \foo\bar\Baz());
        $this->assertEquals("yeah!", $tpl->fetch());
        
        $tpl = $this->smarty->createTemplate('eval:{if $object instanceof \'\foo\bar\Baz\'}yeah!{/if}');
        $tpl->assign('object', new \foo\bar\Baz());
        $this->assertEquals("yeah!", $tpl->fetch());
    }
} 

class mystaticclass {
    const STATIC_CONSTANT_VALUE = 3;
    public static $static_var = 5;
    
    static function square($i)
    {
        return $i*$i;
    } 
} 

?>