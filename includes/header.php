<?php 
session_start();
if(!isset($_SESSION['petetion_access_token'])){
	header ("Location: login.php");
	exit;
}
error_reporting(E_ALL); ini_set('display_errors', 'On');
require "includes/config.php";
require "vendor/autoload.php";
$api = new CloudWelder\PetitionsApi\PetitionsApi($config['api_key'], $config['client_id'],$config['redirect_path']);
$api->withToken($_SESSION['petetion_access_token']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">


<title>Dashboard</title>
<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet"
	href="assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
	href="assets/vendor/font-awesome/css/font-awesome.min.css">
<!-- Styles -->

</head>
<body>
	<div id="app" class="container">
 	<?php $first_name =  isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'First Name' ; 
	$last_name =  isset($_SESSION['last_name'])? $_SESSION['last_name']:'Last Name';
	?> 
	<!-- Top nav bar -->
		<nav class="navbar navbar-default" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="">Petition API</a>
			</div>
			<div class="collapse navbar-collapse" id="">
				<ul class="nav navbar-nav">
					<li class="active"><a href="dashboard.php"><span
							class="glyphicon glyphicon-home"></span>Dashboard</a></li>
					<li class="dropdown"><a href="users.php" class="dropdown-toggle"
						data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span>
							User <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="create_user.php"> Create User </a></li>
							<li><a href="users.php"> List Users </a></li>
							<li><a>Search User</a></li>
							<li>
								<form class="navbar-form" role="search" method="post"
									action="search_users.php">
									<div class="input-group">
										<input type="text" class="form-control"
											placeholder="Search Users" name="filter">
										<div class="input-group-btn">
											<button class="btn btn-default" type="submit">
												<i class="glyphicon glyphicon-search"></i>
											</button>
										</div>
									</div>
								</form>
							</li>
						</ul></li>
					<li class="dropdown"><a href="organisations.php"
						class="dropdown-toggle" data-toggle="dropdown"><span
							class="glyphicon glyphicon-list-alt"></span>Organisation <b
							class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="organisations.php">list Organisation</a></li>
							<li><a href="create_organisation.php">Create Organisation</a></li>
							<li><a>Search Organisation</a></li>
							<li>
								<form class="navbar-form" role="search" method="post"
									action="search_organisation.php">
									<div class="input-group">
										<input type="text" class="form-control"
											placeholder="Search Organisation" name="filter">
										<div class="input-group-btn">
											<button class="btn btn-default" type="submit">
												<i class="glyphicon glyphicon-search"></i>
											</button>
										</div>
									</div>
								</form>
							</li>
						</ul></li>
					<li class="dropdown"><a href="create_petition.php"
						class="dropdown-toggle" data-toggle="dropdown"><span
							class="glyphicon glyphicon-list-alt"></span>Create Petition <b
							class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="create_petition.php">Create petition</a></li>
						</ul></li>
					<li class="dropdown"><a href="create_target.php"
						class="dropdown-toggle" data-toggle="dropdown"><span
							class="glyphicon glyphicon-list-alt"></span>Target <b
							class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="create_target.php">Create Target</a></li>
						</ul></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><?php echo $first_name." ".$last_name;?> <b
							class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>Profile</a></li>
							<li><a href="update_password.php"><span
									class="glyphicon glyphicon-cog"></span>Update Password</a></li>
							<li class="divider"></li>
							<li><a href="logout.php"><span class="glyphicon glyphicon-off"></span>Logout</a></li>
						</ul></li>
				</ul>
			</div>
		</nav>