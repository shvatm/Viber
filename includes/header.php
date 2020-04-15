<?php
include("includes/config.php");
include("includes/classes/User.php");
include("includes/classes/Playlist.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");


if(isset($_SESSION['userLoggedIn'])){
	$userLoggedIn = new User($conn, $_SESSION['userLoggedIn']);
	$username = $userLoggedIn->getUserName();
	echo "<script>userLoggedIn = '$username'; </script>";
}
else{
	header("Location: register.php"); 
}

  ?>


<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Viber!</title>
	<link href="assets/css/style.css?<?=filemtime("assets/css/style.css")?>" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="assets/js/script.js?newversion"></script>
</head>
<body>


	<div id="mainContainer">
		<div id="topContainer">
			<?php include("includes/navBarContainer.php"); ?>
			<div id="mainViewContainer">
				<div id="mainContent">