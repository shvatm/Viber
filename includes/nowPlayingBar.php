<?php
$songQuery = mysqli_query($conn, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
$resultArr = array();
while($row = mysqli_fetch_array($songQuery)){
array_push($resultArr, $row['id']);
}
$jsonArr = json_encode($resultArr);
  ?>
<script type="text/javascript">
	
	$(document).ready(function(){
		var newPlaylist = <?php echo $jsonArr; ?>;
		audioElement = new Audio();
		setTrack(newPlaylist[0],newPlaylist,false);
		updateVolumeProgressBar(audioElement.audio);
		$("#nowPlayingContainer").on("mousedown touchstart mousemove touchmove", function(e){
			e.preventDefault();
		});

		$(".playbackBar .ProgressBar").mousedown(function(){
			mouseDown = true;
		});
		$(".playbackBar .ProgressBar").mousemove(function(e){
			if(mouseDown){
				//set time according to the position of the mouse
				timeOffset(e,this);
			}	
		});
		$(".playbackBar .ProgressBar").mouseup(function(e){
			timeOffset(e,this);
		});
		$(document).mouseup(function(){
			//using document so the mouseDown will be updated to false no matter where the user will let go of the mouse
			mouseDown = false;
		});

		//volume bar
		$(".volumeBar .ProgressBar").mousedown(function(){
			mouseDown = true;
		});
		$(".volumeBar .ProgressBar").mousemove(function(e){
			if(mouseDown){
				//set time according to the position of the mouse
				var percent = e.offsetX / $(this).width();
				if(percent >=0 && percent <=1){
					audioElement.audio.volume = percent;	
				}	
			}});
		$(".volumeBar .ProgressBar").mouseup(function(e){

				var percent = e.offsetX / $(this).width();
				if(percent >=0 && percent <=1){
					audioElement.audio.volume = percent;	
				}				
		});
	
	});

	function timeOffset(mouse,ProgressBar){
		var percent = mouse.offsetX / $(ProgressBar).width() * 100 ;
		var sec = audioElement.audio.duration * (percent/100);
		audioElement.setTime(sec);

	}

	function nextSong(){
		if(repeat){
			audioElement.setTime(0);
			playSong();
			return;
		}
		if(currId == currentPlaylist.length-1)
			{currId = 0;}
		else{
		currId++;}

		var trackToPlay = shuffle ? shufflePlaylist[currId] : currentPlaylist[currId];
		setTrack(trackToPlay,currentPlaylist,true);
	}

	function prevSong(){
		if(audioElement.audio.currentTime>=3 || currId == 0){
			audioElement.setTime(0);
		}
		else {
			currId = currId - 1;
			setTrack(currentPlaylist[currId],currentPlaylist,true);
		}
	}

	function setRepeat(){
		repeat = !repeat;
		var imgName = repeat? "repeat-active.png": "repeat.png";
		$(".controlButton.repeat img").attr("src","assets/images/icons/" + imgName);
	}

	function setMute(){
		audioElement.audio.muted = !audioElement.audio.muted;
		var muteImg = audioElement.audio.muted? "volume-mute.png": "volume.png";
		$(".controlButton.volume img").attr("src","assets/images/icons/" + muteImg);
	}

	function setShuffle(){
		shuffle = !shuffle;
		var Img = shuffle ? "shuffle-active.png": "shuffle.png";
		$(".controlButton.shuffle img").attr("src","assets/images/icons/" + Img);

		if(shuffle){
			shuffleArr(shufflePlaylist);
			currId = shufflePlaylist.indexOf(audioElement.currentlyPlaying);
		}
		else{
			currId = currentPlaylist.indexOf(audioElement.currentlyPlaying);
		}

	}

	function shuffleArr(a) {
	    var j, x, i;
	    for (i = a.length - 1; i > 0; i--) {
	        j = Math.floor(Math.random() * (i + 1));
	        x = a[i];
	        a[i] = a[j];
	        a[j] = x;
	    }
	    return a;
	}


	function setTrack(trackId,newPlaylist,play){
			if(newPlaylist != currentPlaylist){
				currentPlaylist = newPlaylist;
				shufflePlaylist = currentPlaylist.slice();
				shuffleArr(shufflePlaylist);
			}

			if(shuffle){
				currId = shufflePlaylist.indexOf(trackId);
			}
			else{
				currId = currentPlaylist.indexOf(trackId);	
			}
			
			pauseSong();
		$.post("includes/handlers/ajax/getSongJson.php",{songId: trackId}, function(data){
			var track = JSON.parse(data);
			$(".trackName span").text(track.title);


			$.post("includes/handlers/ajax/getArtistJson.php",{artistId: track.artist}, function(data){
				var artist = JSON.parse(data);
				$(".trackInfo .artistName span").text(artist.name);
				$(".trackInfo .artistName span").attr("onclick","openPage('artist.php?id=" + artist.id + "')");
			});
			$.post("includes/handlers/ajax/getAlbumJson.php",{albumId: track.album}, function(data){
			var album = JSON.parse(data);
			$(".content .albumLink img").attr("src",album.artworkPath);
			$(".content .albumLink img").attr("onclick","openPage('album.php?id=" + album.id + "')");
			$(".trackInfo .trackName span").attr("onclick","openPage('album.php?id=" + album.id + "')");

			});
			audioElement.setTrack(track);
			if(play){
			playSong();
			}
		});

		
	}

	function playSong(){
		if(audioElement.audio.currentTime == 0){
			$.post("includes/handlers/ajax/updatePlays.php",{songId: audioElement.currentlyPlaying.id });		
		}
		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
		audioElement.play();
	}

		function pauseSong(){
		$(".controlButton.pause").hide();
		$(".controlButton.play").show();
		audioElement.pause();
	}

</script>



	<div id="nowPlayingContainer">

		<div id="nowPlayingBar">

		<div id="nowPlayingLeft">
			<div class="content">
				<span class="albumLink">
					<img role="link" tabindex="0" src="" class="albumartwork">
				</span>
				<div class="trackInfo">
					
					<span class="trackName">
						<span role="link" tabindex="0"></span>
					</span>
					<span class="artistName">
						<span role="link" tabindex="0"></span>
					</span>

				</div>

			</div>
		</div>
		<div id="nowPlayingCenter">
			<div class="content playerControl">
				<div class="buttons">
					<button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
						<img src="assets/images/icons/shuffle.png" alt="shuffle">
					</button>
						<button class="controlButton previous" title="previous button" onclick="prevSong()">
						<img src="assets/images/icons/previous.png" alt="previous">
					</button>
						<button class="controlButton play" title="play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="play">
					</button>
					<button class="controlButton pause" title="pause button" style="display: none;" onclick="pauseSong()">
						<img src="assets/images/icons/pause.png" alt="pause">
					</button>
						<button class="controlButton next" title="next button" onclick="nextSong()">
						<img src="assets/images/icons/next.png" alt="next">
					</button>
						<button class="controlButton repeat" title="repeat button" onclick="setRepeat()">
						<img src="assets/images/icons/repeat.png" alt="repeat">
					</button>
			</div>

				<div class="playbackBar">
					<span class="progressTime current">0.00</span>
					<div class="ProgressBar">
						<div class="ProgressBarBg">
							<div class="progress"></div>
						</div>
					</div>
					<span class="progressTime remaining">0.00</span>

				</div>

			</div>
		</div>

		<div id="nowPlayingRight">
			<div class="volumeBar">

						<button class="controlButton volume" title="Volume button" onclick="setMute()">
							<img src="assets/images/icons/volume.png" alt="Volume">
						</button>

						<div class="ProgressBar">
							<div class="ProgressBarBg">
								<div class="progress"></div>
							</div>
						</div>

			</div>

		</div>

</div>
</div>