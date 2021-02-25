<?php if (!defined('access')): die("403"); endif; ?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS["settings"]["site_title"] . " - Edit Profile"; ?></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatProfiles.css" />
</head>
<body>
	<?php if (isset($_GET["saved"])): echo "<div id=\"success\">Profile successfully saved! <a href=\"profile.php?uid={$GLOBALS["user"]["user_id"]}\">Return to profile?</a></div>"; endif; ?>
	<div class="overlay"></div>
	<div id="saving" class="modal">
		<div id="pleaseWait">
			<div id="loader" class="loading"></div><br />
			<div id="waitText">
				Please Wait<br />
				<span style="font-size:13px;">Saving your profile</span>
			</div>
		</div>
	</div>
	<div class="wrapper">
		<div id="profile_header">
			Profile Editor
			
			<div id="private_chat"><?php echo $private_chat; ?></div>
		</div>
		<div id="profile_container">
			<div id="profile_left">
				<div id="avatar"><img src="template/avatars/<?php echo $avaurl; ?>"></div>
				<div id="gender"><?php echo $gender; ?></div>
				<?php echo $edit_profile; ?> 
			</div>
			<div id="profile_right">
				<form id="save_profile" action="profile.php?uid=<?php echo $user["user_id"]; ?>&edit=1" method="post">
					<div class="title" style="margin-top:-20px;">Your Biography</div>
					<div id="bio">
						<textarea id="bio_text" name="bio" maxlength="300"><?php echo $user["profile_bio"]; ?></textarea>
					</div>
					
					<div class="title">Your Location</div>
					<div id="bio">
						<input type="text" id="location_text" name="location" maxlength="100" value="<?php echo $location; ?>">
					</div>
					
					<div class="title">Your Gender</div>
					<div id="gender_select">
						<select id="gender_text" name="gender">
							<?php
							switch($profile["profile_sex"])
							{
								case 1:
								{
									echo "
										<option value=\"1\">-- Male</option>
										<option value=\"2\">Female</option>
										<option value=\"0\">Undisclosed</option>
									";
									
									break;
								}
								case 2:
								{
									echo "
										<option value=\"2\">-- Female</option>
										<option value=\"1\">Male</option>
										<option value=\"0\">Undisclosed</option>
									";
									
									break;
								}	
								default:
								{
									echo "
										<option value=\"0\">-- Undisclosed</option>
										<option value=\"2\">Female</option>
										<option value=\"1\">Male</option>
									";
									
									break;
								}
							}
							?>
						</select>
					</div>
					<div id="save_profile">
						<input id="button" type="submit" value="Save Profile">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="footer">
		<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
	</div>
</body>
<script type="text/javascript" src="library/jquery.js"></script>
<script type="text/javascript" src="core/rc.profile.js"></script>
</html>