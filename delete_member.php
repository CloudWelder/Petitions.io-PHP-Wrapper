<?php
ob_start();
include 'includes/header.php';
if (isset ( $_GET['organization_id'] ) && isset ( $_GET['user_id'] )) {
	$data = [ ];
	try {
		$response = $api->delete( "organizations/".$_GET['organization_id']."/members/".$_GET['user_id'], $data );
	} catch ( CloudWelder\PetitionsApi\Exceptions\RestException $e ) {
		$responseMessage = $e->getMessage ();
		$messageArray = explode ( '{', $responseMessage );
		$messages = '{' . $messageArray ['1'];
		$error = json_decode ( $messages );
		$_SESSION ['form'] ['error'] = true;
		$_SESSION ['form'] ['message'] = '';
		foreach ( $error as $errorMessage ) {
			if (is_array ( $errorMessage )) {
				$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . $errorMessage [0];
			} else {
				$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . $errorMessage;
			}
		}
		header ( "Location: organisations.php" );
		exit ();
	}
	$_SESSION ['form'] ['success'] = true;
	$_SESSION ['form'] ['message'] = "Member deleted ";
} else {
	$_SESSION ['form'] ['error'] = true;
	$_SESSION ['form'] ['message'] = "Couldnt identify and delete the member ";
}
header ( "Location: organisations.php" );
ob_end_flush();

?>