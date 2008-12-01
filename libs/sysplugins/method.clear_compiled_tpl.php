<?php

/**
* Smarty method Clear_Compiled_Tpl
* 
* Deletes compiled template files
* 
* @package Smarty
* @subpackage Smartymethod
* @author Uwe Tews 
*/

/**
* Smarty class Clear_Compiled_Tpl
* 
* Deletes compiled template files
*/

class Smarty_Method_Clear_Compiled_Tpl extends Smarty_Internal_Base {
    /**
    * Delete compiled template file
    */
    public function execute($args)
    {
        $count = 0;
        $compileDirs = new RecursiveDirectoryIterator($this->smarty->compile_dir);
        $compile = new RecursiveIteratorIterator($compileDirs);
        foreach ($compile as $file) {
            if ($compile->isDot() || $compile->isDir() || substr($file, -4) !== '.php') {
                continue;
            } 
            $count += unlink((string) $file) ? 1 : 0;
        } 
        return $count;
    } 
} 

?>
