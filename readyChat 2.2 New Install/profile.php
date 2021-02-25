<?php
	/*
		ReadyChat 2.2.0
		Profiles
	*/
	 
	require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	/*
		Confirm whether profiles should be enabled or not
	*/
	
	if ($GLOBALS["settings"]["allow_profiles"] == 1)
	{
		$profiles = true;
	}
	else
	{
		$profiles = false;
	}
	
	/*
		Configure UID
	*/
	
	if (isset($_GET["uid"]))
	{
		$id = trim(rcClean($_GET["uid"]));
		
		$query_profiles = $GLOBALS["mysqli"]->query("SELECT user_id, user_name, profile_age, profile_bio, profile_avatar, profile_sex, profile_location, active FROM `users` WHERE `user_id` = '{$id}'");
		if ($query_profiles->num_rows > 0 && isset($id))
		{
			$profile = $query_profiles->fetch_assoc();
			$view = true;
			
			/*
				Edit Link
			*/
			
			$edit_profile = null;
			
			if (isset($uSession))
			{
				if ($profile["user_id"] == $GLOBALS["user"]["user_id"])
				{
					$edit_profile = "
					<div id=\"edit_profile\">
						<a href=\"?uid={$profile["user_id"]}\"><img src=\"template/{$GLOBALS["settings"]["template"]}/icons/profile.png\"> My Profile</a><br />
						<a href=\"?uid={$profile["user_id"]}&edit=1\"><img src=\"template/{$GLOBALS["settings"]["template"]}/icons/edit.png\"> Edit Profile</a><br />
						<a href=\"?uid={$profile["user_id"]}&edit=1&avatar=1\"><img src=\"template/{$GLOBALS["settings"]["template"]}/icons/avatar.png\"> Change Avatar</a>
					</div>";
				}
				elseif ($GLOBALS["user"]["rank"] == 3 || $GLOBALS["user"]["apanel"])
				{
					$edit_profile = "<div id=\"edit_profile\"><a href=\"admin/index.php?page=users&sub=edit&edit={$profile["user_id"]}\">Edit Profile</a></div>";
				}
			}
			
			/*
				User Avatar
			*/

			if (file_exists("template/avatars/{$profile["profile_avatar"]}"))
			{
				$avaurl = $profile["profile_avatar"];
			}
			else
			{
				$avaurl = "no_avatar.jpg";
			}
			
			/*
				Check if user is online
			*/
			
			if ($profile["active"])
			{
				$online = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/online.png\" title=\"{$profile["user_name"]} is online\">";
			}
			else
			{
				$online = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/offline.png\" title=\"{$profile["user_name"]} is offline\">";
			}
			
			/*
				User Location
			*/
			
			if (isset($profile["profile_location"]) && $profile["profile_location"] != "Unknown" && $profile["profile_location"] != null)
			{
				$location = $profile["profile_location"];
			}
			else
			{
				$location = "Unknown";
			}
			
			/* 
				User Gender
			*/
			
			switch($profile["profile_sex"])
			{
				case 1:
				{
					$gender = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/male.png\" title=\"{$profile["user_name"]} is male\">";
					break;
				}
				case 2:
				{
					$gender = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/female.png\" title=\"{$profile["user_name"]} is female\">";
					break;
				}	
				default:
				{
					$gender = null;
					break;
				}
			}
			
			/*
				Private Chat Link
			*/
			
			if ($GLOBALS["settings"]["private_messages"] && isset($GLOBALS["uSession"]) && $profile["user_id"] != $GLOBALS["user"]["user_id"])
			{
				$private_chat = "<div class=\"button\"><a onclick=\"url('Private Chat', 'private_chat.php?cid={$profile["user_id"]}&who={$profile["user_name"]}', '600', '460')\"><img src=\"template/{$GLOBALS["settings"]["template"]}/icons/alert.png\"> Private Chat</a></div>";
			}
			else
			{
				$private_chat = null;
			}
			
			/*
				User Bio
			*/
			
			if ($profile["profile_bio"] == "0" || $profile["profile_bio"] == null) 
			{ 
				$bio = "{$profile["user_name"]} has not shared their bio."; 
			}
			else 
			{ 
				$bio = nl2br($profile["profile_bio"]); 
			}
			
			if (isset($_GET["save_profile"]) && $_GET["save_profile"] == 1)
			{
				/*
					Save Profile
				*/
				
				$bio = rcClean($_POST["bio"]);
				$gender = rcClean($_POST["gender"]);
				
				if (!is_numeric($gender))
				{
					$gender = 0;
				}
				
				$mysqli->query("UPDATE `users` SET `profile_bio` = '{$bio}', `profile_sex` = '{$gender}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
				header('location: profile.php?uid=' . $GLOBALS["user"]["user_id"] . '&edit=1&saved=1');
			}
			elseif (isset($_GET["avatar"]) && $_GET["avatar"] == 1 && isset($uSession) && $user["user_name"] == $profile["user_name"])
			{
				include("template/{$GLOBALS["settings"]["template"]}/html/selectAvatar.html.php");
			}
			else
			{
				/*
					Include profile page
				*/
			
				if (isset($_GET["edit"]) && isset($uSession) && $user["user_name"] == $profile["user_name"])
				{
					include("template/{$GLOBALS["settings"]["template"]}/html/editProfile.html.php");
				}
				else
				{	
					include("template/{$GLOBALS["settings"]["template"]}/html/profile.html.php");
				}			
			}
		}
		else
		{
			$view = false;
			$edit = "";
			
			exit("Unable to load profile.");
		}
	}
	else
	{
		$view = false;
		$edit = "";
	}
	
?>