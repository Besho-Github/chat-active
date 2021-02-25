<?php
	/*
		ReadyChat 2.2.0
		Command Processing
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"])){ echo 4; exit(); }
	if (!isset($_POST["token"])){ die("Invalid params."); }

	if ($_POST["token"] == md5($_SESSION["admin_key"]))
	{
		if ($user["rank"] >= 2)
		{
			// query room
			$query_room = $mysqli->query("SELECT room_id, room_json, room_password FROM `rooms_permanent` WHERE `room_id` = '{$user["user_room"]}'");
			if ($query_room->num_rows > 0)
			{
				$room = $query_room->fetch_array(MYSQLI_BOTH);
				if ($_POST["cmd"] == "/prune")
				{
					$GLOBALS["mysqli"]->query("DELETE FROM `chat_messages` WHERE `chat_room` = '{$room["room_json"]}'");
					
					echo 20;
				}
				elseif (strstr($_POST["cmd"], "/announce"))
				{
					if ($user["rank"] == 3)
					{
						// Find message to announce
						$announce = $mysqli->real_escape_string(str_replace('/announce ', '', $_POST["cmd"]));
						$announce = explode(' ', $announce, 1);
						
						// Select content
						$message = $announce[0];
						
						if ($message != null)
						{
							//
							// Send announcement to room
							$GLOBALS["mysqli"]->query("INSERT INTO `chat_messages` (chat_room, chat_user, chat_text, chat_time)
																			VALUES ('ALL', 'N/A_', '{$message}', '{$GLOBALS["config"]["micro"]}')");
							
							echo 24;
						}
						else
						{
							echo 2;
						}
					}
					else
					{
						echo 4;
					}
				}
				elseif (strstr($_POST["cmd"], "/warn"))
				{
					// Find user to warn
					$warn = $mysqli->real_escape_string(str_replace('/warn ', '', $_POST["cmd"]));
					$warn = explode(' ', $warn, 2);
					
					// Select content
					$username = $warn[0];
					$message = $warn[1];
					
					if ($warn != null && $message != null)
					{
						// Select user from the database
						$select_user = $mysqli->query("SELECT user_name, rank, active FROM `users` WHERE `user_name` = '{$username}'");
						if ($select_user->num_rows > 0)
						{
							$array = $select_user->fetch_array(MYSQLI_BOTH);
							
							if ($array["rank"] >= $user["rank"] || $array["active"] == 0)
							{
								echo 2;
							}
							else
							{
								// Update the user's information to "warned"
								$mysqli->query("UPDATE `users` SET `warned` = '1', `warning_text` = '{$message}' WHERE `user_name` = '{$username}'");
								
								echo 23;
							}
						}

						$select_guest = $mysqli->query("SELECT guest_name, active FROM `guests` WHERE `guest_name` = '{$username}'");
						if ($select_guest->num_rows > 0)
						{
							$array = $select_guest->fetch_array(MYSQLI_BOTH);
							if ($array["active"] == 0)
							{
								echo 2;
							}
							else
							{
								// Update the user's information to "warned"
								$mysqli->query("UPDATE `guests` SET `warned` = '1', `warning_text` = '{$message}' WHERE `guest_name` = '{$username}'");
								
								echo 23;
							}
						}
						
						if ($select_user->num_rows < 1 && $select_guest->num_rows < 1)
						{
							// Results were empty
							echo 2;
						}
					}
				}
				elseif (strstr($_POST["cmd"], "/kick"))
				{
					// Find user to kick
					$kick = $mysqli->real_escape_string(str_replace('/kick ', '', $_POST["cmd"]));
					$kick = explode(' ', $kick, 2);
					
					// Select content
					$username = $kick[0];
					
					if (isset($kick[1]))
					{
						$message = $kick[1];
					}
					
					if ($kick != null)
					{
						// Select user from the database
						$select_user = $mysqli->query("SELECT user_name, rank, active FROM `users` WHERE `user_name` = '{$username}'");
						if ($select_user->num_rows > 0)
						{
							$array = $select_user->fetch_array(MYSQLI_BOTH);
							if ($array["rank"] >= $user["rank"] || $array["active"] == 0)
							{
								echo 2;
							}
							else
							{
								// Update the user's information to "kicked"
								$mysqli->query("UPDATE `users` SET `kicked` = '1', `active` = '0' WHERE `user_name` = '{$username}'");
								
								echo 21;
							}
						}
						
						$select_guest = $mysqli->query("SELECT guest_name, active FROM `guests` WHERE `guest_name` = '{$username}'");
						if ($select_guest->num_rows > 0)
						{
							$array = $select_guest->fetch_array(MYSQLI_BOTH);
							if ($array["active"] == 0)
							{
								echo 2;
							}
							else
							{
								// Update the user's information to "kicked"
								$mysqli->query("UPDATE `guests` SET `kicked` = '1', `active` = '0' WHERE `guest_name` = '{$username}'");
								
								echo 21;
							}
						}
						
						if ($select_user->num_rows < 1 && $select_guest->num_rows < 1)
						{
							// Results were empty
							echo 2;
						}
					}
				}
				elseif (strstr($_POST["cmd"], "/ban"))
				{
					// Find user to ban
					$ban = $mysqli->real_escape_string(str_replace('/ban ', '', $_POST["cmd"]));
					$ban = explode(' ', $ban, 2);
					
					// Select content
					$username = $ban[0];
					$message = $ban[1];
					
					if ($ban != null)
					{
						// Select user from the database
						$select_user = $mysqli->query("SELECT user_name, rank, active FROM `users` WHERE `user_name` = '{$username}'");
						if ($select_user->num_rows > 0)
						{
							$array = $select_user->fetch_array(MYSQLI_BOTH);
							if ($array["rank"] >= $user["rank"] || $array["active"] == 0)
							{
								echo 4;
							}
							else
							{
								// Update the user's information to "banned"
								$mysqli->query("UPDATE `users` SET `banned` = '1', `active` = '0' WHERE `user_name` = '{$username}'");
								
								echo 22;
							}
						}
						
						$select_guest = $mysqli->query("SELECT guest_name, active, guest_ip FROM `guests` WHERE `guest_name` = '{$username}'");
						if ($select_guest->num_rows > 0)
						{
							$array = $select_guest->fetch_array(MYSQLI_BOTH);
							if ($array["active"] == 0)
							{
								echo 4;
							}
							else
							{
								// Update the user's information to "banned"
								$mysqli->query("UPDATE `guests` SET `banned` = '1', `active` = '0' WHERE `guest_name` = '{$username}'");
								
								// We'll also blacklist the guest's IP address to prevent them from returning with a new nick
								$mysqli->query("INSERT INTO `blacklist` (blacklist_ip) VALUES ('{$array["guest_ip"]}')");
								
								echo 22;
							}
						}
						
						if ($select_user->num_rows < 1 && $select_guest->num_rows < 1)
						{
							// Results were empty
							echo 2;
						}
					}
				}
				else
				{
					echo 2;
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
		echo 4;
	}
	
?>