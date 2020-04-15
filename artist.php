<?php
include("includes/includedFiles.php");

if(isset($_GET['id'])){
	$artist_id = $_GET['id'];
}
else{
	//need to change to artist cant be found page
	header("Location: index.php");
}

$artist = new Artist($conn, $artist_id);
?>
<div class="entityInfo">
	<div class="centerSection">
		<div class="artistInfo">
			<h1 class="artistName"><?php echo $artist->getName(); ?></h1>
			<div class="headerButtons">
				<button class="button green" onclick="playFirstSong()">Play</button>
			</div>
	</div>
	
</div>

<div class="trackListContainer">
	<h2>Songs</h2>
	<ul class="trackList">
		<?php 

		$songIdArray = $artist->getSongIds();
		$i = 1;

		foreach ($songIdArray as $songid) {
			if ($i > 5){
				break;
			}
			$albumSong = new Song($conn, $songid);
			$albumArtist = $albumSong->getArtist();

			echo "<li class='tracklistrow'>
					<div class='tracKCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $albumSong->getId(). "\", tempPlaylist, true)'>
						<span class='trackNumber'>$i</span>

					</div>

					<div class='trackInfo'>
						<span class='trackName'>" . $albumSong->getTitle() . "</span>
						<span class='artistName'>". $albumArtist->getName(). "</span>
					</div>

					<div class='trackOptions'>
					<input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
						<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $albumSong->getDuration(). "</span>
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

<div class="gridViewContainer">
	<h2>Albums</h2>
	<?php 
	$albumQuery = mysqli_query($conn,"SELECT * FROM albums WHERE artist='$artist_id'");
	while($row = mysqli_fetch_array($albumQuery)){
		echo "<div class='gridViewItem'>
			<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'> 
			<img src='" . $row['artworkPath'] ."'>
			<div class='gridViewInfo'>"
			. $row['title'] .
			"</div>
			</span>
		 </div>";
	
	}
	 ?>
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistDropdown($conn,$userLoggedIn->getUserName()); ?>
</nav>


