<?php
/**
 * Response
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Http;

use Alpha\Http\StatusCode;
use Alpha\Http\ContentType;

/**
 * Base class for responses.
 */
class Response
{
    protected $content, $statusCode, $contentType, $headers;
    
    /**
     * Constructs a Response.
     * 
     * @param string $content     The content of the response.
     * @param int    $statusCode  The status code.
     * @param string $contentType The type of content.
     */
    public function __construct($content = null, $statusCode = StatusCode::OK, $contentType = ContentType::TEXT_HTML)
    {        
        $this->content     = $content;
        $this->statusCode  = $statusCode;
        $this->contentType = $contentType;        
        $this->headers     = [];
    }
    
    /**
     * Sets the content.
     * 
     * @param string $content The content of the response.
     * 
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * Sets the HTTP status code.
     * 
     * @param int $statusCode The http status code.
     * 
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
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
     * Sets the content type.
     * 
     * @param string $contentType The content type.
     * 
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader(Header::CONTENT_TYPE, $contentType);
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
    
    /**
     * Sets the headers.
     * 
     * @param array $headers The array containing the headers.
     * 
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Returns the headers of the response.
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Adds an Header to the response.
     * 
     * @param string $name  The name of the header.
     * @param mixed  $value The value of the header.
     * @param int    $code  The http response code.
     * 
     * @return void
     */
    public function addHeader($name, $value, $code = null)
    {
        $this->headers[$name]           = [];
        $this->headers[$name]['value']  = sprintf('%s: %s', $name, $value);
        $this->headers[$name]['status'] = $code;
    }
    
    /**
     * Outputs the headers of the response.
     * 
     * @return void
     */
    public function outputHeaders()
    {
        if(!array_key_exists(Header::CONTENT_TYPE, $this->headers)){
            $this->addHeader(Header::CONTENT_TYPE, $this->getContentType(), $this->getStatusCode());
        }
        
        foreach($this->headers as $header)
        {
            header($header['value'], true, $header['status']);
        }
    }
}