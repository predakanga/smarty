<?php
/**
* Smarty PHPunit tests stream variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for stream variables tests
*/
class StreamVariableTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->force_compile = true;
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
        stream_wrapper_register("var", "VariableStream")
        or die("Failed to register protocol");
        $fp = fopen("var://foo", "r+");
        fwrite($fp, 'hello world');
        fclose($fp);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
        stream_wrapper_unregister('var');
    } 

    /**
    * test stream variable
    */
    public function testStreamVariable1()
    {
        $tpl = $this->smarty->createTemplate('string:{$var:foo}', $this->smarty);
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
    } 
    /**
    * test no existant stream variable
    */
    public function testStreamVariable2()
    {
        $tpl = $this->smarty->createTemplate('string:{$var:bar}', $this->smarty);
        $this->assertEquals('', $this->smarty->fetch($tpl));
    } 
} 
class VariableStream {
    private $position;
    private $varname;
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;
        return true;
    } 
    public function stream_read($count)
    {
        $p = &$this->position;
        $ret = substr($GLOBALS[$this->varname], $p, $count);
        $p += strlen($ret);
        return $ret;
    } 
    public function stream_write($data)
    {
        $v = &$GLOBALS[$this->varname];
        $l = strlen($data);
        $p = &$this->position;
        $v = substr($v, 0, $p) . $data . substr($v, $p += $l);
        return $l;
    } 
    public function stream_tell()
    {
        return $this->position;
    } 
    public function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    } 
    public function stream_seek($offset, $whence)
    {
        $l = strlen(&$GLOBALS[$this->varname]);
        $p = &$this->position;
        switch ($whence) {
            case SEEK_SET: $newPos = $offset;
                break;
            case SEEK_CUR: $newPos = $p + $offset;
                break;
            case SEEK_END: $newPos = $l + $offset;
                break;
            default: return false;
        } 
        $ret = ($newPos >= 0 && $newPos <= $l);
        if ($ret) $p = $newPos;
        return $ret;
    } 
} 

?>
