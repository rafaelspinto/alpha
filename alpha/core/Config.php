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
        return static::getInstance()->getConfiguration($section, $key);
    }
    
    // instance
    protected $configuration, $configurationFile, $loadedConfig;
    
    /**
     * Constructs a Config.
     */
    public function __construct()
    {
        $this->configuration     = array();
        $this->configurationFile = PATH_PROJECT . 'configuration.ini';
        $this->loadedConfig      = false;
    }
    
    /**
     * Returns the configuration for the section and key,
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * 
     * @return string|array|null
     */
    public function getConfiguration($section = null, $key = null)
    {
        if(!$this->loadedConfig) {
            $this->init();
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
     * Initializes the configuration data.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    protected function init()
    {
        if(!file_exists($this->configurationFile)) {
            throw new \Exception('configuration_file_does_not_exist:'.$this->configurationFile);
        }
        $this->configuration = parse_ini_file($this->configurationFile, true);
    }
}

