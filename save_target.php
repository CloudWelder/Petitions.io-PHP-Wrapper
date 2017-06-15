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
    $responseMessage = $e->getMessage();
    $messageArray = explode('{',$responseMessage);
    $messages = '{'.$messageArray['1'];
    $error = json_decode($messages);
    foreach ($error as $errorMessage)	{
        if(is_array($errorMessage)){
            echo $errorMessage[0];
        }else{
            echo $errorMessage;
        }
    }
    exit;
}
$target = $response->getResponseData();
echo "Target Created";

