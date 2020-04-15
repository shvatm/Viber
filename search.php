<?php
include("includes/includedFiles.php");
if(isset($_GET['term'])){
	$term = urldecode($_GET['term']);
}
else{
	$term="";
}
  ?>
  <div class="searchContainer">
  	<h4>Search for an artist, album or song</h4>
	<input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Search Here" onfocus="this.value=this.value">  	
  </div>
  <script type="text/javascript">
  	$(".searchInput").focus();
  	$(function(){
  		$(".searchInput").keyup(function(){
  			clearTimeout(timer);
  			timer = setTimeout(function(){
  				var val = $(".searchInput").val();
  				openPage("search.php?term="+val);
  			},2000);
  		});
  	});
  </script>
  <?php if($term =="") exit();  ?>

  <div class="trackListContainer">
	<h2>Songs</h2>
	<ul class="trackList">
		<?php 
		$songsQuery = mysqli_query($conn, "SELECT id FROM songs WHERE title LIKE '%$term%' LIMIT 15");

		if(mysqli_num_rows($songsQuery)==0){
			echo "<span class='noResults'>No songs found mathcing " .$term. "</span>";
		}
		$songIdArray = array();
		$i = 1;

		while($row = mysqli_fetch_array($songsQuery)){
				if ($i > 15){
				break;
			}
			array_push($songIdArray, $row['id']);		
			$albumSong = new Song($conn, $row['id']);
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
<div class="artistContainer">
	<h2>Artist</h2>
	<?php
		$artistQuery = mysqli_query($conn, "SELECT id from artists WHERE name LIKE '%$term%'");
		if(mysqli_num_rows($artistQuery)==0){
			echo "<span class='noResults'>No artists found mathcing " .$term. "</span>";
		}
		while($row = mysqli_fetch_array($artistQuery)){
			$artistfound = new Artist($conn , $row['id']);
			echo "<div class='searchResultrow'>
			<div class='artistName'>
				<span role='link' tableindex='0' onclick='openPage(\"artist.php?id=". $artistfound->getId(). "\")'>
				"
				.$artistfound->getName(). 
				"
				</span>
			</div>
			</div>";
		}
	  ?>
</div>
<div class="albumContainer">
	<h2>Albums</h2>
	<?php 
	$albumQuery = mysqli_query($conn,"SELECT * FROM albums WHERE title LIKE  '%$term%' LIMIT 15");
	if(mysqli_num_rows($albumQuery)==0){
			echo "<span class='noResults'>No albums found mathcing " .$term. "</span>";
		}
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