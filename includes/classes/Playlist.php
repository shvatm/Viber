<?php
	class Playlist { 
	private $con;
	private $id;
	private $name;
	private $owner;



	public function __construct($conn,$data){
		if(!is_array($data)){
			$query = mysqli_query($conn, "SELECT * FROM Playlists WHERE id='$data'");
			$data = mysqli_fetch_array($query);
		}
		$this->con = $conn;
		$this->id = $data['id']; 
		$this->name = $data['name']; 
		$this->owner = $data['owner']; 
	}
	public function getName(){
		return $this->name;
	}
	public function getOwner(){
	return $this->owner;
	}
	public function getId(){
	return $this->id;
	}
	public function getNumberofSongs(){
		$query = mysqli_query($this->con,"SELECT songId FROM playlistsongs WHERE playlistId='$this->id'");
		return mysqli_num_rows($query);
	}
	public function getSongIds(){
		$query = mysqli_query($this->con, "SELECT songId FROM playlistsongs WHERE playlistId='$this->id' ORDER BY playlistOrder ASC");
		$array = array();
		while($row = mysqli_fetch_array($query)){
			array_push($array, $row['songId']);
		}
		return $array;
	}

	public static function getPlaylistDropdown($conn,$userName){
		$dropDown = '<select class="item playlist">
					<option value="">Add to playlist</option>';
		$query = mysqli_query($conn,"SELECT id,name FROM playlists WHERE owner='$userName'");
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$name = $row['name'];
			$dropDown = $dropDown. "<option value='$id'>$name</option>";
		}
		return $dropDown . '</select>';
	}


	}
  ?>