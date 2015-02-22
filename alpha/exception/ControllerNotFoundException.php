<?php
/**
 * ControllerNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a controller is not found.
 */
class ControllerNotFoundException extends \Exception
{
    /**
     * Constructs a ControllerNotFoundException.
     * 
     * @param string $name The name of the controller.
     */
    public function __construct($name)
    {
        parent::__construct('controller_not_found:'.$name, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

