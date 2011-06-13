<?php
/**
 * Smarty Internal Plugin CompileBase
 * 
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews 
 */

/**
 * This class does extend all internal compile plugins
 * 
 * @package Smarty
 * @subpackage Compiler
 * @todo  This class is not abstract but there is an (unmentioned) abstract function compile(),
 *        that each sub-class implements. If you do not want to make this class abstract,
 *        consider introducing an interface class, to ensure all sub-classes implement the function
 *        correctly.
 */
// abstract class Smarty_Internal_CompileBase implements TagCompilerInterface
class Smarty_Internal_CompileBase {

    /**
     * @var array
     * @todo Missing documentation
     */
    public $required_attributes = array();
    /**
     * @var array
     * @todo Missing documentation
     */
    public $optional_attributes = array();
    /**
     * @var array
     * @todo Missing documentation
     */
    public $shorttag_order = array();
    /**
     * @var array
     * @todo Missing documentation
     */
    public $option_flags = array('nocache');

    /**
     * This function checks if the attributes passed are valid
     * 
     * The attributes passed for the tag to compile are checked against the list of required and 
     * optional attributes. Required attributes must be present. Optional attributes are check against
     * against the corresponding list. The keyword '_any' specifies that any attribute will be accepted 
     * as valid
     * 
     * @param object $compiler   compiler object
     * @param array  $attributes attributes applied to the tag
     * @return array of mapped attributes for further processing
     * @todo Consider using CamelCase here.
     */
    public function _get_attributes($compiler, $attributes)
    {
        $_indexed_attr = array();
        // loop over attributes
        foreach ($attributes as $key => $mixed) {
            // shorthand ?
            if (!is_array($mixed)) {
                // option flag ?
                if (in_array(trim($mixed, '\'"'), $this->option_flags)) {
                    $_indexed_attr[trim($mixed, '\'"')] = true;
                    // shorthand attribute ?
                } else if (isset($this->shorttag_order[$key])) {
                    $_indexed_attr[$this->shorttag_order[$key]] = $mixed;
                } else {
                    // too many shorthands
                    $compiler->trigger_template_error('too many shorthand attributes', $compiler->lex->taglineno);
                }
                // named attribute
            } else {
                $kv = each($mixed);
                // option flag?
                if (in_array($kv['key'], $this->option_flags)) {
                    if (is_bool($kv['value'])) {
                        $_indexed_attr[$kv['key']] = $kv['value'];
                    } else if (is_string($kv['value']) && in_array(trim($kv['value'], '\'"'), array('true', 'false'))) {
                        if (trim($kv['value']) == 'true') {
                            $_indexed_attr[$kv['key']] = true;
                        } else {
                            $_indexed_attr[$kv['key']] = false;
                        }
                    } else if (is_numeric($kv['value']) && in_array($kv['value'], array(0, 1))) {
                        if ($kv['value'] == 1) {
                            $_indexed_attr[$kv['key']] = true;
                        } else {
                            $_indexed_attr[$kv['key']] = false;
                        }
                    } else {
                        $compiler->trigger_template_error("illegal value of option flag \"{$kv['key']}\"", $compiler->lex->taglineno);
                    }
                    // must be named attribute
                } else {
                    reset($mixed);
                    $_indexed_attr[key($mixed)] = $mixed[key($mixed)];
                }
            }
        }
        // check if all required attributes present
        foreach ($this->required_attributes as $attr) {
            if (!array_key_exists($attr, $_indexed_attr)) {
                $compiler->trigger_template_error("missing \"" . $attr . "\" attribute", $compiler->lex->taglineno);
            }
        }
        // check for unallowed attributes
        if ($this->optional_attributes != array('_any')) {
            $tmp_array = array_merge($this->required_attributes, $this->optional_attributes, $this->option_flags);
            foreach ($_indexed_attr as $key => $dummy) {
                if (!in_array($key, $tmp_array) && $key !== 0) {
                    $compiler->trigger_template_error("unexpected \"" . $key . "\" attribute", $compiler->lex->taglineno);
                }
            }
        }
        // default 'false' for all option flags not set
        foreach ($this->option_flags as $flag) {
            if (!isset($_indexed_attr[$flag])) {
                $_indexed_attr[$flag] = false;
            }
        }

        return $_indexed_attr;
    }

    /**
     * Push opening tag name on stack
     * 
     * Optionally additional data can be saved on stack
     * 
     * @param object $compiler compiler object
     * @param string $openTag  the opening tag's name
     * @return mixed any type the opening tag's name or saved data
     * @todo Consider using CamelCase here.
     */
    public function _open_tag($compiler, $openTag, $data = null)
    {
        array_push($compiler->_tag_stack, array($openTag, $data));
    }

    /**
     * Pop closing tag
     * 
     * Raise an error if this stack-top doesn't match with expected opening tags
     * 
     * @param object       $compiler    compiler object
     * @param array|string $expectedTag the expected opening tag names
     * @return mixed any type the opening tag's name or saved data
     * @todo Consider using CamelCase here.
     */
    public function _close_tag($compiler, $expectedTag)
    {
        if (count($compiler->_tag_stack) > 0) {
            // get stacked info
            list($_openTag, $_data) = array_pop($compiler->_tag_stack);
            // open tag must match with the expected ones
            if (in_array($_openTag, (array) $expectedTag)) {
                if (is_null($_data)) {
                    // return opening tag
                    return $_openTag;
                } else {
                    // return restored data
                    return $_data;
                }
            }
            // wrong nesting of tags
            $compiler->trigger_template_error("unclosed {" . $_openTag . "} tag");
            return;
        }
        // wrong nesting of tags
        $compiler->trigger_template_error("unexpected closing tag", $compiler->lex->taglineno);
        return;
    }

}

?>