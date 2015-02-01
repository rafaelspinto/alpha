<?php
/**
 * Beta
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha;

use Alpha\Http\UriHandler;
use Alpha\Web\Router;

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
    public static function run()
    {
        $uriHandler = new UriHandler();
        $uriHandler->setPattern('/{s:controller}/{s:action}/{i:id}');
        $router = new Router($uriHandler, PATH_CONTROLLER);
        $router->go($_SERVER['REQUEST_URI']);        
    }
}


