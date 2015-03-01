<?php
/**
 * ControllerAbstract
 *
 * @author Rafael Pinto <rafael.pinto@fixeads.com>
 */
namespace Alpha\Controller;

use Alpha\Core\Connectors;
use Alpha\Http\Response;
use Alpha\Http\ContentType;
use Alpha\Utils\ArrayUtils;
use Alpha\Exception\ControllerActionNotFoundException;

/**
 * Base class for Controllers.
 */
abstract class ControllerAbstract
{
    /**
     * @var Response
     */
    protected $response;

    protected $data, $viewsPath, $filters;
    
    /**
     * Constructs a ControllerAbstract.
     * 
     * @param string $viewsPath The path of the views.
     */
    public function __construct($viewsPath)
    {
        $this->viewsPath = $viewsPath;
        $this->response  = new Response();
        $this->data      = [];
        $this->filters   = [];
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
        // apply BEFORE filters
        $this->executeFilters('before', [], $actionName);
        $content = $this->getContentForView($this->getViewFilename($context, $actionName));                
        if(($hasMethod = method_exists($this, $actionName))) {
            $response = call_user_func_array(array($this, $actionName), $parameters);
            
            // apply AFTER filters
            $this->executeFilters('after', $this->data, $actionName);
            
            // response is already the final response
            if($response instanceof Response) {
                return $response;
            }
            
            // response is the name of the view to be used
            if(is_string($response)) {
                $response = $this->viewsPath . $response;
                $content  = $this->getContentForView($response);
            }
        }

        if($content|| $hasMethod) {
            return $this->makeResponse($content);
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
        if(empty($content) && !empty ($this->data)){
            $this->response->setContentType(ContentType::APPLICATION_JSON);
            $this->response->setContent(json_encode(ArrayUtils::encodeToUtf8($this->data)));            
        }else if(!empty ($content)){
            $this->response->setContent(Connectors::get('View')->render($content, $this->data));
        }
        return $this->response;
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
     * Adds a filter to the list.
     * 
     * @param callable $filter     The filter.
     * @param string   $type       The type of filter (pre|post)
     * @param string   $actionName The name of the action.
     * 
     * @return void
     */
    public function filter(callable $filter, $type = 'before', $actionName = null)
    {
        if(!empty($actionName)) {
            $this->filters[$type][$actionName][] = $filter;
        }else {
            $this->filters[$type]['global'][] = $filter;
        }
    }

    /**
     * Executes the filters list.
     * 
     * @param string $type       The type of filter (before|after)
     * @param array  $data       The array containing the data resulting of the request execute.
     * @param string $actionName The name of the action.
     * 
     * @return void
     */
    protected function executeFilters($type = 'before', array $data = array(), $actionName = null)
    {
        // filters action specific
        if(isset($this->filters[$type][$actionName])) {
            foreach($this->filters[$type][$actionName] as $filter) {
                $this->executeFilter($filter, $data);
            }
        }
        
        // global filters
        if(isset($this->filters[$type]['global'])) {            
            foreach($this->filters[$type]['global'] as $filter) {
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