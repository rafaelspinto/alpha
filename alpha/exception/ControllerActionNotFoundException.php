<?php
/**
 * ControllerActionNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a controller is not found.
 */
class ControllerActionNotFoundException extends \Exception
{
    /**
     * Constructs a ControllerActionNotFoundException.
     * 
     * @param string $name The name of the action.
     */
    public function __construct($name)
    {
        parent::__construct('controller_action_not_found:'.$name, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

