<?php
/**
 * Connectors
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Connector;

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
    public function getConnector($name)
    {
        if(!array_key_exists($name, $this->repository)) {
            throw new \Exception('instance_not_found:'.$name);
        }
        
        return $this->repository[$name];
    }
    
    /**
     * Initializes the default connectors.
     * 
     * @return void
     */
    protected function initDefaults()
    {
        $this->repository['View'] = new \Alpha\Web\View();
    }
}
