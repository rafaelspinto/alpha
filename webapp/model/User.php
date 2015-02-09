<?php
namespace Webapp\Model;

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
                array('id' => array('type' => 'integer')),
                array('name' => array('type' => 'string')),
            )
        );
    }
}

