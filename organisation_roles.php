<?php
ob_start();
include 'includes/header.php';
$data= ['per_page'=>'20','page'=>'1'];

if(isset($_GET['org_id'])){
	$organizationId= (int) $_GET['org_id'];
	try{
		$response1= $api->get("organizations/$organizationId/all-roles",$data);
		$response2= $api->get("organizations/$organizationId/own-roles",$data);
		
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
	$allRoleList = $response1->getResponseData();
	$ownRoleList = $response2->getResponseData();
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
	        <div class="col-md-5 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">Organization Roles</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href=<?php echo "create_organisation_role.php?org_id=$organizationId" ?> class="btn btn-sm btn-primary btn-create" role="button">Add roles</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Roles Title</th>
	                        <th>Description</th>
	                        <th>Permissions</th>
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($allRoleList['data'] as $role){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=""><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $role['title']; ?></td>
	                            <td><?php echo $role['description']; ?></td>
	                            <td>
	                            <div style="overflow-y: scroll; height:150px; ">
									<?php foreach ($role['permissions'] as $permissions){
										echo $permissions['title']." <br> ";
	                    			 } ?>  
	                    		</div>		  
	                    		</td>   
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $allRoleList['current_page']." of ".$allRoleList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $allRoleList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $allRoleList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
			</div>
	        <div class="col-md-5 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">Organization Own Roles</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href=<?php echo "create_organisation_role.php?org_id=$organizationId" ?> class="btn btn-sm btn-primary btn-create" role="button">Add roles</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Roles Title</th>
	                        <th>Description</th>
	                        <th>Permissions</th>
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($ownRoleList['data'] as $role){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=""><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $role['title']; ?></td>
	                            <td><?php echo $role['description']; ?></td>
	                            <td>
									<?php foreach ($role['permissions'] as $permissions){
										echo $permissions['title']." <br> ";
	                    			 } ?>   
	                    		</td>  
	                    		</tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $ownRoleList['current_page']." of ".$ownRoleList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $ownRoleList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $ownRoleList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
			</div>			
		</div>

<?php include 'includes/footer.php';?>