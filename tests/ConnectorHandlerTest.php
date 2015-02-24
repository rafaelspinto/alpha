<?php
namespace Tests;

use Alpha\Handler\ConnectorHandler;

/**
 * Dummy class.
 */
class DummyClassDoesNotImplementConnectorInterface
{
    // void
}

/**
 * Dummy class.
 */
class StubImplementsConnectorInterface implements \Alpha\Connector\ConnectorInterface
{
    protected $value;
    
    /**
     * The setup method.
     * 
     * @param array $configuration configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        $this->value = $configuration['value'];
    }
    
    /**
     * Does stuff.
     * 
     * @return string
     */
    public function doStuff()
    {
        return $this->value;
    }
}

/**
 * Test case for ConnectorHandler.
 */
class ConnectorHandlerTest extends \Alpha\Test\Unit\TestCaseAbstract
{
     /**
     * Constructs a ConnectorHandlerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('ConnectorHandlerTest');
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
        $connectorHandler->registerConnector('xpto', 'Tests\StubImplementsConnectorInterface', array('value' => 'Peter Griffin'));
        $connector = $connectorHandler->getConnector('xpto');
        $this->assertEquals('Peter Griffin', $connector->doStuff());
    }
    
    /**
     * Tests getNamespaceForConnector method.
     * 
     * @return void
     */
    public function testGetNamespaceForConnector_ShouldAssertEquals()
    {
        $connectorHandler = new ConnectorHandler('/path/to/connectors/', null);
        $this->assertEquals('\Path\To\Connectors\Xpto\\', $connectorHandler->getNamespaceForConnector('Xpto'));
    }
}

