<?php
/**
* Smarty PHPunit tests token fallback of parser
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for token fallback tests
*/
class TokenFallbackTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->force_compile = true;
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test token fallback part 1
    */
    public function testTokenFallback1()
    {
        $tpl = $this->smarty->createTemplate('string:word 123 \'string\' x { x } x === x == x != x >= x ge x <= x le x > x gt ', $this->smarty);
        $this->assertEquals('word 123 \'string\' x { x } x === x == x != x >= x ge x <= x le x > x gt ', $this->smarty->fetch($tpl));
    } 
    /**
    * test token fallback part 2
    */
    public function testTokenFallback2()
    {
        $tpl = $this->smarty->createTemplate('string: < x lt x ! x not x && x and x || or x ( x ) x [ x ] x -> x => ', $this->smarty);
        $this->assertEquals(' < x lt x ! x not x && x and x || or x ( x ) x [ x ] x -> x => ', $this->smarty->fetch($tpl));
    } 
    /**
    * test token fallback part 3
    */
    public function testTokenFallback3()
    {
        $tpl = $this->smarty->createTemplate('string: = x + x - x * x / x % x ++ x -- x $ x ; x : x " x ` x | x . x , x & ', $this->smarty);
        $this->assertEquals(' = x + x - x * x / x % x ++ x -- x $ x ; x : x " x ` x | x . x , x & ', $this->smarty->fetch($tpl));
    } 
    /**
    * test token fallback part 4
    */
    public function testTokenFallback4()
    {
        $tpl = $this->smarty->createTemplate('string: in x true x false x $a ', $this->smarty);
        $this->assertEquals(' in x true x false x $a ', $this->smarty->fetch($tpl));
    } 
} 

?>
