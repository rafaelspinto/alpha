<?php
/**
 * Connectors
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Config;

/**
 * Class that manages connectors.
 */
class Connectors extends SingletonAbstract
{
    /**
     * Returns the instance of the Connector.
     * 
     * @param string $name The name of the Connector.
     * 
     * @return object
     */
    public static function get($name)
    {
        return static::getInstance()->getConnector($name);
    }
    
    // instance
    protected $repository;

    /**
     * Constructs a Connectors.
     */
    public function __construct()
    {
        $this->repository = array();
        $this->initDefaults();
        $this->init();
    }

    /**
     * Returns the instance of the registered Connector.
     * 
     * @param string $name The name of the Connector.
     * 
     * @return object
     * 
     * @throws \Exception
     */
    protected function getConnector($name)
    {
        if(!array_key_exists($name, $this->repository)) {
            throw new \Exception('instance_not_found:'.$name);
        }
        if($this->repository[$name]['instance'] == null) {
            $this->repository[$name]['instance'] = $this->initConnector($this->repository[$name]);
        }
        return $this->repository[$name]['instance'];
    }
    
    /**
     * Registers a connector in the Connectors repository.
     * 
     * @param string $name      The name of the connector identifier.
     * @param string $className The fully qualified class name.
     * 
     * @return void
     */
    public function registerConnector($name, $className)
    {
        $this->repository[$name]['className'] = $className;
        $this->repository[$name]['instance']  = null;
    }
    
    /**
     * Initializes the connectors in the connectors.ini file.
     * 
     * @return void
     */
    protected function init()
    {
        $connectorsPath = Config::getConnectorsPath();
        if(file_exists($connectorsPath)) {
            $directoryIterator = new \DirectoryIterator($connectorsPath);
            foreach($directoryIterator as $file){
                if($file->isFile() && strpos($file->getExtension(), 'plug')!== false) {
                    $connector                             = str_replace('.plug', '', $file->getFilename());
                    $classname                             = 'Alpha\\Connectors\\' . $connector . 'Connector';
                    $configuration                         = parse_ini_file($file->getRealPath(), true);
                    $name                                  = isset($configuration['target']['name']) ? $configuration['target']['name'] : $connector;
                    $this->repository[$name]['className']  = $classname;
                    $this->repository[$name]['properties'] = $configuration;
                    $this->repository[$name]['instance']   = null;                    
                }
            }
        }
    }

    /**
     * Initializes the default connectors.
     * 
     * @return void
     */
    protected function initDefaults()
    {
        $this->registerConnector('View', 'Alpha\Web\View');
        $this->registerConnector('Language', 'Alpha\Language\LanguageRepository');
    }
    
    /**
     * Initializes a connector.
     * 
     * @param array $connector The array containing the connector setup.
     * 
     * @return object
     */
    protected function initConnector(array $connector)
    {
        $className = $connector['className'];
        $instance  = new $className();
        if(method_exists($instance, 'setup') && isset($connector['properties'])) {                
            $instance->setup($connector['properties']);
        }
        return $instance;
    }
}
