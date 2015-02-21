<?php
/**
 * UsesTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Alpha\Core\Config;
use Connectors\View\ViewTagAbstract;
use Connectors\View\SectionTag;

/**
 * Handles Uses tags.
 */
class UsesTag extends ViewTagAbstract
{
    /**
     * Constructs a ForeachTag.
     */
    public function __construct()
    {
        parent::__construct('#@uses\("?(.*?)"?\)#');
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
            $viewFile = Config::getViewsPath() . $matches[1][$i];
            if (file_exists($viewFile)) {
                $layoutContent = file_get_contents($viewFile);
            } else {
                $layoutContent = 'layout_not_found:' . $viewFile;
            }
        }
        $sections       = $this->getSectionsFromContent($content, $data);
        $layoutSections = $this->getSectionsFromContent($layoutContent, $data);
        $content        = $layoutContent;
        foreach ($layoutSections as $name => $section) {
            if(isset($section[$name])){
                $layoutContent = str_replace($section['content'], $sections[$name]['innerContent'], $layoutContent);
            }
        }
        return $layoutContent;
    }   

    /**
     * Returns the sections from a given content.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * 
     * @return array
     */
    protected function getSectionsFromContent($content, $data)
    {
        $sectionTag = new SectionTag();
        $sectionTag->render($content, $data);
        return $sectionTag->getSections();
    }
}
