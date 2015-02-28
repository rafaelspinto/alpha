<?php
/**
 * RouteHandler
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Handler;

use Alpha\Core\Autoloader;
use Alpha\Core\Buckets;
use Alpha\Core\Parameters;
use Alpha\Controller\CrudBaseController;
use Alpha\Exception\ControllerNotFoundException;

/**
 * Class that handles routes.
 */
class RouteHandler
{
    protected $controllersPath, $modelsPath, $viewsPath, $defaultControllerName;

    /**
     * @var \Alpha\Handler\UriHandler 
     */
    protected $uriHandler;

    /**
     * Constructs a Router.
     * 
     * @param \Alpha\Handler\UriHandler $uriHandler            The uri handler.
     * @param string                    $controllersPath       The path of the controllers.
     * @param string                    $modelsPath            The path of the models.
     * @param string                    $viewsPath             The path of the views.
     * @param string                    $defaultControllerName The default controller name.
     */
    public function __construct(UriHandler $uriHandler, $controllersPath, $modelsPath, $viewsPath, $defaultControllerName)
    {
        $this->uriHandler            = $uriHandler;
        $this->controllersPath       = $controllersPath;
        $this->modelsPath            = $modelsPath;
        $this->viewsPath             = $viewsPath;
        $this->defaultControllerName = $defaultControllerName;
    }

    /**
     * Routes the request to the Controller Action.
     * 
     * @param string $uri The uri of the request.
     * 
     * @return \Alpha\Http\Response
     * 
     * @throws \Exception
     */
    public function go($uri)
    {
        $this->uriHandler->setUri($uri);
        $controllerName = $this->uriHandler->getComponent('controller');
        $action         = $this->uriHandler->getComponent('action');
        $controller     = $this->makeController($controllerName);
        $actionName     = $this->buildActionMethodName($action);
        $parameters     = Parameters::get($controller, $actionName);        
        return $controller->execute($controllerName, $actionName, $parameters);        
    }
    
    /**
     * Routes the request to the Controller Action.
     * 
     * @param string $controllerName The name of the controller.
     * @param string $actionName     The name of the action.
     * @param array  $parameters     The array of the parameters.
     * 
     * @return \Alpha\Http\Response
     */
    public function goToAction($controllerName, $actionName, array $parameters = array())
    {
        $controller = $this->makeController($controllerName);
        return $controller->execute($controllerName, $actionName, $parameters);
    }
    
    /**
     * Factory for instantiating a Controller based on the name.
     * 
     * @param string $controllerName The name of the controller.
     * 
     * @return \Alpha\Handler\controllerClass|\Alpha\Controller\CrudBaseController|\Alpha\Handler\defaultControllerName
     * 
     * @throws \Exception
     */
    public function makeController($controllerName)
    {
        $controllerFilename = $this->makeControllerFilename($controllerName);
        if (file_exists($controllerFilename)) {
            $controllerClass = Autoloader::getFullyQualifiedClassNameForFilename($controllerFilename);
            $reflection      = new \ReflectionClass($controllerClass);
            if($reflection->isSubclassOf('\Alpha\Controller\CrudBaseController')) {
                return new $controllerClass(Buckets::get($controllerName), $this->viewsPath);
            }
            return new $controllerClass($this->viewsPath);            
        }
        
        // check if exists model
        $modelFile = $this->modelsPath . $controllerName . '.php';
        if(file_exists($modelFile)) {            
            return new CrudBaseController(Buckets::get($controllerName), $this->viewsPath);
        }
        
        // could be the default controller
        $defaultControllerFilename = $this->makeControllerFilename($this->defaultControllerName);
        if(file_exists($defaultControllerFilename)) {
            $controllerClass = Autoloader::getFullyQualifiedClassNameForFilename($defaultControllerFilename);
            return new $controllerClass($this->viewsPath);
        }
        
        throw new ControllerNotFoundException($controllerFilename);
    }
    
    /**
     * Returns the name of the method for the given action.
     * 
     * @param string $actionName The name of the action.
     * 
     * @return string
     */
    public function buildActionMethodName($actionName)
    {
        if(filter_var($actionName, FILTER_VALIDATE_INT) !== false) {
            return $actionName;
        }
        
        $type   = 'get';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        switch($method){
            case 'PUT' :
            case 'DELETE' :
            case 'POST' :
            case 'GET'  :
                $type = strtolower($method);
                break;
        }
        return $type . ucfirst($actionName);
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
        return $this->controllersPath . $controllerName . 'Controller.php';
    }
    
    /**
     * Returns the UriHandlers.
     * 
     * @return UriHandler
     */
    public function getUriHandler()
    {
        return $this->uriHandler;
    }
}