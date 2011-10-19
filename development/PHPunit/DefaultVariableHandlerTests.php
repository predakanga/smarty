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
class DefaultVariableHandlerTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    public function testVariable()
    {
        $this->smarty->registerDefaultVariableHandler('DefaultVariableHandlerTests_value');
        $tpl = $this->smarty->createTemplate('eval:{$foo}');
        $this->assertEquals('foo-Smarty_Internal_Template', $tpl->fetch());
        $this->assertEquals('blurp-Smarty_Internal_Template', $tpl->getTemplateVars('blurp'));
        $this->assertEquals('baz-Smarty', $this->smarty->getTemplateVars('baz'));
    }
    
    public function testVariableFailIgnore()
    {
        /*
        const UNASSIGNED_IGNORE = 0;
        const UNASSIGNED_NOTICE = 1;
        const UNASSIGNED_EXCEPTION = 2;
        */
        $this->smarty->error_unassigned = Smarty::UNASSIGNED_IGNORE;
        $this->smarty->registerDefaultVariableHandler('DefaultVariableHandlerTests_null');
        $tpl = $this->smarty->createTemplate('eval:{$foo}');
        $this->assertEquals('', $tpl->fetch());
    }
    
    protected $_errors = array();
    public function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->_errors[] = $errstr;
    }
    
    public function testVariableFailNotice()
    {
        $this->_errors = array();
        set_error_handler(array($this, 'error_handler'));
        
        $this->smarty->error_unassigned = Smarty::UNASSIGNED_NOTICE;
        $this->smarty->registerDefaultVariableHandler('DefaultVariableHandlerTests_null');
        $tpl = $this->smarty->createTemplate('eval:{$foo}');
        $this->assertEquals('', $tpl->fetch());
        
        $this->assertEquals(1, count($this->_errors));
        $this->assertEquals("Unassigned template variable 'foo'", $this->_errors[0]);
        
        restore_error_handler();
    }
    
    public function testVariableFailException()
    {
        $this->smarty->error_unassigned = Smarty::UNASSIGNED_EXCEPTION;
        $this->smarty->registerDefaultVariableHandler('DefaultVariableHandlerTests_null');
        $tpl = $this->smarty->createTemplate('eval:{$foo}');
        try {
            $tpl->fetch();
        } catch(SmartyException $e) {
            $this->assertEquals("Unassigned template variable 'foo'", $e->getMessage());
            return;
        }
        
        $this->fail('Exception not thrown');
    }
} 

function DefaultVariableHandlerTests_value($name, &$value, $context)
{
    $value = $name .'-'. get_class($context);
    return true;
}

function DefaultVariableHandlerTests_null($name, &$value, $context)
{
    return false; // not found
}

?>
