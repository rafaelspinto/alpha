<?php
namespace Tests;

use Alpha\Handler\ConnectorHandler;

class DummyClassDoesNotImplementConnectorInterface
{
    // void
}

class DummyImplementsConnectorInterface implements \Alpha\Connector\ConnectorInterface
{
    protected $value;
    public function setup(array $configuration)
    {
        $this->value = $configuration['value'];
    }
    
    public function doStuff()
    {
        return $this->value;
    }
}

class ConnectorHandlerTestCase extends \Alpha\Test\Unit\TestCaseAbstract
{
     /**
     * Constructs a ConnectorHandlerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('ConnectorHandlerTestCase');
    }
    
    /**
     * Tests getConnector method.
     * 
     * @expectedException \Alpha\Exception\ConnectorNotFoundException
     * 
     * @return void
     */
    public function testGetConnector_ConnectorDoesNotExist_ShouldThrowConnectorNotFoundException()
    {
        $connectorHandler = new ConnectorHandler(null, null);
        $connectorHandler->getConnector('xpto');
    }
    
    /**
     * Tests getConnector method.
     * 
     * @expectedException \Alpha\Exception\InterfaceNotImplementedException
     * 
     * @return void
     */
    public function testGetConnector_ConnectorDoesNotImplementInterface_ShouldThrowInterfaceNotImplementedException()
    {
        $connectorHandler = new ConnectorHandler(null, null);
        $connectorHandler->registerConnector('xpto', 'Tests\DummyClassDoesNotImplementConnectorInterface', array());
        $connectorHandler->getConnector('xpto');
    }
    
    /**
     * Tests getConnector method.
     * 
     * @return void
     */
    public function testGetConnector_ConnectorImplementsInterface_ShouldThrowInterfaceNotImplementedException()
    {
        $connectorHandler = new ConnectorHandler(null, null);
        $connectorHandler->registerConnector('xpto', 'Tests\DummyImplementsConnectorInterface', array('value' => 'Peter Griffin'));
        $connector = $connectorHandler->getConnector('xpto');
        $this->assertEquals('Peter Griffin', $connector->doStuff());
    }
}

