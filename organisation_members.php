<?php
ob_start();
include 'includes/header.php';

if(isset($_GET['org_id'])){
	$organizationId= (int) $_GET['org_id'];
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get("organizations/$organizationId/members",$data);

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
	$memberList = $response->getResponseData();
}else {
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
	                    <h3 class="panel-title">Member List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href=<?php echo "add_organization_member.php?org_id=$organizationId" ?> class="btn btn-sm btn-primary btn-create" role="button">Add members</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>First Name</th>
	                        <th>Last name</th>
	                        <th>Phone</th>
	                        <th>Address </th>
	                        <th>City </th>
	                        <th>Roles </th>
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($memberList['data'] as $member){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "edit_user.php?id=".$member['id'] ?>><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger" href=<?php echo "delete_member.php?user_id=".$member['id']."&organization_id=".$organizationId ?>><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $member['first_name']; ?></td>
	                            <td><?php echo $member['last_name']; ?></td>
	                            <td><?php echo $member['phone']; ?></td>
	                            <td><?php echo $member['address_1']; ?></td>
	                            <td><?php echo $member['city']; ?></td>
	                            <td><?php foreach($member['roles'] as $roles){
	                            	echo($roles['name']);
	                            	} ?></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $memberList['current_page']." of ".$memberList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $memberList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $memberList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>