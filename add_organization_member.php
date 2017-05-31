<?php
ob_start();
include 'includes/header.php';
if (isset($_POST['submit'])){
	$data['user']= isset($_POST['user'])? $_POST['user']:'';
	$organizationId = isset($_POST['org_id'])? $_POST['org_id']:null;
	try{
		$response = $api->post("organizations/$organizationId/members",$data);
	} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
		$responseMessage = $e->getMessage();
		$messageArray = explode('{',$responseMessage);
		$messages = '{'.$messageArray['1'];
		$error = json_decode($messages);
		$_SESSION['form']['error']= true;

		foreach ($error as $errorMessage)	{
			$_SESSION['form']['message'] = $_SESSION['form']['message'].$errorMessage[0];
		}
		header("Location: petition_targets.php");
		exit;
	}
	$target = $response->getResponseData();

	$_SESSION['form']['success']= true;
	$_SESSION['form']['message'] = "User Added ";
	header("Location: organisation_members.php?org_id=".$organizationId);
	exit;

}
if(isset($_GET['org_id'])){
	$organizationId= (int) $_GET['org_id'];
	
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get('users',$data);
		
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
		header("Location: organisations.php");
		exit;
	}
	$userList = $response->getResponseData();
}else{
	header("Location : organisations.php");
}
ob_end_flush();
?>
   <div class="row">
	  <?php include 'includes/messages.php';?>	
   </div> 
	<div class="container">
	    <div class="row">	    
	        <div class="col-md-10 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">Users List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th>First Name</th>
	                        <th>Last name</th>
	                        <th> </th>

	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($userList['data'] as $user){?>
	                          <tr>
	                          	<form class="form-horizontal" name="add_member_$user['id']" action="#" method="post">
	                            <td><?php echo $user['first_name']; ?></td>
	                            <td><?php echo $user['last_name']; ?></td>
	                            <input type="hidden" name="org_id" value="<?php echo $organizationId ?>" >
	                            <input id="user" name="user"  class="form-control input-md" value=<?php echo $user['id']; ?> type="hidden">
	                            <td><button id="submit" name="submit" class="btn btn-primary">Add member</button></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	              </div>
	              <div class="panel-footer">
	                <div class="row">
					  <label class="col-md-4 control-label" for="submit"></label>
					  <div class="col-md-4 pull-right">
					  </div>
					</div>
	            	</form>
					<div class="row">
	                  <div class="col col-xs-4">Page <?php echo $userList['current_page']." of ".$userList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination  pull-right">
	                        <li><a href="<?php echo $userList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $userList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>