<?php
session_start();
error_reporting(E_ALL); ini_set('display_errors', 'On');
use CloudWelder\PetitionsApi\Exceptions\ServerException;
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi($config['api_key'], $config['client_id'],$config['redirect_path']);

?>
<html>
	<head>
		<title>Redirected Page</title>
	</head>
	<body>
		<?php
		  $code = $_GET['code'];
		  try {
		      $token = $api->getTokenFromAuthCode($code);
		  } catch (ServerException $e) {
		      echo $e->getMessage();
		      print_r($e->getServerResponse()->getResponseData());
		      die();
		  }		  
		 $data= [];
		 $api->withToken($token->getAccessToken());
		  $response = $api->get('users/me',$data);
		  $userDetails = $response->getResponseData();
		  

		 $_SESSION['petetion_access_token'] = $token->getAccessToken();
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
		  
		?>
	</body>
</html>