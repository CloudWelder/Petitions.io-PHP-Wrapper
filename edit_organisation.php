<?php
ob_start();
if(isset($_GET['id'])&& $_GET['id']!=null ){
	$id = $_GET['id'];
	include 'includes/header.php';
	if(isset($_POST['submit'])){
		$data['name']= $_POST['name'];
		$data['country_code']= $_POST['country_code'];
		$data['mission']= $_POST['mission'];
		$data['address_1']= $_POST['address_1'];
		$data['address_2']= $_POST['address_2'];
		$data['state']= $_POST['state'];
		$data['name']= $_POST['name'];
		$data['city']= $_POST['city'];
		$data['zipcode']= $_POST['zipcode'];
		$data['website']= $_POST['website'];
		$data['twitter_handle']= $_POST['twitter_handle'];
		$data['avatar_url']= $_POST['avatar_url'];
		$data['avatar_media_id']= $_POST['avatar_media_id'];
		try{
		$response = $api->put('organizations/'.$id,$data);
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
			header("Location: edit_organisation.php?id=$id");
			exit;
		}
		$organisation= $response->getResponseData();
		$_SESSION['form']['success']= true;
		$_SESSION['form']['message'] = "Organisation edited ";
	}else {		
		$data= [];
		$response = $api->get('organizations/'.$id,$data);
		$organisation = $response->getResponseData();
	}
	
}else {
	header ("Location: organisations.php");
	exit;
}
ob_end_flush();
?>
	<div class="container">
	    <div class="row">	    
	        <div class="col-md-10 col-md-offset-1">
	
	            <div class="panel panel-default panel-table">
	              <div class="panel-heading">
	                <div class="row">
	                  <div class="col col-xs-6">
	                    <h3 class="panel-title">Edit Organisation</h3>
	                  </div>
	                </div>
	              </div>
	             <?php include 'includes/messages.php';?>	
	              
	              <div class="panel-body">
						<form class="form-horizontal" name='create_organisation' action="#" method="post">
						<fieldset>
						
						<!-- Form Name -->						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="name">Name of the organisation</label>
						  <div class="col-md-4">
						  <input id="name" name="name" placeholder="name" class="form-control input-md" required=""  value="<?php echo $organisation['name']?>"  type="text">
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="country_code">country code</label>  
						  <div class="col-md-4">
						  <input id="country_code" name="country_code" placeholder="country code" class="form-control input-md" required=""  value="<?php echo $organisation['country_code']?>"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="Prénom">Mission statement.</label>  
						  <div class="col-md-4">
						  <input id="mission" name="mission" placeholder="mission" class="form-control input-md"   value="<?php echo $organisation['mission']?>" type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="address_1">address_1</label>  
						  <div class="col-md-4">
						  <input id="address_1" name="address_1" placeholder="address_1" class="form-control input-md"  value="<?php echo $organisation['address_1']?>"  type="text">
						  </div>
						</div>
						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="address_2">address_2</label>
						  <div class="col-md-4">
						  <input id="address_2" name="address_2" placeholder="address_2" class="form-control input-md"  value="<?php echo $organisation['address_2']?>"  type="text">
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="state">State</label>  
						  <div class="col-md-4">
						  <input id="state" name="state" placeholder="state" class="form-control input-md"  value="<?php echo $organisation['state']?>"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="city">City</label>  
						  <div class="col-md-4">
						  <input id="city" name="city" placeholder="city" class="form-control input-md"  value="<?php echo $organisation['city']?>"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="zipcode">zip code.</label>  
						  <div class="col-md-4">
						  <input id="zipcode" name="zipcode" placeholder="zipcode" class="form-control input-md"  value="<?php echo $organisation['zipcode']?>"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="website">Website</label>  
						  <div class="col-md-4">
						  <input id="website" name="website" placeholder="website" class="form-control input-md"  value="<?php echo $organisation['website']?>"  type="text">
						    
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="twitter_handle">twitter_handle</label>  
						  <div class="col-md-4">
						  <input id="twitter_handle" name="twitter_handle" placeholder="twitter_handle" class="form-control input-md"  value="<?php echo $organisation['twitter_handle']?>"  type="text">
						    
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_url">avatar url.</label>  
						  <div class="col-md-4">
						  <input id="avatar_url" name="avatar_url" placeholder="avatar url" class="form-control input-md"  value="<?php echo $organisation['avatar_url']?>"  type="text">
						    
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="avatar_media_id">avatar_media_id</label>  
						  <div class="col-md-4">
						  <input id="avatar_media_id" name="avatar_media_id" placeholder="avatar_media_id" class="form-control input-md"  value="<?php echo $organisation['avatar_media_id']?>"  type="text">
						    
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