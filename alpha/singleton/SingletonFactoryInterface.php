<?php
/**
 * SingletonFactoryInterface
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Singleton;

/**
 * Defines the interfaces that the Singletons must implement.
 */
interface SingletonFactoryInterface
{
    /**
     * Returns the instance that should be used as a singleton.
     * 
     * @return static
     */
    static function make();
}

