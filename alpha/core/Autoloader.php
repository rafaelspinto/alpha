<?php
/**
 * Autoloader
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

spl_autoload_register(array('\Alpha\Core\Autoloader', 'load'));

/**
 * Class Autoloader for Alpha Framework.
 */
class Autoloader
{
    /**
     * Includes the specified class.
     *            
     * @param string $className The name of the class (full path).
     * 
     * @return void
     */
    public static function load($className)
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
    public static function getNameOfFileFromClassName($className)
    {       
        $className = str_replace('\\', '/', $className);        
        $file      = PATH_ROOT . DIRECTORY_SEPARATOR . $className . '.php';
        $dir       = strtolower(dirname($file));
        $file      = basename($file);
        $file      = $dir . DIRECTORY_SEPARATOR . $file;
        return $file;
    }
}
