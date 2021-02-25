<?php
	/*
		ReadyChat 2.2.0
		Send Chat Messages
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"]) && !isset($_SESSION["readyChatGuest"])){ header('location: login.php'); }
	if (!isset($_POST["token"])){ die("Invalid params."); }

	if ($_POST["token"] == md5($_SESSION["post_key"]))
	{
		if ($GLOBALS["gSession"] && !$GLOBALS["settings"]["guest_chat"])
		{
			die("2");
		}
		
		if (isset($_POST['content']))
		{
			if (trim(htmlspecialchars(strip_tags($_POST["content"]))) == null || trim($_POST["room"]) == null)
			{
				echo 2;
			}
			else
			{
				if ($GLOBALS["gSession"])
				{
					if ($GLOBALS["guest"]["last_msg"] + $GLOBALS["settings"]["spam"] < time() || $GLOBALS["guest"]["last_msg"] == 0)
					{
						$query_room = $GLOBALS["mysqli"]->query("SELECT room_id, room_json, room_password FROM `rooms_permanent` WHERE `room_id` = '{$GLOBALS["guest"]["guest_room"]}'");
						
						if ($query_room->num_rows > 0)
						{
							$room = $query_room->fetch_assoc();
							
							$GLOBALS["mysqli"]->query("UPDATE `guests` SET `last_active` = '{$GLOBALS["config"]["time"]}', `last_msg` = '{$GLOBALS["config"]["time"]}', `active` = '1' WHERE `guest_name` = '{$GLOBALS["guest"]["guest_name"]}'");
							
							if (isset($_POST["timestamp"]) && is_numeric($_POST["timestamp"]))
							{
								$date = rcClean($_POST["timestamp"]);
							}
							else
							{
								$date = date("H:i", $GLOBALS["config"]["time"]);
							}
							
							$content = htmlspecialchars(strip_tags(rcClean($_POST["content"])));
							$strip_content = substr($content, 0, $settings["max_message"]);
							$room = $room["room_json"];
							
							$GLOBALS["mysqli"]->query("INSERT INTO `chat_messages` (chat_room, chat_user, chat_text, chat_time, chat_guest)
																			VALUES ('{$room}', '{$GLOBALS["guest"]["guest_name"]}', '{$strip_content}', '{$GLOBALS["config"]["micro"]}', '1')");
						}
					}
					else
					{
						echo 3;
					}				
				}
				else
				{
					if ($GLOBALS["user"]["last_msg"] + $GLOBALS["settings"]["spam"] < time() || $GLOBALS["user"]["rank"] > 1 && $GLOBALS["settings"]["spam_exempt"] == 1 || $GLOBALS["user"]["last_msg"] == 0)
					{
						$query_room = $GLOBALS["mysqli"]->query("SELECT room_id, room_json, room_password FROM `rooms_permanent` WHERE `room_id` = '{$GLOBALS["user"]["user_room"]}'");
						
						if ($query_room->num_rows > 0)
						{
							$room = $query_room->fetch_assoc();
							
							echo 1;

							$GLOBALS["mysqli"]->query("UPDATE `users` SET `last_active` = '{$GLOBALS["config"]["time"]}', `last_msg` = '{$GLOBALS["config"]["time"]}', `active` = '1' WHERE `user_name` = '{$GLOBALS["user"]["user_name"]}'");
							
							$date = date("H:i", $GLOBALS["config"]["time"]);
							$content = htmlspecialchars(strip_tags(rcClean($_POST["content"])));
							$strip_content = substr($content, 0, $settings["max_message"]);
							$room = $room["room_json"];
							
							$GLOBALS["mysqli"]->query("INSERT INTO `chat_messages` (chat_room, chat_user, chat_text, chat_time, chat_guest)
																			VALUES ('{$room}', '{$GLOBALS["user"]["user_name"]}', '{$strip_content}', '{$GLOBALS["config"]["micro"]}', '0')");
						}
					}
					else
					{
						echo 3;
					}
				}
			}
		}
	}
?>