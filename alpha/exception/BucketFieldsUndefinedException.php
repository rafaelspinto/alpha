<?php
/**
 * BucketFieldsUndefinedException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when bucket fields are not defined.
 */
class BucketFieldsUndefinedException extends \Exception
{
    /**
     * Constructs a BucketFieldsUndefinedException.
     * 
     * @param string $className The name of the bucket class.
     */
    public function __construct($className)
    {
        parent::__construct('bucket_fields_undefined:'.$className, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}