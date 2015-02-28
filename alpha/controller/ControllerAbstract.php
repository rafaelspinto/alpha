<?php
/**
 * ControllerAbstract
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Controller;

use Alpha\Core\Connectors;
use Alpha\Http\Header;
use Alpha\Http\Response;
use Alpha\Http\StatusCode;
use Alpha\Http\ContentType;
use Alpha\Utils\ArrayUtils;
use Alpha\Exception\ControllerActionNotFoundException;

/**
 * Base class for Controllers.
 */
abstract class ControllerAbstract
{
    protected $data, $statusCode, $contentType, $viewsPath, $preFilters, $postFilters;
    
    /**
     * Constructs a ControllerAbstract.
     * 
     * @param string $viewsPath The path of the views.
     */
    public function __construct($viewsPath)
    {
        $this->viewsPath   = $viewsPath;
        $this->statusCode  = StatusCode::OK;
        $this->contentType = ContentType::TEXT_HTML;
        $this->data        = [];
        $this->preFilters  = [];
        $this->postFilters = [];
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
     * Returns the array of data.
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
        $this->executePreFilters($actionName);
        $content = $this->getContentForView($this->getViewFilename($context, $actionName));                
        if(($hasMethod = method_exists($this, $actionName))) {
            $otherView = call_user_func_array(array($this, $actionName), $parameters);
            if($otherView) {                
                $otherView = $this->viewsPath . $otherView;
                $content   = $this->getContentForView($otherView);
            }
        }

        if($content|| $hasMethod) {
            $response = $this->makeResponse($content);
            $this->executePostFilters($this->data, $actionName);
            return $response;
        }
        
        throw new ControllerActionNotFoundException($actionName);
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
    
    /**
     * Adds a filter to the PRE execute list.
     * 
     * @param callable $filter     The filter.
     * @param string   $actionName The name of the action.
     * 
     * @return void
     */
    public function preFilter(callable $filter, $actionName = null)
    {
        if(!empty($actionName)) {
            $this->preFilters[$actionName][] = $filter;
        }else {
            $this->preFilters['global'][] = $filter;
        }
    }
    
    /**
     * Adds a filter to the POST execute list.
     * 
     * @param callable $filter     The filter.
     * @param string   $actionName The name of the action.
     * 
     * @return void
     */
    public function postFilter(callable $filter, $actionName = null)
    {
        if(!empty($actionName)) {
            $this->postFilters[$actionName][] = $filter;
        }else {
            $this->postFilters['global'][] = $filter;
        }
    }
    
    /**
     * Executes the PRE filters list.
     * 
     * @param string $actionName The name of the action.
     * 
     * @return void
     */
    protected function executePreFilters($actionName = null)
    {
        // pre-filters action specific
        if(isset($this->preFilters[$actionName])) {
            foreach($this->preFilters[$actionName] as $filter) {                
                $this->executeFilter($filter);
            }
        }
        
        // global filters
        if(isset($this->preFilters['global'])) {            
            foreach($this->preFilters['global'] as $filter) {
                $this->executeFilter($filter);
            }
        }
    }

    /**
     * Executes the POST filters list.
     * 
     * @param array  $data       The array containing the data resulting of the request execute.
     * @param string $actionName The name of the action.
     * 
     * @return void
     */
    protected function executePostFilters(array $data = array(), $actionName = null)
    {
        // pre-filters action specific
        if(isset($this->postFilters[$actionName])) {
            foreach($this->postFilters[$actionName] as $filter) {
                $this->executeFilter($filter, $data);
            }
        }
        
        // global filters
        if(isset($this->postFilters['global'])) {            
            foreach($this->postFilters['global'] as $filter) {
                $this->executeFilter($filter, $data);
            }
        }        
    }
        
    /**
     * Executes a filter.
     * 
     * @param callable $filter The filter.
     * @param array    $data   The array of data to be passed to the filter.
     * 
     * @return mixed
     */
    protected function executeFilter(callable $filter, array $data = array())
    {
        return call_user_func_array($filter, $data);
    }
}