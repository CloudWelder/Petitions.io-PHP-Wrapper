<?php
ob_start();

include 'includes/header.php';
$filter = isset($_POST['filter'])?$_POST['filter']:'';
$data= ['per_page'=>'20','page'=>'1','filter'=>$filter];

	try{
		$response = $api->get('organizations/search',$data);
		
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

	$organizationList = $response->getResponseData();

ob_end_flush();
?>

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
	                    <a class="btn btn-sm btn-primary btn-create" href="create_user.php">Create New</a>
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
	                        <th>City</th>                       
	                        <th>Website </th>
	                        <th>avatar_media_id</th>
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($organizationList['data'] as $organization){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "view_organisation.php?id=".$organization['id'] ?>><em class="fa fa-eye"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $organization['name']; ?></td>
	                            <td><?php echo $organization['address_1']; ?></td>
	                            <td><?php echo $organization['city']; ?></td>
	                            <td><?php echo $organization['website']; ?></td>
	                            <td><?php echo $organization['avatar_media_id']; ?></td>

	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $organizationList['current_page']." of ".$organizationList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible pull-right">
	                        <li><a href="<?php echo $organizationList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $organizationList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>