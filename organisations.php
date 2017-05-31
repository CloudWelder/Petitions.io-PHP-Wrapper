<?php
include 'includes/header.php';

	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get('organizations',$data);
		
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
		header("Location: dashboard.php");
		exit;
	}
	$organisationList = $response->getResponseData();
	
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
	                    <h3 class="panel-title">Organisations List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href="create_organisation.php" class="btn btn-sm btn-primary btn-create" role="button">Create New</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Name</th>
	                        <th>Address 1</th>
	                        <th>Address 2</th>
	                        <th>City </th>
	                        <th>Website </th>
	                        <th>State </th>
	                        <th>  </th>
	                        
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($organisationList['data'] as $organisation){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "edit_organisation.php?id=".$organisation['id'] ?>><em class="fa fa-pencil"></em></a>
	                            </td>
	                            <td><?php echo $organisation['name']; ?></td>
	                            <td><?php echo $organisation['address_1']; ?></td>
	                            <td><?php echo $organisation['address_2']; ?></td>
	                            <td><?php echo $organisation['city']; ?></td>
	                            <td><?php echo $organisation['website']; ?></td>
	                            <td><?php echo $organisation['state']; ?></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "petitions.php?org_id=".$organisation['id'] ?>>Petitions</a></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "organisation_targets.php?org_id=".$organisation['id'] ?>>Targets</a></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "organisation_members.php?org_id=".$organisation['id'] ?>>Members</a></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "organisation_roles.php?org_id=".$organisation['id'] ?>>Roles</a></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $organisationList['current_page']." of ".$organisationList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $organisationList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $organisationList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>