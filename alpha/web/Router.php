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
    protected $controllerPath, $defaultControllerName;

    /**
     * @var \Alpha\Http\UriHandler 
     */
    protected $uriHandler;

    /**
     * Constructs a Router.
     * 
     * @param \Alpha\Http\UriHandler $uriHandler            The uri handler.
     * @param string                 $controllerPath        The path of the controllers.
     * @param string                 $defaultControllerName The path of the controllers.
     */
    public function __construct(UriHandler $uriHandler, $controllerPath, $defaultControllerName)
    {
        $this->uriHandler            = $uriHandler;
        $this->controllerPath        = $controllerPath;
        $this->defaultControllerName = $defaultControllerName;
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
        list($ctrlFile, $ctrlName, $action) = $this->findController($this->uriHandler->getComponent('controller'), $this->uriHandler->getComponent('action'));
        include_once $ctrlFile;
        $controllerClass    = '\\' . $ctrlName . 'Controller';
        $controllerInstance = new $controllerClass($this->uriHandler);
        return $controllerInstance->execute($action);
    }

    /**
     * Returns the filename and name of the controller and the action.
     * 
     * @param string $controllerName The name of the controller.
     * @param string $actionName     The name of the action.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function findController($controllerName, $actionName)
    {
        $controllerFilename = $this->makeControllerFilename($controllerName);
        if (file_exists($controllerFilename)) {
            return array($controllerFilename, $controllerName, $actionName);
        }
        
        // could be the default controller
        $indexControllerFilename = $this->makeControllerFilename($this->defaultControllerName);
        if(file_exists($indexControllerFilename)) {
            return array($indexControllerFilename, $this->defaultControllerName, $controllerName);
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
