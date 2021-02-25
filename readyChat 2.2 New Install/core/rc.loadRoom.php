<?php
	/*
		ReadyChat 2.2.0
		Load a room into the chat
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	/*
		Check if the user has the authority to access this room
	*/
	
	if (!isset($GLOBALS["override"]) && !isset($_SESSION["readyChatUser"]) || isset($_SESSION["readyChatUser"]) && $GLOBALS["user"]["active"] == 0)
	{ 
		exit();
	}
	
	/*
		Query the room information
	*/
	
	if ($GLOBALS["gSession"]) // Query as a guest
	{
		$query_room = $GLOBALS["mysqli"]->query("SELECT room_id, room_json, room_password FROM `rooms_permanent` WHERE `room_id` = '{$GLOBALS["guest"]["guest_room"]}'");
		$user_name = $GLOBALS["guest"]["guest_name"];
	}
	else // Query as a member
	{
		if (isset($_SESSION["readyChatUser"]))
		{
			$query_room = $GLOBALS["mysqli"]->query("SELECT room_id, room_json, room_password FROM `rooms_permanent` WHERE `room_id` = '{$GLOBALS["user"]["user_room"]}'");
			$user_name = $GLOBALS["user"]["user_name"];
		}
	}
	
	if (!isset($GLOBALS["gSession"]) && !isset($_SESSION["readyChatUser"]))
	{
		exit();
	}
	
	if ($query_room->num_rows > 0)
	{
		$room_id = $query_room->fetch_assoc();
		
		/*
			Firstly, we check if the room has a password assigned to it or not.
			We then check if the user has the `room_guard` session set. If not, they
			have not previously entered the password and therefore have no authority
			to view the room and its chat log.
		*/
		
		if ($room_id["room_password"] != null && $_SESSION["room_guard"] != $room_id["room_id"])
		{
			echo "<li class=\"announcement\">You are not authorized to view this chat.</li>";
		}
		else // User has authority to view this chat room
		{
			if (isset($_GET["logview"]))
			{
				echo "Log coming soon";
			}
			else
			{
				if (isset($_GET["lastpoll"]) && $_GET["lastpoll"] != null && is_numeric($_GET["lastpoll"]))
				{
					if (!isset($GLOBALS["gSession"]))
					{
						$lastpoll = $GLOBALS["user"]["last_poll"];
						$GLOBALS["mysqli"]->query("UPDATE `users` SET `last_poll` = '{$GLOBALS["config"]["micro"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
					}
					else
					{
						$lastpoll = $GLOBALS["guest"]["last_poll"];
						$GLOBALS["mysqli"]->query("UPDATE `guests` SET `last_poll` = '{$GLOBALS["config"]["micro"]}' WHERE `guest_id` = '{$GLOBALS["guest"]["guest_id"]}'");
					}
				
					$query_chats = $GLOBALS["mysqli"]->query("SELECT * FROM `chat_messages` WHERE (`chat_room` = '{$room_id["room_json"]}' OR `chat_room` = 'ALL') AND `chat_time` > {$lastpoll} AND `chat_user` != '{$user_name}'");
					if ($query_chats->num_rows > 0)
					{	
						while ($chat = $query_chats->fetch_array(MYSQLI_BOTH))
						{
							$hex = null;
							
							if ($chat["chat_guest"] == 1)
							{
								if ($GLOBALS["settings"]["guest_hex"] != null)
								{
									$hex = "style=\"color:{$GLOBALS["settings"]["guest_hex"]};\"";
								}
								
								$guest = "(guest) ";
							}
							else
							{
								$guest = null;
							}
							
							if ($chat["chat_guest"] == 0)
							{
								$query_user = $GLOBALS["mysqli"]->query("SELECT user_name, rank FROM `users` WHERE `user_name` = '{$chat["chat_user"]}'");
								if ($query_user->num_rows > 0)
								{
									$list = $query_user->fetch_assoc();
									
									switch($list["rank"])
									{
										case 2: // This user is a moderator
										{
											if ($GLOBALS["settings"]["mod_hex"] != null)
											{
												$hex = "style=\"color:{$GLOBALS["settings"]["mod_hex"]};\"";
											}
											
											break;
										}
										case 3: // This user is an administrator
										{
											if ($GLOBALS["settings"]["admin_hex"] != null)
											{
												$hex = "style=\"color:{$GLOBALS["settings"]["admin_hex"]};\"";
											}							
											
											break;
										}
										default: // This user has no rank
										{
											if ($GLOBALS["settings"]["member_hex"] != null)
											{
												$hex = "style=\"color:{$GLOBALS["settings"]["member_hex"]};\"";
											}			
											
											break;
										}
									}
								}
							}
							
							if ($chat["chat_room"] != "ALL")
							{
								echo "<div class=\"cm\"><div class=\"cmt\" {$hex}> {$guest}{$chat["chat_user"]}</div><span class=\"cmtm\"> " . linkable($chat["chat_text"]) . " </div></div>";
							}
							else
							{
								echo "<li class=\"announcement\"> " . linkable($chat["chat_text"]) . " </li>";
							}
						}
					}
					else
					{
						echo "N/N";
					}
				}
				else
				{
					header('location: ../index.php');
				}
			}
		}
	}
?> 