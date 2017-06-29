<?php
ob_start ();

include 'includes/header.php';

if (isset ( $_POST ['submit'] )) {

	$data ['title'] = isset ( $_POST ['title'] ) ? $_POST ['title'] : null;
	$data ['description'] = isset ( $_POST ['description'] ) ? $_POST ['description'] : null;
	$data ['mission'] = isset ( $_POST ['mission'] ) ? $_POST ['mission'] : null;
	$data ['goal'] = isset ( $_POST ['goal'] ) ? $_POST ['goal'] : null;
	$data ['video_url'] = isset ( $_POST ['video_url'] ) ? $_POST ['video_url'] : null;
	$data ['image_url'] = isset ( $_POST ['image_url'] ) ? $_POST ['image_url'] : null;
	$data ['image_media_id'] = isset ( $_POST ['image_media_id'] ) ? $_POST ['image_media_id'] : null;
	$data_targets = array ();
	$data_targets [] = isset ( $_POST ['petition_target_id'] ) ? $_POST ['petition_target_id'] : null;
	try {
		$response = $api->post ( 'petitions', $data );
	} catch ( CloudWelder\PetitionsApi\Exceptions\RestException $e ) {
		$responseMessage = $e->getMessage ();
		$messageArray = explode ( '{', $responseMessage );
		$messages = '{' . $messageArray ['1'];
		$error = json_decode ( $messages );
		$_SESSION ['form'] ['error'] = true;

		foreach ( $error as $errorMessage ) {
			if (is_array ( $errorMessage )) {
				$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . $errorMessage [0];
			} else {
				$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . $errorMessage;
			}
		}
		header ( "Location: create_petition.php" );
		exit ();
	}

	$petition = $response->getResponseData ();
	$_SESSION ['form'] ['success'] = true;
	$_SESSION ['form'] ['message'] = "petition created ";

	if (isset ( $petition ['id'] )) {
		$data ['targets'] = $data_targets;
		$petitionId = $petition ['id'];
		try {
			$response = $api->post ( "petitions/$petitionId/targets", $data );
		} catch ( CloudWelder\PetitionsApi\Exceptions\RestException $e ) {
			$responseMessage = $e->getMessage ();
			$messageArray = explode ( '{', $responseMessage );
			$messages = '{' . $messageArray ['1'];
			$error = json_decode ( $messages );
			$_SESSION ['form'] ['error'] = true;
				
			foreach ( $error as $errorMessage ) {
				$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . $errorMessage [0];
			}
			header ( "Location: petition_targets.php" );
			exit ();
		}
		$target = $response->getResponseData ();

		$_SESSION ['form'] ['success'] = true;
		$_SESSION ['form'] ['message'] = $_SESSION ['form'] ['message'] . " Target Added ";
	}
	// $statusCode = $response->getStatusCode();
}
ob_end_flush ();

?>
<div class="container">
	<div class="row">
	  <?php include 'includes/messages.php';?>	
   </div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="progress">
				<div class="progress-bar progress-bar-striped active"
					role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<div class="panel panel-default panel-table">
				<div class="panel-heading">
					<div class="row">
						<div class="col col-xs-6">
							<h3 class="panel-title">Create Petition</h3>
						</div>
						<div class="col col-xs-6 text-right">
							<a href="create_target.php"
								class="btn btn-sm btn-primary btn-create" role="button">Create
								New Target</a>
						</div>
					</div>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" name='create_petition'
						id='create_petition' action="#" method="post">
						<fieldset>
							<h2>Step 1: Create your petition</h2>
							<!-- Form Name -->
							<!-- Select Basic -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="title">Title of the
									petition</label>
								<div class="col-md-4">
									<input id="title" name="title" placeholder="title"
										class="form-control input-md" type="text">
								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="description">Description</label>
								<div class="col-md-4">
									<textarea rows="4" cols="50" id="description"
										name="description" class="form-control input-md" type="">
										</textarea>

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="Prénom">Mission
									statement.</label>
								<div class="col-md-4">
									<input id="mission" name="mission" placeholder="mission"
										class="form-control input-md" type="text">

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="goal">Goal of this
									petition.</label>
								<div class="col-md-4">
									<input id="goal" name="goal" placeholder="goal"
										class="form-control input-md" type="text">
								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="video_url">A video
									url</label>
								<div class="col-md-4">
									<input id="video_url" name="video_url" placeholder="video url"
										class="form-control input-md" type="text">

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="image_url">URL to the
									image if there is any.</label>
								<div class="col-md-4">
									<input id="image_url" name="image_url" placeholder="image url"
										class="form-control input-md" type="text">

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="image_media_id">Internal
									id of the media image object.</label>
								<div class="col-md-4">
									<input id="image_media_id" name="image_media_id"
										placeholder="image_media_id" class="form-control input-md"
										type="text">

								</div>
							</div>
							<!-- Button -->

							<input type="button" name="petition"
								class="next btn btn-info center-block" value="Next" />


						</fieldset>
						<fieldset>
							<h2>Step 2: Select your target</h2>
							<!-- Select Basic -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="target">Submit this
									petition to :</label>
								<div class="col-md-8 ui-widget">
									<input type=text " name="petition_target" id="petition_target"
										placeholder="Select an organisation">
								</div>
							</div>
							<input name="petition_target_id" type="hidden"
								id="petition_target_id"> <input type="button" name="previous"
								class="previous btn btn-default " value="Previous" />
							<div class="col col-xs-6 text-right pull-right">
								<input type="submit" name="submit"
									class="submit btn btn-success " value="Submit" id="submit_data" />
							</div>

						</fieldset>
					</form>


				</div>

			</div>

		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>