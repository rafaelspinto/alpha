<?php
/**
 * BucketAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Storage;

use Alpha\Core\Connectors;

/**
 * Base class for Buckets.
 */
abstract class BucketAbstract
{
    /**
     * Returns the array containing the schema.
     * 
     * @return array
     */
    abstract static function getSchema();

    /**
     * Creates an entry in the bucket and returns its key. 
     * 
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return int
     */
    public static function create(array $fieldsAndValues)
    {
        return Connectors::get('Repo')->create(static::getSchema(), $fieldsAndValues);
    }
    
    /**
     * Updates an entry in the bucket.
     * 
     * @param array $fieldsAndValues The array containing the fields and values to update.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    public static function update(array $fieldsAndValues)
    {        
        return Connectors::get('Repo')->update(static::getSchema(), $fieldsAndValues);
    }
    
    /**
     * Deletes the entry with the given key.
     * 
     * @param mixed $key The key of the entry.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    public static function delete($key)
    {
        return Connectors::get('Repo')->delete(static::getSchema(), $key);
    }
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $fieldsAndValues The array containing the fields and values to search.
     * 
     * @return MySQLResultIterator
     */
    public static function find(array $fieldsAndValues = array())
    {
        return Connectors::get('Repo')->findByFields(static::getSchema(), $fieldsAndValues);
    }
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $key The key of the entry.
     * 
     * @return MySQLResultIterator
     */
    public static function findByKey($key)
    {
        return Connectors::get('Repo')->findByKey(static::getSchema(), $key);
    }
    
    /**
     * Validates schema definition and returns schema if is valid.
     * 
     * @param array $schema The schema.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public static function validateSchemaDefinition(array $schema)
    {        
        if(!isset($schema['bucket'])) {
            throw new \Exception('bucket_undefined:'.get_called_class());
        }
        
        if(!isset($schema['key'])) {
            throw new \Exception('key_undefined:'.get_called_class());
        }
        
        if(!isset($schema['fields'])) {
            throw new \Exception('fields_undefined:'.get_called_class());
        }
                
        if(empty($schema['fields'])) {
            throw new \Exception('empty_fields:'.get_called_class());
        }
        
        foreach($schema['fields'] as $k => $field) {
            if(!isset($field['type'])) {
                throw new \Exception('field_type_undefined:'.$k);
            }
        }
        
        return $schema;        
    }
}
