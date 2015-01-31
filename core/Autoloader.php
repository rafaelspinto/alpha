<?php
/**
 * Autoloader
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */

/**
 * Simple Autoloader for classes where the namespace is camelcased and
 * the filesystem structure is lowercased except the name of the file.
 * 
 * e.g. MyProject\Path\Class
 */
class Autoloader
{
    protected $projectName, $rootDirectory, $projectNameIsFolderInRootDirectory;

    /**
     * Constructs an Autoloader.
     * 
     * @param string $projectName   The name of the project.
     * @param string $rootDirectory The root directory.
     */
    public function __construct($projectName, $rootDirectory)
    {
        $this->projectName                        = $projectName;
        $this->rootDirectory                      = $rootDirectory;
        $this->projectNameIsFolderInRootDirectory = file_exists($this->rootDirectory . DIRECTORY_SEPARATOR . strtolower($this->projectName));
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
        if (!$this->projectNameIsFolderInRootDirectory) {
            $className = preg_replace('#^' . $this->projectName . '#', '', $className);
        }

        $className = str_replace('\\', '/', $className);        
        $file      = $this->rootDirectory . DIRECTORY_SEPARATOR . $className . '.php';
        $dir       = strtolower(dirname($file));
        $file      = basename($file);
        $file      = $dir . DIRECTORY_SEPARATOR . $file;
        
        return $file;
    }
}
