<?php
namespace Tests;

use Alpha\Handler\RouteHandler;
use Alpha\Handler\UriHandler;

/**
 * Test case for RouteHandler.
 */
class RouteHandlerTest extends \Alpha\Test\Unit\TestCaseAbstract
{
    /**
     * Constructs a RouteHandlerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('RouteHandlerTest');
    }
    
    /**
     * Tests makeControllerFilename method.
     * 
     * @return void
     */
    public function testMakeControllerFilename_NameIsDefined_ShouldAssertEquals()
    {
        $routeHandler = new RouteHandler(new UriHandler(), '/', null, null, null);
        $this->assertEquals('/XptoController.php', $routeHandler->makeControllerFilename('Xpto'));
    }
    
    /**
     * Tests buildActionMethodName method.
     * 
     * @return void
     */
    public function testBuildActionMethodName_ActionNameIsInteger_shouldReturnInteger()
    {
        $routeHandler = new RouteHandler(new UriHandler(), '/', null, null, null);
        $this->assertEquals(123, $routeHandler->buildActionMethodName(123));
    }
    
    /**
     * Tests buildActionMethodName method.
     * 
     * @return void
     */
    public function testBuildActionMethodName_ActionNameIsXpto_shouldReturnGetXpto()
    {
        $routeHandler = new RouteHandler(new UriHandler(), '/', null, null, null);        
        $this->assertEquals('getXpto', $routeHandler->buildActionMethodName('xpto'));
    }
    
    /**
     * Tests makeController method.
     * 
     * @expectedException \Alpha\Exception\ControllerNotFoundException
     * 
     * @return void
     */
    public function testMakeController_ControllerDoesNotExist_shouldThrowControllerNotFoundException()
    {
        $routeHandler = new RouteHandler(new UriHandler(), '/', null, null, null);        
        $routeHandler->makeController('xpto');
    }
    
    /**
     * Tests makeController method.
     * 
     * @return void
     */
    public function testMakeController_ControllerExists_ShouldReturnMockController()
    {
        $mocksDir     = __DIR__ . DIRECTORY_SEPARATOR . 'mocks' . DIRECTORY_SEPARATOR;
        $routeHandler = new RouteHandler(new UriHandler(), $mocksDir, null, null, null);        
        $this->assertTrue($routeHandler->makeController('Mock') instanceof \Tests\Mocks\MockController);
    }
    
    /**
     * Tests makeController method.
     * 
     * @return void
     */
    public function testMakeController_DefaultControllerExists_ShouldReturnMockDefaultController()
    {
        $mocksDir     = __DIR__ . DIRECTORY_SEPARATOR . 'mocks' . DIRECTORY_SEPARATOR;
        $routeHandler = new RouteHandler(new UriHandler(), $mocksDir, null, null, 'MockDefault');
        $this->assertTrue($routeHandler->makeController('Mock2') instanceof \Tests\Mocks\MockDefaultController);
    }
    
    /**
     * Tests makeController method.
     * 
     * @return void
     */
    public function testMakeController_ControllerDoesNotExistButBucketExists_ShouldReturnMockCrudBaseController()
    {
        $mocksDir     = __DIR__ . DIRECTORY_SEPARATOR . 'mocks' . DIRECTORY_SEPARATOR;
        $routeHandler = new RouteHandler(new UriHandler(), $mocksDir, $mocksDir, null, 'MockDefault');
        $this->assertTrue($routeHandler->makeController('MockBucket') instanceof \Alpha\Controller\CrudBaseController);
    }
    
    /**
     * Tests execute method.
     * 
     * @return void
     */
    public function testExecute_ControllerIsStub_ShouldReturnJsonResponseOne()
    {
        $mocksDir     = __DIR__ . DIRECTORY_SEPARATOR . 'mocks' . DIRECTORY_SEPARATOR;
        $stubsDir     = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;
        $uriHandler   = new UriHandler();
        $uriHandler->setPattern('/{s:controller}/{s:action}/{i:id}');
        $routeHandler = new RouteHandler($uriHandler, $stubsDir, $mocksDir, null, 'MockDefault');        
        $this->assertEquals(json_encode(array('stub' => 'one')), $routeHandler->go('/Stub/one')->getContent());
    }
}