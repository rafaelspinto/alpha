<?php
/**
 * BucketFieldsEmptyException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when bucket fields definition is empty.
 */
class BucketFieldsEmptyException extends \Exception
{
    /**
     * Constructs a BucketFieldsEmptyException.
     * 
     * @param string $className The name of the bucket class.
     */
    public function __construct($className)
    {
        parent::__construct('bucket_fields_empty:'.$className, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}