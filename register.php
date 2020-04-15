<?php 
include("includes/classes/Constants.php");
include("includes/config.php");
include("includes/classes/Account.php");
$account = new Account($conn);
include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");



function getInputValue($name){
	if(isset($_POST[$name])){
		echo $_POST[$name];
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Viber!</title>
	<link href="assets/css/register.css?<?=filemtime("assets/css/register.css")?>" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
</head>
<body>
	<?php

	if(isset($_POST['registerButton']))
		echo '<script>
			$(document).ready(function(){
					$("#loginForm").hide();
					$("#registerForm").show();
			});
		</script>';
	else{
			echo '<script>
			$(document).ready(function(){
					$("#loginForm").show();
					$("#registerForm").hide();
			});
		</script>';
	}


	 ?>
	
	<div id="background">
		<div id="loginContainer">
			<div id="inputContainer">
				<form id="loginForm" action="register.php" method="POST">
						<h2>Login to your account</h2>
						<p>
						<?php echo $account->getError(Constants::$loginFail); ?>	
						<label for="loginUsername">Username</label>
						<input id="loginUsername" type="text" name="loginUsername" placeholder="Your name" value="<?php getInputValue('loginUsername')?>"  required> </p>
						<p>	
						<label for="loginPassword">Password</label>
						<input id="loginPassword" type="password" name="loginPassword" placeholder="Your password" required>
						</p>
					<button type="submit" name=logginButton>Log In</button>
					<div class="hasAccountText">
						<span id="hideLogin">Don't have an account yet? Sign Up HERE.</span>
					</div>

				</form>
				<form id="registerForm" action="register.php" method="POST">
					<h2>Open a new account</h2>
					<p>
					<?php echo $account->getError(Constants::$UsernameLength); ?>	
					<?php echo $account->getError(Constants::$UsernameExists); ?>		
					<label for="registerUsername">Username</label>
					<input id="registerUsername" type="text" name="registerUsername" placeholder="What should we call you?" value="<?php getInputValue('registerUsername')?>" required></p>
					<p>
					<?php echo $account->getError(Constants::$firstNameLength); ?>	
					<label for="firstname">First name</label>
					<input id="firstname" type="text" name="firstname" value="<?php getInputValue('firstname')?>" placeholder="Your first name" required></p>
					<p>
					<?php echo $account->getError(Constants::$lNameLength); ?>	
					<label for="lastname">Last name</label>
					<input id="lastname" type="text" name="lastname" value="<?php getInputValue('lastname')?>" placeholder="Your last name" required></p>
					<p>
					<?php echo $account->getError(Constants::$emailsDontMatch); ?>	
					<?php echo $account->getError(Constants::$emailValidation); ?>
					<?php echo $account->getError(Constants::$emailExists); ?>		
					<label for="email">Email</label>
					<input id="email" type="email" name="email" value="<?php getInputValue('email')?>" placeholder="e.g. rick@morty.com" required></p>
					<p>
					<label for="email2">Confirm your Email</label>
					<input id="email2" type="email" name="email2" value="<?php getInputValue('email2')?>" placeholder="e.g. rick@morty.com" required></p>
					<p>	
					<?php echo $account->getError(Constants::$passwordDontMatch); ?>	
					<?php echo $account->getError(Constants::$passwordLength); ?>
					<?php echo $account->getError(Constants::$passwordValidation); ?>	
					<label for="registerPassword">Password</label>
					<input id="registerPassword" type="password" name="registerPassword" placeholder="Your password" required>
					</p>
					<p>	
					<label for="confirmPassword">Confirm password</label>
					<input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confrim Your password" required>
					</p>
					<button type="submit" name="registerButton">Register</button>

					<div class="hasAccountText">
						<span id="hideRegister">Already have an account? Log in HERE.</span>
					</div>
				</form>

			</div>
			<div id="loginText">
				<h1>Great Music for EVERYONE.</h1>
				<h2>a free music and audio streaming platform </h2>
				<ul>
					<li>
						Listen to your favorite songs anywhere, anytime.
					</li>
					<li>
						 Find music that exists nowhere else
					</li>
					<li>Discover exclusive tracks, playlists, DJ sets, remixes and freestyles</li>
					<li>Create your OWN playlists</li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>