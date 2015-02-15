<?php
/**
 * ForeachTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web\View;

use Alpha\Web\View\ViewTagAbstract;
use Alpha\Web\View\PropertiesTag;
use Alpha\Web\View\ConditionalPropertiesTag;

/**
 * Handles Foreach tags.
 */
class ForeachTag extends ViewTagAbstract
{
    /**
     * Constructs a ForeachTag.
     */
    public function __construct()
    {
        parent::__construct('#@{foreach (.*?)}(.*?)@{/foreach \1}#si');
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
        $innerContent             = '';
        $propertiesTag            = new PropertiesTag();
        $conditionalPropertiesTag = new ConditionalPropertiesTag();
        for ($i = 0; $i < $found; $i++) {
            $list = $this->getValue(explode('.', $matches[1][$i]), $data);
            if (!empty($list)) {
                foreach ($list as $item) {
                    $itemContent   = $propertiesTag->render($matches[2][$i], $item);
                    $innerContent .= $conditionalPropertiesTag->render($itemContent, $item);                    
                }
            }
            $content = str_replace($matches[0][$i], $innerContent, $content);
        }
        return $content;
    }
}