<?php
namespace Tests\Mocks;

/**
 * Mock Bucket.
 */
class MockBucket extends \Alpha\Storage\BucketAbstract
{
    /**
     * Returns the mock bucket schema.
     * 
     * @return array
     */
    public function getSchema()
    {
        return array();
    }
}

