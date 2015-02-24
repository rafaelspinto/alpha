<?php
/**
 * BucketAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Storage;

use Alpha\Storage\BucketInterface;
use Alpha\Connector\RepositoryConnectorInterface;
use Alpha\Exception\BucketNameUndefinedException;
use Alpha\Exception\BucketKeyUndefinedException;
use Alpha\Exception\BucketFieldsUndefinedException;
use Alpha\Exception\BucketFieldsEmptyException;
use Alpha\Exception\BucketFieldTypeUndefinedException;

/**
 * Base class for Buckets.
 */
abstract class BucketAbstract implements BucketInterface
{
    /**
     * @var \Alpha\Connector\RepositoryConnectorInterface
     */
    protected $repository;

    /**
     * Constructs a BucketAbstract.
     * 
     * @param \Alpha\Connector\RepositoryConnectorInterface $repository The repository.
     */
    public function __construct(RepositoryConnectorInterface $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Creates an entry in the bucket and returns its key. 
     * 
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return int
     * 
     * @throws \Exception
     */
    public function create(array $fieldsAndValues)
    {
        return $this->repository->create(static::getSchema(), $fieldsAndValues);
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
    public function update(array $fieldsAndValues)
    {        
        return $this->repository->update(static::getSchema(), $fieldsAndValues);
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
    public function delete($key)
    {
        return $this->repository->delete(static::getSchema(), $key);
    }
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $fieldsAndValues The array containing the fields and values to search.
     * 
     * @return MySQLResultIterator
     * 
     * @throws \Exception
     */
    public function find(array $fieldsAndValues = array())
    {
        return $this->repository->findByFields(static::getSchema(), $fieldsAndValues);
    }
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param mixed $key The key of the entry.
     * 
     * @return MySQLResultIterator
     * 
     * @throws \Exception
     */
    public function findByKey($key)
    {
        return $this->repository->findByKey(static::getSchema(), $key);
    }
    
    /**
     * Validates schema definition and returns schema if is valid.
     * 
     * @param array $schema The schema.
     * 
     * @return array
     * 
     * 
     * @throws BucketNameUndefinedException
     * @throws BucketKeyUndefinedException
     * @throws BucketFieldsUndefinedException
     * @throws BucketFieldsEmptyException
     * @throws BucketFieldTypeUndefinedException
     */
    public static function validateSchemaDefinition(array $schema)
    {        
        if(!isset($schema['bucket'])) {
            throw new BucketNameUndefinedException(get_called_class());
        }
        
        if(!isset($schema['key'])) {
            throw new BucketKeyUndefinedException(get_called_class());
        }
        
        if(!isset($schema['fields'])) {
            throw new BucketFieldsUndefinedException(get_called_class());
        }
                
        if(empty($schema['fields'])) {
            throw new BucketFieldsEmptyException(get_called_class());
        }
        
        foreach($schema['fields'] as $k => $field) {
            if(!isset($field['type'])) {
                throw new BucketFieldTypeUndefinedException($k);
            }
        }
        
        return $schema;        
    }
}