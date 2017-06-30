<?php
ob_start ();
include 'includes/header.php';

if (isset ( $config ['organisation_id'] )) {
	$organisationId = ( int ) $config ['organisation_id'];
	$data = [
			'per_page' => '4',
			'page' => '1'
	];
	try {
		$response = $api->get ( "users/me/petitions", $data );
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
		$_SESSION ['form'] ['error'] = true;

		$_SESSION ['form'] ['message'] = $message;

		header ( "Location: index.php" );
		exit ();
	}
	$petitionsList = $response->getResponseData ();
} else {

	$_SESSION ['form'] ['error'] = true;

	$_SESSION ['form'] ['message'] = " Organisation not configured";
	header ( "Location : error.php" );
}
ob_end_flush ();
?>
<div class="row">
	  <?php include 'includes/messages.php';?>	
   </div>
<!-- Page Content -->
<div class="container">

	<!-- Jumbotron Header -->
	<header class="jumbotron hero-spacer">
		<h1>Voice for change!</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa,
			ipsam, eligendi, in quo sunt possimus non incidunt odit vero aliquid
			similique quaerat nam nobis illo aspernatur vitae fugiat numquam
			repellat.</p>
		<p>
			<a class="btn btn-primary btn-large" href="create_petition.php">Call
				to action!Start A petition</a>
		</p>
	</header>

	<hr>

	<!-- Title -->
	<div class="row">
		<div class="col-lg-12">
			<h3>Latest Petitions</h3>
		</div>
	</div>
	<!-- /.row -->

	<!-- Page Features -->
        
	    <?php
					$count = 0;
					foreach ( $petitionsList ['data'] as $petition ) {
						if ($count % 4 == 0) {
							echo "<div class='row text-center'>";
						}
					$count++;		
						?>
    <div class="col-md-3 col-sm-6 hero-feature">
		<div class="thumbnail">
			<img src="<?php echo $petition['image_url']; ?>" alt="">
			<div class="caption">
				<h3><?php echo $petition['title']; ?></h3>
				<p><?php echo $petition['mission']; ?></p>
				<p>
				<h6><?php echo $petition['signatures_count']; ?> Supporters!</h6>
				<a
					href="<?php echo "view_petition.php?petition_id=".$petition['id'] ?>"
					class="btn btn-default">More Info</a>
				</p>
			</div>
		</div>
	</div>
            <?php
						if ($count % 4 == 0) {
							echo "</div>";
						}
					}
					?>
        
        <!-- /.row -->
<?php include 'includes/footer.php';?>
