<?php
/**
 * UnknownTypeException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a type is unknown.
 */
class UnknownTypeException extends \Exception
{
    /**
     * Constructs an UnknownTypeException.
     * 
     * @param string $name The name of the file.
     */
    public function __construct($name)
    {
        parent::__construct('type_unknown:'.$name, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

