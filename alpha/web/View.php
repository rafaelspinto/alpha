<?php
/**
 * View
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web;

use Alpha\Connector\ViewConnectorInterface;

/**
 * Base class for Views.
 */
class View implements ViewConnectorInterface
{
    const REGEX_DATA = '/@{(.*)}/';
    
    /**
     * Renders the content of the view with the data.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * 
     * @return string
     */
    public function render($content, array $data)
    {
        $matches = array();
        $found   = preg_match_all(static::REGEX_DATA, $content, $matches);
        if($found) {
            for($i=0; $i < $found; $i++) {
                $tmpData = explode('.', $matches[1][$i]);
                $count   = count($tmpData); 
                if($count > 1) {                    
                    $value = $this->getValue($tmpData, $data);
                }else{
                    $value = $data[$matches[1][$i]];
                }
                $content = str_replace($matches[0][$i], $value, $content);
            }
        }
        return $content;
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
        // void
    }
    
    /**
     * Returns the value of the placeholder by providing the path(keys) and the array.
     * 
     * @param array $keys The array containing the keys that identify the path.
     * @param array $data The array containing the data.
     * 
     * @return string
     */
    protected function getValue($keys, $data)
    {
        if(count($keys) > 1) {
            return $this->getValue(array_slice($keys, 1), $data[$keys[0]]);
        }
        return $data[$keys[0]];
    }
}
