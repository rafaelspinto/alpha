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
        return static::getInstance()->get($section, $key);
    }
    
    /**
     * Sets the configuration for the section and key,
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * @param mixed  $value   The value.
     * 
     * @return void
     */
    public static function set($section, $key, $value)
    {
        static::getInstance()->set($section, $key, $value);
    }
    
    /**
     * Returns the root path.
     * 
     * @return string
     */
    public static function getRootPath()
    {
        return static::getInstance()->get('path', 'root');
    }
    
    /**
     * Returns the project path.
     * 
     * @return string
     */
    public static function getProjectPath()
    {
        return static::getInstance()->get('path', 'project');
    }
    
    /**
     * Returns the controllers path.
     * 
     * @return string
     */
    public static function getControllersPath()
    {
        return static::getInstance()->get('path', 'controllers');
    }
    
    /**
     * Returns the views path.
     * 
     * @return string
     */
    public static function getViewsPath()
    {
        return static::getInstance()->get('path', 'views');
    }
    
    /**
     * Returns the models path.
     * 
     * @return string
     */
    public static function getModelsPath()
    {
        return static::getInstance()->get('path', 'models');
    }
    
    /**
     * Returns the connectors path.
     * 
     * @return string
     */
    public static function getConnectorsPath()
    {
        return static::getInstance()->get('path', 'connectors');
    }
    
    /**
     * Returns the connectors path.
     * 
     * @return string
     */
    public static function getPlugsPath()
    {
        return static::getInstance()->get('path', 'plugs');
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
        $configurationHandler->set('path', 'root', PATH_ROOT);
        $configurationHandler->set('path', 'project', $projectPath);
        $configurationHandler->set('path', 'models', $projectPath . 'models' . DIRECTORY_SEPARATOR);
        $configurationHandler->set('path', 'views', $projectPath . 'views' . DIRECTORY_SEPARATOR);
        $configurationHandler->set('path', 'controllers', $projectPath . 'controllers' . DIRECTORY_SEPARATOR);
        $configurationHandler->set('path', 'connectors', PATH_ROOT . DIRECTORY_SEPARATOR . 'connectors' . DIRECTORY_SEPARATOR); 
        $configurationHandler->set('path', 'plugs', $projectPath . 'plugs' . DIRECTORY_SEPARATOR); 
        return $configurationHandler;
    }
}