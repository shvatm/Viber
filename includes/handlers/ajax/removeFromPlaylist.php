<?php
	include("../../config.php");
	if(isset($_POST['playlistId']) && isset($_POST['songId'])){
	$playlistId = $_POST['playlistId'];
	$songId = $_POST['songId'];
	
	$query = mysqli_query($conn,"DELETE FROM playlistsongs WHERE playlistId='$playlistId' AND songId='$songId'");

}
else{
	echo "playlist id or song id wasn't passed to the removeFromPlaylist.php ";
}

  ?>