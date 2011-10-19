<?php
/**
* Smarty PHPunit tests variable variables
* 
* @package PHPunit
* @author Rodney Rehm
*/


/**
* class for variable variables tests
*/
class VariableCachevalueTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 
    
    public function testRegularNocaching()
    {
        $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var}{nocache}{$v}{/nocache}{/foreach}');
        $this->assertEquals('12345', $tpl->fetch());
    }
    
    public function testRegularCaching()
    {
        $this->smarty->clearAllCache();
        $this->smarty->caching = true;
        $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var}{nocache}{$v}{/nocache}{/foreach}');
        $this->assertEquals('55555', $tpl->fetch());
    }
    
    public function testRegularCaching2()
    {
        // execute the cached file!
        $this->smarty->error_unassigned = Smarty::UNASSIGNED_EXCEPTION;
        $this->smarty->caching = true;
        $this->smarty->template_objects = array();
        Smarty_Resource::$sources = array();
        try {
            $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var}{nocache}{$v}{/nocache}{/foreach}');
            $tpl->fetch();
        } catch (SmartyException $e) {
            $this->assertEquals("Unassigned template variable 'v'", $e->getMessage());
            return;
        }
        
        $this->fail("Exception not thrown");
    }
    
    public function testNocaching()
    {
        $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var cachevalue}{nocache}{$v}{/nocache}{/foreach}');
        $this->assertEquals('12345', $tpl->fetch());
    }
    
    public function testCaching()
    {
        $this->smarty->clearAllCache();
        $this->smarty->caching = true;
        $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var cachevalue}{nocache}{$v}{/nocache}{/foreach}');
        $this->assertEquals('12345', $tpl->fetch());
    }
    
    public function testCaching2()
    {
        // execute the cached file!
        $this->smarty->caching = true;
        $this->smarty->template_objects = array();
        Smarty_Resource::$sources = array();
        $tpl = $this->smarty->createTemplate('string:{$vars = [1,2,3,4,5]}{foreach $vars as $var}{$v = $var cachevalue}{nocache}{$v}{/nocache}{/foreach}');
        $this->assertEquals('12345', $tpl->fetch());
    }
}
