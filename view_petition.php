<?php
ob_start ();
if (isset ( $_GET ['petition_id'] ) && $_GET ['petition_id'] != null) {
	$petition_id = ( int ) $_GET ['petition_id'];
	include 'includes/header.php';
	
	if (isset ( $_POST ['submit'] )) {
		$data ['petition_id'] = isset ( $_POST ['petition_id'] ) ? $_POST ['petition_id'] : null;
		$data ['email'] = isset ( $_POST ['email'] ) ? $_POST ['email'] : null;
		$data ['is_visible'] = isset ( $_POST ['is_visible'] ) ? $_POST ['is_visible'] : false;
		$data ['first_name'] = isset ( $_POST ['first_name'] ) ? $_POST ['first_name'] : null;
		$data ['last_name'] = isset ( $_POST ['last_name'] ) ? $_POST ['last_name'] : null;
		$data ['comment'] = isset ( $_POST ['comment'] ) ? $_POST ['comment'] : null;
		$data ['address_1'] = isset ( $_POST ['address_1'] ) ? $_POST ['address_1'] : null;
		$data ['address_2'] = isset ( $_POST ['address_2'] ) ? $_POST ['address_2'] : null;
		$data ['state'] = isset ( $_POST ['state'] ) ? $_POST ['state'] : null;
		$data ['city'] = isset ( $_POST ['city'] ) ? $_POST ['city'] : null;
		$data ['country_code'] = isset ( $_POST ['country_code'] ) ? $_POST ['country_code'] : null;
		$data ['zipcode'] = isset ( $_POST ['zipcode'] ) ? $_POST ['zipcode'] : null;
		$data ['locale'] = isset ( $_POST ['locale'] ) ? $_POST ['locale'] : null;
		$data ['timezone'] = isset ( $_POST ['timezone'] ) ? $_POST ['timezone'] : null;
		$data ['source_ip'] = $_SERVER ['REMOTE_ADDR'];
		$data ['source_url'] = $_SERVER['HTTP_HOST']."".$_SERVER ['REQUEST_URI'];
		
		try {
			$response = $api->post ( 'signatures', $data );
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
			header ( "Location: petitions.php" );
			exit ();
		}
		$signature = $response->getResponseData ();
		$_SESSION ['form'] ['success'] = true;
		$_SESSION ['form'] ['message'] = "Signature created";
		
		if (isset ( $_COOKIE ['signed_petitions'] )) {
			$signed_petitions = $_COOKIE ['signed_petitions'];
			$signed_petitions = stripslashes ( $signed_petitions );
			$signed_petitions = json_decode ( $signed_petitions, true );
			array_push ( $signed_petitions, $data ['petition_id'] );
		} else {
			$signed_petitions = array (
					$data ['petition_id'] 
			);
		}
		$signed_petitions = json_encode ( $signed_petitions );
		
		setcookie ( 'signed_petitions', $signed_petitions, time () + 60 * 60 * 24 * 30 );
		header ( "Location: petitions.php" );
		
	}
	
	$data = [ ];
	try {
		$response = $api->get ( 'petitions/' . $petition_id, $data );
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
		header ( "Location: petitions.php" );
		exit ();
	}
	$petition = $response->getResponseData ();
} else {
	$_SESSION ['form'] ['message'] = "Select an Petition under organisation";
	header ( "Location: petitions.php" );
}
if (isset ( $_COOKIE ["signed_petitions"] )) {
	$signed_petitions = $_COOKIE ["signed_petitions"];
	$signed_petitions = stripslashes ( $signed_petitions );
	$signed_petitions = json_decode ( $signed_petitions, true );
	$signed = in_array ( $_GET ['petition_id'], $signed_petitions ) ? true : false;
} else {
	$signed = false;
}
ob_end_flush ();
?>
<div class="row">
	  <?php include 'includes/messages.php';?>	
   </div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-8">
				<h3><?php echo $petition['title'];?></h3>
				<div class="pull-left">
					<img src="<?php echo $petition['image_url']; ?>" class=""
						alt="Image" height="220">
				</div>
				<div style="clear: both;">
					<p style="padding-top: 40px;"><?php echo $petition['description']; ?></p>
				</div>

			</div>
			<div class="panel panel-default panel-table col-sm-4">
				<div class="panel-heading">
					<div class="row">
						<div class="col col-xs-6">
							<h3 class="panel-title">Sign petition</h3>
						</div>
					</div>
				</div>

				<div class="panel-body">
					<div class="row">

						<form class="form-horizontal" name='create_signature' action="#"
							method="post">
							<fieldset>

								<!-- Form Name -->
								<!-- Select Basic -->
								<input id="petition_id" name="petition_id" type="hidden"
									class="form-control input-md" required=""
									value="<?php echo $petition_id?>">

								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="title">Email</label>
									<div class="col-md-8">
										<input id="email" name="email" placeholder="email"
											type="email" class="form-control input-md" required=""
											<?php echo isset($_GET['sign']) ? "autofocus='autofocus'":"";?>>

									</div>
								</div>

								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="title">First Name</label>
									<div class="col-md-8">
										<input id="first_name" name="first_name"
											placeholder="first_name" class="form-control input-md"
											type="text">
									</div>
								</div>
								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="title">Last Name</label>
									<div class="col-md-8">
										<input id="last_name" name="last_name" placeholder="last_name"
											class="form-control input-md" type="text">
									</div>
								</div>

								<!-- Select Basic -->
								<div class="form-group">
									<label class="col-md-4 control-label" for="type">comment</label>
									<div class="col-md-8">
										<input id="comment" name="comment" placeholder="comment"
											class="form-control input-md" type="text">
									</div>
								</div>


								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="address_1">address 1</label>
									<div class="col-md-8">
										<input id="address_1" name="address_1" placeholder="address_1"
											class="form-control input-md" type="text">
									</div>
								</div>

								<!-- Select Basic -->
								<div class="form-group">
									<label class="col-md-4 control-label" for="address_2">address 2</label>
									<div class="col-md-8">
										<input id="address_2" name="address_2" placeholder="address_2"
											class="form-control input-md" type="text">
									</div>
								</div>

								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="country_code">country
										code</label>
									<div class="col-md-8">
										<input id="country_code" name="country_code"
											placeholder="country code" class="form-control input-md"
											type="text">

									</div>
								</div>


								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="state">State</label>
									<div class="col-md-8">
										<input id="state" name="state" placeholder="state"
											class="form-control input-md" type="text">

									</div>
								</div>

								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="city">City</label>
									<div class="col-md-8">
										<input id="city" name="city" placeholder="image city"
											class="form-control input-md" type="text">

									</div>
								</div>
								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="zipcode">zip code.</label>
									<div class="col-md-8">
										<input id="zipcode" name="zipcode" placeholder="zipcode"
											class="form-control input-md" type="text">

									</div>
								</div>
								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="locale">locale.</label>
									<div class="col-md-8">
										<input id="locale" name="locale" placeholder="locale"
											class="form-control input-md" type="text">

									</div>
								</div>

								<!-- Text input-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="timezone">timezone</label>
									<div class="col-md-8">
										<input id="timezone" name="timezone" placeholder="timezone"
											class="form-control input-md" type="text">

									</div>
								</div>
								
								<!-- Check box-->
								<div class="form-group">
									<label class="col-md-4 control-label" for="name">Is Visible</label>
									<div class="col-md-8">
										<input id="visible" name="visible" placeholder="visible"
											class="form-control input-md" value='True' type="checkbox">
									</div>
								</div>

								<!-- Button -->
								<div class="form-group">
								<?php if(!$signed){ ?>
									<label class="col-md-4 control-label" for="submit"></label>
									<div class="col-md-8">
										<button id="submit" name="submit" class="btn btn-primary">Submit</button>
									</div>
									<?php
								} else {?>
									<div class="col-md-8"> Already signed !</div>
								<?php }
								?>
								</div>
							</fieldset>
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>	