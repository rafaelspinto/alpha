<?php
/**
 * ConfigurationHandler
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Handler;

/**
 * Class for handling configuration.
 */
class ConfigurationHandler
{
    protected $rootDirectory, $configuration, $configurationFile, $loadedConfig;
    
    /**
     * Constructs a ConfigurationHandler.
     * 
     * @param string $configurationFile The full path of the configuration file.
     */
    public function __construct($configurationFile)
    {
        $this->configurationFile = $configurationFile;
    }
    
    /**
     * Returns the configuration for the section and key,
     * 
     * @param string $section The section of the configuration.
     * @param string $key     The key of the property.
     * 
     * @return string|array|null
     */
    public function get($section = null, $key = null)
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
    public function set($section, $key, $value)
    {
        $this->configuration[$section][$key] = $value;
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
        $config              = parse_ini_file($this->configurationFile, true);
        $this->configuration = array_merge($this->configuration, $config);
    }    
}