<?php
ob_start();
if(isset($_GET['petition_id'])&& $_GET['petition_id']!=null ){
	$petition_id = (int)$_GET['petition_id'];
	include 'includes/header.php';
	
	if(isset($_POST['submit'])){
		$data['petition_id']= isset($_POST['petition_id'])? $_POST['petition_id']:null;
		$data['email']= isset($_POST['email'])? $_POST['email']:null;
		$data['is_visible']= isset($_POST['is_visible'])? $_POST['is_visible']:null;
		$data['first_name']= isset($_POST['first_name'])? $_POST['first_name']:null;
		$data['last_name']= isset($_POST['last_name'])? $_POST['last_name']:null;
		$data['comment']= isset($_POST['comment'])?$_POST['comment'] :null;
		$data['address_1']= isset($_POST['address_1'])?$_POST['address_1'] :null;
		$data['address_2']= isset($_POST['address_2'])? $_POST['address_2']:null;
		$data['state']= isset($_POST['state'])? $_POST['state']:null;
		$data['city']= isset($_POST['city'])?$_POST['city'] :null;
		$data['country_code']= isset($_POST['country_code'])? $_POST['country_code']:null;
		$data['zipcode']= isset($_POST['zipcode'])? $_POST['zipcode']:null;
		$data['locale']= isset($_POST['locale'])? $_POST['locale']:null;
		$data['timezone']= isset($_POST['timezone'])? $_POST['timezone']:null;
		$data['source_ip']= isset($_POST['source_ip'])? $_POST['source_ip']:null;
		$data['source_url']= isset($_POST['source_url'])? $_POST['source_url']:null;
	
		try{
			$response = $api->post('signatures',$data);
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
		$signature = $response->getResponseData();
		$_SESSION['form']['success']= true;
		$_SESSION['form']['message'] = "Signature created";
	}

		//$data= [];
		//$response = $api->get('organizations/'.$organisationId.'/petitions',$data);
		//$petitionList = $response->getResponseData();
}else {
	$_SESSION['form']['message'] = "Select an Petition under organisation";
	header("Location: organisations.php");
	
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
	                    <h3 class="panel-title">Create Signature</h3>
	                  </div>
	                </div>
	              </div>
	              
	              <div class="panel-body">
						<form class="form-horizontal" name='create_signature' action="#" method="post">
						<fieldset>
						
						<!-- Form Name -->						
						<!-- Select Basic -->
						  <input id="petition_id" name="petition_id"  type="hidden" class="form-control input-md" required="" value="<?php echo $petition_id?>" >

						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">Email</label>  
						  <div class="col-md-4">
						  <input id="email" name="email" placeholder="email" type="email" class="form-control input-md" required="" >
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">First Name</label>  
						  <div class="col-md-4">
							<input id="first_name" name="first_name" placeholder="first_name" class="form-control input-md"  type="text">						    
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="title">Last Name</label>  
						  <div class="col-md-4">
							<input id="last_name" name="last_name" placeholder="last_name" class="form-control input-md"  type="text">						    
						  </div>
						</div>
						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="type">comment</label>
						  <div class="col-md-4">
						  <input id="comment" name="comment" placeholder="comment" class="form-control input-md"  type="text">
						  </div>
						</div>

						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="address_1">address 1</label>  
						  <div class="col-md-4">
						  <input id="address_1" name="address_1" placeholder="address_1" class="form-control input-md"  type="text">
						  </div>
						</div>
						
						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="address_2">address 2</label>
						  <div class="col-md-4">
						  <input id="address_2" name="address_2" placeholder="address_2" class="form-control input-md"  type="text">
						  </div>
						</div>
												
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="country_code">country code</label>  
						  <div class="col-md-4">
						  <input id="country_code" name="country_code" placeholder="country code" class="form-control input-md"  type="text">
						    
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
						  <label class="col-md-4 control-label" for="zipcode">zip code.</label>  
						  <div class="col-md-4">
						  <input id="zipcode" name="zipcode" placeholder="zipcode" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="locale">locale.</label>  
						  <div class="col-md-4">
						  <input id="locale" name="locale" placeholder="locale" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="timezone">timezone</label>  
						  <div class="col-md-4">
						  <input id="timezone" name="timezone" placeholder="timezone" class="form-control input-md"  type="text">
						    
						  </div>
						</div>	
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="source_ip">source ip</label>  
						  <div class="col-md-4">
						  <input id="source_ip" name="source_ip" placeholder="source_ip" class="form-control input-md"  type="text">
						    
						  </div>
						</div>
						
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="source_url">source url</label>  
						  <div class="col-md-4">
						  <input id="source_url" name="source_url" placeholder="source_url" class="form-control input-md"  type="text">
						    
						  </div>
						</div>	
						<!-- Check box-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="name">Is Visible</label>
						  <div class="col-md-4">
						  <input id="visible" name="visible" placeholder="visible" class="form-control input-md" required="" type="checkbox">
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