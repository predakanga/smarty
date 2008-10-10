<?php
//  Compiler foreach tags
//
//  Not yet complete
//  - name tag not yet supported
//  - nesting checks missing

class Smarty_Internal_Compile_If extends Smarty_Internal_CompileBase {
    /**
    * Compile {if...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
        $this->_open_tag('if');
        return '<?php if (' .$args[ifexp] . '): ?>';   
        }
} 

class Smarty_Internal_Compile_Else extends Smarty_Internal_CompileBase {
    /**
    * Compile {else ...} tag
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
            $this->_close_tag(array('if','elseif'));
            $this->_open_tag('else');

        return '<?php else: ?>';
    } 
} 
class Smarty_Internal_Compile_ElseIf extends Smarty_Internal_CompileBase {
    /**
    * Compile {elseif ...} tag
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
            $this->_close_tag(array('if','elseif'));
            $this->_open_tag('elseif');

        return '<?php elseif (' .$args[ifexp] . '): ?>'; 
    } 
} 

 
class Smarty_Internal_Compile_End_If extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {/if} tag
        * 
       * @return string 
        */ 
            $this->_close_tag(array('if','else','elseif'));
        return "<?php endif;?>";
         } 
} 
?>
