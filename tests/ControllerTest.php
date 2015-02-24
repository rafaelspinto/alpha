<?php
namespace Tests;

/**
 * Test case for Controller.
 */
class ControllerTest extends \Alpha\Test\Unit\TestCaseAbstract
{
    /**
     * Constructs a ControllerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('ControllerTest');
    }
    
    /**
     * Tests execute method.
     * 
     * @expectedException \Alpha\Exception\ControllerActionNotFoundException
     * 
     * @return void
     */
    public function testExecute_ActionDoesNotExist_ShouldThrowControllerActionNotFoundException()
    {
        $stubController = new \Tests\Stubs\StubController(__DIR__);
        $stubController->execute('stub', 'doesnotexist', array());
    }
    
    /**
     * Tests execute method.
     * 
     * @return void
     */
    public function testExecute_ActionExists_DataShouldAssertEquals()
    {
        $stubController = new \Tests\Stubs\StubController(__DIR__);
        $response       = $stubController->execute('stub', 'getOne', array());
        $this->assertEquals(array('stub' => 'one'), $stubController->getData());
        $this->assertEquals(json_encode(array('stub' => 'one')), $response->getContent());
        $this->assertEquals(\Alpha\Http\ContentType::APPLICATION_JSON, $response->getContentType());
    }
}