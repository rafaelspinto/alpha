<?php
/**
 * BucketKeyUndefinedException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a bucket key is not defined.
 */
class BucketKeyUndefinedException extends \Exception
{
    /**
     * Constructs a BucketKeyUndefinedException.
     * 
     * @param string $className The name of the bucket class.
     */
    public function __construct($className)
    {
        parent::__construct('bucket_key_undefined:'.$className, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}