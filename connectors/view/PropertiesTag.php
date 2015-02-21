<?php
/**
 * PropertiesTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Connectors\View\ViewTagAbstract;

/**
 * Handles Properties tags.
 */
class PropertiesTag extends ViewTagAbstract
{
    /**
     * Constructs a PropertiesTag.
     */
    public function __construct()
    {
        parent::__construct('#@\((?!/?\[)(.*?)(?!\])\)#');
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
            $tmpData = explode('.', $matches[1][$i]);
            $count   = count($tmpData);
            if ($count > 1) {
                $value = $this->getValue($tmpData, $data);
            } else {
                $value = isset($data[$matches[1][$i]]) ? $data[$matches[1][$i]] : null;
            }
            $content = str_replace($matches[0][$i], $value, $content);
        }
        return $content;
    } 
}

