<?php
	/*
		ReadyChat 2.2.0
		Private Chat
	*/
	 
    require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	if ($uSession == null){ die("Please login to access this feature."); }
	
	if (!$settings["private_messages"]){ die("Private messaging has been disabled by the site administrator."); }
	
	if (!isset($_GET["cid"]))
	{
		die("Unable to initialise the private chat window.");
	}
	
	if ($_GET["cid"] == $GLOBALS["user"]["user_id"]){ die("You cannot initiate a private chat session with yourself."); }
	
	/*
		Fetch Partner Information
	*/
	
	$chat_to = rcClean($_GET["cid"]);
	$query_user = $GLOBALS["mysqli"]->query("SELECT user_id, user_name, active, profile_age, profile_sex FROM `users` WHERE `user_id` = '{$chat_to}'");
	if ($query_user->num_rows > 0)
	{
		$chat_partner = $query_user->fetch_assoc();
		
		/*
			Post security key
		*/
		
		if (isset($_SESSION["post_key"]))
		{
			$post_key = md5($_SESSION["post_key"]);
		}
		else
		{
			$post_key = "invalid";
		}
		
		/*
			Chat partner's name
		*/
		
		$who = $chat_partner["user_name"];
		
		/*
			Check if partner is online
		*/
		
		if ($chat_partner["active"])
		{
			$online = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/online.png\" title=\"{$who} is online\" style=\"float:left; margin-right:5px;\">";
		}
		else
		{
			$online = null;
		}
		
		/* 
			Partner Gender
		*/
		
		switch($chat_partner["profile_sex"])
		{
			case 1:
			{
				$gender = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/male.png\" title=\"{$who} is male\" style=\"float:left; margin-right:5px;\">";
				break;
			}
			case 2:
			{
				$gender = "<img src=\"template/{$GLOBALS["settings"]["template"]}/icons/female.png\" title=\"{$who} is female\" style=\"float:left; margin-right:5px;\">";
				break;
			}	
			default:
			{
				$gender = null;
				break;
			}
		}
		
		$info = "<span id=\"online\">{$online} {$gender}</span>";
	
		
		/*
			Show private chat page
		*/
		
		include("template/{$GLOBALS["settings"]["template"]}/html/privatechat.html.php");
	}
	else
	{
		echo "Error initialising the chat session.";
	}
?> 