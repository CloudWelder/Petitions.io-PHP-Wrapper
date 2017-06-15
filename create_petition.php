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
									<textarea id="description" name="description" class="form-control input-md"></textarea>

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
								class="previous btn btn-default " value="Previous" /> <input
								type="submit" name="submit" class="submit btn btn-success "
								value="Submit" id="submit_data" />
							<div class="col col-xs-6 text-right pull-right">
								<a href="javascript:void(0);"
									class="btn btn-sm btn-primary btn-create" data-toggle="modal" data-target="#myModal" role="button">Create
									New Target</a>
							</div>
						</fieldset>
					</form>


				</div>

			</div>

		</div>
	</div>
</div>

<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Target</h4>
      </div>
      <div class="modal-body">
      
     
	              <div class="panel-body">
						<form class="form-horizontal" name='create_target_form' id="create_target_form" action="save_target.php" method="post">
						<fieldset>
						
						<!-- Form Name -->						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="name">Name of the Target</label>
						  <div class="col-md-4">
						  <input id="name" name="name" placeholder="name" class="form-control input-md" required="" type="text">
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">Title</label>  
						  <div class="col-md-4">
						  <input id="title" name="title" placeholder="title" class="form-control input-md" required=""  type="text">
						    
						  </div>
						</div>
						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="type">Type of Target</label>
						  <div class="col-md-4">
						    <select id="type" name="type" class="form-control">
						      <option value="">Select Type</option>
						      <option value="government">government</option>
						      <option value="organization">organization</option>
						      <option value="company">company</option>
						      <option value="other">other</option>
						    </select>
						  </div>
						</div>
												
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="country_code">country code</label>  
						  <div class="col-md-4">
						  <input id="country_code" name="country_code" placeholder="country code" class="form-control input-md" required=""  type="text">
						    
						  </div>
						</div>
						
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="state">State</label>  
						  <div class="col-md-4">
						  <input id="state" name="state" placeholder="state" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="city">City</label>  
						  <div class="col-md-4">
						  <input id="city" name="city" placeholder="image city" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_url">avatar url.</label>  
						  <div class="col-md-4">
						  <input id="avatar_url" name="avatar_url" placeholder="avatar_url" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_media_id">avatar_media_id</label>  
						  <div class="col-md-4">
						  <input id="avatar_media_id" name="avatar_media_id" placeholder="avatar_media_id" class="form-control input-md"  type="text">
						    
						  </div>
						</div>												
						</fieldset>
						</form>
	              </div>
      </div>
      <div class="modal-footer">
      <button id="submit-target" name="submit-target" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php include 'includes/footer.php';?>
<script type="text/javascript">
$(document).ready(function(){
	$("#submit-target").on('click', function() {
		$("#create_target_form").submit();
    });

	$("#create_target_form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                alert(data);
                $('#myModal').modal('hide');
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });
	
});
</script>
