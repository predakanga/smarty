<?php
// compiler.test.php
class smarty_compiler_test extends Smarty_Internal_CompileBase
{
    public function execute($args, $compiler)
    {
        $this->compiler=$compiler;
        $this->required_attributes = array('data');

        $_attr = $this->_get_attributes($args);

        $this->_open_tag('test');

        return "<?php echo 'test output'; ?>";
    }

}

?> 
