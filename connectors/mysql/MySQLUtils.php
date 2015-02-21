<?php
/**                                                                                                                                                                                                                                                                            
 * MySQLUtils
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Connectors\MySQL;

use Alpha\Storage\BucketAbstract;

/**
 * Helper class for operations of MySQL.
 */
class MySQLUtils
{
    /**
     * Binds the parameters to a mysqli statement.
     * 
     * @param \mysqli_stmt $stmt            The statement.
     * @param array        $schema          The array containing the schema.
     * @param array        $fieldsAndValues The array containing the fields and values.
     * 
     * @return void
     */
    public static function bindParameters(\mysqli_stmt $stmt, array $schema, array $fieldsAndValues)
    {
        if(count($fieldsAndValues) == 0) {
            return;
        }
        
        $types           = '';
        $values          = array();
        $fieldsAndValues = static::filterNoKey($schema, $fieldsAndValues);
        foreach($fieldsAndValues as $parameter => $value) {
            $types   .= static::makeType($schema['fields'][$parameter]['type']);
            $values[] = $value;
        }

        $args[] = $types;
        foreach ($values as $val) {
            $args[] = $val;
        }
        
        call_user_func_array(array($stmt, 'bind_param'), static::refValues($args));
    }
    
    /**
     * Makes the type used by the bind_param.
     * 
     * @param string $type The type.
     * 
     * @return string
     */
    public static function makeType($type)
    {
        switch ($type) {
            case 'integer':
                return 'i';
            case 'double' :
                return 'd';
            default :
                return 's';
        }
    }
    
    /**
     * Returns an array of reference values.
     * 
     * Auxiliary function for mysqli_bind_param because it only accepts
     * reference values.
     * 
     * @param array $arr The source array.
     * 
     * @return array
     */
    public static function refValues($arr)
    {
        if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }
    
    /**
     * Filters the elements by using the schema.
     * 
     * @param array $schema          The array containing the schema.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return array
     */
    public static function filter(array $schema, array $fieldsAndValues)
    {
        $filtered = array();
        foreach ($fieldsAndValues as $name => $value) {
            if (isset($schema['fields'][$name])) {
                $filtered[$name] = $value;
            }
        }
        return $filtered;
    }
    
     /**
     * Filters the elements by using the schema and excludes the key.
     * 
     * @param array $schema          The array containing the schema.
     * @param array $fieldsAndValues The array containing the fields and values.
     * 
     * @return array
     */
    public static function filterNoKey(array $schema, array $fieldsAndValues)
    {   
        $filtered = array();        
        foreach ($fieldsAndValues as $name => $value) {
            if ($name != $schema['key'] && isset($schema['fields'][$name])) {
                $filtered[$name] = $value;
            }
        }
        return $filtered;
    }
    
    /**
     * Creates a wildcard string.
     * 
     * @param array  $parameters The array containing the parameters.
     * @param string $separator  The separator to join the wildcards.
     * 
     * @return string
     */
    public static function makeWildcards(array $parameters, $separator = ',')
    {
        $result = array();
        foreach($parameters as $k => $v) {
            $result[] = $k .' = ? ';
        }
        return implode($separator .' ', $result);
    }
    
    /**
     * Returns a mysqli_stmt.
     * 
     * @param \mysqli $mysqli The mysqli instance.
     * @param string  $query  The query.
     * @param array   $schema The array containing the schema.
     * 
     * @return \mysqli_stmt
     * 
     * @throws \Exception
     */
    public static function prepare(\mysqli $mysqli, $query, array $schema)
    {
        BucketAbstract::validateSchemaDefinition($schema);
        $stmt = $mysqli->prepare($query);
        if(!$stmt){
            throw new \Exception('could_not_prepare_query:'.$schema['bucket']);
        }
        return $stmt;
    }

    /**
     * Executes the statement.
     * 
     * @param \mysqli_stmt $stmt   The mysqli statement.
     * @param boolean      $isRead The flag to indicate if is a read query.
     * 
     * @return \mysqli_result
     * 
     * @throws \Exception
     */
    public static function execute(\mysqli_stmt $stmt, $isRead = true)
    {
        $stmt->execute();
        if($isRead) {
            $result = $stmt->get_result();
            if(!$result) {
                throw new \Exception('could_not_retrieve_statement_result:'.$stmt->error.':'.$stmt->errno);
            }
            return $result;
        }
    }
}
