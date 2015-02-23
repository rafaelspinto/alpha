<?php
/**
 * BucketInterface
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Storage;

interface BucketInterface
{
    /**
     * Returns the array containing the schema.
     * 
     * @return array
     */
    function getSchema();
    
    /**
     * Creates an entry in the bucket and returns its key. 
     * 
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return int
     */
    function create(array $fieldsAndValues);
    
    /**
     * Updates an entry in the bucket.
     * 
     * @param array $fieldsAndValues The array containing the fields and values to update.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    function update(array $fieldsAndValues);
    
    /**
     * Deletes the entry with the given key.
     * 
     * @param mixed $key The key of the entry.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    function delete($key);
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $fieldsAndValues The array containing the fields and values to search.
     * 
     * @return MySQLResultIterator
     */
    function find(array $fieldsAndValues = array());
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $key The key of the entry.
     * 
     * @return MySQLResultIterator
     */
    function findByKey($key);
    
    /**
     * Validates schema definition and returns schema if is valid.
     * 
     * @param array $schema The schema.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    static function validateSchemaDefinition(array $schema);
}
