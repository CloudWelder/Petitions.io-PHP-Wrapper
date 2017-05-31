<?php
session_start();
error_reporting(E_ALL); ini_set('display_errors', 'On');
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi($config['client_id'], $config['api_key'],$config['redirect_path']);
if(isset($_POST['submit'])){
	$data['username']= $_POST['username'];
	$data['password']= $_POST['password'];
	try{
	$response = $api->getTokenFromCredentials($data['username'],$data['password'],array("*"));
	}catch( Exception $e ){
		if ($e instanceof CloudWelder\PetitionsApi\Exceptions\OAuthException OR $e instanceof  CloudWelder\PetitionsApi\Exceptions\RestException) {
			$responseMessage = $e->getMessage();
			$message = str_replace("oAuth Server Error Server said: ", "", $responseMessage);
			$error = json_decode($message);
			$_SESSION['form']['error']= true;
			$_SESSION['form']['message'] = $error->message;
			header("Location: login.php");
			exit;
		} else {
			// Keep throwing it.
			throw $e;
		}
	}
	$_SESSION['petetion_access_token'] = $response->getAccessToken();
	$data = [];
	try{
		$api->withToken($response->getAccessToken());
		$response = $api->get('users/me',$data);
		
		}catch( Exception $e ){
		if ($e instanceof CloudWelder\PetitionsApi\Exceptions\OAuthException OR $e instanceof  CloudWelder\PetitionsApi\Exceptions\RestException) {
			$responseMessage = $e->getMessage();
			$_SESSION['form']['error']= true;
			$_SESSION['form']['message'] = $responseMessage;
			header("Location: login.php");
			exit;
		} else {
			// Keep throwing it.
			throw $e;
		}
	}
	$userDetails = $response->getResponseData();
	$_SESSION['first_name']= $userDetails['first_name'];
	$_SESSION['last_name'] = $userDetails['last_name'];
	
	$domain = $_SERVER['HTTP_HOST'];
	$docRoot = $_SERVER['DOCUMENT_ROOT'];
	$dirRoot = dirname(__FILE__);
	$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
	$urlDir = str_replace('/include', '/',str_replace($docRoot, '', $dirRoot));
	$urlDir = str_replace($docRoot, '', $dirRoot);
	$site_path = $protocol.$domain.$urlDir;
	
	//You can redirect them to a members-only page.
	header('Location: '.$site_path.'/dashboard.php');
}else {

header("Location : login.php");

}
?>
