<?php
/**
 * Response
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Web;

use Alpha\Http\StatusCode;
use Alpha\Http\ContentType;

/**
 * Base class for responses.
 */
class Response
{
    protected $content, $statusCode, $contentType;
    
    /**
     * Constructs a Response.
     * 
     * @param string $content     The content of the response.
     * @param int    $statusCode  The status code.
     * @param string $contentType The type of content.
     */
    public function __construct($content, $statusCode = StatusCode::OK, $contentType = ContentType::TEXT_HTML)
    {        
        $this->content     = $content;
        $this->statusCode  = $statusCode;
        $this->contentType = $contentType;
    }
    
    /**
     * Returns the content.
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the status code.
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the content type.
     * 
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}

