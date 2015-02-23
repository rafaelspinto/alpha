<?php
/**
 * SessionInterface
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Session;

/**
 * Defines the interface the session handlers must implement.
 */
interface SessionInterface
{
    /**
     * Returns the data stored in the Session identified by the key.
     * 
     * @param string $key The key.
     * 
     * @return mixed
     */
    function get($key);
    
    /**
     * Stores a value in the session.
     * 
     * @param string $key   The key of the value.
     * @param mixed  $value The value.
     * 
     * @return void
     */
    function set($key, $value);
}
