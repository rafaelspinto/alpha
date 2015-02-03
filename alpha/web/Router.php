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
     * Constructs a Router.
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
            $actionName         = $this->buildActionMethodName($actionName);
            if (method_exists($controllerInstance, $actionName)) {
                $response = call_user_func_array(array($controllerInstance, $actionName), $this->buildParameters($controllerClass, $actionName));
                
                // TODO: change this code to use Response.
                $viewFile = PATH_VIEW . strtolower($controllerName) . DIRECTORY_SEPARATOR . $actionName . '.html';
                if(file_exists($viewFile)){
                    print file_get_contents($viewFile);
                }
                return $response;
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
    
    /**
     * Builds the parameters required for the action.
     * 
     * @param string $controllerClass The name of the class of the controller.
     * @param string $actionName      The name of the action.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function buildParameters($controllerClass, $actionName)
    {
        $ref        = new \ReflectionMethod($controllerClass, $actionName);
        $parameters = array();
        foreach($ref->getParameters() as $parameter) {
            $param        = $parameter->getName();
            $separatorPos = stripos($param, '_');
            $paramName    = substr($param, $separatorPos + 1);
            $paramType    = substr($param, 0, $separatorPos);
            switch($paramType) {
                case 'PATH' :
                    $parameters[$paramName] = $this->uriHandler->getComponent($paramName);
                    break;
                case 'QUERY' :
                    $parameters[$paramName] = filter_input(INPUT_GET, $paramName);
                    break;
                case 'PARAM' :
                    $parameters[$paramName] = filter_input(INPUT_POST, $paramName);
                    break;
                case 'COOKIE' :
                    $parameters[$paramName] = filter_input(INPUT_COOKIE, $paramName);
                    break;
                case 'SESSION' :
                    $parameters[$paramName] = filter_input(INPUT_SESSION, $paramName);
                    break;
            }
        }
        return $parameters;
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
}
