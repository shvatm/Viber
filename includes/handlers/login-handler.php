<?php 
if(isset($_POST['logginButton'])){
	$username = $_POST['loginUsername'];
	$pswrd = $_POST['loginPassword'];

$result = $account->login($username,$pswrd);	

if($result){
	$_SESSION['userLoggedIn'] = $username;
	header("Location: index.php");
}

} ?>