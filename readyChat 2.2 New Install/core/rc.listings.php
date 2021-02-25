<?php
	/*
		ReadyChat 2.2.0
		Listing for various features
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($GLOBALS["override"]) && !isset($_SESSION["readyChatUser"]) || !isset($_SESSION["readyChatUser"]) && !isset($_SESSION["readyChatGuest"])){ die("You can't do that right now."); }
	 
	if (isset($_GET["list"]))
	{
		switch($_GET["list"])
		{
			/*
				Count unread private messages
			*/
			
			case "pms":
			{
				if ($GLOBALS["settings"]["private_messages"])
				{
					$query_pms = $GLOBALS["mysqli"]->query("SELECT im_to, im_status FROM `private_chats` WHERE `im_to` = '{$GLOBALS["user"]["user_id"]}' AND `im_status` = '1'");
					echo "<a href=\"#privateList\" id=\"pvtlist\">Private Messages</a> (<span id=\"new_count\">{$query_pms->num_rows}</span> new)";
				}
				else
				{
					echo "This feature is unavailable.";
				}
				
				break;
			}
		
			/*
				Check for new private messages
			*/
			
			case "newpms":
			{
				$query_pms = $GLOBALS["mysqli"]->query("SELECT DISTINCT im_to, im_status, im_from FROM `private_chats` WHERE `im_to` = '{$GLOBALS["user"]["user_id"]}' AND `im_status` = '1'");
				if ($query_pms->num_rows > 0)
				{
					while($pm = $query_pms->fetch_array(MYSQLI_BOTH))
					{
						$pm_from = mysqli_fetch_array($mysqli->query("SELECT user_id, user_name, active, profile_avatar FROM `users` WHERE user_id = '{$pm["im_from"]}'"));
						
						echo "
						<div class=\"pm\">
							<div class=\"pm_avatar\"><img src=\"template/avatars/{$pm_from["profile_avatar"]}\"></div>
							<div class=\"pm_title\">
								Conversation with <strong>{$pm_from["user_name"]}</strong>
							</div>
							<div class=\"pm_link\">
								<div class=\"pm_go\"><a id=\"private\" href=\"#private\" onclick=\"url('Private Chat', 'private_chat.php?cid={$pm["im_from"]}&who={$pm_from["user_name"]}', '600', '460')\">Open Chat</a></div>						
							</div>
						</div>";
						
						unset($pm_from);
					}
				}
				else
				{
					echo "There are no unread private messages.";
				}
				
				break;
			}
			
			/*
				Update the active user list
			*/
			
			case "active":
			{
				activeUsers();
				
				break;
			}
			
			/*
				Update the rooms list & active user count
			*/
		
			case "rooms":
			{
				listRooms();
				
				break;
			}
			
			/*
				Update the room topic
			*/
		
			case "topic":
			{
				$room_id = trim(rcClean($_GET["rid"]));
				$query_room = $GLOBALS["mysqli"]->query("SELECT room_json, room_desc FROM `rooms_permanent` WHERE `room_id` = '{$room_id}'");
				
				if ($query_room->num_rows > 0)
				{
					$room = $query_room->fetch_assoc();
					
					if ($room["room_desc"] != null)
					{
						echo $room["room_desc"];
					}
					else
					{
						echo "No topic.";
					}
				}
				else
				{
					echo "No topic.";
				}
				
				break;
			}
			
			/*
				Update the room background
			*/
			
			case "background":
			{
				$room_id = trim(rcClean($_GET["rid"]));
				$query_room = $GLOBALS["mysqli"]->query("SELECT room_id, room_json, room_desc, room_background FROM `rooms_permanent` WHERE `room_id` = '{$room_id}'");
				
				if ($query_room->num_rows > 0)
				{
					$room = $query_room->fetch_assoc();
					
					if (isset($room["room_background"]) && $room["room_background"] != null)
					{
						echo $room["room_background"];
					}
					else
					{
						echo "nobg";
					}
				}
				else
				{
					echo "No topic.";
				}
				
				break;
			}
		}
	}
	
?>