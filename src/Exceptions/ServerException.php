<?php
namespace Cloudwelder\PetitionsApi\Exceptions;

class ServerException extends PetitionsApiException
{
    
    private $response;
    
    public function __construct($message = null, $response = null, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
        
        $this->response = $response;
    }
    
    public function getServerResponse() {
        return $this->response; 
    }

}
