<?php
	/*
		ReadyChat 2.2.0
		Login
	*/
	
	if (file_exists("install/install.txt")){ die("<a href='./install/'>Install readyChat</a>"); }
	if (file_exists("upgrade/upgrade.txt")){ die("<a href='./upgrade/'>Upgrade readyChat</a>"); }
	 
    require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	$offline_notice = null;
	
	// blacklist checker
	$my_ip = rcClean($_SERVER['REMOTE_ADDR']);;
	$query_blacklist = $GLOBALS["mysqli"]->query("SELECT * FROM `blacklist` WHERE `blacklist_ip` = '{$my_ip}'");
	if ($query_blacklist->num_rows > 0)
	{
		header('location: banned.php');
		exit();
	}
	
	//
	// Count online users
	$query_active = $GLOBALS["mysqli"]->query("SELECT user_id, active, last_active FROM `users` WHERE `active` = '1'");
	$query_guests = $GLOBALS["mysqli"]->query("SELECT guest_id, active, last_active FROM `guests` WHERE `active` = '1'");
	
	if ($query_active->num_rows > 0)
	{
		$currently_online = 0;
		while($list = $query_active->fetch_assoc())
		{
			$max_time = $list["last_active"] + $settings["idle_kick"] * 100;
		
			// Remove inactive users
			if (time() > $max_time)
			{
				$GLOBALS["mysqli"]->query("UPDATE `users` SET `active` = '0' WHERE `user_id` = '{$list["user_id"]}'");
			}
			else
			{
				$currently_online++;
			}
		}
	}
	else
	{
		$currently_online = 0;
	}
	
	// remove guests
	$GLOBALS["mysqli"]->query("DELETE FROM `guests` WHERE `active` = '0'");
	
	if ($query_guests->num_rows > 0)
	{
		$g_currently_online = 0;
		while($glist = $query_guests->fetch_assoc())
		{
			$max_time = $glist["last_active"] + $settings["idle_kick"] * 100;
		
			// Remove inactive users
			if (time() > $max_time)
			{
				$GLOBALS["mysqli"]->query("DELETE FROM `guests` WHERE `guest_id` = '{$glist["guest_id"]}'");
			}
			else
			{
				$g_currently_online++;
			}
		}
	}
	else
	{
		$g_currently_online = 0;
	}
	
	$total_online = $currently_online + $g_currently_online;
	
	if (isset($total_online) && $total_online == 1)
	{
		$show_online = "There is currently 1 user online.";
	}
	elseif (isset($total_online) && $total_online > 1)
	{
		$show_online = "There are currently {$total_online} users online.";
	}
	else
	{
		$show_online = "There are no users online.";
	}
	
	//
	// Logged in checker
	if ($uSession != null): header('location: index.php'); else:
	
	//
	// Request login

	if (isset($_GET["action"]) && $_GET["action"] == "login")
	{
		if (isset($_POST["guest"]))
		{
			$guestMode = rcClean(trim($_POST["guest"]));
		}
		else
		{
			$guestMode = 0;
		}
		
		$username = rcClean(trim($_POST["username"]));
		
		if (!$guestMode)
		{
			$password = rcClean(trim($_POST["password"]));
		}
		else
		{
			$password = "guest";
		}
		
		if (!isset($password) && $guestMode != 1 || $username == null)
		{
			header('location: login.php?e=1');
		}
		else
		{
			if (strlen($username) <= 10)
			{
				if ($guestMode && !$settings["offline_mode"] && $settings["allow_guests"])
				{	
					$query_guests = $GLOBALS["mysqli"]->query("SELECT * FROM `guests` WHERE `guest_name` = '{$username}'");
					if ($query_guests->num_rows > 0)
					{
						header('location: login.php?e=6');
					}
					else
					{
						// ensure username consists of letters and numbers only
						if (preg_match("/^[a-zA-Z0-9]+$/", $username))
						{
							// login as guest
							$_SESSION["readyChatGuest"] = $username;
							$_SESSION["room_guard"] = 1;
							$GLOBALS["mysqli"]->query("INSERT INTO `guests` (guest_name, guest_room, last_active, guest_ip, last_poll) VALUES ('{$username}', '1', '{$GLOBALS["config"]["time"]}', '{$my_ip}', '{$config["micro"]}')") or die($GLOBALS["mysqli"]->error);
							header('location: index.php');
						}
						else
						{
							header('location: login.php?e=7');
						}
					}
				}
				else
				{
					$query_users = $GLOBALS["mysqli"]->query("SELECT * FROM `users` WHERE `user_name` = '{$username}'");
					if ($query_users->num_rows < 1)
					{
						header('location: login.php?e=2');
					}
					else
					{
						$array = $query_users->fetch_array(MYSQLI_BOTH);
						if ($array["user_password"] != sha1(str_rot13($password . $keys["enc_1"])))
						{
							header('location: login.php?e=3');
						}	
						else
						{
							if ($settings["offline_mode"] && $array["rank"] < 2 || $settings["offline_mode"] && !$array["apanel"])
							{
								header('location: login.php?e=5');
							}
							else
							{
								if ($array["banned"] == 1)
								{
									header('location: login.php?e=4');
								}
								else
								{
									$_SESSION["readyChatUser"] = $username;
									$_SESSION["room_guard"] = 1;
									$GLOBALS["mysqli"]->query("UPDATE `users` SET `kicked` = '0', `active` = '1', `last_active` = '{$GLOBALS["config"]["time"]}', `last_poll` = '{$GLOBALS["config"]["micro"]}', `user_room` = '1' WHERE `user_name` = '{$username}'") or die($GLOBALS["mysqli"]->error);
									header('location: index.php');
								}
							}
						}
					}
				}
			}
			else
			{
				header('location: login.php?e=8');
			}
		}
	}
	else
	{
		if ($settings["offline_mode"])
		{
			$offline_notice = "<div id=\"offline\">{$settings["offline_message"]}</div>";
		}
		else
		{
			$offline_notice = "";
		}
	}
	
	/*
		Include Login Template
	*/
	
	include("template/{$GLOBALS["settings"]["template"]}/html/login.html.php");
	
	endif;
?>