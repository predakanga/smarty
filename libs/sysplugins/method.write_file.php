<?php

/**
 * Smarty function class
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Method_Write_File extends Smarty_Internal_Base {
 
  /**
   * @param string $args[0] filepath
   * @param string $args[1] contents
   */
  public function execute($args)
  {
      if (count($args) !== 2)
      {
          throw new SmartyException("write_file: bad parameters");
          return false;
      }
      
      $_filepath = $args[0];
      $_contents = $args[1];

      $_dirpath = dirname($_filepath);
      $_filename = basename($_filepath);
      
      // if subdirs, create dir structure
      if($_dirpath !== '.' && !file_exists($_dirpath))
      {
          mkdir($_dirpath,0755,true);
      }

      // write to tmp file, then move to overt file lock race condition
      $_tmp_file = tempnam($_dirpath, 'wrt');
      
      if(!file_put_contents($_tmp_file,$_contents))
      {
          throw new SmartyException("unable to write file {$_tmp_file}");
          return false;
      }

      // remove original file
      if(file_exists($_filepath))
        unlink($_filepath);
      
      // rename tmp file  
      rename($_tmp_file, $_filepath);
      
      // set file permissions
      chmod($_filepath, 0644);
      
      return true;
    
  }
  
}

?>
