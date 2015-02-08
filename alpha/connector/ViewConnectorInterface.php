<?php
/**                                                                                                                                                                                                                                                                            
 * ViewConnectorInterface
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Alpha\Connector;

/**
 * Defines the interface the View Renderers must implement.
 */
interface ViewConnectorInterface extends ConnectorInterface
{
    /**
     * Renders the content of the view with the data.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * 
     * @return string
     */
    function render($content, array $data);
}

