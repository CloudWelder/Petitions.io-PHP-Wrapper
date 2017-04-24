<?php
namespace CloudWelder\PetitionsApi;

use Psr\Http\Message\ResponseInterface;

class Response {
    
    /**
     *
     * @var ResponseInterface dsfsdfsdf
     */
    private $guzzleResponse;
    
    private $data;
    
    public function __construct(ResponseInterface $guzzleResponse) {
        $this->guzzleResponse = $guzzleResponse;
    }
    
    public function getStatusCode() {
        return $this->guzzleResponse->getStatusCode();
    }
    
    public function getResponseData() {
        if ($this->data) {
            return $this->data;
        }
        
        $this->data = json_decode($this->guzzleResponse->getBody(), true);
        return $this->data;
    }
}