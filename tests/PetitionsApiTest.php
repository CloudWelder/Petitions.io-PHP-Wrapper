<?php

use PHPUnit\Framework\TestCase;
use CloudWelder\PetitionsApi\PetitionsApi;

class PetitionsApiTest extends TestCase
{
    function testApiWrapper() {
        $api = new PetitionsApi(2, 'mock_secret', 'http://example.com/redirect');
        
        $this->assertInstanceOf(PetitionsApi::class, $api);
        
    }
    
    /**
     * 
     * @expectedException CloudWelder\PetitionsApi\Exceptions\InvalidApiCredentialsException
     */
    function testApiCredentialException1() {
        $api = new PetitionsApi('', 'mock_secret', 'http://example.com/redirect');
        
        $dummy = $api->getLoginUrl('http://example.com/redirect');
    }
    
    /**
     *
     * @expectedException CloudWelder\PetitionsApi\Exceptions\InvalidApiCredentialsException
     */
    function testApiCredentialException2() {
        $api = new PetitionsApi(2, '', 'http://example.com/redirect');
        
        $dummy = $api->getLoginUrl('http://example.com/redirect');
    }
    
    function testGetLoginUrl() {
        $api = new PetitionsApi(2, 'mock', 'http://example.com/redirect');
        
        $url = $dummy = $api->getLoginUrl('http://example.com/redirect');
        
        $this->assertEquals('http://oauth.petitions.io/oauth/authorize?client_id=2&redirect_uri=' . urlencode('http://example.com/redirect') . '&response_type=code&scope=', $url);
    }
}