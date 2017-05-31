<?php
ob_start();

	include 'includes/header.php';
	if(isset($_POST['submit'])){
		$data['current_password']= $_POST['current_password'];
		$data['password']= $_POST['password'];
		$data['password_confirmation']= $_POST['password_confirmation'];
		try{
			$response = $api->put('users/me/password',$data);
				
		} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
			$responseMessage = $e->getMessage();
			var_dump($responseMessage);
			$messageArray = explode("{",$responseMessage);
			$messages = "{".$messageArray['1'];
			$error = json_decode($messages);
			$_SESSION['form']['error']= true;
			foreach ($error as $errorMessage)	{
				if(is_array($errorMessage)){
					$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage[0];
				}else{
					$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage;
				}
			}
			header("Location: update_password.php");
			exit;
		}
		$user = $response->getResponseData();
		$statusCode = $response->getStatusCode();
		$_SESSION['form']['success']= true;
		$_SESSION['form']['message'] = "Profile edited ";


	}else {
		$data= [];
		$response = $api->get('users/me',$data);
		$user = $response->getResponseData();
	}
	ob_end_flush();
?>
	<div class="container">
	    <div class="row">	    
	        <div class="col-md-10 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">Update Password</h3>
	                  </div>
	                </div>
	              </div>
	              <?php include 'includes/messages.php';?>	
	              
	              <div class="panel-body">
	              <form class="form-horizontal" name="update_password" action="#" method="post">
	                <table class="table table-striped table-bordered table-list">
	                  <tbody>
	                          <tr>
	                            <td>current password</td>
	                            <td><input id="current_password" name="current_password" placeholder="current_password" type="password" class="form-control input-md" required="" type="text"></td>
	                          </tr>
	                          <tr>
	                            <td>new password</td>
	                            <td><input id="password" name="password" placeholder="password" class="form-control input-md" type="password" required=""></td>
	                          </tr>
	                          <tr>
	                            <td>password confirmation </td>
	                            <td><input id="password_confirmation" name="password_confirmation" placeholder="password_confirmation" type="password" class="form-control input-md" required="" type="text"></td>
	                          </tr>
	                          <tr>
						  	<td><label class="col-md-4 control-label" for="submit"></label></td>
						    <td><button id="submit" name="submit" class="btn btn-primary">Submit</button></td>
						    </tr>
	                   </tbody>
	                </table>
						</form>
	              </div>

	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>