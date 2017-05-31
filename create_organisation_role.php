<?php
ob_start ();
include 'includes/header.php';

if (isset ( $_POST ['submit'] )) {
	$data ['name'] = isset ( $_POST ['name'] ) ? $_POST ['name'] : null;
	$data ['title'] = isset ( $_POST ['title'] ) ? $_POST ['title'] : null;
	$data ['description'] = isset ( $_POST ['description'] ) ? $_POST ['description'] : null;
	$data ['permissions'] = isset ( $_POST ['permissions'] ) ? $_POST ['permissions'] : null;
	if ($_POST ['org_id']) {
		$data ['org_id'] = $_POST ['org_id'];
	} else {
		$_SESSION ['form'] ['message'] = "coudnt identify the organisation";
		header ( "Location: create_organisation.php" );
		exit ();
	}
	try {
		$response = $api->post ( "organizations/" . $data ['org_id'] . "/roles", $data );
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
		header ( "Location: organisations.php" );
		exit ();
	}
	$petition = $response->getResponseData ();
	$_SESSION ['form'] ['success'] = true;
	$_SESSION ['form'] ['message'] = "Role created ";
} else {
	$data = [ ];
	try {
		$response = $api->get ( 'permissions', $data );
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
		header ( "Location: organisations.php" );
		exit ();
	}
	$response = $api->get ( 'permissions', $data );
	$permissions = $response->getResponseData ();
}
ob_end_flush ();
?>

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default panel-table">
				<div class="panel-heading">
					<div class="row">
						<div class="col col-xs-6">
							<h3 class="panel-title">Create Organisation</h3>
						</div>
					</div>
				</div>
	              <?php include 'includes/messages.php';?>	
	              
	              <div class="panel-body">
					<form class="form-horizontal" name='create_organisation_role'
						action="#" method="post">
						<fieldset>

							<!-- Form Name -->
							<!-- Select Basic -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="name">Name of the
									role</label>
								<div class="col-md-4">
									<input id="name" name="name" placeholder="name"
										class="form-control input-md" required="" type="text">
								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="title">Title of the
									role </label>
								<div class="col-md-4">
									<input id="title" name="title" placeholder="Title"
										class="form-control input-md" required="" type="text">

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="description">Description
									.</label>
								<div class="col-md-4">
									<input id="description" name="description"
										placeholder="description" class="form-control input-md"
										type="text">

								</div>
							</div>

							<!-- Select Basic -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="permissions">Permissions</label>
								<div class="col-md-4">
									<select id="permissions[]" name="permissions[]"
										class="form-control" multiple>
										<option value="">Select permissions</option>
						      <?php
												foreach ( $permissions ['data'] as $permission ) {
													echo "<option value='" . $permission ['id'] . "'>" . $permission ['title'] . "</option>";
												}
												?>
						    </select>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="form-group">
								<div class="col-md-4">
									<input id="org_id" name="org_id" value="<?php echo $_GET['org_id'];?>"
										class="form-control input-md" type="hidden">
								</div>
							</div>
							<!-- Button -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="submit"></label>
								<div class="col-md-4">
									<button id="submit" name="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>