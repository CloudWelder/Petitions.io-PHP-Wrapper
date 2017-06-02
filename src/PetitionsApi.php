<?php
namespace CloudWelder\PetitionsApi;

use GuzzleHttp\Client as GuzzleClient;
use CloudWelder\PetitionsApi\Exceptions\RestException;
use CloudWelder\PetitionsApi\Exceptions\InvalidApiCredentialsException;
use CloudWelder\PetitionsApi\Exceptions\OAuthException;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;

class PetitionsApi
{
    /**
     * Basic api url
     */
    const API_ROOT = 'http://api.petitions.io/api/';
    const OAUTH_SERVER = 'http://oapp.petitions.io/';    
    
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    const GRANT_AUTHCODE = 'authorization_code';
    const GRANT_PASSWORD = 'password';
    /**
     *
     * @var string Access token for a user
     */
    protected $accessToken;
    
    /**
     *
     * @var string The client id
     */
    protected $clientId;
    
    /**
     *
     * @string Client secret for the API
     */
    protected $clientSecret;
    
    /**
     *
     * @string Url to redirect to
     */
    protected $redirectUri;
    
    /**
     *
     * @param string $clientId Your petitions.io API client id
     * @param string $clientSecret Your petitions.io API client secret
     * @param string $redirectUri Redirect URI registered with petitions.io
     */
    public function __construct($clientId = null, $clientSecret = null, $redirectUri = null) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }
    
    /**
     * Set client credntials
     * 
     * @param string $clientId Your petitions.io API client id
     * @param string $clientSecret Your petitions.io API client secret
     * @param string $redirectUri Redirect URI registered with petitions.io
     */
    public function setClientCredentials($clientId, $clientSecret, $redirectUri) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }
    
    /**
     *
     * Make a GET API call.
     *
     * @param string $url API endpoint (relative)
     * @param array $data Any query parameters
     *
     * @throws InvalidApiCredentialsException
     * @throws RestException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function get($url, $data = []) {
        return $this->makeRequest($url, self::METHOD_GET, $data);
    }
    
    /**
     *
     * Make a POST API call.
     *
     * @param string $url API endpoint (relative)
     * @param array $data Any data to be sent as JSON body
     *
     * @throws InvalidApiCredentialsException
     * @throws RestException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function post($url, $data = []) {
        return $this->makeRequest($url, self::METHOD_POST, $data);
    }
    
    /**
     *
     * Make a PUT API call.
     *
     * @param string $url API endpoint (relative)
     * @param array $data Any data to be sent as JSON body
     *
     * @throws InvalidApiCredentialsException
     * @throws RestException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function put($url, $data = []) {
        return $this->makeRequest($url, self::METHOD_PUT, $data);
    }
    
    /**
     *
     * Make a DELETE API call.
     *
     * @param string $url API endpoint (relative)
     *
     * @throws InvalidApiCredentialsException
     * @throws RestException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function delete($url) {
        return $this->makeRequest($url, self::METHOD_DELETE);
    }
    
    /**
     * Set the access token to be used for any further API requests.
     *
     * @param string $token
     */
    public function withToken($token) {
        $this->accessToken = $token;
        
        return $this;
    }
    
    
    /**
     * Returns the URL users should be redirected to for them to login.
     *
     * @param string[] An array of scopes you require
     *
     * @throws InvalidApiCredentialsException
     *
     * @return string
     */
    public function getLoginUrl($scopes = []) {
        if (! $this->clientId || $this->clientId == '' ) {
            throw new InvalidApiCredentialsException('Client ID is missing');
        }
        
        if (! $this->clientSecret || $this->clientSecret == '') {
            throw new InvalidApiCredentialsException('Client Secret is missing');
        }
        
        $scopeStr = '';
        if (! empty($scopes)) {
            $scopes = array_map(function($a) {return trim($a);}, $scopes);
            $scopeStr = trim(implode(' ', $scopes));
        }
        
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => $scopeStr
        ]);
        
        return self::OAUTH_SERVER . "/oauth/authorize?" . $query;
    }
    
    /**
     * Retrieve/generate token from authorization code.
     *
     * @param string $authCode Authorization code from petitions.io
     *
     * @throws InvalidApiCredentialsException
     * @throws OAuthException
     *
     * @return \Cloudwelder\PetitionsApi\Token
     */
    public function getTokenFromAuthCode($authCode) {
        if (! $this->clientId || $this->clientId == '' ) {
            throw new InvalidApiCredentialsException('Client ID is missing');
        }
        
        if (! $this->clientSecret || $this->clientSecret == '') {
            throw new InvalidApiCredentialsException('Client Secret is missing');
        }
        
        $http = new GuzzleClient;
        try {
            $response = $http->post(self::OAUTH_SERVER . '/oauth/token', [
                'form_params' => [
                    'grant_type' => self::GRANT_AUTHCODE,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $this->redirectUri,
                    'code' => $authCode,
                ],
            ]);
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $resp = $e->getResponse();
            throw new OAuthException('oAuth Server Error', $resp, $resp->getStatusCode());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $resp = $e->getResponse();
            throw new OAuthException('oAuth Server Error', $resp, $resp->getStatusCode());
        }
        
        
        $petitionsResponse = new Response($response);
        
        if ($petitionsResponse->getStatusCode() == 200) {
            $data = $petitionsResponse->getResponseData();
            
            //Create a token object.
            $token = new Token($data['access_token'], $data['refresh_token'], $data['expiry_in']);
        } else {
            throw new OAuthException('Request Failed.', $response, $response->getStatusCode());
        }
        
        return $token;
    }
    
    
    /**
     * Get access token in exchange for provding username and password.
     * 
     * @param string $username Username
     * @param string $password Password
     * @param array  $scopes Scopes
     * 
     * @throws InvalidApiCredentialsException
     * @throws OAuthException
     *
     * @return \Cloudwelder\PetitionsApi\Token
     */
    public function getTokenFromCredentials($username, $password, $scopes = []) {
        if (! $this->clientId || $this->clientId == '' ) {
            throw new InvalidApiCredentialsException('Client ID is missing');
        }
        
        if (! $this->clientSecret || $this->clientSecret == '') {
            throw new InvalidApiCredentialsException('Client Secret is missing');
        }
        
        $scopeStr = '';
        if (! empty($scopes)) {
            $scopes = array_map(function($a) {return trim($a);}, $scopes);
            $scopeStr = trim(implode(' ', $scopes));
        }
        
        $http = new GuzzleClient;
        try {
            $response = $http->post(self::OAUTH_SERVER . '/oauth/token', [
                'form_params' => [
                    'grant_type' => self::GRANT_PASSWORD,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'username' => $username,
                    'password' => $password,
                    'scope' =>  $scopeStr
                ],
            ]);
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $resp = $e->getResponse();
            throw new OAuthException('oAuth Server Error', $resp, $resp->getStatusCode());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $resp = $e->getResponse();
            throw new OAuthException('oAuth Server Error', $resp, $resp->getStatusCode());
        }
        
        
        $petitionsResponse = new Response($response);
        
        if ($petitionsResponse->getStatusCode() == 200) {
            $data = $petitionsResponse->getResponseData();
            
            //Create a token object.
            $token = new Token($data['access_token'], $data['refresh_token'], $data['expiry_in']);
        } else {
            throw new OAuthException('Request Failed.', $response, $response->getStatusCode());
        }
        
        return $token;
    }
    
    /**
     * Refresh/re-generate token from refres token
     *
     * @param string $refreshToken Previously obtained refresh token.
     *
     * @throws OAuthException
     *
     * @return \Cloudwelder\PetitionsApi\Token
     */
    public function getTokenFromRefreshToken($refreshToken, $scopes = []) {
        $http = new GuzzleHttp\Client;
        
        $scopeStr = '';
        if (! empty($scopes)) {
            $scopes = array_map(function($a) {return trim($a);}, $scopes);
            $scopeStr = trim(implode(' ', $scopes));
        }
        
        $response = $http->post(self::OAUTH_SERVER . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => $scopeStr,
            ],
        ]);
        
        $petitionsResponse = new Response($response);
        
        if ($petitionsResponse->getStatusCode() == 200) {
            $data = $petitionsResponse->getResponseData();
            
            //Create a token object.
            $newToken = new Token($data['access_token'], $data['refresh_token'], $data['expiry_in']);
        } else {
            throw new OAuthException('Request Failed.', $response, $response->getStatusCode());
        }
        
        return $newToken;
    }
    
    
    /**
     *
     * Make an API call.
     *
     * @param string $url
     * @param string $method
     * @param array $data
     *
     * @throws InvalidApiCredentialsException
     * @throws RestException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    private function makeRequest($url, $method = self::METHOD_GET, $data = []) {
        if (! $this->accessToken || $this->accessToken == '') {
            throw new InvalidApiCredentialsException('Access token missing/expired.');
        }
        
        //Create a guzzle request.        
        $httpClient = new GuzzleClient([
            'base_uri'  =>  self::API_ROOT,
            
        ]);
        
        //Set up the auth headers.
        $requestOptions = [
            'headers'   =>  [
                'Authorization' =>  'Bearer ' . $this->accessToken
            ]
        ];
        
        //Add any data
        if (! empty($data)) {
            if ($method == self::METHOD_GET) {
                //Append additional parameters to the url.
                $requestOptions['query'] = $data;
            } else {
                $requestOptions['json'] = $data;
            }
        }
        
        try {
            $response = $httpClient->request($method, $url, $requestOptions);
            $petitionsResponse = new Response($response);
            
            $responseCode = $response->getStatusCode();
            
            if ($responseCode >= 200 && $responseCode <= 210) {
                return $petitionsResponse;
            }
            
            throw new RestException('Unknown error', $response, $responseCode);
            
        } catch (ClientException $e) {
            $this->handleRestException($e);
        }
    }
    
    private function handleRestException(ClientException $e) {
        $response = $e->getResponse();
        $responseCode = $response->getStatusCode();
        
        switch ($responseCode) {
            case 400:
                throw new RestException('Bad Request', $response, $responseCode);
                break;
            case 401:
                throw new RestException('Unauthorized: Check your access token', $response, $responseCode);
                break;
            case 403:
                throw new RestException('Forbidden: Missing permission', $response, $responseCode);
                break;
            case 404:
                throw new RestException('Not Found: Check your API endpoint url', $response, $responseCode);
                break;
            case 409:
                throw new RestException('Not Allowed: The specified operation is not allowed at the moment', $response, $responseCode);
                break;
            case 422:
                throw new RestException('Unprocessable Entity: Make sure the required parameters present and are valid', $response, $responseCode);
                break;
            default:
                throw new RestException('Request Failed.', $response, $responseCode);
                break;
        }
    }
}