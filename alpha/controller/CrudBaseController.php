<?php
/**
 * CrudBaseController
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Controller;

use Alpha\Controller\ControllerAbstract;
use Alpha\Http\UriHandler;
use Alpha\Core\Buckets;

/**
 * Base controller for CRUD operations.
 */
class CrudBaseController extends ControllerAbstract
{
    protected $modelVar, $bucket;
       
    /**
     * Constructs a CrudBaseController.
     * 
     * @param UriHandler $uriHandler The uri handler.
     * @param string     $className  The name of the class.
     */
    public function __construct(UriHandler $uriHandler, $className = null)
    {
        parent::__construct($uriHandler);        
        $this->initFromModel($className);
    }
    
    /**
     * Executes the controller action.
     * 
     * @param string $context    The context.
     * @param string $actionName The name of the action.
     * 
     * @return \Alpha\Http\Response
     * 
     * @throws \Exception
     */
    public function execute($context, $actionName)
    {
        if(intval($actionName)) {
            $this->getById($actionName);     
            return $this->makeResponse($this->getContentForView($this->getViewFilename($this->modelVar, 'get' . ucfirst($this->modelVar))));
        }
        return parent::execute($context, $actionName);        
    }
    
    /**
     * Returns the item.
     * 
     * @param int $PATH_id The id of the item.
     * 
     * @return void
     */
    public function getById($PATH_id)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->findByKey($PATH_id);
    }
    
    /**
     * Returns the list of items.
     * 
     * @return void
     */
    public function get()
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->find();
    }
    
    /**
     * Creates an element in the context.
     * 
     * @param array $PARAM The array containing the parameters.
     * 
     * @return void
     */
    public function postCreate($PARAM)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->create($PARAM);
    }

    /**
     * Searchs element(s) in the context.
     * 
     * @param array $PARAM The array containing the parameters.
     * 
     * @return void
     */
    public function postSearch($PARAM)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->find($PARAM);
    }
    
    /**
     * Returns the element to be updated.
     * 
     * @param int $PATH_id The id of the context.
     * 
     * @return void
     */
    public function getEdit($PATH_id)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->findByKey($PATH_id);
    }
    
    /**
     * Updates the element in the context.
     * 
     * @param int   $PATH_id The id of the element.
     * @param array $PARAM   The array containing the parameters.
     * 
     * @return void
     */
    public function postEdit($PATH_id, $PARAM)
    {
        $PARAM['id']                 = $PATH_id;
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->update($PARAM);
    }
    
    /**
     * Returns the element to delete.
     * 
     * @param int $PATH_id The id of the element.
     * 
     * @return void
     */
    public function getDelete($PATH_id)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->findByKey($PATH_id);
    }
    
    /**
     * Deletes the element from the context.
     * 
     * @param int $PATH_id The id of the element.
     * 
     * @return void
     */
    public function postDelete($PATH_id)
    {
        $this->data[$this->modelVar] = Buckets::get($this->bucket)->delete($PATH_id);
    }
    
    /**
     * Initializes the controller.
     * 
     * @param string $className The name of the class.
     * 
     * @return void
     */
    protected function initFromModel($className = null)
    {
        if($className == null) {
            $className = str_replace('Controller', '', get_called_class());
        }
        $this->bucket   = $className;
        $this->modelVar = strtolower($className);
    }
}