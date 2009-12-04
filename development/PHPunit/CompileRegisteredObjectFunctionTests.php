<?php
/**
* Smarty PHPunit tests compilation of registered object functions
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for registered object function tests
*/
class CompileRegisteredObjectFunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
        $this->object = new RegObject;
        $this->smarty->register_object('objecttest', $this->object, 'myhello', false, 'myblock');
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test resgistered object as function
    */
    public function testRegisteredObjectFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{objecttest->myhello}');
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 
    /**
    * test resgistered object as function with modifier
    */
    public function testRegisteredObjectFunctionModifier()
    {
        $tpl = $this->smarty->createTemplate('string:{objecttest->myhello|truncate:6}');
        $this->assertEquals('hel...', $this->smarty->fetch($tpl));
    } 

    /**
    * test resgistered object as block function
    */
    public function testRegisteredObjectBlockFunction()
    {
        $tpl = $this->smarty->createTemplate('string:{objecttest->myblock}hello world{/objecttest->myblock}');
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
