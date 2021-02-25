<?php
	/*
		ReadyChat 2.2.0
		Notifications
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"]) && !isset($_SESSION["readyChatGuest"])){ die("You can't do that right now."); }

	if ($GLOBALS["gSession"])
	{
		if ($GLOBALS["guest"]["kicked"] == 1)
		{
			$mysqli->query("DELETE FROM `guests` WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'") or die($mysqli->error);

			if (isset($_SESSION["room_guard"])){ unset($_SESSION["room_guard"]); }
			if (isset($_SESSION["readyChatGuest"])){ unset($_SESSION["readyChatGuest"], $_SESSION["post_key"]); }
			echo 1;
		}
		if ($GLOBALS["guest"]["banned"] == 1)
		{
			$mysqli->query("DELETE FROM `guests` WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'") or die($mysqli->error);

			if (isset($_SESSION["room_guard"])){ unset($_SESSION["room_guard"]); }
			if (isset($_SESSION["readyChatGuest"])){ unset($_SESSION["readyChatGuest"], $_SESSION["post_key"]); }
			echo 2;
		}
		elseif ($GLOBALS["guest"]["warned"] == 1)
		{
			$mysqli->query("UPDATE `guests` SET `warned` = '0' WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'");
			echo 5 . "(nxt)" . $GLOBALS["guest"]["warning_text"];
		}
	}
	else
	{
		if ($GLOBALS["user"]["kicked"] == 1)
		{
			$mysqli->query("UPDATE `users` SET `active` = '0', `kicked` = '0' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			unset($_SESSION["readyChatUser"]);
			echo 1;
		}
		elseif ($GLOBALS["user"]["banned"] == 1)
		{
			$mysqli->query("UPDATE `users` SET `active` = '0' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			unset($_SESSION["readyChatUser"]);
			echo 2;
		}
		elseif (!$GLOBALS["user"]["active"] && !$GLOBALS["user"]["reset"])
		{
			echo 3;
		}
		elseif ($GLOBALS["user"]["reset"] == 1)
		{
			$mysqli->query("UPDATE `users` SET `reset` = '0', `active` = '0', `user_room` = '{$GLOBALS["settings"]["default_room"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			echo 4;
		}
		elseif ($GLOBALS["user"]["warned"] == 1)
		{
			$mysqli->query("UPDATE `users` SET `warned` = '0' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			echo 5 . "(nxt)" . $GLOBALS["user"]["warning_text"];
		} 
	}
	
?>