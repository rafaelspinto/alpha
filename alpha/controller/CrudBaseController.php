<?php
/**
 * CrudBaseController
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Controller;

use Alpha\Storage\BucketInterface;
use Alpha\Controller\ControllerAbstract;

/**
 * Base controller for CRUD operations.
 */
class CrudBaseController extends ControllerAbstract
{
    /**
     * @var BucketInterface
     */
    protected $bucket;

    protected $modelVar; 
       
    /**
     * Constructs a CrudBaseController.
     * 
     * @param Alpha\Storage\BucketInterface $bucket    Bucket to use.
     * @param string                        $viewsPath The path of the views.
     */
    public function __construct(BucketInterface $bucket, $viewsPath)
    {
        parent::__construct($viewsPath);        
        $this->bucket   = $bucket;
        $this->modelVar = strtolower($this->getModelVar(get_class($bucket)));
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
        if(filter_var($actionName, FILTER_VALIDATE_INT) !== false) {
            $this->getById($actionName);
            return $this->makeResponse($this->getContentForView($this->getViewFilename($this->modelVar, 'get' . ucfirst($this->modelVar))));
        }
        return parent::execute($context, $actionName, $parameters);
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
        $this->data[$this->modelVar] = $this->bucket->findByKey($PATH_id);
    }
    
    /**
     * Returns the list of items.
     * 
     * @return void
     */
    public function get()
    {
        $this->data[$this->modelVar] = $this->bucket->find();
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
        $this->data[$this->modelVar] = $this->bucket->create($PARAM);
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
        $this->data[$this->modelVar] = $this->bucket->find($PARAM);
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
        $this->data[$this->modelVar] = $this->bucket->findByKey($PATH_id);
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
        $this->data[$this->modelVar] = $this->bucket->update($PARAM);
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
        $this->data[$this->modelVar] = $this->bucket->findByKey($PATH_id);
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
        $this->data[$this->modelVar] = $this->bucket->delete($PATH_id);
    }
    
    /**
     * Returns the model var.
     * 
     * @param type $className The name of the class.
     * 
     * @return string
     */
    protected function getModelVar($className)
    {
        $parts = explode('\\', $className);
        return $parts[count($parts) - 1];
    }
}