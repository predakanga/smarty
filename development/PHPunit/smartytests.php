<?php
/**
* Smarty PHPunit test suite
* 
* @package PHPunit
* @author Uwe Tews 
*/
require_once 'PHPUnit/Framework.php';

define ('SMARTY_DIR','../../distribution/libs/');

/**
* class for running test suite
*/
class SmartyTests extends PHPUnit_Framework_TestSuite {
    /**
    * look for test units and run them
    */
    public static function suite()
    {
        $testorder = array('DoubleQuotedStringTests','CoreTests','ClearCompiledTests','ClearCacheTests','StringResourceTests','FileResourceTests'
                            ,'PhpResourceTests','CompileAssignTests');

//        PHPUnit_Util_Filter::addDirectoryToWhitelist(SMARTY_DIR);
        PHPUnit_Util_Filter::removeDirectoryFromWhitelist('../');
//        PHPUnit_Util_Filter::addDirectoryToWhitelist('../libs/plugins');

        $suite = new self('Smarty 3 - Unit Tests Report'); 
        // load test which should run in specific order
        foreach ($testorder as $class) {
            require_once $class . '.php';
            $suite->addTestSuite($class);
        } 

        foreach (new DirectoryIterator(dirname(__FILE__)) as $file) {
            if (!$file->isDot() && !$file->isDir() && (string) $file !== 'smartytests.php' && substr((string) $file, -4) === '.php') {
                $class = basename($file, '.php');
                if (!in_array($class, $testorder)) {
                    require_once $file->getPathname(); 
                    // to have an optional test suite, it should implement a public static function isRunnable
                    // that returns true only if all the conditions are met to run it successfully, for example
                    // it can check that an external library is present
                    if (!method_exists($file, 'isRunnable') || call_user_func(array($file, 'isRunnable'))) {
                        $suite->addTestSuite($class);
                    } 
                } 
            } 
        } 
        return $suite;
    } 
} 

?>
