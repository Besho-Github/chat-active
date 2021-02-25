<?php
	/*
		ReadyChat 2.2.0
		Register
	*/
	 
    require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	/*
		Query site blacklist
	*/
	
	$my_ip = rcClean($_SERVER['REMOTE_ADDR']);
	$query_blacklist = $GLOBALS["mysqli"]->query("SELECT * FROM `blacklist` WHERE `blacklist_ip` = '{$my_ip}'");
	if ($query_blacklist->num_rows > 0)
	{
		header('location: banned.php');
		exit();
	}
	
	if ($uSession != null) 
	{
		header('location: index.php');
	}
	else
	{
		if (isset($_GET["action"]) && $_GET["action"] == "register" && !$settings["offline_mode"] && $settings["can_register"])
		{
		
			if (isset($_POST["username"]) 
				&& $_POST["username"] != null 
				&& isset($_POST["password"]) && $_POST["password"] != null 
				&& isset($_POST["email"]) && $_POST["email"] != null 
				&& isset($_POST["terms"]) && $_POST["terms"] != null
				&& isset($_POST["regid"]) && $_POST["regid"] != null)
			{
				if($_POST["regid"] == $_SESSION["regid"])
				{
					$username = trim(rcClean($_POST["username"]));
					$password = trim(rcClean($_POST["password"]));
					$email = trim(rcClean($_POST["email"]));

					$query_users = $GLOBALS["mysqli"]->query("SELECT `user_name` FROM `users` WHERE `user_name` = '{$username}'");
					if ($query_users->num_rows < 1)
					{
						$query_email = $GLOBALS["mysqli"]->query("SELECT `user_email` FROM `users` WHERE `user_email` = '{$email}'");
						if ($query_email->num_rows < 1)
						{
							if (filter_var($email, FILTER_VALIDATE_EMAIL))
							{
								if (preg_match("/^[a-zA-Z0-9]+$/", $username))
								{
									if (strlen($username) <= 10)
									{
										$epw = sha1(str_rot13($password . $keys["enc_1"]));
										$ip = rcClean($_SERVER['REMOTE_ADDR']);
										
										$GLOBALS["mysqli"]->query("INSERT INTO `users` (user_name, user_password, user_email, user_ip, user_room, user_joined, last_poll) 
																	 VALUES ('{$username}', '{$epw}', '{$email}', '{$ip}', '1', '{$time}', '{$config["micro"]}')") or die($GLOBALS["mysqli"]->error);
																	 
										unset($_SESSION["regid"]);
										$_SESSION["readyChatUser"] = $username;
										$GLOBALS["mysqli"]->query("UPDATE `users` SET `kicked` = '0', `active` = '1', `last_active` = '{$time}', `room` = 'lobby.html' WHERE `user_name` = '{$username}'");
										
										header('location: index.php');
									}
									else
									{
										header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=7');
									}
								}
								else
								{
									unset($_SESSION["regid"]);
									header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=5');
								}
							}
							else
							{
								unset($_SESSION["regid"]);
								header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=4');
							}
						}
						else
						{
							unset($_SESSION["regid"]);
							header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=6');						
						}
					}
					else
					{
						unset($_SESSION["regid"]);
						header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=3');
					}
				}
				else
				{
					unset($_SESSION["regid"]);
					header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=2');
				}
			}
			else
			{
				header('location: register.php?u=' . $_POST["username"] . '&e=' . $_POST["email"] . '&n=1');
			}
		}
		else
		{
			if (isset($_GET["u"]) && $_GET["u"] != null)
			{
				$username = rcClean(substr($_GET["u"], 0, 30));
			}
			else
			{
				$username = "";
			}

			if (isset($_GET["e"]) && $_GET["e"] != null && filter_var($_GET["e"], FILTER_VALIDATE_EMAIL))
			{
				$email = rcClean(substr($_GET["e"], 0, 100));
			}
			else
			{
				$email = "";
			}
			
			if ($settings["offline_mode"])
			{
				$offline_notice = "<div id=\"offline\">{$settings["offline_message"]}</div>";
				$field_off = "DISABLED";
			}
			elseif (!$settings["can_register"])
			{
				$offline_notice = "";
				$field_off = "DISABLED";
			}
			else
			{
				$offline_notice = "";
				$field_off = "";
			}
			
			include("template/{$GLOBALS["settings"]["template"]}/html/register.html.php");
		}
	}

?>