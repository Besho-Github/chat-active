<?php if (!defined('access')): die("403"); endif; ?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-store" />
	<title><?php echo $GLOBALS["settings"]["site_title"] . " - Edit Profile"; ?></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatProfiles.css" />
</head>
<body>
	<?php if (isset($_GET["saved"])): echo "<div id=\"success\">Profile successfully saved! <a href=\"profile.php?uid={$GLOBALS["user"]["user_id"]}\">Return to profile?</a></div>"; endif; ?>
	<div class="wrapper">
		<div id="profile_header">
			Avatar Selection
			
			<div id="private_chat"><?php echo $private_chat; ?></div>
		</div>
		<div id="profile_container">
			<div id="profile_left">
				<div id="avatar"><img src="template/avatars/<?php echo $avaurl; ?>"></div>
				<div id="gender"><?php echo $gender; ?></div>
				<?php echo $edit_profile; ?>
			</div>
			<div id="profile_right">
				<?php
					if (isset($_GET["sav"]) && $_GET["sav"] != null)
					{
						$newav = trim(rcClean($_GET["sav"]));
						if (file_exists("template/avatars/{$newav}"))
						{
							// update avatar
							$mysqli->query("UPDATE `users` SET `profile_avatar` = '{$newav}' WHERE `user_id` = '{$profile["user_id"]}'");
							
							// redirect
							header('location: profile.php?uid=' . $profile["user_id"] . '&edit=1&avatar=1');
						}
						else
						{
							echo "
								<div class=\"title\">Error</div>
								<span style=\"padding:20px; text-align:center; width:459px; float:left;\">The avatar you selected does not exist! <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.</span>
							";
						}	
					}
					else
					{
						echo "<div class=\"title\">Select an avatar</div>";
						echo "<div id=\"avatarselect\" style=\"padding:9px;\">";
						if ($handle = opendir('template/avatars')) 
						{
							while (false !== ($file = readdir($handle))) 
							{
								if ($file != "." && $file != ".." && $file != "uploads")
								{
									echo "<a href='profile.php?uid={$profile["user_id"]}&edit=1&avatar=1&sav={$file}'><img src='template/avatars/$file' height='80px;' width='80px;'></a>";
								}
							}
							
							closedir($handle);
						}		
						
						echo "</div>";
						
						if ($settings["allow_uploads"])
						{
							if ($user["user_id"] == $profile["user_id"])
							{
								if (isset($_GET["error"]) && $_GET["error"] == 1)
								{
									$upload_error = "<span style=\"color:red;\">There was an error uploading the avatar.</span><br /><br />";
								}
								else
								{
									$upload_error = "";
								}
								
								if (isset($_GET["success"]) && $_GET["success"] == 1)
								{
									$upload_error = "<span style=\"color:green;\">Avatar uploaded successfully</span><br /><br />";
								}
								
								if (!isset($_SESSION["upload_token"]))
								{
									$_SESSION["upload_token"] = sha1(rand(128, 5000));
								}
								
								if (isset($_GET["delete"]) && $_GET["delete"] == 1)
								{
									// delete avatar
									if (file_exists("template/avatars/uploads/{$profile["user_id"]}.jpg"))
									{
										unlink("template/avatars/uploads/{$profile["user_id"]}.jpg");
									}
									
									// set no avatar
									$mysqli->query("UPDATE `users` SET `profile_avatar` = 'no_avatar.jpg' WHERE `user_id` = '{$profile["user_id"]}'");
									
									// redirect
									header('location: profile.php?uid=' . $profile["user_id"] . '&edit=1&avatar=1');
								}
								else
								{
									echo "
									<div id=\"upload\">
										<div class=\"title\">Upload your own avatar!</div>
										<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\" style=\"padding:20px;\">
											{$upload_error}
											<input type=\"file\" name=\"file\"  />
											<div class=\"clear\"></div>
											<input type=\"hidden\" name=\"upload_token\" value=\"" . md5($_SESSION["upload_token"]) . "\">
											<input id=\"button\" style=\"margin-top:20px;\" type=\"submit\" value=\"Upload Avatar\">
										</form>
									</div>
									";
								}
							}
						}
					}
				?>
			</div>
		</div>
	</div>
	<div id="footer">
		<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
	</div>
</body>
</html>