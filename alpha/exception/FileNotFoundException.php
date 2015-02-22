<?php
/**
 * FileNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a file is not found.
 */
class FileNotFoundException extends \Exception
{
    /**
     * Constructs a FileNotFoundException.
     * 
     * @param string $name The name of the file.
     */
    public function __construct($name)
    {
        parent::__construct('file_not_found:'.$name, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

