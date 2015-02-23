<?php
/**
 * ComponentNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a component is not found.
 */
class ComponentNotFoundException extends \Exception
{
    /**
     * Constructs a ComponentNotFoundException.
     * 
     * @param string $component The name of the component.
     */
    public function __construct($component)
    {
        parent::__construct('component_not_found:'.$component, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

