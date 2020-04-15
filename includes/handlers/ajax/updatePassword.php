<?php 
include("../../config.php");
if(!isset($_POST['username']) && $_POST['email']!=""){
	echo "error: could not set username";
	exit();
}

if(!isset($_POST['oldpassword'])||!isset($_POST['newpass1'])||!isset($_POST['newpass2'])){
	echo "Not all passwords have been set";
	exit();
}
if($_POST['oldpassword']==""||$_POST['newpass1']==""||$_POST['newpass2']==""){
	echo "Please fill in all fields";
	exit();
}
$username = $_POST['username'];
$oldpassword = $_POST['oldpassword'];
$newPass1 = $_POST['newpass1'];
$newPass2 = $_POST['newpass2'];

$oldMd5 = md5($oldpassword);
$passCheck = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
if(mysqli_num_rows($passCheck)!=1){
	echo "password is incorrect";
	exit();
}
if($newPass1!=$newPass2){
	echo "Your new passwords dont match";
	exit();
}

if(preg_match('/[^A-Za-z0-9]/', $newPass1)){
	echo "Your password can only contain letters and numbers";
	exit();
}

if(strlen($newPass1)>30 || strlen($newPass1) <5){
	echo "Your password length should be between 5 to 30 characters";
}

$newMd5 = md5($newPass1);
$query = mysqli_query($conn, "UPDATE users SET password='$newMd5' WHERE username='$username'");
echo "Successfully updated";
?>