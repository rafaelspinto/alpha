<?php
/**
 * ControllerAbstract
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Web;

use Alpha\Http\UriHandler;
use Alpha\Core\Connectors;
use Alpha\Http\StatusCode;
use Alpha\Http\ContentType;
use Alpha\Utils\ArrayUtils;
use Alpha\Http\Header;

/**
 * Base class for Controllers.
 */
abstract class ControllerAbstract
{
    protected $uriHandler, $data, $statusCode, $contentType;
    
    /**
     * Constructs a ControllerAbstract.
     * 
     * @param \Alpha\Http\UriHandler $uriHandler The uri handler.
     */
    public function __construct(UriHandler $uriHandler)
    {
        $this->uriHandler  = $uriHandler;
        $this->data        = array();
        $this->statusCode  = StatusCode::OK;
        $this->contentType = ContentType::TEXT_HTML;
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
        $content        = '';
        if(($hasView = file_exists($viewFile))){
            $content = file_get_contents($viewFile);
        }
        
        if (($hasMethod = method_exists($this, $actionName))) {
            $otherView = call_user_func_array(array($this, $actionName), $this->buildParameters($actionName));
            if($otherView) {                
                $otherView = PATH_VIEW . $otherView;
                if(file_exists($otherView)) {
                    $content = file_get_contents($otherView);
                }
            }
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
     * Sets the HTTP status code.
     * 
     * @param int $statusCode The http status code.
     * 
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the content type.
     * 
     * @param string $contentType The content type.
     * 
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Returns the http status code.
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the content type.
     * 
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
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
            return new Response(json_encode(ArrayUtils::encodeToUtf8($this->data)), $this->getStatusCode(), ContentType::APPLICATION_JSON);
        }      
        return new Response(Connectors::get('View')->render($content, $this->data), $this->getStatusCode(), $this->getContentType());
    }
}
