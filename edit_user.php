<?php
ob_start();
include 'includes/header.php';
if(isset($_GET['id'])&& $_GET['id']!=null ){
	$id = $_GET['id'];
	$data= [];
	try{
		$response = $api->get("users/".$id,$data);
		
	} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
		$responseMessage = $e->getMessage();
		$messageArray = explode('{',$responseMessage);
		$messages = '{'.$messageArray['1'];
		$error = json_decode($messages);
		$_SESSION['form']['error']= true;
	
		foreach ($error as $errorMessage)	{
			if(is_array($errorMessage)){
				$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage[0];
			}else{
				$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage;
			}
		}
		header("Location: edit_user.php?id=".$id);
		exit;
	}
	$user = $response->getResponseData();

}else {
	header ("Location: users.php");
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
	                    <h3 class="panel-title">profile details</h3>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <tbody>
	                          <tr>
	                            <td>First Name</td>
	                            <td><?php echo $user['first_name']?></td>
	                          </tr>
	                          <tr>
	                            <td>Last Name</td>
	                            <td><?php echo $user['last_name']?></td>
	                          </tr>
	                          <tr>
	                            <td>Email </td>
	                            <td><?php echo $user['phone']?></td>
	                          </tr>	                          <tr>
	                            <td>Address</td>
	                            <td><?php echo $user['address_1']?></td>
	                          </tr>	                          <tr>
	                            <td>Address</td>
	                            <td><?php echo $user['address_2']?></td>
                          
	                        </tbody>
	                </table>
	            
	              </div>

	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>