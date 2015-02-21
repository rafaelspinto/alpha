<?php
/**
 * ViewTagAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

/**
 * Base class for view tags.
 */
abstract class ViewTagAbstract
{
    protected $regex;
    
    /**
     * Constructs a ViewTagAbstract.
     * 
     * @param string $regex The regex for the tags.
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

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
        $found   = preg_match_all($this->regex, $content, $matches);
        if($found) {
            $content = $this->handleMatches($content, $data, $matches, $found);
        }
        return $content;
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
        if(count($keys) > 1 && isset($data[$keys[0]])) {
            return $this->getValue(array_slice($keys, 1), $data[$keys[0]]);
        }
        return isset($data[$keys[0]]) ? $data[$keys[0]] : '';
    }
    
    /**
     * Handles the matches found.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * @param array  $matches The matches found.
     * @param int    $found   The number of matches found.
     * 
     * @return string
     */
    abstract protected function handleMatches($content, array $data, array $matches, $found);    
}
