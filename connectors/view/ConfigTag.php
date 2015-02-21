<?php
/**
 * ConfigTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Alpha\Core\Config;
use Connectors\View\ViewTagAbstract;

/**
 * Handles Config tags.
 */
class ConfigTag extends ViewTagAbstract
{
    /**
     * Constructs a ConfigTag.
     */
    public function __construct()
    {
        parent::__construct('#@config\("?(.*?),(.*?)"?\)#');
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
            $content = str_replace($matches[0][$i], Config::get($matches[1][$i], $matches[2][$i]), $content);
        }
        return $content; 
    }
}

