<?php
namespace Webapp\Models;

class User extends \Alpha\Storage\BucketAbstract
{
    /**
     * Returns the array containing the schema.
     * 
     * @return array
     */
    public static function getSchema()
    {
        return [
                'bucket' => 'user',
                'key'    => 'id',
                'fields' => [ 'id'   => ['type' => 'integer'],
                              'name' => ['type' => 'string'],
                ]
        ];
    }
}

