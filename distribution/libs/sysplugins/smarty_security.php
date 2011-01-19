<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage Security
 * @author Uwe Tews 
 */ 

/**
 * This class does contain the security settings
 */
class Smarty_Security {
    /**
     * This determines how Smarty handles "<?php ... ?>" tags in templates.
     * possible values:
     * <ul>
     *   <li>Smarty::PHP_PASSTHRU -> echo PHP tags as they are</li>
     *   <li>Smarty::PHP_QUOTE    -> escape tags as entities</li>
     *   <li>Smarty::PHP_REMOVE   -> remove php tags</li>
     *   <li>Smarty::PHP_ALLOW    -> execute php tags</li>
     * </ul>
     * 
     * @var integer 
     */
    public $php_handling = Smarty::PHP_PASSTHRU;

    /**
     * This is the list of template directories that are considered secure.
     * $template_dir is in this list implicitly.
     * 
     * @var array 
     */
    public $secure_dir = array();


    /**
     * This is an array of directories where trusted php scripts reside.
     * {@link $security} is disabled during their inclusion/execution.
     * 
     * @var array 
     */
    public $trusted_dir = array();


    /**
     * This is an array of trusted static classes.
     *
     * If empty access to all static classes is allowed.
     * If set to 'none' none is allowed.
     * @var array 
     */
    public $static_classes = array();

    /**
     * This is an array of trusted PHP functions.
     *
     * If empty all functions are allowed.
     * To disable all PHP functions set $php_functions = null.
     * @var array 
     */
    public $php_functions = array(
        'isset', 'empty',
        'count', 'sizeof',
        'in_array', 'is_array',
        'time',
        'nl2br',
    );

    /**
     * This is an array of trusted PHP modifers.
     *
     * If empty all modifiers are allowed.
     * To disable all modifier set $modifiers = null.
     * @var array 
     */
    public $php_modifiers = array(
        'escape',
        'count'
    );

    /**
     * This is an array of trusted streams.
     *
     * If empty all streams are allowed.
     * To disable all streams set $streams = null.
     * @var array 
     */
    public $streams = array('file');
    /**
     * + flag if constants can be accessed from template
     */
    public $allow_constants = true;
    /**
     * + flag if super globals can be accessed from template
     */
    public $allow_super_globals = true;
    /**
     * + flag if the {php} and {include_php} tag can be executed
     */
    public $allow_php_tag = false;

    public function __construct($smarty)
    {
        $this->smarty = $smarty; 
	}
	
	protected $_resource_dir = null;
	protected $_template_dir = null;
	protected $_config_dir = null;
	protected $_secure_dir = null;
	protected $_php_resource_dir = null;
	protected $_trusted_dir = null;
	
    /**
     * Check if PHP function is trusted.
     * 
     * @param string $function_name 
     * @param object $compiler compiler object
     * @return boolean true if function is trusted
     * @throws SmartyCompilerException if php function is not trusted
     */
    function isTrustedPhpFunction($function_name, $compiler)
    {
        if (isset($this->php_functions) && (empty($this->php_functions) || in_array($function_name, $this->php_functions))) {
            return true;
        }
        
        $compiler->trigger_template_error ("PHP function '{$function_name}' not allowed by security setting");
        return false; // should not, but who knows what happens to the compiler in the future?
    } 

    /**
     * Check if static class is trusted.
     * 
     * @param string $class_name 
     * @param object $compiler compiler object
     * @return boolean true if class is trusted
     * @throws SmartyCompilerException if static class is not trusted
     */
    function isTrustedStaticClass($class_name, $compiler)
    {
        if (isset($this->static_classes) && (empty($this->static_classes) || in_array($class_name, $this->static_classes))) {
            return true;
        }
        
        $compiler->trigger_template_error("access to static class '{$class_name}' not allowed by security setting");
        return false; // should not, but who knows what happens to the compiler in the future?
    } 
    
    /**
     * Check if modifier is trusted.
     * 
     * @param string $modifier_name 
     * @param object $compiler compiler object
     * @return boolean true if modifier is trusted
     * @throws SmartyCompilerException if modifier is not trusted
     */
    function isTrustedModifier($modifier_name, $compiler)
    {
        if (isset($this->php_modifiers) && (empty($this->php_modifiers) || in_array($modifier_name, $this->php_modifiers))) {
            return true;
        }

        $compiler->trigger_template_error("modifier '{$modifier_name}' not allowed by security setting");
        return false; // should not, but who knows what happens to the compiler in the future?
    } 
    
