<?php
/**
 * View
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web;

use Alpha\Connector\ViewConnectorInterface;

/**
 * Base class for Views.
 */
class View implements ViewConnectorInterface
{
    const REGEX_DATA   = '#@{(?!/?foreach|layout)(.*?)}#';
    const REGEX_LOOP   = '#@{foreach (.*?)}(.*)@{/foreach \1}#si';
    const REGEX_LAYOUT = '#@{layout "(.*?)"}#';
    
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
        $this->handleLayouts($content);
        $this->handleLoops($content, $data);
        $this->handleDataProperties($content, $data);
        return $content;
    }
    
    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        // void
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
        if(count($keys) > 1) {
            return $this->getValue(array_slice($keys, 1), $data[$keys[0]]);
        }
        return $data[$keys[0]];
    }
    
    /**
     * Includes the content of the LAYOUTS.
     * 
     * @param string $content The original content.
     * 
     * @return string
     */
    protected function handleLayouts(&$content)
    {
        $matches = array();
        $found   = preg_match_all(static::REGEX_LAYOUT, $content, $matches);
        if($found) {
            for($i=0;$i<$found;$i++) {
                $viewFile = PATH_VIEW . $matches[1][$i];
                if(file_exists($viewFile)) {
                    $replaceContent = file_get_contents($viewFile);
                }else {
                    $replaceContent = 'layout_not_found:'.$viewFile;
                }
                $content = str_replace($matches[0][$i], $replaceContent, $content);
            }
        }
        return $content;
    }
    
    /**
     * Renders the content of the FOREACH with the data.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * 
     * @return string
     */
    protected function handleLoops(&$content, &$data)
    {
        $matches = array();
        $found   = preg_match_all(static::REGEX_LOOP, $content, $matches);
        if($found) {
            $innerContent = '';
            for($i=0;$i<$found;$i++) {
                $list = $this->getValue(explode('.', $matches[1][$i]), $data);
                foreach($list as $item) {
                    $innerContent .= $this->render($matches[2][$i], $item);
                }
                $content = str_replace($matches[0][$i], $innerContent, $content);
            }
        }
        return $content;
    }
    
    /**
     * Renders the content of the view with the data.
     * 
     * @param string $content The original content.
     * @param array  $data    The data to bind.
     * 
     * @return string
     */
    protected function handleDataProperties(&$content, &$data)
    {
        $matches = array();
        $found   = preg_match_all(static::REGEX_DATA, $content, $matches);
        if($found) {
            for($i=0; $i < $found; $i++) {
                $tmpData = explode('.', $matches[1][$i]);
                $count   = count($tmpData); 
                if($count > 1) {                    
                    $value = $this->getValue($tmpData, $data);
                }else{
                    $value = $data[$matches[1][$i]];
                }
                $content = str_replace($matches[0][$i], $value, $content);
            }
        }
        return $content;
    }
}
