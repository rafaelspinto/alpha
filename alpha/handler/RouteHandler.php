<?php
/**
 * RouteHandler
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Handler;

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
     * @return Response
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
        $parameters     = $this->buildParameters($controller, $actionName);        
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
            include_once $controllerFilename;
            $controllerClass = '\\' . $controllerName . 'Controller';
            return new $controllerClass($this->viewsPath);            
        }
        
        // check if exists model
        $modelFile = $this->modelsPath . $controllerName . '.php';
        if(file_exists($modelFile)) {
            return new CrudBaseController($this->viewsPath, $controllerName);
        }
        
        // could be the default controller
        $indexControllerFilename = $this->makeControllerFilename($this->defaultControllerName);
        if(file_exists($indexControllerFilename)) {
            include_once $indexControllerFilename;
            $controllerClass = '\\' . $this->defaultControllerName . 'Controller';
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
     * Builds the parameters required for the action.
     * 
     * @param object $controller The instance of the controller.
     * @param string $actionName The name of the action.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function buildParameters($controller, $actionName)
    {
        $parameters = array();
        if(method_exists($controller, $actionName)) {
            $ref = new \ReflectionMethod($controller, $actionName);
            foreach($ref->getParameters() as $parameter) {
                $parameters[$parameter->getName()] = $this->makeParameterValue($parameter);
            }
        }
        return $parameters;
    }
    
    /**
     * Returns the parameter value.
     * 
     * @param \ReflectionParameter $parameter The parameter of the action.
     * 
     * @return mixed
     */
    public function makeParameterValue(\ReflectionParameter $parameter)
    {
        $param = $parameter->getName();
        if(strpos($param, '_') == false){
            switch ($param){
                case 'QUERY' :
                    return $_GET;
                case 'PARAM' :
                    return $_POST;
                case 'COOKIE' :
                    return $_COOKIE;
                case 'SESSION' :
                    return $_SESSION;
            }
        }
        $separatorPos = stripos($param, '_');
        $paramType    = substr($param, 0, $separatorPos);
        $paramName    = substr($param, $separatorPos + 1);
        
        switch($paramType) {
            case 'PATH' :
                return $this->uriHandler->getComponent($paramName);
            case 'QUERY' :
                return filter_input(INPUT_GET, $paramName);
            case 'PARAM' :
                return filter_input(INPUT_POST, $paramName);
            case 'COOKIE' :
                return filter_input(INPUT_COOKIE, $paramName);
            case 'SESSION' :
                return filter_input(INPUT_SESSION, $paramName);
        }
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
}