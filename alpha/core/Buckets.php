<?php
/**
 * Buckets
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Autoloader;
use Alpha\Exception\BucketNotFoundException;

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
        $filename = Config::getModelsPath() . $bucket . '.php';
        if(!file_exists($filename)) {
            throw new BucketNotFoundException($bucket);
        }
        $className = Autoloader::getNamespaceFromDirectory(Config::getModelsPath()) . $bucket;
        return new $className;
    }
}
