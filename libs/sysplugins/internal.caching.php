<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
/**
*  Cache Handler
*/

class Smarty_Internal_Caching extends Smarty_Internal_PluginBase {
    public function display($_compiled_filepath, $_cached_filepath)
    {
        // get file timestamp from cache file 
        if (file_exists($_cached_filepath)) {
            $cache_file_time = filemtime($_cached_filepath);
        } else {
            $cache_file_time = 0;
        } 
        if ($cache_file_time == 0 || filemtime($_compiled_filepath) > $cache_file_time || $this->smarty->cache_lifetime > 0 AND time() > $cache_file_time + $this->smarty->cache_lifetime) {
            // must create cache file
            // get output
            ob_start();
            include($_compiled_filepath);
            $_smarty_results = ob_get_contents();
            ob_end_clean(); 
            // write to tmp file, then rename it to avoid file locking race condition
            $_tmp_file = tempnam($this->smarty->cache_dir, 'wrt');

            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                $_tmp_file = $this->smarty->cache_dir . DIRECTORY_SEPARATOR . uniqid('wrt');
                if (!($fd = @fopen($_tmp_file, 'wb'))) {
                    // $smarty->trigger_error("problem writing temporary file '$_tmp_file'");
                    echo "problem writing temporary file '$_tmp_file'";
                    return false;
                } 
            } 

            fwrite($fd, $_smarty_results);
            fclose($fd);

            if (DIRECTORY_SEPARATOR == '\\' || !@rename($_tmp_file, $_cached_filepath)) {
                // On platforms and filesystems that cannot overwrite with rename()
                // delete the file before renaming it -- because windows always suffers
                // this, it is short-circuited to avoid the initial rename() attempt
                @unlink($_cached_filepath);
                @rename($_tmp_file, $_cached_filepath);
            } 
        } // end create cache file

        // get cache file and process it. It may contain nocached code
        include($_cached_filepath);
        
        return;
    } 
} 

?>
