<?php
/**
 * ConnectorHandler
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Handler;

use Alpha\Core\Autoloader;
use Alpha\Connector\ConnectorInterface;
use Alpha\Exception\ConnectorNotFoundException;
use Alpha\Exception\InterfaceNotImplementedException;

/**
 * Class that handles connectors.
 */
class ConnectorHandler
{
    protected $connectorsPath, $plugsPath, $repository;

    /**
     * Constructs a ConnectorHandler.
     * 
     * @param string $connectorsPath The path where the connectors are located.
     * @param string $plugsPath      The path where the plug files are located.
     */
    public function __construct($connectorsPath, $plugsPath)
    {
        $this->connectorsPath = $connectorsPath;
        $this->plugsPath      = $plugsPath;
        $this->repository     = array();
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
    public function getConnector($name)
    {
        if(!array_key_exists($name, $this->repository)) {
            throw new ConnectorNotFoundException($name);
        }
        if($this->repository[$name]['instance'] == null) {
            $this->repository[$name]['instance'] = $this->initConnector($this->repository[$name]);
        }
        return $this->repository[$name]['instance'];
    }
    
    /**
     * Registers a connector in the Connectors repository.
     * 
     * @param string $name          The name of the connector identifier.
     * @param string $className     The fully qualified class name.
     * @param array  $configuration The array containing the configuration.
     * 
     * @return void
     */
    public function registerConnector($name, $className, array $configuration)
    {
        $this->repository[$name]['className']  = $className;
        $this->repository[$name]['properties'] = $configuration;
        $this->repository[$name]['instance']   = null;
    }
    
    /**
     * Returns the namespace for a connector.
     * 
     * @param string $connector The name of the Connector.
     * 
     * @return string
     */
    public function getNamespaceForConnector($connector)
    {
        return Autoloader::getNamespaceFromDirectory($this->connectorsPath . strtolower($connector) . DIRECTORY_SEPARATOR);
    }
    
    /**
     * Initializes the connectors in the connectors.ini file.
     * 
     * @return void
     */
    protected function init()
    {
        if(file_exists($this->connectorsPath)) {
            $directoryIterator = new \DirectoryIterator($this->plugsPath);
            foreach($directoryIterator as $file){
                if($file->isFile() && strpos($file->getExtension(), 'plug')!== false) {
                    $connector          = str_replace('.plug', '', $file->getFilename());
                    $connectorNamespace = $this->getNamespaceForConnector($connector);
                    $className          = $connectorNamespace . $connector . 'Connector';
                    $configuration      = parse_ini_file($file->getRealPath(), true);
                    $name               = isset($configuration['target']['name']) ? $configuration['target']['name'] : $connector;
                    $this->registerConnector($name, $className, $configuration);                    
                }
            }
        }
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
        
        if (!$instance instanceof ConnectorInterface) {
            throw new InterfaceNotImplementedException($className, 'ConnectorInterface');
        }

        if(isset($connector['properties'])) {                
            $instance->setup($connector['properties']);
        }
        return $instance;
    }
}