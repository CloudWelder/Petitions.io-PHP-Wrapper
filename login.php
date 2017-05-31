<?php
session_start ();
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi ( $config ['api_key'], $config ['client_id'], $config ['redirect_path'] );
?>
<!DOCTYPE html>
<html>
<head>
<title>Sample application login page</title>
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet"
	href="assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
	href="assets/vendor/font-awesome/css/font-awesome.min.css">


<body>
	<div class="container">
    <div class="row">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Login via Petetion Api</h3>
			 	</div>
			 	<?php include 'includes/messages.php';?><div class="panel-body">
	<form accept-charset="UTF-8" role="form" method="post"
		action="login_action.php">
		<fieldset>
			<div class="form-group">
				<input class="form-control" placeholder="yourmail@example.com"
					name="username" type="email" required="required" type="text">
			</div>
			<div class="form-group">
				<input class="form-control" placeholder="Password" name="password"
					type="password" required="required">
			</div>

			<input class="btn btn-lg btn-success btn-block" type="submit"
				name="submit" value="Login">
		</fieldset>
	</form>
	<hr />
	<center>
		<h4>OR</h4>
	</center>
	<a
		href="<?php echo $api->getLoginUrl(['create-signatures', 'view-signatures']) ?>"
		class="btn btn-info" role="button">Login with Petetions Api</a>
</div>
</div>
</div>
</div>
</div>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>



</body>
</html>
