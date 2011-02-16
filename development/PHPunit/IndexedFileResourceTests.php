<?php
/**
* Smarty PHPunit tests for File resources
* 
* @package PHPunit
* @author Rodney Rehm
*/


class IndexedFileResourceTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        $this->smarty->template_dir[] = dirname(__FILE__) .'/templates_2';
        // note that 10 is a string!
        $this->smarty->template_dir['10'] = dirname(__FILE__) .'/templates_3';
        $this->smarty->template_dir['foo'] = dirname(__FILE__) .'/templates_4';
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 
    
    protected function relative($path)
    {
        return str_replace( dirname(__FILE__), '.', $path );
    }

    public function testGetTemplateFilepath()
    {
        $tpl = $this->smarty->createTemplate('dirname.tpl');
        $this->assertEquals(realpath('./templates/dirname.tpl'), realpath($this->relative($tpl->source->filepath)));
    } 
    public function testGetTemplateFilepathNumber()
    {
        $tpl = $this->smarty->createTemplate('[1]dirname.tpl');
        $this->assertEquals(realpath('./templates_2/dirname.tpl'), realpath($this->relative($tpl->source->filepath)));
    }
    public function testGetTemplateFilepathNumeric()
    {
        $tpl = $this->smarty->createTemplate('[10]dirname.tpl');
        $this->assertEquals(realpath('./templates_3/dirname.tpl'), realpath($this->relative($tpl->source->filepath)));
    }
    public function testGetTemplateFilepathName()
    {
        $tpl = $this->smarty->createTemplate('[foo]dirname.tpl');
        $this->assertEquals(realpath('./templates_4/dirname.tpl'), realpath($this->relative($tpl->source->filepath)));
    }
    
    
    public function testFetch()
    {
        $tpl = $this->smarty->createTemplate('dirname.tpl');
        $this->assertEquals('templates', $this->smarty->fetch($tpl));
    } 
    public function testFetchNumber()
    {
        $tpl = $this->smarty->createTemplate('[1]dirname.tpl');
        $this->assertEquals('templates_2', $this->smarty->fetch($tpl));
    }
    public function testFetchNumeric()
    {
        $tpl = $this->smarty->createTemplate('[10]dirname.tpl');
        $this->assertEquals('templates_3', $this->smarty->fetch($tpl));
    }
    public function testFetchName()
    {
        $tpl = $this->smarty->createTemplate('[foo]dirname.tpl');
        $this->assertEquals('templates_4', $this->smarty->fetch($tpl));
    }


    public function testGetCompiledFilepath()
    {
        $tpl = $this->smarty->createTemplate('[foo]dirname.tpl');
        $expected = './templates_c/'.sha1($this->smarty->template_dir['foo'].DS.'dirname.tpl').'.file.dirname.tpl.php';
        $this->assertEquals(realpath($expected), realpath($this->relative($tpl->compiled->filepath)));
    }


    public function testGetCachedFilepathCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('[foo]dirname.tpl');
        $this->assertFalse($tpl->cached->filepath);
    } 


    public function testGetCachedFilepath()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('[foo]dirname.tpl');
	    $expected = './cache/'.sha1($this->smarty->template_dir['foo'].DS.'dirname.tpl').'.dirname.tpl.php';
        $this->assertEquals(realpath($expected), realpath($this->relative($tpl->cached->filepath)));
    }

    public function testRelativeFail()
    {
        try {
            $this->smarty->fetch('[foo]./dirname.tpl');
        } 
        catch (Exception $e) {
            $this->assertContains("may not start with ../ or ./", $e->getMessage());
            return;
        } 
        $this->fail('Exception for relative filepath has not been raised.');
    }

    public function testFinalCleanup()
    {
        $this->smarty->clearCompiledTemplate();
	  $this->smarty->clearAllCache();
    } 
} 

?>
