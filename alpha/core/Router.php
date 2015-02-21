<?php
/**
 * Router
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Core;

use Alpha\Core\Config;
use Alpha\Handler\UriHandler;
use Alpha\Controller\CrudBaseController;

/**
 * Class that handles request routing.
 */
class Router
{
    protected $controllerPath, $defaultControllerName;

    /**
     * @var \Alpha\Handler\UriHandler 
     */
    protected $uriHandler;

    /**
     * Constructs a Router.
     * 
     * @param \Alpha\Handler\UriHandler $uriHandler            The uri handler.
     * @param string                    $controllerPath        The path of the controllers.
     * @param string                    $defaultControllerName The path of the controllers.
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
        return $this->findController($this->uriHandler->getComponent('controller'), $this->uriHandler->getComponent('action'));
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
            return $this->execute($controllerFilename, $controllerName, $actionName);
        }
        
        // check if exists model
        $modelFile = Config::getModelsPath() . $controllerName . '.php';
        if(file_exists($modelFile)) {
            $controller = new CrudBaseController($this->uriHandler, $controllerName);
            return $controller->execute($controllerName, $actionName);
        }
        
        // could be the default controller
        $indexControllerFilename = $this->makeControllerFilename($this->defaultControllerName);
        if(file_exists($indexControllerFilename)) {
            return $this->execute($indexControllerFilename, $this->defaultControllerName, $controllerName);
        }
        
        throw new \Exception('controller_not_found:' . $controllerName);
    }
    
    /**
     * Executes the controller action.
     * 
     * @param string $ctrlFile The filename of the controller.
     * @param string $ctrlName The name of the controller
     * @param string $action   The name of the action
     * 
     * @return \Alpha\Http\Response
     */
    public function execute($ctrlFile, $ctrlName, $action)
    {
        include_once $ctrlFile;
        $controllerClass    = '\\' . $ctrlName . 'Controller';
        $controllerInstance = new $controllerClass($this->uriHandler);
        $controllerName     = str_replace('Controller', '', $ctrlName);
        return $controllerInstance->execute($controllerName, $action);
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
