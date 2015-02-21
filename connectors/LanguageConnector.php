<?php
/**
 * LanguageConnector
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors;

use Alpha\Core\Config;
use Alpha\Connector\LanguageRepositoryInterface;
use Connectors\Language\LanguageHandler;

/**
 * Language Repository.  
 */
class LanguageConnector implements LanguageRepositoryInterface
{
    protected $languageHandler;

    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        // defaults
        $languageFilename = 'language';
        $languageCode     = 'en';
        
        if(isset($configuration['language']['active'])) {
            $languageCode = $configuration['language']['active'];
        }
        
        if(isset($configuration['filename'])) {
            $languageFilename =  $configuration['filename'];
        }
        
        $languageFile = Config::getProjectPath() . $languageFilename;
        $suffixedFile = Config::getProjectPath() . $languageFilename . '.' . $languageCode;
        if(!file_exists($languageFile) && file_exists($suffixedFile)) {
            $languageFile = $suffixedFile;
        }
        
        $this->languageHandler = new LanguageHandler($languageFile, $languageCode);
    }
    
    /**
     * Returns the value for the string identified by a key.
     * 
     * @param string $key The key.
     * 
     * @return string
     */
    public function getString($key)
    {
        return $this->languageHandler->getString($key);
    }
    
    /**
     * Returns an array containing the keys and values of the repository.
     * 
     * @return array
     */
    public function getStrings()
    {
        return $this->languageHandler->getStrings();
    }    
}