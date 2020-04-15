<?php
	class User { 
	private $new_conn;
	private $username;

	public function __construct($conn,$username){
		$this->new_conn = $conn;
		$this->username = $username; 
	}
	public function getUserName(){
		return $this->username;
	}
	public function getEmail(){
		$query = mysqli_query($this->new_conn,"SELECT email FROM users WHERE userName='$this->username'");
		$row = mysqli_fetch_array($query);
		return $row['email'];
	}
	public function getFirstAndLastName(){
		$query = mysqli_query($this->new_conn,"SELECT CONCAT(firstName,' ',lastName) AS 'name' FROM users WHERE userName='$this->username'");
		$row = mysqli_fetch_array($query);
		return $row['name'];
	}
	}
  ?>