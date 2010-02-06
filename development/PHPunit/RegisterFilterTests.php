<?php
/**
* Smarty PHPunit tests register_filter / unregister_filter methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register_filter / unregister_filter methods tests
*/
class RegisterFilterTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test register->preFilter method for function
    */
    public function testRegisterPrefilterFunction()
    {
        $this->smarty->register->preFilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['pre']['myfilter']));
    } 
    /**
    * test register->preFilter method for class methode
    */
    public function testRegisterPrefilterMethode()
    {
        $this->smarty->register->preFilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['pre']['myfilterclass_execute']));
    } 
    /**
    * test register->preFilter method for class object
    */
    public function testRegisterPrefilterObject()
    {
        $this->smarty->register->preFilter(array(new myfilterclass,'execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['pre']['myfilterclass_execute']));
    } 
    /**
    * test unregister->preFilter method for function
    */
    public function testUnegisterPrefilterFunction()
    {
        $this->smarty->register->preFilter('myfilter');
        $this->smarty->unregister->preFilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['pre']['myfilter']));
    } 
    /**
    * test unregister->preFilter method for class methode
    */
    public function testUnregisterPrefilterMethode()
    {
        $this->smarty->register->preFilter(array('myfilterclass','execute'));
        $this->smarty->unregister->preFilter(array('myfilterclass','execute'));
        $this->assertFalse(isset($this->smarty->registered_filters['pre']['myfilterclass_execute']));
    } 
    /**
    * test register->postFilter method for function
    */
    public function testRegisterPostfilterFunction()
    {
        $this->smarty->register->postFilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['post']['myfilter']));
    } 
    /**
    * test register->postFilter method for class methode
    */
    public function testRegisterPostfilterMethode()
    {
        $this->smarty->register->postFilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['post']['myfilterclass_execute']));
    } 
    /**
    * test unregister->postFilter method for function
    */
    public function testUnegisterPostfilterFunction()
    {
        $this->smarty->register->postFilter('myfilter');
        $this->smarty->unregister->postFilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['post']['myfilter']));
    } 
    /**
    * test unregister->postFilter method for class methode
    */
    public function testUnregisterPostfilterMethode()
    {
        $this->smarty->register->postFilter(array('myfilterclass','execute'));
        $this->smarty->unregister->postFilter(array('myfilterclass','execute'));
        $this->assertFalse(isset($this->smarty->registered_filters['post']['myfilterclass_execute']));
    } 
    /**
    * test register->outputFilter method for function
    */
    public function testRegisterOutputfilterFunction()
    {
        $this->smarty->register->outputFilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['myfilter']));
    } 
    /**
    * test register->outputFilter method for class methode
    */
    public function testRegisterOutputfilterMethode()
    {
        $this->smarty->register->outputFilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['myfilterclass_execute']));
    } 
    /**
    * test unregister->outputFilter method for function
    */
    public function testUnegisterOutputfilterFunction()
    {
        $this->smarty->register->outputFilter('myfilter');
        $this->smarty->unregister->outputFilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['output']['myfilter']));
    } 
    /**
    * test unregister->outputFilter method for class methode
    */
    public function testUnregisterOutputfilterMethode()
    {
        $this->smarty->register->outputFilter(array('myfilterclass','execute'));
        $this->smarty->unregister->outputFilter(array('myfilterclass','execute'));
        $this->assertFalse(isset($this->smarty->registered_filters['output']['myfilterclass_execute']));
    } 
} 
function myfilter($input)
{
    return $input;
} 
class myfilterclass {
    static function execute($input)
    {
        return $input;
    } 
} 

?>
