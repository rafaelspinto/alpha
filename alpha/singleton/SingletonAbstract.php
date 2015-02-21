<?php
/**
 * SingletonAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Singleton;

/**
 * Base class for Singletons.
 */
abstract class SingletonAbstract implements SingletonFactoryInterface
{
    static protected $instances = array();
        
    /**
     * Returns the instance that should be used as a singleton.
     * 
     * @return static
     */
    protected static function getInstance()
    {
        $className = get_called_class();
        if(!isset(self::$instances[$className])) {
            self::$instances[$className] = static::make();
        }
        return self::$instances[$className];
    }
}