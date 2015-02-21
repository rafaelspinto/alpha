<?php
/**
 * LanguageHandler
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\Language;

/**
 * Class that handles language.  
 */
class LanguageHandler
{
    protected $languageFilename, $languageCode, $strings, $isLanguageLoaded;
    
    /**
     * Constructs a LanguageHandler.
     * 
     * @param string $languageFilename The filename of the language.
     * @param string $languageCode     The language code.
     */
    public function __construct($languageFilename, $languageCode)
    {
        $this->languageFilename = $languageFilename;
        $this->languageCode     = $languageCode;
        $this->strings          = array();
        $this->isLanguageLoaded = false;
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
            $this->loadStrings();
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
    protected function loadStrings()
    {
        if(file_exists($this->languageFilename)) {
            $this->strings[$this->languageCode] = parse_ini_file($this->languageFilename);
            $this->isLanguageLoaded             = true;        
        }     
    }
}