<?php
	/*
		ReadyChat 2.2.0
		Listing for various features
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (isset($_SESSION["readyChatUser"]))
	{
		if (isset($_POST["save"]) && $_POST["save"] == 1)
		{
			if (isset($_POST["bio_text"]))
			{
				$bio = trim(rcClean($_POST["bio_text"]));
			}
			else
			{
				$bio = null;
			}
			
			if (isset($_POST["location_text"]))
			{
				$location = trim(rcClean($_POST["location_text"]));
			}
			else
			{
				$location = null;
			}
			
			if (isset($_POST["gender_text"]) && is_numeric($_POST["gender_text"]))
			{
				$gen = trim(rcClean($_POST["gender_text"]));
			}
			else
			{
				$gen = 0;
			}
			
			$GLOBALS["mysqli"]->query("UPDATE `users` SET `profile_sex` = '{$gen}', `profile_bio` = '{$bio}', `profile_location` = '{$location}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			
			echo 1;
		}
	}
	
?>