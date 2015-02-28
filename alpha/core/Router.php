<?php
/**
 * Router
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Config;
use Alpha\Singleton\SingletonAbstract;
use Alpha\Handler\RouteHandler;
use Alpha\Handler\UriHandler;

/**
 * Class that handles request routing.
 */
class Router extends SingletonAbstract
{
    /**
     * Routes the request to the Controller Action.
     * 
     * @param string $uri The uri of the request.
     * 
     * @return Response
     * 
     * @throws \Exception
     */
    public static function go($uri)
    {        
        return static::getInstance()->go($uri);
    }
    
    /**
     * Returns the UriHandler.
     * 
     * @return UriHandler
     */
    public static function getUriHandler()
    {
        return static::getInstance()->getUriHandler();
    }
    
    /**
     * Returns the RouteHandler instance that should be used as a singleton.
     * 
     * @return static
     */ 
    public static function make()
    {
        $uriHandler = new UriHandler();
        $uriHandler->setPattern('/{s:controller}/{s:action}/{i:id}');
        return new RouteHandler($uriHandler, Config::getControllersPath(), Config::getModelsPath(), Config::getViewsPath(), 'Index');
    }
}
