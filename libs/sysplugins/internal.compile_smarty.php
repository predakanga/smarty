<?php
/**
* Smarty Internal Plugin Compile Smarty
* 
* Compiles the special $smarty variables
* 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Compile Smarty Class
*/
class Smarty_Internal_Compile_Smarty extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the speical $smarty variables
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $_index = explode(',', str_replace(array('][', '[', ']'), array(',', '', ''), $args));
        $compiled_ref = ' ';
        switch (trim($_index[0], "'")) {
            case 'foreach':
                $compiled_ref = "\$_smarty_tpl->getVariable('smarty')->value$args";
                $_max_index = 1;
                break;
            case 'capture':
                $compiled_ref = "\$_smarty_tpl->getVariable('smarty')->value$args";
                $_max_index = 1;
                break;

            case 'now':
                $compiled_ref = 'time()';
                $_max_index = 1;
                break;

            case 'get':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_GET[$_index[1]]" : "\$GLOBALS['HTTP_GET_VARS'][$_index[1]]";
                break;

            case 'post':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_POST[$_index[1]]" : "\$GLOBALS['HTTP_POST_VARS'][$_index[1]]";
                break;

            case 'cookies':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_COOKIE[$_index[1]]" : "\$GLOBALS['HTTP_COOKIE_VARS'][$_index[1]]";
                break;

            case 'env':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_ENV[$_index[1]]" : "\$GLOBALS['HTTP_ENV_VARS'][$_index[1]]";
                break;

            case 'server':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_SERVER[$_index[1]]" : "\$GLOBALS['HTTP_SERVER_VARS'][$_index[1]]";
                break;

            case 'session':
                $compiled_ref = ($this->smarty->request_use_auto_globals) ? "\$_SESSION[$_index[1]]" : "\$GLOBALS['HTTP_SESSION_VARS'][$_index[1]]";
                break;

            case 'request':
                if ($this->smarty->request_use_auto_globals) {
                    $compiled_ref = "\$_REQUEST[$_index[1]]";
                    break;
                } 

            case 'template':
                $_template_name = $compiler->template->getTemplateFilepath();
                $compiled_ref = "'$_template_name'";
                $_max_index = 1;
                break;

            case 'version':
                $_version = Smarty::$_version;
                $compiled_ref = "'$_version'";
                $_max_index = 1;
                break;

            case 'const':
                if ($this->smarty->security && !$this->smarty->security_policy->allow_constants) {
                    $compiler->trigger_template_error("(secure mode) constants not permitted");
                    break;
                } 
                $compiled_ref = '@' . trim($_index[1], "'");
                $_max_index = 1;
                break;

            case 'config':
                $compiled_ref = "\$this->_config[0]['vars']";
                $_max_index = 3;
                break;

            case 'ldelim':
                $_ldelim = $this->smarty->left_delimiter;
                $compiled_ref = "'$_ldelim'";
                break;

            case 'rdelim':
                $_rdelim = $this->smarty->right_delimiter;
                $compiled_ref = "'$_rdelim'";
                break;

            default:
                $compiler->trigger_template_error('$smarty.' . trim($_index[0], "'") . ' is an unknown reference');
                break;
        } 

        return $compiled_ref;
    } 
} 

?>
