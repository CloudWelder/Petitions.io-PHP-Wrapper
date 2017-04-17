<?php
namespace CloudWelder\PetitionsApi;

class Token {
    
    protected $accessToken;
    
    protected $refreshToken;
    
    protected $expiresIn;
    
    protected $tokenRefreshedAt;
    
    public function __construct($accessToken, $refreshToken, $expiresIn) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn = $expiresIn;
        
        $this->tokenRefreshedAt = time();
    }
    
    public function getTokenRemaingLife() {
        return $this->tokenRefreshedAt + $this->expiresIn - time();
    }
    
    public function isTokenActive() {
        return time() < ($this->tokenRefreshedAt + $this->expiresIn);
    }
    
    public function getAccessToken() {
        return $this->accessToken;
    }    
    
    public function getRefreshToken() {
        return $this->refreshTokenToken;
    }
}