    /**
     * Check if stream is trusted.
     * 
     * @param string $stream_name 
     * @param object $compiler compiler object
     * @return boolean true if stream is trusted
     * @throws SmartyException if stream is not trusted
     */
    function isTrustedStream($stream_name)
    {
        if (isset($this->streams) && (empty($this->streams) || in_array($stream_name, $this->streams))) {
            return true;
        }
        
        throw new SmartyException ("stream '{$stream_name}' not allowed by security setting");
    } 

    /**
     * Check if directory of file resource is trusted.
     * 
     * @param string $filepath 
     * @param object $compiler compiler object
     * @return boolean true if directory is trusted
     */
    function isTrustedResourceDir($filepath)
    {
        $_template = false;
        $_config = false;
        $_secure = false;
        
        // check if index is outdated
        if ((!$this->_template_dir || $this->_template_dir !== $this->smarty->template_dir)
            || (!$this->_config_dir || $this->_config_dir !== $this->smarty->config_dir)
            || (!empty($this->secure_dir) && (!$this->_secure_dir || $this->_secure_dir !== $this->secure_dir))
        ) {
            $this->_resource_dir = array();
            $_template = true;
            $_config = true;
            $_secure = !empty($this->secure_dir);
        }
        
        // rebuild template dir index
        if ($_template) {
            $this->_template_dir = $this->smarty->template_dir;
            foreach ((array)$this->smarty->template_dir as $directory) {
                $directory = realpath($directory);
                $this->_resource_dir[$directory] = true;
            }
        }
        
        // rebuild config dir index
        if ($_config) {
            $this->_config_dir = $this->smarty->config_dir;
            foreach ((array)$this->smarty->config_dir as $directory) {
                $directory = realpath($directory);
                $this->_resource_dir[$directory] = true;
            }
        }
        
        // rebuild secure dir index
        if ($_secure) {
            $this->_secure_dir = $this->secure_dir;
            foreach ((array)$this->secure_dir as $directory) {
                $directory = realpath($directory);
                $this->_resource_dir[$directory] = true;
            }
        }
        
        $_filepath = realpath($filepath);
        $directory = dirname($_filepath);
        $_directory = array();
        while (true) {
            // remember the directory to add it to _resource_dir in case we're successful
            $_directory[] = $directory;
            // test if the directory is trusted
            if (isset($this->_resource_dir[$directory])) {
                // merge sub directories of current $directory into _resource_dir to speed up subsequent lookups
                $this->_resource_dir = array_merge($this->_resource_dir, $_directory);
                return true;
            }
            // abort if we've reached root
            if (($pos = strrpos($directory, DS)) === false || strlen($directory) < 2) {
                break;
            }
            // bubble up one level
            $directory = substr($directory, 0, $pos);
        }
        
        // give up
        throw new SmartyException ("directory '{$_filepath}' not allowed by security setting");
    } 
    
    /**
     * Check if directory of file resource is trusted.
     * 
     * @param string $filepath 
     * @param object $compiler compiler object
     * @return boolean true if directory is trusted
     * @throws SmartyException if PHP directory is not trusted
     */
    function isTrustedPHPDir($filepath)
    {
        if (empty($this->trusted_dir)) {
            throw new SmartyException ("directory '{$filepath}' not allowed by security setting (no trusted_dir specified)");
        }
        
        // check if index is outdated
        if (!$this->_trusted_dir || $this->_trusted_dir !== $this->trusted_dir) {
            $this->_php_resource_dir = array();

            $this->_trusted_dir = $this->trusted_dir;
            foreach ((array)$this->trusted_dir as $directory) {
                $directory = realpath($directory);
                $this->_php_resource_dir[$directory] = true;
            }
        }
        
        $_filepath = realpath($filepath);
        $directory = dirname($_filepath);
        $_directory = array();
        while (true) {
            // remember the directory to add it to _resource_dir in case we're successful
            $_directory[] = $directory;
            // test if the directory is trusted
            if (isset($this->_php_resource_dir[$directory])) {
                // merge sub directories of current $directory into _resource_dir to speed up subsequent lookups
                $this->_php_resource_dir = array_merge($this->_php_resource_dir, $_directory);
                return true;
            }
            // abort if we've reached root
            if (($pos = strrpos($directory, DS)) === false || strlen($directory) < 2) {
                break;
            }
            // bubble up one level
            $directory = substr($directory, 0, $pos);
        }

        throw new SmartyException ("directory '{$_filepath}' not allowed by security setting");
    } 
} 

?>