<?php
ob_start();
if(isset($_GET['petition_id'])&& $_GET['petition_id']!=null ){
	$petition_id = (int)$_GET['petition_id'];
	include 'includes/header.php';
	if(isset($_POST['submit'])){
		
		$data['title']= $_POST['title'];
		$data['description']= $_POST['description'];
		$data['mission']= $_POST['mission'];
		$data['goal']= $_POST['goal'];
		$data['owner_organization_id']= $_POST['owner_organization_id'];
		$data['video_url']= $_POST['video_url'];
		$data['image_url']= $_POST['image_url'];
		$data['image_media_id']= $_POST['image_media_id'];
		try{
			$response = $api->put('petitions/'.$petition_id,$data);
		
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
			header("Location: edit_petition.php?id=".$petition_id);
			exit;
		}
		$_SESSION['form']['success']= true;
		$_SESSION['form']['message'] = "petition updated ";
	
	}
	
	$data= [];
	$response = $api->get('petitions/'.$petition_id,$data);
	$petition = $response->getResponseData();

	$datanew= [];
	$response = $api->get('organizations',$datanew);
	$organisationList = $response->getResponseData();
}else {
	header ("Location: organisations.php");
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
	                    <h3 class="panel-title">Create Petition</h3>
	                  </div>
	                </div>
	              </div>
	              <div class="panel-body">
						<form class="form-horizontal" name='create_petition' action="#" method="post">
						<fieldset>
						
						<!-- Form Name -->						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">Title of the petition</label>
						  <div class="col-md-4">
						  <input id="title" name="title" placeholder="title" class="form-control input-md" required="" value="<?php echo $petition['title']?>" type="text">
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="description">Description</label>  
						  <div class="col-md-4">
						  <input id="description" name="description" placeholder="description" class="form-control input-md" value="<?php echo $petition['description']?>" type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="Prénom">Mission statement.</label>  
						  <div class="col-md-4">
						  <input id="mission" name="mission" placeholder="mission" class="form-control input-md"  value="<?php echo $petition['mission']?>"type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="goal">Goal of this petition.</label>  
						  <div class="col-md-4">
						  <input id="goal" name="goal" placeholder="goal" class="form-control input-md" value="<?php echo $petition['goal']?>" type="text">
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
						      	if($petition['owner_organization_id']==$organisation['id']){
						      		echo "<option value='".$organisation['id']."' selected >".$organisation['name']."</option>";
						      		 }else{
						      		echo "<option value='".$organisation['id']."'>".$organisation['name']."</option>";
						      
						      	}
						      }
						      ?>
						    </select>
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="video_url">A video url</label>  
						  <div class="col-md-4">
						  <input id="video_url" name="video_url" placeholder="video url" class="form-control input-md" value="<?php echo $petition['video_url']?>" type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="image_url">URL to the image if there is any.</label>  
						  <div class="col-md-4">
						  <input id="image_url" name="image_url" placeholder="image url" class="form-control input-md" value="<?php echo $petition['image_url']?>" type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="image_media_id">Internal id of the media image object.</label>  
						  <div class="col-md-4">
						  <input id="image_media_id" name="image_media_id" placeholder="image_media_id" class="form-control input-md" value="<?php echo $petition['image_media_id']?>" type="text">
						    
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