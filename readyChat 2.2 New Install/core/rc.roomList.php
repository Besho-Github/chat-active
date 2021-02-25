<?php
	/*
		ReadyChat 2.2.0
		List chat rooms
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!$GLOBALS["override"] && !isset($_SESSION["readyChatUser"]) || isset($_SESSION["readyChatUser"]) && $user["active"] == 0)
	{ 
		echo "<div class=\"room\">No rooms available.</div>";
		exit();
	}
	
	/*
		Query the chat rooms
	*/
	
	$query_rooms = $GLOBALS["mysqli"]->query("SELECT * FROM `rooms_permanent`");
	
	if ($query_rooms->num_rows > 0)
	{
		while ($room = $query_rooms->fetch_array(MYSQLI_BOTH))
		{
			if (isset($room["room_icon"]) && $room["room_icon"] != null)
			{
				$icon = "<img src=\"{$room["room_icon"]}\">";
			}
			else
			{
				$icon = "";
			}
			
			if (isset($room["room_password"]) && $room["room_password"] != null)
			{
				$lock = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/password.png\" width=\"12px\" title=\"Password Required\">";
			}
			else
			{
				$lock = "";
			}
			
			/*
				Count how many users/guests are in the room
			*/
			
			$count = $GLOBALS["mysqli"]->query("SELECT user_id, active, user_room FROM `users` WHERE `active` = '1' AND `user_room` = '{$room["room_id"]}'");
			$count_g = $GLOBALS["mysqli"]->query("SELECT guest_id, active, guest_room FROM `guests` WHERE `active` = '1' AND `guest_room` = '{$room["room_id"]}'");
			$total_count = $count->num_rows + $count_g->num_rows;
			
			/*
				Create the room listing and display it on the list
			*/
			
			$goto = preg_replace( '/\.[a-z0-9]+$/i' , '', $room["room_json"]);
			echo "
			<div class=\"room\" room_title=\"{$room["room_title"]}\" room_link=\"{$room["room_id"]}\">
				{$icon} {$room["room_title"]} 
				<div class=\"room_count\"> {$lock} {$total_count}/{$room["room_limit"]}</div>
			</div>";
			
			unset($icon, $lock);
		}	
	}	
	else
	{
		echo "<div class=\"room\">No rooms available.</div>";
	}	
	
?> 