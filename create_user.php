<?php
ob_start();
	include 'includes/header.php';
	if(isset($_POST['submit'])){
		$data['first_name']= $_POST['first_name'];
		$data['last_name']= $_POST['last_name'];
		$data['email']= $_POST['email'];
		$data['password']= $_POST['password'];
		$data['password_confirmation']= $_POST['password_confirmation'];

		try{
			$response = $api->post('users',$data);
				
		} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
			$responseMessage = $e->getMessage();
			$messageArray = explode('{',$responseMessage);
			$messages = '{'.$messageArray['1'];
			$error = json_decode($messages);
			$_SESSION['form']['error']= true;
			$_SESSION['form']['message'] = '';
			foreach ($error as $errorMessage)	{
				if(is_array($errorMessage)){
					$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage[0];
				}else{
					$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage;
				}
			}
			header("Location: create_user.php");
			exit;
		}
		$user = $response->getResponseData();
		$_SESSION['form']['success']= true;
		$_SESSION['form']['message'] = "user created ";
		
		header("Location: users.php");
		exit;

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
	                    <h3 class="panel-title">Create user</h3>
	                  </div>
	                </div>
	              </div>
	              <?php include 'includes/messages.php';?>	
	              
	              <div class="panel-body">
	              <form class="form-horizontal" name="create_user" action="#" method="post">
	                <table class="table table-striped table-bordered table-list">
	                  <tbody>
	                          <tr>
	                            <td>First Name</td>
	                            <td><input id="first_name" name="first_name" placeholder="first_name" class="form-control input-md" required=""  type="text"></td>
	                            	                          </tr>
	                          <tr>
	                            <td>Last Name</td>
	                            <td><input id="last_name" name="last_name" placeholder="last_name" class="form-control input-md" required=""  type="text"></td>
	                          </tr>
	                          <tr>
	                            <td>Email </td>
	                            <td><input id="email" name="email" placeholder="email" type="email" class="form-control input-md" required="" type="text"></td>
	                          </tr>
	                          <tr>
	                            <td>Password</td>
	                            <td><input id="password" name="password" placeholder="password" class="form-control input-md" required="" type="text"></td>
	                          </tr>	                          
	                          <tr>
	                            <td>Confirm pasword</td>
	                            <td><input id="password_confirmation" name="password_confirmation" placeholder="password_confirmation" required="" class="form-control input-md"  type="text"></td>                          
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