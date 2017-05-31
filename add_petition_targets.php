<?php
ob_start();
include 'includes/header.php';
if (isset($_POST['submit'])){
	$data['targets']= isset($_POST['targets'])? $_POST['targets']:array();
	$petitionId = isset($_POST['petition_id'])? $_POST['petition_id']:null;
	try{
		$response = $api->post("petitions/$petitionId/targets",$data);
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
	$_SESSION['form']['message'] = "Target Added ";
	header("Location: petition_targets.php?petition_id=".$petitionId);
	exit;

}
if(isset($_GET['org_id'])){
	$organizationId= (int) $_GET['org_id'];
	$petitionId= (int) $_GET['petition_id'];
	
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
	                    <h3 class="panel-title">Target List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	              <form class="form-horizontal" name='add_target' action="#" method="post">
	                <input type="hidden" name="petition_id" value="<?php echo $petitionId ?>" >
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th>Name</th>
	                        <th>Title</th>
	                        <th>Type</th>
	                        <th> </th>

	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($targetList['data'] as $target){?>
	                          <tr>
	                            <td><?php echo $target['name']; ?></td>
	                            <td><?php echo $target['title']; ?></td>
	                            <td><?php echo $target['type']; ?></td>
	                            <td><input id="targets" name="targets[]"  class="form-control input-md" value=<?php echo $target['id']; ?> type="checkbox"></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	              </div>
	              <div class="panel-footer">
	                <div class="row">
					  <label class="col-md-4 control-label" for="submit"></label>
					  <div class="col-md-4 pull-right">
					    <button id="submit" name="submit" class="btn btn-primary">Submit</button>
					  </div>
					</div>
	            	</form>
					<div class="row">
	                  <div class="col col-xs-4">Page <?php echo $targetList['current_page']." of ".$targetList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination  pull-right">
	                        <li><a href="<?php echo $targetList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $targetList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>