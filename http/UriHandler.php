<?php
/**
 * UriHandler
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Http;

/**
 * Handles URI components.
 */
class UriHandler
{
    // components should by defined as {(type):(name)}
    const COMPONENT_REGEX = '{(.*?):(.*?)}';
    
    protected $pattern, $uri, $components, $componentsAreInit;
    
    /**
     * Constructs an UriHandler.
     */
    public function __construct()
    {
        $this->components        = array();
        $this->componentsAreInit = false;
    }
    
    /**
     * Sets the URI pattern.
     * 
     * @param string $pattern The pattern.
     * 
     * @return void
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
    
    /**
     * Sets the URI.
     * 
     * @param string $uri The URI.
     * 
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    
    /**
     * Returns the value of the URI component.
     * 
     * @param string $componentName The name of the component.
     * 
     * @return mixed
     */
    public function getComponent($componentName)
    {
        if(!$this->componentsAreInit) {
            $this->buildComponents();
        }
        
        return $this->components[$componentName]['value'];
    }
    
    /**
     * Returns the components of the URI.
     * 
     * @return array
     */
    public function getComponents()
    {
        if (!$this->componentsAreInit) {
            $this->buildComponents();
        }

        return $this->components;
    }
        
    /**
     * Builds the URI components from the defined URI.
     * 
     * @return void
     */
    public function buildComponents()
    {
        $this->componentsAreInit = true;        
        $matches                 = array();
        $found                   = preg_match_all(sprintf('#%s#', static::COMPONENT_REGEX), $this->pattern, $matches);
        if($found) {
            $uriComponents   = parse_url($this->uri, PHP_URL_PATH);
            $uriParts        = array_filter(explode('/', $uriComponents));            
            $uriPatternParts = array_filter(explode('/', $this->pattern));            
            for($i=0; $i < $found; $i++){
                $part                              = array_shift($uriParts);
                $patternPart                       = array_shift($uriPatternParts);
                $ignoreValue                       = str_replace($matches[0][$i], '', $patternPart);
                $value                             = str_replace($ignoreValue, '', $part);
                $this->components[$matches[2][$i]] = array(
                                                        'type'  => $matches[1][$i], 
                                                        'raw'   => $value, 
                                                        'value' => $this->makeValue($matches[1][$i], $value)
                                                    ); 
            }
        }
    }
    
    /**
     * Returns the properly casted valued.
     * 
     * @param string $type  The type of the value.
     * @param string $value The value.
     * 
     * @return mixed
     */
    protected function makeValue($type, $value)
    {
        switch ($type) {
            case 'i':
                return (int) $value;
            default :
                return (string) $value;
        }
    }
}
