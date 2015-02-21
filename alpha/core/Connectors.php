<?php
/**
 * Connectors
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Config;
use Alpha\Handler\ConnectorHandler;
use Alpha\Singleton\SingletonAbstract;

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

    /**
     * Returns the ConnectorHandler instance that should be used as a singleton.
     * 
     * @return static
     */
    public static function make()
    {
        return new ConnectorHandler(Config::getConnectorsPath(), Config::getPlugsPath());
    }
}
