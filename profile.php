<?php
include 'includes/header.php';
$data= [];
$response = $api->get('users/me',$data);
$user = $response->getResponseData();

?>

	<div class="container">
	    <div class="row">	    
	        <div class="col-md-10 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">profile details</h3>
	                    <a class="btn btn-default" href=<?php echo "edit_profile.php?id=".$user['id'] ?>><em class="fa fa-pencil"></em></a>
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
	                            <td><?php echo $user['email']?></td>
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