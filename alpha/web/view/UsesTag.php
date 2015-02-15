<?php
/**
 * UsesTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web\View;

use Alpha\Web\View\ViewTagAbstract;
use Alpha\Web\View\SectionTag;

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
        parent::__construct('#@{uses "(.*?)"}#');
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
                $layoutContent = file_get_contents($viewFile);
            } else {
                $layoutContent = 'layout_not_found:' . $viewFile;
            }
        }
        $sections       = $this->getSectionsFromContent($content, $data);
        $layoutSections = $this->getSectionsFromContent($layoutContent, $data);
        $content        = $layoutContent;
        foreach ($sections as $name => $section) {
            $content = str_replace($layoutSections[$name]['content'], $section['innerContent'], $layoutContent);
        }
        return $content;
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
