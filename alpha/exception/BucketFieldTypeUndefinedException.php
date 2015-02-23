<?php
/**
 * BucketFieldTypeUndefinedException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when bucket field type is undefined.
 */
class BucketFieldTypeUndefinedException extends \Exception
{
    /**
     * Constructs a BucketFieldTypeUndefinedException.
     * 
     * @param string $name The name of the field.
     */
    public function __construct($name)
    {
        parent::__construct('bucket_fields_empty:'.$name, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}