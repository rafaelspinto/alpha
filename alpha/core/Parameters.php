<?php
/**
 * Parameters
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Core;

use Alpha\Core\Router;

/**
 * Class that handles parameters.
 */
class Parameters
{
    /**
     * Returns the parameters required for the action.
     * 
     * @param object $controller The instance of the controller.
     * @param string $actionName The name of the action.
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public static function get($controller, $actionName)
    {
        $parameters = array();
        if(method_exists($controller, $actionName)) {
            $ref = new \ReflectionMethod($controller, $actionName);
            foreach($ref->getParameters() as $parameter) {
                $parameters[$parameter->getName()] = static::getValue($parameter->getName());
            }
        }
        return $parameters;
    }
    
    /**
     * Returns the parameter value.
     * 
     * @param string $parameterName The name of the parameter.
     * 
     * @return mixed
     */
    public static function getValue($parameterName)
    {        
        if(strpos($parameterName, '_') == false){
            $paramName = null;
            $paramType = $parameterName;
        }else {
            $separatorPos = stripos($parameterName, '_');
            $paramType    = substr($parameterName, 0, $separatorPos);
            $paramName    = substr($parameterName, $separatorPos + 1);
        }
        
        switch($paramType) {
            case 'PATH' :
                return static::path($paramName);
            case 'QUERY' :
                return static::query($paramName);
            case 'PARAM' :
                return static::param($paramName);
            case 'COOKIE' :
                return static::cookie($paramName);
            case 'SESSION' :
                return static::session($paramName);
        }
        throw new UnknownTypeException($paramType);
    }
    
    /**
     * Returns the PATH parameter value or the array of all values.
     * 
     * @param string $paramName The name of the parameter.
     * 
     * @return mixed
     */
    public static function path($paramName = null)
    {
        if($paramName){
            return Router::getUriHandler()->getComponent($paramName);
        }
        return Router::getUriHandler()->getComponents();
    }
    
    /**
     * Returns the PARAM parameter value or the array of all values.
     * 
     * @param string $paramName The name of the parameter.
     * 
     * @return mixed
     */
    public static function param($paramName = null)
    {
        return $paramName ? filter_input(INPUT_POST, $paramName) : $_POST;
    }
    
    /**
     * Returns the QUERY parameter value or the array of all values.
     * 
     * @param string $paramName The name of the parameter.
     * 
     * @return mixed
     */
    public static function query($paramName = null)
    {
        return $paramName ? filter_input(INPUT_GET, $paramName) : $_GET;
    }
    
    /**
     * Returns the COOKIE parameter value or the array of all values.
     * 
     * @param string $paramName The name of the parameter.
     * 
     * @return mixed
     */
    public static function cookie($paramName = null)
    {
        return $paramName ? filter_input(INPUT_COOKIE, $paramName) : $_COOKIE;
    }
    
    /**
     * Returns the SESSION parameter value or the array of all values.
     * 
     * @param string $paramName The name of the parameter.
     * 
     * @return mixed
     */
    public static function session($paramName = null)
    {
        return $paramName ? filter_input(INPUT_SESSION, $paramName) : $_SESSION;
    }
}