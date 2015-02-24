<?php
/**                                                                                                                                                                                                                                                                            
 * LanguageConnectorInterface
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Alpha\Connector;

use Alpha\Connector\ConnectorInterface;

/**
 * Defines the interface that the Language Repositories must implement.
 */
interface LanguageConnectorInterface extends ConnectorInterface
{
    /**
     * Returns the value for the string identified by a key.
     * 
     * @param string $key The key.
     * 
     * @return string
     */
    public function getString($key);
    
    /**
     * Returns an array containing the keys and values of the repository.
     * 
     * @return array
     */
    public function getStrings();
}

