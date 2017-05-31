<?php
	include 'includes/header.php';
	if(isset($_POST['submit'])){
		$data['first_name']= $_POST['first_name'];
		$data['last_name']= $_POST['last_name'];
		$data['phone']= $_POST['phone'];
		$data['address_1']= $_POST['address_1'];
		$data['address_2']= $_POST['address_2'];
		
		$response = $api->put('users/me',$data);
		$user = $response->getResponseData();
		$statusCode = $response->getStatusCode();
		if($statusCode == '200'){
			$_SESSION['form']['success']= true;
			$_SESSION['form']['message'] = "Profile edited ";
		} else {
			$_SESSION['form']['error']= true;
			$_SESSION['form']['message'] = "Couldnt save the changes ";
		}
	
	}else {		
		$data= [];
		$response = $api->get('users/me',$data);
		$user = $response->getResponseData();
	}

?>
	<div class="container">
	    <div class="row">	    
	        <div class="col-md-10 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">profile details</h3>
	                  </div>
	                </div>
	              </div>
	              <?php include 'includes/messages.php';?>	
	              
	              <div class="panel-body">
	              <form class="form-horizontal" name="edit_profile" action="#" method="post">
	                <table class="table table-striped table-bordered table-list">
	                  <tbody>
	                          <tr>
	                            <td>First Name</td>
	                            <td><input id="first_name" name="first_name" placeholder="first_name" class="form-control input-md" value="<?php echo $user['first_name']?>" type="text"></td>
	                            	                          </tr>
	                          <tr>
	                            <td>Last Name</td>
	                            <td><input id="last_name" name="last_name" placeholder="last_name" class="form-control input-md" value="<?php echo $user['last_name']?>" type="text"></td>
	                          </tr>
	                          <tr>
	                            <td>Phone </td>
	                            <td><input id="phone" name="phone" placeholder="phone" class="form-control input-md" value="<?php echo $user['phone']?>" type="text"></td>
	                          </tr>
	                          <tr>
	                            <td>Address</td>
	                            <td><input id="address_1" name="address_1" placeholder="address_1" class="form-control input-md" value="<?php echo $user['address_1']?>" type="text"></td>
	                          </tr>	                          
	                          <tr>
	                            <td>Address</td>
	                            <td><input id="address_2" name="address_2" placeholder="address_2" class="form-control input-md" value="<?php echo $user['address_2']?>" type="text"></td>                          
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