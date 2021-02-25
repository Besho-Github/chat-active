<?php
	/*
		ReadyChat 2.2.0
		Load Embedded Profiles
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (isset($_GET["profile_id"]) && is_numeric($_GET["profile_id"]) && $GLOBALS["settings"]["allow_profiles"])
	{
		$profile_id = rcClean($_GET["profile_id"]);
		$query_profile = $GLOBALS["mysqli"]->query("SELECT user_id, user_name, profile_age, profile_bio, profile_avatar, profile_sex FROM `users` WHERE `user_id` = '{$profile_id}'");
		
		if ($query_profile->num_rows > 0)
		{
			$profile = $query_profile->fetch_assoc();
			
			/*
				Profile Gender
			*/
			
			switch($profile["profile_sex"])
			{
				case 1:
				{
					$sex = "Male";
					
					break;
				}
				case 2:
				{
					$sex = "Female";
					
					break;
				}
				default:
				{
					$sex = "Unknown Gender";
					
					break;
				}
			}
		
			/*
				Profile Age
			*/
			
			if ($profile["profile_age"] == 0) 
			{ 
				$age = "Unknown Age"; 
			}
			else 
			{ 
				$age = "{$profile["profile_age"]} years old"; 
			}
			
			/*
				Profile Bio
			*/
			
			if ($profile["profile_bio"] == "0" || $profile["profile_bio"] == null) 
			{ 
				$bio = "{$profile["user_name"]} has not shared their bio."; 
			}
			else 
			{ 
				$bio = $profile["profile_bio"]; 
			}
			
			/*
				Show Avatar
			*/
			
			if (file_exists("../template/avatars/{$profile["profile_avatar"]}"))
			{
				$avaurl = $profile["profile_avatar"];
			}
			else
			{
				$avaurl = "no_avatar.jpg";
			}
			
			/*
				Profile Editing Links
			*/
			
			if (isset($_SESSION["readyChatUser"]))
			{
				if ($user["user_id"] == $profile["user_id"])
				{
					$avatar = "<a href=\"profile.php?uid={$profile["user_id"]}&edit=1&avatar=1\" target=\"_blank\"><img src=\"template/avatars/{$avaurl}\"></a>";
					$edit_profile = "<span style=\"font-size:12px;\">(<a href=\"profile.php?uid={$profile["user_id"]}&edit=1\" target=\"_blank\">edit your profile</a>)</span>";
				}
				elseif ($user["rank"] == 3 || $user["apanel"])
				{
					$avatar = "<a href=\"#\" target=\"_blank\"><img src=\"template/avatars/{$avaurl}\"></a>";
					$edit_profile = "<span style=\"font-size:12px;\">(<a href=\"admin/index.php?page=users&sub=edit&edit={$profile["user_id"]}\" target=\"_blank\">edit {$profile["user_name"]}'s profile</a>)</span>";
				}
				else
				{
					$avatar = "<img src=\"template/avatars/{$avaurl}\">";
					$edit_profile = "";
				}
			}
			else
			{
				$avatar = "<img src=\"template/avatars/{$avaurl}\">";
				$edit_profile = "";
			}
			
			/*
				Show Profile
			*/
			
			echo "
			<div id=\"profile_container\">
				<div id=\"profile_box\">
					<div id=\"profile_avatar\">
						{$avatar}
					</div>
					<div id=\"info\">
						<div class=\"title\"><a href=\"profile.php?uid={$profile["user_id"]}\" target=\"_blank\">{$profile["user_name"]}</a>'s Profile {$edit_profile}</div>
						<div class=\"info\">{$sex}</div>
						<br />
						<div class=\"title\">{$profile["user_name"]}'s Bio</div>
						" . nl2br($bio) . "
					</div>
				</div>
			</div>";
		}
		else
		{
			echo 2; // Unknown Profile
		}
	}
	else
	{
		echo 2; // Unknown Profile
	}
	
?> 