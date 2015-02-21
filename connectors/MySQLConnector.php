<?php
/**                                                                                                                                                                                                                                                                            
 * MySQLConnector
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Connectors;

use Connectors\MySQL\MySQLResultIterator;
use Connectors\MySQL\MySQLUtils;

/**
 * MySQL repository.
 */
class MySQLConnector implements \Alpha\Connector\RepositoryInterface
{
    /**
     * @var \mysqli
     */
    protected $mysqliInstance;
    
    protected $host, $port, $user, $password, $database, $socket;
    
    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        $this->host     = isset($configuration['server']['host']) ? $configuration['server']['host'] : null;
        $this->port     = isset($configuration['server']['port']) ? $configuration['server']['port'] : null;
        $this->user     = isset($configuration['server']['user']) ? $configuration['server']['user'] : null;
        $this->password = isset($configuration['server']['password']) ? $configuration['server']['password'] : null;
        $this->database = isset($configuration['server']['database']) ? $configuration['server']['database'] : null;
        $this->socket   = isset($configuration['server']['socket']) ? $configuration['server']['socket'] : null;
    }
    
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
    public function create(array $schema, array $fieldsAndValues)
    {
        $this->prepareConnection();
        $stmt = MySQLUtils::prepare($this->mysqliInstance, $this->getInsertQuery($schema, $fieldsAndValues), $schema);
        MySQLUtils::bindParameters($stmt, $schema, $fieldsAndValues);
        MySQLUtils::execute($stmt, false);
        return $stmt->insert_id;
    }

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
    public function update(array $schema, array $fieldsAndValues)
    {
        $this->prepareConnection();
        $stmt = MySQLUtils::prepare($this->mysqliInstance, $this->getUpdateQuery($schema, $fieldsAndValues), $schema);        
        MySQLUtils::bindParameters($stmt, $schema, $fieldsAndValues);
        MySQLUtils::execute($stmt, false);
        return $stmt->affected_rows > 0;
    }
    
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
    public function delete(array $schema, $key)
    {
        $this->prepareConnection();
        $stmt = MySQLUtils::prepare($this->mysqliInstance, $this->getDeleteByIdQuery($schema), $schema);
        $stmt->bind_param('i', $key);
        MySQLUtils::execute($stmt, false);
        return $stmt->affected_rows > 0;
    }

    /**
     * Returns the entry that has the given key.
     * 
     * @param array $schema The array containing the schema.
     * @param mixed $key    The key of the entry.
     * 
     * @return \Traversable
     */
    public function findByKey(array $schema, $key)
    {
        $this->prepareConnection();
        $stmt = MySQLUtils::prepare($this->mysqliInstance, $this->getById($schema), $schema);
        $stmt->bind_param('i', $key);                
        $iter = new MySQLResultIterator(MySQLUtils::execute($stmt));
        return $iter->current();
    }

    /**
     * Returns the entries that match the fields with the given values.
     * 
     * @param array $schema          The array containing the schema.
     * @param mixed $fieldsAndValues The array containing the fields and values to search.
     * 
     * @return \Traversable
     */
    public function findByFields(array $schema, $fieldsAndValues)
    {        
        $this->prepareConnection();
        if(empty($fieldsAndValues)) {
            $query = $this->getAllQuery($schema);
        }else {
            $query = $this->getByFieldsQuery($schema, $fieldsAndValues);
        }
        $stmt = MySQLUtils::prepare($this->mysqliInstance, $query, $schema);
        MySQLUtils::bindParameters($stmt, $schema, $fieldsAndValues);
        $result = MySQLUtils::execute($stmt);
        return new MySQLResultIterator($result);
    }
    
    /**
     * Prepares a connection in case it does not exist already.
     * 
     * @return void
     */
    protected function prepareConnection()
    {
        if($this->mysqliInstance == null) {
            $this->mysqliInstance = new \mysqli($this->host, $this->user, $this->password, $this->database, $this->port, $this->socket);
        }
    }
    
    /**
     * Returns the query for all elements.
     * 
     * @param array $schema The array containing the schema definition.
     * 
     * @return string
     */
    protected function getAllQuery(array $schema)
    {
        return 'SELECT * FROM '.$schema['bucket'];
    }
    
    /**
     * Returns the query for the element identified by the id.
     * 
     * @param array $schema The array containing the schema definition.
     * 
     * @return string
     */
    protected function getById(array $schema)
    {
        return $this->getAllQuery($schema) . ' WHERE ' .$schema['key'] . ' = ?';
    }
    
    /**
     * Returns the query for the elements identified by the values of the fields.
     * 
     * @param array $schema          The array containing the schema definition.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return string
     */
    protected function getByFieldsQuery(array $schema, $fieldsAndValues)
    {        
        return $this->getAllQuery($schema) . ' WHERE ' . MySQLUtils::makeWildcards(MySQLUtils::filter($schema, $fieldsAndValues), 'AND');
    }

    /**
     * Returns the query for DELETE the element identified by the id.
     * 
     * @param array $schema The array containing the schema definition.
     * 
     * @return string
     */
    protected function getDeleteByIdQuery(array $schema)
    {
        return 'DELETE FROM '. $schema['bucket'] . ' WHERE ' .$schema['key'] . ' = ?';
    }
    
    /**
     * Returns the query for CREATE an element.
     * 
     * @param array $schema          The array containing the schema definition.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return string
     */
    protected function getInsertQuery(array $schema, $fieldsAndValues)
    {
        return 'INSERT INTO '. $schema['bucket'] . ' SET ' . MySQLUtils::makeWildcards(MySQLUtils::filterNoKey($schema, $fieldsAndValues));
    }
    
    /**
     * Returns the query for UPDATE an element.
     * 
     * @param array $schema          The array containing the schema definition.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return string
     */
    protected function getUpdateQuery(array $schema, $fieldsAndValues)
    {        
        return 'UPDATE '. $schema['bucket'] . ' SET ' . MySQLUtils::makeWildcards(MySQLUtils::filterNoKey($schema, $fieldsAndValues)) . ' WHERE ' . $schema['key'] . ' = ' . (int) $fieldsAndValues[$schema['key']];
    }
}

