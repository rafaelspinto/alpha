<?php
/**
 * LayoutTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web\View;

use Alpha\Web\View\ViewTagAbstract;

/**
 * Handles Layout tags.
 */
class IncludeTag extends ViewTagAbstract
{
    /**
     * Constructs a LayoutTag.
     */
    public function __construct()
    {
        parent::__construct('#@include\("?(.*?)"?\)#');
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
            $viewFile = PATH_VIEW . $matches[1][$i];
            if (file_exists($viewFile)) {
                $replaceContent = file_get_contents($viewFile);
            } else {
                $replaceContent = 'layout_not_found:' . $viewFile;
            }
            $content = str_replace($matches[0][$i], $replaceContent, $content);
        }
        return $content;
    }
}
