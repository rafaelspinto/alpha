<?php
/**
 * Buckets
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Autoloader;

/**
 * Class that handles buckets.
 */
class Buckets
{
    /**
     * Returns the given Bucket.
     * 
     * @param string $bucket The name of the bucket.
     * 
     * @return \Alpha\Storage\BucketAbstract
     */
    public static function get($bucket)
    {
        $className = "Webapp\Models\\" .$bucket;
        $filename  = Autoloader::getNameOfFileFromClassName($className);
        if(!file_exists($filename)) {
            throw new \Exception('bucket_does_not_exist:'.$bucket);
        }
        return new $className;
    }
}
