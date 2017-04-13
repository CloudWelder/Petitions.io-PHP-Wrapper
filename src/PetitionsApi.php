<?php
namespace Cloudwelder\PetitionsApi;

use GuzzleHttp\Client as GuzzleClient;
use Cloudwelder\PetitionsApi\Exceptions\ServerException;
use Cloudwelder\PetitionsApi\Exceptions\InvalidApiCredentialsException;

class PetitionsApi
{
    /**
     * Basic api url
     */
    const API_ROOT = 'http://api.petitions.io/';
    
    
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
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
     * @param string $clientId Your petitions.io API client id
     * @param string $clientSecret Your petitions.io API client secret
     */
    public function __construct($clientId, $clientSecret) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }
    
    /**
     *
     * Make a GET API call.
     *
     * @param string $url API endpoint (relative)
     * @param array $data Any query parameters
     *
     * @throws InvalidApiCredentialsException
     * @throws ServerException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function get($url, $data) {
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
     * @throws ServerException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function post($url, $data) {
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
     * @throws ServerException
     *
     * @return \Cloudwelder\PetitionsApi\Response
     */
    public function put($url, $data) {
        return $this->makeRequest($url, self::METHOD_PUT, $data);
    }
    
    /**
     *
     * Make a DELETE API call.
     *
     * @param string $url API endpoint (relative)
     *
     * @throws InvalidApiCredentialsException
     * @throws ServerException
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
    }
    
    
    /**
     * Returns the URL users should be redirected to for them to login.
     * 
     * @param string $redirectUri The url to redirect to after succesful login
     * 
     * @throws InvalidApiCredentialsException
     * 
     * @return string
     */
    public function getLoginUrl($redirectUri) {
        if (! $this->clientId || $this->clientId == '' ) {
            throw new InvalidApiCredentialsException('Client ID is missing');
        }
        
        if (! $this->clientSecret || $this->clientSecret == '') {
            throw new InvalidApiCredentialsException('Client Secret is missing');
        }       
        
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => '',
        ]);
        
        return self::API_ROOT . "/oauth/authorize?" . $query;
    }
    
    /**
     * Retrieve/generate token from authorization code.
     * 
     * @param string $authCode Authorization code from petitions.io 
     * 
     * @throws InvalidApiCredentialsException
     * @throws ServerException
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
        
        $response = $http->post(self::API_ROOT . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
            ],
        ]);
        
        $petitionsResponse = new Response($response);
        
        if ($petitionsResponse->getStatusCode() == 200) {
            $data = $petitionsResponse->getResponseData();
            
            //Create a token object.
            $token = new Token($data['access_token'], $data['refresh_token'], $data['expiry_in']);
        } else {
            throw new ServerException('Request Failed.', $petitionsResponse, $petitionsResponse->getStatusCode());
        }
        
        return $token;
    }
    
    /**
     * Refresh/re-generate token from refres token
     * 
     * @param string $refreshToken Previously obtained refresh token.
     * 
     * @throws ServerException
     * 
     * @return \Cloudwelder\PetitionsApi\Token
     */
    public function getTokenFromRefreshToken($refreshToken) {
        $http = new GuzzleHttp\Client;
        
        $response = $http->post('http://your-app.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => '',
            ],
        ]);
        
        $petitionsResponse = new Response($response);
        
        if ($petitionsResponse->getStatusCode() == 200) {
            $data = $petitionsResponse->getResponseData();
            
            //Create a token object.
            $newToken = new Token($data['access_token'], $data['refresh_token'], $data['expiry_in']);
        } else {
            throw new ServerException('Request Failed.', $petitionsResponse, $petitionsResponse->getStatusCode());
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
     * @throws ServerException
     * 
     * @return \Cloudwelder\PetitionsApi\Response
     */
    private function makeRequest($url, $method = self::METHOD_GET, $data = []) {        
        //Make sure the API credentials are present.
        if (! $this->clientId || $this->clientId == '' ) {
            throw new InvalidApiCredentialsException('Client ID is missing');
        }
        
        if (! $this->clientSecret || $this->clientSecret == '') {
            throw new InvalidApiCredentialsException('Client Secret is missing');
        }        
        
        if (! $this->accessToken || ! $this->accessToken == '') {
            throw new InvalidApiCredentialsException('Access token missing/expired.');
        }
        
        //Create a guzzle request.
        
        $httpClient = new GuzzleClient([
            'base_uri'  =>  self::API_ROOT,
            
        ]);
        
        //Set up the auth headers.
        $requestOptions = [
            'headers'   =>  [
                'Authorization' =>  $this->accessToken
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
        
        
        $response = $httpClient->request($method, $url, $requestOptions);
        $petitionsResponse = new Response($response);
        
        $responseCode = $response->getStatusCode();
        
        if ($responseCode >= 200 && $responseCode <= 210) {
            return $petitionsResponse;
        }
        
        switch ($responseCode) {
            case 400:
                throw new ServerException('Bad Request', $petitionsResponse, $responseCode);
                break;
            case 401:
                throw new ServerException('Unauthorized: Check your access token', $petitionsResponse, $responseCode);
                break;
            case 403:
                throw new ServerException('Forbidden: Missing permission', $petitionsResponse, $responseCode);
                break;
            case 404:
                throw new ServerException('Not Found: Check your API endpoint url', $petitionsResponse, $responseCode);
                break;
            case 409:
                throw new ServerException('Not Allowed: The specified operation is not allowed at the moment', $petitionsResponse, $responseCode);
                break;
            case 422:
                throw new ServerException('Unprocessable Entity: Make sure the required parameters present and are valid', $petitionsResponse, $responseCode);
                break;
            default:
                throw new ServerException('Request Failed.', $petitionsResponse, $responseCode);
                break;
        }
        
    }
}