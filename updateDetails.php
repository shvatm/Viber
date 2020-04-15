<?php
include("includes/includedFiles.php"); 
  ?>

 <div class="userDetails">
 	<div class="container borderBottom">
 		<h2>Email</h2>
 		<input type="text" class="email" name="email" placeholder="Email adress..." value="<?php echo $userLoggedIn->getEmail(); ?>">
 		<span class="message"></span>
 		<span class="button" onclick="updateEmail('email')">Save</span>
 	</div>

 	<div class="container borderBottom">
 		<h2>Password</h2>
		<input type="password" class="oldPassword" name="oldpassword" placeholder="current password">
		<input type="password" class="newPassword1" name="newPassword1" placeholder="New password">
		<input type="password" class="newPassword2" name="newPassword2" placeholder="Confirm your new password">
 		<span class="message"></span>
 		<span class="button" onclick="updatePassword('oldPassword','newPassword1','newPassword2')">Save</span>
 	</div>
 	
 </div>