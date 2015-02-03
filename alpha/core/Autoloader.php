<?php
/**
 * Autoloader
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

/**
 * Class Autoloader for Alpha Framework.
 */
class Autoloader
{
    protected $rootDirectory;

    /**
     * Constructs an Autoloader.
     * 
     * @param string $rootDirectory The root directory of the Framework.    
     */
    public function __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }
    
    /**
     * Register this as the class Autoloader.
     * 
     * @return boolean
     */
    public function register()
    {
        return spl_autoload_register(array($this, 'load'));
    }

    /**
     * Includes the specified class.
     *            
     * @param string $className The name of the class (full path).
     * 
     * @return void
     */
    public function load($className)
    {      
        $file = $this->getNameOfFileFromClassName($className);
        
        if(!file_exists($file)) {
            return false;
        }
        
        include $file;
    }
    
    /**
     * Returns the name of the filename from a fully qualified class name.
     * 
     * @param string $className The name of the class (full path).
     * 
     * @return string
     */
    public function getNameOfFileFromClassName($className)
    {       
        $className = str_replace('\\', '/', $className);        
        $file      = $this->rootDirectory . DIRECTORY_SEPARATOR . $className . '.php';
        $dir       = strtolower(dirname($file));
        $file      = basename($file);
        $file      = $dir . DIRECTORY_SEPARATOR . $file;
        return $file;
    }
}
