var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currId = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(window).scroll(function(){
	hideOptionsMenu();
})

$(document).click(function(click){
	var target = $(click.target);
	if(!target.hasClass("item")&& !target.hasClass("optionsButton")){
		hideOptionsMenu();
	}

})

$(document).on("change","select.playlist",function(){
	var select = $(this);
	var playlistid = select.val();
	var songid = $(this).prev(".songId").val();
	$.post("includes/handlers/ajax/addToPlaylist.php",{playlistId : playlistid ,songId : songid}).done(function(error){
		if(error!=""){
				alert(error);
				return;
			}
		hideOptionsMenu();
		$(select).val="";
	});
	
});

function logout(){
	$.post("includes/handlers/ajax/logout.php",function(){
		location.reload();
	});
}


function playFirstSong(){
	setTrack(tempPlaylist[0],tempPlaylist, true);
}

function updateEmail(emailClass){
	var emailValue = $("." + emailClass).val();
	$.post("includes/handlers/ajax/updateEmail.php",{email: emailValue, username: userLoggedIn}).done(function(response){
		$("."+ emailClass).nextAll(".message").text(response);
	});
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2){
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPasswordClass1).val();
	var newPassword2 = $("." + newPasswordClass2).val();

	$.post("includes/handlers/ajax/updatePassword.php",{oldpassword: oldPassword, newpass1: newPassword1 , newpass2 : newPassword2, username: userLoggedIn}).done(function(response){
		$("."+ oldPasswordClass).nextAll(".message").text(response);
	});
}

function openPage(url){
	if(timer != null){
		clearTimeout(timer);
	}
	if(url.indexOf("?") == -1){
		url = url + "?";
	}
	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);
	history.pushState(null,null,url);

}

function removeFromPlaylist(button,playlistId){
	/*goes to the ancestor with class songId and gets its value*/
	var songId = $(button).prevAll(".songId").val();		
	$.post("includes/handlers/ajax/removeFromPlaylist.php",{playlistId:playlistId, songId:songId}).done(function(error){
		if(error!=""){
			alert(error);
			return;
		}
		openPage("playlist.php?id="+ playlistId);
	});
		

}

function createPlaylist(username){
	var  input = prompt("Please enter the name of your playlist");

	if(input != null){
		$.post("includes/handlers/ajax/createPlaylist.php",{name: input ,username : userLoggedIn}).done(function(error){
			if(error!=""){
				alert(error);
				return;
			}
			openPage("yourmusic.php");
		});
	}

}

function hideOptionsMenu(){
	var menu = $(".optionsMenu");
	if(menu.css("display")!= "none"){
		menu.css("display","none");
	}

}

function showOptionsMenu(button){
	/*goes to the ancestor with class songId and gets its value*/
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	/*goes to the options menu and set the value of songId*/
	menu.find(".songId").val(songId);
	var scrollTop = $(window).scrollTop(); //distance from top of the window to top of the document
	var elementOffset = $(button).offset().top; //position of the button from the windows top
	var top = elementOffset - scrollTop;
	var left = $(button).position().left;
	menu.css({"top":top+"px", "left":left - menuWidth+"px", "display":"inline"})

}

function deletePlaylist(playlistId){
	var prompt = confirm("Are you sure you want to delete this playlist?");
	if(prompt){
		
			$.post("includes/handlers/ajax/deletePlaylist.php",{playlistId:playlistId}).done(function(error){
				if(error!=""){
					alert(error);
					return;
				}
				openPage("yourmusic.php");
			});
		}
}


function formatTime(seconds){
	var time = Math.round(seconds);
	var minutes = Math.floor(time/60); //rounds it down
	var sec = time - (minutes*60); //seconds that have left
	var extraZero=(sec < 10)? "0":"";
	return minutes + ":" + extraZero + sec;

}

function updateTimeProgressBar(audio){
	$(".progressTime.current").text(formatTime(audio.currentTime));
	$(".progressTime.remaining").text(formatTime(audio.duration-audio.currentTime));
	var progress = audio.currentTime / audio.duration * 100;
	$(".playbackBar .progress").css("width",progress + "%");
}

function updateVolumeProgressBar(audio){
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width",volume + "%");
}

function Audio(){
	this.currentlyPlaying;
	this.audio = document.createElement('audio');
	this.audio.addEventListener("canplay",function(){
		//this refers to the object the event was called on= audio
		$(".progressTime.remaining").text(formatTime(this.duration));
	});

	this.audio.addEventListener("ended",function(){
		nextSong();
	});

	this.audio.addEventListener("timeupdate", function(){
		if(this.duration){
			updateTimeProgressBar(this);
		}
	});

	this.audio.addEventListener("volumechange", function(){
		updateVolumeProgressBar(this);
	});

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function(){
		this.audio.play();
	}

	this.pause = function(){
		this.audio.pause();
	}

	this.setTime = function(seconds){
		this.audio.currentTime = seconds;
	}
}