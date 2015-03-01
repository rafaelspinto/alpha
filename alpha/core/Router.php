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
use Alpha\Http\Header;

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
     * @return \Alpha\Http\Response
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
     * Redirects the request to the given url.
     * 
     * @param string $url The url.
     * 
     * @return void
     */
    public static function redirectTo($url)
    {        
        header(Header::LOCATION.': '.$url);
        exit;
    }
    
    /**
     * Routes the request to the Controller Action.
     * 
     * @param string $controller The name of the controller.
     * @param string $action     The name of the action.
     * @param array  $parameters The array of the parameters.
     * 
     * @return void
     */
    public static function redirectToAction($controller, $action, array $parameters = array())
    {        
        return static::getInstance()->goToAction($controller, $action, $parameters);
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
