<?php

/**
 * Router
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */

namespace Alpha\Web;

use Alpha\Http\UriHandler;

/**
 * Class that handles request routing.
 */
class Router
{
    protected $controllerPath;

    /**
     * @var \Alpha\Http\UriHandler 
     */
    protected $uriHandler;

    /**
     * 
     * @param \Alpha\Http\UriHandler $uriHandler     The uri handler.
     * @param string                 $controllerPath The path of the controllers.
     */
    public function __construct(UriHandler $uriHandler, $controllerPath)
    {
        $this->uriHandler     = $uriHandler;
        $this->controllerPath = $controllerPath;
    }

    /**
     * Routes the request to the Controller Action.
     * 
     * @param string $uri The uri of the request.
     * 
     * @return Response
     * 
     * @throws \Exception
     */
    public function go($uri)
    {
        $this->uriHandler->setUri($uri);
        $controllerName = $this->uriHandler->getComponent('controller');
        $actionName     = $this->uriHandler->getComponent('action');
        return $this->executeAction($controllerName, $actionName);
    }

    /**
     * Executes the requested action.
     * 
     * @param string $controllerName The name of the controller.
     * @param string $actionName     The name of the action.
     * 
     * @return Response
     * 
     * @throws \Exception
     */
    public function executeAction($controllerName, $actionName)
    {
        $controllerFilename = $this->makeControllerFilename($controllerName);
        if (file_exists($controllerFilename)) {
            include_once $controllerFilename;
            $controllerClass    = '\\' . $controllerName . 'Controller';
            $controllerInstance = new $controllerClass();
            if (method_exists($controllerInstance, $actionName)) {
                return $controllerInstance->$actionName();
            }

            throw new \Exception('action_not_found:' . $actionName);
        }
        throw new \Exception('controller_not_found:' . $controllerName);
    }

    /**
     * Returns the filename for the controller.
     * 
     * @param string $controllerName The name of the controller.
     * 
     * @return string
     */
    public function makeControllerFilename($controllerName)
    {
        return $this->controllerPath . $controllerName . 'Controller.php';
    }
}
