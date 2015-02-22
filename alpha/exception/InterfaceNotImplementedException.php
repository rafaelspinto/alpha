<?php
/**
 * InterfaceNotImplementedException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when an interface is not implemented by an instance.
 */
class InterfaceNotImplementedException extends \Exception
{
    /**
     * Constructs an InterfaceNotImplementedException.
     * 
     * @param string $instance  The name of the instance.
     * @param string $interface The name of the interface.
     */
    public function __construct($instance, $interface)
    {
        parent::__construct('interface_not_implemented:'.$instance .':'. $interface, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

