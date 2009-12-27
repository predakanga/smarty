<?php
/**
* Smarty PHPunit tests for deleting compiled templates
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for delete compiled template tests
*/
class ClearCompiledTests extends PHPUnit_Framework_TestCase {

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
    * test clear_compiled_tpl method for all files
    */
    public function testClearCompiledAll()
    {
        $this->smarty->clear_compiled_tpl();
        file_put_contents($this->smarty->compile_dir . 'dummy.php', 'test');
        file_put_contents($this->smarty->compile_dir . 'dummy2.php', 'test');
        $this->assertEquals(2, $this->smarty->clear_compiled_tpl());
    } 
    /**
    * test clear_compiled_tpl method for a specific resource
    */
    public function testClearCompiledResource()
    {
        $this->smarty->clear_compiled_tpl();
        file_put_contents($this->smarty->compile_dir . 'dummy.php', 'test');
        file_put_contents($this->smarty->compile_dir . 'dummy2.php', 'test');
        $this->assertEquals(1, $this->smarty->clear_compiled_tpl('dummy'));
    } 
    /**
    * test clear_compiled_tpl method not expired
    */
    public function testClearCompiledNotExpired()
    {
        $this->smarty->clear_compiled_tpl();
        file_put_contents($this->smarty->compile_dir . 'dummy.php', 'test');
        touch($this->smarty->compile_dir . 'dummy.php', time()-1000);
        $this->assertEquals(0, $this->smarty->clear_compiled_tpl(null, null, 2000));
    } 
    /**
    * test clear_compiled_tpl method expired
    */
    public function testClearCompiledExpired()
    {
        $this->smarty->clear_compiled_tpl();
        file_put_contents($this->smarty->compile_dir . 'dummy.php', 'test');
        touch($this->smarty->compile_dir . 'dummy.php', time()-1000);
        $this->assertEquals(1, $this->smarty->clear_compiled_tpl(null, null, 500));
    } 
    /**
    * test clear_compiled_tpl with compile_id
    */
    public function testClearCompiledCompileId()
    {
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'blar');
        Smarty_Internal_Write_File::writeFile($tpl->getCompiledFilepath(), 'hello world', $this->smarty);
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'blar2');
        Smarty_Internal_Write_File::writeFile($tpl2->getCompiledFilepath(), 'hello world', $this->smarty);
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'blar');
        Smarty_Internal_Write_File::writeFile($tpl3->getCompiledFilepath(), 'hello world', $this->smarty);
        $this->assertTrue(file_exists($tpl->getCompiledFilepath()));
        $this->assertTrue(file_exists($tpl2->getCompiledFilepath()));
        $this->assertTrue(file_exists($tpl3->getCompiledFilepath()));
        $this->assertEquals(2, $this->smarty->clear_compiled_tpl (null, 'blar'));
        $this->assertFalse(file_exists($tpl->getCompiledFilepath()));
        $this->assertTrue(file_exists($tpl2->getCompiledFilepath()));
        $this->assertFalse(file_exists($tpl3->getCompiledFilepath()));
    } 
} 

?>
