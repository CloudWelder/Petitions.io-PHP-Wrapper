<?php
if (isset($_SESSION['form']['error']) &&  $_SESSION['form']['error'] == true): /* The last form submission had 1 or more errors */ ?>
<div class="alert alert-danger fade in">There was a problem with your submission.<br>  <?php 
if(is_array($_SESSION['form']['message'] )){
	foreach ($_SESSION['form']['message'] as $message)
		echo $message."<br>";
	
}else{
echo $_SESSION['form']['message'];
}
?> </div><br>
<?php elseif (isset($_SESSION['form']['success']) && $_SESSION['form']['success'] == true): /* form was processed successfully */ ?>
<div class="alert alert-success fade in">The Form was successfully submitted .</br>
<?php echo $_SESSION['form']['message'];
?></div><br />
<?php endif; 
unset($_SESSION['form']);
?> 