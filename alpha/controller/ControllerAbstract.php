<?php
/**
 * ControllerAbstract
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Controller;

use Alpha\Core\Connectors;
use Alpha\Http\StatusCode;
use Alpha\Http\ContentType;
use Alpha\Utils\ArrayUtils;
use Alpha\Http\Header;
use Alpha\Http\Response;

/**
 * Base class for Controllers.
 */
abstract class ControllerAbstract
{
    protected $data, $statusCode, $contentType, $viewsPath;
    
    /**
     * Constructs a ControllerAbstract.
     * 
     * @param string $viewsPath The path of the views.
     */
    public function __construct($viewsPath)
    {
        $this->viewsPath   = $viewsPath;
        $this->data        = array();
        $this->statusCode  = StatusCode::OK;
        $this->contentType = ContentType::TEXT_HTML;
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
     * Executes the controller action.
     * 
     * @param string $context    The context.
     * @param string $actionName The name of the action.
     * @param array  $parameters The parameters.
     * 
     * @return \Alpha\Http\Response
     * 
     * @throws \Exception
     */
    public function execute($context, $actionName, array $parameters)
    {        
        $content = $this->getContentForView($this->getViewFilename($context, $actionName));                
        if(($hasMethod = method_exists($this, $actionName))) {
            $otherView = call_user_func_array(array($this, $actionName), $parameters);
            if($otherView) {                
                $otherView = $this->viewsPath . $otherView;
                $content   = $this->getContentForView($otherView);
            }
        }

        if($content|| $hasMethod) {
            return $this->makeResponse($content);    
        }
        
        throw new \Exception('action_not_found:' . $actionName);
    }
    
    /**
     * Returns a response.
     * 
     * @param string $content The content of the response.
     * 
     * @return \Alpha\Http\Response
     */
    public function makeResponse($content)
    {
        // json response
        if(empty($content) && !empty ($this->data)){            
            return new Response(json_encode(ArrayUtils::encodeToUtf8($this->data)), $this->getStatusCode(), ContentType::APPLICATION_JSON);
        }      
        return new Response(Connectors::get('View')->render($content, $this->data), $this->getStatusCode(), $this->getContentType());
    }
    
    /**
     * Returns the content for the given filename.
     * 
     * @param string $filename The filename of the view.
     * 
     * @return string | null
     */
    public function getContentForView($filename)
    {
        if(file_exists($filename)) {
            return file_get_contents($filename);
        }
        return null;
    }
    
    /**
     * Returns the filename for the view.
     * 
     * @param string $context    The context.
     * @param string $actionName The name of the action.
     * 
     * @return string
     */
    public function getViewFilename($context, $actionName)
    {
        return $this->viewsPath . strtolower($context) . DIRECTORY_SEPARATOR . $actionName . '.html';
    }
}