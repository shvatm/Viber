<?php 


function cleanUsername($inputText)
{	//strip tags- clean the data from html tags
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	return $inputText;
}

function cleanString($inputText)
{	//strip tags- clean the data from html tags
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	//Upper case first letter of the name
	$inputText = ucfirst(strtolower($inputText));
	return $inputText;
}

function cleanPassword($inputText)
{
	$inputText = strip_tags($inputText);
	return $inputText;
}

if(isset($_POST['registerButton'])){
	//cleaning
	$username = cleanUsername($_POST['registerUsername']);
	$firstname = cleanString($_POST['firstname']);
	$lastname = cleanString($_POST['lastname']);
	$email = cleanString($_POST['email']);
	$email2 = cleanString($_POST['email2']);
	$password = cleanPassword($_POST['registerPassword']);
	$password2 = cleanPassword($_POST['confirmPassword']);
	//validation and creating
	$succeed = $account->register($username, $firstname, $lastname, $email, $email2, $password, $password2);

	if ($succeed) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}

	}
 ?>