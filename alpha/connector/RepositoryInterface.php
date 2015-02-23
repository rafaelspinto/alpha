<?php
/**                                                                                                                                                                                                                                                                            
 * RepositoryInterface
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Alpha\Connector;

use Alpha\Connector\ConnectorInterface;

/**
 * Defines the interface that the Repositories must implement.
 */
interface RepositoryInterface extends ConnectorInterface
{
    /**
     * Creates an entry in the repository and returns its key.
     * 
     * @param array $schema          The array containing the schema.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return mixed
     * 
     * @throws \Exception
     */
    function create(array $schema, array $fieldsAndValues);
    
    /**
     * Updates an entry in the repository.
     * 
     * @param array $schema          The array containing the schema.
     * @param array $fieldsAndValues The array containing the fields and values to update.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    function update(array $schema, array $fieldsAndValues);
    
    /**
     * Deletes the entry with the given key.
     * 
     * @param array $schema The array containing the schema.
     * @param mixed $key    The key of the entry.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    function delete(array $schema, $key);
    
    /**
     * Returns the entry that has the given key.
     * 
     * @param array $schema The array containing the schema.
     * @param mixed $key    The key of the entry.
     * 
     * @return \Traversable
     * 
     * @throws \Exception
     */
    function findByKey(array $schema, $key);
    
    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param array $schema          The array containing the schema.
     * @param mixed $fieldsAndValues The array containing the fields and values to search.
     * 
     * @return \Traversable
     * 
     * @throws \Exception
     */
    function findByFields(array $schema, $fieldsAndValues);
}