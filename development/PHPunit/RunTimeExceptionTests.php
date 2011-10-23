<?php
/**
* Smarty PHPunit tests for runtime exceptions
*
* @package PHPunit
* @author Rodney Rehm
*/


/**
* class for runtime exceptions tests
*/
class RunTimeExceptionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    }

    public static function isRunnable()
    {
        return true;
    }

    /*
    * test run time exceptions
    */
    public function testException()
    {
        $this->smarty->assign('foo', true);
        try {
            $this->smarty->fetch('test_runtime_exception.tpl');
        } catch(SmartyRunTimeException $e) {
            $this->assertContains('My Exception', $e->getMessage());
            return;
        }
    }

    /*
    * create cache file for the following test
    */
    public function testExceptionCreateCache()
    {
        $this->smarty->assign('foo', false);
        $this->smarty->caching = 1;
        $this->smarty->fetch('test_runtime_exception.tpl');
    }

    /*
    * test run time exceptions from cache
    */
    public function testExceptionFromCache()
    {
        $this->smarty->assign('foo', true);
        $this->smarty->caching = 1;
        try {
            $this->smarty->fetch('test_runtime_exception.tpl');
        } catch(SmartyRunTimeException $e) {
            $this->assertContains('My Exception', $e->getMessage());
            return;
        }
    }

}

