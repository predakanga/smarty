<?php
/**
* Smarty PHPunit test suite
* 
* @package PHPunit
* @author Uwe Tews 
*/
require_once 'PHPUnit/Framework.php';

/**
* class for running test suite
*/
class SmartyTests extends PHPUnit_Framework_TestSuite {

    /**
    * look for test units and run them
    */
    public static function suite()
    {
        PHPUnit_Util_Filter::addDirectoryToWhitelist('../libs');
	  PHPUnit_Util_Filter::removeDirectoryFromWhitelist('../libs/lexer');
        PHPUnit_Util_Filter::addDirectoryToWhitelist('../plugins');

        $suite = new self('Smarty 3 - Unit Tests Report');

        foreach (new DirectoryIterator(dirname(__FILE__)) as $file) {
            if (!$file->isDot() && !$file->isDir() && (string) $file !== 'smartytests.php' && substr((string) $file, -4) === '.php') {
                require_once $file->getPathname();
                $class = basename($file, '.php'); 
                // to have an optional test suite, it should implement a public static function isRunnable
                // that returns true only if all the conditions are met to run it successfully, for example
                // it can check that an external library is present
                if (!method_exists($file, 'isRunnable') || call_user_func(array($file, 'isRunnable'))) {
                    $suite->addTestSuite($class);
                } 
            } 
        } 

        return $suite;
    }
}
?>
