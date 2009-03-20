<?php
/**
* Smarty PHPunit tests compilation of registered object functions
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for registered object function tests
*/
class CompileRegisteredObjectFunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
        $this->object = new RegObject;
        $this->smarty->register_object('test', $this->object, 'myhello', false, 'myblock');
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test resgistered object as function
    */
    public function testRegisteredObjectFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{test->myhello}');
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 

    /**
    * test resgistered object as block function
    */
    public function testRegisteredObjectBlockFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{test->myblock}hello world{/test->myblock}');
        $this->assertEquals('block test', $this->smarty->fetch($tpl));
    } 
} 

Class RegObject {
    function myhello($params)
    {
        return 'hello world';
    } 
    function myblock($params, $content, &$smarty_tpl, &$repeat)
    {
        if (!$repeat) {
            $output = str_replace('hello world', 'block test', $content);
            return $output;
        } 
    } 
} 

?>
