<?php
	/*
		ReadyChat 2.2.0
		Logout of readyChat
	*/
	 
	require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	if ($GLOBALS["uSession"] != null || $GLOBALS["gSession"] != null):
	
		if ($GLOBALS["gSession"])
		{
			$GLOBALS["mysqli"]->query("UPDATE `guests` SET `active` = '0' WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'");
			
			if (isset($_SESSION["room_guard"])){ unset($_SESSION["room_guard"]); }
			if (isset($_SESSION["readyChatGuest"])){ unset($_SESSION["readyChatGuest"], $_SESSION["post_key"]); }
		}
		else
		{
			$GLOBALS["mysqli"]->query("UPDATE `users` SET `active` = '0' WHERE `user_id` = '{$user["user_id"]}'");
			
			unset($_SESSION["readyChatUser"], $_SESSION["post_key"]);
			if (isset($_SESSION["room_guard"])){ unset($_SESSION["room_guard"]); }
		
		
			if (isset($_SESSION["admin_key"])){ unset($_SESSION["admin_key"]); }
			if (isset($_SESSION["settings_token_1"])){ unset($_SESSION["settings_token_1"]); }
			if (isset($_SESSION["settings_token_2"])){ unset($_SESSION["settings_token_2"]); }
			if (isset($_SESSION["settings_token_3"])){ unset($_SESSION["settings_token_3"]); }
			if (isset($_SESSION["settings_token_4"])){ unset($_SESSION["settings_token_4"]); }
			if (isset($_SESSION["settings_token_games"])){ unset($_SESSION["settings_token_games"]); }
			if (isset($_SESSION["users_token"])){ unset($_SESSION["users_token"]); }
			if (isset($_SESSION["delete_key"])){ unset($_SESSION["delete_key"]); }
			if (isset($_SESSION["games_token"])){ unset($_SESSION["games_token"]); }
			if (isset($_SESSION["rooms_token"])){ unset($_SESSION["rooms_token"]); }
		}
		
		header('location: login.php');
	
	else:
	
		header('location: login.php');
	
	endif;
?>