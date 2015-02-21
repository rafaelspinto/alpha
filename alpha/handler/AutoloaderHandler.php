<?php
/**
 * AutoloaderHandler
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Handler;

/**
 * Class Autoloader for Alpha Framework.
 */
class AutoloaderHandler
{
    protected $rootDirectory;
    
    /**
     * Constructs an AutoloaderHandler. 
     * 
     * @param string $rootDirectory The root directory.
     */
    public function __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
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
        $file = static::getNameOfFileFromClassName($className);
        
        if(!file_exists($file)) {
            return false;
        }
        
        include $file;
        return true;
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
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);        
        $file      = DIRECTORY_SEPARATOR . $className . '.php';
        $dir       = strtolower(dirname($file));
        $file      = basename($file);
        $file      = $this->rootDirectory . $dir . DIRECTORY_SEPARATOR . $file;
        return $file;
    }
    
    /**
     * Returns the namespace from a directory.
     * 
     * @param string $directory The full path of the directory.
     * 
     * @return string
     */
    public function getNamespaceFromDirectory($directory)
    {
        $directory = str_replace($this->rootDirectory, '', $directory);
        $dirTree   = explode(DIRECTORY_SEPARATOR, $directory);
        array_walk($dirTree,
                function(&$a) {
                    $underData = explode('_', $a);
                    array_walk($underData, function(&$b){ $b = ucfirst($b); });
                    $a = ucfirst(implode('_', $underData));
                }
        );
        return implode('\\', $dirTree);
    }
}