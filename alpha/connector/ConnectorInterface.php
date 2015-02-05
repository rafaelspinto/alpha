<?php                                                                                                                                                                                                                                                                          
/**                                                                                                                                                                                                                                                                            
 * ConnectorInterface
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */                                                                                                                                                                                                                                                                            
namespace Alpha\Connector;

/**
 * Defines the interface that the connectors must implement.
 */
interface ConnectorInterface
{
    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    function setup(array $configuration);
}