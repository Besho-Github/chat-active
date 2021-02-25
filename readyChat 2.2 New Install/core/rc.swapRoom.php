<?php
	/*
		ReadyChat 2.2.0
		Swap Room
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"]) && !isset($_SESSION["readyChatGuest"])){ die("You can't do that right now."); }
	
	if (isset($_POST['nid']))
	{
		if (trim($_POST["nid"]) == null)
		{
			echo 2;
		}
		else
		{
			$nid = trim(rcClean($_POST["nid"]));
			$select_room = $GLOBALS["mysqli"]->query("SELECT * FROM `rooms_permanent` WHERE `room_id` = '{$nid}'");
			
			if ($select_room->num_rows > 0)
			{
				$room = $select_room->fetch_assoc();
				
				if ($gSession && !$room["guest_access"])
				{
					echo 2;
				}
				else
				{
					$count_users = $mysqli->query("SELECT user_room, active FROM `users` WHERE `user_room` = '{$room["room_json"]}' AND `active` = '1'");
					
					if ($count_users->num_rows < $room["room_limit"] || $settings["full_exempt"] == 1 && !$gSession && $GLOBALS["user"]["rank"] >= 2)
					{
						/*
							Room Requires A Password
						*/
						
						if ($room["room_password"] != null)
						{
							if (isset($_POST["key"]))
							{
								$key = trim($mysqli->real_escape_string($_POST["key"]));
								if ($key == $room["room_password"])
								{
									if ($gSession)
									{
										$mysqli->query("UPDATE `guests` SET `guest_room` = '{$room["room_id"]}', `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'");
									}
									else
									{
										$mysqli->query("UPDATE `users` SET `user_room` = '{$room["room_id"]}', `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
									}
									
									$_SESSION["room_guard"] = $room["room_id"];
									echo $room["room_json"];
								}
								else
								{
									if ($_POST["key"] == null)
									{
										echo 4;
									}
									else
									{
										echo 5;
									}
								}
							}
							else
							{
								echo 4;
							}
						}
						else
						{
							if ($gSession)
							{
								$mysqli->query("UPDATE `guests` SET `guest_room` = '{$room["room_id"]}', `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `guest_id` = '{$guest["guest_id"]}'");
							}
							else
							{
								$mysqli->query("UPDATE `users` SET `user_room` = '{$room["room_id"]}', `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}' WHERE `user_id` = '{$user["user_id"]}'");
							}
							
							echo $room["room_json"];
						}
					}
					else
					{	
						echo 3;
					}
				}
			}
			else
			{
				echo 2;
			}
		}
	}
?>