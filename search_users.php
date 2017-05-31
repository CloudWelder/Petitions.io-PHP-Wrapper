<?php
include 'includes/header.php';
$filter = isset($_POST['filter'])?$_POST['filter']:'';
$data= ['per_page'=>'20','page'=>'1','filter'=>$filter];
$response = $api->get('users/search',$data);
$userList = $response->getResponseData();

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
	                        <th>First Name</th>
	                        <th>Last Name</th>
	                        <th>Phone </th>
	                        <th>Address 1</th>
	                        <th>Address 2</th>                       
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($userList['data'] as $user){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "edit_user.php?id=".$user['id'] ?>><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $user['first_name']; ?></td>
	                            <td><?php echo $user['last_name']; ?></td>
	                            <td><?php echo $user['phone']; ?></td>
	                            <td><?php echo $user['address_1']; ?></td>
	                            <td><?php echo $user['address_2']; ?></td>

	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $userList['current_page']." of ".$userList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible pull-right">
	                        <li><a href="<?php echo $userList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $userList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>