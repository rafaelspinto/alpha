<?php
/**
 * Connectors
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

/**
 * Class that manages connectors.
 */
class Connectors
{
    // singleton 
    static $instance;
    
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
    
    /**
     * Returns the instance of the Connectors.
     * 
     * @return \Alpha\Connector\Connectors
     */
    protected static function getInstance()
    {
        if(static::$instance == null) {
            return static::$instance = new Connectors();
        }
        
        return static::$instance;
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
        $connectorsFile = PATH_PROJECT . 'connectors.ini';
        if(file_exists($connectorsFile)) {
            $connectors = parse_ini_file($connectorsFile, true);
            foreach($connectors as $name => $connectorProperties){
                $this->repository[$name]['properties'] = $connectorProperties;               
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
        $this->registerConnector('Repo', 'Alpha\Storage\MySQLRepository');
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
        if(method_exists($instance, 'setup')) {
            $instance->setup($connector['properties']);
        }
        return $instance;
    }
}
