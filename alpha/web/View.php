<?php
/**
 * View
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web;

use Alpha\Connector\ViewConnectorInterface;
use Alpha\Web\View\ViewTagAbstract;
use Alpha\Web\View\PropertiesTag;
use Alpha\Web\View\ForeachTag;
use Alpha\Web\View\IncludeTag;
use Alpha\Web\View\UsesTag;
use Alpha\Web\View\ConditionalPropertiesTag;

/**
 * Base class for Views.
 */
class View implements ViewConnectorInterface
{
    protected $tags;
    
    /**
     * Constructs a View.
     */
    public function __construct()
    {
        $this->tags = array();
        $this->registerTag(new UsesTag());
        $this->registerTag(new IncludeTag());
        $this->registerTag(new ForeachTag());
        $this->registerTag(new PropertiesTag());
        $this->registerTag(new ConditionalPropertiesTag());
    }

    /**
     * Registers a tag in the View.
     * 
     * @param \Alpha\Web\View\ViewTagAbstract $tag The tag to be registered.
     * 
     * @return void
     */
    public function registerTag(ViewTagAbstract $tag)
    {
        $this->tags[] = $tag;
    }
        
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
        foreach ($this->tags as $tag){            
            $content = $tag->render($content, $data);
        }
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
}
