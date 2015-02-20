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
        return array(
            'bucket' => 'user',
            'key'    => 'id',
            'fields' => array(
                'id' => array('type' => 'integer'),
                'name' => array('type' => 'string'),
            )
        );
    }
}

