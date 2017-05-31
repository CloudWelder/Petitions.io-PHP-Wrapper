<?php
ob_start();
include 'includes/header.php';

if(isset($_POST['submit'])){
	$data['name']= isset($_POST['name'])? $_POST['name']:null;
	$data['country_code']= isset($_POST['country_code'])? $_POST['country_code']:null;
	$data['title']= isset($_POST['title'])?$_POST['title'] :null;
	$data['type']= isset($_POST['type'])?$_POST['type'] :null;
	$data['state']= isset($_POST['state'])? $_POST['state']:null;
	$data['city']= isset($_POST['city'])?$_POST['city'] :null;
	$data['owner_organization_id']= isset($_POST['owner_organization_id'])? $_POST['owner_organization_id']:null;
	$data['avatar_url']= isset($_POST['avatar_url'])? $_POST['avatar_url']:null;
	$data['avatar_media_id']= isset($_POST['avatar_media_id'])?$_POST['avatar_media_id']:null;

	try{
		$response = $api->post('targets',$data);
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
		header("Location: create_organisation.php");
		exit;
	}
	$target = $response->getResponseData();
	$_SESSION['form']['success']= true;
	$_SESSION['form']['message'] = "Target created ";
	
}

	$data= [];
	$response = $api->get('organizations',$data);
	$organisationList = $response->getResponseData();


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
	                    <h3 class="panel-title">Create Target</h3>
	                  </div>
	                </div>
	              </div>
	              
	              <div class="panel-body">
						<form class="form-horizontal" name='create_target' action="#" method="post">
						<fieldset>
						
						<!-- Form Name -->						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="name">Name of the Target</label>
						  <div class="col-md-4">
						  <input id="name" name="name" placeholder="name" class="form-control input-md" required="" type="text">
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">Title</label>  
						  <div class="col-md-4">
						  <input id="title" name="title" placeholder="title" class="form-control input-md" required=""  type="text">
						    
						  </div>
						</div>
						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="type">Type of Target</label>
						  <div class="col-md-4">
						    <select id="type" name="type" class="form-control">
						      <option value="">Select Type</option>
						      <option value="government">government</option>
						      <option value="organization">organization</option>
						      <option value="company">company</option>
						      <option value="other">other</option>
						    </select>
						  </div>
						</div>

						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="owner_organization_id">Id of the organization, if this is an organizational petition</label>
						  <div class="col-md-4">
						    <select id="owner_organization_id" name="owner_organization_id" class="form-control">
						      <option value="">Select an organisation</option>
						      <?php 
						      foreach ($organisationList['data'] as $organisation){
						      	echo "<option value='".$organisation['id']."'>".$organisation['name']."</option>";
						      }
						      ?>
						    </select>
						  </div>
						</div>
												
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="country_code">country code</label>  
						  <div class="col-md-4">
						  <input id="country_code" name="country_code" placeholder="country code" class="form-control input-md" required=""  type="text">
						    
						  </div>
						</div>
						
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="state">State</label>  
						  <div class="col-md-4">
						  <input id="state" name="state" placeholder="state" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="city">City</label>  
						  <div class="col-md-4">
						  <input id="city" name="city" placeholder="image city" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_url">avatar url.</label>  
						  <div class="col-md-4">
						  <input id="avatar_url" name="avatar_url" placeholder="avatar_url" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_media_id">avatar_media_id</label>  
						  <div class="col-md-4">
						  <input id="avatar_media_id" name="avatar_media_id" placeholder="avatar_media_id" class="form-control input-md"  type="text">
						    
						  </div>
						</div>												
						<!-- Button -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="submit"></label>
						  <div class="col-md-4">
						    <button id="submit" name="submit" class="btn btn-primary">Submit</button>
						  </div>
						</div>
						</fieldset>
						</form>

	            
	              </div>

	            </div>
	
	</div></div></div>
<?php include 'includes/footer.php';?>	