<?php
/**
 * CrudBaseController
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web;

use Alpha\Web\ControllerAbstract;
use Alpha\Http\UriHandler;

/**
 * Base controller for CRUD operations.
 */
class CrudBaseController extends ControllerAbstract
{
    protected $context, $modelVar;
       
    /**
     * Constructs a CrudBaseController.
     * 
     * @param UriHandler $uriHandler The uri handler.
     */
    public function __construct(UriHandler $uriHandler)
    {
        parent::__construct($uriHandler);
        $this->init();
    }
    
    /**
     * Returns the list of items.
     * 
     * @return void
     */
    public function get()
    {
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::find();
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
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::create($PARAM);
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
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::findByKey($PATH_id);
    }
    
    /**
     * Updates the element in the context.
     * 
     * @param array $PARAM The array containing the parameters.
     * 
     * @return void
     */
    public function postEdit($PATH_id, $PARAM)
    {
        $PARAM['id']                 = $PATH_id;
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::update($PARAM);
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
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::findByKey($PATH_id);
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
        $context                     = $this->context;
        $this->data[$this->modelVar] = $context::delete($PATH_id);
    }
    
    /**
     * Initializes the controller.
     * 
     * @return void
     */
    protected function init()
    {
        $className      = str_replace('Controller', '', get_called_class());
        $this->context  = "Webapp\Model\\" .$className;
        $this->modelVar = strtolower($className);
    }
}