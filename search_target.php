<?php
session_start ();
error_reporting ( E_ALL );
ini_set ( 'display_errors', 'On' );
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi ();
$api->withToken ( $config ['access_token'] );

$filter = trim ( strip_tags ( $_GET ['term'] ) );
$organizationId = ( int ) $config ['organisation_id'];

$data = [ 
		'per_page' => '20',
		'page' => '1',
		'filter' => $filter 
];
try {
	$response = $api->get ( "organizations/$organizationId/targets", $data );
} catch ( CloudWelder\PetitionsApi\Exceptions\RestException $e ) {
	
	$response = $e->getServerResponse ();
	$responseCode = $response->getStatusCode ();
	switch ($responseCode) {
		case 400 :
			$message = 'Bad Request';
			break;
		case 401 :
			$message = 'Unauthorized: Check your access token';
			break;
		case 403 :
			$message = 'Forbidden: Missing permission';
			break;
		case 404 :
			$message = 'Not Found: Check your API endpoint url';
			break;
		case 409 :
			$message = 'Not Allowed: The specified operation is not allowed at the moment';
			break;
		case 422 :
			$message = 'Unprocessable Entity: Make sure the required parameters present and are valid';
			break;
		default :
			$message = 'Request Failed.';
			break;
	}
	return json_encode($message);
}

$a_json = array();
$a_json_row = array();
$targetList = $response->getResponseData ();
foreach ($targetList['data'] as $target){
	$a_json_row["value"] = $target['id'];
	$a_json_row["label"] = $target['name'];
	array_push($a_json, $a_json_row);
}
echo json_encode($a_json);
flush();

?>