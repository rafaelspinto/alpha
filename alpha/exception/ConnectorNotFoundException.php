<?php
/**
 * ConnectorNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a connector is not found.
 */
class ConnectorNotFoundException extends \Exception
{
    /**
     * Constructs a ConnectorNotFoundException.
     * 
     * @param string $connector The name of the connector.
     */
    public function __construct($connector)
    {
        parent::__construct('connector_not_found:'.$connector, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

