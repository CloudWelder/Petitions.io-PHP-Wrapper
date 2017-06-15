<?php
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi ();
$api->withToken ( $config ['access_token'] );

$data['name']= isset($_POST['name'])? $_POST['name']:null;
$data['country_code']= isset($_POST['country_code'])? $_POST['country_code']:null;
$data['title']= isset($_POST['title'])?$_POST['title'] :null;
$data['type']= isset($_POST['type'])?$_POST['type'] :null;
$data['state']= isset($_POST['state'])? $_POST['state']:null;
$data['city']= isset($_POST['city'])?$_POST['city'] :null;
$data['avatar_url']= isset($_POST['avatar_url'])? $_POST['avatar_url']:null;
$data['avatar_media_id']= isset($_POST['avatar_media_id'])?$_POST['avatar_media_id']:null;
$data['owner_organization_id']= isset($config['organisation_id'])? $config['organisation_id']:null;

try{
    $response = $api->post('targets',$data);
} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
    
    $response = $e->getServerResponse();
    $responseCode = $response->getStatusCode();
    switch ($responseCode) {
        case 400:
            $message = 'Bad Request';
            break;
        case 401:
            $message ='Unauthorized: Check your access token';
            break;
        case 403:
            $message ='Forbidden: Missing permission';
            break;
        case 404:
            $message ='Not Found: Check your API endpoint url';
            break;
        case 409:
            $message ='Not Allowed: The specified operation is not allowed at the moment';
            break;
        case 422:
            $message ='Unprocessable Entity: Make sure the required parameters present and are valid';
            break;
        default:
            $message ='Request Failed.';
            break;
    }
    echo $message;
    exit;
}
$target = $response->getResponseData();
echo "Success";

