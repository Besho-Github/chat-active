<?php
	/*
		ReadyChat 2.2.0
		Private Chat
	*/
	 
	require_once("../core/rc/config.inc.php");
	require_once("../core/rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"]) || isset($_SESSION["readyChatUser"]) && $user["active"] == 0){ die("N/N"); }
	if (!isset($_GET["cid"])){ die("Invalid params."); }
	
	$cid = trim($mysqli->real_escape_string($_GET["cid"]));
	
	$mysqli->query("UPDATE `private_chats` SET `im_status` = '2' WHERE `im_to` = '{$user["user_id"]}' AND `im_from` = '{$cid}'");
	
	$query_chat = $mysqli->query("SELECT * FROM `private_chats` WHERE `im_from` = '{$user["user_id"]}' AND `im_to` = '{$cid}' OR `im_to` = '{$user["user_id"]}' AND `im_from` = '{$cid}' ORDER BY `im_time` ASC LIMIT 50");
	if ($query_chat->num_rows > 0)
	{
		$lastpoll = $GLOBALS["user"]["private_poll"];
		$GLOBALS["mysqli"]->query("UPDATE `users` SET `private_poll` = '{$GLOBALS["config"]["micro"]}' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
		
		$query_chats = $GLOBALS["mysqli"]->query("SELECT * FROM `private_chats` WHERE `im_to` = '{$GLOBALS["user"]["user_id"]}' AND `im_from` = '{$cid}' AND `im_time` > {$lastpoll} ORDER BY `im_time` ASC LIMIT 50");
		
		if ($query_chats->num_rows > 0)
		{	
			while ($chat = $query_chats->fetch_array(MYSQLI_BOTH))
			{
				$message = linkable($chat["im_msg"]);
				$date = date("H:i", $chat["im_time"] / 1000);
				$who = mysqli_fetch_array($GLOBALS["mysqli"]->query("SELECT user_id, user_name, active FROM `users` WHERE user_id = '{$chat["im_from"]}'"));
				
				if ($who["active"] == 0)
				{
					$active = "<span style=\"color:#A4A4A4;\">(Offline)</span>";
				}
				else
				{
					$active = "";
				}

				echo "<li><small>{$date} {$who["user_name"]} {$active} </small>{$message}</li>";
				
				$GLOBALS["mysqli"]->query("UPDATE `private_chats` SET `im_status` = '2' WHERE `im_to` = '{$GLOBALS["user"]["user_id"]}' AND `im_from` = '{$cid}'");
				unset($message, $date, $who, $active);
			}
		}
		else
		{
			echo "N/N";
		}
	}
	else
	{
		echo "N/N";
	}
?> 