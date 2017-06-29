<?php
ob_start();
include 'includes/header.php';

if(isset($config['organisation_id'])){
	$organisationId= (int) $config['organisation_id'];
	$data['per_page']=isset($_GET['per_page'])?$_GET['per_page']:'20';
	$data['page']=isset($_GET['page'])?$_GET['page']:'1';
	try{
		$response = $api->get("users/me/petitions",$data);

	} catch(CloudWelder\PetitionsApi\Exceptions\RestException $e){

		$response = $e->getServerResponse();
		$responseCode = $response->getStatusCode();
		switch ($responseCode) {
			case 400:
				$message = 'Bad Request';
				break;
			case 401:
				$message ='Unauthorized: Check your access token';
				break;
			case 403:
				$message ='Forbidden: Missing permission';
				break;
			case 404:
				$message ='Not Found: Check your API endpoint url';
				break;
			case 409:
				$message ='Not Allowed: The specified operation is not allowed at the moment';
				break;
			case 422:
				$message ='Unprocessable Entity: Make sure the required parameters present and are valid';
				break;
			default:
				$message ='Request Failed.';
				break;
		}
		$_SESSION['form']['error']= true;

		$_SESSION['form']['message'] =$message;

		header("Location: index.php");
		exit;
	}
	$petitionsList = $response->getResponseData();
}else {

	header("Location : index.php");
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
	                  <?php foreach ($petitionsList['data'] as $petition){?>
	                  <div class="row">
	                  <div class="col-sm-12">
	                  	<div class="pull-left col-sm-4">
					      <img src="<?php echo $petition['image_url']; ?>" class="" alt="Image" height="120">
					     </div>
					     <div class="pull-right col-sm-8"> 
					        <h3><?php echo $petition['title']; ?></h3>
                            <h6><?php echo $petition['signatures_count']; ?> Supporters!</h6> 
                            <a href="<?php echo "view_petition.php?petition_id=".$petition['id'] ?>" class="btn btn-default">More Info</a>
	                        <a class="btn btn-sm btn-primary btn-create" href=<?php echo "view_petition.php?petition_id=".$petition['id']."&sign=true" ?>> Sign </a></td>

                          </div>
                      </div>
                      </div>
                      <hr>
                      
	                  <?php } ?>      
	              </div>
	              <div class="panel-footer">
	                <div class="row">
	                  <div class="col col-xs-4">Page <?php echo $petitionsList['current_page']." of ".$petitionsList['last_page'] ?>
	                  </div>
	                  <div class="col col-xs-8">
	                    <ul class="pagination visible pull-right">
	                        <li><a href="<?php echo str_replace("http://api.petitions.io/api/users/me/petitions",$_SERVER['REQUEST_URI'],$petitionsList['prev_page_url']); ?>">«</a></li>
	                        <li><a href="<?php echo str_replace("http://api.petitions.io/api/users/me/petitions",$_SERVER['REQUEST_URI'],$petitionsList['next_page_url']); ?>">»</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	            </div>
	

<?php include 'includes/footer.php';?>