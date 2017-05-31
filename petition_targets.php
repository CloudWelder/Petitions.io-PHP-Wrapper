<?php
ob_start();
include 'includes/header.php';

if(isset($_GET['petition_id'])){
	$petitionId= (int) $_GET['petition_id'];
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get("petitions/$petitionId/targets",$data);

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
	exit;
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
	                    <h3 class="panel-title">Petition Targets List</h3>
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
	                        <th>State</th>
	                        <th>City </th>

	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($targetList['data'] as $target){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "view_target.php?target_id=".$target['id'] ?>><em class="fa fa-eye"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $target['name']; ?></td>
	                            <td><?php echo $target['title']; ?></td>
	                            <td><?php echo $target['type']; ?></td>
	                            <td><?php echo $target['state']; ?></td>
	                            <td><?php echo $target['city']; ?></td>
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