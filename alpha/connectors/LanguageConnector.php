<?php
/**
 * LanguageConnector
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Connectors;

use Alpha\Core\Config;
use Alpha\Connector\LanguageRepositoryInterface;

/**
 * Language Repository.  
 */
class LanguageConnector implements LanguageRepositoryInterface
{
    protected $languageFilename, $languageCode, $strings, $isLanguageLoaded;
    
    /**
     * Constructs a LanguageRepository.
     */
    public function __construct()
    {
        $this->languageFilename = 'language';
        $this->languageCode     = 'en';
        $this->strings          = array();
        $this->isLanguageLoaded = false;
    }
    
    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        if(isset($configuration['language']['active'])) {
            $this->languageCode = $configuration['language']['active'];
        }
        
        if(isset($configuration['filename'])) {
            $this->languageFilename =  $configuration['filename'];
        }                
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
        if(!$this->isLanguageLoaded) {
            $this->loadLanguage();
        }
        return isset($this->strings[$this->languageCode][$key]) ? $this->strings[$this->languageCode][$key] : $key;
    }
    
    /**
     * Returns an array containing the keys and values of the repository.
     * 
     * @return array
     */
    public function getStrings()
    {
        return $this->strings;
    }
    
    /**
     * Loads the language into the strings array.
     * 
     * @return void
     */
    protected function loadLanguage()
    {
        $languageFile = Config::getProjectPath() . $this->languageFilename;
        if(file_exists($languageFile)) {
            $this->strings          = parse_ini_file($languageFile, true);
            $this->isLanguageLoaded = true;
            return;
        }
        
        $suffixedFile = Config::getProjectPath() . $this->languageFilename . '.' . $this->languageCode;
        if(file_exists($suffixedFile)) {
            $this->strings[$this->languageCode] = parse_ini_file($suffixedFile);
            $this->isLanguageLoaded             = true;
        }
    }
}

