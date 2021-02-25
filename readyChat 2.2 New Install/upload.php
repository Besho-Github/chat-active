<?php
	/*
		ReadyChat 2.2.0
		File Uploader
	*/
	 
	require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"]) || isset($_SESSION["readyChatUser"]) && $GLOBALS["user"]["active"] == 0){ die("You can't do that right now."); }
	
	if (isset($_POST["upload_token"]) && $_POST["upload_token"] == md5($_SESSION["upload_token"]) && $settings["allow_uploads"])
	{
		/*
			Define Valid File Types
		*/
		
		$types = array(
			"png", "jpeg", "jpg", "gif"
		);
		
		/*
			Confirm file extension & determine max size allowed
		*/
		
		$extension = explode(".", $_FILES["file"]["name"]);
		$full_size = $settings["avatar_size"] * 1024;
		
		
		if (((
			$_FILES["file"]["type"] == "image/gif") || 
			($_FILES["file"]["type"] == "image/jpeg") || 
			($_FILES["file"]["type"] == "image/jpg") || 
			($_FILES["file"]["type"] == "image/pjpeg") || 
			($_FILES["file"]["type"] == "image/x-png") || 
			($_FILES["file"]["type"] == "image/png")) && 
			($_FILES["file"]["size"] <= $full_size) && in_array($extension[1], $types))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				header('location: profile.php?uid=' . $GLOBALS["user"]["user_id"] . '&edit=1&avatar=1&error=1');
			}
			else
			{
				imagepng(imagecreatefromstring(file_get_contents($_FILES["file"]["tmp_name"])), "template/avatars/uploads/{$GLOBALS["user"]["user_id"]}.jpg");
				$mysqli->query("UPDATE `users` SET `profile_avatar` = 'uploads/{$GLOBALS["user"]["user_id"]}.jpg' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
				
				header('location: profile.php?uid=' . $GLOBALS["user"]["user_id"] . '&edit=1&avatar=1&success=1');
			}
		}
		else
		{
			header('location: profile.php?uid=' . $GLOBALS["user"]["user_id"] . '&edit=1&avatar=1&error=1');
		}
	}
	else
	{
		header('location: profile.php?uid=' . $GLOBALS["user"]["user_id"] . '&edit=1&avatar=1&error=1');
	}
?>
