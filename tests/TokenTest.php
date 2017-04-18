<?php

use PHPUnit\Framework\TestCase;
use CloudWelder\PetitionsApi\Token;

class TokenTest extends TestCase
{
    
    public function testToken() {
        $token = new Token('mock_a_token', 'mock_r_token', 2);
        $this->assertInstanceOf(Token::class, $token);
        
        $this->assertEquals('mock_a_token', $token->getAccessToken());
        $this->assertEquals('mock_r_token', $token->getRefreshToken());
        
        sleep(1);
        
        $this->assertLessThanOrEqual(1, $token->getTokenRemaingLife());
        
        $this->assertTrue($token->isTokenActive());
        
        sleep(1);
        
        $this->assertFalse($token->isTokenActive());
        
    }
}
