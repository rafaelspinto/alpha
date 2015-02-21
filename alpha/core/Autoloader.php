<?php
/**
 * Autoloader
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Singleton\SingletonAbstract;
use Alpha\Handler\AutoloaderHandler;

require_once PATH_ROOT . DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'handler' . DIRECTORY_SEPARATOR . 'AutoloaderHandler.php';
require_once PATH_ROOT . DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'singleton' . DIRECTORY_SEPARATOR . 'SingletonFactoryInterface.php';
require_once PATH_ROOT . DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'singleton' . DIRECTORY_SEPARATOR . 'SingletonAbstract.php';

spl_autoload_register(function($className){
    return Autoloader::load($className);
});

/**
 * Class Autoloader for Alpha Framework.
 */
class Autoloader extends SingletonAbstract
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
        return static::getInstance()->load($className);
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
        return static::getInstance()->getNameOfFileFromClassName($className);
    }
    
    /**
     * Returns the namespace from a directory.
     * 
     * @param string $directory The full path of the directory.
     * 
     * @return string
     */
    public static function getNamespaceFromDirectory($directory)
    {
        return static::getInstance()->getNamespaceFromDirectory($directory);
    }

    /**
     * Returns the AutoloaderHandler instance that should be used as a singleton.
     * 
     * @return static
     */
    public static function make()
    {        
        return new AutoloaderHandler(PATH_ROOT);
    }
}