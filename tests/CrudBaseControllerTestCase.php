<?php
namespace Tests;

use Alpha\Controller\CrudBaseController;

/**
 * Test case for CrudBaseController.
 */
class CrudBaseControllerTestCase extends \Alpha\Test\Unit\TestCaseAbstract
{
    /**
     * Constructs a RouteHandlerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('CrudBaseControllerTestCase');
    }
    
    /**
     * Tests execute method.
     * 
     * @return void
     */
    public function testExecute_ActionNameIs1_ShouldExecuteFindByKeyMethod()
    {
        $bucket = $this->getMock('\Alpha\Storage\BucketInterface');        
        $bucket->expects($this->any())
               ->method('findByKey')
                ->will($this->returnValue(array('id' => 1, 'name' => 'Peter Griffin')));
        $crudController = new CrudBaseController($bucket, null);
        $crudController->execute('crud', 1, array());
        $result = $crudController->getData();
        $this->assertEquals(array('id' => 1, 'name' => 'Peter Griffin'), array_shift($result));
    }
    
    /**
     * Tests execute method.
     * 
     * @return void
     */
    public function testExecute_ActionNameIsExistingMethod_ShouldExecuteFindByKeyMethod()
    {
        $bucket = $this->getMock('\Alpha\Storage\BucketInterface');        
        $bucket->expects($this->any())
               ->method('find')
               ->will($this->returnValue(array('id' => 1, 'name' => 'John Doe')));
        $crudController = new CrudBaseController($bucket, null);
        $crudController->execute('crud', 'get', array());
        $result = $crudController->getData();
        $this->assertEquals(array('id' => 1, 'name' => 'John Doe'), array_shift($result));
    }
    /**
     * Tests execute method.
     * 
     * @expectedException \Alpha\Exception\ControllerActionNotFoundException
     * 
     * @return void
     */
    public function testExecute_ActionNameDoesNotExist_ShouldThrowControllerActionNotFoundException()
    {
        $bucket         = $this->getMock('\Alpha\Storage\BucketInterface');        
        $crudController = new CrudBaseController($bucket, null);
        $crudController->execute('crud', 'doesnotexist', array());
    }
}