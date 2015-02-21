<?php
/**
 * StringTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Alpha\Core\Connectors;
use Connectors\View\ViewTagAbstract;

/**
 * Handles String tags.
 */
class StringTag extends ViewTagAbstract
{
    /**
     * Constructs a StringTag.
     */
    public function __construct()
    {
        parent::__construct('#@string\("?(.*?)"?\)#');
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
        $languageConnector = Connectors::get('Language');
        for ($i = 0; $i < $found; $i++) {
            $content = str_replace($matches[0][$i], $languageConnector->getString($matches[1][$i]), $content);
        }
        return $content; 
    }
}