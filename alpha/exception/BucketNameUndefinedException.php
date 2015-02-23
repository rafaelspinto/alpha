<?php
/**
 * BucketNameUndefinedException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a bucket name is not defined.
 */
class BucketNameUndefinedException extends \Exception
{
    /**
     * Constructs a BucketNameUndefinedException.
     * 
     * @param string $className The name of the bucket class.
     */
    public function __construct($className)
    {
        parent::__construct('bucket_name_undefined:'.$className, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}