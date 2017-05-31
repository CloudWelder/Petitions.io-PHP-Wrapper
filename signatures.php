<?php
ob_start();
include 'includes/header.php';

if(isset($_GET['petition_id'])){
	$petitionId= (int) $_GET['petition_id'];
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get("petitions/$petitionId/signatures",$data);
		
	} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){
		
		$responseMessage = $e->getMessage();
		$messageArray = explode('{',$responseMessage);
		$messages = '{'.$messageArray['1'];
		$error = json_decode($messages);
		$_SESSION['form']['error']= true;
		var_dump($responseMessage);exit;
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
	$signatureList = $response->getResponseData();
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
	                    <h3 class="panel-title">Signature List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href=<?php echo "create_signature.php?petition_id=$petitionId"?> class="btn btn-sm btn-primary btn-create" role="button">Create New</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Email</th>
	                        <th>First Name</th>
	                        <th>Last Name</th>
	                        <th>Comment </th>
	                        <th>City </th>
	                        <th>Source URL </th>

	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($signatureList['data'] as $signature){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td class="hidden-xs">1</td>
	                            <td><?php echo $signature['email']; ?></td>
	                            <td><?php echo $signature['first_name']; ?></td>
	                            <td><?php echo $signature['last_name']; ?></td>
	                            <td><?php echo $signature['comment']; ?></td>
	                            <td><?php echo $signature['city']; ?></td>
	                            <td><?php echo $signature['source_url']; ?></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $signatureList['current_page']." of ".$signatureList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $signatureList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $signatureList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>