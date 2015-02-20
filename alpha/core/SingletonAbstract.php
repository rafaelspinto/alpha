<?php
/**
 * SingletonAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

/**
 * Base class for Singletons.
 */
abstract class SingletonAbstract
{
    static protected $instances = array();
        
    /**
     * Returns the instance of the called class.
     * 
     * @return static
     */
    protected static function getInstance()
    {
        $className = get_called_class();
        if(!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className;
        }
        return self::$instances[$className];
    }
}

