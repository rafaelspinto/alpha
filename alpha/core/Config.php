<?php
/**
 * Config
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

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
        return static::getInstance()->getPath('root');
    }
    
    /**
     * Returns the project path.
     * 
     * @return string
     */
    public static function getProjectPath()
    {
        return static::getInstance()->getPath('project');
    }
    
    /**
     * Returns the controllers path.
     * 
     * @return string
     */
    public static function getControllersPath()
    {
        return static::getInstance()->getPath('controllers');
    }
    
    /**
     * Returns the views path.
     * 
     * @return string
     */
    public static function getViewsPath()
    {
        return static::getInstance()->getPath('views');
    }
    
    /**
     * Returns the models path.
     * 
     * @return string
     */
    public static function getModelsPath()
    {
        return static::getInstance()->getPath('models');
    }
    
    /**
     * Returns the connectors path.
     * 
     * @return string
     */
    public static function getConnectorsPath()
    {
        return static::getInstance()->getPath('connectors');
    }
    
    // instance
    protected $configuration, $configurationFile, $loadedConfig;
    
    /**
     * Constructs a Config.
     */
    public function __construct()
    {
        $this->configuration = array();
        $this->loadedConfig  = false;
        $this->initPaths();        
    }
    
    /**
     * Returns the configuration for the section and key,
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * 
     * @return string|array|null
     */
    public function getConfig($section = null, $key = null)
    {
        if(!$this->loadedConfig) {
            $this->load();
        }
        
        if($section == null) {
            return $this->configuration;
        }
        
        if($key == null) {
            return isset($this->configuration[$section]) ? $this->configuration[$section] : null;
        }
        
        return isset($this->configuration[$section][$key]) ? $this->configuration[$section][$key] : null;
    }
    
    /**
     * Sets the configuration of the section of the key.
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * @param mixed  $value   The value of the property.
     * 
     * @return void
     */
    public function setConfig($section, $key, $value)
    {
        $this->configuration[$section][$key] = $value;
    }
    
    /**
     * Returns the path.
     * 
     * @param string $key The key of the path.
     * 
     * @return string
     */
    public function getPath($key)
    {
        return $this->configuration['path'][$key];
    }
    
    /**
     * Sets the path.
     * 
     * @param string $key   The key of the path.
     * @param string $value The value of the path.
     * 
     * @return void
     */
    public function setPath($key, $value)
    {
        $this->configuration['path'][$key] = $value;
    }
    
    /**
     * Initializes the configuration data.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    protected function load()
    {
        if(!file_exists($this->configurationFile)) {
            throw new \Exception('configuration_file_does_not_exist:'.$this->configurationFile);
        }
        $this->configuration = parse_ini_file($this->configurationFile, true);
    }
    
    /**
     * Initializes the paths.
     * 
     * @return void
     */
    protected function initPaths()
    {
        $projectPath             = PATH_ROOT . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR;
        $this->configurationFile = $projectPath . 'configuration.ini';
        $this->setPath('root', PATH_ROOT);
        $this->setPath('project', $projectPath);
        $this->setPath('models', $projectPath . 'models' . DIRECTORY_SEPARATOR);
        $this->setPath('views', $projectPath . 'views' . DIRECTORY_SEPARATOR);
        $this->setPath('controllers', $projectPath . 'controllers' . DIRECTORY_SEPARATOR);
        $this->setPath('connectors', $projectPath . 'connectors' . DIRECTORY_SEPARATOR);
    }
}

