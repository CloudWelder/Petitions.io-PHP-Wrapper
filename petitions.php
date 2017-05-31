<?php
ob_start();
include 'includes/header.php';

if(isset($_GET['org_id'])){
	$organisationId= (int) $_GET['org_id'];
	$data= ['per_page'=>'20','page'=>'1'];
	try{
		$response = $api->get("organizations/".$organisationId."/petitions",$data);
		
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
	$petitionsList = $response->getResponseData();
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
	                    <h3 class="panel-title">Petitions List</h3>
	                  </div>
	                  <div class="col col-xs-6 text-right">
	                     <a href="create_petition.php" class="btn btn-sm btn-primary btn-create" role="button">Create New</a>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
	                <table class="table table-striped table-bordered table-list">
	                  <thead>
	                    <tr>
	                        <th><em class="fa fa-cog"></em></th>
	                        <th>Title</th>
	                        <th>description</th>
	                        <th>mission</th>
	                        <th>goal </th>
	                        <th>Owner </th>
	                        <th>Video </th>
	                        <th></th>
	                    </tr> 
	                  </thead>
	                  <tbody>
	                  <?php foreach ($petitionsList['data'] as $petition){?>
	                          <tr>
	                            <td align="center">
	                              <a class="btn btn-default" href=<?php echo "edit_petition.php?petition_id=".$petition['id'] ?>><em class="fa fa-pencil"></em></a>
	                              <a class="btn btn-danger"><em class="fa fa-trash"></em></a>
	                            </td>
	                            <td><?php echo $petition['title']; ?></td>
	                            <td><?php echo $petition['description']; ?></td>
	                            <td><?php echo $petition['mission']; ?></td>
	                            <td><?php echo $petition['goal']; ?></td>
	                            <td><?php echo $petition['owner_organization_id']; ?></td>
	                            <td><?php echo $petition['video_url']; ?></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "signatures.php?petition_id=".$petition['id'] ?>>Signatures</a>
	                            </br><a class="btn btn-sm btn-primary btn-create" href=<?php echo "create_signature.php?petition_id=".$petition['id'] ?>> Create Signatures</a></td>
	                            <td><a class="btn btn-sm btn-primary btn-create" href=<?php echo "petition_targets.php?petition_id=".$petition['id'] ?>> Targets</a>
	                            <a class="btn btn-sm btn-primary btn-create" href=<?php echo "add_petition_targets.php?petition_id=".$petition['id']."&org_id=".$organisationId ?>> Add targets to petition</a></td>
	                          </tr>
	                    <?php } ?>      
	                        </tbody>
	                </table>
	            
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $petitionsList['current_page']." of ".$petitionsList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible-xs pull-right">
	                        <li><a href="<?php echo $petitionsList['prev_page_url']; ?>">«</a></li>
	                        <li><a href="<?php echo $petitionsList['next_page_url']; ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>