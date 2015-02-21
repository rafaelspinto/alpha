<?php
/**
 * Config
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Singleton\SingletonAbstract;
use Alpha\Handler\ConfigurationHandler;

/**
 * Class for handling configuration.
 */
class Config extends SingletonAbstract
{
    /**
     * Returns the configuration for the section and key,
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * 
     * @return string|array|null
     */
    public static function get($section = null, $key = null)
    {
        return static::getInstance()->getConfig($section, $key);
    }
    
    /**
     * Returns the root path.
     * 
     * @return string
     */
    public static function getRootPath()
    {
        return static::getInstance()->getConfig('path', 'root');
    }
    
    /**
     * Returns the project path.
     * 
     * @return string
     */
    public static function getProjectPath()
    {
        return static::getInstance()->getConfig('path', 'project');
    }
    
    /**
     * Returns the controllers path.
     * 
     * @return string
     */
    public static function getControllersPath()
    {
        return static::getInstance()->getConfig('path', 'controllers');
    }
    
    /**
     * Returns the views path.
     * 
     * @return string
     */
    public static function getViewsPath()
    {
        return static::getInstance()->getConfig('path', 'views');
    }
    
    /**
     * Returns the models path.
     * 
     * @return string
     */
    public static function getModelsPath()
    {
        return static::getInstance()->getConfig('path', 'models');
    }
    
    /**
     * Returns the connectors path.
     * 
     * @return string
     */
    public static function getConnectorsPath()
    {
        return static::getInstance()->getConfig('path', 'connectors');
    }
    
    /**
     * Returns the connectors path.
     * 
     * @return string
     */
    public static function getPlugsPath()
    {
        return static::getInstance()->getConfig('path', 'plugs');
    }

    /**
     * Returns the ConfigurationHandler instance that should be used as a singleton.
     * 
     * @return static
     */
    public static function make()
    {
        $projectPath          = PATH_ROOT . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR;
        $configurationFile    = $projectPath . 'configuration.ini';
        $configurationHandler = new ConfigurationHandler($configurationFile);        
        $configurationHandler->setConfig('path', 'root', PATH_ROOT);
        $configurationHandler->setConfig('path', 'project', $projectPath);
        $configurationHandler->setConfig('path', 'models', $projectPath . 'models' . DIRECTORY_SEPARATOR);
        $configurationHandler->setConfig('path', 'views', $projectPath . 'views' . DIRECTORY_SEPARATOR);
        $configurationHandler->setConfig('path', 'controllers', $projectPath . 'controllers' . DIRECTORY_SEPARATOR);
        $configurationHandler->setConfig('path', 'connectors', PATH_ROOT . DIRECTORY_SEPARATOR . 'connectors' . DIRECTORY_SEPARATOR); 
        $configurationHandler->setConfig('path', 'plugs', $projectPath . 'plugs' . DIRECTORY_SEPARATOR); 
        return $configurationHandler;
    }
}