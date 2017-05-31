<?php
ob_start();
include 'includes/header.php';

if(isset($_GET['org_id'])){
	$organizationId= (int) $_GET['org_id'];
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get("organizations/$organizationId/targets",$data);

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
	$targetList = $response->getResponseData();
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
	                    <h3 class="panel-title">Target List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href=<?php echo "create_target.php" ?> class="btn btn-sm btn-primary btn-create" role="button">Create New</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Name</th>
	                        <th>Title</th>
	                        <th>Type</th>
	                        <th>City </th>
	                        <th>state </th>

	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($targetList['data'] as $target){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "edit_target.php?target_id=".$target['id'] ?>><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $target['name']; ?></td>
	                            <td><?php echo $target['title']; ?></td>
	                            <td><?php echo $target['type']; ?></td>
	                            <td><?php echo $target['city']; ?></td>
	                            <td><?php echo $target['state']; ?></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $targetList['current_page']." of ".$targetList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $targetList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $targetList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>