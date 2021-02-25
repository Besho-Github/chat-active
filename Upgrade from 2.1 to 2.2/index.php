<?php
	/*
		ReadyChat 2.2.0
		Index Configuration
	*/
	
	// Required Files
	require("core/rc/config.inc.php");
	require("core/rc/functions.inc.php");
	
	// Check if the user is logged in or not
	if ($uSession == null && $gSession == null) 
	{
		header('location: login.php');
		exit();
	}
	
	// Update the user's last known active time
	if ($GLOBALS["gSession"])
	{
		$mysqli->query("UPDATE `guests` SET `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `guest_name` = '{$GLOBALS["guest"]["guest_name"]}'");
	}
	else
	{
		$mysqli->query("UPDATE `users` SET `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `user_name` = '{$GLOBALS["user"]["user_name"]}'");
	}
	
	// Query chat rooms and place user inside one
	if (!$gSession)
	{
		$query_uroom = $mysqli->query("SELECT * FROM `rooms_permanent` WHERE `room_id` = '{$mysqli->real_escape_string($GLOBALS["user"]["user_room"])}'");
		if ($query_uroom->num_rows < 1)
		{
			$mysqli->query("UPDATE `users` SET `user_room` = '{$GLOBALS["settings"]["default_room"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
			$croom = $GLOBALS["settings"]["default_room"];
		}
		else
		{
			$room = $query_uroom->fetch_array(MYSQLI_BOTH);
			
			$count_users = $mysqli->query("SELECT user_room, active FROM `users` WHERE `user_room` = '{$room["room_limit"]}'");
			if ($count_users->num_rows < $room["room_limit"] || $settings["full_exempt"] == 1 && $user["rank"] >= 2)
			{
				$croom = $user["user_room"];
			}
			else
			{
				$mysqli->query("UPDATE `users` SET `user_room` = '{$GLOBALS["settings"]["default_room"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
				$croom = $GLOBALS["settings"]["default_room"];		
			}
		}
	}
	else
	{
		$croom = $GLOBALS["guest"]["guest_room"];
	}

    /*
        Token verification to prevent CSRF
    */

    if (!isset($_SESSION["post_key"]))
    {
        $_SESSION["post_key"] = sha1(rand(323, 4000));
    }

    if (!$gSession)
    {
        if (!isset($_SESSION["admin_key"]) && $user["rank"] > 1)
        {
            $_SESSION["admin_key"] = sha1(rand(323, 4000));
        }
    }

    if (isset($_SESSION["post_key"]))
    {
        $post_key = md5($_SESSION["post_key"]);
    }
    else
    {
        $post_key = "invalid";
    }
	
	/*
		Load the chat room(s)
	*/

    require("template/{$settings["template"]}/html/index.html.php");
	
?>
