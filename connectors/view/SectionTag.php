<?php
/**
 * SectionTag
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Connectors\View;

use Connectors\View\ViewTagAbstract;

/**
 * Handles Section tags.
 */
class SectionTag extends ViewTagAbstract
{
    protected $sections;

    /**
     * Constructs a SectionTag.
     */
    public function __construct()
    {
        parent::__construct('#@section\((.*?)\)(.*?)@/section\(\1\)#si');
        $this->sections = array();
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
            $this->sections[$matches[1][$i]] = array('content' => $matches[0][$i], 'innerContent' => $matches[2][$i]);  
        }
        return $content;
    }
    
    /**
     * Returns the sections.
     * 
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }   
}
