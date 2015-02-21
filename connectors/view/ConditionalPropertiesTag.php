<?php
/**
 * ConditionalPropertiesTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Connectors\View\ViewTagAbstract;

/**
 * Handles condition Properties tags.
 */
class ConditionalPropertiesTag extends ViewTagAbstract
{
    /**
     * Constructs a ConditionalPropertiesTag.
     */
    public function __construct()
    {
        parent::__construct('#@\(\[(.*?)((!|=|<|>)(.*?))\]\)(.*?)@/\(\[\1\2\]\)#si');
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
    protected function handleMatches($content, array $data, array $matches, $found)
    {
        for ($i = 0; $i < $found; $i++) {
            $propertyName   = $matches[1][$i];
            $comparator     = $matches[3][$i];
            $valueToCompare = $matches[4][$i];
            $value          = isset($data[$propertyName]) ? $data[$propertyName] : null;
            if($this->isToShow($value, $valueToCompare, $comparator)) {
                $content = str_replace($matches[0][$i], $matches[5][$i], $content);
            }else {
                $content = str_replace($matches[0][$i], '', $content);
            }
        }
        return $content;
    }
    
    /**
     * Checks if the content is to be shown.
     * 
     * @param string $value          The value.
     * @param string $valueToCompare The value to compare to.
     * @param string $comparator     The comparator.
     * 
     * @return boolean
     */
    protected function isToShow($value, $valueToCompare, $comparator)
    {
        $isToShow = false;
        if (!empty($comparator)) {
            switch ($comparator) {
                case '!':
                    $isToShow = !empty($valueToCompare) ? $value != $valueToCompare : !$value;
                    break;
                case '=':
                    $isToShow = ( strcmp((string) $value, (string) $valueToCompare) === 0 );
                    break;
                case '<':
                    $isToShow = $value < $valueToCompare;
                    break;
                case '>':
                    $isToShow = $value > $valueToCompare;
                    break;
            }
        }
        return $isToShow;
    }
}
