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
        $this->smarty = Smarty::instance();
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test register_prefilter method for function
    */
    public function testRegisterPrefilterFunction()
    {
        $this->smarty->register_prefilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['pre']['myfilter']));
    } 
    /**
    * test register_prefilter method for class methode
    */
    public function testRegisterPrefilterMethode()
    {
        $this->smarty->register_prefilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['pre']['myfilterclass']));
    } 
    /**
    * test unregister_prefilter method for function
    */
    public function testUnegisterPrefilterFunction()
    {
        $this->smarty->register_prefilter('myfilter');
        $this->smarty->unregister_prefilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['pre']['myfilter']));
    } 
    /**
    * test unregister_prefilter method for class methode
    */
    public function testUnregisterPrefilterMethode()
    {
        $this->smarty->register_prefilter(array('myfilterclass','execute'));
        $this->smarty->unregister_prefilter('myfilterclass');
        $this->assertFalse(isset($this->smarty->registered_filters['pre']['myfilterclass']));
    } 
    /**
    * test register_postfilter method for function
    */
    public function testRegisterPostfilterFunction()
    {
        $this->smarty->register_postfilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['post']['myfilter']));
    } 
    /**
    * test register_postfilter method for class methode
    */
    public function testRegisterPostfilterMethode()
    {
        $this->smarty->register_postfilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['post']['myfilterclass']));
    } 
    /**
    * test unregister_postfilter method for function
    */
    public function testUnegisterPostfilterFunction()
    {
        $this->smarty->register_postfilter('myfilter');
        $this->smarty->unregister_postfilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['post']['myfilter']));
    } 
    /**
    * test unregister_postfilter method for class methode
    */
    public function testUnregisterPostfilterMethode()
    {
        $this->smarty->register_postfilter(array('myfilterclass','execute'));
        $this->smarty->unregister_postfilter('myfilterclass');
        $this->assertFalse(isset($this->smarty->registered_filters['post']['myfilterclass']));
    } 
    /**
    * test register_outputfilter method for function
    */
    public function testRegisterOutputfilterFunction()
    {
        $this->smarty->register_outputfilter('myfilter');
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['myfilter']));
    } 
    /**
    * test register_outputfilter method for class methode
    */
    public function testRegisterOutputfilterMethode()
    {
        $this->smarty->register_outputfilter(array('myfilterclass','execute'));
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['myfilterclass']));
    } 
    /**
    * test unregister_outputfilter method for function
    */
    public function testUnegisterOutputfilterFunction()
    {
        $this->smarty->register_outputfilter('myfilter');
        $this->smarty->unregister_outputfilter('myfilter');
        $this->assertFalse(isset($this->smarty->registered_filters['output']['myfilter']));
    } 
    /**
    * test unregister_outputfilter method for class methode
    */
    public function testUnregisterOutputfilterMethode()
    {
        $this->smarty->register_outputfilter(array('myfilterclass','execute'));
        $this->smarty->unregister_outputfilter('myfilterclass');
        $this->assertFalse(isset($this->smarty->registered_filters['output']['myfilterclass']));
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
