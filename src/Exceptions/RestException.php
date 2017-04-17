<?php
namespace CloudWelder\PetitionsApi\Exceptions;

use Psr\Http\Message\ResponseInterface;

class RestException extends PetitionsApiException
{
    
    /**
     * 
     * @var ResponseInterface
     */
    private $response;
    
    public function __construct($message = null, ResponseInterface $response = null, $code = null, $previous = null) {
        $body = '';
        if ($response) {
            $body = " Server said: " . $response->getBody();
        }
        parent::__construct($message . $body, $code, $previous);
        
        $this->response = $response;
    }
    
    public function getServerResponse() {
        return $this->response; 
    }

}
