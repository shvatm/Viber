<?php 
include("includes/includedFiles.php"); 



if(isset($_GET['id'])){
	$playlist_id = $_GET['id'];
}
else{
	header("Location: index.php");
}


$playlist = new Playlist($conn,$playlist_id);
$owner = new User($conn, $playlist->getOwner());

 ?>

<div class="entityInfo">
	<div class="leftSection">
			<div class="PlaylistImage">
				<img src="assets/images/icons/playlist.png">			
			</div>
		
	</div>

	<div class="rigthSection">
		
		<h2><?php echo $playlist->getName(); ?></h2>
		<p>By <?php echo $playlist->getOwner(); ?></p>
		<p><?php echo $playlist->getNumberofSongs(); ?> songs</p>
		<button class="button" onclick="deletePlaylist('<?php echo $playlist_id; ?>')">Delete Playlist</button>

	
	</div>
</div>

<div class="trackListContainer">
	<ul class="trackList">
		<?php 

		$songIdArray = $playlist->getSongIds();
		$i = 1;

		foreach ($songIdArray as $songid) {
			$playlistSong = new Song($conn, $songid);
			$songArtist = $playlistSong->getArtist();

			echo "<li class='tracklistrow'>
					<div class='tracKCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $playlistSong->getId(). "\", tempPlaylist, true)'>
						<span class='trackNumber'>$i</span>

					</div>

					<div class='trackInfo'>
						<span class='trackName'>" . $playlistSong->getTitle() . "</span>
						<span class='artistName'>". $songArtist->getName(). "</span>
					</div>

					<div class='trackOptions'>
					<input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
						<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $playlistSong->getDuration(). "</span>
					</div>

				</li>";

			$i=$i+1;

		}


		 ?>
		 <script>
		 	var tempSongIds = '<?php echo json_encode($songIdArray);?>';
		 	tempPlaylist = JSON.parse(tempSongIds);

		 </script>
	</ul>
	

</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistDropdown($conn,$userLoggedIn->getUserName()); ?>
	<div class="item" onclick="removeFromPlaylist(this,'<?php echo $playlist_id;?>')">Remove from playlist</div>
</nav>