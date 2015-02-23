<?php
/**
 * BucketNotFoundException
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Exception;

use Alpha\Http\StatusCode;

/**
 * Exception to be thrown when a bucket is not found.
 */
class BucketNotFoundException extends \Exception
{
    /**
     * Constructs a BucketNotFoundException.
     * 
     * @param string $bucket The name of the bucket.
     */
    public function __construct($bucket)
    {
        parent::__construct('bucket_not_found:'.$bucket, StatusCode::NOT_FOUND, parent::getPrevious());
    }
}

