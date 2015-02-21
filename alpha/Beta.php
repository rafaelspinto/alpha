<?php
/**
 * Beta
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha;

use Alpha\Core\Config;
use Alpha\Http\UriHandler;
use Alpha\Core\Router;

/**
 * Class that bootstraps the Alpha Framework.
 */
class Beta
{
    /**
     * Handles the request.
     * 
     * @return void
     */
    public static function gamma()
    {
        $uriHandler = new UriHandler();
        $uriHandler->setPattern('/{s:controller}/{s:action}/{i:id}');
        $router   = new Router($uriHandler, Config::getControllersPath(), 'Index');
        $response = $router->go(filter_input(INPUT_SERVER, 'REQUEST_URI'));        
        header('Content-type: '.$response->getContentType(), true, $response->getStatusCode());
        print $response->getContent();
    }
}


