<?php

	class Account { 
	private $new_conn;
	private $errorArray=[];

	public function __construct($conn){
		$this->new_conn = $conn;
		$this->errorArray = array(); 
	}

	public function login($username, $pswrd){
		$pw = md5($pswrd);
		$query = mysqli_query($this->new_conn, "SELECT * FROM users WHERE userName ='$username' AND password ='$pw'");
		if(mysqli_num_rows($query)==1){
			return true;
		}
		else
			array_push($this->errorArray, Constants::$loginFail);
			return false;

	}

	public function register($un, $fn, $ln, $email1, $email2, $p1, $p2){
		$this->validateUsername($un);
		$this->validateFirstname($fn);
		$this->validateLastname($ln);
		$this->validateemails($email1,$email2);
		$this->validatePasswords($p1,$p2);	

		if (empty($this->errorArray)) {
			return $this->insertuserDetails($un,$fn,$ln,$email1,$p1);
		}
		else{
			return false;
		}
	}


	private function insertuserDetails($uname,$fname,$lname,$email,$pswrd){
			$encryptedPW = md5($pswrd); //encrypte password
			$profilePic = "assets/images/profilepics/p1.png";
			$date = date("Y-m-d");
			$query = "INSERT INTO users VALUES('','$uname','$fname','$lname','$email','$encryptedPW','$date','$profilePic')";
			$result = mysqli_query($this->new_conn, $query);
			return $result;
	}
	
	public function getError($error) {
		if (is_array($this->errorArray)) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			
		}		
		return "<span class='errorMessage'>$error</span>";
		}

	

	private function validateUsername($urname){
		if(strlen($urname) > 30 || strlen($urname) < 5 ){
			array_push($this->errorArray, Constants::$UsernameLength);
			return;
		}
		$checkuserNameQuery = mysqli_query($this->new_conn,"SELECT userName FROM users WHERE userName='$urname'");
		echo $urname;
		if(mysqli_num_rows($checkuserNameQuery)!= 0){
			array_push($this->errorArray, Constants::$UsernameExists);
			return;
		}

	}
	private function validateFirstname($fname){
		if(strlen($fname) > 30 || strlen($fname) < 1 ){
			array_push($this->errorArray, Constants::$firstNameLength);
			return;
		}
	}
	private function validateLastname($lname){
			if(strlen($lname) > 30 || strlen($lname) < 1 ){
			array_push($this->errorArray, Constants::$lNameLength);
			return;
		}
	}
	private function validateemails($email1,$email2){
		if($email1 != $email2){
			array_push($this->errorArray, Constants::$emailsDontMatch);
			return;
		}

		if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
			array_push($this->errorArray, Constants::$emailValidation);
			return;
		}

		$checkemailQuery = mysqli_query($this->new_conn,"SELECT email FROM users WHERE email='$email1'");
		if(mysqli_num_rows($checkemailQuery)!=0){
			array_push($this->errorArray, Constants::$emailExists);
		}
		
	}
	private function validatePasswords($p1,$p2){
		if($p1 != $p2){
			array_push($this->errorArray, Constants::$passwordDontMatch);
			return;
		}
		if(preg_match('/[^A-Za-z0-9]/', $p1)){
			array_push($this->errorArray, Constants::$passwordValidation);
			return;

		}

		if(strlen($p1) > 30 || strlen($p1) < 5 ){
			array_push($this->errorArray,Constants::$passwordLength);
			return;
		}
	}

}

  ?>