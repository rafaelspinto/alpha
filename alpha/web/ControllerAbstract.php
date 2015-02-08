<?php
/**
 * ControllerAbstract
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Web;

use Alpha\Http\UriHandler;
use Alpha\Connector\Connectors;

/**
 * Base class for Controllers.
 */
abstract class ControllerAbstract
{
    protected $uriHandler, $data;
    
    /**
     * Constructs a ControllerAbstract.
     * 
     * @param \Alpha\Http\UriHandler $uriHandler The uri handler.
     */
    public function __construct(UriHandler $uriHandler)
    {
        $this->uriHandler = $uriHandler;
        $this->data       = array();
    }
          
    /**
     * Executes the controller action.
     * 
     * @param string $actionName The name of the action.
     * 
     * @return \Alpha\Web\Response
     * 
     * @throws \Exception
     */
    public function execute($actionName)
    {        
        $controllerName = str_replace('Controller', '', get_called_class());
        $actionName     = $this->buildActionMethodName($actionName);
        $viewFile       = PATH_VIEW . strtolower($controllerName) . DIRECTORY_SEPARATOR . $actionName . '.html';
        if(($hasView = file_exists($viewFile))){
            $content = file_get_contents($viewFile);
        }
        
        if (($hasMethod = method_exists($this, $actionName))) {
            call_user_func_array(array($this, $actionName), $this->buildParameters($actionName));            
        }

        if($hasView || $hasMethod) {
            return $this->makeResponse($content);    
        }
        
        throw new \Exception('action_not_found:' . $actionName);
    }
    
    /**
     * Builds the parameters required for the action.
     * 
     * @param string $actionName The name of the action.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function buildParameters($actionName)
    {
        $ref        = new \ReflectionMethod($this, $actionName);
        $parameters = array();
        foreach($ref->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $this->makeParameterValue($parameter);
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
    protected function buildActionMethodName($actionName)
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
    
    /**
     * Returns the parameter value.
     * 
     * @param \ReflectionParameter $parameter The parameter of the action.
     * 
     * @return mixed
     */
    protected function makeParameterValue(\ReflectionParameter $parameter)
    {
        $param        = $parameter->getName();
        $separatorPos = stripos($param, '_');
        $paramName    = substr($param, $separatorPos + 1);
        $paramType    = substr($param, 0, $separatorPos);
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
     * Returns a response.
     * 
     * @param string $content The content of the response.
     * 
     * @return \Alpha\Web\Response
     */
    protected function makeResponse($content)
    {       
        // json response
        if(empty($content) && !empty ($this->data)){
            return new Response(json_encode($this->data));
        }
        
        // html response with data to bind
        if(!empty($content) && !empty($this->data)) {
            return new Response(Connectors::get('View')->render($content, $this->data));
        }
        
        return new Response($content);
    }
}
